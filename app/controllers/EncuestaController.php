<?php

require_once './models/Encuesta.php';

class EncuestaController{

  public function CargarUno($request, $handler){
    $params = $request->getParsedBody();
    $payload = json_encode(array("message" => "Algo salio mal"));
    if (isset($params['mesa_punt']) && isset($params['cocinero_punt'])
    && isset($params['mozo_punt']) && isset($params['resto_punt'])
    && isset($params['pedido_id']) && isset($params['comentario'])) {
      $pedido_id = $params['pedido_id'];
      $mesa_punt = $params['mesa_punt'];
      $resto_punt = $params['resto_punt'];
      $mozo_punt = $params['mozo_punt'];
      $cocinero_punt = $params['cocinero_punt'];
      $comentario = $params['comentario'];

      $Encuesta = Encuesta::createEncuesta($pedido_id, $mesa_punt, $resto_punt, $mozo_punt, $cocinero_punt, $comentario);

      if(Encuesta::insertEncuesta($Encuesta) > 0){
        $payload = json_encode(array("Encuesta" => $Encuesta, "message" => "Encuesta creada con exito"));
      }
    }
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function mejoresEncuestas($request, $handler){
    $params = $request->getParsedBody();
    $payload = json_encode(array("message" => 'Error al cargar las encuestas'));
    if (isset($params['cantidad'])){
      $cantidad = $params['cantidad'];
      $Encuestas = Encuesta::mejoresEncuestas($cantidad);
      $payload = json_encode(array("Best Encuestas" => $Encuestas));
    }
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }
}
?>