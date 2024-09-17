<?php 

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
//exit;
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');


// $host       =   "localhost"; 
// $username   =   "root";
// $password   =   "dial@mas123";
// $db_name    =   "db_dialdesk";

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

$ispark_host = "192.168.10.22";

$dd = mysqli_connect("$host", "$username", "$password","$db_name"); 
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$ispark = mysqli_connect("$ispark_host", "$username", "321*#LDtr!?*ktasb","db_bill"); 
if (mysqli_connect_errno($ispark)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error($ispark);
  exit();
}


$selec_new_client = "select * from registration_master where send_to_ispark='1'";
$rsc_client_det= mysqli_query($dd,$selec_new_client);

while($det = mysqli_fetch_assoc($rsc_client_det))
{
    $company_name = addslashes($det['company_name']);
    $branch = 'NOIDA-DIALDESK';
    $dialdesk_client_id = $det['company_id'];

    // Basant

      
    $UserAddress1=$det['reg_office_address1'];
    $UserName1=$det['auth_person'];

    $UserDesignation1=$det['designation'];
    $UserContactNo1=$det['phone_no'];
    $UserEmailId1=$det['email'];


    $VendorGSTState=$det['state'];
    $VendorGSTNo=$det['gst_no'];
    $VendorStateCode='';

    // Basant

    if(!empty($dialdesk_client_id))
    {
        $db_query = "INSERT INTO tmp_cost_master SET 
        client='$company_name',company_name='IDC',branch='$branch',
        dialdesk_client_id='$dialdesk_client_id',
        UserAddress1='$UserAddress1',
        UserName1='$UserName1',
        UserDesignation1='$UserDesignation1',
        UserContactNo1='$UserContactNo1',
        UserEmailId1='$UserEmailId1',
        VendorGSTState='$VendorGSTState',
        VendorGSTNo='$VendorGSTNo',
        VendorStateCode='$VendorStateCode'

        ";

        $rsc_ins_coll = mysqli_query($ispark,$db_query);


        // for client master

        $client_type='Client';
        $client_name=$company_name;
        $branch_name='NOIDA-DIALDESK';
        $client_status='1';

        // check client name in client master client_name
        
        $client_name_cm_sql = "SELECT client_name FROM client_master WHERE client_name='$client_name'";
        $result = mysqli_query($ispark, $client_name_cm_sql);

        if (mysqli_num_rows($result) == 0) 
        {


            $client_master_sql = "INSERT INTO client_master (client_type,client_name,branch_name,client_status) values ('$client_type','$client_name','$branch_name','$client_status') ";


            mysqli_query($ispark, $client_master_sql);

        }

        if($rsc_ins_coll)
        {
            $upd = "update registration_master set send_to_ispark='0' where company_id='$dialdesk_client_id' limit 1"; 
            $rsc_upd = mysqli_query($dd,$upd);
        }
    }	
}



?>