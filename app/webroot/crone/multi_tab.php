<?php


$db1=mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk',$db1) or die('unable to connect');

include("PHPExcel/Classes/PHPExcel/IOFactory.php");
include("combay_overall.php");

function mergeCellsT($sheet,$cell)
{
  return  $sheet->mergeCells($cell);
}
function textCenter($sheet,$cell)
{
    $styleCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
        $sheet->getStyle($cell)->applyFromArray($styleCenter); 
    return  $sheet;
}
function textBold($sheet,$cell)
{
    $sheet->getStyle($cell)->getFont()->setBold(true);
    return  $sheet;
}
function textWrap($sheet,$cell)
{
    $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
    return  $sheet;
}

function textWidth($sheet,$cell,$width)
{
    $sheet->getColumnDimension($cell)->setWidth($width);
    return  $sheet;
}

function cellBorder($sheet,$cell)
{
    $styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );
        $sheet->getStyle($cell)->applyFromArray($styleArray); 
    return  $sheet;
}
function cellColor($sheet,$cell)
{
    $sheet->getStyle($cell)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '99A3A4')
        )
    )
);
    return  $sheet;
}
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br/>');
    $ext = explode('.','pwc_dqr_report_13_10_2017_21_33_02data.xls');
    $count = count($ext);
    $ext1 = $ext[$count-1];
    if(strtolower ($ext[$count-1])=='xls')
    {
        $fileVersion = 'Excel5';
    }
    


$sheet1 = 0;
$array = array('overview','Qualified leads report','other potentials','DQR','Export Report');

//var_dump(get_class_methods ($objPHPExcel->setActiveSheetIndex($sheet))); exit;
//print_r(overview()); exit;
$objPHPExcel = new PHPExcel(); 
foreach($array as $value){

        $sheet = $objPHPExcel->setActiveSheetIndex(0)->setTitle("$value");
        if($value=='overview')
        {
            /// Start of Overview ////////////////
            $overview = overview();
          //  print_r($overview);exit;
            $i=1;
            foreach($overview as $over)
            {
                echo $over;
                $sheet->fromArray($over, NULL, 'A'.$i);
               echo $i++;
            } 
            $sheet = mergeCellsT($sheet,"A1:E1");
            $sheet = textCenter($sheet,"A1:E1");
            $sheet = textBold($sheet,"A1:E".($i+1));
            $sheet = cellBorder($sheet,"A$start:E".($i-1));
            $sheet = cellColor($sheet,"A$i:E".($i+1));
            $sheet = textCenter($sheet,"A".($i-1).":E".($i-1));
            $sheet = textBold($sheet,"A".($i-1).":E".($i-1));
            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
            
          
            
            /// Start of Non Contactable ////////////////
//            $start = $i;
//            $noncontactablereason = noncontactablereason();
//            foreach($noncontactablereason as $over)
//            {
//                $sheet->fromArray($over, NULL, 'A'.$i);
//                $i++;
//            }
//            
//            $sheet = mergeCellsT($sheet,"A$start:E$start");
//            $sheet = textCenter($sheet,"A$start:E$start");
//            $sheet = textBold($sheet,"A$start:E".($start+1));
//            $sheet = cellBorder($sheet,"A$start:E".($i-1));
//            $sheet = cellColor($sheet,"A$start:E".($start+1));
//            $sheet = textCenter($sheet,"A".($i-1).":E".($i-1));
//            $sheet = textBold($sheet,"A".($i-1).":E".($i-1));
//            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
//            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
//            
//            /// Start of Lead Validation ////////////////
//            $start = $i;
//            $leadvalidationoverview = leadvalidationoverview();
//            foreach($leadvalidationoverview as $over)
//            {
//                $sheet->fromArray($over, NULL, 'A'.$i);
//                $i++;
//            }
//            
//            $sheet = mergeCellsT($sheet,"A$start:F$start");
//            $sheet = mergeCellsT($sheet,"A".($start+3).":A".($start+5));
//            $sheet = mergeCellsT($sheet,"A".($start+7).":A".($start+9));
//            $sheet = mergeCellsT($sheet,"A".($start+11).":A".($start+13));
//            $sheet = textCenter($sheet,"A$start:F$start");
//            $sheet = textBold($sheet,"A$start:F".($start+1));
//            $sheet = cellBorder($sheet,"A$start:F".($i-1));
//            $sheet = cellColor($sheet,"A$start:F".($start+1));
//            $sheet = textCenter($sheet,"A".($i-1).":F".($i-1));
//            $sheet = textBold($sheet,"A".($i-1).":F".($i-1));
//            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
//            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
//            
//            
//            /// Start of Interested in Mercedes
//            $start = $i;
//            $interestedmercedes = interestedmercedes();
//            foreach($interestedmercedes as $over)
//            {
//                $sheet->fromArray($over, NULL, 'A'.$i);
//                $i++;
//            }
//            
//            $sheet = mergeCellsT($sheet,"A$start:E$start");
//            $sheet = textCenter($sheet,"A$start:E$start");
//            $sheet = textBold($sheet,"A$start:E".($start+1));
//            $sheet = cellBorder($sheet,"A$start:E".($i-1));
//            $sheet = cellColor($sheet,"A$start:E".($start+1));
//            $sheet = textCenter($sheet,"A".($i-3).":E".($i-3));
//            $sheet = textBold($sheet,"A".($i-3).":E".($i-3));
//            $sheet = textCenter($sheet,"A".($i-1).":E".($i-1));
//            $sheet = textBold($sheet,"A".($i-1).":E".($i-1));
//            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
//            $sheet->fromArray(array("",""), NULL, 'A'.$i++);
        }
        $sheet->setTitle("$value");
    }
    $sheet1++;
    

//echo "<pre>";
//print_r($sheet);die;
//echo "</pre>";
$fileName = "overall_report_".date('Y_m_d_h_i').".xls"; 
$savePath = "/var/www/html/dialdesk/app/webroot/crone/comway/$fileName";
$objw = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileVersion);
$objw->save($savePath);


