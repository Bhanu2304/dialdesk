<?php
class ObCustomizedMisReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('ObReportTabMaster','ObReportHeaderMaster','CallMasterOut','CampaignName','ObAllocationMaster');
    
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }

    public function index(){
        $this->layout   =   'user';
        $ClientId       =   $this->Session->read('companyid');
        $Campaign       =   $this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A'))); 
        $this->set('Campaign',$Campaign);
    }

    public function export_customize_mis(){
        require_once(APP . 'Lib' . DS . 'Classes' . DS . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        
        $ClientId   =   $this->Session->read('companyid');
        $search     =   $this->request->data['ObCustomizedMisReports']; 
        
        
        
        $CampaignName   =   $search['CampaignName'];
        $AllocationName =   $search['AllocationName'];
        $start_date     =   date("Y-m-d",strtotime($search['startdate']));
        $end_date       =   date("Y-m-d",strtotime($search['enddate']));
        
        $TabArr         =   $this->ObReportTabMaster->find('all',array('fields'=>array('id','tab_name','tab_field'),'conditions'=>array('client_id'=>$ClientId,'campaign_id'=>$CampaignName,'tab_status'=>'A'),'order'=>array('tab_order'=>'ASC')));
        
        
        /*
        echo "<pre>";
        print_r($TabArr);
        echo "<pre>";die;
        */
        
        $i=0;
        $header=array();
        $value=array();
        foreach($TabArr as $v1){
            
            $tab_id     =   $v1['ObReportTabMaster']['id'];
            $sheetname  =   $v1['ObReportTabMaster']['tab_name'];
            $tabfields  =   $v1['ObReportTabMaster']['tab_field']; 
            $condition  =   array('date(CallMasterOut.CallDate) >='=>$start_date,'date(CallMasterOut.CallDate) <='=>$end_date,'ClientId'=>$ClientId,"$tabfields"=>"$sheetname");
            
            if(isset($AllocationName) && $AllocationName !=""){$condition['CallMasterOut.AllocationId']=$AllocationName;}else{unset($condition['CallMasterOut.AllocationId']);}
            
            $header_arr =   $this->getheader($tab_id);
            $header     =   $header_arr['header'];
            $fields     =   $header_arr['fields'];
            $value      =   $this->getdata($condition,$fields);            
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
                                        ->setSize(8)
                                        ->getColor()->setRGB('fffff');
                
                $objPHPExcel->getActiveSheet()->getStyle("A1:$ch".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
                
                foreach($array as $key=>$val){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($val)->setWidth(20);
                }
                
                
                $objWorksheet->fromArray($DataArray);
            
            
            $i++;
        }
        
        
        
        
        
        $objPHPExcel->setActiveSheetIndex(0);

        $fileName = date("m-d-Y") . '.xlsx';
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
    }
    
    public function getheader($tab_id){ 
        $HeadArr    =   $this->ObReportHeaderMaster->find('all',array('fields'=>array('header_name','header_field'),'conditions'=>array('tab_id'=>$tab_id,'header_status'=>'A'),'order'=>array('header_order'=>'ASC')));
        foreach($HeadArr as $v2){
            $data['header'][]=$v2['ObReportHeaderMaster']['header_name'];
            $data['fields'][]=$v2['ObReportHeaderMaster']['header_field'];
        }
        return $data; 
    }
    
    public function getdata($condition,$fields){
        
        $value=array();
        $RecordArray = $this->CallMasterOut->find('all',array(
                    'fields'=>$fields,
                    'joins' => array(
                                    array(
                                        'table' => 'ob_campaign_data',
                                        'type'=>'Left',
                                        'alias'=>'obcd',
                                        'conditions'=>array("CallMasterOut.DataId=obcd.id"),
                                    ),			
                                ),'conditions' =>$condition));

        foreach($RecordArray as $v3){
            
            $value[]=array_merge($v3['CallMasterOut'],$v3['obcd']);
            
        }
        
        return $value;
    }
    
    public function get_allocation(){
        $this->layout='ajax';
        if($_REQUEST['campid']){
                $clientid=$this->Session->read('companyid');
                $campaignid=$_REQUEST['campid'];
                $allocation=$this->ObAllocationMaster->find('list',array('fields'=>array("id","AllocationName"),'conditions'=>
                array('ClientId'=>$clientid,'CampaignId'=>$campaignid)));
                $this->set('allocations',$allocation);
        }
    }
    
}
?>