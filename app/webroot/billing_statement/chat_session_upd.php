<?php
$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

$dd = mysqli_connect("$host", "$username", "$password","$db_name"); 
if (mysqli_connect_errno($dd)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}


$dialdee = mysql_connect("192.168.10.23", "root", "Mas@1234")or die("cannot connect to dialdee"); 
mysql_select_db("$db_name",$dialdee)or die("cannot select DB");

$sel_chat = "SELECT DATE(created_at) dater,COUNT(1) cnt FROM chat_customer  WHERE  client_id='499' GROUP BY DATE(created_at)";
$rsc = mysql_query($sel_chat,$dialdee) or die("error in qry");

while($det = mysql_fetch_assoc($rsc))
{
    $dater = $det['dater']; 
    $cnt = $det['cnt'];
    $total = round($cnt*0.75,2);
    
    
    echo $upd = "update billing_consume_daily set dialdee_pulse='$cnt',dialdee_charge='0.75',dialdee_total='$total' where client_id='499' and date(cm_date)='$dater' limit 1;";
    echo '<br/>';
    $rsc_upd = mysql_query($upd,$dd) ;
    //exit;
}