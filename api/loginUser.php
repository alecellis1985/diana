<?php

function loginUser() {
    $request = Slim::getInstance()->request();
    $userLogin = json_decode($request->getBody());
    //$userLogin = getUserArrayFromRequest($request);
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);

    echo logUser($conn, $userLogin);
}

function logUser($conn, $userLogin) {
    $response = null;
    if ($conn->conectar()) {
        $sql = "SELECT * FROM users WHERE username = :username and password = :password";
        $params = array();
        $params[0] = array("username", $userLogin->username, "string");
        $params[1] = array("password", md5($userLogin->password), "string");
        if ($conn->consulta($sql, $params)) {
            $user = $conn->siguienteRegistro();
            //$user = $users[0];
            if ($user && isset($user->username)) {
                $_SESSION['ingreso'] = true;
                $_SESSION['usuario'] = $user->username;
                $_SESSION['idUser'] = $user->idUser;
                $_SESSION['password'] = $user->password;
                $_SESSION['email'] = $user->email;
                //TODO: Agregar campo isAdmin para el administrador
                $_SESSION['IsAdmin'] = $user->IsAdmin == 1;
                setcookie('usuario', $user->username);
                if($user->IsAdmin == 1){
                    $user->IsAdmin = true;
                }
                $error = false;
            } else {
                $_SESSION['ingreso'] = false;
                $error = true;
            }

            if (!$error) {
                $response = MessageHandler::getSuccessResponse("Successfully logged in!", $user);
            } else {
                $response = MessageHandler::getErrorResponse("El nombre de usuario y/o contraseña son inválidos, intente nuevamente.");
            }
        } else {
            echo MessageHandler::getErrorResponse("Error en el login, vuelva a intentar más tarde.");
        }
    }
    if ($response == null) {
        header('HTTP/1.1 400 Bad Request');
        return MessageHandler::getDBErrorResponse();
    } else {
        $conn->desconectar();
        return $response;
    }
}

function getUserArrayFromRequest($request) {
    return array(
        "username" => is_null($request->post('username')) ? "" : $request->post('username'),
        "password" => is_null($request->post('password')) ? "" : $request->post('password')
    );
}

function logoutUser() {
   
    $response = null;
    $_SESSION['ingreso'] = false;
    $_SESSION['IsAdmin'] = false;
    unset($_SESSION['usuario']);
    unset($_SESSION['password']);
    unset($_SESSION['idUser']);    
    unset($_SESSION['email']);
    
    if (isset($_COOKIE['usuario'])) {
        unset($_COOKIE['usuario']);
    }
    
    if (!isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
        $response = MessageHandler::getSuccessResponse("Successfully logged in!", null);
    } else {
        $response = MessageHandler::getErrorResponse("Error in logout, try again later");
    }
   
    echo $response;
}
