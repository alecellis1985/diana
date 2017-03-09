<?php
function recoverUserPassword() {
    $request = Slim::getInstance()->request();
    $emailPostData = json_decode($request->getBody());
    
    
    
    if(empty($emailPostData->email) || !isset($emailPostData->email)){	
        echo MessageHandler::getErrorResponse("Por favor ingrese email o username para recuperar su contraseña.");
        return;
    }
    
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;
     if ($conn->conectar()) {
        $sql = "SELECT idUser FROM users WHERE email = :email";
        $params = array();
        $email = $emailPostData->email;
        $params[0] = array("email", $email, "string");
        if ($conn->consulta($sql,$params)) {
            $userId = $conn->restantesRegistros();
            if(count($userId)==0){
                echo MessageHandler::getErrorResponse("El email no se encuentra registrado en nuestro sitio.");
                return;
            }
            $date = new DateTime();
            $timeSpan = $date->getTimestamp();
            $token = bin2hex(openssl_random_pseudo_bytes(8));
            $resetToken = $token . "--". (string)$timeSpan;
            $resetPasswordQuery = "Insert INTO reset_password values(". $userId[0]->idUser .",'". $resetToken ."')";
            if ($conn->consulta($resetPasswordQuery)) {
                $to = $email;
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= 'From: Profesionales.uy <info@profesionales.com.uy>' . "\r\n";
                $subject = '=?UTF-8?B?'.base64_encode("Nueva contraseña para profesionales.uy").'?=';
                $messageBody = "";
                $messageBody .= '<p>Este mail ha sido enviado debido a su petición'. "\r\n". 'de recuperación de contraseña. <br>'. "\r\n"; 
                $messageBody .= 'Entra en la siguiente pagina para resetear tu contraseña:'. "\r\n". 'http://profesionales.uy/#/resetPassword/' .$resetToken. ' .';
                $messageBody = strip_tags($messageBody);
                
                try{
                    if(!mail($to, $subject, $messageBody, $headers)){
                            $response = MessageHandler::getErrorResponse("Error al enviar el mail, por favor intente mas tarde.");
                    }else{
                            $response = MessageHandler::getSuccessResponse("Se ha enviado un mail con una nueva contraseña a su mail!",null);
                    }
                }catch(Exception $e){
                        $response = MessageHandler::getErrorResponse("Error al enviar el mail, por favor intente mas tarde.");
                }
                
            }
                
            }
            else{
                 $response = MessageHandler::getErrorResponse("No nos hemos podido conectar con el servidor, por favor intente más tarde.");
            }
        }
        else{
            $response = MessageHandler::getErrorResponse("No nos hemos podido conectar con el servidor, por favor intente más tarde.");
        }
        if ($response == null) {
            header('HTTP/1.1 400 Bad Request');
            echo MessageHandler::getDBErrorResponse();
        } else {
            $conn->desconectar();
            echo $response;
        }
        
        
    }
    
function resetUserPasswordAndValidateToken() {
    $request = Slim::getInstance()->request();
    $resetData = json_decode($request->getBody());
    
    if(empty($resetData->password) || !isset($resetData->password)){
        echo MessageHandler::getErrorResponse("Por favor ingrese una nueva contraseña.");
        return;
    }
    
    if(!isset($resetData->token)|| !isset($resetData->token)){	
        echo MessageHandler::getErrorResponse("Debe resetear tu contraseña nuevamente ya que su token ha expirado.");
        return;
    }
    
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    if ($conn->conectar()) {
        try {
            $conn->beginTransaction();
        $sql = "SELECT userId FROM reset_password WHERE token = :token";
        $params = array();
        $token = $resetData->token;
        
        $params[0] = array("token", $resetData->token, "string");
        if ($conn->consulta($sql,$params)) {
            $userId = $conn->restantesRegistros();
            $date = new DateTime();
            $timeSpan = $date->getTimestamp();
            $tokenTimeSpan = explode( '--', $resetData->token );
            if(count($tokenTimeSpan) == 2){
                $timespan2 = (int)$tokenTimeSpan[1];
                if($timeSpan-$timespan2>3600*24){
                    echo MessageHandler::getErrorResponse("El link recibido en el mail ha expirado, por favor genere uno nuevo.");
                    return;
                }
                else{
                    $password = md5($resetData->password);
                    $resetPasswordQuery = "UPDATE users SET password = :password where idUser = ". $userId[0]->userId;
                    $params2[0] = array("password", $password, "string");
                        if ($conn->consulta($resetPasswordQuery,$params2)) {
                            $conn->closeCursor();
                            $conn->commitTransaction();
                            $conn->desconectar();
                            echo MessageHandler::getSuccessResponse("Contraseña actualizada!",null);
                        }else {
                            $conn->rollbackTransaction();
                            $conn->desconectar();
                            echo MessageHandler::getErrorResponse("No se pudo resetear la contraseña. Intente nuevamente.");
                        }
                }
            }
        }
        else{
            $conn->desconectar();
             echo MessageHandler::getErrorResponse("Problemas al conectarse con el servidor, intente más tarde.");
        }
        }catch (Exception $exc) {
            $conn->rollbackTransaction();
            $conn->desconectar();
            echo MessageHandler::getErrorResponse("No se pudo resetear la contraseña. Intente nuevamente.");
        }
        
    }
    else{
        echo MessageHandler::getErrorResponse("Problemas al conectarse con el servidor, intente más tarde.");
    }
}