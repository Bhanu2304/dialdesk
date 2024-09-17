<?php
class IncallactionReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('CallRecord','RegistrationMaster');
    
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='user';

        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            //$this->set('category',$this->EcrRecord->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>1,'parent_id'=>NULL))));
            //$client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }else{
            $clientId   = $this->Session->read('companyid');
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }
        
        if($this->request->is("POST")){
            $search=$this->request->data['IncallactionReports'];
            //$clientId = $this->Session->read('companyid');
            $clientId    =  $search['clientID'];

            $start_date = date("Y-m-d",strtotime($search['startdate']));
            $end_date = date("Y-m-d",strtotime($search['enddate']));
            $categoryName = $search['category'];


            $where_cat = '';
            if($categoryName != 'All')
            {
                $where_cat ="AND Category1='$categoryName'";
            }else{
                $where_cat = '';
            }
            
            $qry="SELECT
            CloseLoopCate1 AS ActionType,
            COUNT(CloseLoopCate1) AS TotalCont
            FROM `call_master` 
            WHERE ClientId='$clientId' AND CallType !='Upload' AND CloseLoopingDate IS NOT NULL $where_cat AND DATE(CallDate) BETWEEN '$start_date' AND '$end_date'
            GROUP BY CloseLoopCate1;";
            
            $RecArr=$this->CallRecord->query($qry);
            $this->set('data',$RecArr);
            
            $TotalCount=$this->CallRecord->query("SELECT COUNT(Id) as TotalCount FROM call_master WHERE ClientId='$clientId' AND CallType !='Upload' $where_cat AND DATE(CallDate) BETWEEN '$start_date' AND '$end_date'");
            $this->set('TotalCount',$TotalCount[0][0]['TotalCount']); 

            $this->set('companyid',$clientId);
        }
    }
        
    public function export_callaction_mis() {
        $this->layout='ajax';
        if($this->request->is("POST")){
            
            $search=$this->request->data['IncallactionReports'];
            //$clientId = $this->Session->read('companyid');

            $start_date = date("Y-m-d",strtotime($search['startdate']));
            $end_date = date("Y-m-d",strtotime($search['enddate']));
            $clientId    =  $search['clientID'];
            $categoryName = $search['category'];


            $where_cat = '';
            if($categoryName != 'All')
            {
                $where_cat ="AND Category1='$categoryName'";
            }else{
                $where_cat = '';
            }
            
            $RecArr=$this->CallRecord->query("SELECT
            CloseLoopCate1 AS ActionType,
            COUNT(CloseLoopCate1) AS TotalCont
            FROM `call_master` 
            WHERE ClientId='$clientId' AND CallType !='Upload' AND CloseLoopingDate IS NOT NULL $where_cat AND DATE(CallDate) BETWEEN '$start_date' AND '$end_date'
            GROUP BY CloseLoopCate1");
           
            $TotalCount   = $this->CallRecord->query("SELECT COUNT(Id) as TotalCount FROM call_master WHERE ClientId='$clientId' AND CallType !='Upload' $where_cat AND DATE(CallDate) BETWEEN '$start_date' AND '$end_date'");
            $TotalTagging = $TotalCount[0][0]['TotalCount'];
            
            $value1=array();
            
            $value1[0][]="IN CALL ACTION";
            $value1[0][]="COUNT";
            $value1[0][]="%";
            
            $i=1;
            $TotalAction=0;
            foreach($RecArr as $v1){
                $TotalAction=$TotalAction+$v1[0]['TotalCont'];
                
                $value1[$i][]=strtoupper($v1['call_master']['ActionType']);
                $value1[$i][]=$v1[0]['TotalCont'];
                $value1[$i][]=round($v1[0]['TotalCont']*100/$TotalTagging).'%';
                $i++;
            }
            
            $TotalNotAction=($TotalTagging-$TotalAction);
            
            $value1[$i+1][]="NOT CALL ACTION";
            $value1[$i+1][]=$TotalNotAction;
            $value1[$i+1][]=round($TotalNotAction*100/$TotalTagging).'%';
            
            $value1[$i+2][]="TOTAL";;
            $value1[$i+2][]=$TotalTagging;
            $value1[$i+2][]=round($TotalTagging*100/$TotalTagging).'%';

            $this->export_excel($value1);
        }
    }
    
    
    public function export_excel($SumeryDetails,$exportArray2){
        require_once(APP . 'Lib' . DS . 'Classes' . DS . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
         $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ))->getFont()->setBold(true)
                                ->setName('Verdana')
                                ->setSize(10)
                                ->getColor()->setRGB('fffff');
         $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
         
         
       // print_r($objPHPExcel);
       
       
        
        
        $objWorksheet->fromArray($SumeryDetails);

            $dataseriesLabels1 = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', NULL, 1),   //  Temperature
            );
            $dataseriesLabels2 = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),   //  Rainfall
            );
            $dataseriesLabels3 = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1),   //  Humidity
            );


            $xAxisTickValues = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$13', NULL, 12),    //  Jan to Dec
            );

            $dataSeriesValues1 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$13', NULL, 12),
            );

            //  Build the dataseries
            $series1 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_BARCHART,       // plotType
                PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,  // plotGrouping
                range(0, count($dataSeriesValues1)-1),          // plotOrder
                $dataseriesLabels1,                             // plotLabel
                $xAxisTickValues,                               // plotCategory
                $dataSeriesValues1                              // plotValues
            );

            $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

            $dataSeriesValues2 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$13', NULL, 12),
            );

            //  Build the dataseries
            $series2 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_LINECHART,      // plotType
                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,   // plotGrouping
                range(0, count($dataSeriesValues2)-1),          // plotOrder
                $dataseriesLabels2,                             // plotLabel
                NULL,                                           // plotCategory
                $dataSeriesValues2                              // plotValues
            );

            $dataSeriesValues3 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$13', NULL, 12),
            );

            //  Build the dataseries
            $series3 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_AREACHART,      // plotType
                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,   // plotGrouping
                range(0, count($dataSeriesValues2)-1),          // plotOrder
                $dataseriesLabels3,                             // plotLabel
                NULL,                                           // plotCategory
                $dataSeriesValues3                              // plotValues
            );


            //  Set the series in the plot area
            $plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series1, $series2, $series3));
            //  Set the chart legend
            $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

            $title = new PHPExcel_Chart_Title('IN CALL ACTION');


            //  Create the chart
            $chart = new PHPExcel_Chart(
                'chart1',       // name
                $title,         // title
                $legend,        // legend
                $plotarea,      // plotArea
                true,           // plotVisibleOnly
                0,              // displayBlanksAs
                NULL,           // xAxisLabel
                NULL            // yAxisLabel
            );

            $chart->setTopLeftPosition('D1');
            $chart->setBottomRightPosition('M15');
            $objWorksheet->addChart($chart);

            
        //=====================================
           if($exportArray2!=NULL){
            $objPHPExcel->createSheet();

// Add some data to the second sheet, resembling some different data types
       $objWorksheet= $objPHPExcel->setActiveSheetIndex(1);
       //print_r($exportArray2[0]); EXIT;
    $col = count($exportArray2[0]);
      //print_r($exportArray2);  exit;
       $array=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
// Rename 2nd sheet
//$objPHPExcel->getActiveSheet()->setTitle('Second sheet'); 
       $a = round($col/26,0);
       $b = $col%26;
       $ch = $array[$a].$array[$b].'1';
       //echo $ch; exit;
       
        $objPHPExcel->getActiveSheet()->getStyle("A1:$ch")->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ))->getFont()->setBold(true)
                                ->setName('Verdana')
                                ->setSize(10)
                                ->getColor()->setRGB('fffff');
         $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);      
            
             $objWorksheet->fromArray($exportArray2);
             
           }
            
        //====================================
            
            
            

    $fileName = "in_call_mis_".date("d_m_Y") . '.xlsx';
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
    
    
    
    
    
    
}
?>