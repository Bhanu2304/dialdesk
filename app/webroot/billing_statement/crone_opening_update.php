<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

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
exit;
$curdate = date('Y-m-d');
//$curdate = '2022-01-20';
$selec_all_clients_qry = "SELECT company_id FROM `registration_master` WHERE  `status`='A' and company_id!='37'";
$RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry);

while($ClientInfo = mysqli_fetch_assoc($RegistrationMaster))
{
    $clientId = $ClientInfo['company_id'];
    
    
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-t'); 
    
    //echo $start_date;exit;
    if(!empty($start_date) && !empty($end_date))
    {
        
        //strtotime($curdate)>=strtotime($start_date) && strtotime($curdate)<=strtotime($last_date)
        //echo $start_date;exit;
        //echo $last_date;exit;
        //$next_date = date('Y-m-d',strtotime($start_date ." + 1 months"));
        //$last_date = date('Y-m-d',strtotime($next_date ." - 1 days"));
        //$nextlast_date = date('Y-m-d',strtotime($last_date ." + 1 months"));
        
        $select_opening_exist = "select * from billing_opening_balance where clientId='$clientId' limit 1";
        $rsc_opening_exist = mysqli_query($dd,$select_opening_exist);
        $opening_exist_det = mysqli_fetch_assoc($rsc_opening_exist);
        
        if(!empty($opening_exist_det))
        {
            $select_opening_date_exist = "select * from billing_opening_balance where clientId='$clientId'  limit 1";
            $rsc_opening_date_exist = mysqli_query($dd,$select_opening_date_exist);
            $opening_exist_date_det = mysqli_fetch_assoc($rsc_opening_date_exist);
            if(!empty($opening_exist_date_det))
            {
                $insHistory_qry = "INSERT INTO `billing_opening_balance_history`(clientId,op_bal,cs_bal,bill_start_date,bill_end_date,as_on_date)
                SELECT clientId,op_bal,cs_bal,bill_start_date,bill_end_date,as_on_date FROM `billing_opening_balance`
                WHERE clientId='$clientId'"; 
            
                $rscInsertHistory = mysqli_query($dd,$insHistory_qry);
                if($rscInsertHistory)
                {
                    $new_openingbal =  round((float)$opening_exist_det['op_bal'] + round((float)$opening_exist_det['cs_bal']));
                    $update_new_detail = "update billing_opening_balance set op_bal='$new_openingbal',cs_bal='0',bill_start_date='$start_date',bill_end_date='$end_date' where clientId='$clientId' limit 1";
                    $rscUpdate = mysqli_query($dd,$update_new_detail);
                }
            }
        }
        else
        {
            
                $new_openingbal =  0;
                $insert_new_detail = "insert into billing_opening_balance set clientId='$clientId',op_bal='$new_openingbal',cs_bal='0',bill_start_date='$start_date',bill_end_date='$end_date' ";
                $rscInsert = mysqli_query($dd,$insert_new_detail);
            
        }
    }
    
    
}



//$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where Id='70' limit 1"));
//echo "<pre>";
//print_r($BalanceMaster);die;



