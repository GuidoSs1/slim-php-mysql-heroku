<?php

require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './middlewares/AuthJWT.php';

class UsuarioController extends Usuario{

  public function CargarUno($request, $handler){

    $params = $request->getParsedBody();
    echo '<br>Datos de usuario a crear:<br>';
    var_dump($params);
    
    $usuario = Usuario::createUsuario(
      $params['username'], 
      $params['password'], 
      $params['isAdmin'], 
      $params['user_type'],
      $params['estado'], 
      date('Y-m-d H:i:s'));
    if (Usuario::insertUsuario($usuario) > 0) {
      $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
    }else{
      $payload = json_encode(array("mensaje" => "Error al crear el Usuario"));
    }

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $handler, $args){

    $usr = $args['nombre'];
    $Usuario = Usuario::getUsuarioById($usr);
    $payload = json_encode($Usuario);

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $handler){

    $UsuariosList = Usuario::getAllUsuarios();
    $payload = json_encode(array("UsuariosList" => $UsuariosList));

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $handler){
    $params = $request->getParsedBody();
    var_dump($params);
    if(isset($params['username'])){
      $usr = $params['username'];
      $usuario = Usuario::getUsuarioByUsername($usr);
      $usuario->__set("username",$params['username']);
      $usuario->__set("password",$params['password']);


      if(!Usuario::modificarUsuario($usuario)){
        $payload = json_encode(array("mensaje" => "Usuario no modificado"));
      }else{
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
      }
      $handler->getBody()->write($payload);
    }

    return $handler->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $handler)
  {
    $params = $request->getParsedBody();

    $usuarioBaja = Usuario::getUsuarioById($params['Id']);
    Usuario::eliminarUsuario($usuarioBaja);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function Login($request, $handler){ 
    $params = $request->getParsedBody();

    if (isset($params['username']) && isset($params['password'])) {
      $username = $params['username'];
      $password = $params['password'];
      $usuario = Usuario::getUsuarioByUsername($username);

      if ($usuario != null && ($usuario->__get("username") == $username && $usuario->__get("password") == $password)) {
        $payload = json_encode(array("mensaje" => "Login exitoso"));
      } else {
        $payload = json_encode(array("mensaje" => "Login fallido"));
      }
    }

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public static function GetInfoByToken($request){
    $header = $request->getHeader('Authorization');
    $token = trim(str_replace("Bearer", "", $header[0]));
    $Usuario = AuthJWT::ObtenerData($token);
    
    return $Usuario;
  }
}
?>