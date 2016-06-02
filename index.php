<?php
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
	$post = $request->getParsedBody();
	$user = $usersDAO->selectByInputAndPassword($post['input'], $post['password']);
	if(empty($user)) {
		return $response->withStatus(403);
	} else {
		session_start();
		unset($user['password']);
		$_SESSION['tt_user'] = $user;
		return $response->withStatus(200);
	}
});

$app->get('/api/users', function ($request, $response, $args) {
	$usersDAO = new UsersDAO();
	$queryParams = $request->getQueryParams();
	if(!empty($queryParams['group_id'])) {
		$users = $usersDAO->selectByGroupId($queryParams['group_id']);
	} else {
		$users = $usersDAO->selectAll();
	}
	for($i = 0; $i < count($users); $i++){
		unset($users[$i]['email']); // data mining and all...
		unset($users[$i]['password']);
	}
	return $response->write(json_encode($users))->withHeader('Content-Type', 'application/json');
});

$app->post('/api/users', function ($request, $response, $args) {
	$usersDAO = new UsersDAO();
	$post = $request->getParsedBody();
	$user = $usersDAO->insert($post['username'], $post['password'], $post['email'], $post['firstname'], $post['lastname'], $post['bio']);
	if(empty($user)) {
		$response = $response->withStatus(404);
	} else {
		unset($user['password']);
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
		unset($user['password']);
		$response = $response->withStatus(200);
	}
	return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
});

$app->put('/api/users/{id}', function ($request, $response, $args) {
	$usersDAO = new UsersDAO();
	$user = $usersDAO->selectById($args['id']);
	if(!empty($user)) {
		$post = $request->getParsedBody();
		if(empty($post['role_id'])) {
			$post['role_id'] = $user['role_id'];
		} if(empty($post['hidden'])) {
			$post['hidden'] = $user['hidden'];
		}
		$updatedUser = $usersDAO->update($user['id'], $post['username'], $post['password'], $post['email'], $post['firstname'], $post['lastname'], $post['bio'], $post['role_id'], $post['hidden']);
		if(empty($updatedUser)) {
			$response = $response->withStatus(404);
		} else {
			unset($updatedUser['password']);
			$response = $response->withStatus(200);
		}
	} else {
		$response = $response->withStatus(404);
	}
	return $response->write(json_encode($updatedUser))->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/users/{id}', function ($request, $response, $args) {
	$usersDAO = new UsersDAO();
	$user = $usersDAO->delete($args['id']);
	return $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
});

// CREATIONS

$app->get('/api/creations', function ($request, $response, $args) {
	$creationsDAO = new CreationsDAO();
	$creations = $creationsDAO->selectAll();
	$queryParams = $request->getQueryParams();
	if(!empty($queryParams['user_id'])) {
		$creations = $creationsDAO->selectByUserId($queryParams['user_id']);
	} elseif(!empty($queryParams['group_id'])) {
		$creations = $creationsDAO->selectByGroupId($queryParams['group_id']);
	} else {
		$creations = $creationsDAO->selectAll();
	}
	return $response->write(json_encode($creations))->withHeader('Content-Type', 'application/json');
});

$app->post('/api/creations', function ($request, $response, $args) { // TODO: add session check
	$creationsDAO = new CreationsDAO();
	$post = $request->getParsedBody();
	$creation = $creationsDAO->insert($_SESSION['tt_user']['id'], $post['title'], $post['info'], $post['image_url'], $post['group_id']);
	if(empty($user)) {
		$response = $response->withStatus(404);
	} else {
		$response = $response->withStatus(201);
	}
	return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/creations/{id}', function ($request, $response, $args) {
	$creationsDAO = new CreationsDAO();
	$creation = $creationsDAO->selectById($args['id']);
	if(empty($creation)) {
		$response = $response->withStatus(404);
	} else {
		$response = $response->withStatus(200);
	}
	return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
});

$app->put('/api/creations/{id}', function ($request, $response, $args) {
	$creationsDAO = new CreationsDAO();
	$creation = $creationsDAO->selectById($args['id']);
	if(!empty($creation)) {
		$post = $request->getParsedBody();
		if(empty($post['featured'])) {
			$post['featured'] = $creation['featured'];
		} if(empty($post['elected'])) {
			$post['elected'] = $creation['elected'];
		}
		$updatedCreation = $creationsDAO->update($creation['id'], $_SESSION['tt_user']['id'], $post['title'], $post['info'], $post['image_url'], $post['group_id'], $post['featured'], $post['elected']);
		if(empty($updatedCreation)) {
			$response = $response->withStatus(404);
		} else {
			$response = $response->withStatus(200);
		}
	} else {
		$response = $response->withStatus(404);
	}
	return $response->write(json_encode($updatedCreation))->withHeader('Content-Type', 'application/json');
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
			$like = $likesDAO->insert($creation['id'], $_SERVER['REMOTE_ADDR'], (empty($_SESSION['tt_user'])? 0 : $_SESSION['tt_user']['id']));
		} else {
			$like = $likesDAO->delete($alreadyLiked['id']);
		}
		$response = $response->withStatus(200);
	}
	return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/creations/{id}', function ($request, $response, $args) {
	$creationsDAO = new CreationsDAO();
	$creation = $creationsDAO->delete($args['id']);
	return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
});

// GROUPS

$app->get('/api/groups', function ($request, $response, $args) {
	$groupsDAO = new GroupsDAO();
	$groups = $creationsDAO->selectAll();
	$queryParams = $request->getQueryParams();
	if(!empty($queryParams['user_id'])) {
		$groups = $groupsDAO->selectByUserId($queryParams['user_id']);
	} else {
		$groups = $groupsDAO->selectAll();
	}
	return $response->write(json_encode($creations))->withHeader('Content-Type', 'application/json');
});

$app->post('/api/groups', function ($request, $response, $args) { // TODO: add session check
	$groupsDAO = new GroupsDAO();
	$post = $request->getParsedBody();
	$group = $groupsDAO->insert($post['title'], $post['info'], $_SESSION['tt_user']['id']);
	if(empty($user)) {
		$response = $response->withStatus(404);
	} else {
		$response = $response->withStatus(201);
	}
	return $response->write(json_encode($group))->withHeader('Content-Type', 'application/json');
});

$app->get('/api/groups/{id}', function ($request, $response, $args) {
	$groupsDAO = new GroupsDAO();
	$group = $groupsDAO->selectById($args['id']);
	if(empty($user)) {
		$response = $response->withStatus(404);
	} else {
		$response = $response->withStatus(200);
	}
	return $response->write(json_encode($creation))->withHeader('Content-Type', 'application/json');
});

$app->put('/api/groups/{id}', function ($request, $response, $args) {
	$groupsDAO = new GroupsDAO();
	$group = $groupsDAO->selectById($args['id']);
	if(!empty($group)) {
		$post = $request->getParsedBody();
		if(empty($post['approved'])) {
			$post['approved'] = $group['approved'];
		}
		$updatedGroup = $groupsDAO->update($group['id'], $post['title'], $_SESSION['tt_user']['id'], $post['approved']);
		if(empty($updatedGroup)) {
			$response = $response->withStatus(404);
		} else {
			$response = $response->withStatus(200);
		}
	} else {
		$response = $response->withStatus(404);
	}
	return $response->write(json_encode($updatedCreation))->withHeader('Content-Type', 'application/json');
});

$app->delete('/api/groups/{id}', function ($request, $response, $args) {
	$groupsDAO = new GroupsDAO();
	$group = $groupsDAO->delete($args['id']);
	return $response->write(json_encode($group))->withHeader('Content-Type', 'application/json');
});

// SCORES


//TODO: Check authorization; if unauthenticated: 401

$app->run();
