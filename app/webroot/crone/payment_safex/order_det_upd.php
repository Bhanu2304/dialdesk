 <?php
 
 
include("jwt.php");
$con = mysql_connect("localhost","root","dial@mas123","false",128) or die("could not connect");
$db  = mysql_select_db("db_dialdesk",$con) or die("database not exists");

$TempId = $_GET['TempId'];

    $MeId = "Deepak0002";
    $Tos = $To = date('Y-m-d');
    //$froms = $From = date('Y-m-d');
	$froms = $From = '2018-07-25';
        
	$paymode ='All';
	$status = 'Successful';
	$amountFrom= '0';
	$amountTo 	= '10000000';
	$limitFrom= 0;
	$limitTo= 1000;
	
    $ToArr = explode('/',$To);
    $FromArr = explode('/',$From);
    
    $ToArrN[0] =  $ToArr[2];
    $ToArrN[1] =  $ToArr[0];
    $ToArrN[2] =  $ToArr[1];
    
    $FromArrN[0] =  $FromArr[2];
    $FromArrN[1] =  $FromArr[0];
    $FromArrN[2] =  $FromArr[1];
    
    
    $conPaypik = mysql_connect("182.71.80.197","root","dial2123","false",128) or die("paypik not connect");
    $rscPaypik = mysql_query("Select ukey from db_paypik.tbl_user where UserId='$MeId'",$conPaypik) or die("error in query");;
    $selMeId = mysql_fetch_assoc($rscPaypik);
    $ukey = $selMeId['ukey'];   
    	 
    $arr = array("fromDate"=>$From,"toDate"=>$To,"amountFrom"=>$amountFrom,"paymode"=>$paymode,"status"=>$status,"amountTo"=>$amountTo,"limitFrom"=>0,"limitTo"=>500000);
    $req =   array('encryptText'=>json_encode($arr)); 
    
    $encodeJson = JWT::encode($req, $ukey);       
    $req = json_encode(array("sdkTransactionMISRequestBeanPostRequest"=>$encodeJson));  
    $MerStatusUrl = "https://www.avantgardepayments.com/agmerchant/sdk/transactionMis/userId/$MeId";  
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $MerStatusUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $req);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $response  = curl_exec($ch);
    curl_close($ch);
    //print_r($response); exit;
     $responeArr = JWT::decode($response,$ukey,'HS256');   
     
    
    $dataa = json_decode($responeArr->encryptText);
	
    //print_r($dataa); exit;
	
	
	/*$TOTAL_AMOUNT = $data->TOTAL_AMOUNT;
    $TRANSFERRED_AMOUNT = $data->TRANSFERRED_AMOUNT;
    $TRANSACTION_FEE = $data->TRANSACTION_FEE;
    $GST = $data->GST;
    $PENDING_PAYOUT = $data->PENDING_PAYOUT;*/
    foreach($dataa as $data){
        $transactionDate = $data->transactionDate;
    $orderNo = $data->orderNo;
    $txnAmount = $data->txnAmount;
    
    //$PENDING_PAYOUT = $data->PENDING_PAYOUT;
	$me_igst = $data->meIgst;
	$me_cgst = $data->meCgst;
	$me_sgst = $data->meSgst;
	$transactionId = $data->transactionId;
	$paymodeName =$data->paymodeName;
	$txnStatus = $data->txnStatus;
        
	$surchargeAmount = $data->surchargeAmount;
	$transactionFee = $data->$me_igst+$me_cgst+$me_sgst+$surchargeAmount;
	$netAMount=$txnAmount-$transactionFee;
	$txnStatus = $data->txnStatus;
	
       $upd = "Update db_dialdesk.payment_order_det set PaymentStatus='$txnStatus',PaymentDate='$transactionDate' where OrderNo='$orderNo'"; 
        $rscUpd = mysql_query($upd,$con);
        if($rscUpd)
        {
            $select = "select CaseId,OrderId,OrderNo from db_dialdesk.payment_order_det where OrderNo='$orderNo'" ;
            $rsc = mysql_query($select,$con);
            $SrNoId = mysql_fetch_assoc($rsc);

            $updInCallMaster = "Update db_dialdesk.call_master set PaymentStatus=1,PaymentAmount=PaymentAmount+$netAMount where Id='$SrNoId'";
            $rscUpd = mysql_query($updInCallMaster,$con);
        }
    
    
}

?>

