<?php
function getBarrios() {
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;
    if ($conn->conectar()) {
        $sql = "SELECT * FROM barrios ORDER BY barrioNombre";// ORDER BY nombreDepartamento
        if ($conn->consulta($sql)) {
            $barrio = $conn->restantesRegistros(); 
            $response = MessageHandler::getSuccessResponse("",$barrio);
        } else {
            $response = MessageHandler::getErrorResponse("Internet connection error, please reload the page.");
        }
    }
    if ($response == null) {
        header('HTTP/1.1 400 Bad Request');
        echo MessageHandler::getDBErrorResponse();
    } else {
        $conn->desconectar();
        echo $response;
    }
}