<?php

require 'Slim/Slim.php';

require_once("Slim/includes/class.Conexion.BD.php");
require_once("Slim/config/parametros.php");
require_once("Slim/includes/MessageHandler.php");
require_once('registerUser.php');
require_once('loginUser.php');
require_once('getUsers.php');
require_once('mailHandler.php');
require_once('userState.php');

session_cache_limiter(false);
session_start();
$app = new Slim();

$app->get('/users', 'getAllUsers');
$app->get('/users/loggedUser', 'isUserLogged');
$app->post('/sendMail', 'sendEmail');
$app->post('/login-user', 'loginUser');
$app->post('/logout-user', 'logoutUser');
$app->post('/agregar_usuario', 'registerUser');
$app->post('/editar_usuario', 'editUser');
$app->post('/check-username', 'checkUsername');
$app->get('/getCurrentUser', 'getLoggedUser');
$app->get('/currentUserAdmin', 'isCurrentUserAdmin');
$app->post('/editar_img', 'editImg');
$app->post('/update_userState', 'changeUserState');
$app->post('/edit-user-pwd', 'editUserPwd');
$app->post('/check-email', 'checkEmail');

$app->run();