<?php
$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password="dial@mas123"; // Mysql password 
$db_name="db_bill"; // Database name 
// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");



//echo $newmascode;die;
  $query =  "select qs.*,qa.Present,qa.WO,qa.Holiday from qual_salary qs left join qual_attendance qa on qs.EmpCode=qa.EmpCode and qs.SalMonth=qa.SalMonth and qs.SalYear=qa.SalYear  where qs.SalMonth = '$Month' and qs.SalYear = '$Year' and qs.EmpCode ='$track' limit 1"; 
$sel = mysql_query($query);

?> 


<style>
table{
    width:800px;
}
table tr th{
  border-top: thin solid;
  border-color: black;
  background-color:#F8F8F8;
  padding:0 11px 0 11px;
  font-size: 11px;
}
table tr td{
  font-size: 10px; 
}
table tr th{
  border-bottom: thin solid;
  border-color: black;
}
.left {
  border-left: thin solid;
  border-color: black;
}
.right {
  border-right: thin solid;
  border-color: black;
}
.dotline{
    border-top: 1px dashed black;
    border-color: black;
    margin-top:10px;
    width: 740px;
}    
.alc{
    text-align:center;
}

</style>
<style>
table{
    width:800px;
}
table tr th{
  border-top: thin solid;
  border-color: black;
  background-color:#F8F8F8;
  padding:0 11px 0 11px;
  font-size: 11px;
}
table tr td{
  font-size: 10px; 
}
table tr th{
  border-bottom: thin solid;
  border-color: black;
}
.left {
  border-left: thin solid;
  border-color: black;
}
.right {
  border-right: thin solid;
  border-color: black;
}
.dotline{
    border-top: 1px dashed black;
    border-color: black;
    margin-top:10px;
    width: 740px;
}    
.alc{
    text-align:center;
}

</style>
<?php while ($data = mysql_fetch_array($sel)) {
   // print_r($data);die;

?>


<div style="width: 800px;">
    <div>
        <img src="/var/www/html/ispark/app/webroot/html2pdf/examples/res/maslogo.png" style="width:150px;" >
        <span style="float:right;font-size: 16px;font-weight: bold;margin-left:100px;" >Mas Callnet India Pvt. Ltd</span>
    </div>
    <div style="font-size: 9px;font-weight: bold;margin-left:275px;" >Pay Slip for the Month of <?php echo $Month.','.$Year; ?></div>
    <table cellspacing="0" cellpadding="0" >
        <tr>
          <th class="left " >Employee Particulars</th>
          <th  colspan="3"><---Days---></th>
            <th >Earnings</th>
            <th >Basic Rate</th>
            <th >Amount</th>
            
            <th >Deductions</th>
            <th >Amount</th>
            <th class="right" ></th>
        </tr>
        <tr>
            <td>
                <table>
                    <tr><td>Emp.Cd.:<?php echo $data['EmpCode']; ?></td></tr>
                    <tr><td>Name: <?php echo $data['EmapName']; ?></td></tr>
                    <tr><td>F/H Name: <?php echo $data['FatherName']; ?></td></tr>
                    <tr><td>Dept: <?php echo $data['Dept']; ?></td></tr>
                    <tr> <td>Desg: <?php echo $data['Desg']; ?></td></tr>
                    <tr><td>PF No: <?php echo $data['PFNo']; ?></td></tr>
                    <tr><td>ESI: <?php echo $data['ESINo']; ?></td></tr>
                    <tr><td>PAN#: <?php echo $data['PAN']; ?></td></tr>
                    <tr><td>UAN#: <?php echo $data['UAN']; ?></td></tr>
                    <tr><td>A/C No: <?php echo $data['Acno']; ?></td></tr>
                    <tr> <td>Bank:<?php echo $data['Bank']; ?></td></tr>
                    
                    
                </table>
            </td>
            <td></td>
            
            <td>
                <table>
           <tr><td> WD</td></tr>
           <?php if($data['WO']!='0'  ) { ?> <tr><td>WO</td></tr> <?php } ?>
           <?php if($data['CL']!='0'  ) { ?><tr><td> CL</td></tr> <?php } ?>
           <?php if($data['SL']!='0'  ) { ?> <tr><td>SL</td></tr> <?php } ?>
           <?php if($data['Holiday']!='0') { ?><tr><td> H</td></tr> <?php } ?>
            <?php if($data['PL']!='0') { ?><tr><td>PL</td></tr><?php } ?>
            <tr><td>PD</td></tr>
                </table>
            </td>
            
            <td>
                <table>
                    <tr><td class="alc"><?php echo $data['Present']; ?></td></tr>
                     <?php if($data['WO']!='0'  ) { ?><tr><td class="alc"><?php echo $data['WO']; ?></td></tr> <?php } ?>
                    <?php if($data['CL']!='0'  ) { ?><tr><td><?php  echo $data['CL']; ?></td></tr><?php } ?>
                    <?php if($data['SL']!='0'  ) { ?><tr><td class="alc"> <?php echo $data['SL'];?></td></tr><?php } ?>
                   <?php if($data['Holiday']!='0') { ?> <tr><td class="alc"><?php  echo $data['Holiday'];?></td></tr><?php } ?>
                    <?php if($data['PL']!='0') { ?><tr><td class="alc"><?php echo $data['PL']; ?></td></tr><?php } ?>
                    <tr><td class="alc"><?php echo $data['Paiddays']; ?></td></tr>
                   
                    
                </table>  
            </td>
            
            
            <td style="text-align:left;" >
              <table  >
                  <tr ><td> <span style="margin-left:6px;" >Basic</span></td></tr>
                  <tr><td  ><span style="margin-left:6px;" >HRA</span></td></tr> 
           <?php if($data['Conv']!='0'  ) { ?><tr><td><span style="margin-left:5px;" > Convence</span></td></tr> <?php } ?>
           <?php if($data['overtime']!='0'  ) { ?> <tr><td><span style="margin-left:6px;" >OT INC</span></td></tr> <?php } ?>
           <?php if($data['Incentive']!='0') { ?><tr><td><span style="margin-left:6px;" > INC</span></td></tr> <?php } ?>
            <?php if($data['EOthAllw']!='0') { ?><tr><td><span style="margin-left:6px;" >Other All</span></td></tr><?php } ?>
           <?php if($data['ArrLM']!='0') { ?> <tr><td><span style="margin-left:6px;" >ArrLm</span></td></tr><?php } ?>
                </table>  
            </td>
            
            <td style="text-align:right;">
                <table>
                    <tr><td> <span style="margin-left:35px;" ><?php echo $data['Basic']; ?></span></td></tr>
            <tr><td><?php echo $data['HRA']; ?></td></tr> 
           <?php if($data['Conv']!='0'  ) { ?><tr><td> <?php echo $data['Conv']; ?></td></tr> <?php } ?>
           <?php if($data['overtime']!='0'  ) { ?> <tr><td>0.00</td></tr> <?php } ?>
           <?php if($data['Incentive']!='0') { ?><tr><td>0.00</td></tr> <?php } ?>
            <?php if($data['EOthAllw']!='0') { ?><tr><td><?php  echo $data['EOthAllw']; ?></td></tr><?php } ?>
           <?php if($data['ArrLM']!='0') { ?> <tr><td>0.00</td></tr><?php } ?>
                </table> 
            </td>
            <td style="text-align:right;">
              <table>
                  <tr><td> <span style="margin-left:21px;" ><?php echo $data['EBasic']; ?></span></td></tr>
            <tr><td><?php echo $data['EHRA']; ?></td></tr> 
           <?php if($data['Conv']!='0'  ) { ?><tr><td> <?php echo $data['EConv']; ?></td></tr> <?php } ?>
           <?php if($data['overtime']!='0'  ) { ?> <tr><td><?php  echo $data['overtime']; ?></td></tr> <?php } ?>
           <?php if($data['Incentive']!='0') { ?><tr><td> <?php  echo $data['Incentive']; ?></td></tr> <?php } ?>
            <?php if($data['EOthAllw']!='0') { ?><tr><td><?php  echo $data['EOthAllw']; ?></td></tr><?php } ?>
           <?php if($data['ArrLM']!='0') { ?> <tr><td><?php  echo $data['ArrLM']; ?></td></tr><?php } ?>
                </table>  
            </td>
            <td><table>
                    <tr><td ><span style="margin-left:6px;" >EPF@12.00%</span></td></tr>
            <?php if($data['ESI']!='0'  ) { ?><tr><td><span style="margin-left:6px;">ESI@1.75%</span></td></tr> <?php } ?>
           <?php if($data['TDS']!='0'  ) { ?><tr><td><span style="margin-left:6px;" >TDS</span></td></tr> <?php } ?>
           <?php if($data['AdvDed']!='0'  ) { ?> <tr><td><span style="margin-left:6px;">Adv</span></td></tr> <?php } ?>
           <tr><td> </td></tr> 
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td><span style="margin-left:6px;">Emplr.EPS</span></td></tr>
            <tr><td><span style="margin-left:6px;">Emplr.FPS</span></td></tr>
             <?php if($data['EmplrESI']!='0' ) { ?><tr><td><span style="margin-left:6px;">Emplr.ESI</span></td></tr><?php } ?>
             <tr><td><span style="margin-left:6px;">C.EPF Sal</span></td></tr>
             <tr><td><span style="margin-left:6px;">C.FPS Sal</span></td></tr>
                </table></td>
            <td style="text-align:right;"><table>
                    <tr><td style="text-align:right;"><span style="margin-left:25px;" ><?php echo $data['PF']; ?></span></td></tr>
             <?php if($data['ESI']!='0'  ) { ?><tr><td> <?php echo $data['ESI']; ?></td></tr> <?php } ?>
             <?php if($data['TDS']!='0'  ) { ?><tr><td> <?php echo $data['TDS']; ?></td></tr> <?php } ?>
             <?php if($data['AdvDed']!='0'  ) { ?><tr><td> <?php echo $data['AdvDed']; ?></td></tr> <?php } ?>
           <tr><td> </td></tr> 
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr> <td ><?php echo ceil(($data['EBasic']*3.67)/100); ?> </td></tr> 
            <tr><td ><?php echo ROUND(($data['EBasic']*8.33)/100); ?></td></tr>
            <?php if($data['EmplrESI']!='0' ) { ?><tr><td ><?php echo $data['EmplrESI']; ?></td></tr> <?php } ?>
            <tr><td ><?php echo $data['EBasic']; ?></td></tr>
            <tr><td><?php echo $data['EBasic']; ?></td></tr>
                </table></td>
            
            

        </tr>
 
        <tr>
            <th class="left" colspan="4"></th>
            <th>Total</th>
            <th><span style="margin-left:25px;" ><?php echo $data['Gross']; ?></span></th>
            <th><span style="margin-left:12px;" ><?php echo $data['TotalGross']; ?></span></th>
            <th><span style="margin-left:10px;" >Total</span></th>
            <th ><span style="margin-left:16px;" ><?php echo ($data['PF']+$data['ESI']+$data['AdvDed']); ?></span></th>
            <th class="right" >Net: <?php echo $data['Netpay']; ?></th>
        </tr>
    </table>
    <div style="font-size: 13px;margin-left:672px;font-style: italic;text-decoration: underline;" ></div>
    <div style="font-size: 10px;margin-top:10px;margin-left: 200px;" >This is a computer generated statement, hence not signature required</div>
    <div class="dotline" ></div>
</div>



<?php } ?>