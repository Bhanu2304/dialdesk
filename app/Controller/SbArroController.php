<?php
class SbArroController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ClientCategory','UploadExistingBase','RegistrationMaster','vicidialCloserLog',
            'vicidialUserLog','CallMaster','CallRecord','CampaignName','CallMasterOut','ClientReportMaster',
            'AbandCallMaster','vicidialLog','PlanMaster','InitialInvoice','CostCenterMaster','BillMaster','BillingLedger','BalanceMaster','BillingMaster');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow();
        
        $this->InitialInvoice->useDbConfig = 'dbNorth';
               
        $this->vicidialCloserLog->useDbConfig = 'db2';

        $this->BillMaster->useDbConfig = 'dbNorth';
        $this->InitialInvoiceTmp->useDbConfig = 'dbNorth';
        $this->Addclient->useDbConfig = 'dbNorth';
        $this->Addbranch->useDbConfig = 'dbNorth';
        $this->Addcompany->useDbConfig = 'dbNorth';
        $this->CostCenterMaster->useDbConfig = 'dbNorth';


    }

    
	public function index() {
            $this->layout='user';
               
 
            
            
            if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client);
               
                if($this->request->is('Post')){
                    $data=$this->request->data['Home'];
                    //print_r($this->request->data);exit;
                    if(!empty($data)){
                        $status =$this->RegistrationMaster->find('first',array('fields'=>array("status","campaignid",'create_date'),'conditions'=>array('company_id'=>$data['clientID'])));
                        $this->Session->write("companyid",$data['clientID']);
                        $this->Session->write("clientstatus",$status['RegistrationMaster']['status']);
                        $this->Session->write("campaignid",$status['RegistrationMaster']['campaignid']);
                        $activation_date = $status['RegistrationMaster']['create_date'];
                        if(empty($activation_date))
                        {
                            // $activation_date = date('d-M-y',strtotime($status['RegistrationMaster']['create_date']));
                            $activation_date = date('d-j',strtotime($status['RegistrationMaster']['create_date']));
                        }
                        else
                        {
                            $activation_date = date('d M Y',strtotime($status['RegistrationMaster']['create_date']));
                        }
                        $this->set("activation_date",$activation_date);
                      
                    }
               
                }
            }
            
              
                
		$ClientId = $this->Session->read('companyid');
		$start = date('Y-m-d');
                
                $callType=$this->request->data['callType'];
                //$campaignid=$this->request->data['campaignid'];
                $fd=$this->request->data['fdate'];
                $ld=$this->request->data['ldate'];
		if (isset($this->request->data['view_type']))
        {
			$view_type=$this->request->data['view_type'];
			if($view_type=="Today"){

				$conditions = "and date(CallDate) = curdate()";
                                $Abandconditions = "and date(AbandDate) = curdate()";
                                $conditions1 = "and date(cm.CallDate) = curdate()";
				$view_date="date(t2.call_date) = curdate()";
                                $condArr['date(CallDate)']=date('Y-m-d');
                $sbarr_date = "date(t2.call_time) = curdate()";
			}
			if($view_type=="Custom"){
				$fdate = date('Y-m-d', strtotime($this->request->data['fdate']));
				$ldate = date('Y-m-d', strtotime($this->request->data['ldate']));
                                
                                $condArr['date(CallDate) >=']=$fdate;
                                $condArr['date(CallDate) <=']=$ldate;
                                
				$conditions = "and date(CallDate) between '$fdate' and '$ldate'";
                                $Abandconditions = "and date(AbandDate) between '$fdate' and '$ldate'";
                                $conditions1 = "and date(cm.CallDate) between '$fdate' and '$ldate'";
                                $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
                                $sbarr_date = "date(t2.call_time) between '$fdate' and '$ldate'";
                  
			}
                        
		}
		else{
			$conditions = "and date(CallDate) = curdate()";
                        $conditions1 = "and date(cm.CallDate) = curdate()";
                        $curDate=date('Y-m-d');
                        $view_date = "date(t2.call_date)='$curDate'";
                        $sbarr_date = "date(t2.call_time)='$curDate'";
                        $condArr['date(CallDate)']=$curDate;


                        
		}
               // echo $view_date;die;
		if(!empty($this->Session->read('companyid')))
        {
            
            $status =$this->RegistrationMaster->find('first',array('fields'=>array("status","campaignid",'create_date'),'conditions'=>array('company_id'=>$this->Session->read('companyid'))));
            $activation_date = $status['RegistrationMaster']['create_date'];
            if(empty($activation_date))
            {
                // $activation_date = date('d-M-y',strtotime($status['RegistrationMaster']['create_date']));
                $activation_date = date('d-j',strtotime($status['RegistrationMaster']['create_date']));
            }
            else
            {
                $activation_date = date('d M Y',strtotime($status['RegistrationMaster']['create_date']));
            }
            $this->set("activation_date",$activation_date);
            $fd=$this->request->data['fdate'];
            $ld=$this->request->data['ldate'];

            
            // echo $ld;die;

            $Campagn  = $this->Session->read('campaignid');
            if(!empty($Campagn)){
            $CampagnId ="and t2.campaign_id in ($Campagn)";

            $qry = "SELECT COUNT(*) `Total`,SUM(IF(`pin_no` IS NOT NULL ,1,0)) `Answered`,SUM(IF((`pin_no` IS NULL || pin_no='' ),1,0)) `Abandon` FROM `sbarro_log` t2
            LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid where $sbarr_date";

            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt= $this->vicidialCloserLog->query($qry);
        
            $this->set('Abandon',$dt[0][0]['Abandon']);
            $this->set('Total',$dt[0][0]['Total']);
            $this->set('Answer',$dt[0][0]['Answered']);

            if(empty($fd))
            {
               
                $Nmonth = date('M');
                $year = date( 'Y');
         
            }else
            {
                $Nmonth = date( 'M', strtotime($fd));
                $year = date( 'Y', strtotime($fd));
              
            }

            //echo $Nmonth;die;
            $consunption = $this->get_consunption($Nmonth,$year); 
            $opening_balance = $this->get_opening_balance($Nmonth,$year);
           // echo $opening_balance;die;
            
            $this->set('opening_balance',$opening_balance);
            $this->set('consunption',$consunption);
            

            


            }

           
            
            
            

        
        }

        $this->set('fd',$fd);
        $this->set('ld',$ld);
        $this->set('viewType',$view_type);
                
                
	}

    public function get_opening_balance($month,$year)
    {
        
        $ClientId = $this->Session->read('companyid');
        $Nmonth = date('M');
        $year = date("Y");
        $op_det_ledger = $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='2022' and fin_month='Apr' and clientId = '$ClientId'");


        $opening_balance = round($op_det_ledger['0']['bl']['talk_time'],2);

        return $opening_balance;
        exit;
        
    } 

    public function get_consunption($month,$year)
    {
        
        $ClientId = $this->Session->read('companyid');
        $Nmonth = date('M');
        $year = date("Y");
        $qry = "select * from billing_opening_balance where clientId='$ClientId' and fin_year='$year' and  fin_month='$month' limit 1";

        $BalanceMaster_det = $this->CallMaster->query($qry);
        $BalanceMaster = round($BalanceMaster_det['0']['billing_opening_balance']['cs_bal']);
            //$Plan_Det = $this->CallMaster->query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1");
            //$PlanDetails = $Plan_Det['0']['plan_master'];
            //return $PlanDetails['CreditValue'];
            return $BalanceMaster;
            exit;
        
    } 
        

}
?>