<?php

require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';

class Pedido{

  public $id;
  public $id_mesa;
  public $estado_pedido;
  public $nombre_cliente;
  public $img_pedido;
  public $cost_pedido;

  public function __construct(){}

  public static function createPedido($id_mesa, $estado_pedido, $nombre_cliente, $img_pedido, $cost_pedido = 0){
    $newPedido = new Pedido();
    $newPedido->__set("id_mesa",$id_mesa);
    $newPedido->__set("estado_pedido",$estado_pedido);
    $newPedido->__set("nombre_cliente",$nombre_cliente);
    $newPedido->__set("img_pedido",$img_pedido);
    $newPedido->__set("cost_pedido",$cost_pedido);

    return $newPedido;
  }

  public function __get($property){
    if(property_exists($this,$property)){
      return $this->$property;
    }
  }

  public function __set($property, $value){
    if(property_exists($this,$property)){
      $this->$property = $value;
    }
  }

  public static function insertPedido($Pedido){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('INSERT INTO pedidos (id_mesa, estado_pedido, nombre_cliente, img_pedido, cost_pedido) 
    VALUES (:id_mesa, :estado_pedido, :nombre_cliente, :img_pedido, :cost_pedido)');
    $query->bindValue(':id_mesa', $Pedido->__get("id_mesa"));
    $query->bindValue(':estado_pedido', $Pedido->__get("estado_pedido"));
    $query->bindValue(':nombre_cliente', $Pedido->__get("nombre_cliente"));
    $query->bindValue(':img_pedido', $Pedido->__get("img_pedido"));
    $query->bindValue(':cost_pedido', $Pedido->__get("cost_pedido"));
    $query->execute();

    return $objAccesoDatos->obtenerUltimoId();
  }

  public static function getAll(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM pedidos');
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
  }

  public static function getPedidoById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM pedidos WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();

    return $query->fetchObject('Pedido');
  }

  public static function getPedidosByMesa($mesa){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM pedidos WHERE id_mesa = :id_mesa');
    $query->bindParam(':id_mesa', $mesa);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
  }

  public static function getPedidosByEmpleado($empleado){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT p.id, p.id_mesa, p.estado_pedido 
    FROM pedidos AS p
    LEFT JOIN mesas AS m ON p.id_mesa = m.id
    LEFT JOIN empleados AS e ON m.id_empleado = :id');
    $query->bindValue(':id', $empleado->__get("id"));
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
  }

  public static function getPedidosByUserType($type){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT p.id, p.id_mesa, p.estado_pedido 
    FROM pedidos AS p
    LEFT JOIN mesas AS m ON p.id_mesa = m.id
    LEFT JOIN empleados AS e ON m.id_empleado = e.id
    LEFT JOIN usuarios AS u ON e.user_id = u.id
    WHERE u.user_type = :type;');
    $query->bindParam(':type', $type);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
  }

  public static function modificarPedido($Pedido){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('UPDATE pedidos 
    SET estado_pedido = :estado_pedido, cost_pedido = :cost_pedido 
    WHERE id = :id');
    $query->bindValue(':id', $Pedido->__get("id"));
    $query->bindValue(':estado_pedido', $Pedido->__get("estado_pedido"));
    $query->bindValue(':cost_pedido', $Pedido->__get("cost_pedido"));
    $query->execute();

    return $query->rowCount() > 0;
  }

  public static function updatePicture($pedido){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('UPDATE pedidos SET img_pedido = :img_pedido WHERE id = :id');
    $query->bindValue(':id', $pedido->__get("id"));
    $query->bindValue(':img_pedido', $pedido->__get("img_pedido"));
    $query->execute();

    return $query->rowCount() > 0;
  }

  public static function eliminarPedidoById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('DELETE FROM pedidos WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();
    
    return $objAccesoDatos->rowCount() > 0;
  }

  public static function getByMesaId($id_mesa){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM pedidos WHERE id_mesa = :id_mesa');
    $query->bindParam(':id_mesa', $id_mesa);
    $query->execute();

    return $query->fetchObject('Pedido');
  }

  public static function getMaxTimePedidoByMesaCode($pedido_id, $mesa_code){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta(
      'SELECT 
      MAX(pr.time_to_finish) AS time_pedido 
      FROM producto AS pr
      LEFT JOIN pedidos as p
      ON pr.producto_pedido_asoc = :pedido_id
      LEFT JOIN mesas AS m
      ON p.id_mesa = m.id
      WHERE m.mesa_code = :mesa_code');
    $query->bindParam(':mesa_code', $mesa_code);
    $query->bindParam(':pedido_id', $pedido_id);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getPedidosWithTime(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta(
      'SELECT 
      p.id,
      p.id_mesa,
      p.estado_pedido,
      p.nombre_cliente,
      p.img_pedido,
      p.cost_pedido,
      MAX(pr.time_to_finish) AS waiting_time
      FROM producto AS pr
      LEFT JOIN pedidos as p
      ON pr.producto_pedido_asoc = p.id
      GROUP BY p.id
      pedido by waiting_time DESC;');
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, "stdClass");
  }

  public static function getByStatus($estado_pedido){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM pedidos WHERE estado_pedido = :estado_pedido');
    $query->bindParam(':estado_pedido', $estado_pedido);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
  }

  public static function getByStatusAndMesaId($estado_pedido, $id_mesa){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE estado_pedido = :estado_pedido AND id_mesa = :id_mesa");
    $query->bindParam(':estado_pedido', $estado_pedido);
    $query->bindParam(':id_mesa', $id_mesa);
    $query->execute();

    return $query->fetchObject('Pedido');
  }
}
?>