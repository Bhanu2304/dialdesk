<?php
$NewField = explode(',',$Data[0][0]['CatField']);
$ArrCnt   = count($NewField);
for ($i = 0; $i < $ArrCnt; $i++) {
    $out .= '"'.$NewField[$i].'",';
  }
 // $out .="\n";
  header("Content-type: text/x-csv");
  header("Content-type: text/csv");
  header("Content-type: application/csv");
  header("Content-Disposition: attachment; filename=import_format_for_upload_existing_base.csv");
  echo $out;
  exit;  
 ?> 