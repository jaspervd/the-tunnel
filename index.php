<?php
session_start();
ini_set('display_errors', true);
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('WWW_ROOT', __DIR__ . DS);

require 'vendor/autoload.php';
require 'dao/AdminRolesDAO.php';
require 'dao/LikesDAO.php';
require 'dao/GroupsDAO.php';
require 'dao/CreationsDAO.php';
require 'dao/UsersDAO.php';
require 'dao/ScoresDAO.php';
require 'dao/UserGroupsDAO.php';

$configuration = ['settings' => ['displayErrorDetails' => true]];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

//$app = new \Slim\App;

$app->get('/', function($request, $response, $args) {
  $view = new \Slim\Views\PhpRenderer('view/');
  $basePath = $request->getUri()->getBasePath();
  return $view->render($response, 'home.php', ['basePath' => $basePath]);
});

// USERS

$app->post('/api/auth', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $adminRolesDAO = new AdminRolesDAO();
  if(!authenticated()) {
    $post = $request->getParsedBody();
    $user = $usersDAO->selectByInputAndPassword($post['input'], $post['password']);
    if(empty($user)) {
      return $response->withStatus(403);
    } else {
      $_SESSION['tt_user'] = $user;
      $user['role'] = $adminRolesDAO->selectById($user['role_id']); // role shouldn't be passed on in session
    }
} else {
  $user = $usersDAO->selectById($_SESSION['tt_user']['id']);
  if(empty($user)) {
    return $response->withStatus(403);
  } else {
    $user['role'] = $adminRolesDAO->selectById($user['role_id']);
  }
}
return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
});

$app->post('/api/logout', function ($request, $response, $args) {
  if(authenticated()) {
    unset($_SESSION['tt_user']);
  }
  return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->get('/api/users', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $queryParams = $request->getQueryParams();
  if(isset($queryParams['group_id'])) {
    $users = $usersDAO->selectByGroupId($queryParams['group_id']);
  } else {
    $users = $usersDAO->selectAll();
  }
  for($i = 0; $i < count($users); $i++){
    unset($users[$i]['email']); // data mining and all...
  }
return $response->write(json_encode($users))->withHeader('Content-Type', 'application/json');
});



$app->post('/api/users', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $post = $request->getParsedBody();
  $errors = $usersDAO->validate($post);
  if(!empty($errors)) {
    return $response->write(json_encode($errors))->withHeader('Content-Type', 'application/json')->withStatus(422);
  }
  $user = $usersDAO->insert($post);
  if(empty($user)) {
    $response = $response->withStatus(404);
  } else {
    $_SESSION['tt_user'] = $user;
    $response = $response->withStatus(201);
  }
  return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
});

// USER

$app->get('/api/users/{id}', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $user = $usersDAO->selectById($args['id']);
  if(empty($user)) {
    $response = $response->withStatus(404);
  } else {
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
});

$app->put('/api/users/{id}', function ($request, $response, $args) {
  if(authenticated()) {
    if(!($_SESSION['tt_user']['id'] === $args['id'] || !checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_users'))) {
      return $response->withStatus(403);
      exit;
    }
    $usersDAO = new UsersDAO();
    $user = $usersDAO->selectById($args['id']);
    if(!empty($user)) {

      $post = $request->getParsedBody();
      $post['id'] = $user['id'];
      $post['image_url'] = '';
      $imageMimeTypes = array('image/jpeg', 'image/png');
      if (!empty($_FILES['image']) && in_array($_FILES['image']['type'], $imageMimeTypes)) {
        $targetFile = WWW_ROOT . 'upload' . DIRECTORY_SEPARATOR . $_FILES['image']['name'];
        $pos = strrpos($targetFile, '.');
        $filename = substr($targetFile, 0, $pos);
        $ext = substr($targetFile, $pos + 1);
        $i = 0;
        while (file_exists($targetFile)) {
          $i++;
          $targetFile = $filename . $i . '.' . $ext;
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

        $post['image_url'] = str_replace(WWW_ROOT, '', $targetFile);
      }
      print_r($request->getParsedBody);
      die();
      $updatedUser = $usersDAO->update($post);
      if(empty($updatedUser)) {
        $response = $response->withStatus(404);
      } else {
        $response = $response->withStatus(200);
      }
    } else {
      $response = $response->withStatus(404);
    }
    return $response->write(json_encode($updatedUser))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }
});

$app->get('/api/users/{id}/likes', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $user = $usersDAO->selectById($args['id']);
  $likes = array();
  if(empty($user)) {
    $response = $response->withStatus(404);
  } else {
    $likesDAO = new LikesDAO();
    $likes = $likesDAO->selectByUserId($user['id']);
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($likes))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/users/{id}/groups', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $user = $usersDAO->selectById($args['id']);
  $groups = array();
  if(empty($user)) {
    $response = $response->withStatus(404);
  } else {
    $groupsDAO = new GroupsDAO();
    $groups = $groupsDAO->selectByUserId($user['id']);
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($groups))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/users/{id}/creations', function ($request, $response, $args) {
  $usersDAO = new UsersDAO();
  $user = $usersDAO->selectById($args['id']);
  $creations = array();
  if(empty($user)) {
    $response = $response->withStatus(404);
  } else {
    $creationsDAO = new CreationsDAO();
    $creations = $creationsDAO->selectByUserId($user['id']);
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($creations))->withHeader('Content-Type', 'application/json');
});

$app->patch('/api/users/{id}/hide', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_users')) {
    $usersDAO = new UsersDAO();
    $user = $usersDAO->setHidden($args['id'], 1);
    if(empty($user)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(200);
    }
    return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->patch('/api/users/{id}/role', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_roles')) {
    $usersDAO = new UsersDAO();
    $post = $request->getParsedBody();
    $user = $usersDAO->setRole($args['id'], $post['role_id']);
    if(empty($user)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(200);
    }
    return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->delete('/api/users/{id}', function ($request, $response, $args) {
  if(checkPrivilige($_SESSION['tt_user']['id'], 'can_delete_users')) {
    $usersDAO = new UsersDAO();
    $user = $usersDAO->delete($args['id']);
    return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
  }
});

// CREATIONS

$app->get('/api/creations', function ($request, $response, $args) {
  $creationsDAO = new CreationsDAO();
  $creations = $creationsDAO->selectAll();
  $queryParams = $request->getQueryParams();
  if(isset($queryParams['user_id'])) {
    $creations = $creationsDAO->selectByUserId($queryParams['user_id']);
  } elseif(isset($queryParams['group_id'])) {
    $creations = $creationsDAO->selectByGroupId($queryParams['group_id']);
  } elseif(isset($queryParams['featured'])) {
    $creations = $creationsDAO->selectByFeatured($queryParams['featured']);
  } elseif(isset($queryParams['nominated'])) {
    $creations = $creationsDAO->selectByNominated($queryParams['nominated']);
  } elseif(isset($queryParams['elected'])) {
    $creations = $creationsDAO->selectByElected($queryParams['elected']);
  } else {
    $creations = $creationsDAO->selectAll();
  }

  $usersDAO = new UsersDAO();
  $likesDAO = new LikesDAO();

  // attach user + likes to creation
  foreach($creations as $key => $value) {
    $creations[$key]['user'] = $usersDAO->selectById($creations[$key]['user_id']);
    $creations[$key]['likes'] = $likesDAO->countByCreationId($creations[$key]['id']);
    $creations[$key]['alreadyLiked'] = !empty($likesDAO->selectByInputAndCreationId((empty($_SESSION['tt_user'])? $_SERVER['REMOTE_ADDR'] : $_SESSION['tt_user']['id']), $creations[$key]['id']));
  }
  return $response->write(json_encode($creations))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/creations/count', function ($request, $response, $args){
  $creationsDAO = new CreationsDAO();
  $count = $creationsDAO->getCount();
  return $response->write(json_encode($count))->withHeader('Content-Type', 'applicaton/json');
});

$app->post('/api/creations', function ($request, $response, $args) {
  if(authenticated()) {
    $creationsDAO = new CreationsDAO();
    $post = $request->getParsedBody();
    $post['user_id'] = $_SESSION['tt_user']['id'];
    $post['image_url'] = '';
    $post['type'] = '';
    $post['group_id'] = (empty($post['group_id'])? 0 : $post['group_id']);

    $imageMimeTypes = array('image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/ogg', 'video/webm');
    if (!empty($_FILES['image']) && in_array($_FILES['image']['type'], $imageMimeTypes)) {
      $targetFile = WWW_ROOT . 'upload' . DIRECTORY_SEPARATOR . $_FILES['image']['name'];
      $pos = strrpos($targetFile, '.');
      $filename = substr($targetFile, 0, $pos);
      $ext = substr($targetFile, $pos + 1);
      $i = 0;
      while (file_exists($targetFile)) {
        $i++;
        $targetFile = $filename . $i . '.' . $ext;
      }
      move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

      $slashPos = strrpos($_FILES['image']['type'],'/');
      $type = substr($_FILES['image']['type'],0,$slashPos);
      $ext = substr($_FILES['image']['type'],$slashPos+1);
      if($ext == 'gif'){
        $type = 'gif';
      }

      $post['image_url'] = str_replace(WWW_ROOT, '', $targetFile);
      $post['type'] = $type;
    }

    $errors = $creationsDAO->validate($post);
    if(!empty($errors)) {
      return $response->write(json_encode($errors))->withHeader('Content-Type', 'application/json')->withStatus(422);
    }
    $creation = $creationsDAO->insert($post);
    if(empty($creation)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(201);
    }
    return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }
});

$app->get('/api/creations/{id}', function ($request, $response, $args) {
  $creationsDAO = new CreationsDAO();
  $creation = $creationsDAO->selectById($args['id']);
  if(empty($creation)) {
    $response = $response->withStatus(404);
  } else {
    $usersDAO = new UsersDAO();
    $likesDAO = new LikesDAO();
    $creation['user'] = $usersDAO->selectById($creation['user_id']);
    $creation['likes'] = $likesDAO->countByCreationId($creation['id']);
    $creation['alreadyLiked'] = !empty($likesDAO->selectByInputAndCreationId((empty($_SESSION['tt_user'])? $_SERVER['REMOTE_ADDR'] : $_SESSION['tt_user']['id']), $creation['id']));

    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
});

$app->put('/api/creations/{id}', function ($request, $response, $args) {
  if(authenticated()) {
    $creationsDAO = new CreationsDAO();
    $creation = $creationsDAO->selectById($args['id']);
    if(!empty($creation)) {
      if(!($_SESSION['tt_user']['id'] === $creation['user_id'] || !checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_creations'))) {
        return $response->withStatus(403);
        exit;
      }
      $post = $request->getParsedBody();
      $post['id'] = $creation['id'];
      $post['user_id'] = $_SESSION['tt_user']['id'];
      $errors = $creationsDAO->validate($post);
      if(!empty($errors)) {
        return $response->write(json_encode($errors))->withHeader('Content-Type', 'application/json')->withStatus(422);
      }
      $updatedCreation = $creationsDAO->update($post);
      if(empty($updatedCreation)) {
        $response = $response->withStatus(404);
      } else {
        $response = $response->withStatus(200);
      }
    } else {
      $response = $response->withStatus(404);
    }
    return $response->write(json_encode($updatedCreation))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }
});

$app->patch('/api/creations/{id}/feature', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_feature_creations')) {
    $creationsDAO = new CreationsDAO();
    $creation = $creationsDAO->setFeatured($creation['id'], 1);
    if(empty($creation)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(200);
    }
    return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->post('/api/creations/{id}/like', function ($request, $response, $args) {
  $creationsDAO = new CreationsDAO();
  $creation = $creationsDAO->selectById($args['id']);
  if(empty($creation)) {
    $response = $response->withStatus(404);
  } else {
    $likesDAO = new LikesDAO();
    $alreadyLiked = $likesDAO->selectByInputAndCreationId((empty($_SESSION['tt_user'])? $_SERVER['REMOTE_ADDR'] : $_SESSION['tt_user']['id']), $creation['id']);
    if(empty($alreadyLiked)) {
      if(!$creation['nominated'] && $likesDAO->countByCreationId($creation['id']) > 50) { // when creation has over 50 likes
        $creation->setNominated(1);
      }
      $like = $likesDAO->insert($creation['id'], $_SERVER['REMOTE_ADDR'], (empty($_SESSION['tt_user'])? 0 : $_SESSION['tt_user']['id']));
    } else {
      $like = $likesDAO->delete($alreadyLiked['id']);
    }
    $response = $response->withStatus(200);
  }
  return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/creations/{id}', function ($request, $response, $args) {
  if(authenticated()) {
    $creationsDAO = new CreationsDAO();
    $creation = $creationsDAO->selectById($args['id']);
    if(!empty($creation)) {
      if($_SESSION['tt_user']['id'] === $creation['user_id'] || !checkPrivilige($_SESSION['tt_user']['id'], 'can_delete_creations')) {
        $creation = $creationsDAO->delete($creation['id']);
      } else {
        return $response->withStatus(403);
        exit;
      }
    } else {
      return $response->withStatus(404);
    }
    return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }

});

// GROUPS

$app->get('/api/groups', function ($request, $response, $args) {
  $groupsDAO = new GroupsDAO();
  $groups = $groupsDAO->selectAll();
  $queryParams = $request->getQueryParams();
  if(isset($queryParams['user_id'])) {
    $groups = $groupsDAO->selectByUserId($queryParams['user_id']);
  } elseif(isset($queryParams['approved'])) {
    $groups = $groupsDAO->selectByApproved($queryParams['approved']);
  } else {
    $groups = $groupsDAO->selectAll();
  }
  return $response->write(json_encode($groups))->withHeader('Content-Type', 'application/json');
});

$app->post('/api/groups', function ($request, $response, $args) {
  if(authenticated()) {
    $groupsDAO = new GroupsDAO();
    $post = $request->getParsedBody();
    $post['image_url'] = '';
    $post['creator_id'] = $_SESSION['tt_user']['id'];

    $imageMimeTypes = array('image/jpeg', 'image/png', 'image/gif');
    if (!empty($_FILES['image']) && in_array($_FILES['image']['type'], $imageMimeTypes)) {
      $targetFile = WWW_ROOT . 'upload' . DIRECTORY_SEPARATOR . $_FILES['image']['name'];
      $pos = strrpos($targetFile, '.');
      $filename = substr($targetFile, 0, $pos);
      $ext = substr($targetFile, $pos + 1);
      $i = 0;
      while (file_exists($targetFile)) {
        $i++;
        $targetFile = $filename . $i . '.' . $ext;
      }
      move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
      $post['image_url'] = str_replace(WWW_ROOT, '', $targetFile);
    }

    $errors = $groupsDAO->validate($post);
    if(!empty($errors)) {
      return $response->write(json_encode($errors))->withHeader('Content-Type', 'application/json')->withStatus(422);
    }
    $group = $groupsDAO->insert($post);
    if(empty($group)) {
      $response = $response->withStatus(404);
    } else {
      $user_groups = new UserGroupsDAO();
      $group['approvedToGroup'] = 1;
      $user_group = $user_groups->insert($group);
      $response = $response->withStatus(201);
    }
    return $response->write(json_encode($group))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }
});

$app->get('/api/groups/{id}', function ($request, $response, $args) {
  $groupsDAO = new GroupsDAO();
  $group = $groupsDAO->selectById($args['id']);
  if(empty($group)) {
    $response = $response->withStatus(404);
  } else {
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($group))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/groups/{id}/creations', function ($request, $response, $args) {
  $groupsDAO = new GroupsDAO();
  $group = $groupsDAO->selectById($args['id']);
  $creations = array();
  if(empty($group)) {
    $response = $response->withStatus(404);
  } else {
    $creationsDAO = new CreationsDAO();
    $creations = $creationsDAO->selectGroupId($group['id']);
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($creations))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/groups/{id}/users', function ($request, $response, $args) {
  $groupsDAO = new GroupsDAO();
  $group = $groupsDAO->selectById($args['id']);
  $users = array();
  if(empty($group)) {
    $response = $response->withStatus(404);
  } else {
    $usersDAO = new UsersDAO();
    $users = $usersDAO->selectByGroupId($group['id']);
    for($i = 0; $i < count($users); $i++){
      unset($users[$i]['password']);
      unset($users[$i]['email']);
    }
    $response = $response->withStatus(200);
  }
  return $response->write(json_encode($users))->withHeader('Content-Type', 'application/json');
});

$app->put('/api/groups/{id}', function ($request, $response, $args) {
  if(authenticated()) {
    $groupsDAO = new GroupsDAO();
    $group = $groupsDAO->selectById($args['id']);
    if(!empty($group)) {
      if(!($_SESSION['tt_user']['id'] === $group['creator_id'] || !checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_groups'))) {
        return $response->withStatus(403);
        exit;
      }
      $post = $request->getParsedBody();
      $post['id'] = $group['id'];
      $post['user_id'] = $_SESSION['tt_user']['id'];
      $errors = $groupsDAO->validate($post);
      if(!empty($errors)) {
        return $response->write(json_encode($errors))->withHeader('Content-Type', 'application/json')->withStatus(422);
      }
      $updatedGroup = $groupsDAO->update($post);
      if(empty($updatedGroup)) {
        $response = $response->withStatus(404);
      } else {
        $response = $response->withStatus(200);
      }
    } else {
      $response = $response->withStatus(404);
    }
    return $response->write(json_encode($updatedCreation))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }
});

$app->post('/api/groups/{id}/join', function ($request, $response, $args) {
  if(authenticated()) {
    $userGroupsDAO = new UserGroupsDAO();
    $post['id'] = $args['id'];
    $post['creator_id'] = $_SESSION['tt_user']['id'];
    $post['approvedToGroup'] = 0;
    $userGroup = $userGroupsDAO->insert($post);
    if(empty($userGroup)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(200);
    }
    return $response->write(json_encode($userGroup))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->patch('/api/groups/{id}/approve', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_approve_groups')) {
    $groupsDAO = new GroupsDAO();
    $group = $groupsDAO->setApproved($args['id'], 1);
    if(empty($group)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(200);
    }
    return $response->write(json_encode($group))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->delete('/api/groups/{id}', function ($request, $response, $args) {
  if(authenticated()) {
    $groupsDAO = new GroupsDAO();
    $group = $groupsDAO->selectById($args['id']);
    if(!empty($group)) {
      if($_SESSION['tt_user']['id'] === $creation['creator_id'] || !checkPrivilige($_SESSION['tt_user']['id'], 'can_delete_groups')) {
        $group = $groupsDAO->delete($group['id']);
      } else {
        return $response->withStatus(403);
        exit;
      }
    } else {
      return $response->withStatus(404);
    }
    return $response->write(json_encode($group))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(401);
  }
});

// SCORES

$app->get('/api/scores', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_judge_creations')) {
    $scoresDAO = new ScoresDAO();
    $scores = $scoresDAO->selectAll();
    $queryParams = $request->getQueryParams();
    if(isset($queryParams['creation_id'])) {
      $scores = $scoresDAO->selectByCreationId($queryParams['creation_id']);
    } elseif(isset($queryParams['user_id'])) {
      $scores = $scoresDAO->selectByUserId($queryParams['user_id']);
    } else {
      $scores = $scoresDAO->selectAll();
    }
    return $response->write(json_encode($scores))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->post('/api/scores', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_judge_creations')) {
    $scoresDAO = new ScoresDAO();
    $post = $request->getParsedBody();
    $score = $scoresDAO->insert($_SESSION['tt_user']['id'], $post['creation_id'], $post['score']);
    if(empty($score)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(201);
    }
    return $response->write(json_encode($score))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->delete('/api/scores/{id}', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_judge_creations')) {
    $scoresDAO = new ScoresDAO();
    $score = $scoresDAO->delete($args['id']);
    return $response->write(json_encode($score))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

// ROLES

$app->get('/api/roles', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_roles')) {
    $adminRolesDAO = new AdminRolesDAO();
    $roles = $adminRolesDAO->selectAll();
    return $response->write(json_encode($roles))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->post('/api/roles', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_roles')) {
    $adminRolesDAO = new AdminRolesDAO();
    $post = $request->getParsedBody();
    $role = $adminRolesDAO->insert($post);
    if(empty($role)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(201);
    }
    return $response->write(json_encode($role))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->put('/api/roles/{id}', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_roles')) {
    $adminRolesDAO = new AdminRolesDAO();
    $post = $request->getParsedBody();
    $post['id'] = $args['id'];
    $role = $adminRolesDAO->update($post);
    if(empty($role)) {
      $response = $response->withStatus(404);
    } else {
      $response = $response->withStatus(201);
    }
    return $response->write(json_encode($role))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

$app->delete('/api/roles/{id}', function ($request, $response, $args) {
  if(authenticated() && checkPrivilige($_SESSION['tt_user']['id'], 'can_edit_roles')) {
    $adminRolesDAO = new AdminRolesDAO();
    $role = $adminRolesDAO->delete($args['id']);
    return $response->write(json_encode($role))->withHeader('Content-Type', 'application/json');
  } else {
    return $response->withStatus(403);
  }
});

function authenticated() {
  if(!empty($_SESSION['tt_user'])) {
    $usersDAO = new UsersDAO();
    return !empty($usersDAO->selectById($_SESSION['tt_user']['id']));
  }
  return false;
}

function checkPrivilige($user_id, $privilige) {
  $usersDAO = new UsersDAO();
  $user = $usersDAO->selectById($user_id);
  if(!empty($user)) {
    $adminRoles = new AdminRolesDAO();
    return !empty($adminRoles->selectByIdAndPrivilige($user['role_id'], $privilige));
  }
  return false;
}

$app->run();
