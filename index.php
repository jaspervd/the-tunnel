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
	unset($user['password']);
	$response = $response->write(json_encode($user))->withHeader('Content-Type', 'application/json');
	if(empty($insertedTeacher)) {
		$response = $response->withStatus(404);
	} else {
		$response = $response->withStatus(201);
	}
	return $response;
});

$app->run();
