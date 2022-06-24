<?php

class HistorialEmpleados{
  public $id;
  public $user_id;
  public $username;
  public $date_login;

  public function __construct() {}

  public static function createHistorial($user_id, $username, $date_login){
      $historialEmpleado = new HistorialEmpleados();
      $historialEmpleado->__set("user_id",$user_id);
      $historialEmpleado->__set("username",$username);
      $historialEmpleado->__set("date_login",$date_login);
      
      return $historialEmpleado;
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

  public static function ReadCsv($filename="./Reportes/historial_empleados.csv"){
    $file = fopen($filename, "r");
    $array = array();
    try {
      while (!feof($file)) {
        $line = fgets($file);
        
        if (!empty($line)) {
          $line = str_replace(PHP_EOL, "", $line);
          $loginsArray = explode(",", $line);
          $hLogin = HistorialEmpleados::createHistorial($loginsArray[0], $loginsArray[1], $loginsArray[2]);
          array_push($array, $hLogin);
          HistorialEmpleados::insertHistoricalLogin($hLogin);
        }
      }
    } catch (\Throwable $th) {
      echo "Error while reading the file";
    }finally{
      fclose($file);
      return $array;
    }
  }

  public static function WriteCsv($entitiesList, $filename = './Reportes/historial_empleados.csv'):bool{
    $success = false;
    $directory = dirname($filename, 1);
    
    try {
      if(!file_exists($directory)){
        mkdir($directory, 0777, true);
      }
      $file = fopen($filename, "w");
      if ($file) {
        foreach ($entitiesList as $entity) {
          $line = $entity->__get("user_id") . "," . $entity->__get("username") . "," . $entity->__get("date_login") . PHP_EOL;
          fwrite($file, $line);
          $success = true;
        }
      }
    } catch (\Throwable $th) {
      echo "Error saving the file<br>";
    }finally{
      fclose($file);
    }

    return $success;
  }

  public static function insertHistoricalLogin($historicalLogin){
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $query = $objAccesoDatos->prepararConsulta("INSERT INTO `historial_empleados` (user_id, username, date_login) 
      VALUES (:user_id, :username, :date_login);");
      $query->bindValue(':user_id', $historicalLogin->__get("user_id"), PDO::PARAM_INT);
      $query->bindValue(':username', $historicalLogin->
      __get("username"), PDO::PARAM_STR);
      $query->bindValue(':date_login', $historicalLogin->__get("date_login"), PDO::PARAM_STR);
      $query->execute();

      return $objAccesoDatos->obtenerUltimoId();
  }

  public static function getHistorialById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("SELECT * FROM `historial_empleados` WHERE id = :id;");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_CLASS, 'HistorialEmpleados');
  }

  public static function getAll(){
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $query = $objAccesoDatos->prepararConsulta("SELECT * FROM `historial_empleados`;");
      $query->execute();
      return $query->fetchAll(PDO::FETCH_CLASS, 'HistorialEmpleados');
  }

  public static function deleteHistorialById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("DELETE FROM `historial_empleados` WHERE id = :id;");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->rowCount() > 0;
  }

  public static function deleteTable(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta("DELETE FROM `historial_empleados` WHERE 1=1;");
    $query->execute();

    return $query->rowCount() > 0;
  }
}
?>