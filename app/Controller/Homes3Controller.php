<?php
class Homes3Controller extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ClientCategory','UploadExistingBase','RegistrationMaster','vicidialCloserLog',
            'vicidialUserLog','CallMaster','CallRecord','CampaignName','CallMasterOut','ClientReportMaster',
            'AbandCallMaster','vicidialLog','PlanMaster','InitialInvoice','CostCenterMaster','BillMaster','BillingLedger','BalanceMaster','BillingMaster','TimePeriod','WhatsappTicket');
	
	
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
    //$this->Session->check("companyid")
    //$this->Session->write("role","admin");
    
	public function index() {
            $this->layout='user';
               
            //$Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$this->Session->read('companyid'))));
            //$this->set('Campaign',$Campaign);
            
            //print_r($leftMenu); exit;
            
            
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
                    /*
                    else{
                        $this->Session->delete('companyid');
                        $this->Session->delete('clientstatus');
                        $this->Session->delete('campaignid');  
                    }*/
                }
            }
            
              
                
		$ClientId = $this->Session->read('companyid');
		$start = date('Y-m-d');
                
                $callType=$this->request->data['callType'];
                //$campaignid=$this->request->data['campaignid'];
                $fd=$this->request->data['fdate'];
                $ld=$this->request->data['ldate'];
                //echo $callType; exit;
		if (isset($this->request->data['view_type']))
                {
			$view_type=$this->request->data['view_type'];
			if($view_type=="Today"){

				$conditions = "and date(CallDate) = curdate()";
                                $Abandconditions = "and date(AbandDate) = curdate()";
                                $conditions1 = "and date(cm.CallDate) = curdate()";
				$view_date="date(t2.call_date) = curdate()";
                                $condArr['date(CallDate)']=date('Y-m-d');
			}
			if($view_type=="Yesterday"){
				$yesterday=date('Y-m-d', strtotime('-1 day'));
                                $condArr['date(CallDate)']=$yesterday;
				$conditions = "and date(CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                                $Abandconditions = "and date(AbandDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                                $conditions1 = "and date(cm.CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
				$view_date="date(t2.call_date) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
			}
			if($view_type=="Weekly"){
        		$end = date('Y-m-d', strtotime('-6 day'));
                                $condArr['date(CallDate) <=']=date('Y-m-d');
                                $condArr['date(CallDate) >=']=$end;
				$conditions = "and date(CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                                $Abandconditions = "and date(AbandDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                                $conditions1 = "and date(cm.CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
				$view_date="date(t2.call_date) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
			}
			if($view_type=="Monthly"){
				$end = date('Y-m-d', strtotime('-30 day'));
                                $condArr['date(CallDate) <=']=date('Y-m-d');
                                $condArr['date(CallDate) >=']=$end;
                                
				//$conditions = "and date(CallDate) between SUBDATE(CURDATE(),INTERVAL 30 DAY) and CURDATE()";
                                //$conditions1 = "and date(cm.CallDate) between SUBDATE(CURDATE(),INTERVAL 30 DAY) and CURDATE()";
				//$view_date="date(t2.call_date) between SUBDATE(CURDATE(),INTERVAL 30 DAY) and CURDATE()";
                                
                                
                                $conditions ="and MONTH(CallDate) = MONTH(CURDATE()) and YEAR(CallDate) = YEAR(CURDATE())";
                                $Abandconditions ="and MONTH(AbandDate) = MONTH(CURDATE()) and YEAR(AbandDate) = YEAR(CURDATE())";
                                $conditions1 ="and MONTH(cm.CallDate) = MONTH(CURDATE()) and YEAR(cm.CallDate) = YEAR(CURDATE())";
                                $view_date ="MONTH(t2.call_date) = MONTH(CURDATE()) and YEAR(t2.call_date) = YEAR(CURDATE())";
                                
                                
                                 
                                
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
                                        /*
					if($this->request->data['fdate'] !="" && $this->request->data['ldate'] !=""){
					$view_date=$fdate."&nbsp;-&nbsp;".$ldate;
					}
					else{
						$view_date="";
					}*/
			}
                        
                        if($callType ==="outbounds"){
                            $this->view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld);
                            
                        }else{
                            
                            $this->view_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld,$Abandconditions);
                        
                        }
                        
		}
		else{
			$conditions = "and date(CallDate) = curdate()";
                        $conditions1 = "and date(cm.CallDate) = curdate()";
                        $curDate=date('Y-m-d');
                        $view_date = "date(t2.call_date)='$curDate'";
                        $condArr['date(CallDate)']=$curDate;
                       
                        if($callType ==="outbounds"){
                            $this->view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType); 
                        }else{
                            // $this->view_deshbord($conditions1,$conditions,$view_type='Today',$view_date,$condArr,$callType);
                            
                            $this->view_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType);

                        }
                        
		}
                
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
                    
                    
                    
                    
                    
                    if (isset($this->request->data['view_type']))
                    {
                        $view_type=$this->request->data['view_type'];
                        if($view_type=="Today"){
            
                            $conditions_new = "and date(cm_date) = curdate()";
                            $sum_query = "sum";
                                            
                            // $Abandconditions = "and date(AbandDate) = curdate()";
                            //                 $conditions1 = "and date(cm.CallDate) = curdate()";
                            // $view_date="date(t2.call_date) = curdate()";
                            //                 $condArr['date(CallDate)']=date('Y-m-d');
                        }
                        if($view_type=="Yesterday"){
                            $yesterday=date('Y-m-d', strtotime('-1 day'));
                                            $condArr['date(CallDate)']=$yesterday;

                            $conditions_new = "and date(cm_date) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                            $sum_query = "sum";

                            //                 $Abandconditions = "and date(AbandDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                            //                 $conditions1 = "and date(cm.CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                            // $view_date="date(t2.call_date) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                        }
                        if($view_type=="Weekly"){
                            $end = date('Y-m-d', strtotime('-6 day'));
                                            $condArr['date(CallDate) <=']=date('Y-m-d');
                                            $condArr['date(CallDate) >=']=$end;

                            $conditions_new = "and date(cm_date) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                            $sum_query = "sum";
                                           
                            // $Abandconditions = "and date(AbandDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                            //                 $conditions1 = "and date(cm.CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                            // $view_date="date(t2.call_date) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                        
                        }
                        if($view_type=="Monthly"){
                            $end = date('Y-m-d', strtotime('-30 day'));
                                            $condArr['date(CallDate) <=']=date('Y-m-d');
                                            $condArr['date(CallDate) >=']=$end;
                                          
                                            
                                            $conditions ="and MONTH(cm_date) = MONTH(CURDATE()) and YEAR(CallDate) = YEAR(CURDATE())";
                                            
                                            
                                            // $Abandconditions ="and MONTH(AbandDate) = MONTH(CURDATE()) and YEAR(AbandDate) = YEAR(CURDATE())";
                                            // $conditions1 ="and MONTH(cm.CallDate) = MONTH(CURDATE()) and YEAR(cm.CallDate) = YEAR(CURDATE())";
                                            // $view_date ="MONTH(t2.call_date) = MONTH(CURDATE()) and YEAR(t2.call_date) = YEAR(CURDATE())";
                                            
                                            
                                            
                                            
                        }
                        if($view_type=="Custom"){
                            $fdate = date('Y-m-d', strtotime($this->request->data['fdate']));
                            $ldate = date('Y-m-d', strtotime($this->request->data['ldate']));
                                            
                                            $condArr['date(CallDate) >=']=$fdate;
                                            $condArr['date(CallDate) <=']=$ldate;
                                            
                            $conditions_new = "and date(cm_date) between '$fdate' and '$ldate'";
                            $sum_query = "sum";
                                           
                            // $Abandconditions = "and date(AbandDate) between '$fdate' and '$ldate'";
                            //                 $conditions1 = "and date(cm.CallDate) between '$fdate' and '$ldate'";
                            //                 $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
                                                    
                            
                            /*
                                if($this->request->data['fdate'] !="" && $this->request->data['ldate'] !=""){
                                $view_date=$fdate."&nbsp;-&nbsp;".$ldate;
                                }
                                else{
                                    $view_date="";
                                }*/

                        }

                        if(empty($view_type))

                        {
                            $sum_query = "";
                        }
                                    
                    }
                    // outbound dashboard start
                    $Campagn  = $this->Session->read('campaignid');
			        $Campagn  = $this->Session->read('GroupId');

                    $dataArr = $this->CallRecord->query("SELECT * FROM registration_master WHERE company_id='$ClientId' limit 1");

                    $DialerConnectionPage = $dataArr['0']['registration_master']['DialerConnectionPage'];
			        $GroupId = $dataArr['0']['registration_master']['GroupId']; 
                    //echo $GroupId;die;
                    if(!empty($GroupId))
                    {
                    //$CampagnId ="and t2.campaign_id in ($Campagn)";
                    $CampagnId ="and t2.campaign_id in(".$GroupId.")";
                    }
                    // if(!empty($Campagn)){
                    //     $CampagnId ="and t2.campaign_id in ($Campagn)";
                    //     }
                    // $qry_check = "SELECT * FROM vicidial_log WHERE campaign_id IN  (".$GroupId.")";

                    // $this->vicidialLog->useDbConfig = 'db2';
                    // $outcheck = $this->vicidialLog->query($qry_check); 
                    // print_r($outcheck);die;                     
                    
                   $view_dat =" date(t2.call_date) = curdate() ";

                   $qry1 = "SELECT COUNT(*) `Total`,
                    SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
                    SUM(if(t2.`user` ='VDCL',1,0)) `NotAnswered`
                    FROM asterisk.vicidial_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $view_dat $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";

                   $attempt = "SELECT SUM(IF(t2.`called_count` ='1',1,0)) `attempt1`, 
                    SUM(IF(t2.`called_count` ='2',1,0)) `attempt2`,
                    SUM(IF(t2.`called_count` ='3',1,0)) `attempt3`
                    FROM asterisk.vicidial_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $view_dat $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";
 
                    $first_date_month = date("Y-m-01");
                    $curdate = date("Y-m-d");
                    $mtd_conditions =" date(t2.call_date) between '$first_date_month' AND '$curdate'";

                   $qry2 = "SELECT COUNT(*) `Total`,
                    SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
                    SUM(if(t2.`user` ='VDCL',1,0)) `NotAnswered`
                    FROM asterisk.vicidial_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $mtd_conditions $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";

                   $attempt2 = "SELECT SUM(IF(t2.`called_count` ='1',1,0)) `attempt1`, 
                    SUM(IF(t2.`called_count` ='2',1,0)) `attempt2`,
                    SUM(IF(t2.`called_count` ='3',1,0)) `attempt3`
                    FROM asterisk.vicidial_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $mtd_conditions $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";

                  $voc_connected_today = "SELECT SUM(if(t2.`user` ='VDCL',1,0)) `Today`
                    FROM asterisk.vicidial_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $view_dat $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";

                  $voc_connected_mtd = "SELECT SUM(if(t2.`user` ='VDCL',1,0)) `MTD`
                    FROM asterisk.vicidial_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $mtd_conditions $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";

                    $this->vicidialLog->useDbConfig = 'db2';
                    $connectivity_today = $this->vicidialLog->query($qry1);
                    $attempt_analysis_today = $this->vicidialLog->query($attempt);

                        //priint_r($connectivity_today);die;

                    $connectivity_mtd = $this->vicidialLog->query($qry2);
                    $attempt_analysis_mtd = $this->vicidialLog->query($attempt2);

                    $voc_today = $this->vicidialLog->query($voc_connected_today);
                    $voc_mtd = $this->vicidialLog->query($voc_connected_mtd);

                    //print_r($attempt_analysis_today);die;
                        
                    $this->set('connectivity_today',$connectivity_today);
                    $this->set('attempt_analysis_today',$attempt_analysis_today);

                    $this->set('connectivity_mtd',$connectivity_mtd);
                    $this->set('attempt_analysis_mtd',$attempt_analysis_mtd);

                    $this->set('voc_today',$voc_today);
                    $this->set('voc_mtd',$voc_mtd);

                    // outbound dashboard end

                    
                    //$this->set('consume',$consume);
                    $consume = $this->get_consume_data($conditions_new,$sum_query); 
                    $today_cs_details = $this->get_today_consume_data();
                    //print_r($today_cs_details);exit;
                    if(!empty($today_cs_details))
                    {
                        if(!empty($today_cs_details['total']))
                        {
                           // $consume = $consume+(int)$today_cs_details['total'];
                        }
                    }
                    
                    $this->set('consumed',$consume);
                    $this->set('opening_balance',$this->get_opening_bal());
                    $this->set('cs_pulse',$this->get_consume_pulse());
                    $this->set('today_cs_pulse',$today_cs_details);
                    $this->set('coll_bal',$this->get_collection());
                    
                    $this->new_dash($today_cs_details);
                }
                
                
	}
        
        
        
        
        public function new_dash($today_cs_details)
        {
            // Basant code start from here
                           
                            // for daywise data
                            $ClientId = $this->Session->read('companyid');

                            $list=array();
                            $days=array();


                            $month = date("m");
                            $year = date("Y");
                            //$today = date("d");

                            $today = date("Y-m-d");                              


                            $fd=$this->request->data['fdate'];
                            $ld=$this->request->data['ldate'];
                            if (isset($this->request->data['view_type']))
                            {
                                $view_type=$this->request->data['view_type'];
                                if($view_type=="Today"){
                    
                                    $view_date1 =" date(t2.call_date) = curdate()";
                                    $sum_query = "sum";

                                    // for week1, week2 ...

                                    $first_date_month = date("Y-m-01");

                                    $curdate = date("Y-m-d");

                                    $weeks_mtd_ticket_conditions =" DATE(calldate) between '$first_date_month' AND '$curdate'"; 


                                    $ticket_today_mtd_case =" DATE(CloseLoopingDate) = CURDATE() ";

                                    $openclose_ticket_qry_conditions = " DATE(cm.CallDate) = CURDATE() ";


                                }
                                if($view_type=="Yesterday"){
                                    
                                    $view_date1 ="  date(t2.call_date) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                                    $sum_query = "sum";
                                    // for week1, week2 ...

                                    $first_date_month = date("Y-m-01");

                                    $curdate = date('Y-m-d',strtotime("-1 days"));

                                    $weeks_mtd_ticket_conditions =" DATE(calldate) between '$first_date_month' AND '$curdate'"; 


                                    $ticket_today_mtd_case =" DATE(CloseLoopingDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)"; 

                                    $openclose_ticket_qry_conditions = " DATE(cm.CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)"; 
                                    
                                }
                                if($view_type=="Weekly"){
                                    $end = date('Y-m-d', strtotime('-6 day'));

                                    
                                                    $condArr['date(CallDate) <=']=date('Y-m-d');
                                                    $condArr['date(CallDate) >=']=$end;

                                    $view_date1 ="  date(t2.call_date) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                                    $sum_query = "sum"; 
                                    
                                    // for week1, week2 ...

                                    $first_date_month = $end;

                                    $curdate = date('Y-m-d');

                                    $weeks_mtd_ticket_conditions =" DATE(calldate) between '$first_date_month' AND '$curdate'"; 


                                    //$weeks_mtd_ticket_conditions =" DATE(calldate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()"; 
                                
                                    $ticket_today_mtd_case =" DATE(CloseLoopingDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()"; 


                                    $openclose_ticket_qry_conditions = " DATE(cm.CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()"; 
                                }
                                if($view_type=="Monthly"){

                                    $end = date('Y-m-d', strtotime('-30 day'));
                                    $condArr['date(CallDate) <=']=date('Y-m-d');
                                    $condArr['date(CallDate) >=']=$end;
                                
                                    
                                    $view_date1 = " MONTH(t2.call_date) = MONTH(CURDATE()) and YEAR(t2.call_date) = YEAR(CURDATE())"; 
                                    
                                    // for week1, week2 ...

                                    $first_date_month = $end;

                                    $curdate = date('Y-m-d');

                                    $weeks_mtd_ticket_conditions =" DATE(calldate) between '$first_date_month' AND '$curdate'"; 

                                    //$weeks_mtd_ticket_conditions =" DATE(calldate) = MONTH(CURDATE()) and YEAR(t2.call_date) = YEAR(CURDATE())"; 
                                    
                                    $ticket_today_mtd_case =" DATE(CloseLoopingDate) = MONTH(CURDATE()) and YEAR(t2.call_date) = YEAR(CURDATE())"; 

                                    $openclose_ticket_qry_conditions = " DATE(cm.CallDate)  = MONTH(CURDATE()) ";
                                }
                                if($view_type=="Custom"){

                                    $fdate = date('Y-m-d', strtotime($this->request->data['fdate']));
                                    $ldate = date('Y-m-d', strtotime($this->request->data['ldate']));
                                                    
                                        $condArr['date(CallDate) >=']=$fdate;
                                        $condArr['date(CallDate) <=']=$ldate;
                                                    
                                    $view_date1 ="  date(t2.call_date) between '$fdate' and '$ldate'";
                                    $sum_query = "sum";   
                                    
                                    // for week1, week2 ...


                                    if($ldate < $fdate)
                                    {
                                        $a = $fdate;
                                        $fdate = $ldate;
                                        
                                        $ldate = $a;
                                    }

                                    $weeks_mtd_ticket_conditions =" DATE(calldate) between '$fdate' and '$ldate'"; 

                                    $ticket_today_mtd_case =" DATE(CloseLoopingDate) between '$fdate' and '$ldate'";    

                                    $openclose_ticket_qry_conditions = " DATE(cm.CallDate) between '$fdate' and '$ldate' ";
                                }


                                if(empty($view_type))

                                {
                                    $sum_query = "";
                                }
                                            
                                            // if($callType ==="outbounds"){
                                            //     $this->view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld);
                                                
                                            // }else{
                                                
                                            //     $this->view_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld,$Abandconditions);
                                            
                                            // }
                                            
                            }

                            else
                            {
                                    $view_date1 =" date(t2.call_date) ='$today' ";

                                    // for week1, week2 ...

                                    $first_date_month = date("Y-m-01");

                                    $curdate = date("Y-m-d");

                                    $weeks_mtd_ticket_conditions =" DATE(calldate) between '$first_date_month' AND '$curdate'"; 


                                    //$weeks_mtd_ticket_conditions =" DATE(calldate) =  curdate()";

                                    $ticket_today_mtd_case =" DATE(CloseLoopingDate) =  curdate()";

                                    $openclose_ticket_qry_conditions = " DATE(cm.CallDate)= curdate()";

                            }


                            // $view_date1 ="date(t2.call_date) between  '$today' ";


                            // $qry1 = "SELECT COUNT(*) `Total`,
                            // SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
                            // SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`                            
                            // FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                            // WHERE $view_date1 $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL GROUP BY date(t2.call_date) ";
                                
                            $Campagn  = $this->Session->read('campaignid');
                            if(!empty($Campagn)){
                            $CampagnId ="and t2.campaign_id in ($Campagn)";
                            }
                            


                              $qry1 = "SELECT COUNT(*) `Total`,
                            SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
                            SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`,
                            date(t2.call_date) as gdate                            
                            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                            WHERE $view_date1 $CampagnId and  t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL GROUP BY date(t2.call_date) ";
                              


                                $this->vicidialCloserLog->useDbConfig = 'db2';
                            $graph_date= $this->vicidialCloserLog->query($qry1);
                                
                            $this->set('graph_date_wise',$graph_date);
                            
                                
                            // Ticket Case Analysis


                            // for Week1

                            $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
                            
                            $date=date_create($first_day_this_month);

                            date_add($date,date_interval_create_from_date_string("7 days"));

                            $w1=date_format($date,"Y-m-d");

                            

                            // $qry1 = "SELECT Category1,DAY(calldate) dayt FROM call_master cm WHERE
                            //  DATE(calldate)  BETWEEN '2022-06-28' AND '2022-07-05' AND clientId='$ClientId' 
                            // GROUP BY DAY(calldate),Category1 order by date(calldate)";

                            $qry1 = "SELECT Category1,DAY(calldate) dayt,date_format(calldate,'%b-%y') week_month_year FROM call_master cm WHERE
                            $weeks_mtd_ticket_conditions AND clientId='$ClientId' 
                             order by date(calldate)"; 

                            $week_data= $this->CallMaster->query($qry1);
                            $week_arr = array();
                            $category_arr = array();
                            $week_divider = array();
                            $wm_array = array();
                            
                            foreach($week_data as $wk_records)
                            {
                                
                                $day = (int) $wk_records['0']['dayt'];
                                $category = $wk_records['cm']['Category1'];
                                $category_arr[$category] = $category;
                                $week_month_year = $wk_records['0']['week_month_year'];
                                //$week_divider[$week_month_year] = $week_month_year;
                                if($day<=7)
                                {
                                    $week_arr[$week_month_year.'-wk1'][$category] +=1; 
                                    $week_divider[$week_month_year.'-wk1'] = $week_month_year.'-wk1';
                                }
                                else if($day<=14)
                                {
                                    $week_arr[$week_month_year.'-wk2'][$category] +=1; 
                                    $week_divider[$week_month_year.'-wk2'] = $week_month_year.'-wk2';
                                }
                                else if($day<=21)
                                {
                                    $week_arr[$week_month_year.'-wk3'][$category] +=1; 
                                    $week_divider[$week_month_year.'-wk3'] = $week_month_year.'-wk3';
                                }
                                else if($day<=28)
                                {
                                    $week_arr[$week_month_year.'-wk4'][$category] +=1; 
                                    $week_divider[$week_month_year.'-wk4'] = $week_month_year.'-wk4';
                                }
                                else if($day<=31)
                                {
                                    $week_arr[$week_month_year.'-wk5'][$category] +=1; 
                                    $week_divider[$week_month_year.'-wk5'] = $week_month_year.'-wk5';
                                }
                                
                                $week_arr['mtd'][$category] +=1; 
                                
                                
                            }
                            $week_divider['mtd'] = 'mtd';
                            $this->set('category_arr',$category_arr);
                            $this->set('week_arr',$week_arr);
                            $this->set('week_divider',$week_divider);
                            
                            
                            //print_r($week_divider); die;
                              


                               // for ticket case today,mtd

                               $tctm_today_qry="SELECT SUM(IF(CloseLoopCate1 = 'Done',1,0)) AS TClose , SUM(IF(CloseLoopCate1 = 'Pending',1,0)) AS TOpen FROM `call_master`
                               WHERE  DATE(CloseLoopingDate) = CURDATE() AND ClientId='$ClientId'";
                               
                               $tctm_today_row = $this->CallMaster->query($tctm_today_qry);

                               $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
                            

                               $current_date = date("Y-m-d");

                               $tctm_mtd_qry="SELECT SUM(IF(CloseLoopCate1 = 'Done',1,0)) AS TClose , SUM(IF(CloseLoopCate1 = 'Pending',1,0)) AS TOpen FROM `call_master`
                               WHERE DATE(CloseLoopingDate) BETWEEN ('$first_day_this_month') AND ('$current_date') AND ClientId='$ClientId' ";
                               
                               $tctm_mtd_row = $this->CallMaster->query($tctm_mtd_qry);

                               $this->set('tctm_today_row',$tctm_today_row);
                               $this->set('tctm_mtd_row',$tctm_mtd_row);


                                // start whatsapp 

                                $w_qry = "SELECT SUM(IF(closure_status = 'Satisfied',1,0)) AS SATISFIED,
                                SUM(IF(closure_status = 'Not Satisfied',1,0)) AS NOTSATISFIED FROM dialdesk_whatsapp_ticket WHERE  complete='1'";

                                $Closure_status = $this->WhatsappTicket->query($w_qry);
                                $this->set('Closure_status',$Closure_status);

                                // end whatsapp 

                                // open & close case status

                               /*  $openclose_ticket_qry="
                                 SELECT  SUM(IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1, IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0))) `outtat`,
                                  SUM(IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `intat`,
                                   SUM(IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL, 1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0))) `openouttat`,
                                    SUM(IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `openintat` 
                                    FROM call_master cm 
                                 INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId AND 
                                 CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,'')) 
                                 WHERE $openclose_ticket_qry_conditions AND cm.ClientId='$ClientId' 
                                 "; 
                                
                                  
                                $openclose_ticket_RecArr=$this->CallMaster->query($openclose_ticket_qry);*/

                                 //print_r($openclose_ticket_RecArr);exit;

                            //     $this->set('close_RecArr',$close_RecArr);
                             //   $this->set('open_RecArr',$openclose_ticket_RecArr);


                                // for ledger balance

                                //for opening Balance

                            //  agar aaj 1 hai  aur yesterday search karta hai to last month ka data show hoga

                            $condition_ledger = "" ;

                            if (isset($this->request->data['view_type']))
                            {
                                $type=$this->request->data['view_type'];        


                                if($type=="Yesterday")
                                {
                                    $dd = date("d");
                                    
                                    if($dd ==01)
                                    {
                                                $datestring= date("Y-m-d")." first day of last month";
                                                $dt=date_create($datestring);
                                                $last_month = $dt->format('m');                           
                                                $lastmonth = $dt->format('Y-m-d');   
                                                $finYear = date_format($lastmonth,"Y");
                                                
                                                $month = $lastmonth;

                                                $condition_ledger = " AND Month(bpp.createdate) = '$month' and Month(bpp.createdate) = '$finYear' ";

                                                
                                    }
                                    else
                                    {                    

                                                $fd=date_create(date('d-m-Y',strtotime("-1 days")));                          

                                                $finYear = date_format($fd,"Y");
                                                $month = date_format($fd,"m");

                                                $condition_ledger = " AND Month(bpp.createdate) = '$month' and Month(bpp.createdate) = '$finYear' ";

                                    }
                                }

                                elseif($type=="Custom")
                                {

                                                $fd=date_create($this->request->data['fdate']);
                                                $ld=date_create($this->request->data['ldate']);                            

                                                $finYear1 = date_format($fd,"Y");
                                                $month1 = date_format($fd,"m");

                                                $finYear2 = date_format($ld,"Y");
                                                $month2 = date_format($ld,"m");

                                                $fd=$this->request->data['fdate'];
                                                $ld=$this->request->data['ldate'];

                                                // BETWEEN QUERY

                                                $condition_ledger = " AND date(bpp.createdate)  between '$fd' and '$ld'";

                                }

                                else
                                // if($type=="Today")
                                {                

                                    $date11=date_create(date("Y-m-d"));

                                    $finYear = date_format($date11,"Y");
                                    $month = date_format($date11,"m");
                                    $condition_ledger = " AND Month(bpp.createdate) = '$month' and Month(bpp.createdate) = '$finYear' ";
                                }
                                

                            }
                            else
                            {
                                $date11=date_create(date("Y-m-d"));

                                $finYear = date_format($date11,"Y");
                                $month = date_format($date11,"m");           

                                $condition_ledger = " AND Month(bpp.createdate) = '$month' and Month(bpp.createdate) = '$finYear' ";
                                
                            }

                            
                        $opening_balance_qry = "SELECT talk_time FROM `billing_ledger` WHERE clientId='$ClientId' and  fin_year='$finYear' and fin_month='$month' limit 1";

                        $opening_balance_arr = $this->BillingLedger->query($opening_balance_qry);
                        $grnd = $opening_balance_arr['0']['ti']['grnd'];

                        // $this->set('opening_balance_fy',$grnd);

                        // for Billed

                        $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$ClientId' limit 1"); //part 1 query get cost_center from client id
                                

                        $cost_center = $cost_cat_arr[0]['cm']['cost_center']; //part 2


                        $sel_firstplan_qry = "select id,sum(grnd) grnd from tbl_invoice ti where cost_center='$cost_center' and  finance_year='$finYear' and month='$month' limit 1";
                        $sel_firstplan_arr = $this->InitialInvoice->query($sel_firstplan_qry);
                        $grnd_billed = $sel_firstplan_arr['0']['0']['grnd'];

                        // $this->set('billed_fy',$grnd_billed);




                        //paid part 4
                        $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
                        tbl_invoice ti 
                        INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
                        INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                        AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                        WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk'

                        $condition_ledger GROUP BY ti.bill_no"; 

                        $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);
                        $first_bill_coll = 0;
                        foreach($collection_det_arr as $coll_det)
                        {
                            $first_bill_coll += $coll_det['0']['bill_passed'];
                        }
                        //end paid part 4
                                            

                                            
                        $this->set('opening_balance_fy',$grnd);    
                        $this->set('billed_fy',$grnd_billed);
                        $this->set('paid_fy',$first_bill_coll);

                              //echo $ClientId;
                        
                        // for Active Plan Clientwise
                        $active_plan_arr = $this->BalanceMaster->query("SELECT * FROM `balance_master` WHERE clientId='$ClientId'");
                        
                        if(!empty($active_plan_arr))

                        {
                            $planId = $active_plan_arr[0]['balance_master']['PlanId'];                           
                        
                            // for Plan Master

                            $planmaster_arr = $this->PlanMaster->query("SELECT PlanName,PeriodType,CreditValue,InboundCallCharge FROM plan_master WHERE `Id`=$planId");

                            //print_r($planmaster_arr); die;
                                
                            $this->set('planmaster_arr',$planmaster_arr);
                        }
                        
                        
                      
                        //print_r($active_plan_arr); 
                        


                        // for index ledger data

                        $data = array();
                        $finYear = '2022-23';
                        $year = date('Y');
                        $arr = explode('-',$finYear);
                        $selectedMonth = $this->request->data['month'];
                        //$selectedMonth='Feb';
                        $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
                        $month_arr_swap =array_flip($month_arr) ;
                        $month_no = 0;
                        if(empty($selectedMonth))
                        {
                            $Nmonth =$month = date('M');
                            $month_no = $month_arr[date('M')];
                        }
                        else
                        {
                            $Nmonth =$month = $selectedMonth;
                            $month_no = $month_arr[$selectedMonth]; 
                        }
                        
                        $current_month = date('M');
                        $today_date = date('Y-m-d');
                        //$today_date = date('Y-03-01');



                            $dateInfo = date_parse_from_format('Y-M-d',"$year-$Nmonth-01");
                            $unixTimestamp = mktime(
                            $dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'],
                            $dateInfo['month'], $dateInfo['day'], $dateInfo['year'],
                            $dateInfo['is_dst']
                            );
                            $month_start_date = date('Y-m-d',$unixTimestamp);
                            $month_end_date = date("Y-m-t",$unixTimestamp);

                        //$month_start_date = '2022-03-11';
                            //$month_end_date = date("Y-m-d");
                            
                            if(in_array($month,array('Jan','Feb','Mar')))
                        {
                            if($arr[0]==date('Y') || $arr[1]==date('y'))
                            {
                                //$month=$month."-".date('y');
                                $month=$month."-".$arr[1];
                            }
                            else
                            {
                                $month=$month."-".$arr[1]; 
                            }
                            
                        }
                        else
                        {
                            $month=$month."-".($arr[1]-1);
                        }
                            
                            //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id in ('278','301','389') and  `status` = 'A' and is_dd_client='1' order by company_name");
                            //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='278' and  `status` = 'A' and is_dd_client='1'"); 
                            $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE is_dd_client='1' and company_id='$ClientId'  and  `status` = 'A' order by company_name"); 
                            foreach($client_det_arr as $clr)
                            {
                                $ClientId = $clr['rm']['company_id'];
                                $clientName = $clr['rm']['company_name'];
                                $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
                                $data[$clientName]['clientEmail'] = $clr['rm']['email'];
                                $first_bill = $data[$clientName]['first_bill'] =  $clr['rm']['first_bill'];
                                $data[$clientName]['clientId'] = $ClientId;
                                $sel_start_date = "SELECT start_date FROM `balance_master` bm WHERE clientId='$ClientId'";
                                $rsc_start_date=$this->RegistrationMaster->query($sel_start_date);
                                $record_start_date = $rsc_start_date['0']['bm']['start_date'];
                                if(strtotime($record_start_date)>strtotime($month_end_date))
                                {
                                    continue;
                                }
                                
                                
                                //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$ClientId'";exit;
                                $op_det_ledger = $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$ClientId'");
                                if(!empty($op_det_ledger))
                                {
                                    $talktime = $op_det_ledger['0']['bl']['talk_time'];
                                    if(1!=1)
                                    {
                                        $talktime = abs($talktime*1.18);
                                        $data[$clientName]['ledger_access_usage'] = round($talktime,2);
                                        $data[$clientName]['talk_time'] = 0;
                                    }
                                    else
                                    {
                                        $data[$clientName]['ledger_access_usage'] = 0;
                                        $data[$clientName]['talk_time'] = round($op_det_ledger['0']['bl']['talk_time'],2);
                                    }

                                    $data[$clientName]['ledger_sub'] = round($op_det_ledger['0']['bl']['subs'],2);
                                    
                                    $data[$clientName]['ledger_topup'] = round($op_det_ledger['0']['bl']['topup'],2);
                                    $data[$clientName]['ledger_setup'] = round($op_det_ledger['0']['bl']['setup_cost'],2);
                                    
                                }

                                $billing_opening_balance = "billing_opening_balance";
                                /*if($current_month!=$Nmonth)
                                {
                                    $billing_opening_balance = "billing_opening_balance_history";
                                }*/
                                $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$ClientId' and fin_year='$year' and  fin_month='$Nmonth'");
                                //print_r($op_det);exit;
                                /*if(!empty($op_det['0']['bob']['op_dd']))
                                {
                                    $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                                }
                                else
                                {
                                    $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$ClientId' and bill_start_date='$month_start_date'");
                                    if(!empty($op_det['0']['bob']['op_dd']))
                                    {
                                        $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                                    }
                                }*/
								$view_type2=$this->request->data['view_type2'];
                                                                $this->set('viewType2',$view_type2);
                                $cs_bal = 0; $open_till_bal = 0;
								
                                if(empty($view_type2) || $view_type2=="Today"){
                                    $Cstart_date = date('Y-m-01');
                                    $this->set('fromBilling',date('d-M-Y'));
                                    $this->set('toBilling',date('d-M-Y'));
                                    $this->set('fromConsume',date('Y-m-d'));
                                    $this->set('toConsume',date('Y-m-d'));
                                    //print_r($open_till_bal);exit;
                                    $consume_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' and DATE(cm_date) between date('$Cstart_date') and curdate()";
                                    $consume_data = $this->RegistrationMaster->query($consume_qry);
                                    $open_till_bal = !empty($consume_data[0][0]['total'])?$consume_data[0][0]['total']:0;
                                    
                                    if(!empty($today_cs_details['total']))
                                    {
                                        
                                            $cs_bal  =$today_cs_details['total'];
                                        
                                    }
                                    else
                                    {
                                        $cs_bal  =0;
                                    }
                                    //print_r($cs_bal);exit;
                                }
                                if($view_type2=="Yesterday"){
                                    
                                    $Cstart_date = date('Y-m-01');
                                    $consume_till_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' AND DATE(cm_date) BETWEEN date('$Cstart_date') AND subdate(curdate(),interval 2 day)";
                                    $open_till_data = $this->RegistrationMaster->query($consume_till_qry);
                                    $open_till_bal = !empty($open_till_data[0][0]['total'])?$open_till_data[0][0]['total']:0;
                                    //print_r($open_till_bal);exit;
                                    $consume_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' and DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) = subdate(curdate(),interval 1 day)";
                                    $consume_data = $this->RegistrationMaster->query($consume_qry);
                                    $cs_bal = !empty($consume_data[0][0]['total'])?$consume_data[0][0]['total']:0;
                                   // print_r($cs_bal);exit;
                                    $this->set('fromBilling',date('d-M-Y', strtotime('-1 day')));
                                    $this->set('toBilling',date('d-M-Y', strtotime('-1 day')));
                                    $this->set('fromConsume',date('Y-m-d', strtotime('-1 day')));
                                    $this->set('toConsume',date('Y-m-d', strtotime('-1 day')));
                                }
                                if($view_type2=="Weekly"){
                                    $Cstart_date = date('Y-m-01');
                                    $Cstart_date2 = date('Y-m-d');
                                    $end = date('Y-m-d', strtotime('-6 day'));
                                    if(strtotime($end)<strtotime($Cstart_date))
                                    {
                                        $end = date('Y-m-d');
                                        $Cstart_date2=$Cstart_date; 
                                    }
                                    // $condArr['date(CallDate) <=']=date('Y-m-d');
                                    // $condArr['date(CallDate) >=']=$end;
                                    // $Cstart_date = date('Y-m-01');
                                    
                                    if(strtotime($end)<strtotime($Cstart_date2))
                                    {
                                        $temp = $Cstart_date2;
                                        $Cstart_date2 = $end;
                                        $end = $temp;
                                    }
                                    
                                   $this->set('fromBilling',date('d-M-Y', strtotime($Cstart_date2)));
                                    $this->set('toBilling',date('d-M-Y', strtotime($end)));
                                    $this->set('fromConsume',date('Y-m-d', strtotime($Cstart_date2)));
                                    $this->set('toConsume',date('Y-m-d', strtotime($end)));
                                    
                                    
                                    $consume_till_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' AND DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) between '$Cstart_date' and subdate('$Cstart_date2',interval 1 day)";
                                    $open_till_data = $this->RegistrationMaster->query($consume_till_qry);
                                    $open_till_bal = !empty($open_till_data[0][0]['total'])?$open_till_data[0][0]['total']:0;
                                    $consume_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' and DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) between '$Cstart_date2' and '$end'";
                                    $consume_data = $this->RegistrationMaster->query($consume_qry);
                                    $cs_bal = !empty($consume_data[0][0]['total'])?$consume_data[0][0]['total']:0;
                                    if(!empty($today_cs_details['total']))
                                    {
                                        $cs_bal  +=$today_cs_details['total'];   
                                    }
                                                                                   
                                
                                }
                                if($view_type2=="Monthly"){

                                    

                                    $Cstart_date = date('Y-m-01');
                                    //$Cstart_date2 = date('Y-m-d');
                                    //$end = date('Y-m-d', strtotime('-30 day'));
                                    $Cstart_date2 = date('Y-m-01');
                                    $end = date('Y-m-d');
                                   
                                    if(strtotime($end)<strtotime($Cstart_date2))
                                    {
                                        $temp = $Cstart_date2;
                                        $Cstart_date2 = $end;
                                        $end = $temp;
                                    }
                                    
                                   $this->set('fromBilling',date('d-M-Y', strtotime($Cstart_date2)));
                                    $this->set('toBilling',date('d-M-Y', strtotime($end)));
                                    $this->set('fromConsume',date('Y-m-d', strtotime($Cstart_date2)));
                                    $this->set('toConsume',date('Y-m-d', strtotime($end)));
                                    
                                    
                                    //$consume_till_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' AND DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) between '$Cstart_date' and subdate('$Cstart_date2',interval 1 day)";
                                    //$open_till_data = $this->RegistrationMaster->query($consume_till_qry);
                                    //$open_till_bal = !empty($open_till_data[0][0]['total'])?$open_till_data[0][0]['total']:0;
                                    $consume_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' and DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) between '$Cstart_date2' and '$end'";
                                    $consume_data = $this->RegistrationMaster->query($consume_qry);
                                    $cs_bal = !empty($consume_data[0][0]['total'])?$consume_data[0][0]['total']:0;
                                    //print_r($cs_bal);exit;
                                    if(!empty($today_cs_details['total']))
                                    {
                                        $cs_bal  +=$today_cs_details['total'];   
                                    }
                                    //print_r($cs_bal);exit;                
                                }
                                if($view_type2=="Custom"){
                                    $Cstart_date = date('Y-m-01');
                                    $Cstart_date2 = date('Y-m-d', strtotime($this->request->data['fdate2']));
                                    $end = date('Y-m-d', strtotime($this->request->data['ldate2']));
                                     $this->set('fd2',$this->request->data['fdate2']);
                                    $this->set('ld2',$this->request->data['ldate2']);
                                   
                                    $this->set('fromBilling',date('d-M-Y', strtotime($Cstart_date2)));
                                    $this->set('toBilling',date('d-M-Y', strtotime($end)));
                                    $this->set('fromConsume',date('Y-m-d', strtotime($Cstart_date2)));
                                    $this->set('toConsume',date('Y-m-d', strtotime($end)));
                                    
                                    $consume_till_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' AND DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) between '$Cstart_date' and subdate('$Cstart_date2',interval 1 day)";
                                    $open_till_data = $this->RegistrationMaster->query($consume_till_qry);
                                    $open_till_bal = !empty($open_till_data[0][0]['total'])?$open_till_data[0][0]['total']:0;
                                    $consume_qry = "SELECT SUM(cm_total) total FROM `billing_consume_daily` WHERE client_id='$ClientId' and DATE(cm_date) >= date('$Cstart_date') AND DATE(cm_date) between '$Cstart_date2' and '$end'";
                                    $consume_data = $this->RegistrationMaster->query($consume_qry);
                                    $cs_bal = !empty($consume_data[0][0]['total'])?$consume_data[0][0]['total']:0;                
                                                    
                                    if(strtotime(date('Y-m-d'))<=strtotime($end))
									{
										if(!empty($today_cs_details['total']))
										{
											$cs_bal  +=$today_cs_details['total'];   
										}
									}

                                }
                                $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$ClientId' and fin_year='$year' and  fin_month='$Nmonth'");
                                //print_r($op_det);exit;
                                /*if(!empty($op_det['0']['bob']['op_dd']))
                                {
                                    $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                                }
                                else
                                {
                                    $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$ClientId' and bill_start_date='$month_start_date'");
                                    if(!empty($op_det['0']['bob']['op_dd']))
                                    {
                                        $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                                    }
                                }*/
                                $data[$clientName]['cs_bal'] = $cs_bal;
                                $data[$clientName]['open_till_bal'] = $open_till_bal;
                                
                                
                                    //$data[$clientName]['cs_bal'] = $op_det['0']['bob']['cs_bal'];
                                    //$data[$clientName]['fr_val'] = $op_det['0']['bob']['fr_val'];
                                    //$data[$clientName]['adv_val'] = $op_det['0']['bob']['adv_val'];

                                    $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$ClientId' limit 1"; 
                                    $bal_det = $this->RegistrationMaster->query($plan_get_qry);
                                    $planId = $bal_det['0']['bm']['PlanId'];
                                    $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                                    $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                                    //$FreeValue = $plan_det['0']['pm']['CreditValue'];
                                    $FreeValue = round($plan_det['0']['pm']['Balance']/12);
                                    $data[$clientName]['RentalAmount'] = $plan_det['0']['pm']['RentalAmount'];
                                    $data[$clientName]['Balance'] = $plan_det['0']['pm']['Balance'];
                                    $SetupCost = $plan_det['0']['pm']['SetupCost'];
                                    $developmentCost = $plan_det['0']['pm']['DevelopmentCost'];
                                    $start_date = $bal_det['0']['bm']['start_date']; 
                                    $end_date = date('Y-m-d');
                                    $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                                    $PeriodType = $plan_det['0']['pm']['PeriodType'];
                                    $Balance = $plan_det['0']['pm']['Balance'];
                                    $data[$clientName]['free_value'] = round($FreeValue,2);
                                    
                                    $no_of_month = 0;
                                    
                                    switch ($PeriodType) {
                                        case "YEAR":
                                            $pamnt= $RentalAmount; 
                                            $no_of_month = 365;
                                            break;
                                        case "Half YEAR":
                                            $pamnt= round($RentalAmount/2,2);
                                            $no_of_month = 180;
                                            break;
                                        case "Quater":
                                            $pamnt= round($RentalAmount/4,2);
                                            $no_of_month = 90; 
                                            break;
                                        default:
                                            $pamnt= round($RentalAmount/12,2);
                                            $no_of_month = 30;
                                        }
                                        
                                        $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$ClientId' limit 1");
                                        
                                            if($first_bill=='0' || $first_bill==0)
                                            {
                                                $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                                                $data[$clientName]['new_plan_setup_cost'] = $SetupCost;
                                                $data[$clientName]['new_plan_dev_cost'] = $developmentCost;
                                                $data[$clientName]['first_plan_value'] = ($pamnt+$SetupCost+$developmentCost);
                                                $data[$clientName]['first_plan_value_with_gst'] = round(($pamnt+$SetupCost+$developmentCost)*1.18,2);
                                                $data[$clientName]['sub_start_date'] = $start_date;
                                                $data[$clientName]['sub_end_date'] = date('Y-m-d',strtotime($start_date." +$no_of_month days"));;  
                                                $data[$clientName]['due_date'] = 'Immediate';  
                                            }
                                            else
                                            {
                                                $cost_center = $cost_cat_arr[0]['cm']['cost_center'];
                                                $sel_firstplan_qry = "select id,grnd from tbl_invoice ti where cost_center='$cost_center' and category='first_bill' and  finance_year='$finYear' and month='$month' limit 1";
                                                $sel_firstplan_arr = $this->InitialInvoice->query($sel_firstplan_qry);
                                                $intital_id = $sel_firstplan_arr['0']['ti']['id'];

                                                $sel_first_sub_qry = "SELECT amount FROM `inv_particulars` ip WHERE initial_id='$intital_id' and sub_category='subscription' limit 1";
                                                $bill_subs_arr = $this->InitialInvoice->query($sel_first_sub_qry);
                                                $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                                                $data[$clientName]['first_plan_value'] = $sel_firstplan_arr['0']['ti']['total'];
                                                $data[$clientName]['first_plan_value_with_gst'] = $sel_firstplan_arr['0']['ti']['grnd'];
                                                $data[$clientName]['firstbilled'] += $sel_firstplan_arr['0']['ti']['grnd'];
                                                
                                                //$data[$clientName]['subbilled'] += $sel_firstplan_arr['0']['ti']['grnd']; 
                                                
                                                $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
                                                tbl_invoice ti 
                                                INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
                                                INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                                                AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                                                WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('first_bill')
                                                AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                                                $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);
                                                $first_bill_coll = 0;
                                                foreach($collection_det_arr as $coll_det)
                                                {
                                                    $first_bill_coll += $coll_det['0']['bill_passed'];
                                                }
                                                $data[$clientName]['first_plan_coll'] = round($first_bill_coll,2);
                                                $first_bill_coll_ntgst = round(($first_bill_coll/118)*100,2);
                                                $first_subs = $first_bill_coll_ntgst- $SetupCost- $developmentCost;
                                                if($first_subs>0)
                                                {
                                                    $data[$clientName]['first_plan_subscoll'] = round($first_subs*1.18,2);
                                                }
                                            }
                                    

                                
                                
                                
                                
                                foreach($cost_cat_arr as $cost)
                                {
                                    $cost_center = $cost['cm']['cost_center'];  
                                    $data[$clientName]['cost_center'] = $cost_center;
                                    $data[$clientName]['month'] = $month;

                                    $sel_setup_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Setup Cost','Development Cost') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
                                    $bill_set_arr = $this->InitialInvoice->query($sel_setup_qry);
                                    foreach($bill_set_arr as $inv)
                                    {    $data[$clientName]['setupbilled'] += $inv['ti']['grnd'];    }    

                                    $selec_setup_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
                                        tbl_invoice ti 
                                        INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
                                        INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                                        AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                                        WHERE   bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Setup Cost','Development Cost')
                                        AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                                        $collection_setup_arr = $this->BillMaster->query($selec_setup_payment_qry);
                                        foreach($collection_setup_arr as $coll_det)
                                        {    $data[$clientName]['setupcoll'] += $coll_det['0']['bill_passed']; }

                                    $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
                                    $bill_det_arr = $this->InitialInvoice->query($sel_billing_qry);


                                    foreach($bill_det_arr as $inv)
                                    {
                                        $data[$clientName]['billed'] += $inv['ti']['grnd'];
                                        $fin_year = $inv['ti']['finance_year'];
                                        $company_name = $cost['cm']['company_name'];
                                        $branch = $inv['ti']['branch_name'];
                                        $bill_no = $inv['ti']['bill_no'];
                                        //$net_amount = $inv['ti']['total'];
                                    }

                                    $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
                                    tbl_invoice ti 
                                    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
                                    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                                    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                                    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Talk Time','Topup')
                                    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                                        $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                                        foreach($collection_det_arr as $coll_det)
                                        {
                                            //$data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                                            $data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                                        }

                                    $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
                                    $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);

                                    $subs_coll = 0; $subs_coll2 = 0;
                                    foreach($bill_subs_arr as $inv)
                                    {
                                        $fin_year = $inv['ti']['finance_year'];
                                        $company_name = $cost['cm']['company_name'];
                                        $branch = $inv['ti']['branch_name'];
                                        $bill_no = $inv['ti']['bill_no'];
                                        $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
                                    
                                    }

                                    $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
                                        tbl_invoice ti 
                                        INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
                                        INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                                        AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                                        WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
                                        AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                                        $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                                        foreach($collection_det_arr as $coll_det)
                                        {
                                            //echo $month;exit;
                                            $subs_coll +=$coll_det['0']['bill_passed'];
                                            /*if($coll_det['ti']['month']==$month)
                                            {
                                                //$subs_coll += $coll_det['bpp']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                                                $subs_coll += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
                                            }
                                            else
                                            {
                                            // $data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                                                //$data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                                                $subs_coll2 +=$coll_det['0']['bill_passed'];
                                            }*/
                                        }

                                        $selec_all_payment_qry_for_release = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
                                        tbl_invoice ti 
                                        INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
                                        INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                                        AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                                        WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
                                        AND ti.month='$month'   GROUP BY ti.bill_no"; 
                                        $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry_for_release);
                                        $subs_coll3 =0;
                                        foreach($collection_det_arr as $coll_det)
                                        {
                                            $subs_coll3 += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
                                        }
                                        if($subs_coll3<$FreeValue)
                                            {
                                                $data[$clientName]['fr_val'] = round($subs_coll3,2);
                                            }
                                            else
                                            {
                                                $data[$clientName]['fr_val'] = round($FreeValue,2);
                                            }
                                    

                                    //echo $subs_coll;exit;
                                    $data[$clientName]['subs_coll'] = round($subs_coll+$subs_coll2,2);
                                    


                                    $monthlyFreeValue = 0;

                                    $start_date1 = strtotime($start_date);
                                    if($start_date1<strtotime('2022-01-01'))
                                    {
                                    $start_date1 = strtotime(date('2022-01-d',$start_date1));
                                    }
                                    //exit;
                                    $datediff = strtotime($end_date) - $start_date1;
                                    $noofday = round($datediff / (60 * 60 * 24));
                                    $subs_penP =0;
                                    $NewRentalAmount =0;
                                    $no_of_month = 0;
                                    $no_of_days = 0;
                                    if(strtolower($PeriodType)==strtolower('MONTH'))
                                    {
                                        $subs_penP = ceil($noofday/30);
                                        $NewRentalAmount = $RentalAmount/12;
                                        $monthlyFreeValue = $Balance;
                                        $no_of_month = 1;
                                        $no_of_days = 30;
                                    }
                                    else if(strtolower($PeriodType)==strtolower('Quater'))
                                    {
                                        $subs_penP = ceil($noofday/90);
                                        $NewRentalAmount = $RentalAmount/4;
                                        $monthlyFreeValue = round($Balance/3,2);
                                        $no_of_month = 3;
                                        $no_of_days =90;
                                    }
                                    else if(strtolower($PeriodType)==strtolower('YEAR'))
                                    {
                                        $subs_penP = round($noofday/365);
                                        $NewRentalAmount = $RentalAmount/1;
                                        $monthlyFreeValue = round($Balance/12,2);
                                        $no_of_month = 12;
                                        $no_of_days = 365;
                                    }
                                    if($subs_penP>=1)
                                    {
                                    $subs_penP = 1; 
                                    }

                                    $sub_amt =round($NewRentalAmount*$subs_penP*1.18,2);
                                    $subs_val = round($sub_amt-$subs_coll,2);
                                    
                                    if($subs_val>0 && strtotime($today_date)>=strtotime($start_date))
                                    {
                                        $last_month = $start_date;
                                        //$today_date = '2022-05-17'; 
                                        

                                        
                                        // $new_start_Date = date('Y-m-01');
                                        //$new_end_date = date('Y-m-t');
                                        $flag = true;
                                        while(strtotime($last_month)<=strtotime($today_date))
                                        {
                                            $last_month = date('Y-m-d',strtotime($last_month." +$no_of_days days"));  
                                            if(strtotime($last_month)>strtotime(date('Y-m-01')) && strtotime($last_month)<=strtotime($today_date))
                                            {
                                                $data[$clientName]['subs_val'] = $subs_val; 
                                                $data[$clientName]['due_date'] = 'Immediate';
                                                $data[$clientName]['sub_start_date'] = $last_month; 
                                                $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($last_month." +$no_of_days days"));
                                                $flag = false;
                                                break;
                                            }
                                            //echo '<br/>';
                                        }
                                        //exit;
                                        $due_date = date('Y-m-d',strtotime($last_month.' -30 days')); 
                                        //echo "$due_date $last_month" ; exit;

                                        if($flag && strtotime($today_date)>= strtotime($due_date) )
                                        {
                                            $data[$clientName]['subs_val'] = $subs_val; 
                                            $data[$clientName]['due_date'] = date('d-m-Y',strtotime($last_month." -1 days"));
                                            $data[$clientName]['sub_start_date'] = $last_month; 
                                            $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($last_month." +$no_of_days days"));
                                        }
                                        
                                    }
                                }
                                
                                
                                    
                            }
                            
                        
                            $index_ledger_data=$data;
                                
                        
                        $this->set('index_ledger_data',$index_ledger_data);

                           // echo $ClientId; 

                           $fd=$this->request->data['fdate'];
                           $ld=$this->request->data['ldate'];
                            if (isset($this->request->data['view_type']))
                            {
                                $view_type=$this->request->data['view_type'];
                                if($view_type=="Today")
                                {
                    
                                    $ticket_conditions = "and date(CallDate) = curdate()";
                                }
                                if($view_type=="Yesterday")
                                {
                               
                                    $ticket_conditions = "and date(CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                                                
                                }
                                if($view_type=="Weekly")
                                {
                                    $end = date('Y-m-d', strtotime('-6 day'));
                                                    $condArr['date(CallDate) <=']=date('Y-m-d');
                                                    $condArr['date(CallDate) >=']=$end;
                                    $ticket_conditions = "and date(CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                                                    
                                }
                                if($view_type=="Monthly")
                                {
                                                    $end = date('Y-m-d', strtotime('-30 day'));
                                                    $condArr['date(CallDate) <=']=date('Y-m-d');
                                                    $condArr['date(CallDate) >=']=$end;          
                                                    
                                                    $ticket_conditions ="and MONTH(CallDate) = MONTH(CURDATE()) and YEAR(CallDate) = YEAR(CURDATE())";
                                                      
                                                    
                                }
                                if($view_type=="Custom")
                                {
                                    $fdate = date('Y-m-d', strtotime($this->request->data['fdate']));
                                    $ldate = date('Y-m-d', strtotime($this->request->data['ldate']));
                                                    
                                                    $condArr['date(CallDate) >=']=$fdate;
                                                    $condArr['date(CallDate) <=']=$ldate;
                                                    
                                    $ticket_conditions = "and date(CallDate) between '$fdate' and '$ldate'";
                                                   
                                    
                                }
                                            
                                if($callType ==="outbounds"){

                                    
                                    
                                    // $ticket_by_source_call_array = $this->CallMasterOut->query("SELECT SUM(IF (CloseLoopingDate !='',1,0) ) AS OPEN , SUM(IF (CloseLoopingDate ='',1,0) ) AS CLOSE  FROM call_master_out cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,'')) WHERE cm.ClientId='$ClientId'  $ticket_conditions ");    
                                    
                                }else{
                                    
                                      
                                    /*$ticket_by_source_call_array = $this->CallMaster->query("SELECT SUM(IF (CloseLoopingDate !='',1,0) ) AS OPEN , SUM(IF (CloseLoopingDate ='',1,0) ) AS CLOSE,IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,
                        1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `overdue`  FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,'')) WHERE cm.ClientId='$ClientId'  $ticket_conditions ");  */
                                    //echo "SELECT SUM(IF (CloseLoopingDate !='',1,0) ) AS OPEN , SUM(IF (CloseLoopingDate ='',1,0) ) AS CLOSE  FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,'')) WHERE cm.ClientId='$ClientId'  $ticket_conditions "; die;
                                }
                                            
                            }
                            else
                            {
                                    $ticket_conditions = "and date(CallDate) = curdate()";
                                                                                
                                    if($callType ==="outbounds"){
                                        
                                        //$ticket_by_source_call_array = $this->CallMasterOut->query("SELECT SUM(IF (CloseLoopingDate !='',1,0) ) AS OPEN , SUM(IF (CloseLoopingDate ='',1,0) ) AS CLOSE  FROM call_master_out cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,'')) WHERE cm.ClientId='$ClientId'  $ticket_conditions ");      
                                    }else{
                                        //$ticket_by_source_call_array = $this->CallMaster->query("SELECT SUM(IF (CloseLoopingDate !='',1,0) ) AS OPEN , SUM(IF (CloseLoopingDate ='',1,0) ) AS CLOSE  FROM call_master cm  WHERE cm.ClientId='$ClientId'  $ticket_conditions "); 
                                        
            
                                    }
                                            
                            }

                        // Ticket By Source
                       $tbl_time = array();
                       $qryT = "SELECT * FROM tbl_time WHERE clientId='$ClientId'";
                       $ticket_time = $this->TimePeriod->query($qryT);
                       foreach($ticket_time as $time)
                       {

                            $Category1 = $time['tbl_time']['Category1'];
                            //$tbl_time[$Category1]['time_Hours'] = $time['tbl_time']['time_Hours'];

                            $qry = "SELECT * FROM call_master WHERE clientId='$ClientId' limit 10 ";
                            $ticket = $this->CallMaster->query($qry);

                            // $ticket = $this->CallMaster->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
                            // print_r($ticket);die;
                            
                    
                            // foreach($ticket as $tic)
                            // {
                            //     $date = $tic['call_master']['CloseLoopingDate'];
                            //     $date = strtotime($date);
                            //     $close_date =  date('H', $date);

                            //     if(strtotime($tic['call_master']['CloseLoopingDate']) > 0)
                            //     {
                            //         $tbl_time[$Category1]['close'] += count($tic['call_master']['Id']);
                                
                            //     }
                            //     else if($close_date > $tbl_time[$Category1]['time_Hours'])
                            //     {
                            //         $tbl_time[$Category1]['close'] += count($tic['call_master']['Id']);

                            //     }else{

                            //         $tbl_time[$Category1]['open'] += count($tic['call_master']['Id']);
                            //     }
                                
                            //     $total_close += $tbl_time[$Category1]['close'];
                            //     $total_open  += $tbl_time[$Category1]['open'];

                            // }


                       }
                       //print_r($tbl_time);die;
                      
                        
                       

                        // $qry2 = "SELECT SUM(IF (CloseLoopingDate ='',1,0) ) AS OPEN,SUM(IF (CloseLoopingDate !='',1,0) ) AS CLOSE FROM call_master cm 
                        // WHERE clientId='$ClientId' and Category1 ='Complaint' $ticket_conditions ";

                        // $ticket_by_comp = $this->CallMaster->query($qry2);

                        // $qry3 = "SELECT SUM(IF (CloseLoopingDate ='',1,0) ) AS OPEN,SUM(IF (CloseLoopingDate !='',1,0) ) AS CLOSE FROM call_master cm 
                        // WHERE clientId='$ClientId' and Category1 ='Sales' $ticket_conditions ";

                        // $ticket_by_sale = $this->CallMaster->query($qry3); 


                     

                        //print_r($ticket_by_sale); 
                        
                        // die;


                        $this->set('ticket_by_source_call_array',$ticket_by_source_call_array);
                        $this->set('ticket_by_source_email_array',$ticket_by_source_email_array);
                        $this->set('ticket_by_enq',$ticket_by_enq);
                        $this->set('ticket_by_comp',$ticket_by_comp);
                        $this->set('ticket_by_sale',$ticket_by_sale);

                        $this->set('ClientId',$ClientId);



                   // Basant Code End Here
        }
        
        
        
        
        
        
        public function get_opening_bal()
        {
            
                $ClientId = $this->Session->read('companyid');
                $year = date('Y');
                $month = date('M');
                $qry_opening = "SELECT * FROM billing_ledger bl where clientId='$ClientId' AND fin_year='$year' AND fin_month='$month'  limit 1";
                $BalanceMaster_det = $this->CallMaster->query($qry_opening);
                
                $talk_time = $BalanceMaster_det['0']['bl']['talk_time'];
                
                $op = $talk_time;
                $op = $op;
                
                //$Plan_Det = $this->CallMaster->query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1");
                //$PlanDetails = $Plan_Det['0']['plan_master'];
                //return $PlanDetails['CreditValue'];
                return $op;
            
        }
        
        public function get_today_consume_data()
        {
            $ClientId = $this->Session->read('companyid');   
            $last_date = date('Y-m-d');
            $bal_qry = "select * from `balance_master` where clientId='$ClientId'  limit 1";
            //$BalanceMasterRsc = mysqli_query($dd,$bal_qry);
            $BalanceMasterRsc =$this->RegistrationMaster->query($bal_qry);
            $BalanceMaster = $BalanceMasterRsc['0']['balance_master'];
            //print_r($BalanceMaster);exit;

          
            $cm_total = 0;
            $ib_pulse = 0;      $ib_secs=0;         $ib_charge = 0;     $ib_flat = 0;       $ib_total = 0;      $ib_pulse_per_call = 60;
            $ibn_pulse = 0;     $ibn_secs=0;         $ibn_charge = 0;    $ibn_flat = 0;      $ibn_total = 0;     $ibn_pulse_per_call = 60;
            $ob_pulse = 0;      $ob_secs=0;         $ob_charge = 0;     $ob_flat = 0;       $ob_total = 0;      $ob_pulse_per_call = 60;
            $ivr_pulse = 0;     $ivr_secs=0;         $ivr_charge = 0;    $ivr_flat = 0;      $ivr_total = 0;     //$ivr_pulse_per_call = 60;
            $miss_pulse = 0;    $miss_secs=0;         $miss_charge = 0;   $miss_flat = 0;     $miss_total = 0;    //$miss_pulse_per_call = 60;
            $vfo_pulse = 0;     $vfo_secs=0;         $vfo_charge = 0;    $vfo_flat = 0;      $vfo_total = 0;     //$vfo_pulse_per_call = 60;
            $sms_pulse = 0;     $sms_secs=0;         $sms_charge = 0;    $sms_flat = 0;      $sms_total = 0;     $sms_pulse_per_call = 60;
            $email_pulse = 0;   $email_secs=0;         $email_charge = 0;  $email_flat = 0;    $email_total = 0;   //$email_pulse_per_call = 60;
    
    
            

        if($BalanceMaster['PlanId'] !="")
        {
            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$ClientId'"));
            
            $Campagn=$ClientInfo['RegistrationMaster']['campaignid']; 

            if(empty($Campagn))
            {
                
                $Campagn = "''";

            }
        


            $PlanId = $BalanceMaster['PlanId'];
            $plan_det_qry = "select * from `plan_master` where Id='$PlanId' limit 1";
            $PlanDetailsRsc = $this->RegistrationMaster->query($plan_det_qry);
            $PlanDetails = $PlanDetailsRsc['0']['plan_master'];
            
            
            
            
            $ib_charge = $PlanDetails['InboundCallCharge'];
            if($PlanDetails['IB_Call_Charge']=='Minute')
            {
                $ib_flat = 0;
            }
            else if($PlanDetails['IB_Call_Charge']=='Second')
            {
                $ib_flat = 1;
            }
            else if($PlanDetails['IB_Call_Charge']=='Minute/Second')
            {
                $ib_flat = 2;
            }
            
            $ibn_charge = $PlanDetails['InboundCallChargeNight'];    
            if($PlanDetails['IB_Call_Charge']=='Minute')
            {
                $ibn_flat = 0;
            }
            else if($PlanDetails['IB_Call_Charge']=='Second')
            {
                $ibn_flat = 1;
            }
            else if($PlanDetails['IB_Call_Charge']=='Minute/Second')
            {
                $ibn_flat = 2;
            }
            
            $ob_charge = $PlanDetails['OutboundCallCharge'];
            if($PlanDetails['OB_Call_Charge']=='Minute')
            {
                $ob_flat = 0;
            }
            else if($PlanDetails['OB_Call_Charge']=='Second')
            {
                $ob_flat = 1;
            }
            else if($PlanDetails['OB_Call_Charge']=='Minute/Second')
            {
                $ob_flat = 2;
            }
            
            $ivr_charge = $PlanDetails['IVR_Charge'];    
            $ivr_flat = 0;
            $miss_charge = $PlanDetails['MissCallCharge'];   
            $miss_flat = 0;
            $vfo_charge = $PlanDetails['VFOCallCharge'];    
            $vfo_flat = 0;
            $sms_charge = $PlanDetails['SMSCharge'];    
            $sms_flat = 0;
            $email_charge = $PlanDetails['EmailCharge'];  
            $email_flat = 0;
            
            $IvrQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom,uniqueid FROM `rx_log` WHERE clientId='$ClientId'  AND date(call_time) = '$last_date'";
            $IvrDetails = $this->RegistrationMaster->query($IvrQry);

            
            $SMSQuery = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$ClientId' AND DedType='SMS' AND date(CallDate) = '$last_date'  ";
            $SMSDetails = $this->RegistrationMaster->query($SMSQuery);
            
            $EmailQry = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$ClientId' AND DedType='Email' AND date(CallDate) = '$last_date'";
            $EmailDetails = $this->RegistrationMaster->query($EmailQry);
            
            //$MissQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom FROM `billing_master` WHERE clientId='$ClientId'  AND date(CallDate) = '$last_date'";
            //$MissDetails = $this->RegistrationMaster->query($MissQry);
            
            // Inbound Call duration details
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $InboundQry = "select length_in_sec,queue_seconds,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_closer_log` where user !='VDCL' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
            $InboundDetails=$this->vicidialCloserLog->query($InboundQry);
            
            $OutboundQry = "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
            $OutboundDetails=$this->vicidialCloserLog->query($OutboundQry);
            
            //$VFOQry = "SELECT DATE_FORMAT(calltime,'%d %b %y') `CallDate1`,calltime CallDate,source_number, uniqueid FROM sbarro_data WHERE clientId='$ClientId'  AND DATE(calltime) = '$last_date'";
            //$VfoDetails = $this->vicidialCloserLog->query($VFOQry);

        
            
            
            
        
            foreach($InboundDetails as $key=>$inbDurArrRsc)
            {
                //print_r($inbDurArrRsc);exit; 
                $inbDurArr = $inbDurArrRsc['vicidial_closer_log'];
                $call_duration = $inbDurArr['length_in_sec']-$inbDurArr['queue_seconds'];
                $call_pulse = 0;
                $call_rate = 0;
                $call_pulsesec = 0;
                
                if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
                                || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<=strtotime('08:00:00'))
                {
                    if($ibn_flat==0)
                    {
                        $call_pulse = ceil($call_duration/$ibn_pulse_per_call);
                        $call_rate = $call_pulse*$ibn_charge;
                    }
                    else if($ibn_flat==1)
                    {
                        $call_pulsesec = $call_duration;
                        $call_rate = $call_pulsesec*$ibn_charge;
                    }
                    else
                    {
                        $call_pulse = 1;
                        $call_rate = $ibn_charge;
                        if($call_duration>$ibn_pulse_per_call)
                        {
                            $call_duration=($call_duration-$ibn_pulse_per_call);
                            $call_rate += ($call_duration*($ibn_charge/$ibn_pulse_per_call));
                            $call_pulsesec = $call_duration;
                        }
                    }

                    $ibn_pulse += $call_pulse;
                    $ibn_secs += $call_pulsesec;
                    $ibn_total += $call_rate;
                }
                else
                {
                    if($ib_flat==0)
                    {
                        $call_pulse = ceil($call_duration/$ib_pulse_per_call);
                        $call_rate = $call_pulse*$ib_charge;
                    }
                    else if($ib_flat==1)
                    {
                        $call_pulsesec = $call_duration;
                        $call_rate = $call_pulsesec*$ib_charge;
                    }
                    else
                    {
                        $call_pulse = 1;
                        $call_rate = $ib_charge;
                        if($call_duration>$ib_pulse_per_call)
                        {
                            $call_duration=($call_duration-$ib_pulse_per_call);
                            $call_rate += ($call_duration*($ib_charge/$ib_pulse_per_call));
                            $call_pulsesec = $call_duration;
                        }
                    }

                    $ib_pulse += $call_pulse;
                    $ib_secs += $call_pulsesec;
                    $ib_total += $call_rate;
                }
                
            }
            
            
            foreach($OutboundDetails as $key=>$outDurArrRsc)        
            {
                $outDurArr = $outDurArrRsc['vicidial_log'];
                $call_duration = $outDurArr['length_in_sec'];
                $call_pulse = 0;
                $call_rate = 0;
                $call_pulsesec = 0;
                
                if($ob_flat==0)
                {
                    $call_pulse = ceil($call_duration/$ob_pulse_per_call);
                    $call_rate = $call_pulse*$ob_charge;
                }
                else if($ob_flat==1)
                {
                    $call_pulsesec = $call_duration;
                    $call_rate = $call_pulsesec*$ob_charge;
                }
                else
                {
                    $call_pulse = 1;
                    $call_rate = $ob_charge;
                    if($call_duration>$ob_pulse_per_call)
                    {
                        $call_duration=($call_duration-$ob_pulse_per_call);
                        $call_rate += ($call_duration*($ob_charge/$ob_pulse_per_call));
                        $call_pulsesec = $call_duration;
                    }
                }

                $ob_pulse += $call_pulse;
                $ob_secs += $call_pulsesec;
                $ob_total += $call_rate;
            }
            
            $ivr_uniqueid_arr = array();
            
            
            foreach($IvrDetails as $key=>$ivrArrRsc)        
            {
                $ivrArr =$ivrArrRsc['rx_log'];
                $uniqueid = $ivrArr['uniqueid'];
                $ivr_uniqueid_arr[$uniqueid] = $uniqueid;
            }
            $ivr_unit = count($ivr_uniqueid_arr); 
            $ivr_rate = $ivr_unit*$ivr_charge;
            $ivr_total = $ivr_rate;
            $ivr_pulse = $ivr_unit;
            
            
            foreach($SMSDetails as $key=>$smsArrRsc)        
            {
                $smsArr = $smsArrRsc['billing_master'];
                $smsChar = $smsArr['Duration'];
                
                $sms_unit =$smsArr['Unit'];;
                $sms_rate = 0;
                $sms_pulsesec = 0;
                
                //        if($sms_flat==0)
                //        {
                //            $sms_unit = ceil($smsChar/60);
                //            $sms_rate = ceil($sms_unit*$sms_charge);
                //        }
                //        else if($sms_flat==1)
                //        {
                //            $sms_pulsesec = $smsChar;
                //            $sms_rate = $sms_pulsesec*$sms_charge;
                //        }
                //        else
                //        {
                //            $sms_unit = 1;
                //            $sms_rate = $sms_charge;
                //            if($smsChar>60)
                //            {
                //                $smsChar=($smsChar-60);
                //                $sms_rate += ($smsChar*($sms_charge/60));
                //                $sms_pulsesec = $smsChar;
                //            }
                //        }

                $sms_pulse += $sms_unit;
                $sms_secs += $smsChar;
                $sms_total += $sms_charge*$sms_unit;
            }
    
            $EmailUnit = 0;
            
            foreach($EmailDetails as $key=>$emailRsc)        
            {
                $emailArr = $emailRsc['billing_master'];
                $EmailUnit = $emailArr['Unit'];
                $email_rate = $EmailUnit*$email_charge;
                $email_pulse    += $EmailUnit;
                $email_total      += $email_rate;
            }
            
            
            foreach($MissDetails as $key=>$misArr)        
            {
                $MissUnit = $misArr['Unit'];
                $email_rate = ceil($MissUnit*$miss_charge);
                $miss_pulse    += $MissUnit;
                $miss_total      += $email_rate;
            }
            $ib = round($ib_pulse+$ibn_pulse+($ib_secs/60)+($ibn_secs/60));
            $ob = round($ob_pulse+($ob_secs/60));
            $cm_total = round($ib_total+$ibn_total+$ob_total+$ivr_total+$miss_total+$vfo_total+$sms_total+$email_total,3);
            $return =  array('total'=>$cm_total,'ib'=>$ib,'ob'=>$ob,'sms'=>$sms_pulse,'email'=>$email_pulse,'ivr'=>$ivr_pulse,'miss'=>$miss_pulse,'vfo'=>$vfo_pulse);
            //print_r($return);exit;
        return $return;
        }


            
            return array('total'=>0,'ib'=>0,'ob'=>0,'sms'=>0,'email'=>0,'ivr'=>0,'miss'=>0,'vfo'=>0); exit;
}
        
        public function get_consume_data1()
        {
            
            $ClientId = $this->Session->read('companyid');
            $Nmonth = date('M');
            $year = date("Y");
            $qry = "select * from billing_opening_balance where clientId='$ClientId' and fin_year='$year' and  fin_month='$Nmonth' limit 1";

            $BalanceMaster_det = $this->CallMaster->query($qry);
            $BalanceMaster = round($BalanceMaster_det['0']['billing_opening_balance']['cs_bal']);
                //$Plan_Det = $this->CallMaster->query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1");
                //$PlanDetails = $Plan_Det['0']['plan_master'];
                //return $PlanDetails['CreditValue'];
                return $BalanceMaster;
                exit;
            
        } 

        public function get_consume_data($conditions,$sum_query)
        {

            
            $ClientId = $this->Session->read('companyid');

            if(empty($sum_query))
            {
                    $qry = "select *,cm_total AS CV_MTD from billing_consume_daily where client_id='$ClientId' $conditions  LIMIT 1"; 
                    
                    $BalanceMaster_det = $this->CallMaster->query($qry);
                    
                    $BalanceMaster = round($BalanceMaster_det['0']['billing_consume_daily']['CV_MTD']);
            }
            else
            {
                    $qry = "select *,SUM(cm_total) AS CV_MTD from billing_consume_daily where client_id='$ClientId' AND date(cm_date) >= '2022-05-01'  $conditions "; 
            
                
                    $BalanceMaster_det = $this->CallMaster->query($qry);
                    
                    $BalanceMaster = round($BalanceMaster_det['0']['0']['CV_MTD']);
            }
            

               
                 return $BalanceMaster;
                exit;
            
        }
        
        public function get_consume_pulse()
        {
            
            $ClientId = $this->Session->read('companyid');
            $pulse_det_arr = $this->CallMaster->query("SELECT * FROM `billing_consume_daily` bcd WHERE client_Id='$ClientId' and DATE_FORMAT(CURDATE(),'%m-%Y') = DATE_FORMAT(cm_date,'%m-%Y')");
            
            $pulse_month_det = array();
            $ib_minute = 0;
            $ob_minute = 0;
            $sms_minute = 0;
            $email_minute = 0;
            foreach($pulse_det_arr as $pulse_det)    
            {
                $ib_pulse = (int) $pulse_det['bcd']['ib_pulse'];
                $ib_sec = (int) $pulse_det['bcd']['ib_secs'];
                $ibn_pulse = (int) $pulse_det['bcd']['ibn_pulse'];
                $ibn_secs = (int) $pulse_det['bcd']['ibn_secs'];
                $ob_pulse = (int) $pulse_det['bcd']['ob_pulse'];
                $ob_secs = (int) $pulse_det['bcd']['ob_secs'];
                
                $ib_minute +=$ib_pulse;
                $ib_minute += round($ib_sec/60);
                $ib_minute +=$ibn_pulse;
                $ib_minute +=round($ibn_secs/60);
                
                $ob_minute += $ob_pulse;
                $ob_minute += round($ob_secs/60);
                
                $sms_minute += $pulse_det['bcd']['sms_pulse'];
                $email_minute += $pulse_det['bcd']['email_pulse'];
            }    
                return array('ib'=>$ib_minute,'ob'=>$ob_minute,'sms'=>$sms_minute,'email'=>$email_minute);
                exit;
            
        }
	
	public function view_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld,$Abandconditions){
            
		        $ClientId = $this->Session->read('companyid');
                        $total = 0;
                $this->set('callType',$callType);
                $this->set('fd',$fd);
                $this->set('ld',$ld);
                $this->set('viewType',$view_type);

                $tatqry="SELECT cm.Category1, IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
                IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
                IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`, IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,
                1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`, 
                IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)
                `openintat`, DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,
                tt.time_hours FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId 
                AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = 
                CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,''))
                 WHERE cm.ClientId='$ClientId' $conditions1 AND cm.CallType !='Upload'  ORDER BY cm.Category1 ASC"; 
                
                $clmaster=$this->CallMaster->query($tatqry);
               
                
                foreach($clmaster as $row){
                    $key = $row[cm]['Category1'];
                    if(key_exists($key, $newcategory))
                    {
                        $newcategory[$key]['MTD'] +=1;
                        $newcategory[$key]['intat'] +=$row[0]['intat'];
                        $newcategory[$key]['outtat'] +=$row[0]['outtat'];
                        $newcategory[$key]['openintat'] +=$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] +=$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                       
                    }
                    else
                    {
                        $newcategory[$key]['MTD'] =1;
                        $newcategory[$key]['intat'] =$row[0]['intat'];
                        $newcategory[$key]['outtat'] =$row[0]['outtat'];
                        $newcategory[$key]['openintat'] =$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] =$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                    }
                    
                    $total +=1;
                    //$DataArr[] = $row[CallDate]['CloseLoopDate'];
                 }
                 
                 
                 
                 
                //$html .= "<table border='1'>";
                //$html .= "<tr><th><b>Summary</b></th>";
                //$html .= "<th><b>MTD</b></th>";
                //$html .= "<th><b>%</b></th>";
                
                
                
                $this->set('newcategory',$newcategory);
                $this->set('tatTotal',$total);
                 /*
                $keys = array_keys($newcategory);
                $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                foreach($header as $k1=>$v1){
                    foreach($keys as $k){
                        $html .= "<tr><th><b>Total ".$k.' '.$v1."</b></th>";
                        $html .= "<th><b>".$newcategory[$k][$k1]."</b></th>"; 
                        $html .= "<th><b>".round($newcategory[$k][$k1]*100/$total,2)."%</b></th>";
                        
                      
                        $html .= "</tr>";     
                    }
                }*/
                
                //echo $html; 
                
                //exit;
                
                
                
                //**********************************************************************************************
                
                
		$Category =$this->ClientCategory->find('all',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId)));
			$ecrArr=array();
			foreach($Category as $row)
                        {
                            $data = $this->fetchChildTree($parent =$row['ClientCategory']['id'],$user_tree_array = '');
                            $ecrArr[]=array(
                                'parent'=>array('id'=>$row['ClientCategory']['id'],'name'=>$row['ClientCategory']['ecrName']),
                                'sub'=>$data,
                            );	
			}
                        
                         
                        
			            $this->set('ecr',$ecrArr);
			
                       $Campagn  = $this->Session->read('campaignid');
                       
                       
                       
                        if(!empty($Campagn))
                        {
                                $CampagnId ="and t2.campaign_id in ($Campagn)";
                                    
                                    
                                $qry = "SELECT COUNT(*) `Total`,
                                SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
                                SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`
                                FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                                WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL";
                                
                                
                                $this->set('Category1',$this->CallMaster->query("SELECT Category1,GROUP_CONCAT(if(Category2 is null,Category1,Category2),'@@',countNo)`count`,SUM(countNo) `Total` FROM (SELECT Category1,Category2,COUNT(1)`countNo` FROM call_master
                                where ClientId='$ClientId' $conditions AND CallType !='Upload'  GROUP BY Category1,Category2)AS tab GROUP BY Category1"));
                                
                                
                                
                                $this->set('closeloop',$this->CallMaster->query("SELECT Category1,Category2,COUNT(Category1) `cate1`,CloseLoopCate1,CloseLoopCate2,COUNT(CloseLoopCate1)
                                FROM call_master where ClientId='$ClientId' $conditions AND CallType !='Upload' GROUP BY Category1,Category2,CloseLoopCate1,CloseLoopCate2"));
                                
                                $this->vicidialCloserLog->useDbConfig = 'db2';
                                $dt= $this->vicidialCloserLog->query($qry);

                                $this->set('qry',$conditions);
                                $condArr['ClientId']=$ClientId;
            
                                $this->set('fd',$fd);
                                $this->set('ld',$ld);
                            
                                $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$ClientId' $conditions AND TagStatus IS NULL");
                                $this->set('Abandon',$dt[0][0]['Abandon']);
                                
                                $tc=$this->CallMaster->query("Select count(Id) as totaltag FROM call_master where ClientId='$ClientId' $conditions AND CallType !='Upload'");
                                $this->set('totalTag',$tc[0][0]['totaltag']);
                                
                                $TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='$ClientId' $conditions AND TagStatus='yes'");
                                $this->set('AbandCallBack',$TACB[0][0]['AbandCallBack']);                     
                                
                                
                                $this->set('Answered',$dt[0][0]['Answered']);

                    
                    
                        }
                        $this->set('clientid',$ClientId);


                         



        }
        
        
        
        public function view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld){
             
		        $ClientId = $this->Session->read('companyid');
                $this->set('callType',$callType);
                $this->set('fd',$fd);
                $this->set('ld',$ld);
                $this->set('viewType',$view_type);
                //$this->set('campaignid',$campaignid);
                
                $tatqry="SELECT cm.Category1,
                IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
                IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
                IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`,

                IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,1,
                IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`,

                IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `openintat`,

                DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,tt.time_hours FROM call_master cm
                INNER JOIN tbl_time tt ON cm.ClientId = tt.ClientId 
                 WHERE cm.ClientId='$ClientId' $conditions1";
                
                $clmaster=$this->CallMaster->query($tatqry);
               
                
                foreach($clmaster as $row){
                    $key = $row[cm]['Category1'];
                    if(key_exists($key, $newcategory))
                    {
                        $newcategory[$key]['MTD'] +=1;
                        $newcategory[$key]['intat'] +=$row[0]['intat'];
                        $newcategory[$key]['outtat'] +=$row[0]['outtat'];
                        $newcategory[$key]['openintat'] +=$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] +=$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                       
                    }
                    else
                    {
                        $newcategory[$key]['MTD'] =1;
                        $newcategory[$key]['intat'] =$row[0]['intat'];
                        $newcategory[$key]['outtat'] =$row[0]['outtat'];
                        $newcategory[$key]['openintat'] =$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] =$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                    }
                    
                    $total +=1;
                    //$DataArr[] = $row[CallDate]['CloseLoopDate'];
                 }
                 
                 
                 
                 
                //$html .= "<table border='1'>";
                //$html .= "<tr><th><b>Summary</b></th>";
                //$html .= "<th><b>MTD</b></th>";
                //$html .= "<th><b>%</b></th>";
                
                
                
                $this->set('newcategory',$newcategory);
                 /*
                $keys = array_keys($newcategory);
                $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                foreach($header as $k1=>$v1){
                    foreach($keys as $k){
                        $html .= "<tr><th><b>Total ".$k.' '.$v1."</b></th>";
                        $html .= "<th><b>".$newcategory[$k][$k1]."</b></th>"; 
                        $html .= "<th><b>".round($newcategory[$k][$k1]*100/$total,2)."%</b></th>";
                        
                      
                        $html .= "</tr>";     
                    }
                }*/
                
                //echo $html; 
                
                //exit;
                
                
                
                //**********************************************************************************************
                
                
		    $Category =$this->ClientCategory->find('all',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId)));
			$ecrArr=array();
			foreach($Category as $row)
                        {
                            $data = $this->fetchChildTree($parent =$row['ClientCategory']['id'],$user_tree_array = '');
                            $ecrArr[]=array(
                                'parent'=>array('id'=>$row['ClientCategory']['id'],'name'=>$row['ClientCategory']['ecrName']),
                                'sub'=>$data,
                            );	
			}
                        
                         
                        
			$this->set('ecr',$ecrArr);
			
                        $Campagn  = $this->Session->read('campaignid');
                        if(!empty($Campagn)){
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                     $qry = "SELECT COUNT(*) `Total`,
                    SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
                    SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'";
                    
                    
                    $this->set('Category1',$this->CallMaster->query("SELECT Category1,GROUP_CONCAT(if(Category2 is null,Category1,Category2),'-',countNo)`count`,SUM(countNo) `Total` FROM (SELECT Category1,Category2,COUNT(1)`countNo` FROM call_master
                    where ClientId='$ClientId' $conditions  GROUP BY Category1,Category2)AS tab GROUP BY Category1"));
                    
                    
                    
                    $this->set('closeloop',$this->CallMaster->query("SELECT Category1,Category2,COUNT(Category1) `cate1`,CloseLoopCate1,CloseLoopCate2,COUNT(CloseLoopCate1)
                    FROM call_master where ClientId='$ClientId' $conditions GROUP BY Category1,Category2,CloseLoopCate1,CloseLoopCate2"));
                    
                    $this->vicidialCloserLog->useDbConfig = 'db2';
                    $dt= $this->vicidialCloserLog->query($qry);
                    
                     
                    
                    $this->set('qry',$conditions);
                    
                    
                    $condArr['ClientId']=$ClientId;
                                        
                    $obTotalTag=count($this->CallMasterOut->find('all',array('fields'=>array('Id'),'conditions'=>$condArr)));
                    $this->set('obTotalTag',$obTotalTag);
                    
                    
                    $this->set('obAnswered','');
                    $this->set('obAbandon','');
                   
                        } 
                        
                $this->set('clientid',$ClientId);
	}

	public function fetchChildTree($parent,$user_tree_array) {
		if (!is_array($user_tree_array))
			$user_tree_array = array();
			$ClientId = $this->Session->read('companyid');
			$query =$this->ClientCategory->find('all',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$parent,'Client'=>$ClientId)));
			if (count($query) > 0) {
				foreach($query as $row){
					$id=$row['ClientCategory']['id'];
					$name=$row['ClientCategory']['ecrName'];
					$user_tree_array[] = array("id" => $id, "name" =>$name);
				}
			}
			return $user_tree_array;
	}

        public function getTypeCount()
        {
            if($this->request->is('Post'))
            {
                $category1 = $this->request->data['Category'];
                $category2 = $this->request->data['Type'];
                $condition = $this->request->data['qry'];





                $ClientId = $this->Session->read('companyid');
                $cat2 = $this->CallMaster->query("SELECT Category2, GROUP_CONCAT(if(Category3 is null or Category3='',Category2,Category3),'@@',countNo)`count`,SUM(countNo) `Total` FROM (SELECT Category2,Category3,COUNT(1)`countNo` FROM call_master
        WHERE ClientId='$ClientId' AND Category1='$category1' AND Category2='$category2' $condition GROUP BY Category2,Category3)AS tab GROUP BY Category2");


                $count = 0; $Total = 0;
                foreach($cat2 as $c):
            //$data[$count]['tab'] = $c['ClientCategory']['id'];
            $data[$count]['ecrName'] = str_replace(' ','',trim($c['tab']['Category2'])).rand(1000,10000);
            $data[$count]['count'] = $c['0']['count'];
            $Total += $c['0']['Total'];
            $count++;
            endforeach;
            $this->set('Total',$Total);
            $this->set('data',$data);
            //$this->set('qry',$cat2);
            $this->set('subscenarioname',$category2);
            $this->set('scenarioname',$category1);


            }
        }
	
        public function get_collection()
        {
            
                $ClientId = $this->Session->read('companyid');
                $month_start_date = date('Y-m-01');
                $month_end_date = date('Y-m-t');
                $CollectionMaster_det = $this->InitialInvoice->query("SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$ClientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' 
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no");
                
                //$BalanceMaster = round($BalanceMaster_det['0']['billing_collection']['coll_bal']);
                //$Plan_Det = $this->CallMaster->query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1");
                //$PlanDetails = $Plan_Det['0']['plan_master'];
                //return $PlanDetails['CreditValue'];
                $coll_bal = 0;
                
                foreach($CollectionMaster_det as $bal)
                {
                    $coll_bal +=round($bal['0']['bill_passed']);
                }
                return $coll_bal;
            
        }
}
?>