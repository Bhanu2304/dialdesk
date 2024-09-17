<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.airtable.com/v0/appGBRJtMaWKdc0zp/Table%201/?view=Grid%20view&api_key=keyRLCczvMt3WWuuZ',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
]);
$resp = curl_exec($curl);
curl_close($curl);

$result = json_decode($resp,true);

foreach($result as $row){
    foreach($row as $val){
        $ClientId   =   "325";
        $Id         =   $val['id'];
        $Name       =   $val['fields']['Name'];
        $Email      =   $val['fields']['Email'];
        $Phone      =   $val['fields']['Phone'];
        $City       =   $val['fields']['City'];
        $Date       =   $val['fields']['Date'] !=""?date("Y-m-d H:i:s",strtotime($val['fields']['Date'])):NULL;
        $LeadSource =   $val['fields']['LeadSource'];
        $SzoSource  =   $val['fields']['SzoSource'];
        $Uniqueid   =   $val['fields']['Uniqueid'];
        $cfhID      =   $val['fields']['cfhID'];
        
        /*
        $data = array('fields'=>array(
            "Name" => $Name,
            "Email" => $Email,
            "Phone" => $Phone,
            "City" => $City,
            "Date" => $val['fields']['Date'],
            "Action" => "upload",
            "LeadSource" => $LeadSource,
            "SzoSource" => $SzoSource,
            "Uniqueid" => $Uniqueid,
            "cfhID" => $cfhID,
            ));
        */
        
        //print_r($data);die;
         
        $existRes   =   mysql_query("SELECT COUNT(Id) AS NumberRow FROM AnandHearingApiData WHERE cfhID='$cfhID'");
        $existRow   =   mysql_fetch_assoc($existRes);
        
        if($existRow['NumberRow'] ==0){
            
            $Insert =   mysql_query("INSERT INTO AnandHearingApiData (ClientId,Name,Email,Phone,City,CustDate,LeadSource,SzoSource,Uniqueid,cfhID)VALUES('$ClientId','$Name','$Email','$Phone','$City','$Date','$LeadSource','$SzoSource','$Uniqueid','$cfhID')");
       
            if($Insert){
                $curl = curl_init();
                curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE"),
                //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"),
                //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)),   
                CURLOPT_URL => 'https://api.airtable.com/v0/appGBRJtMaWKdc0zp/Table%201/'.$Id.'?view=Grid%20view&api_key=keyRLCczvMt3WWuuZ',
                CURLOPT_USERAGENT => 'Codular Sample cURL Request'
                ]);
                $resp = curl_exec($curl);
                curl_close($curl);
            }     
        }
    }
}
?>


