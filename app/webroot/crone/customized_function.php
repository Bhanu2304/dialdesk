<?php 
session_start();

function customized_report($CallDate,$ClientId,$name,$email,$filename,$sub){
    $filename = "/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".$filename.date('d_m_Y_H_i_s')."_Export.xls";
    
    require_once('Classes/PHPExcel.php');
    $objPHPExcel = new PHPExcel();    
   
    $qry=mysql_query("SELECT id,tab_name,tab_field FROM `report_tab_master` WHERE client_id='$ClientId' AND tab_status='A' ORDER BY tab_order");
    
    $i=0;
    $header=array();
    $value=array();
    while($v1=mysql_fetch_assoc($qry)){
        
        $tab_id     =   $v1['id'];
        $sheetname  =   $v1['tab_name'];
        $tabfields  =   $v1['tab_field']; 
        $header_arr =   getheader($tab_id);
        $header     =   $header_arr['header'];
        $fields     =   implode(",", $header_arr['fields']);
        $condition  =   "SELECT $fields FROM call_master WHERE $CallDate AND ClientId='$ClientId' AND $tabfields='$sheetname'";
        $value      =   getdata($condition);
        $DataArray  =   array_merge(array($header),$value);
        
        if($i > 0){   
            $objPHPExcel->createSheet();  
        }

        $objWorksheet=$objPHPExcel->setActiveSheetIndex($i); 
        $objPHPExcel->getActiveSheet()->setTitle($sheetname);

        $array  =   array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
            $col    =   count($header);
            $a      =   round($col/26,0);
            $b      =   $col%26;
            
            if(count($header) ==26){$ch ="Z1";}else{$ch = $array[$a].$array[$b].'1';}

            $objPHPExcel->getActiveSheet(0)->getStyle("A1:$ch")->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '008B8B'),

                )
            ))->getFont()->setBold(true)
                                    ->setName('Verdana')
                                    ->setSize(10)
                                    ->getColor()->setRGB('fffff');
            
            $objPHPExcel->getActiveSheet()->getStyle("A1:$ch".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);    
            foreach($array as $key=>$val){
                $objPHPExcel->getActiveSheet()->getColumnDimension($val)->setWidth(20);
            }

            //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);      
            $objWorksheet->fromArray($DataArray);


        $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0);

    ob_end_clean();
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->setIncludeCharts(TRUE);
    $objWriter->save(str_replace(__FILE__,$filename,__FILE__));
    
    $ReceiverEmail  =   array('Email'=>$email,'Name'=>$name); 
    $SenderEmail    =   array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     =   array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Attachment     =   array( $filename); 
    $Subject        =   $sub;
    
    $dated = date('d-M',strtotime('-1 days'));
    $EmailText ='';
    $EmailText .="<table><tr><td style=\"padding-left:12px;\">Dear $name</td></tr>"; 
    $EmailText .="<table><tr><td style=\"padding-left:12px;\"></td></tr>"; 
    $EmailText .="<tr><td style=\"padding-left:12px;\">Please find the enclosed Customized Report updated till $dated.</td></tr>";
    $EmailText .="</table>"; 
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'Attachment'=>$Attachment);
    
    try{
        $done = send_email( $emaildata);
    }
    catch (Exception $e){
        $error = $e.printStackTrace();
        $updQry = "insert into error_master(data_id,process,error_msg,createdate) values('{$ClientId}','{$sub}','{$error}',now())";
        mysql_query($updQry);
    }
    if($done=='1'){
        $msg =  "Mail Sent Successfully !";        
        $updQry2 = "insert into mail_log_report(clientId,mail_status,mail_date)values('$ClientId','$done',now()) ";
        mysql_query($updQry2);
    }  
    die;
}
    
function getheader($tab_id){ 
    $qry=mysql_query("SELECT header_name,header_field FROM `report_header_master` WHERE tab_id='$tab_id' AND header_status='A' ORDER BY header_order");
    while($v2=mysql_fetch_assoc($qry)){
        $data['header'][]=$v2['header_name'];
        $data['fields'][]=$v2['header_field'];
    }
    return $data; 
}
    
function getdata($query){
    $value=array();
    $qry=mysql_query($query);
    while($v3=mysql_fetch_assoc($qry)){
        $value[]=$v3;
    }
    return $value;
}
        
?>