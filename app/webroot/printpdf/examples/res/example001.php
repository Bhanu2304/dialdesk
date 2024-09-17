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

$BillMaster = mysql_fetch_assoc(mysql_query("SELECT Id,clientId,DATE_FORMAT(BillStartDate,'%d %b %Y') `BillStartDate`,DATE_FORMAT(BillEndDate,'%d %b %Y') `BillEndDate`, LastBilled, paymentDue,
AfterDueDate,DATE_FORMAT(DATE_ADD(BillEndDate,INTERVAL 15 DAY),'%d %b %Y') `DueDate`,paymentPaid,Adjustments,LastCarriedAmount
 FROM `post_bill_master` WHERE clientId='$clientId' ORDER BY id DESC LIMIT 1"));
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
th#t01 {
        border-right:1px solid black;
	border-bottom:1px solid black;
}
td#t02
{
	border-right:1px solid black;
        border-bottom:1px solid black;
	border-Left:1px solid black;
	border-Top:1px solid black;
        font-size:10px;font-family:Arial,Helvetica, sans-serif;
}
</style>
<style>
td#t03
{
	border-bottom:1px solid black;
        border-Top:1px solid black;
        border-Left:1px solid black;
        
}
td#t04
{
	border-right:1px solid black;
}
th#t05
{
	border-bottom:1px solid black;
	border-Left:1px solid black;
	border-Top:1px solid black;
}
td#t06
{
	border-bottom:1px solid black;
	border-Top:1px solid black;
}
td#t07
{
	border-bottom:1px solid black;
}

</style>

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
    font-size:10px;
}
table.gridtable td {
    border-width: 1px;
    border-style: solid;
    border-color: #666666;
    background-color: #ffffff;
    font-size:10px;
}
.topleft{
    font-size:10px;
}
.topright{
    font-size:10px;
    position: relative;
    left:70%;
    top:-55px;
}
.summary{
    position: relative;
    top:-40px;
}
</style>

</head>
<body style="font-size:12px;  font-family:Arial, Helvetica, sans-serif;">

    <div>
        
            
            <span style="font-size: 8px;font-weight: bold;">A DIVISION OF <font color="red">ISPARK</font> Dataconnect Pvt Ltd</span>
        
    </div>
    
    <div class="topleft">
        <p><?php echo $ClientInfo['auth_person'];?></p>
        <p><?php echo $ClientInfo['reg_office_address1']; ?></p>
        <p><?php echo $ClientInfo['city'].', '.$ClientInfo['state'].' '.' '.$ClientInfo['pincode']; ?></p>
        <p><?php echo $ClientInfo['phone_no'];?></p>
    </div>
    
    <div class="topright">
        <p>Bill No: <span style="margin-left:40px;"><?php echo $BillMaster['Id'];?></span></p>
        <p>Bill Date:<span style="margin-left:37px;"><?php echo $BillMaster['BillEndDate']; ?></span></p>
        <p>Bill Period:<span style="margin-left:30px;"><?php echo $BillMaster['BillStartDate'].'  To  '.$BillMaster['BillStartDate']; ?></span></p>
        <p>Service Tax No:<span style="margin-left:7px;"><?php echo 'AAFCM4591GST001'; ?></span></p>
        <p>Pan No:<span style="margin-left:42px;"><?php echo 'AAFCM91GS'; ?></span></p>
    </div>
  
<table cellpadding="0" cellspacing="0" border="1">
<tr>
    <td>
        <table border="0">
            <tr>
                <td colspan="3" align="center"><b>Bill - Summary</b></td>
            </tr>
            <tr>
                <td align="center" colspan="3">
                    <table class="gridtable" cellspacing="0" cellpadding="1" align ="center">
                        <tr>
                            <td id="t02" align ="center"><b>Last Billed Amt</b></td>
                            <td></td>
                            <td id="t02" align ="center"><b>Payments</b></td>
                            <td></td>
                            <td  id="t02" align ="center"><b>Adjustments</b></td>
                            <td></td>
                            <td id="t02" align ="center"><b>Current Charges</b></td>
                            <td></td>
                            <td id="t02" align ="center"><b>Total Amount Due</b></td>
                            <td id="t02" align ="center"><b>Due Date</b></td>
                            <td id="t02" align ="center"><b>Total Amount Payble After Due Date</b></td>
                        </tr>
                        <tr>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['LastBilled'];?></b></td>
                            <td>&nbsp;-&nbsp;</td>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['paymentPaid'];?></b></td>
                            <td>&nbsp;-&nbsp;</td>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['Adjustments'];?></b></td>
                            <td>&nbsp;+&nbsp;</td>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['LastBilled'];?></b></td>
                            <td>&nbsp;+&nbsp;</td>
                            <td id="t02" align ="center" style="color:blue"><b><?php echo ($BillMaster['LastBilled']-$BillMaster['paymentPaid']-$BillMaster['Adjustments']);?></b></td>
                            <td id="t02" align ="center" style="color:blue"><b><?php echo $BillMaster['DueDate'];?></b></td>
                            <td id="t02" align ="center" style="color:blue"><b><?php echo ($BillMaster['LastBilled']-$BillMaster['paymentPaid']-$BillMaster['Adjustments']+100);?></b></td>
                        </tr>    
                    </table>
                </td>
            </tr>
            
            <tr><td colspan="3" align="center"><br><br></td></tr>
            <tr><td colspan="3" align="left"><b>Bill - Details</b></td></tr>
            <tr>
                <td colspan="2">
                    <table class="gridtable" cellspacing="0" cellpadding="1" width = "300" style="font-size:10px;font-family:Arial,Helvetica, sans-serif;">
                        <tr style="background-color:dimgrey">
                            <td id="3"><b>Summary Of Current Charges</b></td>
                            <td id="t04"></td>
                            <td id="t04" align="right"><b>Amount(Rs.)</b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Balance Carried Forward</b></td>
                            <td id="t04"></td>
                            <td id="t04"  align="right"><b><?php echo $BillMaster['LastCarriedAmount']; ?></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Monthly Charges</b></td>
                            <td id="t04"><?php echo $BillMaster['LastBilled'];?></td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Usage Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Setup Cost</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Bounce Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Latepayment Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Other Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Discount</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Bounce Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Late Payment Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Other Charges</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Discount</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04"  align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Total Due Amount</b></td>
                            <td id="t04"></td>
                            <td id="t04"  align="right"><b><?php echo $BillMaster['LastBilled'] + $BillMaster['LastCarriedAmount'];?></b></td>
                        </tr>
                    </table>
                </td>
                <td align="left">
                    
                </td>
                
            </tr>
        </table>
    </td>
</tr>
</table>
    
    
    
    <br><br><br><br><br><br><br><br><br><br><br><br>  
    <br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br>
    <hr>
    <h6 align="center"><b>
        3F-CS33, Ansal Plaza, Vaishali, Ghaziabad,Uttar Pradesh,201010
        <br>
        Contact - 011-61105555 | care@teammas.in
        </b>
    </h6>
    
    Plan Details
    <table class="gridtable">
        <tr><td>Plan Name</td><td><?php echo $PlanDetails['PlanName']; ?></td></tr>
        <tr><td>Setup Cost</td><td><?php echo $PlanDetails['SetupCost']; ?></td></tr>
        <tr><td>Rental Amount</td><td><?php echo $PlanDetails['RentalAmount']; ?></td></tr>
        <tr><td>Balance</td><td><?php echo $PlanDetails['Balance']; ?></td></tr>
        <tr><td>Rental Period</td><td><?php echo $PlanDetails['RentalPeriod'].' '.$PlanDetails['PeriodType']; ?></td></tr>
        <tr><td>Number Type</td><td><?php echo $PlanDetails['NumberType']; ?></td></tr>
        <tr><td>Inbound Call Charge</td><td><?php echo $PlanDetails['InboundCallCharge'].' Rs./'.$PlanDetails['InboundCallMinute'].' Min'; ?></td></tr>
        <tr><td>Outbound Call Charge</td><td><?php echo $PlanDetails['OutboundCallCharge'].' Rs.'.$PlanDetails['OutboundCallMinute'].' Min'; ?></td></tr>
        <tr><td>Miss Call Charge</td><td><?php echo $PlanDetails['MissCallCharge'].' Rs./Min'; ?></td></tr>
        <tr><td>VFO Call Charge</td><td><?php echo $PlanDetails['VFOCallCharge'].' Rs./Min'; ?></td></tr>
        <tr><td>SMS Charge</td><td><?php echo $PlanDetails['SMSCharge'].' Rs./'.$PlanDetails['PlanMaster']['SMSLength'].' Chr'; ?></td></tr>
        <tr><td>Email Charge</td><td><?php echo $PlanDetails['EmailCharge'].' Rs./Min'; ?></td></tr>
        <tr><td>No. Of Free User</td><td><?php echo $PlanDetails['NoOfFreeUser']; ?></td></tr>
        <tr><td>Charge For Extra User</td><td><?php echo $PlanDetails['ChargePerExtraUser'].' Rs./User'; ?></td></tr>
        <tr><td>Transfer After Rental</td><td><?php echo $PlanDetails['TransferAfterRental'].' Rs./Min'; ?></td></tr>
    </table>
    
</body>
</html>

