<?php
//$db1=mysql_connect('192.168.137.230','root','vicidialnow');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$db1=mysql_connect('localhost','root','dial@mas123');
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

//include('php-sdk/src/facebook.php');

$qry = mysql_query("SELECT * FROM fb_master WHERE id='1'",$db1);
while($data = mysql_fetch_assoc($qry)){

     $token = $data['fb_page_token'];   
     $fbpost ="https://graph.facebook.com/{$data['fb_page_id']}/leadgen_forms?access_token={$data['fb_page_token']}";


    $json_request = file_get_contents($fbpost);

    $jsonObject = json_decode($json_request);

    $dataArr = $jsonObject->data;

    if(is_array($dataArr))
    {
        foreach($dataArr as $data)
        {
           $lead_id = $data->id;
            
           $field_data = 'https://graph.facebook.com/'.$lead_id.'/leads?access_token='.$token.'';

           $json_request1 = file_get_contents($field_data);
           
           $jsonObject1 = json_decode($json_request1);
           $dataArr1 = $jsonObject1->data;

           //print_r($dataArr1);
           foreach($dataArr1 as $datafield)
           {
              $ads_id = $datafield->id;
              $create_time = $datafield->created_time;
              $field = $datafield->field_data;
              $name = $field[0]->values[0];
              $number = substr($field[1]->values[0],3);

              $qry1 = mysql_query("SELECT * FROM fb_leads WHERE ads_id='$ads_id' limit 1",$db1);
              $data_check = mysql_fetch_assoc($qry1);

              $is_valid_no = true;

              if(!empty($data_check))
              {
                $is_valid_no = false;
              }
              
              if($is_valid_no)
              {
                $qry_data[] =  "('$ads_id','495','5030','$name','$number','$create_time',now())";
              }
              
            

           }
        }
    }     
}

if(!empty($qry_data))
{
  $ins_merge = implode(",",$qry_data);
  
  $ins_qry = "insert into fb_leads(ads_id,client_id,list_id,name,phone_number,create_date,created_at) values $ins_merge;"; 
  $rsc_ticket = mysql_query($ins_qry,$db1);
}



 