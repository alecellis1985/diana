<?php

function changeUserState() {
    $request = Slim::getInstance()->request();
    $user = json_decode($request->getBody());
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    echo updateUserState($conn, $user);
}

function updateUserState($conn, $user) {
    if ($_SESSION['ingreso'] && $_SESSION['IsAdmin']) {
        $response = null;
        if ($conn->conectar()) {
            $sql = "SELECT * FROM users WHERE username = :username";
            $params = array();
            $params[0] = array("username", $user->username, "string");
            if ($conn->consulta($sql, $params)) {
                $users = $conn->restantesRegistros();
                $userFromDb = $users[0];
                $userState = $userFromDb->IsActive == 1 ? 0 : 1;
                $sqlUpdateUser = "UPDATE users SET IsActive = " . $userState . " where username = '" . $userFromDb->username . "'";
                if ($conn->consulta($sqlUpdateUser)) {
                    $response = MessageHandler::getSuccessResponse('El estado del usuario ha sido cambiado con éxito!', null);
                }
            }
        }
        if ($response == null) {
            header('HTTP/1.1 400 Bad Request');
            return MessageHandler::getDBErrorResponse();
        } else {
            $conn->desconectar();
            return $response;
        }
    } else {
        header('HTTP/1.1 401 Unauthorized Request');
        return MessageHandler::getDBUnauthorizedResponse();
    }
}
