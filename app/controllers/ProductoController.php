<?php
//require_once './interfaces/IApiUsable.php';
require_once './models/Area.php';
require_once './models/Producto.php';
require_once './models/Pedido.php';

class ProductoController extends Producto {

  public function TraerUno($request, $handler){
    $params = $request->getParsedBody();
    $id = $params['id'];
    $Producto = Producto::getProductoById($id);
    $payload = json_encode($Producto);
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $handler){
      
    $productosList = Producto::getAllProductos();
    $payload = json_encode(array("ProductosList" => $productosList));

    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }
  
  public function CargarUno($request, $handler, $args){
    $params = $request->getParsedBody();
    $area = $params['area'];
    $pedido_id = $params['pedido_asoc'];
    $area = Area::getAreaByName($area);
    $pedido = Pedido::getPedidoById($pedido_id);
    $Producto = Producto::createProducto(
        $area->__get("area_id"), 
        $pedido->__get("id"), 
        $pedido->__get("estado_pedido"), 
        $params['desc'], 
        $params['costo'], 
        date("Y-m-d H:i:s")
    );

    $payload = json_encode($Producto);
    if(Producto::insertProducto($Producto) > 0){

      $pedido_id = $params['pedido_asoc'];
      $pedido = Pedido::getPedidoById($pedido_id);
      $pedido_cost = Producto::getSumOfProductosByPedido($pedido->__get("id"));
      $pedido->__set("cost_pedido",$pedido_cost);
      
      /*if(Pedido::updatePedido($pedido) > 0){
          echo 'El precio total del pro<br>';
          $pedido->printSingleEntityAsMesa();
      }*/

      $payload = json_encode(array("mensaje" => "Producto creado con exito"));
      $handler->getBody()->write("Producto creado con exito");
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
        $id = $params['Id'];
        $Producto = Producto::getProductoById($id);
        $payload = json_encode($Producto);
        if(Producto::eliminarProducto($id) > 0){
            $payload = json_encode(array("mensaje" => "Platillo eliminado con exito"));
            $handler->getBody()->write("Producto eliminado con exito");
        }
        else{
            $handler->getBody()->write("Algo salio mal");
        }
        $handler->getBody()->write($payload);
        return $handler
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $handler, $args){

        $this->TraerTodos($request, $handler, $args);
        
        $params = $request->getParsedBody();
        
        if(isset($params['id_producto']) && isset($params['estado'])){
            $id = $params['id_producto'];
            $estado = $params['estado'];
            $Producto = Producto::getProductoById($id);
            $pedido = Pedido::getPedidoById($Producto->__get("producto_pedido_asoc"));

            $Producto->__set("producto_estado",$estado);
            if(strcmp($estado, 'Listo') == 0){
                $Producto->__set("time_to_finish",0);
            }
        }

        if(isset($params['time_to_finish'])){
            $time_to_finish = $params['time_to_finish'];
            $Producto->__set("time_to_finish",$time_to_finish);
            $Producto->calcularTimeFinished();
        }


        if (isset($pedido) && $pedido->__get("pedido_estado") != 'Listo' && $estado != 'Listo') {
            $pedido->__set("pedido_estado",$estado);
            Pedido::modificarPedido($pedido);
        }

        if (isset($pedido) && $pedido->__get("pedido_estado") != 'Listo' && $estado == 'Listo') {
            $Productos = Producto::getProductosByPedidoId($pedido->__get("id"));

            $filteredProductos = Producto::filterFinishedProductos($Productos, 'Listo');

            if(count($Productos) == count($filteredProductos)){
                
                $mesaid = Mesa::getMesaByPedidoId($pedido->__get("id"));
                $mesaid->__set("estado",'Con Clientes Comiendo');
                Mesa::modificarMesa($mesaid);
            }
        }

        if(isset($Producto) && Producto::modificarProducto($Producto) > 0){
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Algo salio mal"));
        }

        $handler->getBody()->write($payload);
        return $handler
            ->withHeader('Content-Type', 'application/json');
    }
}
?>