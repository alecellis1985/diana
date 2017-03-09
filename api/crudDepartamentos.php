<?php
function getDepartamentos() {
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;
    if ($conn->conectar()) {
        $sql = "SELECT * FROM departamentos ORDER BY nombreDepartamento"; 
        //$sql = "SELECT * FROM barrios b JOIN departamentos d ON b.departamentoId = d.idDepartamento";
        if ($conn->consulta($sql)) {
            $depto = $conn->restantesRegistros(); 
            $response = MessageHandler::getSuccessResponse("",$depto);
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

