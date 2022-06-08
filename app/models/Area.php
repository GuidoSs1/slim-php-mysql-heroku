<?php

require_once './db/AccesoDatos.php';

 class Area {
  public $area_id;
  public $area_desc;
  public static $AREA_JOBS = array(
      'Cervecero' => 3,
      'Cocinero' => 2,
      'Bartender' => 1,
      'Cocinero' => 5,
      'Admin' => 4
  );

  public function __construct(){}
  
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

  public Static function getAreaByJobs($job){
    return intval(self::$AREA_JOBS[$job]);
  }

  public function insertArea(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $sql = "INSERT INTO area (area_desc) VALUES (:area_desc);";
    $query = $objAccesoDatos->prepararConsulta($sql);
    $query->bindValue(':area_desc', $this->__get("area_desc"));
    $query->execute();

    return $objAccesoDatos->obtenerUltimoId();
  }

  public static function updateArea($area){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $sql = "UPDATE area SET area_desc = ':area_desc' WHERE area_id = :area_id;";
    $query = $objAccesoDatos->prepararConsulta($sql);
    $query->bindValue(':area_id', $area->__get("area_id"));
    $query->bindValue(':area_desc', $area->__get("area_desc"));
    return $query->execute();
  }

  public static function deleteArea($area){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $sql = "DELETE FROM area WHERE area_id = :area_id";
    $query = $objAccesoDatos->prepararConsulta($sql);
    $query->bindValue(':area_id', $area->__get("area_id"));
    return $query->execute();
  }

  public static function getAreaById($area_id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM area WHERE area_id = :area_id;");
    $query->bindParam(':area_id', $area_id);
    $query->execute();
    $area = $query->fetchObject('Area');
    if(is_null($area)){
      throw new Exception("The area doesn't exist.");
    }
    
    return $area;
  }

  public static function getAreaByName($area_name){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT area_id, area_desc FROM area WHERE area_desc = :area_desc;");
    $query->bindParam(':area_desc', $area_name);
    $query->execute();
    $area = $query->fetchObject('Area');
    
    return $area;
  }

  public static function getAllAreas(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $sql = "SELECT * FROM area;";
    $query = $objAccesoDatos->prepararConsulta($sql);
    $query->execute();
    $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Area');
    return $areas;
  }

  public static function getAreasByDescription($area_desc){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $sql = "SELECT * FROM area WHERE area_desc = ':area_desc';";
    $query = $objAccesoDatos->prepararConsulta($sql);
    $query->bindParam(':area_desc', $area_desc);
    $query->execute();
    $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Area');
    return $areas;
  } 
 }
?>