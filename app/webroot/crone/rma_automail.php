<?php

mysql_connect('localhost','root','dial@mas123',TRUE);
mysql_select_db('db_dialdesk') or die('unable to connect');

include('report-send.php');

$qry=  mysql_query("SELECT * FROM `rma_master` WHERE `Status`='A'");
while($ResArr=mysql_fetch_assoc($qry)){

    $exeQry=  mysql_query("SELECT * FROM call_master WHERE ClientId='{$ResArr['ClientId']}' AND Category1='{$ResArr['Category']}' AND DATE(CallDate)=CURDATE() AND MailStatus IS NULL");
    $exeArr= mysql_num_rows($exeQry);

    if(mysql_num_rows($exeQry) > 0){
        $num=$ResArr['NameField'];
        $num1=$ResArr['EmailField'];

        while($row = mysql_fetch_array($exeQry)){
            //$email=trim(strtolower($row['Field'.$num1]));
            $email="chandresh.tripathi@teammas.in";
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                send_link($row['Field'.$num],$email,$row['SrNo'],$row['Id'],$ResArr['ClientId']);
            } 
        }
    }
    
}


function send_link($name,$email,$rmano,$id,$clientid){
         
    $url="http://dialdesk.in/replacement/examples/rg0032179.php?rmano=".base64_encode($rmano)."&NRST=".base64_encode($clientid);
    
    $EmailText      ='';
    $ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "Return Request Form ($rmano)"; 
    $EmailText      .="
                        <div style='border: 1px solid silver;width:550px;height:470px;pxposition: relative;top:18;left:22px;'>
                            <div style='border: 1px solid silver;width:548px;height: 100px;background-color:#2c7bb5'>
                                <div style='text-align: center;color:white;font-size: 30px;font-family: sans-serif;margin-top: 15px;'>
                                    Return Request Form
                                </div>
                                <div style='text-align: center;color:white;font-size: 18px;font-family: sans-serif;'>
                                    Complain No #$rmano
                                </div>  
                            </div>

                            <div style='width:500px;margin-left:20px;margin-top: 50px;font-family: sans-serif;font-size: 15px;color: gray;'>
                                <p>Hi <span style='text-transform: uppercase;'>$name</span></p>
                                <p>This is a confirmation of your Return Request.</p>
                                
                                <!--
                                <p style='margin-top:50px;' >Please make sure to view our guidelines on how to package your product(s)<br/> for shipment to expedite your RMA.</p>
                               
                                <p style='font-size:18px;'>Please read the following shipping instructions carefully</p>
                                <p>
                                    
                                Products arrived damaged during shipment, without an Complain number or without appropriate warranty 
                                information will be returned to the sender in their original condition and unrepaired. Products, 
                                damaged through neglect due to improper packaging or during shipment will have the warranty 
                                voided and will be processed andreturned to you unrepaired.
                                </p>
                               
                                <p style='font-size:18px;'>Only products and quantities authorized in the Complain No will be accepted</p>
                                -->
                                <p ><hr/></p>
                                <p>Please click on <a href='$url' style='text-decoration:none;' >Download</a>.If your browser does not open it, please copy download link and paste it in your browser's address bar.<br/></p>
                                <a href='$url' target='blank' style='text-decoration:none;color: white;margin-left: 120px;'><button style='background-color: #008cba;color: white;border: medium none;border-radius: 50%;font-size: 16px; padding: 15px 32px;cursor: pointer;'> Download Form </button></a><br/>   
                                <p><a href='$url' target='blank' >$url</a></p>
                            </div>
                        </div>
                      ";
 
 
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    $done = send_email( $emaildata);
    if($done=='1'){
        mysql_query("UPDATE call_master SET MailStatus='Success',MailDateTime=NOW() WHERE ClientId='$clientid' AND Id='$id' AND MailStatus IS NULL");
    }
    
}


