<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
//exit;
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

$clientId   = $_REQUEST['ClientId'];


$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";



$dd = mysqli_connect("$host", "$username", "$password","$db_name"); 
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

//$curdate = date('Y-m-d');
$curdate = '2022-01-01';
$selec_all_clients_qry = "SELECT company_id FROM `registration_master` WHERE company_id='281' and `status`='A' limit 1";
$RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry);

while($ClientInfo = mysqli_fetch_assoc($RegistrationMaster))
{
    $clientId = $ClientInfo['company_id'];
    $bal_qry = "select * from `balance_master` where clientId='$clientId'  limit 1"; 
    $BalanceMasterRsc = mysqli_query($dd,$bal_qry);
    $BalanceMaster = mysqli_fetch_assoc($BalanceMasterRsc);
    $start_date = $BalanceMaster['start_date']; 
    $next_date = $start_date;
    $end_date = $BalanceMaster['end_date'];
    $planId = $BalanceMaster['PlanId']; 
    
    $plan_get_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue FROM `plan_master` pm WHERE id='$planId' limit 1";
    $PlanMasterRsc = mysqli_query($dd,$plan_get_qry);
    $PlanMaster = mysqli_fetch_assoc($PlanMasterRsc);
    $rental_type = strtolower($PlanMaster['PeriodType']); 
    $CreditValue = $PlanMaster['CreditValue'];
    
    while(strtotime($next_date)<strtotime($curdate))
    {
        if($rental_type=='quater')
        {
            $next_date = date('Y-m-d',strtotime($next_date ." + 3 months"));
        }
        else if($rental_type=='month')
        {
            $next_date = date('Y-m-d',strtotime($next_date ." + 1 months")); 
        }
        else if($rental_type=='year')
        {
            $next_date = date('Y-m-d',strtotime($next_date ." + 1 years")); 
        }
        //echo '<br/>';
    }
    
    $select_fr = "SELECT * FROM `billing_opening_balance_history`  WHERE clientId='$clientId' and fr_sub_date='$next_date' limit 1";
    $fr_sub_exist_Rsc = mysqli_query($dd,$select_fr);
    $frMaster = mysqli_fetch_assoc($fr_sub_exist_Rsc);
    
    if(empty($frMaster))
    {
        $update = "update `billing_opening_balance_history` set fr_sub_date='$next_date',fv_st_rl='$CreditValue'  where clientId='$clientId' limit 1"; 
        $update_bob = mysqli_query($dd,$update);
    }
}



//$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where Id='70' limit 1"));
//echo "<pre>";
//print_r($BalanceMaster);die;



