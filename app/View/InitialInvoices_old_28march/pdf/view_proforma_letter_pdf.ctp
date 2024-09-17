<style>
.hideextra { white-space: nowrap; overflow: hidden; text-overflow:ellipsis; }
</style>
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
<body style="font-size:12px;  font-family:Arial, Helvetica, sans-serif;">
    <?php if($cost_master['CostCenterMaster']['company_name']=='IDC') {echo "<br/><br/><br/><br/><br/><br/><br/><br/>";} ?>
   
<br/>    
<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) {} else { ?> 

<br/>
<?php } ?>	
<table cellpadding="0" cellspacing="0">
    
	<tr>
		<td>
			<table>
			<tr>
				<td width="300" style="font-size:25px; font-family:Arial, Helvetica, sans-serif;">&nbsp;
				<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30") ) { ?> 
                                    <br/><div style="font-size:8px; font-family:Arial, Helvetica, sans-serif;">Branch Add: <?php echo wordwrap($branch_detail['Addbranch']['branch_address'],60,"<br>\n"); ?></div>
                                   <?php }  ?>
				</td>

				<td align="right">
					<?php //echo $this->Html->image('MasLogo.jpg', array('fullBase' => true,'height'=>60));?>
				</td>
			</tr>
			</table>
		</td>	
	</tr>
</table>
<br/>
<div style="margin-top:-30px;" align="center" ><b>Proforma Invoice</b></div>    
<table width="544" border ="1" cellpadding="2" cellspacing="0">
	<tr>
		<td colspan="2">Bill to Address</td>
		<td width="130">Ship to Address</td>
		<td width="130">Date</td>
		<td width="130"><?php 
		$date=date_create($tbl_invoice['InitialInvoice']['invoiceDate']);
		echo date_format($date,"d-M-Y"); ?></td>
	</tr>

	<tr>
		<td colspan="2"  valign="top">
			<?php if($cost_master['CostCenterMaster']['as_client']) echo $btr = '<b>'.$cost_master['CostCenterMaster']['client'].'</b><br>'; ?>
			<?php if(!$cost_master['CostCenterMaster']['as_client']) echo $btr ='<b>'.$cost_master['CostCenterMaster']['bill_to'].'</b><br>'; ?>
			<?php echo $cost_master['CostCenterMaster']['b_Address1']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['b_Address2']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['b_Address3']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['b_Address4']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['b_Address5']; ?><br/>
                        <?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
                        <font size="1">GST No :<?php echo $cost_master['CostCenterMaster']['VendorGSTNo']; ?>
                        <br/>GST STATE NAME :<?php echo $cost_master['CostCenterMaster']['VendorGSTState'];?>
                        <br/>GST STATE CODE :<?php echo $cost_master['CostCenterMaster']['VendorStateCode'];?>
                        </font>
                        <?php } ?>
                        
		</td>
		<td valign="top">
			<?php if($cost_master['CostCenterMaster']['as_bill_to']) echo $btr; ?>
			<?php if(!$cost_master['CostCenterMaster']['as_bill_to']) echo '<b>'.$cost_master['CostCenterMaster']['ship_to'].'</b><br>'; ?>
			<?php echo $cost_master['CostCenterMaster']['a_address1']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['a_address2']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['a_address3']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['a_address4']; ?><br/>
			<?php echo $cost_master['CostCenterMaster']['a_address5']; ?><br/>
                        <?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
                        <font size="1">GST No :<?php echo $cost_master['CostCenterMaster']['VendorGSTNo']; ?>
                        <br/>GST STATE NAME :<?php echo $cost_master['CostCenterMaster']['VendorGSTState'];?>
                        <br/>GST STATE CODE :<?php echo $cost_master['CostCenterMaster']['VendorStateCode'];?>
                        </font>
                        <?php } ?>
			<?php unset($CostCenterMaster); ?>
		</td>
		<td  valign="top">
			<table width="130" cellpadding="0" cellspacing="0">
					<tr><td width="130" id = "t07">Proforma No</td></tr>
					
					<tr><td><b>PO No</b></td></tr>
					<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
                                        <tr><td id = "t06">GST No</td></tr>
					<tr><td id = "t06">HSN Code</td></tr>
                                        <tr><td id = "t06">SAC Code</td></tr>
                                        <?php } else { ?>
					<tr><td id = "t06">Pan Based Service Tax No</td></tr>
                                        <?php } ?>
					
					<tr><td id = "t06">Pan No</td></tr>
					<tr><td id = "t06"><b>GRN No</b></td></tr>
                                        <?php //if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
                                        <tr><td id = "t06"><b>Place Of Supply</b></td></tr>
                                        <tr><td id = "t06"><b>TAX PAYBLE UNDER RCM</b></td></tr>
                                        <?php //} ?>
		  </table>
		</td>
		<td height="100" valign="top">
			<table cellpadding="0" cellspacing="0" width="130">
					<tr><td width="130"  id = "t07"><?php echo $tbl_invoice['InitialInvoice']['proforma_bill_no']; ?>&nbsp;</td></tr>

					
                                        <tr><td id = "t06"><font size="10px"><b><?php if($tbl_invoice['InitialInvoice']['approve_po']=='Yes')echo $tbl_invoice['InitialInvoice']['po_no']; ?>&nbsp;</b></font></td></tr>
					<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
                                       <tr><td id = "t06"><?php  echo $cost_master['CostCenterMaster']['ServiceTaxNo'];?>&nbsp;</td></tr>
					<tr><td id = "t06"><?php  echo $cost_master['CostCenterMaster']['HSNCode'];?>&nbsp;</td></tr>
                                        <tr><td id = "t06"><?php  echo $cost_master['CostCenterMaster']['SACCode'];?>&nbsp;</td></tr>
                                        <?php } else { ?>
					<tr><td id = "t06"><?php  echo $company['Addcompany']['service_no'];?>&nbsp;</td></tr>
                                        <?php } ?>
					
					<tr><td id = "t06"><?php echo $company['Addcompany']['pan_no'];?>&nbsp;</td></tr>
					<tr><td id = "t06"><b><?php if($tbl_invoice['InitialInvoice']['approve_grn']=='Yes')echo $tbl_invoice['InitialInvoice']['grn']; ?>&nbsp;</b></td></tr>
                                        <?php //if( strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
                                        <tr><td width="130"  id = "t07"><?php if(empty($cost_master['CostCenterMaster']['statenamecost'])) { echo $branch_detail['Addbranch']['state']; } else {echo $cost_master['CostCenterMaster']['statenamecost'];} ?>&nbsp;</td></tr>
                                        <tr><td width="130"  id = "t07">NO&nbsp;</td></tr>
                                         <?php //} ?>
		  </table>

		</td>
	</tr>

	<tr>
	<td colspan="5"  valign = "top" style = "height:100px">
	<table width="540" height = "100" cellpadding="0" cellspacing="0" >
		<tr>
		<th width = "20"  id="t01">S.No</th>
		<th width = "202" id="t01">Particulars</th>
		<th width = "48"  id="t01">Qty</th>
		<th width = "60"  id="t01">Rate</th>
		<th width = "110" id="t03">Amount</th>
		</tr>

		<?php
			$i=1; 
			foreach($inv_particulars as $post) :
			?>
				<tr>
				<td align="center" valign="top" id = "t02" <?php if(count($inv_particulars)>20) { echo 'style="font-size:8px;"'; } ?>><?php echo "<br>".$i++."."; ?></td>
				<td align="center" valign="top" id = "t02" <?php if(count($inv_particulars)>20) { echo 'style="font-size:8px;"'; } ?>><?php echo "<br>".$post['Particular']['particulars']; ?></td>
				<td align="center" valign="top" id = "t02" <?php if(count($inv_particulars)>20) { echo 'style="font-size:8px;"'; } ?>><?php echo "<br>".round($post['Particular']['qty'],6); ?></td>
				<td align="center" valign="top" id = "t02" <?php if(count($inv_particulars)>20) { echo 'style="font-size:8px;"'; } ?>><?php echo "<br>".round($post['Particular']['rate'],6); ?></td>
				<td align="center" valign="top" <?php if(count($inv_particulars)>20) { echo 'style="font-size:8px;"'; } ?>><?php echo "<br>".round($post['Particular']['amount'],2); ?></td>
				</tr>
			<?php endforeach; ?>	
			<?php  if(isset($inv_deduct_particulars['0'])) { ?>
				<tr>
				<td align="center" valign="top" id = "t02"></td>
				<th align="center" valign="top" id = "t04">Less</th>
				<td align="center" valign="top" id = "t02"></td>
				<td align="center" valign="top" id = "t02"></td>
				<td align="center" valign="top" id = "t02"></td>
				</tr>
			<?php } ?>
			<?php $j = 1;
			foreach($inv_deduct_particulars as $post) :
			?>
				<tr>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$j++."."; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['DeductParticular']['particulars']; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['DeductParticular']['qty']; ?></td>
				<td align="center" valign="top" id = "t02"><?php echo "<br>".$post['DeductParticular']['rate']; ?></td>
				<td align="center" valign="top"><?php echo "<br>".round($post['DeductParticular']['amount'],2); ?></td>
				</tr>
			<?php endforeach; ?>	

		<tr>
			<td height = "<?php echo 20*(10-$i-$j); ?>" id = "t02"></td>
			<td id = "t02"></td>
			<td id = "t02"></td>
			<td id = "t02"></td>
			<td></td>
		</tr>	
		<tr>
                        <td rowspan="<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) {if($cost_master['CostCenterMaster']['GSTType']=='Integrated') echo "4"; else echo "5";  }  else if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14")) {if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")) echo "6"; else echo "5"; } else echo "4"; ?>" id = "t02"></td>
                        <td rowspan="<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) {if($cost_master['CostCenterMaster']['GSTType']=='Integrated') echo "4"; else echo "5";  }  else if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))  {if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")) echo "6";  else echo "5";} else echo "4"; ?>"></td>
			<td ></td>
			<td ></td>
			<td></td>
		</tr>

	<tr>
		<th colspan="2" id ="t04">TAXABLE INVOICE VALUE</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['total']); ?></th>
	</tr>
    <?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { if($cost_master['CostCenterMaster']['GSTType']=='Integrated') {  ?>
        <tr>
		<th colspan="2" id ="t04">IGST @ 18%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['igst']); ?></th>
	</tr>
    <?php } else { ?>    
        <tr>
		<th colspan="2" id ="t04">CGST @ 9%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['cgst']); ?></th>
	</tr>
        <tr>
		<th colspan="2" id ="t04">SGST @ 9%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['sgst']); ?></th>
	</tr>
    <?php }} else { ?>
	<tr>
		<th colspan="2" id ="t04">Service Tax @ 14%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['tax']); ?></th>
	</tr>

	<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14")) { ?>	
	<tr>
		<th colspan="2" id ="t04">SBC @ 0.5%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['sbctax']); ?></th>
	</tr>
	<?php } ?>
        <?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2016-05-31")) { ?>	
	<tr>
		<th colspan="2" id ="t04">KKC @ 0.5%</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['krishi_tax']); ?></th>
	</tr>
    <?php }}  ?>
	<tr>
		<th colspan="2" id ="t04">G. Total</th>
		<th id ="t05"><?php echo round($tbl_invoice['InitialInvoice']['grnd']); ?></th>
	</tr>
	<tr>
		<td colspan="4" id ="t06"><b><i>Amount In Words : <?php 
		echo ucwords(convertNumberToWordsForIndia(round($tbl_invoice['InitialInvoice']['grnd'])));?></i></b></td>
		<th id ="t05"></th>
	</tr>

	<tr>
		<td colspan="5" id="t06">
		<br>
			Note : Please issue Ch/DD in favour of <br>
			<?php if($cost_master['CostCenterMaster']['company_name']=='Mas Callnet India Pvt Ltd')
                                        {echo "SBI A/c. MAS Callnet India Pvt. Ltd. Payable at Delhi";}
                                        else if($cost_master['CostCenterMaster']['company_name']=='IDC')
                                        {echo "SBI A/c. ISPARK Dataconnect Pvt. Ltd. Payable at Delhi";}
                                        else {echo "Pikquick Services Pvt. Ltd.";}
                                ?> 
		</td>
	</tr>
        
	<tr>
		<td colspan="4" valign="top">
                    <?php if($cost_master['CostCenterMaster']['company_name']!='Pikquick Pvt. Ltd.') { ?>
		<b>Covered under MSME Act vide letter No : F/5/CL/EM/2012/2062 dated 19.12.12 <br>
		Enterpreneurs Memorandum No. : '070092201354'</b>
                    <?php } else { ?>
                    <table border="1">
                        <tr>
                            <th>Bank Details</th>
                            <th>Pikquick Services Pvt. Ltd.</th>
                        </tr>
                        <tr>
                            <th>Bank Name</th>
                            <td>ICICI BANK</td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td>GK-1 ND - 48</td>
                        </tr>
                        <tr>
                            <td>ACCOUNT NO.</td>
                            <td>002905016647</td>
                        </tr>
                        <tr>
                            <td>IFSC CODE</td>
                            <td>ICIC0000029</td>
                        </tr>
                        
                    </table>
                    <?php }  ?>
		</td>
		<td <?php if($tbl_invoice['InitialInvoice']['bill_no'] == 'abc'){ ?> style="background-image:url(<?php echo $this->webroot; ?>app/webroot/img/sign4.png);background-repeat: no-repeat; <?php } ?>">
		<b>for <?php    
                                
                                if($cost_master['CostCenterMaster']['company_name']=='Mas Callnet India Pvt Ltd')
                                {
                                    echo "MAS Callnet India Pvt. Ltd.";
                                }
                                else if($cost_master['CostCenterMaster']['company_name']=='IDC')
                                {
                                    echo "ISPARK Dataconnect Pvt. Ltd.";
                                }
                                else {echo "Pikquick Services Pvt. Ltd.";}
                                ?> </b>
		<br>
		<br>
		<br>
		<br>
		<br>
		<b>Authorised Signatory</b>
		</td>
	</tr>
        <tr>
            <?php if($cost_master['CostCenterMaster']['company_name']=='Mas Callnet India Pvt Ltd') { ?>
            <td width="103" style="font-family: arial;size: 12;font-weight: bold;padding-top: 10px;padding-bottom:10px;" colspan="5" >
			TDS for this invoice to be deducted @ 2%  "Under section 4th Provision to section 194J(1) of the Income Tax Act, 1961. The Finance Act, 2017 " for the  payee engaged only in the business of operation of call centre.
		</td>
            <?php } ?>
        </tr>
		</table>	
	</td>		
	</tr>	

</table>
<?php if(strtotime($tbl_invoice['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30")) { ?>
<?php if($cost_master['CostCenterMaster']['company_name']=='Mas Callnet India Pvt Ltd') { ?>
<br/>
<br/>
<br/>
<br/>
<br/>


<table>
	<tr>
		<td width="227">
			  <b> Registered Office:</b> 
			
		</td>
		<td width="103">
			
		</td>
		<td width="94">

		</td>
		<td width="93">

		</td>

	</tr>
	
	
</table>
<?php } ?>
<?php }  ?>
</body>
</html>