<?php

require_once './db/AccesoDatos.php';
require_once './models/Area.php';

class Producto{

  public $id;
  public $producto_area;
  public $producto_pedido_asoc;
  public $producto_estado;
  public $producto_desc;
  public $producto_cost;
  public $time_init;
  public $time_finish;
  public $time_to_finish;

  public function __construct(){}

  public static function createProducto($producto_area, $producto_pedido_asoc, $producto_estado, $producto_desc, $producto_cost, $time_init){
    $Producto = new Producto();
    $Producto->__set("producto_area",$producto_area);
    $Producto->__set("producto_pedido_asoc",$producto_pedido_asoc);
    $Producto->__set("producto_estado",$producto_estado);
    $Producto->__set("producto_desc",$producto_desc);
    $Producto->__set("producto_cost",$producto_cost);
    $Producto->__set("time_init",$time_init);
    $Producto->__set("time_finish",null);
    $Producto->__set("time_to_finish",null);
      
    return $Producto;
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

  public function calcularTimeFinished(){
    $newDate = new DateTime($this->__get("time_init"));
    $newDate = $newDate->modify('+'.$this->__get("time_to_finish").' minutes');
    $this->__set("time_finish",$newDate->format('Y-m-d H:i:s'));
  }

  public static function filterFinishedProductos($entitiesList, $estado){
    $filteredList = array();
    foreach($entitiesList as $entity){
      if(strcmp($entity->__get("producto_estado"), $estado) == 0){
        array_push($filteredList, $entity);
      }
    }
    return $filteredList;
  }

  public static function insertProducto($Producto){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("INSERT INTO `producto` (`producto_area`, `producto_pedido_asoc`, `producto_estado`, `producto_desc`, `producto_cost`, `time_init`) 
    VALUES (:producto_area, :producto_pedido_asoc, :producto_estado, :producto_desc, :producto_cost, :time_init)");
    $query->bindValue(':producto_area', $Producto->__get("producto_area"));
    $query->bindValue(':producto_pedido_asoc', $Producto->__get("producto_pedido_asoc"));
    $query->bindValue(':producto_estado', $Producto->__get("producto_estado"));
    $query->bindValue(':producto_desc', $Producto->__get("producto_desc"));
    $query->bindValue(':producto_cost', $Producto->__get("producto_cost"));
    $query->bindValue(':time_init', $Producto->__get("time_init"));
    $query->execute();

    return $objAccesoDatos->obtenerUltimoId();
  }

  public static function modificarProducto($Producto){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("UPDATE `producto` 
    SET `producto_estado` = :estado, `time_finish` = :time_finish, `time_to_finish` = :time_to_finish 
    WHERE `id` = :id");
    $query->bindValue(':estado', $Producto->__get("producto_estado"));
    $query->bindValue(':time_finish', $Producto->__get("time_finish"));
    $query->bindValue(':time_to_finish', $Producto->__get("time_to_finish"));
    $query->bindValue(':id', $Producto->__get("id"));
    $query->execute();

    return $query->rowCount();
  }

  public static function getAllProductos(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM `producto`");
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
  }

  public static function getProductoById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM `producto` WHERE `id` = :id");
    $query->bindParam(':id', $id);
    $query->execute();

    return $query->fetchObject("Producto");
  }

  public static function getProductosByUserType($userType){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta(
      "SELECT 
      pr.id AS id,
      pr.producto_area AS producto_area,
      pr.producto_pedido_asoc AS producto_pedido_asoc,
      pr.producto_estado AS producto_estado,
      pr.producto_desc AS producto_desc,
      pr.producto_cost AS producto_cost,
      pr.time_init AS time_init,
      pr.time_finish AS time_finish,
      pr.time_to_finish AS time_to_finish
      FROM producto AS pr
      WHERE pr.producto_area = :user_type;");
    $query->bindParam(':user_type', $userType);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
  }

  public static function getProductosByPedidoId($pedidoId){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta(
      "SELECT 
      pr.id AS id,
      pr.producto_area AS producto_area,
      pr.producto_pedido_asoc AS producto_pedido_asoc,
      pr.producto_estado AS producto_estado,
      pr.producto_desc AS producto_desc,
      pr.producto_cost AS producto_cost,
      pr.time_init AS time_init,
      pr.time_finish AS time_finish,
      pr.time_to_finish AS time_to_finish
      FROM producto AS pr
      WHERE pr.producto_pedido_asoc = :pedido_id;"
    );
    $query->bindParam(':pedido_id', $pedidoId);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
  }

  public static function eliminarProducto($Producto){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("DELETE FROM `producto` WHERE `id` = :id");
    $query->bindValue(':id', $Producto->__get("id"));
    $query->execute();

    return $query->rowCount();
  }

  public static function getSumOfProductosByPedido($pedidoId){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT SUM(pr.producto_cost) AS total FROM `producto` AS pr WHERE `producto_pedido_asoc` = :pedido_id");
    $query->bindParam(':pedido_id', $pedidoId);
    $query->execute();

    return $query->fetchObject()->total;
  }
}
?>