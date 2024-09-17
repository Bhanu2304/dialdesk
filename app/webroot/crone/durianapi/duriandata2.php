<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$ClientId       =   37;
$call_master    =   mysql_query("SELECT * FROM call_master WHERE ClientId='$ClientId' AND Category1='Complaint' AND DATE(CallDate)=CURDATE() AND OtherId IS NULL");
$field_master   =   mysql_query("SELECT FieldName,fieldNumber FROM `field_master` WHERE ClientId='$ClientId' AND FieldStatus IS NULL ORDER BY Priority");

$token_url      =   "https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.5dcc40afc3b5d65b3f5de9a6ec8d4407.9060152e30c1b0a85d5c0c81f877ce46&client_id=1000.3WVPW6Y32NQV51958AX6UZC2135EJH&client_secret=8f5905c472beedb9d7a91e544f8710a037584d43ce&grant_type=refresh_token";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);

$token_array    =   json_decode($response,true);
$token          =   $token_array['access_token'];
//$token          =   "1000.cb1c3a2b59a21776faf2a2a0f84dcf8b.a4165e7515d372cfcceb8527d02c64c8";

//echo "<pre>";

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
    
    $Id                 =   $row['Id'];
    $Mobile             =   $row['Field18'];
    $City               =   $row['Field30'];
    $Description        =   $row['Field35'];
    $Email_Id           =   $row['Field31'];
    $Showroom_Name      =   $row['Field38'];
    $classification     =   $row['Field39'];
    
    $subject            =   $row['Category1']." ".$row['Field25'];
    
    if (filter_var($Email_Id, FILTER_VALIDATE_EMAIL)) {
        $email = $Email_Id;
    }
    else {
        $email = "";
    }
    
    //$email="drnitinwalia@gmail.com";
    //$email="chandresh.mani001@gmail.com";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://desk.zoho.com/api/v1/contacts/search?limit=1&email=$email");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:Zoho-oauthtoken $token", "orgId:681789139","Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $search_result  = curl_exec($ch);
    curl_close($ch);
    $search_array   =   json_decode($search_result,true);
    $contactId      =   $search_array['data'][0]['id'];
    
    //print_r($search_array);die;
    
    $data           =   array();
    
    if($contactId !=""){
        $url="https://desk.zoho.com/api/v1/tickets";
        $data =array(
            'subject'=>$subject,
            'description'=>$Description,
            'departmentId'=>'352472000000006907',
            'contactId'=>$contactId,
            'channel'=>'Dial Desk',
            'phone'=>$Mobile,
            'classification'=>$classification,
            'email'=>$email,
            'customFields'=>array('Showroom Name'=>$Showroom_Name)
        );
        
        $result_array       =   curlRun($data,$url,$token);
        $OtherDate          =   $result_array['createdTime'] !=""?date("Y-m-d H:i:s",strtotime($result_array['createdTime'])):NULL;
        $OtherId            =   $result_array['id'];
        $OtherStatus        =   NULL;
        $OtherDiscription   =   NULL;
        mysql_query("UPDATE `call_master` SET OtherStatus='$OtherStatus',OtherId='$OtherId',OtherDate='$OtherDate',OtherDiscription='$OtherDiscription' WHERE Id='$Id'"); 
    }
    else{
       $url="https://desk.zoho.com/api/v1/contacts";
       $data =array(
           //'zip'=>'123902',
           'lastName'=>$Last_Name,
           'country'=>'India',
           //'secondaryEmail'=>$email,
           //'city'=>$City,
           //'facebook'=>$First_Name,
           //'mobile'=>$Mobile,
           //'description'=>$Description,
           //'type'=>'paidUser',
           //'title'=>'The contact',
           'firstName'=>$First_Name,
           //'twitter'=>$First_Name,
           'phone'=>$Mobile,
           //'street'=>$City,
           //'state'=>$City,
           'email'=>$email
        ); 
       
       $result_array       =   curlRun($data,$url,$token);
       
       if($result_array['id'] !=""){
           $contactId   =   $result_array['id'];
           $url="https://desk.zoho.com/api/v1/tickets";
            $data =array(
                'subject'=>$subject,
                'description'=>$Description,
                'departmentId'=>'352472000000006907',
                'contactId'=>$contactId,
                'channel'=>'Dial Desk',
                'phone'=>$Mobile,
                'classification'=>$classification,
                'email'=>$email,
                'customFields'=>array('Showroom Name'=>$Showroom_Name)
            );
        
            $result_array       =   curlRun($data,$url,$token);
            $OtherDate          =   $result_array['createdTime'] !=""?date("Y-m-d H:i:s",strtotime($result_array['createdTime'])):NULL;
            $OtherId            =   $result_array['id'];
            $OtherStatus        =   NULL;
            $OtherDiscription   =   NULL;
            mysql_query("UPDATE `call_master` SET OtherStatus='$OtherStatus',OtherId='$OtherId',OtherDate='$OtherDate',OtherDiscription='$OtherDiscription' WHERE Id='$Id'"); 
       }
       
    }
}

function curlRun($data,$url,$token){
    $data_json  =   json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:Zoho-oauthtoken $token", "orgId:681789139","Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result  = curl_exec($ch);
    curl_close($ch);
    return  json_decode($result,true); 
}

?>


