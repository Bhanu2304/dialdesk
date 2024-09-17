<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$ClientId       =   37;
$call_master    =   mysql_query("SELECT * FROM call_master WHERE ClientId='$ClientId' AND Category1='Sales Enquiry' AND DATE(CallDate)=CURDATE()  and id='1090912' ");
$field_master   =   mysql_query("SELECT FieldName,fieldNumber FROM `field_master` WHERE ClientId='$ClientId' AND FieldStatus IS NULL ORDER BY Priority");

$url            =   "https://www.zohoapis.com/crm/v2/Leads";
//echo 'token_url=: <br/>';
 $token_url      =   "https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.e228dbf100ebe9a1e499b11a431349aa.7dc8b1b04471403094cb595253e9b8a1&client_id=1000.DFM05K2WDTXE30993XIGJMR99HOH6P&client_secret=218a6c49cf1f31bd09980d1332346801581004ec04&grant_type=refresh_token";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response  = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

echo '<br/><br/>token_response=: <br/>';
print_r($response);
exit;
$token_array    =   json_decode($response,true);

echo '<br/>token=: <br/>';
echo $token          =   $token_array['access_token'];
$header         =   array("Authorization:Zoho-oauthtoken $token", "Content-Type: application/json");

echo '<br/>header=: <br/>';
print_r(json_encode($header));

while($row=mysql_fetch_array($call_master)){
    
    $User_Name          =   $row['Field1'];
    $exp                =   explode(" ", trim($User_Name));
    $First_Name         =   current($exp);
    
    if(end($exp) !=""){
        $Last_Name  =   end($exp);
    }
    else{
        $Last_Name  =   current($exp); 
    }
    
    
    $Lead_Source        =   $row['Category4'];
    $Mobile             =   $row['Field18'];
    $Description        =   $row['Field35'];
    $Product_Category   =   $row['Field25'];
    $City               =   $row['Field30'];
    $Email_Id           =   $row['Field31'];
    $Branch_Name        =   $row['Field38'];
    $Business_Verticals =   $row['Field40'];
    
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
        'Street'=>'',
        'Zip_Code'=>'',
        'City'=>$City,
        'Sales_Person'=>array('name'=>'Dial Desk','id'=>'3608871000003291037'),
        'State'=>'',
        'Country'=>'',
        'Product_Category'=>array($Product_Category),
        'Other_Product_Catogeory'=>'',
        'Description'=>$Description,
        'Business_Type'=>'Non-GST Billing',
        'Rating'=>'Prospecting',
        'Salutation'=>'Mr',
        'Lead_Status'=>'Interested',
        'Integration_Source'=>'Dial Desk - Toll Free',
        'First_Name'=>$First_Name,
        'Mobile'=>$Mobile,
        'Last_Name'=>$Last_Name,
        'Lead_Source'=>$Lead_Source,
        'Business_Verticals'=>array($Business_Verticals),
        'Branch_Name'=>$Branch_Name
    );
    
    $data['lar_id']='3608871000018808047';

    $data_json  =   json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result  = curl_exec($ch);
    curl_close($ch);
    
    $result_array       =   json_decode($result,true);
        echo '<br/>url=: <br/>';
    echo $url;
    echo '<br/>request=: <br/>';
    print_r($data_json);
    echo '<br/>response=: <br/>';
    print_r($result);
    
    if(!empty($result_array)){
    $OtherDate          =   $result_array['data'][0]['details']['Created_Time'] !=""?date("Y-m-d H:i:s",strtotime($result_array['data'][0]['details']['Created_Time'])):NULL;
    $OtherId            =   $result_array['data'][0]['details']['id'];
    $OtherStatus        =   $result_array['data'][0]['status'];
    $OtherDiscription   =   $result_array['data'][0]['message'];
    
    mysql_query("UPDATE `call_master` SET OtherStatus='$OtherStatus',OtherId='$OtherId',OtherDate='$OtherDate',OtherDiscription='$OtherDiscription' WHERE Id='$Id'"); 
    }  
}

?>


