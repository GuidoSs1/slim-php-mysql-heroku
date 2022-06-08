<?php

require_once './interfaces/IApiUsable.php';

require_once './models/Empleado.php';
require_once './models/Area.php';
require_once './models/Usuario.php';

require_once 'UsuarioController.php';


class EmpleadoController extends Empleado{

  public function CargarUno($request, $handler){
    $params = $request->getParsedBody();
    var_dump($params);
    $empleado_name = $params['nombre'];
    $empleado_area = $params['area'];
    $empleado_user = $params['usuario'];
    $empleado_area = Area::getAreaByName($empleado_area);
    $empleado_user_id = Usuario::getUsuarioByUsername($empleado_user)->__get("id");
    $newEmpleado = Empleado::createEmpleado($empleado_user_id, $empleado_area->__get("area_id"), $empleado_name, date("Y-m-d H:i:s"));

    if (Empleado::insertEmpleado($newEmpleado) > 0) {
      $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
      $handler->getBody()->write("Empleado creado con exito");
    }else{
      $handler->getBody()->write("Algo salio mal al crear al empleado");
    }

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $handler, $args){
    $empleado_id = $args['id'];
    $empleado = Empleado::getEmpleadoById($empleado_id);
    $payload = json_encode($empleado);
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $handler){
    $empleados = Empleado::getAllEmpleados();

    $payload = json_encode(array("Empleados" => $empleados));
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $handler){
      $params = $request->getParsedBody();
      $empleado_id = $params['id'];
      $empleado = Empleado::getEmpleadoById($empleado_id);
      if (Empleado::eliminarEmpleado($empleado) > 0) {
          $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
          $handler->getBody()->write("Empleado deleted successfully");
      }else{
          $handler->getBody()->write("Something failed while deleting the Empleado");
      }

      $handler->getBody()->write($payload);
      return $handler
        ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $handler){
    $params = $request->getParsedBody();
    $empleado_id = $params['id'];
    $empleado = Empleado::getEmpleadoById($empleado_id);
    $nombre = $params['nombre'];
    $empleado_area_id = Area::getAreaByName($params['area'])->__get("area_id");
    $empleado_user_id = Usuario::getUsuarioByUsername($params['usuario'])->__get("id");

    $empleado->__set("nombre",$nombre);
    $empleado->__set("empleado_area_id",$empleado_area_id);
    $empleado->__set("user_id",$empleado_user_id);

    if (Empleado::modificarEmpleado($empleado) > 0) {
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
      $handler->getBody()->write("Empleado modificado con exito");
    }else{
      $handler->getBody()->write("Algo salio mal");
    }

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }
}
?>