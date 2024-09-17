<?php

mysql_connect('localhost','root','dial@mas123',TRUE);
mysql_select_db('db_dialdesk') or die('unable to connect');

include('report-send.php');


$exeQry=  mysql_query("SELECT * FROM call_master WHERE ClientId='277' AND Category1='Return Request' AND DATE(CallDate)=CURDATE() AND MailStatus IS NULL");
$exeArr= mysql_num_rows($exeQry);

if(mysql_num_rows($exeQry) > 0){

    $numQry=mysql_query("SELECT fieldNumber FROM field_master WHERE ClientId='277' AND id='498' limit 1");
    $numArr=mysql_fetch_array($numQry);
    $num=$numArr[0];

    $numQry1=mysql_query("SELECT fieldNumber FROM field_master WHERE ClientId='277' AND id='505' limit 1");
    $numArr1=mysql_fetch_array($numQry1);
    $num1=$numArr1[0];

    while($row = mysql_fetch_array($exeQry)){
        $email=trim(strtolower($row['Field'.$num1]));
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            send_link($row['Field'.$num],$email,$row['SrNo'],$row['Id'],$row['AreaPincode'],$row['AreaAddress'],$row['TokenNumber']);
        } 
    }

}
 

//send_link('Arpit','agrawal.arpit@gmail.com','3281','13');

 
function send_link($name,$email,$rmano,$id,$pincode,$AreaAddress,$TokenNumber){
    
    $url="http://dialdesk.co.in/printingpdf/examples/returnlabel.php?rmano=".base64_encode($rmano);
    //$url="http://192.168.1.230/printingpdf/examples/returnlabel.php?rmano=".base64_encode($rmano);
    
    if($pincode !=""){
        $pincodeurl="http://dialdesk.co.in/printingpdf/examples/returnlabel.php?rmano=".base64_encode($rmano);
        //$pincodeurl="http://192.168.1.230/printingpdf/examples/returnlabel.php?rmano=".base64_encode($rmano);
        
        $labelurl="<p>Please download lable using below link.</p><p><a href='$pincodeurl' target='blank' >$pincodeurl</a></p>";
    }
    else{
        $labelurl="";
    }
    
    $EmailText      ='';
    $ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "RMA Confirmation ($rmano)"; 
    $EmailText      .="
                        <div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>
                            <div style='border: 1px solid silver;width:548px;height: 100px;background-color:#2c7bb5'>
                                <div style='text-align: center;color:white;font-size: 30px;font-family: sans-serif;margin-top: 15px;'>
                                    RMA confirmation
                                </div>
                                <div style='text-align: center;color:white;font-size: 18px;font-family: sans-serif;'>
                                    RMA #$rmano
                                </div>  
                            </div>

                            <div style='width:500px;margin-left:20px;margin-top: 50px;font-family: sans-serif;font-size: 15px;color: gray;'>
                                <p>Hi <span style='text-transform: uppercase;'>$name</span></p>
                                <p>This is a confirmation of your RMA (Return Material Authorization) request.</p>
                                <p style='margin-top:50px;' >Please make sure to view our guidelines on how to package your product(s)<br/> for shipment to expedite your RMA.</p>
                                <p style='font-size:18px;'>Please read the following shipping instructions carefully</p>
                                <p>
                                    
                                Products arrived damaged during shipment, without an RMA number or without appropriate warranty 
                                information will be returned to the sender in their original condition and unrepaired. Products, 
                                damaged through neglect due to improper packaging or during shipment will have the warranty 
                                voided and will be processed andreturned to you unrepaired.
                                </p>
                                <p style='font-size:18px;'>Only products and quantities authorized in the RMA will be accepted</p>

                                <p ><hr/></p>
                                <p>Please click on <a href='$url' style='text-decoration:none;' >Download</a>.If your browser does not open it, please copy download link and paste it in your browser's address bar.<br/></p>
                                <a href='$url' target='blank' style='text-decoration:none;color: white;margin-left: 120px;'><button style='background-color: #008cba;color: white;border: medium none;border-radius: 50%;font-size: 16px; padding: 15px 32px;cursor: pointer;'>Download RMA/Shipping Label</button></a><br/>
                                $labelurl
                                    
                                <p><strong>Please drop your shipment at below address and Token No within 3 days</strong></p>
                                <p>$AreaAddress</p>
                                <p><strong>Token Number</strong> : $TokenNumber</p><br/>
                                    
                                <h5>Important</h5>
                                <p>1. LxWxH (CM)- 30x13x6 (this is maximum), Maximum weight should be 480 gram including product and packaging (whole packet), Please pack product properly by yourself, don't wrap in polybag only as it may get damaged.</p>
                                <p>2. Power bank (USB charging cable not covered in warranty, do not send USB charging cable against this RMA),</p>
                                <p>3. Laptop charger (laptop charger Power cord not covered in warranty, do not send charger Power cord against this RMA)</p>
                                <br/>

                            </div>
                        </div>
                      ";
    
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    $done = send_email( $emaildata);
    if($done=='1'){
        mysql_query("UPDATE call_master SET MailStatus='Success',MailDateTime=NOW() WHERE ClientId='277' AND Id='$id' AND MailStatus IS NULL");
    } 
}


