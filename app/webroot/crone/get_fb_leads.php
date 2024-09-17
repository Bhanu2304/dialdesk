<?php
//$db1=mysql_connect('192.168.137.230','root','vicidialnow');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$db1=mysql_connect('localhost','root','dial@mas123');
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

//include('php-sdk/src/facebook.php');

$qry = mysql_query("SELECT * FROM fb_leads WHERE req_status='0'",$db1);
while($data = mysql_fetch_assoc($qry)){

     $ads_id = $data['ads_id']; 
     $client_id = $data['client_id']; 
     $list_id = $data['list_id']; 
     $name = $data['name']; 
     $number = $data['phone_number']; 

     $entry_list[] = $ads_id;

     $send_data[] = "('$client_id','$list_id','$number','$name')";

       
}

if(!empty($send_data))
{
  $ins_merge = implode(",",$send_data);
  $ins_qry = "insert into email_data(client_id,email_no,contact_no,Name) values $ins_merge;"; 
  
  //$rsc_ticket = mysql_query($ins_qry,$db1);
}

if(!empty($rsc_ticket))
{
  $entry_list = implode(",",$entry_list);
  echo $ins_qry = "update fb_leads set req_status='1' where req_status ='0' and ads_id in ($entry_list)"; 
  $rsc_ticket = mysql_query($ins_qry,$db1);
}

 