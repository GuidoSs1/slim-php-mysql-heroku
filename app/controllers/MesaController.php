<?php

//require_once './interfaces/IApiUsable.php';
require_once './models/Mesa.php';

class MesaController extends Mesa {

  public function TraerUno($request, $handler){
    $params = $request->getParsedBody();
    $id = $params['mesa_id'];
    $Mesa = Mesa::getMesaById($id);
    $Mesa->printSingleEntityAsMesa();
    $payload = json_encode($Mesa);
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

	public function TraerTodos($request, $handler){
    $mesas = Mesa::getAllMesas();

    $payload = json_encode(array("Mesas" => $mesas));
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

	public function CargarUno($request, $handler){
    $params = $request->getParsedBody();
    $mesa = Mesa::createMesa($params['mesa_code'], $params['empleado_id'], $params['estado']);
    
    $payload = json_encode($mesa);
    $mesa_id = Mesa::insertMesa($mesa);
    if($mesa_id > 0){
      echo 'Mesa Creada: <br>';
      $mesa->__set("id",$mesa_id);
      $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
      $handler->getBody()->write("Mesa created successfully");
    }
    else{
      $handler->getBody()->write("Something failed while creating the Mesa");
    }
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');

  }

	public function BorrarUno($request, $handler){
        $params = $request->getParsedBody();
        $id = $params['Mesa_id'];
        $Mesa = Mesa::getMesaById($id);
        $payload = json_encode($Mesa);
        if(isset($Mesa) && Mesa::eliminarMesa($id) > 0){
            $payload = json_encode(array("mensaje" => "Mesa eliminada con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Algo salio mal"));
        }
        $handler->getBody()->write($payload);
        return $handler
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $handler){

        $params = $request->getParsedBody();

        $this->TraerTodos($request, $handler);
        
        if (isset($params['mesa_id']) && isset($params['estado']) && isset($params['empleado_id'])) {
            $mesa_id = $params['mesa_id'];
            $empleado_id = $params['empleado_id'];

            $estado = $params['estado'];

            $empleado = Empleado::getEmpleadoById($empleado_id);
            
            if (isset($empleado) && isset($mesa_id) && strcmp($estado, "Cerrada") != 0) {
                $mesa = Mesa::getMesaById($mesa_id);
                $mesa->__set("estado",$estado);
                $mesa->__set("id_empleado",$empleado_id);
            }
        }
        
        if (isset($mesa) && Mesa::modificarMesa($mesa) > 0) {
            $payload = json_encode(array("mensaje" => "Mesa modifica con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Algo salio mal"));
        }
        $handler->getBody()->write($payload);
        return $handler
          ->withHeader('Content-Type', 'application/json');
    }

    public function CobrarUno($request, $handler){
        $params = $request->getParsedBody();
        $payload = json_encode(array("mensaje" => "Algo salio mal modificando la mesa"));

        if(isset($params['mesa_id']) && isset($params['estado'])){
            $mesa_id = $params['mesa_id'];
            $mesa_estado = $params['estado'];
            $mesa = Mesa::getMesaById($mesa_id);

            if(isset($mesa)){
                $mesa->__set("estado",$mesa_estado);
                echo 'Mesa modificada: <br>';
                $mesa->printSingleEntityAsMesa();
                if(Mesa::modificarMesa($mesa) > 0){
                    $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
                }
            }
        }
        
        $handler->getBody()->write($payload);
        return $handler
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUnoAdmin($request, $handler){
        $params = $request->getParsedBody();
        
        if (isset($params['mesa_id']) && isset($params['estado'])) {
            $mesa_id = $params['mesa_id'];
            $estado = $params['estado'];

            if (isset($Mesaid)) {
                $mesa = Mesa::getMesaById($mesa_id);

                if(strcmp($mesa->__get("estado"), "Cerrada") == 0 
                && strcmp($estado, "Cerrada") == 0){
                    echo 'La mesa ya esta cerrada';
                }else{
                    $mesa->__set("estado",$estado);
                }
            }
        }
        
        if (isset($mesa) && Mesa::modificarMesa($mesa) > 0) {
            $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Algo salio mal"));
        }
        $handler->getBody()->write($payload);
        return $handler
          ->withHeader('Content-Type', 'application/json');
    }

    /*public function TraerDemoraPedidoMesa($request, $handler, $args){

        $Mesa_code = $args['Mesa_code'];
        $order_id = $args['order_id'];
        $delay = Order::getMaxTimeOrderByMesaCode($order_id, $Mesa_code)[0]['time_order'];
        if ($delay == 0){
            echo '<h2>Mesa Code: '.$Mesa_code.'<br>Waiting Time: '.$delay.' minutes.</h2>
            <h2>Your order is ready, it will be dispatched shortly. Thanks for choosing us, Bon Appetit</h2><br>';
        }else{
            echo '<h2>Mesa Code: '.$Mesa_code.'<br>Order Will be ready in aprox: '.$delay.' minutes.</h2><br>';
        }
        $payload = json_encode(array("mensaje" => "Waiting Time: ".$delay." minutes"));
        $handler->getBody()->write($payload);
        return $handler
          ->withHeader('Content-Type', 'application/json');
    }*/
}
?>