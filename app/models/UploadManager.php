<?php

require_once 'Pedido.php';

class UploadManager{

  private $_DIR_TO_SAVE;
  private $_fileExtension;
  private $_newFileName;
  private $_pathToSaveImage;


  public function __construct($dirToSave, $pedido_id, $array)
  {
    self::createDirIfNotExists($dirToSave);
    $this->__set("_DIR_TO_SAVE",$dirToSave);
    $this->saveFileIntoDir($pedido_id, $array);
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

  public function setPathToSaveImage(){
    $this->_pathToSaveImage = $this->__get("_DIR_TO_SAVE").'Pedido_'.$this->
    __get("_newFileName").'.'.$this->__get("_fileExtension");
  }  

  public static function getPedidoImageNameExt($fileManager, $id){
    $fullpath = $fileManager->__get("_pathToSaveImage");
    return $fullpath;
  }

  private static function createDirIfNotExists($dirToSave){
    if (!file_exists($dirToSave)) {
      mkdir($dirToSave, 0777, true);
    }
  }

  public function saveFileIntoDir($pedido_id, $array):bool{
    $success = false;
    
    try {
      $this->__set("_newFileName",$pedido_id);
      $this->__set("_fileExtension",'png');
      $this->setPathToSaveImage();
      if ($this->moveUploadedFile($array['pedido_pic']['tmp_name'])) {
        $success = true;
      }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }finally{
        return $success;
    }
  }

  public function moveUploadedFile($tmpFileName){
      return move_uploaded_file($tmpFileName, $this->__get("_pathToSaveImage"));
  }

  public static function moveImageFromTo($oldDir, $newDir, $fileName){
    self::createDirIfNotExists($newDir);
    return rename($oldDir.$fileName, $newDir.$fileName);
  }
}
?>