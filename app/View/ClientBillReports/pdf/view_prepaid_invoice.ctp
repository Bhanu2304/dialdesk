
<?php
function convertNumberToWordsForIndia($strnum)
{
        $words = array(
        '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five',
        '6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten',
        '11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen',
        '16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty',
        '30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy',
        '80' => 'eighty','90' => 'ninty');
		
		//echo $strnum = "2070000"; 
		 $len = strlen($strnum);
		 $numword = "Rupees ";
		while($len!=0)
		{
			if($len>=8 && $len<= 9)
			{
				$val = "";
				
				
				if($len == 9)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 7;
					$strnum =   substr($strnum,2,7);
				}
				if($len== 8)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =7;
					$strnum =   substr($strnum,1,7);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Crore ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
				$numword.=  "Crores ";
				}
				
			}
			if($len>=6 && $len<= 7)
			{
				$val = "";
				
				
				if($len == 7)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 5;
					$strnum =   substr($strnum,2,7);
				}
				if($len== 6)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =5;
					$strnum =   substr($strnum,1,7);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Lakh ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
				$numword.=  "Lakhs ";
				}
				
			}
		
			if($len>=4 && $len<= 5)
			{
				$val = "";
				if($len == 5)
				{
					$value1 = substr($strnum,0,1);
					$value2 = substr($strnum,1,1);
					$value = $value1 *10 + $value2;
					$value1 =$value1*10;
					$val  = $value;
					//$numword.= $words["$value"]." ";
					$len = 3;
					$strnum =   substr($strnum,2,4);
				}
				if($len== 4)
				{
					$value = substr($strnum,0,1);
					$val = $value;
					//$numword.= $words["$value"]." ";
					$len =3;
					$strnum =   substr($strnum,1,3);
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
				}				
				if($val == 1)
				{
					$numword.=  "Thousand ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
					$numword.=  "Thousand ";
				}
			}
			if($len==3)
			{
				$val = "";
				$value = substr($strnum,0,1);

				$val  = $value;
				$numword.= $words["$value"]." ";
				$len = 2;
				$strnum =   substr($strnum,1,2);

				if($val == 1)
				{
					$numword.=  "Hundred ";
				}
				else if($val == 0)
				{
					
				}
				else
				{
					$numword.=  "Hundred ";
				}
			}
			if($len>=1 && $len<= 2)
			{
				if($len ==2)
				{
				$value = substr($strnum,0,1);
				$value = $value *10;
				$value1 = $value;
				$strnum =   substr($strnum,1,1);
				$value2 = substr($strnum,0,1);
				$value =$value1 + $value2;				
				}
				if($len ==1)
				{	
					$value = substr($strnum,0,1);
					
				}
				if($value <=19)
				{
					$numword.= $words["$value"]." ";
					$len =0;
				}
				else
				{
					$numword.= $words["$value1"]." ";
					$numword.= $words["$value2"]." ";
					$len =0;
				}
				$numword.=  "Only ";

			}
			
			break;
		}
		return ucwords(strtolower($numword));

}	
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
}
</style>
<style>
th#t03
{
	border-bottom:1px solid black;
}
th#t04
{
	border: 1px solid black;
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
</head>
<?php
$firstMonth= date("j M", strtotime($BalanceMaster['BalanceMaster']['start_date']));
$firstYear= date("Y", strtotime($BalanceMaster['BalanceMaster']['start_date']));
$lastYear= date("Y", strtotime($BalanceMaster['BalanceMaster']['end_date']));

$setupFee=$PlanDetails['PlanMaster']['SetupCost'];
$planRent=$PlanDetails['PlanMaster']['RentalAmount'];
$period=$firstMonth.' '.$firstYear.' - '.$lastYear;
$total=($setupFee+$planRent);
$serviceTex=$total*15/100;
$grandTotal=($total+$serviceTex);

?>
<body style="font-size:12px;  font-family:Arial, Helvetica, sans-serif;">
    <div>
        <center>
            <?php echo $this->Html->image('logo.jpg', array('fullBase' => true,'style'=>'width:140px;margin-top:-30px;'));?><br/> 
            <span style="font-size: 9px;font-weight: bold;">A Unit of Ispark Dataconnect Pvt Ltd</span>
        </center>
    </div>
    <br/><br/>

    <table width="544" border ="1" cellpadding="2" cellspacing="0">
	<tr>
            <td colspan="2">Bill to Address</td>
            <td width="130">Ship to Address</td>
            <td width="130">Date</td>
            <td width="130"><?php 
            $date=date_create($BalanceMaster['BalanceMaster']['start_date']);
            echo date_format($date,"d-M-Y"); ?></td>
	</tr>

	<tr>
            <td colspan="2"  valign="top">
                <b><?php echo $ClientInfo['RegistrationMaster']['company_name'];?></b><br>
                <?php echo $ClientInfo['RegistrationMaster']['reg_office_address1'];?><br>
                Pin Code : <?php echo $ClientInfo['RegistrationMaster']['pincode'];?><br>
                Phone No : <?php echo $ClientInfo['RegistrationMaster']['phone_no'];?><br>
            </td>
            
            <td valign="top">
                <b><?php echo $ClientInfo['RegistrationMaster']['company_name'];?></b><br>
                <?php echo $ClientInfo['RegistrationMaster']['reg_office_address1'];?><br>
                Pin Code : <?php echo $ClientInfo['RegistrationMaster']['pincode'];?><br>
                Phone No : <?php echo $ClientInfo['RegistrationMaster']['phone_no'];?><br>
            </td>
            </td>
            
            <td  valign="top">
                <table width="130" cellpadding="0" cellspacing="0">
                    <tr><td width="130" id = "t07">Bill No</td></tr>
                    <tr><td id = "t06">Pan Based Service Tax No</td></tr>
                    <tr><td id = "t06">Service Tax Category</td></tr>
                    <tr><td id = "t06">Pan No</td></tr>
              </table>
            </td>
            
            <td height="100" valign="top">
                <table cellpadding="0" cellspacing="0" width="130">
                    <tr><td width="130"  id = "t07"><?php echo $BalanceMaster['BalanceMaster']['Id'];?></td></tr>
                    <tr><td id = "t06">AAFCM4591GST001&nbsp;</td></tr>
                    <tr><td id = "t06">Business Auxillary Services</td></tr>
                    <tr><td id = "t06">AAFCM4591G&nbsp;</td></tr>
                </table>
            </td>
	</tr>

	<tr>
            <td colspan="5"  valign = "top" style = "height:400">
                <table width="540" height = "400" cellpadding="0" cellspacing="0" >
                    <tr>
                        <th width = "20"  id="t01">S.No</th>
                        <th width = "202" id="t01">Particulars </th>
                        <th width = "48"  id="t01">Qty</th>
                        <th width = "60"  id="t01">Rate</th>
                        <th width = "110" id="t03">Amount</th>
                    </tr>
                    
                    <tr>
                        <td align="center" valign="top" id = "t02">1</td>
                        <td align="center" valign="top" id = "t02">Setup Fees</td>
                        <td align="center" valign="top" id = "t02">1</td>
                        <td align="center" valign="top" id = "t02"><?php echo $setupFee;?></td>
                        <td align="center" valign="top"><?php echo $setupFee;?></td>
                    </tr>
                    
                    <tr>
                        <td align="center" valign="top" id = "t02">2</td>
                        <td align="center" valign="top" id = "t02">Plan rent <br/>for the period<br/>(<?php echo $period;?>)</td>
                        <td align="center" valign="top" id = "t02">1</td>
                        <td align="center" valign="top" id = "t02"><?php echo $planRent;?></td>
                        <td align="center" valign="top"><?php echo $planRent;?></td>
                    </tr>
                    
                    <tr>
                        <td height = "200" id = "t02"></td>
                        <td id = "t02"></td>
                        <td id = "t02"></td>
                        <td id = "t02"></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td rowspan="4" id = "t02"></td>
                        <td rowspan="4"></td>
                        <td ></td>
                        <td ></td>
                        <td></td>
                    </tr>
                    

                    <tr>
                        <th colspan="2" id ="t04">Total</th>
                        <th id ="t05"><?php echo $total;?></th>
                    </tr>

                    <tr>
                        <th colspan="2" id ="t04">Service Tax @ 15%</th>
                        <th id ="t05"><?php echo $serviceTex;?></th>
                    </tr>

                    <tr>
                        <th colspan="2" id ="t04">G. Total</th>
                        <th id ="t05"><?php echo $grandTotal;?></th>
                    </tr>
                    
                    <tr>
                        <td colspan="4" id ="t06"><b><i>Amount In Words : <?php 
                        echo ucwords(convertNumberToWordsForIndia(round($grandTotal)));?></i></b></td>
                        <th id ="t05"></th>
                    </tr>

                    <tr>
                        <td colspan="5" id="t06">
                        <br>
                            Note : Please issue Ch/DD in favour of <br>
                            SBI A/c. ISPARK Dataconnect Pvt. Ltd. 
                            Payable at Delhi
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" valign="top">
                            <b>Covered under MSME Act vide letter No : F/5/CL/EM/2012/2062 dated 19.12.12 <br>
                            Enterpreneurs Memorandum No. : '070092201354'</b>
                        </td>

                        <td>
                            <b> for Ispark Dataconnect Pvt Ltd</b> 
                            <br><br><br><br><br>
                            <b>Authorised Signatory</b>
                        </td>
                    </tr>
                </table>	
            </td>		
	</tr>	
    </table>
  
    
    <table width="544" border ="1" cellpadding="2" cellspacing="0" style="margin-top:200px;"  >
        <tr><th>Plan Details</th><th>Value</th></tr>
        <tr><td>Plan Name</td><td><?php echo $PlanDetails['PlanMaster']['PlanName']; ?></td></tr>
        <tr><td>Setup Fees</td><td><?php echo $PlanDetails['PlanMaster']['SetupCost']; ?></td></tr>
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