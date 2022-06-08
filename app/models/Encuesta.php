<?php

//use Fpdf\Fpdf;

class Encuesta {
  public $id;
  public $pedido_id;
  public $mesa_punt;
  public $resto_punt;
  public $mozo_punt;
  public $cocinero_punt;
  public $promedio_punt;
  public $comentario;

  public function __construct() {}

  public static function createEncuesta($pedido_id, $mesa_punt, $resto_punt, $mozo_punt, $cocinero_punt, $comentario) {
    $encuesta = new Encuesta();
    $encuesta->__set("pedido_id",$pedido_id);
    $encuesta->__set("mesa_punt",$mesa_punt);
    $encuesta->__set("resto_punt",$resto_punt);
    $encuesta->__set("mozo_punt",$mozo_punt);
    $encuesta->__set("cocinero_punt",$cocinero_punt);
    $encuesta->setPromedioPuntuacion();
    $encuesta->__set("comentario",$comentario);

    return $encuesta;
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

  public function setPromedioPuntuacion() {
    $promedio = 0;
    $arraySum = array($this->mesa_punt, $this->resto_punt, $this->mozo_punt, $this->cocinero_punt);
    if(count($arraySum) > 0) {
      $promedio = round(array_sum($arraySum) / count($arraySum), 2, PHP_ROUND_HALF_EVEN);
    }
    $this->promedio_punt = $promedio; 
  }

  /*public static function DownloadPdf($directory, $amountEncuestas){
    $Encuestas = self::getBestEncuestas($amountEncuestas);
    if ($Encuestas) {
      if(!file_exists($directory)){
        mkdir($directory, 0777, true);
      }


      $pdf = new FPDF();
      $pdf->AddPage();

      // Letter type size
      $pdf->SetFont('Arial', 'B', 25);

      // Main title of the pdf
      $pdf->Cell(160, 15, 'Comanda', 1, 3, 'L');
      $pdf->Ln(3);

      $pdf->SetFont('Arial', '', 15);

      // Secondary title of the pdf
      $pdf->Cell(60, 4, 'TP Final Programacion III', 0, 1, 'L');
      $pdf->Cell(60, 0, '', 'T');
      $pdf->Ln(3);
      
      // Title of the table
      $pdf->Cell(60, 4, 'Facundo Falcone', 0, 1, 'L');
      $pdf->Cell(40, 0, '', 'T');
      $pdf->Ln(5);

      // Columns of Encuesta Class
      $header = array('ID', 'ORDER', 'T_SCORE', 'R_SCORE', 'W_SCORE', 'C_SCORE', 'promedio', 'comentario');
      
      // RGB colors of the table
      $pdf->SetFillColor(125, 0, 0);
      $pdf->SetTextColor(125);
      $pdf->SetDrawColor(50, 0, 0);
      $pdf->SetLineWidth(.3);
      $pdf->SetFont('Arial', 'B', 8);
      $w = array(10, 12, 15, 15, 15, 15, 15, 92);
        
      for ($i = 0; $i < count($header); $i++) {
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
      }
      $pdf->Ln();

      // Set the color of the text
      $pdf->SetFillColor(215, 209, 235);
      $pdf->SetTextColor(0);
      $pdf->SetFont('');
      // Data
      $fill = false;

      foreach ($Encuestas as $Encuesta) {
        $pdf->Cell($w[0], 6, $Encuesta->getId(), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[1], 6, $Encuesta->__get("pedido_id"), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[2], 6, $Encuesta->__get("mesa_punt"), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[3], 6, $Encuesta->__get("resto_punt"), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[4], 6, $Encuesta->__get("mozo_punt"), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[5], 6, $Encuesta->getCheffScore(), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[6], 6, $Encuesta->__get("promedio_punt"), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[7], 6, $Encuesta->__get("comentario"), 'LR', 0, 'C', $fill);
        $pdf->Ln();
        $fill = !$fill;
      }

      $pdf->Cell(array_sum($w), 0, '', 'T');

      $newFilename = $directory.'Encuestas_' . date('Y_m_d') .'.pdf';
      $pdf->Output('F', $newFilename, 'I');

      $payload = json_encode(array("message" => 'pdf created ' . $newFilename));
    } else {
      $payload = json_encode(array("error" => 'error getting data'));
    }
    
    return $payload;
  }*/

  public static function insertEncuesta($Encuesta){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('INSERT INTO `encuestas` (pedido_id, mesa_punt, resto_punt, mozo_punt, cocinero_punt, promedio_punt, comentario) 
    VALUES (:pedido_id, :mesa_punt, :resto_punt, :mozo_punt, :cocinero_punt, :promedio_punt, :comentario)');
    $query->bindValue(':pedido_id', $Encuesta->__get("pedido_id"));
    $query->bindValue(':mesa_punt', $Encuesta->__get("mesa_punt"));
    $query->bindValue(':resto_punt', $Encuesta->__get("resto_punt"));
    $query->bindValue(':mozo_punt', $Encuesta->__get("mozo_punt"));
    $query->bindValue(':cocinero_punt', $Encuesta->__get("cocinero_punt"));
    $query->bindValue(':promedio_punt', $Encuesta->__get("promedio_punt"));
    $query->bindValue(':comentario', $Encuesta->__get("comentario"));
    $query->execute();

    return $objAccesoDatos->obtenerUltimoId();
  }

  public static function getAll(){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM `encuestas`');
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
  }

  public static function getEncuestaById($id){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta('SELECT * FROM `encuestas` WHERE id = :id');
    $query->bindParam(':id', $id);
    $query->execute();

    return $query->fetchObject('Encuesta');
  }

  public static function mejoresEncuestas($cantidad){
    $objAccesoDatos = AccesoDatos::obtenerInstancia();
    $query = $objAccesoDatos->prepararConsulta(
        'SELECT * FROM `encuestas` 
        ORDER BY promedio_punt DESC 
        LIMIT :cantidad');
    $query->bindParam(':cantidad', $cantidad);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
  }
}
?>