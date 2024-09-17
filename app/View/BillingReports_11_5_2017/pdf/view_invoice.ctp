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
        <center>
            <?php echo $this->Html->image('logo.jpg', array('fullBase' => true,'style'=>'width:140px;margin-top:-30px;'));?><br/> 
            <span style="font-size: 8px;font-weight: bold;">A DIVISION OF <font color="red">ISPARK</font> Dataconnect Pvt Ltd</span>
        </center>
    </div>
    
    <div class="topleft">
        <p><?php echo $ClientInfo['RegistrationMaster']['auth_person'];?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['company_name'];?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['reg_office_address1']; ?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['city'].', '.$ClientInfo['RegistrationMaster']['state'].' '.' '.$ClientInfo['RegistrationMaster']['pincode']; ?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['phone_no'];?></p>
    </div>
    
    <div class="topright">
        <p>Bill No: <span style="margin-left:40px;"><?php echo $BillMaster['0']['post_bill_master']['Id'];?></span></p>
        <p>Bill Date:<span style="margin-left:37px;"><?php echo $BillMaster['0']['0']['BillEndDate']; ?></span></p>
        <p>Bill Period:<span style="margin-left:30px;"><?php echo $BillMaster['0']['0']['BillStartDate'].'  To  '.$BillMaster['0']['0']['BillEndDate']; ?></span></p>
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
                            <td id="t02" align ="center"><b><?php echo $BillMaster['0']['post_bill_master']['LastCarriedAmount'];?></b></td>
                            <td>&nbsp;-&nbsp;</td>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['0']['post_bill_master']['paymentPaid'];?></b></td>
                            <td>&nbsp;-&nbsp;</td>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['0']['post_bill_master']['Adjustments'];?></b></td> 
                            <td>&nbsp;+&nbsp;</td>
                            <td id="t02" align ="center"><b><?php echo $BillMaster['0']['post_bill_master']['CurrentCharge'];?></b></td> 
                            <td>&nbsp;=&nbsp;</td>
                            <td id="t02" align ="center" style="color:blue"><b><?php echo ($BillMaster['0']['post_bill_master']['paymentDue']-$BillMaster['0']['post_bill_master']['paymentPaid']-$BillMaster['0']['post_bill_master']['Adjustments']);?></b></td>
                            <td id="t02" align ="center" style="color:blue"><b><?php echo $BillMaster['0']['0']['DueDate'];?></b></td>
                            <td id="t02" align ="center" style="color:blue"><b><?php echo ($BillMaster['0']['post_bill_master']['paymentDue']-$BillMaster['0']['post_bill_master']['paymentPaid']-$BillMaster['0']['post_bill_master']['Adjustments']+100);?></b></td>
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
                            <td id="t04"  align="right"><b><?php echo round($BillMaster['0']['post_bill_master']['LastCarriedAmount']-$BillMaster['0']['post_bill_master']['paymentPaid'],2); ?></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Monthly Charges</b></td>
                            <td id="t04"><?php echo $BillMaster['0']['post_bill_master']['CurrentCharge'];?></td>
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
                            <td id="3"><b>Waiver</b></td>
                            <td id="t04">0.00</td>
                            <td id="t04" align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Service Tax* @ 14.00 %</b></td>
                            <td id="t04"><?php echo $BillMaster['0']['post_bill_master']['serviceTax'] ?></td>
                            <td id="t04" align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>SBC Tax* @ 0.5 %</b></td>
                            <td id="t04"><?php echo $BillMaster['0']['post_bill_master']['sbcTax'] ?></td>
                            <td id="t04" align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Krishi Tax* @ 0.5 %</b></td>
                            <td id="t04"><?php echo $BillMaster['0']['post_bill_master']['krishiTax'] ?></td>
                            <td id="t04" align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Total Charge</b></td>
                            <td id="t04"><?php echo ($BillMaster['0']['post_bill_master']['krishiTax']+$BillMaster['0']['post_bill_master']['sbcTax']+$BillMaster['0']['post_bill_master']['serviceTax']+$BillMaster['0']['post_bill_master']['CurrentCharge']) ?></td>
                            <td id="t04" align="right"><b></b></td>
                        </tr>
                        <tr>
                            <td id="3"><b>Total Due Amount</b></td>
                            <td id="t04"></td>
                            <td id="t04"  align="right"><b><?php echo ($BillMaster['0']['post_bill_master']['krishiTax']+$BillMaster['0']['post_bill_master']['sbcTax']+$BillMaster['0']['post_bill_master']['serviceTax']+$BillMaster['0']['post_bill_master']['CurrentCharge']+$BillMaster['0']['post_bill_master']['LastCarriedAmount']-$BillMaster['0']['post_bill_master']['paymentPaid']);?></b></td>
                        </tr>
                    </table>
                </td>
                <td align="left">
                   <?php echo $this->Html->image('dialpdflogo.jpg', array('fullBase' => true,'height'=>'200','width'=>'250')); ?>
                </td>
                
            </tr>
        </table>
    </td>
</tr>
</table>
    
    
    
    <br><br><br><br><br><br><br><br><br><br><br><br>  
    <br><br><br><br><br><br><br><br><br><br><br>
    
    <hr>
    <h6 align="center"><b>
        3F-CS33, Ansal Plaza, Vaishali, Ghaziabad,Uttar Pradesh,201010
        <br>
        Contact - 011-61105555 | care@teammas.in
        </b>
    </h6>
    
    Plan Details
    <table class="gridtable">
        <tr><td>Plan Name</td><td><?php echo $PlanDetails['PlanMaster']['PlanName']; ?></td></tr>
        <tr><td>Setup Cost</td><td><?php echo $PlanDetails['PlanMaster']['SetupCost']; ?></td></tr>
        <tr><td>Rental Amount</td><td><?php echo $PlanDetails['PlanMaster']['RentalAmount']; ?></td></tr>
        <tr><td>Balance</td><td><?php echo $PlanDetails['PlanMaster']['Balance']; ?></td></tr>
        <tr><td>Rental Period</td><td><?php echo $PlanDetails['PlanMaster']['RentalPeriod'].' '.$PlanDetails['PlanMaster']['PeriodType']; ?></td></tr>
        <tr><td>Number Type</td><td><?php echo $PlanDetails['PlanMaster']['NumberType']; ?></td></tr>
        <tr><td>Inbound Call Charge</td><td><?php echo $PlanDetails['PlanMaster']['InboundCallCharge'].' Rs./'.$PlanDetails['PlanMaster']['InboundCallMinute'].' Min'; ?></td></tr>
        <tr><td>Outbound Call Charge</td><td><?php echo $PlanDetails['PlanMaster']['OutboundCallCharge'].' Rs.'.$PlanDetails['PlanMaster']['OutboundCallMinute'].' Min';; ?></td></tr>
        <tr><td>Miss Call Charge</td><td><?php echo $PlanDetails['PlanMaster']['MissCallCharge'].' Rs./Min'; ?></td></tr>
        <tr><td>VFO Call Charge</td><td><?php echo $PlanDetails['PlanMaster']['VFOCallCharge'].' Rs./Min'; ?></td></tr>
        <tr><td>SMS Charge</td><td><?php echo $PlanDetails['PlanMaster']['SMSCharge'].' Rs./'.$PlanDetails['PlanMaster']['SMSLength'].' Chr'; ?></td></tr>
        <tr><td>Email Charge</td><td><?php echo $PlanDetails['PlanMaster']['EmailCharge'].' Rs./Min'; ?></td></tr>
        <tr><td>No. Of Free User</td><td><?php echo $PlanDetails['PlanMaster']['NoOfFreeUser']; ?></td></tr>
        <tr><td>Charge For Extra User</td><td><?php echo $PlanDetails['PlanMaster']['ChargePerExtraUser'].' Rs./User'; ?></td></tr>
        <tr><td>Transfer After Rental</td><td><?php echo $PlanDetails['PlanMaster']['TransferAfterRental'].' Rs./Min'; ?></td></tr>
    </table>
    
</body>
</html>