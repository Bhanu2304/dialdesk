<?php
/*
$Id=$_GET['SKCID'];
$Id=base64_decode($_GET['SKCID']);
$con = mysql_connect("localhost","root","mas@1234","false",128);
$db  = mysql_select_db("db_pikquick",$con);
$sel = "SELECT * FROM TaxInvoiceMaster WHERE Id='$Id'";
$rsc = mysql_query($sel);
$data=  mysql_fetch_array($rsc); 
*/
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
    left:26px;
    border:0px solid gray;
}
table, th, td {
    width:70px;
    border: 1px solid gray;
    border-collapse: collapse;
    font-size: 11px;
    padding:5px;
}

.alg-cnt{text-align: center;}

.box{
    border: 1px solid black;
    width:15px;
    height:15px;
}
</style>
</head>
<body>
    <div class="invoicebox">
        <div></div>
        <div></div>
        <table>
            <tr>
                <td colspan="3"><b>Complaint No - </b> <?php echo $data['GSTStateCode'];?></td>
                <td colspan="3"><b>Product :</b> <?php echo $data['PanNo'];?></td> 
            </tr>
            <tr>
                <td colspan="3"><b>Name Of The Customer :</b> <?php echo $data['GSTStateCode'];?></td>
                <td colspan="3"> <b>Product Sub - Detail :</b><?php echo $data['PanNo'];?></td> 
            </tr>
            <tr>
                <td rowspan="4" colspan="3" ><b>Address :</b></td>
                <td colspan="3"><b>Quantity :</b></td>
            </tr>
            <tr><td colspan="3"><b>Dealer Name :</b></td></tr>
            <tr><td colspan="3"><b>Bill No :</b> <span style="margin-left:110px;" ><b>Date :</b></span></td></tr>
            <tr><td colspan="3"><b>Warranty/Out Warranty :</b></td> </tr>
            
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
                <td colspan="3" ><b>Mobile No :</b> <?php echo $data['GSTStateCode'];?></td>
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
                <td colspan="2" ><b>Model Name</b></td>
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
                    <div><b>Call Status/Remarks:</b></div>
                    <div style="margin-left: 500px;margin-top:15px;"><b>Service Agent Signature</b></div>
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
                    <div style=" position: relative;left:500px;top:-15px;font-weight: bold;">Customer Signature</div>
                </td>
            </tr>
            
            <tr>
                <td colspan="6" >
                    <b>Complaint No:</b><div style=" position: relative;left:180px;font-weight: bold;font-size:13px;">Temporary Cash/Cheque Receipt</div>   
                    <div style="margin-top:5px;"  ><b>Received with Thanks the sum of Rs. ............................(Rs. ........................................................)towards Service charges.</b></div>
                    <div style=" position: relative;left:500px;top:10px;font-weight: bold;">Service Agent Signature</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
