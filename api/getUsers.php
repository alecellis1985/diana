<?php

function deleteUser(){
    $response = null;
    $request = Slim::getInstance()->request();
    $user = json_decode($request->getBody());
    if(isset($_SESSION['IsAdmin']) && !empty($_SESSION['IsAdmin']) && $_SESSION['IsAdmin']){
        $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
        $sqldiasatencion = "DELETE FROM diasatencion WHERE idUser = :idUser LIMIT 1";
        $sqlformasdepago = "DELETE FROM formasdepago WHERE idUser = :idUser LIMIT 1";
        $sqllocalidad_user = "DELETE FROM localidad_user WHERE idUser = :idUser LIMIT 1";
        $sqlcategoria_usuario = "DELETE FROM categoria_usuario WHERE idUser = :idUser";
        $sqlusers = "DELETE FROM users WHERE idUser = :idUser LIMIT 1";
        
        $userImgUrl = $user->imagenUrl;
        $deleteDestination = '../uploaded/' . $userImgUrl;
        if ($userImgUrl !== '') {
            unlink($deleteDestination);
        }
        
        $params = array();
        $params[0] = array("idUser", $user->idUser, "int");
        $error = false;
        if ($conn->conectar()) {
            try {
                $conn->beginTransaction();
                if ($conn->consulta($sqlcategoria_usuario,$params)) {
                        if ($conn->consulta($sqldiasatencion,$params)) {
                            if ($conn->consulta($sqlformasdepago,$params)) {
                                if ($conn->consulta($sqllocalidad_user,$params)) {
                                    if ($conn->consulta($sqlusers,$params)) {
                                    } else {
                                       $error = true;
                                    }
                                } else {
                                   $error = true;
                                }
                            } else {
                               $error = true;
                            }
                        } else {
                           $error = true;
                        }
                } else {
                   $error = true;
                }
            } catch (Exception $exc) {
                $response = MessageHandler::getDBErrorResponse("No se ha podido eliminar al usuario, intente más tarde.");;
                $conn->rollbackTransaction();
            }
        }
        if(!$error){
            $conn->commitTransaction();
            $response = MessageHandler::getSuccessResponse("User Deleted",null);
        }
        if ($response == null) {
            header('HTTP/1.1 400 Bad Request');
            echo MessageHandler::getDBErrorResponse();
        } else {
            $conn->desconectar();
            echo $response;
        }
    }
    else{
        header('HTTP/1.1 401 Not Authorized');
        echo MessageHandler::getDBErrorResponse();
    }
}





//THIS function is called from the main search page
function getUsers($categoria, $departamento,$barrioId, $nombreProf = null) {
    
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;

    $addNombreProfToQuery = "";
    if (isset($nombreProf) && $nombreProf != '') {
        $addNombreProfToQuery = "concat_ws(' ',u.nombre,u.apellido) like concat('%', :nombreProf, '%') and ";
    }

    if ($conn->conectar()) {
        $localidadId = -1;
        $error = false;
        if(isset($barrioId) && $barrioId != '' && (int) $departamento === 1){
            $localidadId = (int)$barrioId;
        }
                
        if($localidadId == -1 && (int) $departamento != 1){
            //New query EXCEPT MONTEVIDEO
            $sqlGetLocId = "SELECT barrioId FROM barrios b JOIN departamentos d ON b.departamentoId = d.idDepartamento " .
                    "WHERE b.departamentoId = :deptoId";

            $paramsLoc = array();
            $paramsLoc[0] = array("deptoId", (int) $departamento, "int");
            

            if ($conn->consulta($sqlGetLocId, $paramsLoc)) {
                $localidades = $conn->restantesRegistros();
                if (isset($localidades[0]))
                    $localidadId = $localidades[0]->barrioId;
            } 
            else 
            {
                $response = MessageHandler::getErrorResponse("Internet connection error, please reload the page.");
                $error = true;
            }
        }
        if(!$error){
            $localidadQuery = "";
            $usarLocId = false;
            if($localidadId == -1 && (int) $departamento == 1){
                $localidadQuery = "du.idLocalidad < 82";
            }
            else if($localidadId != -1 ){
                $usarLocId = true;
                $localidadQuery = "du.idLocalidad = :departamento";
            }
            else if($localidadId == -1 ){
                $usarLocId = true;
                $localidadQuery = ":departamento = -1";
                //$localidadQuery = "du.idLocalidad < 82";
            }
            
            
            $sql = "SELECT u.idUser,u.nombre,u.apellido,u.email,u.telefono,u.celular,u.direccion,u.telefonoEmp," .
                    "u.sitioWeb,u.imagenUrl,u.facebookUrl,u.twitterUrl," .
                    "u.linkedinUrl,u.descService,u.servicioOfrecido1,u.servicioOfrecido2,u.servicioOfrecido3," .
                    "u.servicioOfrecido4,u.servicioOfrecido5,u.servicioOfrecido6,u.descServiceLong,u.cardcolor,u.markers,u.plan,u.catspecial," .
                    "fp.*,da.* FROM " .
                    "users u left join formasdepago fp on u.idUser = fp.idUser " .
                    "left join diasatencion da on u.idUser = da.idUser " .
                    "join localidad_user du on u.idUser = du.idUser AND (". $localidadQuery ." ) " .
                    "join categoria_usuario cu on u.idUser = cu.idUser AND (cu.idCategoria = :categoria OR :categoria2 = -1) " .
                    " WHERE " . $addNombreProfToQuery .
                    " IsAdmin = 0 and IsActive = 1  group by u.idUser ORDER BY nombre";

            $params = array();
            //var_dump($localidadId);
            //die();
            if($usarLocId){
                $params[0] = array("departamento", (int) $localidadId, "int");
            }
            
            
            $params[1] = array("categoria", (int) $categoria, "int");
            //$params[2] = array("departamento2", (int) $localidadId, "int");
            $params[3] = array("categoria2", (int) $categoria, "int");

            if (isset($nombreProf) && $nombreProf != '') {
                // variable set, not empty string, not falsy
                $params[4] = array("nombreProf", $nombreProf, "string", 25);
            }
            if ($conn->consulta($sql, $params)) {
                $users = $conn->restantesRegistros();
                $response = MessageHandler::getSuccessResponse("", $users);
            } else {
                $response = MessageHandler::getErrorResponse("Internet connection error, please reload the page.");
            }
        }
    }
    //}
    if ($response == null) {
        header('HTTP/1.1 400 Bad Request');
        echo MessageHandler::getDBErrorResponse();
    } else {
        $conn->desconectar();
        echo $response;
    }
}

function getPremiumUsers() {
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;
    if ($conn->conectar()) {
            $sql = "SELECT u.idUser,u.nombre,u.apellido,u.email, u.direccion,u.telefonoEmp,u.descService," .
                    "u.sitioWeb,u.imagenUrl," .
                    "u.cardcolor,u.markers,u.plan,u.catspecial " .
                    "FROM users u WHERE IsAdmin = 0 and IsActive = 1 and plan in (2,5,6,8,11,12)";
            $retArr = array();
            if ($conn->consulta($sql)) {
                $users = $conn->restantesRegistros();
                $retArr[0] = $users;
                
                $response = MessageHandler::getSuccessResponse("", $retArr);
            } else {
                $response = MessageHandler::getErrorResponse("");
            }
    } else {
        $response = MessageHandler::getErrorResponse("");
    }
    if ($response == null) {
        header('HTTP/1.1 400 Bad Request');
        echo MessageHandler::getDBErrorResponse();
    } else {
        $conn->desconectar();
        echo $response;
    }
}

//Function to get users when you are admin
function getAllUsers() {
    $response = null;
    if(isset($_SESSION['IsAdmin']) && !empty($_SESSION['IsAdmin'])){
        $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
        if ($conn->conectar()) {
            $sql = "SELECT u.username,u.idUser,u.nombre,u.apellido,u.email,u.telefono,u.celular,u.plan,u.telefonoEmp,u.IsActive,u.fecharegistro,u.imagenUrl,u.visitas,emails_received  FROM users u where IsAdmin = 0 ORDER BY nombre";
            if ($conn->consulta($sql)) {
                $users = $conn->restantesRegistros();
                
                foreach ($users as $user) {
                    $sqlGetCats = "select c.* from categoria_usuario cu " .
                    " join categorias c on cu.idCategoria = c.categoriaId " .
                    " where idUser = :userId";
                    $paramsGetCats = array();
                    $paramsGetCats[0] = array("userId", $user->idUser, "int", 11);
                    if ($conn->consulta($sqlGetCats, $paramsGetCats)) {
                        $categorias = $conn->restantesRegistros();
                        $user->categorias = $categorias;
                    }
                }
                
                $response = MessageHandler::getSuccessResponse("", $users);
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
    else{
        header('HTTP/1.1 401 Not Authorized');
        echo MessageHandler::getDBErrorResponse();
    }
}

function isUserLogged() {
    if (array_key_exists("usuario", $_SESSION)) {
        $user = $_SESSION['usuario'];
        $isAdmin = $_SESSION['IsAdmin'];

        if ($_SESSION['ingreso'] && isset($user)) {
            $result = array("success" => true, "user" => $user, "IsAdmin" => $isAdmin);
            echo json_encode($result);
        } else {
            $result = array("success" => false);
            echo json_encode($result);
        }
    } else {
        $result = array("success" => false);
        echo json_encode($result);
    }
}

function getLoggedUser() {
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;
    if ($conn->conectar()) {
        $sql = "select * FROM users WHERE username = :username";
        $params = array();
        $params[0] = array("username", $_SESSION['usuario'], "string", 100);
        if ($conn->consulta($sql, $params)) {
            $users = $conn->restantesRegistros();
            $currentUser = $users[0];
            $userData['user'] = $currentUser;
            $userData['direcciones'] = json_decode($currentUser->direccion);
            $userData['IsAdmin'] = $currentUser->IsAdmin == 1;
            $_SESSION['IsAdmin'] = $currentUser->IsAdmin == 1;
            
            $currentUser->IsAdmin = $currentUser->IsAdmin == 1;
            if($currentUser->IsAdmin)
            {
                $response = MessageHandler::getSuccessResponse("Hellow Admin", $userData);
            }else{
                $sqlFormasDePago = "select * from formasdepago where idUser = :userId";
                $paramsFormasDePago = array();
                $paramsFormasDePago[0] = array("userId", $currentUser->idUser, "int", 11);
                if ($conn->consulta($sqlFormasDePago, $paramsFormasDePago)) {
                    $formasDePago = $conn->restantesRegistros();
                    $formaDePagoUser = $formasDePago[0];
                    $userData['formasDePago'] = $formaDePagoUser;
                    $sqlDiasAtencion = "select * from diasatencion where idUser = :userId";
                    $paramsDiasAtencion = array();
                    $paramsDiasAtencion[0] = array("userId", $currentUser->idUser, "int", 11);
                    if ($conn->consulta($sqlDiasAtencion, $paramsDiasAtencion)) {
                        $diasAtencion = $conn->restantesRegistros();
                        $diasAtencionUser = $diasAtencion[0];
                        $userData['diasAtencion'] = $diasAtencionUser;

                        //Get categorias and departamentos

                        $sqlGetDeptos = "Select b.barrioId, b.barrioNombre, d.idDepartamento, " .
                                " d.nombreDepartamento from localidad_user lu " .
                                " join barrios b on b.barrioId = lu.idLocalidad " .
                                " join departamentos d on b.departamentoId = d.idDepartamento " .
                                " where idUser = :userId";

                        $paramsGetDeptos = array();
                        $paramsGetDeptos[0] = array("userId", $currentUser->idUser, "int", 11);

                        if ($conn->consulta($sqlGetDeptos, $paramsGetDeptos)) {
                            $departamentos = $conn->restantesRegistros();
                            $userData['departamentos'] = $departamentos;

                            $sqlGetCats = "select c.* from categoria_usuario cu " .
                                    " join categorias c on cu.idCategoria = c.categoriaId " .
                                    " where idUser = :userId";

                            $paramsGetCats = array();
                            $paramsGetCats[0] = array("userId", $currentUser->idUser, "int", 11);

                            if ($conn->consulta($sqlGetCats, $paramsGetCats)) {
                                $categorias = $conn->restantesRegistros();
                                $userData['categorias'] = $categorias;

                                //var_dump($userData);
                                $response = MessageHandler::getSuccessResponse("", $userData);
                            } else {
                                $response = MessageHandler::getErrorResponse("Error con la consulta!");
                            }
                        } else {
                            $response = MessageHandler::getErrorResponse("Error con la consulta!");
                        }
                    } else {
                        $response = MessageHandler::getErrorResponse("Error con la consulta!");
                    }
                } else {
                    $response = MessageHandler::getErrorResponse("Error con la consulta!");
                }
            }
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

function isCurrentUserAdmin() {
    $response = MessageHandler::getSuccessResponse("Success", Array('isAdmin' => $_SESSION['IsAdmin']));
    echo $response;
}


function updateDb(){
    $conn = new ConexionBD(DRIVER, SERVIDOR, BASE, USUARIO, CLAVE);
    $response = null;
    $error = false;
    if ($conn->conectar()) {
            $sql = "SELECT * FROM categoria_usuario GROUP BY idUser";
            if ($conn->consulta($sql)) {
                $users = $conn->restantesRegistros();                
                $conn->beginTransaction();
                foreach ($users as $user) {
                    $updateScript = "UPDATE users SET catspecial = " . $user->idCategoria . " WHERE idUser = ". $user->idUser . "; ";
                    if (!$conn->consulta($updateScript)) {
                        $error = true;
                    }
                }
                
                
                
                $response = MessageHandler::getSuccessResponse("aaa",null);
            } else {
                $response = MessageHandler::getErrorResponse("");
            }
    } else {
        $response = MessageHandler::getErrorResponse("");
    }
    
    if (!$error) 
    {
        $conn->commitTransaction();
        $response = MessageHandler::getSuccessResponse("Se registro exitosamente!", null);
    } else {
        $conn->rollbackTransaction();
        $response = MessageHandler::getErrorResponse("DB ERROR.");
    }
    
    if ($response == null) {
        header('HTTP/1.1 400 Bad Request');
        echo MessageHandler::getDBErrorResponse();
    } else {
        $conn->desconectar();
        echo $response;
    }
    
}