<?php

require_once './models/Usuario.php';

class LoginController extends Usuario{
  public function verificarUsuario($request, $handler){
    $params = $request->getParsedBody();
    $username = $params['username'];
    $pass = $params['password'];
    
    $Usuario = Usuario::getUsuarioByUsername($username);
    $payload = json_encode(array('status' => 'Usuario invalido'));
    
    if(!is_null($Usuario)){
      if(password_verify($pass, $Usuario->__get("password"))){
        $UsuarioData = array(
          'id' => $Usuario->__get("id"),
          'username' => $Usuario->__get("username"),
          'password' => $Usuario->__get("password"),
          'isAdmin' => $Usuario->__get("isAdmin"),
          'user_type' => $Usuario->__get("user_type"));
        
        $payload = json_encode(array(
        'Token' => AuthJWT::crearToken($UsuarioData), 
        'handler' => 'Valid_Usuario', 
        'Admin' => $Usuario->__get("isAdmin"),
        'user_type' => $Usuario->__get("user_type")));
        /*$idLoginInserted = Usuario::insertHistoricalLogin($Usuario);

        if($idLoginInserted > 0){
            echo "Login inserted successfully";
        }*/
      }
    }
      
    $handler->getBody()->write($payload);
    return $handler->withHeader('Content-Type', 'application/json');
  }
}
?>