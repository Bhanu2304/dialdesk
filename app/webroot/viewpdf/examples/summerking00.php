<?php
ini_set('max_execution_time', 0); //300 seconds = 5 minutes
    ob_start();
    //$Month = $_GET['start_date'];
    //$Year = $_GET['end_date'];
    
    //print_r($_GET['FromDate']); exit;
    include(dirname(__FILE__).'/res/summerking000.php');
    $content = ob_get_clean();
    require_once(dirname(__FILE__).'/../html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        //$html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('download'.'.pdf');
    }
  catch(HTML2PDF_exception $e) { echo $e;  exit; }
  /*
  header("Content-Type: application/pdf; name='excel'");
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".'download'.".pdf");
  header("Pragma: no-cache");
  header("Expires: 0");
   */
  exit;
?>