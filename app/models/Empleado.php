<?php

require_once './models/Pedido.php';
require_once './db/AccesoDatos.php';

class Empleado{

  public $id;
  public $user_id;
  public $empleado_area_id;
  public $nombre;
  public $date_init;
  public $date_end;

  public function __construct(){}

  public static function createEmpleado($user_id, $empleado_area_id, $nombre, $date_init){
      $empleado = new Empleado();
      $empleado->__set("user_id",$user_id);
      $empleado->__set("empleado_area_id",$empleado_area_id);
      $empleado->__set("nombre",$nombre);
      $empleado->__set("date_init",$date_init);

      return $empleado;
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

  public static function insertEmpleado($Empleado){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("INSERT INTO `empleados` (`user_id`, `empleado_area_id`, `nombre`, `date_init`)
    VALUES (:user_id, :empleado_area_id, :nombre, :date_init);");
    $query->bindValue(':user_id', $Empleado->__get("user_id"));
    $query->bindValue(':empleado_area_id', $Empleado->__get("empleado_area_id"));
    $query->bindValue(':nombre', $Empleado->__get("nombre"));
    $query->bindValue(':date_init', $Empleado->__get("date_init"));
    try {
      $query->execute();
    } catch (\Throwable $th) {
      echo $th->getMessage();
    }
    
    return $objAccesoDatos->obtenerUltimoId();
  }

  public static function getEmpleadoById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM `empleados` WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    
    return $query->fetchObject('Empleado');
  }

  public static function getAllEmpleados(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM `empleados`");
    $query->execute();
    $empleados = $query->fetchAll(PDO::FETCH_CLASS, 'Empleado');

    return $empleados;
  }

  public static function eliminarEmpleado($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("DELETE FROM `empleados` WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->rowCount();
  }

  public static function modificarEmpleado($empleado){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("UPDATE `empleados` SET user_id = :user_id, empleado_area_id = :empleado_area_id, nombre = :nombre WHERE id = :id");
    $query->bindValue(':user_id', $empleado->__get("user_id"));
    $query->bindValue(':empleado_area_id', $empleado->__get("empleado_area_id"));
    $query->bindValue(':nombre', $empleado->__get("nombre"));
    $query->bindValue(':id', $empleado->__get("id"));
    $query->execute();

    return $query->rowCount();
  }

}
?>