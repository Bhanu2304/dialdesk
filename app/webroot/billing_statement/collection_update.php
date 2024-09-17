<?php 

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
//exit;
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');


$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

$ispark_host = "122.160.84.62";

$dd = mysqli_connect("$host", "$username", "$password","$db_name"); 
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$ispark = mysqli_connect("$ispark_host", "$username", "dial@mas123","db_bill"); 
if (mysqli_connect_errno($ispark)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error($ispark);
  exit();
}


$selec_all_payment_qry = "select * from bill_pay_particulars where dialdesk='1'";
$collection_master= mysqli_query($ispark,$selec_all_payment_qry);

while($det = mysqli_fetch_assoc($collection_master))
{
 	$fin_year = $det['financial_year'];
	$company_name = $det['company_name'];
	$branch = $det['branch_name'];
	$bill_no = $det['bill_no'];
	$net_amount = $det['bill_passed'];
	$payment_id = $det['id'];
	
	$sel_bill_no = "SELECT ti.id,cm.dialdesk_client_id FROM tbl_invoice ti 
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
WHERE ti.category in ('Talk Time','Topup') and cm.company_name='$company_name'
AND cm.branch = '$branch'
AND ti.`finance_year`='$fin_year'
AND SUBSTRING_INDEX(ti.bill_no,'/',1) = '$bill_no';"; 
	
	$rsc_client_info = mysqli_query($ispark,$sel_bill_no);
	$client_info = mysqli_fetch_assoc($rsc_client_info);
	$clientId = $client_info['dialdesk_client_id'];
	$bill_id = $client_info['id'];
	
	if(!empty($clientId))
	{
            $db_query = "INSERT INTO `billing_collection` SET client_id='$clientId' ,bill_month=CURDATE(),coll_bal='$net_amount',bill_id='$payment_id',created_at=now()";
            $rsc_ins_coll = mysqli_query($dd,$db_query);
            if($rsc_ins_coll)
            {
                $upd = "update bill_pay_particulars set dialdesk='0' where id='$payment_id' limit 1";
                $rsc_upd = mysqli_query($ispark,$upd);                                            
            }
	}
	else
	{
            $upd = "update bill_pay_particulars set dialdesk='0' where id='$payment_id' limit 1";
            $rsc_upd = mysqli_query($ispark,$upd);
	}
	
	
	
}



?>