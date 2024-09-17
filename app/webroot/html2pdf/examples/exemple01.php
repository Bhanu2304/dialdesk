<?php
    ob_start();
    $clientId = $_GET['ClientId'];
    $FromDate = date_format(date_create($_GET['FromDate']),'Y-m-d');
    $ToDate = date_format(date_create($_GET['ToDate']),'Y-m-d');
    //print_r($_GET['FromDate']); exit;
    include(dirname(__FILE__).'/res/example001.php');
    $content = ob_get_clean();
    require_once(dirname(__FILE__).'/../html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('download'.'.pdf');
    }
  catch(HTML2PDF_exception $e) { echo $e;  exit; }
  header("Content-Type: application/pdf; name='excel'");
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".'download'.".pdf");
  header("Pragma: no-cache");
  header("Expires: 0");

  exit;
?>