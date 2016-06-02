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

//TODO: Check authorization; if unauthenticated: 401

$app->run();
