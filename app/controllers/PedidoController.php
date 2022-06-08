<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './models/UploadManager.php';
require_once './controllers/UsuarioController.php';

class PedidoController extends Pedido{

  public function TraerUno($request, $handler, $args){
    $id = $args['id'];
    $pedido = Pedido::getPedidoById($id);
    $payload = json_encode($pedido);
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

	public function TraerTodos($request, $handler){
    $pedidos = Pedido::getAll();

    $payload = json_encode(array("Pedidos" => $pedidos));
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

	public function TraerSegunArea($request, $handler){
    $user_type = UsuarioController::GetInfoByToken($request)->__get("user_type");

    $Productoes = Producto::getProductosByUserType($user_type);

    $payload = json_encode(array("Productos" => $Productoes));
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

	public function CargarUno($request, $handler){
    $imagesDir = "./PedidoImages/";
    $params = $request->getParsedBody();
    
    $mesa_id = $params['mesa_id'];
    
    $pedido = Pedido::createPedido(
      $mesa_id, 
      $params['estado'], 
      $params['cliente'], 
      $params['costo']
    );
    
    $payload = json_encode($pedido);
    $pedido_id = Pedido::insertPedido($pedido);
    if($pedido_id > 0){
      $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
      $handler->getBody()->write("Pedido creado con exito");
      if($_FILES !== null){
        $fileManager = new UploadManager($imagesDir, $pedido_id, $_FILES);
        $pedido = Pedido::getPedidoById($pedido_id);
        $pedido->__set("img_pedido",UploadManager::getPedidoImageNameExt($fileManager, $pedido_id));
        Pedido::updatePicture($pedido);
      }
      
      echo 'Pedido Created: <br>';
        
    }
    else{
        $handler->getBody()->write("Algo salio mal");
    }
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');

  }

	public function BorrarUno($request, $handler){
    $params = $request->getParsedBody();
    $id = $params['id'];
    $Pedido = Pedido::getPedidoById($id);
    $payload = json_encode($Pedido);
    if(Pedido::eliminarPedidoById($id) > 0){
      $payload = json_encode(array("mensaje" => "Pedido eliminado con exito"));
      $handler->getBody()->write("Pedido eliminado con exito");
    }
    else{
      $handler->getBody()->write("Algo salio mal");
    }
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $handler){

    $params = $request->getParsedBody();
    $id = $params['id'];
    
    $Pedido = Pedido::getPedidoById($id);
    $Pedido->setPedidoStatus($params['estado']);
    $Pedido->setPedidoCost(Producto::getSumOfProductosByPedido($Pedido->__get("id")));

    if (Pedido::modificarPedido($Pedido) > 0) {
      $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
      $handler->getBody()->write("Pedido modificado con exito");
    }else{
      $handler->getBody()->write("Algo salio mal");
    }
  }
}
?>