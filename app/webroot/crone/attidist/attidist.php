<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$ClientId       =   368;
$call_master    =   mysql_query("SELECT * FROM call_master WHERE ClientId='$ClientId' AND Category1='Leads' AND DATE(CallDate)=CURDATE() AND OtherStatus IS NULL ");
$field_master   =   mysql_query("SELECT FieldName,fieldNumber FROM `field_master` WHERE ClientId='$ClientId' AND FieldStatus IS NULL ORDER BY Priority");

$url            =   "https://www.zohoapis.in/crm/v2/Leads";
//$token_url      =   "https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.f6ac595077d11136e4e9028a5536bad2.7080c1693d2d89d4b7420c59753b1917&client_id=1000.PYFF1L3MQV1HLXLTEIIJQ2YRKYHXXK&client_secret=95b3e87e5493296f9e9a8f7aa6d4f60295619a07f0&grant_type=refresh_token";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://accounts.zoho.in/oauth/v2/token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"refresh_token\"\r\n\r\n1000.f6ac595077d11136e4e9028a5536bad2.7080c1693d2d89d4b7420c59753b1917\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"client_id\"\r\n\r\n1000.PYFF1L3MQV1HLXLTEIIJQ2YRKYHXXK\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"client_secret\"\r\n\r\n95b3e87e5493296f9e9a8f7aa6d4f60295619a07f0\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"grant_type\"\r\n\r\nrefresh_token\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
    "postman-token: 4d3e2879-f721-80bc-ada5-ef21d54dcbe5"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$token_array    =   json_decode($response,true);

echo $token          =   $token_array['access_token'];
$header         =   array("Authorization:Zoho-oauthtoken $token", "Content-Type: application/json");



while($row=mysql_fetch_array($call_master)){
    
    
    $First_Name         =   $row['Field1'];
    $Last_Name          =   $row['Field2'];
    $Lead_Source        =   $row['Field7'];
    $Lead_Status        =   $row['Field8'];
    $Email_Id           =   $row['Field3'];
    $Mobile             =   $row['Field5'];
    $Phone              =   $row['Field4'];
    $State              =   $row['Field11'];
    $Street             =   $row['Field9'];
    $Website            =   $row['Field6'];
    $Zip_Code           =   $row['Field12'];
    $Country            =   $row['Field13'];
    $Description        =   $row['Field14'];
    $City               =   $row['Field10'];
    
    if (filter_var($Email_Id, FILTER_VALIDATE_EMAIL)) {
        $email = $Email_Id;
    }
    else {
        $email = "";
    }

    $Id     =   $row['Id'];
    $data=array();
    $data ['data'][]=array(
        //'Owner'=>array('name'=>'customersupport@durian.in','id'=>'3608871000002452324'),
        //'Associate_Owner_1'=>array('name'=>'Varsha Patil','id'=>'3608871000000221008'),
        //'Associate_Owner_2'=>array('name'=>'Varsha Patil','id'=>'3608871000000221008'),
        //'Associate_Owner_3'=>array('name'=>'Varsha Patil','id'=>'3608871000000221008'),
        'Email'=>$email,
        'Street'=>$Street,
        'Zip_Code'=>$Zip_Code,
        'City'=>$City,
        'Website'=>$Website,
        'State'=>$State,
        'Country'=>$Country,
        'Description'=>$Description,
        'Salutation'=>'Mr',
        //'Lead_Status'=>'Interested',
        'Lead_Status'=>$Lead_Status,
        'Integration_Source'=>'Dial Desk - Toll Free',
        'First_Name'=>$First_Name,
        'Mobile'=>$Mobile,
        'Phone'=>$Phone,
        'Last_Name'=>$Last_Name,
        'Lead_Source'=>$Lead_Source,
        'Business_Verticals'=>'',
        'Branch_Name'=>''
    );
    
    $data['lar_id']='3608871000018808047';

 echo   $data_json  =   json_encode($data);

 echo '<br/>'; 
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   echo $result  = curl_exec($ch);
    curl_close($ch);
    
    $req = addslashes($data_json);
    $resp = addslashes($result);
    $result_array       =   json_decode($result,true);
        
    if(!empty($result_array)){
    $OtherDate          =   $result_array['data'][0]['details']['Created_Time'] !=""?date("Y-m-d H:i:s",strtotime($result_array['data'][0]['details']['Created_Time'])):NULL;
    $OtherId            =   $result_array['data'][0]['details']['id'];
    $OtherStatus        =   $result_array['data'][0]['status'];
    $OtherDiscription   =   $result_array['data'][0]['message'];
    
    mysql_query("UPDATE `call_master` SET OtherStatus='$OtherStatus',OtherId='$OtherId',OtherDate='$OtherDate',OtherDiscription='$OtherDiscription',field49='$req',field50='$resp' WHERE Id='$Id'"); 
    }  
}

?>


