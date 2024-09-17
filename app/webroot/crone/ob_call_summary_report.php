<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$db2 = mysqli_connect("localhost","root","dial@mas123") or die(mysqli_error($db2));
mysqli_select_db($db2,"db_dialdesk")or die("cannot select DB dialdesk");



$db6 = mysqli_connect("192.168.10.5","root","vicidialnow") or die(mysqli_error($db6));
mysqli_select_db($db6,"asterisk")or die("cannot select DB dialdesk");

require_once('Classes/PHPExcel.php');


//include('function_alert_mechanism.php');

$objPHPExcel = new PHPExcel();
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);

$select = "select * from scenario_automate where report_type='call_summary'";
$export = mysqli_query($db2,$select);
$tagging_data_arr = array();
$html1 ='';
while($rowfetch = mysqli_fetch_assoc($export)){ 

    $client_id = $rowfetch['client'];
    $to = $rowfetch['to'];
    $cc = $rowfetch['cc'];
    $condition1 = "DATE(t2.call_date)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition2 = "date(CallDate) = DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $client_qry = "select campaignid,company_name,company_id from registration_master where status='A'  and company_id={$client_id} limit 1";
    //print_r($client_qry);exit;
    $Clientname = mysqli_fetch_assoc(mysqli_query($db2,$client_qry));
    
    //print_r($Clientname);exit;
    
    if(!empty($Clientname['campaignid']))
    {
        $campaignId = $Clientname['campaignid'];
        $campaignId =   "t2.campaign_id in(".$campaignId.")";
        
        $qry_calls="SELECT DATE(t2.call_date) AS CallDate,t2.call_date,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
    IF(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.campaign_id AS campaign_id
     FROM asterisk.vicidial_log t2
                WHERE $condition1 and $campaignId
                AND t2.lead_id IS NOT NULL";
        
        //print_r($qry_calls);exit;
        $rsc_calls = mysqli_query($db6,$qry_calls);
        
        
        
        $dialer_arr = array();
        
        while($dial = mysqli_fetch_assoc($rsc_calls))
        {
            //print_r($dial);exit;
            $dialer_arr[] = $dial;
        }

        $call_list = array();

            $call_list_summary = array();
            $campaign_list = array();
            $tag_list = array();
            $total_no_dial = 0;
            
            $call_date_list = array();
            
            foreach($dialer_arr as $dial)
            {
                $call_date = $dial['CallDate'];
                $phone = $dial['PhoneNumber'];
                $calltype = $dial['calltype'];
                $campaign_id = $dial['campaign_id'];
                $call_date_list[$call_date] =  $call_date;
                #$call_date = $dial['0']['call_date'];

                $campaign_list[$campaign_id] = $campaign_id; 
                if(!in_array($phone,$call_list[$call_date][$campaign_id]))
                {
                    $call_list_summary[$call_date][$campaign_id][$calltype] +=1;
                }

                $call_list[$call_date][$campaign_id][]=array('call_date'=>$dial['call_date'],'calltype'=>$calltype,'phone'=>$phone);
                $total_no_dial +=1;
            }
            
            //print_r($call_list_summary);exit;
            
            foreach($campaign_list as $campaign)
            {
               $camp_query = "SELECT * FROM `ob_campaign` WHERE ClientId='$client_id' AND CampaignName='$campaign' limit 1"; //exit;
                $camp_det =  mysqli_fetch_assoc(mysqli_query($db2,$camp_query)); 
                $campaign_id = $camp_det['id'];
                $alloc_qry = "SELECT * FROM `ob_allocation_name` WHERE ClientId='$client_id' AND campaignid='$campaign_id'"; //exit;
                $alloc_rsc = mysqli_query($db2,$alloc_qry);
                $alloc_list = array();
                while($all_record = mysqli_fetch_assoc($alloc_rsc))
                {
                    $alloc_list[] =  $all_record['id'];  
                }
                
                //print_r($alloc_list);exit;
                
                $alloc_list_str = implode("','",$alloc_list);
                
                $tag_arr = mysqli_query($db2,"SELECT *,date(CallDate) call_date FROM call_master_out cmo WHERE AllocationId in ('$alloc_list_str') and $condition2");
                
                while($tag=mysqli_fetch_assoc($tag_arr))
                {
                    $cat1 = $tag['Category2'];
                    if(empty($cat1))
                    {
                        $cat1 = $tag['Category1'];
                    }
                    $call_date = $tag['call_date'];
                    $tag_list[$call_date][$campaign][$cat1] += 1;
                }                                
            }
        $ws = 1;
            
            //print_r($tag_list);exit;
            $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            
          //print_r($campaign_list);exit;  
            foreach($call_date_list as $call_date)
            {
                
                $row = 1;
                $objWorksheet->setCellValue("A{$row}","Total Number Dial");
                $objWorksheet->setCellValue("B{$row}",$total_no_dial);
                $objWorksheet->getStyle("A{$row}:B{$row}")->getFont()->setBold( true );
                $row++;

                $objWorksheet->setCellValue("A{$row}","Campaign");
                $objWorksheet->setCellValue("B{$row}","Connected");            
                $objWorksheet->getStyle("A{$row}:B{$row}")->getFont()->setBold( true );
                $row++;

                $grand_total = 0;
                foreach($campaign_list as $campaign)
                {
                    //print_r($call_list_summary[$call_date][$campaign]);exit;
                    
                    $conn_calls = $call_list_summary[$call_date][$campaign]['Connected'];
                    //print_r($conn_calls);exit;
                    $objWorksheet->setCellValue("A{$row}",$campaign);
                    $objWorksheet->setCellValue("B{$row}",$conn_calls);            
                    //echo "B{$row}";exit;
                    $row++;
                    $grand_total += $conn_calls;

                }

                $objWorksheet->setCellValue("A{$row}","Grand Total");
                $objWorksheet->setCellValue("B{$row}",$grand_total);
                $objWorksheet->getStyle("A{$row}:B{$row}")->getFont()->setBold( true );
                $objWorksheet->getStyle("A1:B{$row}")->applyFromArray(
                array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000000')
                            )
                        )
                    ));
                

                $column = 66; 
                
                //print_r($campaign_list);exit;
                foreach($campaign_list as $campaign)
                {
                    $column = $column+2;
                    $column_h = chr($column);
                    $column++;
                    $column_v = chr($column);
                    $row = 1;
                    
                    //echo "{$column_h}{$row}";
                    //echo '<br/>';
                    $objWorksheet->setCellValue("{$column_h}{$row}",$campaign);
                    $objWorksheet->mergeCells("{$column_h}{$row}:{$column_v}{$row}");
                    $objWorksheet->getColumnDimension("{$column_h}")->setWidth(45);
                    $objWorksheet->getStyle("{$column_h}{$row}:{$column_v}{$row}")->applyFromArray($style);
                    $objWorksheet->getStyle("{$column_h}{$row}:{$column_v}{$row}")->getFont()->setBold( true );
                    $row++;
                    $objWorksheet->setCellValue("{$column_h}{$row}","Scenario");
                    $objWorksheet->setCellValue("{$column_v}{$row}","Count");
                    $objWorksheet->getStyle("{$column_h}{$row}:{$column_v}{$row}")->getFont()->setBold( true );
                    $row++;
                    
                    
                    $grand_total = 0;
                    foreach($tag_list[$call_date][$campaign] as $scen=>$tag)
                    {
                        $objWorksheet->setCellValue("{$column_h}{$row}",$scen);
                        $objWorksheet->setCellValue("{$column_v}{$row}",$tag);
                        $row++;
                        $grand_total += $tag;
                    }
                    
                    $objWorksheet->setCellValue("{$column_h}{$row}","Grand Total");
                    $objWorksheet->setCellValue("{$column_v}{$row}",$grand_total);
                    $objWorksheet->getStyle("{$column_h}{$row}:{$column_v}{$row}")->getFont()->setBold( true );
                    $objWorksheet->getStyle("{$column_h}1:{$column_v}{$row}")->applyFromArray(
                array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000000')
                            )
                        )
                    ));
                    $row++;

                }
                
                $objPHPExcel->createSheet();
                $objWorksheet= $objPHPExcel->setActiveSheetIndex($ws++);
            }
            
            $row = 1;
            $objWorksheet->setCellValue("A{$row}","Call Date");
            $objWorksheet->setCellValue("B{$row}","Phone No.");
            $objWorksheet->setCellValue("C{$row}","Status");
            $objWorksheet->getColumnDimension('A')->setWidth(30);
            $objWorksheet->getColumnDimension('B')->setWidth(30);
            $objWorksheet->getColumnDimension('C')->setWidth(30);
            $objWorksheet->getStyle("A{$row}:C{$row}")->getFont()->setBold( true );
            $row++;
            foreach($call_date_list as $call_date)
            {
                
                foreach($campaign_list as $campaign){
                    foreach($call_list[$call_date][$campaign] as $call_det)
                    {
                        //print_r($call_list);exit;
                        //foreach($call_list as $call_det)
                        //{
                            $objWorksheet->setCellValue("A{$row}",$call_det['call_date']);
                            $objWorksheet->setCellValue("B{$row}",$call_det['phone']);
                            $objWorksheet->setCellValue("C{$row}",$call_det['calltype']);
                            
                            $row +=1;
                        //}
                        
                    }    
                }
                
            }
            
           /*$fileName = "abcdef".date("d-m-Y") . '.xlsx';
            if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
            header('Content-Type: application/force-download');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. $fileName . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            header('Cache-control: no-cache, pre-check=0, post-check=0');
            header('Cache-control: private');
            header('Pragma: private');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // any date in the past
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->setIncludeCharts(TRUE);
            $objWriter->save('php://output');*/
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->setIncludeCharts(TRUE);
            $objWriter->save('php://output');
            $filename = "/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_ob_call_summary_".date('d_m_Y_H_i_s')."_Export.xls";
            $objWriter->save(str_replace(__FILE__,$filename,__FILE__));

            
            
        scenarion_data($filename,$Clientname['company_name'],$to,$cc,$db2);
        

        
    }
    

    
}



function scenarion_data($filename,$client_name,$to,$cc,$db2)
{

    $too = explode(',',$to);
    $ccc = explode(',',$cc);
    //$too = array('bhanu.singh@teammas.in');

    $last_day_date = date('d-m-Y',strtotime("-1 days")); 

    //print_r($ccc);exit;

    
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "$client_name - ($last_day_date) "; 
    $EmailText      ="<div>";

    $EmailText  .= $data_Arr;
    $EmailText  .= "<p>This is auto genrated mail.</p>";
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM Dialdesk</p>";
    $EmailText  .= "</div>"; 
    $attachment = array($filename); 
    include('report-send.php');
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'Attachment'=>$attachment,'EmailText'=> $EmailText,'AddTo'=> $too,'AddCc'=>$ccc);
    $done = send_email($emaildata);


    if($done=='1'){
        mysqli_query($db2,"insert scenariowise_alert_log SET client_name='{$client_name}',too='$to',cc='$cc',MailStatus='Success',MailDateTime=NOW()");
    }
    
}






?>


