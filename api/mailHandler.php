<?php

function sendEmail() {
	$request = Slim::getInstance()->request();
    $emailPostData = json_decode($request->getBody());
	if (isset($emailPostData->email) && !empty($emailPostData->email) && 
		isset($emailPostData->mensaje) && !empty($emailPostData->mensaje)) 
		{
			$subject = "Mail from " . $emailPostData->email;
			$message = $emailPostData->mensaje;
			$headers = "From:" . $emailPostData->email;
			try {
				if ( mail('hello@pb-studio.com',$subject,$message,$headers)) {
					$response = MessageHandler::getSuccessResponse("The email has been sent!", null);
				} else {

					$response = MessageHandler::getErrorResponse("An error has ocurred, pleas try again later.");
				}
			} catch (Exception $e) {

				$response = MessageHandler::getErrorResponse("An error has ocurred, pleas try again later.");
			}
		}
		else{
			$response = MessageHandler::getErrorResponse("Error, please fill in email and message!");
		}
    

    echo $response;
}

function sendMailToContact() {

    $request = Slim::getInstance()->request();

    $emailPostData = json_decode($request->getBody());



    if (isset($emailPostData->contactemail) && !empty($emailPostData->contactemail) &&
            isset($emailPostData->email) && !empty($emailPostData->email) &&
            isset($emailPostData->nombre) && !empty($emailPostData->nombre) &&
            isset($emailPostData->nombre) && !empty($emailPostData->nombre) &&
            isset($emailPostData->apellido) && !empty($emailPostData->apellido) &&
            isset($emailPostData->mensaje) && !empty($emailPostData->mensaje)) {



        $para = $emailPostData->contactemail;

        $headers = "From: No Responder Profesionales.uy <info@profesionales.com.uy>";



        $subject = "Mensaje de: " . $emailPostData->nombre . " " . $emailPostData->apellido;

        $messageBody = "";

        $messageBody .= "<p><b>Por favor no responda este email.</b></p>" . "\n";

        $messageBody .= "<p>Este email ha sido enviado por: " . $emailPostData->nombre . " " . $emailPostData->apellido . "</p>" . "\n";

        $messageBody .= "<p>Contacta a esta persona al email: " . $emailPostData->email . "</p>" . "\n";

        if (isset($emailPostData->telefono) && !empty($emailPostData->telefono)) {

            $messageBody .= "<p>Telefono: " . $emailPostData->telefono . "</p>" . "\n";

            $messageBody .= "<br>" . "\n";
        }



        $messageBody .= "<p> Mensaje: " . $emailPostData->mensaje . "</p>";



        $messageBody = strip_tags($messageBody);



        try {

            if (mail($para, $subject, $messageBody, $headers)) {

                updateEmailsReceived($para);

                $response = MessageHandler::getSuccessResponse("El email ha sido enviado!", null);
            } else {

                $response = MessageHandler::getErrorResponse("Error al enviar el mail, por favor intente mas tarde.");
            }
        } catch (Exception $e) {

            $response = MessageHandler::getErrorResponse("Error al enviar el mail, por favor intente mas tarde.");
        }
    } else {

        $response = MessageHandler::getErrorResponse("Debe completar todos los campos para poder enviar emails.");
    }

    echo $response;
}
