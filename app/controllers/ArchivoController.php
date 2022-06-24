<?php

 require_once './models/HistorialEmpleados.php';

 class FileController extends HistorialEmpleados{

  public function Leer($request, $handler){
    $filename = './Reportes/historial_empleados.csv';
    $dataToRead = HistorialEmpleados::ReadCsv($filename);
    $payload = json_encode(array("Error" => 'Algo salio mal'));
    if(!is_null($dataToRead)){
      $payload = json_encode(array("Exito" => 'Achivo insertado', "Historial Empleados" => $dataToRead));
    }
    
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function Escribir($request, $handler){
    $loginsFromDb = HistorialEmpleados::getAll();
    $filename = './Reportes/historial_empleados.csv';
    $payload = json_encode(array("Error" => 'Archivo no guardado',"Historial Empleados" => 'Error escribiendo el archivo'));
    if(HistorialEmpleados::WriteCsv($loginsFromDb, $filename)){
        echo 'Archivo guardado en '.$filename;
        $payload = json_encode(array("Exito" => 'Archivo guardado como historial_empleados.csv',"Historial Empleados" => $loginsFromDb));
    }
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }

  public function DescargarPdf($request, $handler){
    $params = $request->getParsedBody();
    $directory = './Reportes/';
    $payload = json_encode(array("Error" => 'Archivo no guardado',"Mejores encuentas" => 'Error al escribir el archivo'));
    
    if($params['cant_encuestas']){
      $cantEncuestas = $params['cant_encuestas'];
      $payload = Encuesta::DownloadPdf($directory, $cantEncuestas);
      echo 'Archivo guardado en '.$directory;
    }
    
    $handler->getBody()->write($payload);
    return $handler
      ->withHeader('Content-Type', 'application/json');
  }
 }
?>