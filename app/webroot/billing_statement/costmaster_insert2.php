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
       $curl = curl_init();

curl_setopt_array($curl, array(
    
  CURLOPT_URL => "http://mascallnetnorth.in/ispark/app/webroot/cronejob/save_cost_insert.php?client=$dialdesk_client_id&company_name=$company_name&branch=$branch&dialdesk_client_id=$dialdesk_client_id&UserAddress1=$UserAddress1&UserName1=$UserName1&UserDesignation=$UserDesignation1&UserContactNo=$UserContactNo1&UserEmailId1=$UserEmailId11&VendorGSTState=$VendorGSTState&VendorGSTNo=&$VendorGSTNo&VendorStateCode=$VendorStateCode",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded",
    "postman-token: 200591a4-9a83-87ce-0531-4ff0177e0201"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
  $resp = json_decode($resp,true);
  if($resp->status)
        {
            $upd = "update registration_master set send_to_ispark='0' where company_id='$dialdesk_client_id' limit 1"; 
            $rsc_upd = mysqli_query($dd,$upd);
        }
}
    }	
}



?>