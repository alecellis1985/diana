<?php

require 'Slim/Slim.php';

require_once("Slim/includes/class.Conexion.BD.php");
require_once("Slim/config/parametros.php");
require_once("Slim/includes/MessageHandler.php");
require_once('crudDepartamentos.php');
require_once('crudBarrios.php');
require_once('crudCategorias.php');
require_once('registerUser.php');
require_once('loginUser.php');
require_once('getUsers.php');
require_once('userData.php');
require_once('mailHandler.php');
require_once('planes.php');
require_once('userState.php');
require_once('resetPasswordMail.php');


session_cache_limiter(false);
session_start();
//$app = new Slim(array('debug' => false));
$app = new Slim();


$app->post('/sumarVisita', 'updateVisits');
$app->post('/deleteuser', 'deleteUser');
$app->get('/users', 'getAllUsers');
$app->get('/getPremiumUsers', 'getPremiumUsers');
$app->get('/users/loggedUser', 'isUserLogged');
$app->post('/sendMail', 'sendEmail');
$app->post('/sendMailContact', 'sendMailToContact');
$app->post('/recoverPassword', 'recoverUserPassword');
$app->post('/resetPasswordToken', 'resetUserPasswordAndValidateToken');

$app->get('/userPlans', 'getPlanes');
$app->get('/departamentos', 'getDepartamentos');
$app->get('/categorias', 'getCategorias');
$app->get('/barrios', 'getBarrios');
$app->post('/login-user', 'loginUser');
$app->post('/logout-user', 'logoutUser');
$app->get('/users/:categoria/:departamento/:barrioId(/:nombreProf)', 'getUsers');
$app->post('/agregar_usuario', 'registerUser');
$app->post('/editar_usuario', 'editUser');
$app->post('/check-username', 'checkUsername');
$app->get('/getCurrentUser', 'getLoggedUser');
$app->get('/currentUserAdmin', 'isCurrentUserAdmin');
$app->post('/editar_img', 'editImg');
$app->post('/update_userState', 'changeUserState');
$app->post('/edit-user-pwd', 'editUserPwd');
$app->post('/edit-user-categoria','editUserCategoria');
$app->post('/check-email', 'checkEmail');
//$app->get('/updateDb','updateDb');
$app->run();