<?php
class RenewalsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','BalanceMaster','PlanMaster','Waiver','RenewalPlanMaster');
	
    public function beforeFilter(){
        parent::beforeFilter();
	$this->Auth->allow('index','addbalance','update_status');
	if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }
    
    public function index(){
        $this->layout = "user";
        $this->set('PlanName',$this->PlanMaster->find('list',array('fields'=>array('Id','PlanName'),'Order'=>array('PlanName'=>'Asc'))));
        
        $clientArr = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$_REQUEST['clientid'])));
        $this->set('ClientArray',$clientArr['RegistrationMaster']);
        
        if($this->request->is('Post')){
            $data=$this->request->data;
 
            $userid=$this->Session->read('admin_id');

            if($data['clientid'] !='' && $data['oldplan'] !=''){
                $planId=$data['newplan'];
                $clientid=$data['clientid'];
                $oldplan=$data['oldplan'];
                $view=$data['view'];
                
                if($data['planMode'] =='Same Plan'){
                    $planId=$data['oldplan'];
                }
                
                $balanceArr=$this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$clientid,'PlanId'=>$oldplan)));
                $balanceMaster=$balanceArr['BalanceMaster'];
                $oldStartDate=$balanceArr['BalanceMaster']['start_date'];
                $oldEndDate=$balanceArr['BalanceMaster']['end_date'];
                $oldBalance=$balanceArr['BalanceMaster']['Balance'];
                $oldMainBalance=$balanceArr['BalanceMaster']['MainBalance'];
                $oldUsed=$balanceArr['BalanceMaster']['Used'];
                
                if($data['BillCycle'] =='NF'){
                    $start_date= date('Y-m-d');
                    $applyStatus="Yes";
                    $billCycle="'NF'";
                }
                else{
                    $applyStatus="No";
                    $billCycle="NULL";
                    $start_date= date('Y-m-d', strtotime('+1 day', strtotime($oldEndDate)));
                }
                
                
                $RenewalArr = $this->RenewalPlanMaster->find('first',array('conditions'=>array('clientId'=>$clientid,'Plandate'=>$oldStartDate)));
                
                if(empty($RenewalArr)){
                //if(strtotime($oldEndDate) < strtotime(date('Y-m-d')) || $data['BillCycle'] =='NF'){
                    
                $planArr=$this->PlanMaster->find('first',array('conditions'=>array('Id'=>$planId)));
                $plan=$planArr['PlanMaster'];

                $RentalPeriod = $plan['RentalPeriod'];
                $period = $plan['PeriodType'];
                
                if($data['BillCycle'] =='NF'){
                    if($plan['TransferAfterRental'] =='Yes'){
                        if($oldUsed < $oldMainBalance){
                            $newbalance=($plan['Balance']+$oldBalance);
                        }
                        else{
                            $newbalance=$plan['Balance']; 
                        }
                    }
                    else{
                        $newbalance=$plan['Balance']; 
                    }
                }
                else{
                    $newbalance=$plan['Balance'];    
                }
                
                if($data['paymentTypes'] =='PTP'){
                    $paymentReciive='No';
                    $ptpcount=1;
                }
                else{
                    $paymentReciive='Yes'; 
                }
                    
                    
                if($data['BillCycle'] =='NF'){
                        
                    $this->BalanceMaster->query("INSERT INTO `balance_master_history` (`Id`,`clientId`,`PlanId`,`PlanType`,`Balance`,`Used`,`MainBalance`,`start_date`,`end_date`,`FreeRentalDays`,`userid`,`createdate`,`paymentTypes`,`paymentDate`,`paymentRemark`,`renewalDate`,`paymentReceive`,`ptpCount`,`insertdate`)
                    SELECT `Id`,`clientId`,`PlanId`,`PlanType`,`Balance`,`Used`,`MainBalance`,`start_date`,`end_date`,`FreeRentalDays`,`userid`,`createdate`,`paymentTypes`,`paymentDate`,`paymentRemark`,`renewalDate`,`paymentReceive`,`ptpCount`,NOW()
                    FROM   `balance_master`WHERE  clientId='$clientid' AND PlanId='$oldplan'");
                    
                    $this->BalanceMaster->query("UPDATE `balance_master` SET PlanId='$planId',PlanType='{$plan['PlanType']}',
                    Balance='{$newbalance}',Used=NULL,MainBalance='{$newbalance}',
                    userid='$userid',paymentTypes='{$data['paymentTypes']}',paymentDate='{$data['paymentDate']}',renewalDate=NOW(),paymentReceive='$paymentReciive',ptpCount='$ptpcount',
                    paymentRemark='{$data['paymentRemark']}',start_date ='$start_date',end_date = DATE_ADD('$start_date',INTERVAL $RentalPeriod $period) WHERE clientId='$clientid'");

                }
                    
                $this->BalanceMaster->query("INSERT INTO `renewal_plan_master` (`clientId`,`PlanId`,`PlanType`,`Balance`,`start_date`,
                `end_date`,`userid`,`createdate`,`paymentTypes`,`paymentDate`,`paymentRemark`,`renewalDate`,`paymentReceive`,`ptpCount`,
                `Plandate`,`ApplyStatus`,`BillCycle`)VALUES ('$clientid','$planId','{$plan['PlanType']}','$newbalance','$start_date',
                DATE_ADD('$start_date',INTERVAL $RentalPeriod $period),'$userid',NOW(),'{$data['paymentTypes']}','{$data['paymentDate']}',
                '{$data['paymentRemark']}',NOW(),'$paymentReciive','$ptpcount','$oldStartDate','$applyStatus',$billCycle
                )");
                
                
                $this->BalanceMaster->query("DELETE FROM `alert_used_balance` WHERE ClientId='$clientid' AND AlertType='Renewal Plan'");
                
                if($data['BillCycle'] =='NF'){
                    $this->RegistrationMaster->query("UPDATE registration_master SET `status`='A' WHERE company_id='$clientid' AND `status`='D'");
                }
                
                
                $this->Session->setFlash("Your plan renewal successfully.");
                $this->redirect(array('action' => 'index','?'=>array('clientid'=>$clientid,'planid'=>$oldplan,'view'=>$view)));
                    
                
                }
                else{
                    $this->Session->setFlash("This client plan Allready renewal.");
                    $this->redirect(array('action' => 'index','?'=>array('clientid'=>$clientid,'planid'=>$oldplan,'view'=>$view)));   
                }
    
            }
            else{
                $this->Session->setFlash("Please go back on renewal option and click agains.");
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    
    public function addbalance(){
        if($this->request->is('Post')){
            $data=$this->request->data;  
            $userid=$this->Session->read('admin_id');

            if($data['clientid'] !='' && $data['oldplan'] !=''){
                $clientid=$data['clientid'];
                $oldplan=$data['oldplan'];
                $planId=$data['oldplan'];
                $BalanceAmount=$data['BalanceAmount'];
                $Remark=$data['Remark'];
                 $view=$data['view'];
                
                $balanceArr=$this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$clientid,'PlanId'=>$oldplan)));
                $balanceMaster=$balanceArr['BalanceMaster'];
                $oldEndDate=$balanceArr['BalanceMaster']['end_date'];
                $oldBalance=$balanceArr['BalanceMaster']['Balance'];
                $oldMainBalance=$balanceArr['BalanceMaster']['MainBalance'];
                $start_date= date('Y-m-d');
    
                if(strtotime($oldEndDate) > strtotime(date('Y-m-d'))){
                    
                    $newbalance=($oldBalance+$BalanceAmount);
                    $manbalance=($oldMainBalance+$BalanceAmount);
                    
                    $this->BalanceMaster->query("UPDATE `balance_master` SET Balance='{$newbalance}',MainBalance='{$manbalance}' WHERE clientId='$clientid' AND PlanId='$planId'");
                    
                    $this->BalanceMaster->query("INSERT INTO `add_client_balance` (`client_id`,`plan_id`,`add_balance`,`remark`,`user_id`)
                    VALUES ('$clientid','$planId','$BalanceAmount','$Remark','$userid')");
                    
                    $this->BalanceMaster->query("DELETE FROM `alert_used_balance` WHERE ClientId='$clientid' AND AlertType='Charge Balance'");
                    
                    $this->Session->setFlash("Your balance added successfully.");
                    $this->redirect(array('action' => 'index','?'=>array('clientid'=>$clientid,'planid'=>$oldplan,'view'=>$view)));  
                }
                else{
                    $this->Session->setFlash("Sorry your plan allready expire so please renewal plan.");
                    $this->redirect(array('action' => 'index','?'=>array('clientid'=>$clientid,'planid'=>$oldplan,'view'=>$view)));  
                }
    
            }
            else{
                $this->Session->setFlash("Please go back on add balance option and click agains.");
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    public function update_status(){
        $this->layout = "user";
        if($this->request->is('Post')){
            $data=$this->request->data;
            $userid=$this->Session->read('admin_id');

            if($data['clientid'] !='' && $data['oldplan'] !=''){
                
                $Id=$data['Id'];
                $clientid=$data['clientid'];
                $oldplan=$data['oldplan'];
                $view=$data['view'];
                $paymentReciive=$data['paymentReceive'];
                $payDate=$data['payDate'];
                $payRemark=$data['payRemark'];
                    
                $RenewalArr = $this->RenewalPlanMaster->find('first',array('conditions'=>array('clientId'=>$clientid,'Id'=>$Id)));
                
                if($RenewalArr['RenewalPlanMaster']['paymentReceive'] !='Yes'){
                    
                    if($paymentReciive =='Yes'){
                        $ptpcount="NULL";
                    }
                    else{
                        $ptpcnt=$data['ptpcount']+1;
                        $pc=$data['ptpcount']+1;
                        $ptpcount="'$pc'"; 
                    }

                    $this->BalanceMaster->query("UPDATE `renewal_plan_master` SET userid='$userid',paymentDate='$payDate',paymentReceive='$paymentReciive',ptpCount=$ptpcount,paymentRemark='$payRemark' WHERE clientId='$clientid' AND Id='$Id'");

                    $this->Session->setFlash("Your renewal status update successfully.");
                    $this->redirect(array('action' => 'index','?'=>array('clientid'=>$clientid,'planid'=>$oldplan,'ptpcnt'=>$ptpcnt,'view'=>$view,'id'=>$Id)));
                }
                else{
                    $this->Session->setFlash("Payment allready received.");
                    $this->redirect(array('action' => 'index','?'=>array('clientid'=>$clientid,'planid'=>$oldplan,'ptpcnt'=>$ptpcnt,'view'=>$view,'id'=>$Id)));    
                }
            }
            else{
                $this->Session->setFlash("Please go back on renewal option and click agains.");
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    
    
    
        
}
?>