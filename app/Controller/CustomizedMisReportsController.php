<?php
class CustomizedMisReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('vicidialLog','CampaignName','ObAllocationMaster','CallMasterOut',
        'ReportTabMaster','ReportHeaderMaster','CallRecord','RegistrationMaster');
    
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }

    public function index(){
        $this->layout='user';
    }
    public function customize_report(){
        $this->layout='user';
    }

    public function export_customize_summary()
    {
            //print_r($this->request->data);exit;
            require_once(APP . 'Lib' . DS . 'Classes' . DS . 'PHPExcel.php');
            $objPHPExcel = new PHPExcel();
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
            $req = $this->request->data['CustomizedMisReports'];
            //print_r($req);exit;
            $ClientId   =   $this->Session->read('companyid');
            $FromDate =   date("Y-m-d",strtotime($req['startdate']));
            $ToDate   =   date("Y-m-d",strtotime($req['enddate']));

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$ClientId'"));
            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            //print_r($campaignId);exit;
            $qry_calls="SELECT DATE(t2.call_date) AS CallDate,t2.call_date,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
    IF(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.campaign_id AS campaign_id
     FROM asterisk.vicidial_log t2
                WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate'  AND $campaignId
                AND t2.lead_id IS NOT NULL";
            //echo $qry_calls;exit;
            $this->vicidialLog->useDbConfig = 'db2';
            $dialer_arr=$this->vicidialLog->query($qry_calls);
            //print_r($dialer_arr);exit;
            $call_list = array();

            $call_list_summary = array();
            $campaign_list = array();
            $tag_list = array();
            $total_no_dial = 0;
            
            $call_date_list = array();
            
            foreach($dialer_arr as $dial)
            {
                $call_date = $dial['0']['CallDate'];
                $phone = $dial['0']['PhoneNumber'];
                $calltype = $dial['0']['calltype'];
                $campaign_id = $dial['t2']['campaign_id'];
                $call_date_list[$call_date] =  $call_date;
                #$call_date = $dial['0']['call_date'];

                $campaign_list[$campaign_id] = $campaign_id; 
                if(!in_array($phone,$call_list[$call_date][$campaign_id]))
                {
                    $call_list_summary[$call_date][$campaign_id][$calltype] +=1;
                }

                $call_list[$call_date][$campaign_id][]=array('call_date'=>$dial['t2']['call_date'],'calltype'=>$calltype,'phone'=>$phone);
                $total_no_dial +=1;
            }
            
            
            foreach($campaign_list as $campaign)
            {
                $camp_det = $this->CampaignName->find('first', array('conditions'=>"ClientId='$ClientId' and CampaignName='$campaign'"));
                $campaign_id = $camp_det['CampaignName']['id'];
                $alloc_list = $this->ObAllocationMaster->find('list',array("conditions"=>"ClientId='$ClientId' and CampaignId='$campaign_id'"));
                $alloc_list_str = implode("','",$alloc_list); 
                $tag_arr = $this->CallMasterOut->query("SELECT *,date(CallDate) call_date FROM call_master_out cmo WHERE AllocationId in ('$alloc_list_str') and date(CallDate) between '$FromDate' and '$ToDate'");
                
                foreach($tag_arr as $tag)
                {
                    $cat1 = $tag['cmo']['Category2'];
                    if(empty($cat1))
                    {
                        $cat1 = $tag['cmo']['Category1'];
                    }
                    $call_date = $tag['0']['call_date'];
                    $tag_list[$call_date][$campaign][$cat1] += 1;
                }                                
            }
            
            $ws = 1;
            
            //print_r($call_list_summary);exit;
            $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            
            
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
                    //print_r($call_list[$call_date][$campaign_id]);exit;
                    $conn_calls = $call_list_summary[$call_date][$campaign]['Connected'];
                    $objWorksheet->setCellValue("A{$row}",$campaign);
                    $objWorksheet->setCellValue("B{$row}",$conn_calls);            
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
                foreach($campaign_list as $campaign)
                {
                    $column = $column+2;
                    $column_h = chr($column);
                    $column++;
                    $column_v = chr($column);
                    $row = 1;

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
            
            
            $fileName = "abcdef".date("d-m-Y") . '.xlsx';
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
            $objWriter->save('php://output');
            
            
            exit;
            //print_r($tag_list);exit;
            
            
        }
    
    public function export_customize_mis(){
        require_once(APP . 'Lib' . DS . 'Classes' . DS . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        
        $ClientId   =   $this->Session->read('companyid');
        $search     =   $this->request->data['CustomizedMisReports'];
        $start_date =   date("Y-m-d",strtotime($search['startdate']));
        $end_date   =   date("Y-m-d",strtotime($search['enddate']));

        $TabArr         =   $this->ReportTabMaster->find('all',array('fields'=>array('id','tab_name','tab_field'),'conditions'=>array('client_id'=>$ClientId,'tab_status'=>'A'),'order'=>array('tab_order'=>'ASC')));
        $i=0;
        $header=array(); 
        $value=array();
        foreach($TabArr as $v1){
            
            $tab_id     =   $v1['ReportTabMaster']['id'];
            $sheetname  =   $v1['ReportTabMaster']['tab_name'];
            $tabfields  =   $v1['ReportTabMaster']['tab_field']; 
            $condition  =   array('date(CallDate) >='=>$start_date,'date(CallDate) <='=>$end_date,'ClientId'=>$ClientId,"$tabfields"=>"$sheetname");
            $header_arr =   $this->getheader($tab_id);
            //print_r($header_arr);
            $header     =   $header_arr['header'];
            $fields     =   $header_arr['fields'];
            //print_r($fields);die;
            //echo $ClientId;die;
            $value      =   $this->getdata($condition,$fields);
            //print_r($value);die;
            if($ClientId == '293')
            {
                $DataArray  =   array_merge(array($header),$value);
                $DataArray2 = array();
                foreach($DataArray as $record_arr)
                {
                    //print_r($record);exit;
                    //echo $record['CField4'];exit;
                    $record2 = array();
                    foreach($record_arr as $key=>$record)
                    {
                        $record_str=explode(" ",$record);
                        $date = $record_str[0];
                        $time = $record_str[1];
                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
                        {       
                                $record2[$key] = date('d/m/Y',strtotime($date))." $time";
                        }
                        
                        else{
                            $record2[$key] = $record;
                        }
                    }
                    
                    $DataArray2[] = $record2;
                }
            }else{
                $DataArray2  =   array_merge(array($header),$value);
            }
            
            //print_r($DataArray2);die;
           
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
                                        ->setSize(8)
                                        ->getColor()->setRGB('fffff');
                
                $objPHPExcel->getActiveSheet()->getStyle("A1:$ch".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
                
                foreach($array as $key=>$val){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($val)->setWidth(20);
                }
                
                $objWorksheet->fromArray($DataArray2);
            
            
            $i++;
        }
        // print_r($DataArray);
        // print_r($header_arr);
        // die;
        
        $objPHPExcel->setActiveSheetIndex(0);

        $fileName = "customize_in_call_report".date("m-d-Y") . '.xlsx';
        if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
        header('Content-Type: application/force-download');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        #ob_start();
        header('Cache-control: no-cache, pre-check=0, post-check=0');
        header('Cache-control: private');
        header('Pragma: private');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // any date in the past
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->setIncludeCharts(TRUE);
        ob_end_clean();
        $objWriter->save('php://output'); 
        exit;
        #ob_end_clean();
    }
    
    public function getheader($tab_id){ 
        //echo $tab_id;
        $HeadArr    =   $this->ReportHeaderMaster->find('all',array('fields'=>array('header_name','header_field'),'conditions'=>array('tab_id'=>$tab_id,'header_status'=>'A'),'order'=>array('header_order'=>'ASC')));
        foreach($HeadArr as $v2){
            $data['header'][]=$v2['ReportHeaderMaster']['header_name'];
            $data['fields'][]=$v2['ReportHeaderMaster']['header_field'];
        }
        return $data; 
    }
    
    public function getdata($condition,$fields,$ClientId){ 
        $value=array();
        $RecordArray    =   $this->CallRecord->find('all',array('fields'=>$fields,'conditions'=>$condition));

            foreach($RecordArray as $v3){
                $value[]=$v3['CallRecord'];
            }

        return $value;
    }
}
?>