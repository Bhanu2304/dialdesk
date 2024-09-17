<?php
mysql_connect('localhost','root','dial@mas123',TRUE);
mysql_select_db('db_dialdesk') or die('unable to connect');

include('report-send.php');

//$exeQry=  mysql_query("SELECT * FROM call_master WHERE ClientId='277' AND Category1='Return Request' AND SrNo='39676'");
$exeQry=  mysql_query("SELECT * FROM call_master WHERE ClientId='277' AND Category1='Return Request' AND DATE(CallDate)=CURDATE() AND MailStatus IS NULL AND AreaPlace='Service Available'");
$exeArr= mysql_num_rows($exeQry);

if(mysql_num_rows($exeQry) > 0){
    while($row = mysql_fetch_array($exeQry)){
        bluedartapi($row);
        $email=trim(strtolower($row['Field8'.$num1]));
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            send_link($row['Id']);
        } 
    }
}
 
//send_link('Arpit','agrawal.arpit@gmail.com','3281','13');

function send_link($DataId){
    
    $call_master        =   mysql_query("SELECT * FROM call_master WHERE Id='$DataId'");
    $row                =   mysql_fetch_array($call_master);
    
    $name               =   $row['Field1'];
    $email              =   $row['Field8'];
    $rmano              =   $row['SrNo'];
    $id                 =   $row['Id'];
    $pincode            =   $row['AreaPincode'];
    $TokenNumber        =   $row['AWBNo'];
    $Courier            =   $row['CCRCRDREF'];
    $customerAddress    =   $row['Field22'];
    $CustomerMobile     =   $row['Field2'];
    $customerState      =   $row['Field5'];
    $Dateofpurchase     =   $row['Field19']!=""?date("d-M-Y",strtotime($row['Field19'])):"";
    $OrderId            =   $row['Field20'];
    
    if($TokenNumber !=""){
        
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
                                    <h5>Your Shipment will be Pickup by $Courier, against AWN $TokenNumber.</h5>
                                    <h5>Pickup Address:-</h5>
                                    <h5>MR. $name</h5>
                                    <h5>Adress. $customerAddress</h5>
                                    <h5>State. $customerState Pin Code. $pincode</h5>
                                    <h5>Contact No. $CustomerMobile</h5>

                                    <h5>Pickup related issue Kindly Contact the $Courier Support No.[ ]. </h5>
                                    <h5>Important</h5>
                                    <p>1. LxWxH (CM)- 30x13x6 (this is maximum), Maximum weight should be 480 gram including product and packaging (whole packet), Please pack product properly by yourself, don't wrap in polybag only as it may get damaged.</p>
                                    <p>2. Power bank (USB charging cable not covered in warranty, do not send USB charging cable against this RMA),</p>
                                    <p>3. Laptop charger (laptop charger Power cord not covered in warranty, do not send charger Power cord against this RMA)</p>
                                    <br/><br/>
                                    <p>If any customer give wrong information to the agent (time of Raising the Return Request) about the Product's purchase date is $Dateofpurchase and order id is $OrderId. So the service charge will applicable as 500/-</p>
                                    <br/>

                                </div>
                            </div>
                          ";
        //echo $EmailText;die;


        $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
        $done = send_email( $emaildata);
        if($done=='1'){
            mysql_query("UPDATE call_master SET MailStatus='Success',MailDateTime=NOW() WHERE ClientId='277' AND Id='$id' AND MailStatus IS NULL");
        }
    }
}

function bluedartapi($row){

    $lastid         =   $row['Id'];
    $AreaPincode    =   $row['AreaPincode'];
    $ClientId       =   $row['ClientId'];
    $Category1      =   $row['Category1'];
    
    if($ClientId =="277" && $Category1 =="Return Request" && $AreaPincode !=""){

        $CreditReferenceNo  =   "REV".$row['SrNo'];
        $PickupDate         =   date('Y-m-d',strtotime($row['CallDate']));
        $PickupTime         =   date('Hi',strtotime(date('Y-m-d H:i:s')));
        $ItemName           =   $row['Field9'];

        $ProductArr =   mysql_query("SELECT * FROM `arb_product_master` WHERE ProductName='$ItemName' limit 1 ");
        $ProDetArr  =   mysql_fetch_array($ProductArr);
        
        $Breadth    =   $ProDetArr['Breadth'];
        $Height     =   $ProDetArr['Height'];
        $Length     =   $ProDetArr['Length'];
        $Weight     =   $ProDetArr['Weight'];
        $Count      =   $ProDetArr['Count'];
        $Price      =   $ProDetArr['Price'];
        $Taxiable   =   $Price/18;

        $customerAddress    =   $row['Field22'];
        $CustomerEmailID    =   $row['Field8'];
        $CustomerMobile     =   $row['Field2'];
        $CustomerName       =   $row['Field1'];
        $CustomerPincode    =   $row['AreaPincode'];
        $CustomerTelephone  =   $row['Field4'];
        $customerCity       =   $row['Field5'];
        
        $order = array( 'orders'=> [
            array(
                "orderId"=> $CreditReferenceNo,
                "customerName"=> $CustomerName,
                "customerAddress"=> $customerAddress,
                "customerCity"=> $customerCity,
                "customerPinCode"=> $CustomerPincode,
                "customerContact"=> $CustomerMobile,
                "orderDate"=> $PickupDate,
                "modeType"=> "Lite-0.5kg",
                "orderType"=> "reverse",
                "totalValue"=> $Price,
                "categoryName"=> "Computers and Accessories",
                "packageName"=> $ItemName,
                "quantity"=> $Count,
                "packageLength"=> $Length,
                "packageWidth"=> $Breadth,
                "packageHeight"=> $Height,
                "packageWeight"=> $Weight,
                "sellerAddressId"=> "11294" 
            )
        ]);
        
        $Shipment_Data  =   getShipmentSlip($CreditReferenceNo);
        $Ship_Res       =   json_decode($Shipment_Data);

        $AWBNo          =   $Ship_Res->awbNo;
        $carrierName    =   $Ship_Res->carrierName;
        $fileName       =   $Ship_Res->fileName;
        $manifestID     =   $Ship_Res->manifestID;
        $status         =   $Ship_Res->status;
        
        mysql_query("UPDATE call_master SET AWBNo='$AWBNo',CCRCRDREF='$carrierName',DestinationArea='$fileName',OtherStatus='$status',TokenNumber='$AWBNo' WHERE Id='$lastid'");
    }
}

function authenticatShyplite() {
        $email      =   "arpit@arbaccessories.in";
        $password   =   "in4mation";

        $timestamp  =   time();
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $secret     =   'fxkCl2FPvVIcE/t21fpk0KjZn3iNpYQTPHAEQRBq9dC+SAa9Gd/8MgKbEFaPcHZAFqGBQpl4QKjSsmZpL+Ojug==';

        $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch = curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "emailID=$email&password=$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
        exit;
        curl_close($ch);
    }
        
    function set_order($data){
        $Login      =   authenticatShyplite();
        $obj        =   json_decode($Login);
        $secret     =   $obj->userToken;
        $timestamp  =   time();
        $sellerid   =   15196;
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $sign       =   "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken  =   rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch         =   curl_init();

        $data_json = json_encode($data);
      
        $header = array(
            "x-appid: $appID",
            "x-sellerid: $sellerid",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken",
            "Content-Type: application/json",
            "Content-Length: ".strlen($data_json)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/order');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        return $response;
        exit;
        curl_close($ch);
    }
    
    function getShipmentSlip($OrderID){
        $Login      =   authenticatShyplite();
        $obj        =   json_decode($Login);
        $secret     =   $obj->userToken;
        
        $timestamp  =   time();
        $sellerid   =   15196;
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $sign       =   "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken  =   rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch         =   curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "x-sellerid: $sellerid",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/getSlip?orderID='.urlencode($OrderID));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
        exit;
        curl_close($ch);
    }


