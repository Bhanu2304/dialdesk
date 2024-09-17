<?php
include_once('function.php');

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$qry=mysql_query("SELECT company_id,company_name FROM `registration_master` WHERE `status`='A' ORDER BY company_name");   

$html .='<table border="1">
        <tr>
        <th>CLIENT NAME</th>
        <th>PLAN NAME</th>
        <th>PLAN TYPE</th>
        <th>PLAN RENT</th>
        <th>FREE VALUE</th>
        <th>VALIDITY START DATE</th>
        <th>VALIDITY END DATE</th>
        <th>CURRENT ACCOUNT BALANCE</th>
        <th>ACCOUNT STATUS</th>
    </tr>';
        
        while($row=  mysql_fetch_assoc($qry)){
            
            $Balanceqry=mysql_query("SELECT * FROM `balance_master` WHERE clientId='{$row['company_id']}' LIMIT 1");
            $BalanceArr=mysql_fetch_array($Balanceqry);
            
            $Planqry=mysql_query("select * from `plan_master` where Id='{$BalanceArr['PlanId']}' limit 1");
            $PlanArr=mysql_fetch_array($Planqry);
            
            $CD   = date('Y-m-d');
            $LD   = $BalanceArr['end_date'];

            $datecolor=expir_date_color_code($LD,$CD);
            $balacolor=balance_color_code($BalanceArr['Used'],$BalanceArr['MainBalance'],$LD);
           	
            $html .='<tr>';
            $html .='<td>'.$row['company_name'].'</td>';
            if(!empty($BalanceArr)){
                $html .='<td>'.$PlanArr['PlanName'].'</td>';
                $html .='<td>'.$BalanceArr['PlanType'].'</td>';
                $html .='<td>'.$PlanArr['RentalAmount'].'</td>';
                $html .='<td>'.$PlanArr['Balance'].'</td>';
                $html .='<td>'.$BalanceArr['start_date'].'</td>';
                $html .='<td style="background-color:'.$datecolor.'">'.$BalanceArr['end_date'].'</td>';
                if($LD !=""){
                    $html .='<td style="background-color:'.$balacolor.'">'.$BalanceArr['Balance'].'</td>';
                }
                else{
                    $html .='<td></td>'; 
                }
            }else{
                $html .='<td>No Plan Allocated</td>
                <td>NA</td>
                <td>NA</td>
                <td>NA</td>
                <td>NA</td>
                <td>NA</td>
                <td>NA</td>';
                 }
                $html .='<td>Active</td>
            </tr>';

        } 
    $html .='</table>';
    
   
    $mailqry=mysql_query("SELECT * FROM `bill_summary_auto_mail_master` WHERE `Status`='A'");             
    while($DataArr=  mysql_fetch_assoc($mailqry)){
        mail_send($html,$DataArr['email'],$DataArr['Name']);        
    }
    
    exit;
?>


