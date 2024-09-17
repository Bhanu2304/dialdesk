<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$ClientId       =   368;
$call_master    =   mysql_query("SELECT * FROM call_master WHERE ClientId='$ClientId' AND Category1='Contacts' AND DATE(CallDate)=CURDATE() AND OtherId IS NULL");
$field_master   =   mysql_query("SELECT FieldName,fieldNumber FROM `field_master` WHERE ClientId='$ClientId' AND FieldStatus IS NULL ORDER BY Priority");

$token_url      =   "https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.5dcc40afc3b5d65b3f5de9a6ec8d4407.9060152e30c1b0a85d5c0c81f877ce46&client_id=1000.3WVPW6Y32NQV51958AX6UZC2135EJH&client_secret=8f5905c472beedb9d7a91e544f8710a037584d43ce&grant_type=refresh_token";

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

//$token_array    =   json_decode($response,true);

//echo $token          =   $token_array['access_token'];
//$header         =   array("Authorization:Zoho-oauthtoken $token", "Content-Type: application/json");


$token_array    =   json_decode($response,true);
echo $token          =   $token_array['access_token']; exit;
//$token          =   "1000.cb1c3a2b59a21776faf2a2a0f84dcf8b.a4165e7515d372cfcceb8527d02c64c8";

//echo "<pre>";

while($row=mysql_fetch_array($call_master)){
    
    $First_Name         =   $row['Field1'];
    $Last_Name          =   $row['Field2'];
    $Email_Id           =   $row['Field3'];
    $Mobile             =   $row['Field5'];
    $Phone              =   $row['Field4'];

    
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
    $search_result  = curl_exec($ch);
    curl_close($ch);
    $search_array   =   json_decode($search_result,true);
    $contactId      =   $search_array['data'][0]['id'];
    
    //print_r($search_array);die;
    
    $data           =   array();
    
    if($contactId !=""){
        $url="https://desk.zoho.com/api/v1/tickets";
        $data =array(

            'contactId'=>$contactId,
            'channel'=>'Dial Desk',
            'First_Name'=>$First_Name,
            'Mobile'=>$Mobile,
            'Phone'=>$Phone,
            'Last_Name'=>$Last_Name,
            'Email'=>$email
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
          
            'channel'=>'Dial Desk',
            'First_Name'=>$First_Name,
            'Mobile'=>$Mobile,
            'Phone'=>$Phone,
            'Last_Name'=>$Last_Name,
            'Email'=>$email
        ); 
       
       $result_array       =   curlRun($data,$url,$token);
       
       if($result_array['id'] !=""){
           $contactId   =   $result_array['id'];
           $url="https://desk.zoho.com/api/v1/tickets";
            $data =array(
                'contactId'=>$contactId,
            'channel'=>'Dial Desk',
            'First_Name'=>$First_Name,
            'Mobile'=>$Mobile,
            'Phone'=>$Phone,
            'Last_Name'=>$Last_Name,
            'Email'=>$email
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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result  = curl_exec($ch);
    curl_close($ch);
    return  json_decode($result,true); 
}

?>


