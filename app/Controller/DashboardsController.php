<?php
class DashboardsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ClientCategory','UploadExistingBase','RegistrationMaster','vicidialCloserLog',
            'vicidialUserLog','CallMaster','CallRecord','CampaignName','CallMasterOut','ClientReportMaster',
            'AbandCallMaster','vicidialLog','PlanMaster','InitialInvoice','CostCenterMaster','BillMaster','BillingLedger','BalanceMaster','BillingMaster','AlertMechanism');
	
	
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
               
            
            
            if($this->Session->read('role') =="admin"){
                //$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('company_id'=>'301','status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
                //$this->set('client',$client);

                $client_info =$this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
        
                  
                    // print_r($balance_check[0][0]['non_active']);die;
                    
                   
                    //$client_info =$this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A',),'order'=>array('Company_name'=>'asc')));
                    $qry2 = "SELECT COUNT(1) as not_mapped FROM `registration_master` WHERE campaignid IS NULL AND  GroupId IS NULL AND STATUS ='A' ";
                    $not_mapped_client = $this->RegistrationMaster->query($qry2);
                   
            
                   
                   
                    // $qry3 = "SELECT company_name FROM `registration_master` WHERE  STATUS ='A' ORDER BY company_name ASC;  ";
                    // $company_name = $this->RegistrationMaster->query($qry3); 
                    $client_info_arr = array();
                    foreach($client_info as $client)
                    {
                        $Campagn=$client['RegistrationMaster']['campaignid'];
                        if(!empty($Campagn) )
                        {
                        $qry = "SELECT t2.`user`
                                FROM asterisk.vicidial_closer_log t2  
                                WHERE date(t2.call_date)=curdate() and t2.campaign_id in ($Campagn) and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL limit 1"; 
                                
                                $dt= $this->vicidialCloserLog->query($qry);
                                //echo $qry;print_r($dt);exit;
                                if(empty($dt[0]) )
                                {
                                    $client_info_arr[] = $client;
                                }
                        }
                        else
                        {
                            $client_info_arr[] = $client;
                        }
                    }
                    //exit;
                    //print_r($client_info_arr);exit;
                    

                    // print_r($call_master);die;

                    // foreach($ClientId as $client)
                    // {
                    //     $ClientId = $client['CallMaster']['ClientId'];
                         
                        
                    // }
                    // print_r($call_master);die;

                    $this->set('non_active',$balance_check[0][0]['non_active']);
                    $this->set('non_mapped_client',$not_mapped_client[0][0]['not_mapped']);
                    $this->set('company_name',$company_name);
                    $this->set('client_info_arr',$client_info_arr);

               
            }
            
              
                
		
                

                
	}

    public function alert() 
    {
		$this->layout='user';


		$sim_det = $this->AlertMechanism->find('all');      
        $this->set('sim_det',$sim_det);
                
            if($this->request->is('Post'))
            {

                //print_r($this->request->data);die;
                if(!empty($this->request->data))
                {  
                    $data = array();
                    $sim = $this->request->data;
                    $sim = $sim['Dashboards'];
                    $type = $sim['type'];
                    
                    $exist_sim = $this->AlertMechanism->find('first',array('conditions'=>array('type'=>$type)));
                    //print_r($exist_sim);die;
                        foreach($sim as $k=>$v)
                        {
                            if($exist_sim)
                            {
                                $data[$k] = "'".addslashes($v)."'";
                            }
                            else
                            {
                                $data[$k] = addslashes($v);
                            }
                        }
                            if($exist_sim)
                            {
                              $data['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                            }
                            else
                            {
                                $data['created_at'] = date('Y-m-d H:i:s');
                            }
                           
                        if($exist_sim)
                        {
                            $this->AlertMechanism->updateAll($data,array('id'=>$exist_sim['AlertMechanism']['id']));
                            $this->redirect(array('controller'=>'Dashboards'));
                            $this->Session->setFlash('Alert Updated Successfully');
                        }
                        else if($this->AlertMechanism->save($data))
                        {
                            $this->redirect(array('controller'=>'Dashboards'));
                            $this->Session->setFlash('Alert Added Successfully');
                        }
                        else
                        {
                            $this->Session->setFlash('Alert Not Added, Please Try Again');
                        }
                }
            }
	}


    public function delete_alert(){
        $id=$this->request->query['id'];
        $this->AlertMechanism->deleteAll(array('id'=>$id));
        $this->redirect(array('controller'=>'Dashboards','action'=>'alert'));
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