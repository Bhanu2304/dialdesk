<?php
class BillSummarysController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','BalanceMaster','PlanMaster','BillSummaryAutoMailMaster','BalanceMasterHistory','RenewalPlanMaster');
	
    public function beforeFilter(){
        parent::beforeFilter();
	$this->Auth->allow('index','view_report','add','delete','renewal_report','old_report','old_renewal_report','balance_report');
	if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }
    
    public function add(){
        $this->layout = "user";
        $sendArr = $this->BillSummaryAutoMailMaster->find('all');
        $this->set('data',$sendArr);
       
        if($this->request->is('Post')){
            $data=$this->request->data['BillSummarys'];

            if($this->BillSummaryAutoMailMaster->find('first',array('fields'=>array('Id'),'conditions'=>array('email'=>$data['email'])))){
                $this->Session->setFlash("This email id already exists.");
                $this->redirect(array('action' => 'add'));
            }
            else{
                $this->BillSummaryAutoMailMaster->save($data);
                $this->Session->setFlash("Sender email details save successfully.");
                $this->redirect(array('action' => 'add'));
            } 
        }
    }
    
    public function delete(){
        if(isset($_REQUEST['id'])){
            $this->BillSummaryAutoMailMaster->delete(array('Id'=>$_REQUEST['id']));
        }
        $this->redirect(array('action' => 'add'));
    }

    public function index(){
        $this->layout = "user";
        if($this->request->is('Post')){
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=ClientBillSummary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
            ?>        
            <table border="1">
                <tr style='font-size:12px;'>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>TYPE</th>
                    <th>RENT</th>
                    <th>FREE VALUE</th>
                    <th>START DATE</th>
                    <th>END DATE</th>
                    <th>BALANCE</th>
                    <th>STATUS</th>
                </tr>
                <?php 
                foreach($clientArr as $row){ 
                    $BalanceArr = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$row['RegistrationMaster']['company_id'])));
                    $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$BalanceArr['BalanceMaster']['PlanId'])));
                    
                    $style= "";
                    $CD   = date('Y-m-d');
                    $LD   = $BalanceArr['BalanceMaster']['end_date'];
                    
                    $datecolor=$this->expir_date_color_code($LD,$CD);
                    $balacolor=$this->balance_color_code($BalanceArr['BalanceMaster']['Used'],$BalanceArr['BalanceMaster']['MainBalance'],$LD);
                    
                    ?>	
                    <tr>
                        <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                        <?php if(!empty($BalanceArr)){?>
                        <td><?php echo $PlanArr['PlanMaster']['PlanName'];?></td>
                        <td><?php echo $BalanceArr['BalanceMaster']['PlanType'];?></td>
                        <td><?php echo $PlanArr['PlanMaster']['RentalAmount'];?></td>
                        <td><?php echo $PlanArr['PlanMaster']['Balance'];?></td>
                        <td><?php echo $BalanceArr['BalanceMaster']['start_date'];?></td>
                        <td style="background-color:<?php echo $datecolor;?>;"  ><?php echo $BalanceArr['BalanceMaster']['end_date'];?></td>
                        <?php if($LD !=""){?>
                        <td style="background-color:<?php echo $balacolor;?>;" ><?php echo $BalanceArr['BalanceMaster']['Balance'];?></td>
                        <?php }else{?>
                        <td></td>
                        <?php }?>
                        <?php }else{?>
                        <td>No Plan Allocated</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <?php }?>
                        <td>Active</td>
                    </tr>
                <?php } ?>
            </table>
            <?php
            exit;
        }
    }
    
    
    
    
    
    
    
    public function view_report(){
        $this->layout = "ajax";
        $clientArr = $this->RegistrationMaster->find('all',array('order'=>array('company_name')));
        ?>  
        
                <style>
.myButton {
	background-color:#44c767;
	-moz-border-radius:28px;
	-webkit-border-radius:28px;
	border-radius:28px;
	border:1px solid #18ab29;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:17px;
	padding:16px 31px;
	text-decoration:none;
	text-shadow:0px 1px 0px #2f6627;
}
.myButton:hover {
	background-color:#5cbf2a;
}
.myButton:active {
	position:relative;
	top:1px;
}

        </style>
         <table class="table table-bordered table-hover">
            <thead>
                <tr style='font-size:12px;'>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>TYPE</th>
                    <th>RENT</th>
                    <th>FREE VALUE</th>
                    <th>START DATE</th>
                    <th>END DATE</th>
                    <th>BALANCE</th>
                    <th>STATUS</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($clientArr as $row){
                $BalanceArr = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$row['RegistrationMaster']['company_id'])));
                $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$BalanceArr['BalanceMaster']['PlanId'])));

                $style= "";
                $CD   = date('Y-m-d');
                $LD   = $BalanceArr['BalanceMaster']['end_date'];

                $datecolor=$this->expir_date_color_code($LD,$CD);
                $balacolor=$this->balance_color_code($BalanceArr['BalanceMaster']['Used'],$BalanceArr['BalanceMaster']['MainBalance'],$LD);

                ?>	
                <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    <?php if(!empty($BalanceArr)){?>
                    <td><?php echo $PlanArr['PlanMaster']['PlanName'];?></td>
                    <td><?php echo $BalanceArr['BalanceMaster']['PlanType'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['RentalAmount'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['Balance'];?></td>
                    <td><?php echo $BalanceArr['BalanceMaster']['start_date'];?></td>
                    <td style="background-color:<?php echo $datecolor;?>;"  ><?php echo $BalanceArr['BalanceMaster']['end_date'];?></td>
                    <?php if($LD !=""){?>
                        <td style="background-color:<?php echo $balacolor;?>;" ><?php echo $BalanceArr['BalanceMaster']['Balance'];?></td>
                    <?php }else{?>
                        <td></td>
                    <?php }?>
                    
                    <?php }else{?>
                    <td>No Plan Allocated</td>
                    <td>NA</td>
                    <td>NA</td>
                    <td>NA</td>
                    <td>NA</td>
                    <td>NA</td>
                    <td>NA</td>
                    <?php }?>
                    
                    <td><?php if($row['RegistrationMaster']['status'] =='A'){echo "<span style='color:green;' >Active</span>";}else{echo "<span style='color:red;' >Deactivate </span>";}?></td>
                   
                    <td>
                        <?php
                        $ND1=date('Y-m-d', strtotime('-15 day', strtotime($LD)));
                        if(strtotime($CD) >= strtotime($ND1) && $LD !=""){
                        ?>
                        <a target="_blank" href="<?php $this->webroot?>Renewals?clientid=<?php echo $row['RegistrationMaster']['company_id'];?>&planid=<?php echo $PlanArr['PlanMaster']['Id'];?>&view=renewal"><button style="background-color:gray;color:white;border-radius:28px;border:0px solid #607d8b;">&nbsp;Renewal&nbsp;</button></a>
                        <?php }else{?>
                        <?php if(!empty($BalanceArr)){?>
                        <a target="_blank" href="<?php $this->webroot?>Renewals?clientid=<?php echo $row['RegistrationMaster']['company_id'];?>&planid=<?php echo $PlanArr['PlanMaster']['Id'];?>&view=balance"><button style="background-color:gray;color:white;border-radius:28px;border:0px solid #607d8b;">&nbsp;Balance&nbsp;</button></a>
                        <?php }else{?>
                            <?php if($row['RegistrationMaster']['status'] =='A'){?>
                         <a target="_blank" href="<?php $this->webroot?>AdminPlans/allocate_plan"><button style="background-color:gray;color:white;border-radius:28px;border:0px solid #607d8b;">Plan Alloc</button></a>
                            <?php }?>
                        <?php }?>
                        <?php }?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>                    
        </table>
        <?php
        exit;   
    }
    
    public function renewal_report(){
        $this->layout = "ajax";
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
        ?>  
        <style>
        .myButton {
            background-color:#44c767;
            -moz-border-radius:28px;
            -webkit-border-radius:28px;
            border-radius:28px;
            border:1px solid #18ab29;
            display:inline-block;
            cursor:pointer;
            color:#ffffff;
            font-family:Arial;
            font-size:17px;
            padding:16px 31px;
            text-decoration:none;
            text-shadow:0px 1px 0px #2f6627;
        }
        .myButton:hover {
            background-color:#5cbf2a;
        }
        .myButton:active {
            position:relative;
            top:1px;
        }
        </style>
         <table class="table table-bordered table-hover">
            <thead>
                <tr style='font-size:12px;'>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>RENEW</th>
                    <th>APPLY ON</th>
                    <th>APPLY</th>
                    <th>FO-CYCLE</th>
                    <th>PAY TYPE</th>
                    <th>PAY DATE</th>
                    <th>REMARK</th>
                    <th>PAYMENT</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($clientArr as $row){
                $RenewalArr = $this->RenewalPlanMaster->find('all',array('conditions'=>array('clientId'=>$row['RegistrationMaster']['company_id'])));
                
                foreach($RenewalArr as $BalanceArr){
                    
                    $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$BalanceArr['RenewalPlanMaster']['PlanId'])));
                    ?>
                    <tr>
                        <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                        <td><?php echo $PlanArr['PlanMaster']['PlanName'].'('.$BalanceArr['RenewalPlanMaster']['PlanType'].')';?></td>
                        <td><?php echo date("d-m-Y",  strtotime($BalanceArr['RenewalPlanMaster']['renewalDate']));?></td>
                        <td><?php echo date("d-m-Y",  strtotime($BalanceArr['RenewalPlanMaster']['start_date']));?></td>
                        <td>
                            <?php echo $BalanceArr['RenewalPlanMaster']['ApplyStatus'];?>
                            <?php if($BalanceArr['RenewalPlanMaster']['ApplyStatus'] =="No" && $BalanceArr['RenewalPlanMaster']['start_date'] < date('Y-m-d')){?>
                            <br/><a href="<?php echo $this->webroot;?>app/webroot/crone/billing/manual_apply_renewal_plan.php?renewaldate=<?php echo $BalanceArr['RenewalPlanMaster']['start_date'];?>" onclick="return confirm('Are you sure you want to apply now?');" >Apply Now</a>
                            <?php }?>
                        </td>
                        <td><?php if($BalanceArr['RenewalPlanMaster']['BillCycle'] =='NF'){echo "No";}else{echo "Yes";}?></td>
                        <td><?php echo $BalanceArr['RenewalPlanMaster']['paymentTypes'];?></td>
                        <td><?php echo date("d-m-Y",  strtotime($BalanceArr['RenewalPlanMaster']['paymentDate']));?></td>
                        <td><?php echo $BalanceArr['RenewalPlanMaster']['paymentRemark'];?></td>
                        <td><?php echo $BalanceArr['RenewalPlanMaster']['paymentReceive'];?></td>
                        <td>
                        <?php if($BalanceArr['RenewalPlanMaster']['paymentReceive'] =='No'){?>
                        <a target="_blank" href="<?php $this->webroot?>Renewals?clientid=<?php echo $row['RegistrationMaster']['company_id'];?>&planid=<?php echo $PlanArr['PlanMaster']['Id'];?>&ptpcnt=<?php echo $BalanceArr['RenewalPlanMaster']['ptpCount'];?>&view=update&id=<?php echo $BalanceArr['RenewalPlanMaster']['Id'];?>"><button style="background-color:#607d8b;color:white;border-radius:28px;border:0px solid #607d8b;">Update</button></a>
                        <?php }?>
                        </td> 
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>                    
        </table>
        <?php
        exit;   
    }
    
    
    
    public function old_report(){
        $this->layout = "ajax";
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
        ?>  
        
                <style>
.myButton {
	background-color:#44c767;
	-moz-border-radius:28px;
	-webkit-border-radius:28px;
	border-radius:28px;
	border:1px solid #18ab29;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:17px;
	padding:16px 31px;
	text-decoration:none;
	text-shadow:0px 1px 0px #2f6627;
}
.myButton:hover {
	background-color:#5cbf2a;
}
.myButton:active {
	position:relative;
	top:1px;
}

        </style>
         <table class="table table-bordered table-hover">
            <thead>
                <tr style='font-size:12px;'>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>TYPE</th>
                    <th>RENT</th>
                    <th>FREE VALUE</th>
                    <th>START DATE</th>
                    <th>END DATE</th>
                    <th>BALANCE</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($clientArr as $row){
                $BalanceArr = $this->BalanceMasterHistory->find('all',array('conditions'=>array('clientId'=>$row['RegistrationMaster']['company_id'])));
                if(!empty($BalanceArr)){
                    
                   foreach($BalanceArr as $row1){ 
                    
                $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$row1['BalanceMasterHistory']['PlanId'])));
                ?>	
                <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['PlanName'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['PlanType'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['RentalAmount'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['Balance'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['start_date'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['end_date'];?></td>
                    <td ><?php echo $row1['BalanceMasterHistory']['Balance'];?></td>
                </tr>
            <?php }} }
            
            
            ?>
            </tbody>                    
        </table>
        <?php
        exit;   
    }
    
    
    /*
    public function old_renewal_report(){
        $this->layout = "ajax";
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
        ?>  
        
                <style>
.myButton {
	background-color:#44c767;
	-moz-border-radius:28px;
	-webkit-border-radius:28px;
	border-radius:28px;
	border:1px solid #18ab29;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:17px;
	padding:16px 31px;
	text-decoration:none;
	text-shadow:0px 1px 0px #2f6627;
}
.myButton:hover {
	background-color:#5cbf2a;
}
.myButton:active {
	position:relative;
	top:1px;
}

        </style>
         <table class="table table-bordered table-hover">
            <thead>
                <tr style='font-size:12px;'>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>TYPE</th>
                    <th>RENEWAL</th>
                    <th>PAY TYPE</th>
                    <th>PAY DATE</th>
                    <th>REMARK</th>
                    <th>PAYMENT</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($clientArr as $row){
                $BalanceArr = $this->BalanceMasterHistory->find('all',array('conditions'=>array('clientId'=>$row['RegistrationMaster']['company_id'])));
                if(!empty($BalanceArr)){
                    
                   foreach($BalanceArr as $row1){ 
                    
                $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$row1['BalanceMasterHistory']['PlanId'])));
                ?>
                 <?php if($row1['BalanceMasterHistory']['renewalDate'] !=""){?>
                <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['PlanName'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['PlanType'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['renewalDate'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['paymentTypes'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['paymentDate'];?></td>
                    <td><?php echo $row1['BalanceMasterHistory']['paymentRemark'];?></td>
                    <td ><?php echo $row1['BalanceMasterHistory']['paymentReceive'];?></td>
                    <td ><?php echo $row1['BalanceMasterHistory']['paymentReceive'];?></td>
                </tr>
                 <?php }?>
            <?php }} }
            
            ?>
            </tbody>                    
        </table>
        <?php
        exit;   
    }
    */
    
    
        public function balance_report(){
        $this->layout = "ajax";
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
        ?>  
        
                <style>
.myButton {
	background-color:#44c767;
	-moz-border-radius:28px;
	-webkit-border-radius:28px;
	border-radius:28px;
	border:1px solid #18ab29;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:17px;
	padding:16px 31px;
	text-decoration:none;
	text-shadow:0px 1px 0px #2f6627;
}
.myButton:hover {
	background-color:#5cbf2a;
}
.myButton:active {
	position:relative;
	top:1px;
}

        </style>
         <table class="table table-bordered table-hover">
            <thead>
                <tr style='font-size:12px;'>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>TYPE</th>
                    <th>ADD BALANCE</th>
                    <th>REMARKS</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($clientArr as $row){
                $BalanceArr=$this->BalanceMaster->query("SELECT * FROM `add_client_balance` WHERE client_id='{$row['RegistrationMaster']['company_id']}'");
              
                //$BalanceArr = $this->BalanceMasterHistory->find('all',array('conditions'=>array('clientId'=>$row['RegistrationMaster']['company_id'])));
                if(!empty($BalanceArr)){
                    
                   foreach($BalanceArr as $row1){ 
                       //echo "<pre>";
                        //print_r($BalanceArr);
                       //echo "</pre>";
                $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$row1['add_client_balance']['plan_id'])));
                ?>	
                <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['PlanName'];?></td>
                    <td><?php echo $PlanArr['PlanMaster']['PlanType'];?></td>
                    <td><?php echo $row1['add_client_balance']['add_balance'];?></td>
                    <td><?php echo $row1['add_client_balance']['remark'];?></td>
                    <td ><?php echo $row1['add_client_balance']['create_date'];?></td>
                </tr>
            <?php }} }
            
            
            ?>
            </tbody>                    
        </table>
        <?php
        exit;   
    }
    
    
    public function expir_date_color_code($LD,$CD){
        if($LD !=""){
            $days = (strtotime($LD) - strtotime($CD)) / (60 * 60 * 24);
            if($days <= "7"){
                return "#ff0000";
            }
            else if($days <= "15"){
                return "#ff6666";
            }
            else if($days <= "30"){
                return "#ff9999";
            }
            else if($days <= "90"){
                return "#FFA500";
            }
            else{
                return "#008000";
            }
        }
    }
    
    public function balance_color_code($Used,$Main,$LD){
        if($Main !="" && $LD !=""){
            $UsedBalance =   round($Used*100/$Main);
            if($UsedBalance >= 75 && $UsedBalance < 85 ){
                return "#008000";
            }
            else if($UsedBalance >= 85 && $UsedBalance < 95 ){
                return "#FFA500";
            }
            else if($UsedBalance >= 95 && $UsedBalance < 98 ){
                return "#ff9999";
            }
            else if($UsedBalance >= 98 && $UsedBalance < 100){
                 return "#ff6666";
            }
            else if($UsedBalance >= 100){
                return "#ff0000";
            }
            else{
                return "#008000";   
            }
        }
    }
    
    
}
?>