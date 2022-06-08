<?php

 class Mesa {
  public $id;
  public $mesa_code;
  public $id_empleado;
  public $estado;

  public function __construct() {}

  public static function createMesa($mesa_code, $id_empleado, $estado) {
    $mesa = new Mesa();
    $mesa->__set("mesa_code",$mesa_code);
    $mesa->__set("id_empleado",$id_empleado);
    $mesa->__set("estado",$estado);

    return $mesa;
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

  public static function getMesasByEmployeeId($id_empleado){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM mesas WHERE id_empleado = :id_empleado');
    $query->bindParam(':id_empleado', $id_empleado);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
  }

  public static function getAllMesas(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM mesas');
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
  }

  public static function getMesaById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM mesas WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();

    return $query->fetchObject('Mesa');
  }

  public static function insertMesa($Mesa){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('INSERT INTO mesas (mesa_code, id_empleado, estado) 
    VALUES (:mesa_code, :id_empleado, :estado)');
    $query->bindValue(':mesa_code', $Mesa->__get("mesa_code"));
    $query->bindValue(':id_empleado', $Mesa->__get("id_empleado"));
    $query->bindValue(':estado', $Mesa->__get("estado"));
    $query->execute();

    return $objAccesoDatos->obtenerUltimoId();
  }

  public static function modificarMesa($mesa){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('UPDATE mesas SET id_empleado = :id_empleado, state = :estado WHERE id = :id');
    $query->bindValue(':id_empleado', $mesa->__get("id_empleado"), PDO::PARAM_INT);
    $query->bindValue(':estado', $mesa->__get("estado"), PDO::PARAM_STR);
    $query->bindValue(':id', $mesa->__get("id"), PDO::PARAM_INT);
    $query->execute();

    return $query->rowCount() > 0;
  }

  public static function getClosedMesa(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM mesas WHERE state = "Cerrada" LIMIT 1;');
    $query->execute();

    return $query->fetchObject('Mesa');
  }

  public static function getMesaByPedidoId($pedido_id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta(
        'SELECT * FROM mesas
        WHERE id = (SELECT mesa_id FROM pedidos WHERE id = :pedido_id)');
    $query->bindParam(':pedido_id', $pedido_id);
    $query->execute();

    return $query->fetchObject('Mesa');
  }

  public static function initMesaStatus($estado = 'Cerrada'){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $freeMesa = self::getClosedMesa();
    if($freeMesa){
        $query = $objAccesoDatos->prepararConsulta('UPDATE mesas SET estado = :estado WHERE id = :id;');
        $query->bindParam(':estado', $estado, PDO::PARAM_STR);
        $query->bindValue(':id', $freeMesa->__get("id"), PDO::PARAM_INT);
        $query->execute();
        return $freeMesa->__get("id");
    }
    return 0;
  }

  public static function updateMesaStatus($mesa, $estado){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('UPDATE mesas SET estado = :estado WHERE id = :id;');
    $query->bindParam(':estado', $estado, PDO::PARAM_STR);
    $query->bindValue(':id', $mesa->__get("id"), PDO::PARAM_INT);
    $query->execute();
    return $query->rowCount() > 0;
  }

  public static function eliminarMesa($mesa){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('DELETE FROM mesas WHERE id = :id');
    $query->bindValue(':id', $mesa->__get("id"));
    $query->execute();

    return $query->rowCount() > 0;
  }
 }
?>