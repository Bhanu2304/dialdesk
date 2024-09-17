<?php
$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password="dial@mas123"; // Mysql password 
$db_name="db_dialdesk"; // Database name 
// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
//$track = $_GET['DataId'];
//$cs = $_GET['cs']; 

$ClientInfo = mysql_fetch_assoc(mysql_query("select * from `registration_master` where company_id='$clientId' limit 1"));

$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='$clientId' limit 1"));
$PlanDetails = mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1"));

$data = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' and date(CallDate) between '$FromDate' AND '$ToDate'"));
$Inbound = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
$VFO = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
$SMS =  mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
$Email = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

$InboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate';");
$SMSDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate';");
$EmailDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate';");
$VFODetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate';");
//print_r($Inbound); exit;
//echo "SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
//AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate'"; exit;
?> 

<html>
<head>
    <style>
    p{margin:1px;}
    table.gridtable {
        width: 100%;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
       
    }
    table.gridtable th {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        font-size:12px;
    }
    table.gridtable td {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        font-size:12px;
        width:120px;
    }
    
    table.gridtable2 {
        width: 100%;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable2 th {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        font-size:12px;
    }
    table.gridtable2 td {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        font-size:12px;
        width:105px;
    }

    table.gridtable1 {
        width: 100%;
        font-size:12px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable1 th {
       border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable1 td {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        width:77px;
    }
   
    .topleft{
        font-size:10px;
    }
    .topright{
        font-size:10px;
        position: relative;
        left:75%;
        top:-55px;
    }
    .summary{
        margin-left:300px;
    }
    table td width{width:100px;}
    </style>
</head>

<body style="font-family:Arial, Helvetica, sans-serif;">
    <div>
        <br/>
        <img src="res/logo.jpg" style="width:170px;margin-top:-30px;margin-left: 300px;"><br/><br/><br/>
        <span style="font-size: 8px;font-weight: bold;margin-left: 300px;">A DIVISION OF <font color="red">ISPARK</font> Dataconnect Pvt Ltd</span>
    </div>

    <div class="topleft">
        <p><?php echo $ClientInfo['auth_person'];?></p>
        <p><?php echo $ClientInfo['reg_office_address1']; ?></p>
        <p><?php echo $ClientInfo['city'].', '.$ClientInfo['state'].' '.' '.$ClientInfo['pincode']; ?></p>
        <p><?php echo $ClientInfo['phone_no'];?></p>
    </div>
   
    <div class="topright">
        <p>Statement For: <span style="margin-left:10px;"><?php echo '1234879';?></span></p>
        <p>Service Tax No:<span style="margin-left:10px;"><?php echo 'AAFCM4591GST001'; ?></span></p>
        <p>Pan No:<span style="margin-left:44px;"><?php echo 'AAFCM4591G'; ?></span></p>
    </div><br/><br/>
  
    <div class="summary1" >
        <hr>
        <span style="font-weight:bold;font-size:10px;margin-left: 350px;">Summary</span>
        <table style="width:100%;">
            <tr>
                <td>    
                <table class="gridtable">
                <tr>
                    <th>Description</th>
                    <th>Vol./Pulse</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
                    <?php if(!empty($Inbound['Unit'])) { ?>
                 <tr>
                     <td>ICB <?php //echo $PlanDetails['InboundCallCharge']; ?></td>
                    <td><?php echo $Inbound['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['InboundCallCharge'].' Rs./'.$PlanDetails['InboundCallMinute'].'Min'; ?></td>
                    <td><?php echo round($Inbound['Unit']*$PlanDetails['InboundCallCharge'],2); ?></td>
                </tr>
                    <?php } if(!empty($VFO['Unit'])) { ?>
                <tr>
                    <td>VFO <?php //echo $PlanDetails['VFOCallCharge']; ?></td>
                    <td><?php echo $VFO['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['VFOCallCharge'].' Rs./Min'; ?></td>
                    <td><?php echo round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2); ?></td>
                </tr>
                    <?php } if(!empty($SMS['Unit'])) { ?>
                <tr>
                    <td>SMS <?php //echo $PlanDetails['SMSCharge']; ?></td>
                     <td><?php echo $SMS['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['SMSCharge'].' Rs./Min'; ?></td>
                    <td><?php echo round($SMS['Unit']*$PlanDetails['SMSCharge'],2); ?></td>
                </tr>
                <?php } if(!empty($Email['Unit'])) { ?>
                <tr>
                    <td>Email <?php //echo $PlanDetails['EmailCharge'].' Rs./Min'; ?></td>
                    <td><?php echo $Email['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['EmailCharge'].' Rs./Min'; ?></td>
                    <td><?php echo round($Email['Unit']*$PlanDetails['EmailCharge'],2); ?></td>
                </tr>
                <?php } ?>
            </table>
             
        </td>
        <td>
            <table class="gridtable2">
                <tr>
                    <th>Description</th>
                    <th>&nbsp;</th>
                </tr>
                 <tr>
                    <td>Statement From</td>
                    <td><?php echo date_format(date_create($FromDate),'d-M-y'); ?></td>
                </tr>
                <tr>
                    <td>Statement To</td>
                    <td><?php echo date_format(date_create($ToDate),'d-M-y'); ?></td>
                </tr>
                <tr>
                    <td>Validity</td>
                    <td><?php echo date_format(date_create($BalanceMaster['end_date']),'d-M-y'); ?></td>
                </tr>
                <tr>
                    <td>Credit As On Date</td>
                    <td><?php echo $BalanceMaster['Balance']; ?></td>
                </tr>
                <tr>
                    <td>Credit Used</td>
                    <td><?php echo ($PlanDetails['Balance']-$BalanceMaster['Balance']); ?></td>
                </tr>
            </table>
           
        </td>
    </tr>
</table>
            
        <hr>
        <span style="font-weight:bold;font-size:10px;margin-left: 350px;">Statement</span>
    </div>
    
    
    <div>
       
        <?php if(!empty($Inbound['Unit'])) { ?>
        <br/><span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['company_name']; ?> Inbound</span>
        
        <table class="gridtable1">
            <tr>
                <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                    <th style="border-bottom: 0px;border-top: 0px;background-color: #ffffff;width: 55px;"></th>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
            </tr>
            
            <?php $InTotal = 0; $i =0; $flag = false;
                    echo "<tr>";
                    
                    while($inb = mysql_fetch_assoc($InboundDetails))
                    {        
                        if($i%2==0 && $flag) {echo "</tr><tr>";}
                        if($i%2!=0 && $flag) {echo '<td   style="border-bottom: 0px;border-top: 0px;width: 55px;"></td>';}
                        echo '<td align="center">'.$inb['CallDate'].'</td>';
                        echo '<td align="center">'.$inb['CallTime'].'</td>';
                        echo '<td align="center">'.$inb['CallFrom'].'</td>';
                        echo '<td align="center" style="width: 30px;">'.$inb['Unit'].'</td>';
                        echo '<td align="center" style="width: 50px;">'.round($inb['Unit']*$PlanDetails['InboundCallCharge'],2).'</td>';
                        $InTotal += $inb['Unit'];
                        //if($i%2!=0) {echo "<tr>";}
                        $flag = true; $i++;
                    }
                    //if($i%2!=0){echo "<td></td><td></td><td></td><td></td><td></td>";}
                    echo '</tr><tr>';
                        if($i%2!=0){echo '<td colspan="5"><b>Total Vol.    '.$InTotal.'</b></td>';}
                        else {echo '<td colspan="11"><b>Total Vol.    '.$InTotal.'</b></td>';}
                    echo "</tr>";
            ?>
         
        </table>
                
        <?php }if(!empty($SMS['Unit'])) { ?>
        <br/><span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['company_name']; ?> SMS</span>
            <table class="gridtable1">
                <tr>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                    <th style="border-bottom: 0px;border-top: 0px;background-color: #ffffff;width: 55px;"></th>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                </tr>
               
                <?php   $SMSTotal = 0; $i =0; $flag = false;
                        echo "<tr>";
                        while($inb = mysql_fetch_assoc($SMSDetails))
                        { 
                        
                            if($i%2==0 && $flag) {echo "</tr><tr>";}
                            if($i%2!=0 && $flag) {echo '<td  style="border-bottom: 0px;border-top: 0px;width: 55px;"></td>';}
                                echo '<td align="center">'.$inb['CallDate'].'</td>';
                                echo '<td align="center">'.$inb['CallTime'].'</td>';
                                echo '<td align="center">'.$inb['CallFrom'].'</td>';
                                echo '<td align="center" style="width: 30px;">'.$inb['Unit'].'</td>';
                                echo '<td align="center" style="width: 50px;">'.round($inb['Unit']*$PlanDetails['SMSCharge'],2).'</td>';
                                $SMSTotal += $inb['Unit'];
                                $flag = true; $i++;
                        }
                        
                        echo '</tr><tr>';
                        if($i%2!=0){echo '<td colspan="5"><b>Total Vol.    '.$SMSTotal.'</b></td>';}
                        else {echo '<td colspan="5"><b>Total Vol.    '.$SMSTotal.'</b></td>';}
                            //echo '<td  style="border-bottom: 0px;border-top: 0px;border-right: 0px"></td><td colspan="4"></td>';
                        echo "</tr>";
                ?>
               
            </table>
               
            <?php } if(!empty($Email['Unit'])) { ?>
     
            <br/><span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['company_name']; ?> Email</span>
            <table class="gridtable1">
                <tr>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                    <th style="border-bottom: 0px;border-top: 0px;background-color: #ffffff;width: 55px;"></th>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                </tr>
              
                <?php $EmailTotal = 0; $i =0; $flag = false;
                        echo "<tr>";
                        while($inb = mysql_fetch_assoc($EmailDetails))
                        {
                            if($i%2==0 && $flag) {echo "</tr><tr>";}
                            if($i%2!=0 && $flag) {echo '<td  style="border-bottom: 0px;border-top: 0px;width: 55px;"></td>';}
                                echo '<td align="center">'.$inb['CallDate'].'</td>';
                                echo '<td align="center">'.$inb['CallTime'].'</td>';
                                echo '<td align="center">'.$inb['CallFrom'].'</td>';
                                echo '<td align="center" style="width: 30px;">'.$inb['Unit'].'</td>';
                                echo '<td align="center" style="width: 50px;">'.round($inb['Unit']*$PlanDetails['EmailCharge'],2).'</td>';
                                $EmailTotal += $inb['Unit'];
                                 $flag = true; $i++;
                        }
                        //if($i%2!=0){echo "<td></td><td></td><td></td><td></td><td></td>";}
                        echo '</tr><tr>';
                            if($i%2!=0){echo '<td colspan="5"><b>Total Vol.    '.$EmailTotal.'</b></td>';}
                        else {echo '<td colspan="5"><b>Total Vol.    '.$EmailTotal.'</b></td>';}
                        echo "</tr>";
                ?>
                
            </table>
          
            <?php } if(!empty($VFO['Unit'])) { ?>
        
            <br/><span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['company_name']; ?> VFO</span>
            <table class="gridtable1">
                <tr>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                    <th style="border-bottom: 0px;border-top: 0px;background-color: #ffffff;width: 55px;"></th>
                    <th align="center">Date</th>
                    <th align="center">Time</th>
                    <th align="center">Call From</th>
                    <th align="center">Pulse</th>
                    <th align="center">Rate</th>
                </tr>
               
                <?php   $VFOTotal = 0; $i =0; $flag = false;
                         echo "<tr>";
                        while($inb = mysql_fetch_assoc($VFODetails))
                        { 
                            if($i%2==0 && $flag) {echo "</tr><tr>";}
                            if($i%2!=0 && $flag) {echo '<td  style="border-bottom: 0px;border-top: 0px;width: 55px;"></td>';}
                                echo '<td align="center">'.$inb['CallDate'].'</td>';
                                echo '<td align="center">'.$inb['CallTime'].'</td>';
                                echo '<td align="center">'.$inb['CallFrom'].'</td>';
                                echo '<td align="center" style="width: 30px;">'.$inb['Unit'].'</td>';
                                echo '<td align="center" style="width: 50px;">'.round($inb['Unit']*$PlanDetails['VFOCallCharge'],2).'</td>';
                                $VFOTotal += $inb['Unit'];
                            $flag = true; $i++;
                        }
                        //if($i%2!=0){echo "<td></td><td></td><td></td><td></td><td></td>";}
                        echo '</tr><tr>';
                            if($i%2!=0){echo '<td colspan="5"><b>Total Vol.    '.$VFOTotal.'</b></td>';}
                        else {echo '<td colspan="5"><b>Total Vol.    '.$VFOTotal.'</b></td>';}
                        echo "</tr>";
                ?>
               
            </table>
            <?php } ?>
    </div>
   
</body>
</html>

