<?php

function updateVisits() {
    $response = null;
    $request = Slim::getInstance()->request();
    $user = json_decode($request->getBody());
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $updateVisitas = "UPDATE users u SET visitas = visitas +1 where u.idUser = :idUser";
    $params = array();
    $params[0] = array("idUser", $user->idUser, "int");
    $error = false;
    if ($conn->conectar()) {
        try {
            $conn->beginTransaction();
            if ($conn->consulta($updateVisitas,$params)) {
                $conn->commitTransaction();
            } 
        } catch (Exception $exc) {
            $conn->rollbackTransaction();
        }
    }
    $conn->desconectar();
    echo $response;
}

function updateEmailsReceived($userEmail) {
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $sql = "SELECT u.idUser  FROM users u where email = :userEmail";
    $paramsUserEmail = array();
    $paramsUserEmail[0] = array("userEmail", $userEmail, "int");
    if ($conn->conectar()) {
        if ($conn->consulta($sql,$paramsUserEmail)) {
            if($conn->cantidadRegistros()>0){
                $records = $conn->restantesRegistros();
                $idUser = $records[0]->idUser;
                try {
                    $conn->beginTransaction();
                    $updateVisitas = "UPDATE users u SET emails_received = emails_received +1 where u.idUser = " . $idUser;
                    if ($conn->consulta($updateVisitas)) {
                        $conn->commitTransaction();
                    } 
                } catch (Exception $exc) {
                    $conn->rollbackTransaction();
                }
            }
        }
        $conn->desconectar();
    }
}