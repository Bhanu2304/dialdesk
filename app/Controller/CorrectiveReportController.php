<?php
	class CorrectiveReportController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('RegistrationMaster','ClientCategory','CallMaster','FieldMaster','ObAllocationMaster','ObCampaignDataMaster','ListMaster','VicidialListMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'index',
			'export_corrective_report',
			'download_campaign',
                        'delete_allocation',
                        'get_campaign',
			'download');
    }
	
	public function index() 
    {

		$this->layout='user';
        $companyid   =   $this->Session->read('companyid');
		if($this->Session->read('role') == "admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            $this->set('client',$client); 
        }
        if($this->request->is("POST"))
        {
            $data = $this->request->data['CorrectiveReport'];
            $startdate = $data['startdate'];
            $enddate = $data['enddate'];
            //print_r($data);die;
            $ClientId = $data['clientID'];
            $data1['ClientId'] = $ClientId;

            $data1['date(CallDate) >='] = $startdate;
            $data1['date(CallDate) <='] = $enddate;

            $tArr = $this->CallMaster->find('all',array('conditions' =>$data1,'order'=>array('CallMaster.Category3 ASC')));

            $dataArr = array();

            foreach($tArr as $calldata)
            {

                if(empty($calldata['CallMaster']['CloseLoopCate1']))
                {
                   $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['open'] +=1;
                }
                else
                {
                   $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['close'] +=1; 
                }

                $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['data'] = $calldata['CallMaster'];
                //print_r($dataArr);die;
            }
            //print_r($dataArr);die;


          
            $this->set('dataArr',$dataArr);
            $this->set('companyid',$ClientId);
        }
	
	}

    public function export_corrective_report()
    {
        if($this->request->is("POST"))
        {
            
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=corrective_report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
       
        
            $data = $this->request->data['CorrectiveReport'];
            $startdate = $data['startdate'];
            $enddate = $data['enddate'];
            //print_r($data);die;
            $ClientId = $data['clientID'];
            $data1['ClientId'] = $ClientId;

            $data1['date(CallDate) >='] = $startdate;
            $data1['date(CallDate) <='] = $enddate;

            $tArr = $this->CallMaster->find('all',array('conditions' =>$data1,'order'=>array('CallMaster.Category3 ASC')));

            $dataArr = array();

            foreach($tArr as $calldata)
            {

                if(empty($calldata['CallMaster']['CloseLoopCate1']))
                {
                   $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['open'] +=1;
                }
                else
                {
                   $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['close'] +=1; 
                }

                $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['data'] = $calldata['CallMaster'];
                //print_r($dataArr);die;
            }
                
                
                ?>
            
                <table cellspacing="0" border="1">
                    <tr style="background-color:DarkGray;">
                        <th rowspan="2">Site</th>			
                        <th rowspan="2">Category</th>
                        <th rowspan="2">Total Corrections</th>
                        <th colspan="2" style="text-align: center;">Status</th>
                        <th rowspan="2">Remarks</th>   
                    </tr>
                    <tr style="background-color:DarkGray;">
                            
                            <th>Open</th>
                            <th>close</th>
                              
                    </tr>
                    <?php 
                           $grand_total_corr = 0;
                           foreach($dataArr as $key=>$value){  ?>
                            
                                
                                <?php $a=1;$total_corr=0;$total_open= 0;$total_close= 0; $col2_keys = array_keys($value);?>
                                <?php foreach($col2_keys as $key2){  ?>
                                    <tr>
                                    <?php if($a==1) { ?>
                                    <th rowspan="<?php echo count($value); ?>"><?php echo $key; ?></th>
                                    <?php $a=0; } ?>
                                    <th><?php echo $key2; ?></th>
                                    <td><?php $complaint = $value[$key2]['open']+$value[$key2]['close']; echo $complaint;?></td>
                                    <td><?php echo $value[$key2]['open']; ?></td>
                                    <td><?php echo $value[$key2]['close']; ?></td>
                                    <td><?php //echo wordwrap($value[$key2]['data']['Field21'],25,"<br>\n"); ?></td>
                                    
                                    </tr>
                                    <?php $total_open+=$value[$key2]['open'];
                                          $total_close+=$value[$key2]['close'];
                                          $total_corr += $complaint;
                                      }?>  
                                    
                                    <tr>
                                        <?php $phase_total = $total_close/$total_corr; ?>
                                            <th colspan="2">Total</th>
                                            <th><?php echo $total_corr; ?></th>
                                            <th><?php echo $total_open;?></th>
                                            <th><?php echo $total_close; ?></th>
                                            <th><?php echo number_format($phase_total);?></th>
                                            
                                    </tr>
                            
                                <?php $grand_total_corr += $total_corr;
                                      $grand_total_open += $total_open;
                                      $grand_total_close += $total_close;
                                      }?>    
                                    <tr>
                                            <th style="background-color:yellow;" colspan="2">Grand Total</th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_corr; ?></th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_open; ?></th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_close; ?></th>
                                            <th style="background-color:yellow;"><?php $totalarr = $grand_total_close/$grand_total_corr; echo number_format($totalarr,2) ; ?></th>
                                    </tr>
                        								
                 
                </table> 

                <?php
        }
                
         die;   
    
    }


        

	
}

?>