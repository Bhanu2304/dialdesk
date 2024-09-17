<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

$db2 = mysqli_connect("localhost","root","dial@mas123") or die(mysqli_error($db2));
mysqli_select_db($db2,"db_dialdesk")or die("cannot select DB dialdesk");

$db6 = mysqli_connect("192.168.10.5","root","vicidialnow") or die(mysqli_error($db6));
mysqli_select_db($db6,"asterisk")or die("cannot select DB dialdesk");

$ispark = mysqli_connect("14.97.63.28", "root", "321*#LDtr!?*ktasb","db_bill"); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include('report-send.php');
//include('function_alert_mechanism.php');

$select = "select * from scenario_automate where report_type='scenario'";
$export = mysql_query($select,$db1);
$tagging_data_arr = array();
$html1 ='';
while($row = mysql_fetch_assoc($export)){ 

    $client_id = $row['client'];
    $to = $row['to'];
    $cc = $row['cc'];
    $condition1 = "DATE(t2.call_date)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition2 = "DATE(CallDate)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";

    $Clientname = mysql_fetch_array(mysql_query("select campaignid,company_name,company_id from registration_master where status='A' and is_dd_client='1' and company_id={$client_id} limit 1"));
    $html = "<table cellspacing='0' border='1'>";
    // print_r($Clientname['campaignid']);
    $html .= "<tr>";
    $html .= "<th>Scenario</th>";
    $html .= "<th>Count</th>";
    $html .= "</tr>";
    if(!empty($Clientname['campaignid']))
    {
        $camaign_id = "t2.campaign_id in(".$Clientname['campaignid'].")";

        $qry1 = "SELECT Category2,COUNT(1) as count FROM call_master_out WHERE  ClientId='$client_id' and $condition2  GROUP BY Category2 ";
        $tagging_qry = mysql_query($qry1,$db1);
        $total = '';
        while($row = mysql_fetch_assoc($tagging_qry))
        {
            $html1 .= "<tr>
                    <td>{$row['Category2']}</td>
                    <td>{$row['count']}</td>
                </tr>";

                $total += $row['count'];
        }
        if ($client_id=='511')
        {


            $qry1 = "SELECT Category3,COUNT(1) as count FROM call_master_out WHERE  ClientId='$client_id' and Category3='Walk In' and $condition2   ";
            $tagging_qry = mysql_query($qry1,$db1);
            
            
            while($row = mysql_fetch_assoc($tagging_qry))
            {
                $html1 .= "<tr>
                        <td>{$row['Category3']}</td>
                        <td>{$row['count']}</td>
                    </tr>";


            }
        }
        
    

        $qry = "SELECT SUM(if(t2.`user` !='VDAD',1,0)) `Connected`,SUM(if(t2.`user` ='VDAD',1,0)) `NotConnected` FROM `vicidial_log` t2
                    JOIN vicidial_agent_log va ON t2.uniqueid=va.uniqueid 
                    WHERE $condition1 and $camaign_id";

        $dialer_qry = mysqli_query($db6,$qry);
        $dialer_data =   mysqli_fetch_assoc($dialer_qry);

        $total_connected = $dialer_data['Connected'];
        $total_not_connected = $dialer_data['NotConnected'];

        $total =$total_connected +$total_not_connected;

        $html .= "<tr>
                    <td>Agent Count</td>
                    <td>$total_connected</td>
                </tr>";

        $html .= "<tr>
                    <td>Total Number Of Calls</td>
                    <td>$total</td>
                </tr>";

        $html .= "<tr>
                    <td>Connected Call</td>
                    <td>{$total_connected}</td>
                </tr>";

        $html .= "<tr>
                    <td>Not Connected Calls</td>
                    <td>{$total_not_connected}</td>
                </tr>";

        $html .=   $html1;      

        $html .= "</html>";


        scenarion_data($html,$Clientname['company_name'],$to,$cc,$db1);
        

        
    }
    

    
}



function scenarion_data($data_Arr,$client_name,$to,$cc,$db1)
{

    $too = explode(',',$to);
    $ccc = explode(',',$cc);
    //$too = array('bhanu.singh@teammas.in');

    $last_day_date = date('d-m-Y',strtotime("-1 days")); 



    
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "$client_name - ($last_day_date) "; 
    $EmailText      ="<div>";

    $EmailText  .= $data_Arr;
    $EmailText  .= "<p>This is auto genrated mail.</p>";
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";
    $EmailText  .= "</div>";

    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $too,'AddCc'=>$ccc);
    $done = send_email($emaildata);


    if($done=='1'){
        mysql_query("insert scenariowise_alert_log SET client_name='{$client_name}',too='$to',cc='$cc',MailStatus='Success',MailDateTime=NOW()",$db1);
    }
    
}






?>


