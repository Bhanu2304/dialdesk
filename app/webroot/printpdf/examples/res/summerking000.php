<?php
$Id         =   base64_decode($_GET['SKID']);
$ClientId   =   base64_decode($_GET['SKCD']);

$con = mysql_connect("localhost","root","dial@mas123","false",128);
$db  = mysql_select_db("db_dialdesk",$con);

$rsc = mysql_query("SELECT * FROM call_master WHERE Id='$Id' AND ClientId='$ClientId'");
$data=  mysql_fetch_array($rsc); 
?>
<html>
<head>
<style>
.logo{
    font-size: 20px;
    font-weight: bold;
    color:red;
    position: relative;
    left: 40px;
    top:10px;
}
.headtitle{
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #024457;
}
.invoicebox{
    width:100%;
    height:auto;
    position: relative;
    left:30px;
    border:0px solid gray;
}
table, th, td {
    width:81px;
    border: 1px solid gray;
    border-collapse: collapse;
    font-size: 11px;
    padding:6px;
}

.alg-cnt{text-align: center;}

.box{
    border: 1px solid black;
    width:15px;
    height:15px;
}

.headbox{
    border: 1px solid black;
    width:197px;
    height:70px;
    text-align: center;
    position:relative;
    left:482px;
    top:-80px;
}
</style>
</head>
<body>
    <div class="invoicebox">
        <div style="margin-left:5px;" >
            <div><img src="http://dialdesk.co.in/dialdesk/app/webroot/printpdf/examples/res/summerking.jpg" style="width:200px;height:60px;" ></div>
            <div style="font-weight:bold;" >
                Customer Care No : 8742920920<br/>
                E-mail : Service@summerkingindia.com<br/>
                Web : www.summerkingindia.com 
            </div>
            
            <div style="position:relative;left:265px;top:-50px;" ><h3>SUMMERKING INDIA</h3></div>
            <div class="headbox"><b>Authorised Service Franchise</b></div>
        </div>
       
        <table >
            <tr>
                <td colspan="3"><b>Complaint No - </b> <?php echo isset($data['SrNo'])?$data['SrNo']:'';?></td>
                <td colspan="3"><b>Product :</b> <?php echo isset($data['Field19'])?$data['Field19']:'';?></td> 
            </tr>
            <tr>
                <td colspan="3"><b>Name Of The Customer :</b> <?php echo isset($data['Field15'])?$data['Field15']:'';?></td>
                <td colspan="3"> <b>Product Sub - Detail :</b></td> 
            </tr>
            <tr>
                <td rowspan="4" colspan="3" ><b>Address :</b><?php echo isset($data['Field16'])?$data['Field16']:'';?> <?php echo isset($data['Field17'])?$data['Field17']:'';?></td>
                <td colspan="3"><b>Quantity :</b></td>
            </tr>
            <tr><td colspan="3"><b>Dealer Name :</b></td></tr>
            <tr><td colspan="3"><b>Bill No :</b> <span style="margin-left:110px;" ><b>Date :</b></span></td></tr>
            <tr><td colspan="3"><b>Warranty/Out Warranty :</b> <?php echo isset($data['Field23'])?$data['Field23']:'';?></td> </tr>
            
            <tr>
                <td colspan="3"><b>Pin Code :</b> <?php echo $data['GSTStateCode'];?></td>
                <td colspan="3">
                    <div class="box"></div>
                    <div style=" position: relative;left:30px;top:-15px;font-weight: bold;">Local</div>
                    <div class="box" style=" position: relative;left:160px;top:-15px;"></div>
                    <div style=" position: relative;left:190px;top:-15px;font-weight: bold;">Up Country</div>
                </td> 
            </tr>
            
            <tr>
                <td colspan="3" ><b>Mobile No :</b> <?php echo isset($data['MSISDN'])?$data['MSISDN']:'';?></td>
                <td colspan="3" class="alg-cnt"><?php echo $data['PanNo'];?></td> 
            </tr>
            
            <tr>
                <td><b>Date In :</b></td>
                <td><b>Time In :</b></td>
                <td><b>Time Out :</b></td>
                <td><b>Date In :</b></td>
                <td><b>Time In :</b></td>
                <td><b>Time Out :</b></td>
            </tr>
            
            <tr><td colspan="6" style="text-align:center;font-weight: bold;" >PRODUCT DETAILS</td></tr>
            
            <tr>
                <td colspan="2"></td>
                <td style="text-align: center;" ><b>In Warranty</b></td>
                <td style="text-align:center;"><b>Out Warranty</b></td>
                <td style="text-align:center;"><b>Spare</b></td>
                <td style="text-align:center;"><b>Rate</b></td>
            </tr>

            <tr>
                <td colspan="2" ><b>Model Name</b> </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Serial/Batch No</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Observation</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Cause</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Action Taken</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Service Charge</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Total</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6" >
                    <div><b>Call Status/Remarks:</b><?php echo isset($data['Field26'])?$data['Field26']:'';?></div>
                    <div style="margin-left: 540px;"><b>Service Agent Signature</b></div>
                </td>
            </tr>
            <tr>
                <td colspan="6" >
                    <div><b>Customer Remarks:</b></div>
                    <div style="margin-top:5px;" >
                        <b>Satisfaction:</b>
                        <div class="box" style=" position: relative;left:2px;"></div>
                        <div style=" position: relative;left:90px;top:-15px;font-weight: bold;">Excellent</div>
                        <div class="box" style=" position: relative;left:145px;top:-15px;"></div>
                        <div style=" position: relative;left:168px;top:-15px;font-weight: bold;">Very Good</div>
                        <div class="box" style=" position: relative;left:230;top:-15px;"></div>
                        <div style=" position: relative;left:255px;top:-15px;font-weight: bold;">Good</div>
                        <div class="box" style=" position: relative;left:290px;top:-15px;"></div>
                        <div style=" position: relative;left:315px;top:-15px;font-weight: bold;">Average</div>
                        <div class="box" style=" position: relative;left:365;top:-15px;"></div>
                        <div style=" position: relative;left:390;top:-15px;font-weight: bold;">Poor</div>
                    </div>
                    <div style=" position: relative;left:540px;top:-15px;font-weight: bold;">Customer Signature</div>
                </td>
            </tr>
            
            <tr>
                <td colspan="6" >
                    <div>
                        <span><b>Complaint No:</b> <?php echo isset($data['SrNo'])?$data['SrNo']:'';?></span>
                        <span style="margin-left:150px;" ><b>Temporary Cash/Cheque Receipt</b></span><br/>
                        <span><b>Received with Thanks the sum of Rs. ............................(Rs. .........................................................................)towards Service charges.</b></span>
                    </div>
                    <div style=" position: relative;left:540px;top:10px;font-weight: bold;">Service Agent Signature</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
