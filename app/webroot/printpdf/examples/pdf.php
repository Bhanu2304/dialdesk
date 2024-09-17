<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);
set_time_limit(600);

    $pdf = date('Ymd_His');
	$pdffolder = mkdir("../../pdffile/".$pdf, 0777);
	$Month = $_GET['Month'];
$Year = $_GET['Year'];  
$empcode=explode(",",$_GET['empCodes']);
//	print_r($empcode); exit;
	
	
	
	foreach($empcode as $track)  {
	ob_start();
    include(dirname(__FILE__).'/res/example0001.php');
    $content = ob_get_clean();
	//$FileName = mysql_fetch_array(mysql_query("select MobileNumber from tbl_datamaster where data_id='$track'"));
	//$FilePDF = $FileName['MobileNumber'];

    require_once(dirname(__FILE__).'/../html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('../../pdffile/'.$pdf.'/'.$track.'.pdf','F');
    }
  catch(HTML2PDF_exception $e) { echo $e; exit; }
 }
 
 header('location:../../download.php?pdf='.$pdf);


 
 
 
 
 
?>