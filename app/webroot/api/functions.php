<?php
class BGV_Functions
{

    
public function deConnect()
{
	$this->conn = mysqli_connect("localhost","root","dial@mas123","db_dialdesk") or die("could not connect");

	if (!$this->conn) {
	   echo "Unable to connect to DB: " . mysql_error();
	   exit;
	}

	
}
public function send_sms($smsdata)
{
  $ReceiverNumber=$smsdata['ReceiverNumber'];
  
 
  $SmsText=$smsdata['SmsText'];
 
  $postdata = http_build_query(
   array(
    'uname'=>'MasCall',
    'pass'=>'M@sCaLl@234',
    'send'=>'Paypik',
    'dest'=>$ReceiverNumber,
    'msg'=>$SmsText
   )
  );
 
  $opts = array('http' =>
   array(
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => $postdata
   )
  );
 
  $context  = stream_context_create($opts);
  return $result = file_get_contents('http://www.unicel.in/SendSMS/sendmsg.php', false, $context);
 }




public function login_check()
{
   $user_name = $_REQUEST['username']; 
    $password = $_REQUEST['password'];
    
    $Rsc    = mysqli_query($this->conn,"select * from fecreation_master where username='$user_name' and password2='$password' and active='1'");
    if(mysqli_num_rows($Rsc))
    {
        $data = mysqli_fetch_assoc($Rsc);
        mysqli_query($this->conn,"insert into fecreation_log_master set UserId='{$data['id']}',username='$user_name',login_status='LogIn',LogInTime=now()");
        print_r(json_encode(array(0=>array('response' => '1','user_id'=>$data['id'],'user_name'=>$data['username'],'disp_name'=>$data['name'],'client_id'=>$data['create_id']))));
    }
//    else if(mysqli_num_rows($Rsc2))
//    {
//        $data = mysqli_fetch_assoc($Rsc2);
//        mysqli_query($this->conn,"update fe_vendor_master set VocUpdated='0',updated_at=now() where username='$user_name'");
//        print_r(json_encode(array(0=>array('response' => '1','user_id'=>$data['fe_id'],'user_name'=>$data['fe_name'],'vendor_id'=>$data['Vendor_Id'],'user_type'=>'fev'))));
//    }
    else
    {
        print_r(json_encode(array(0=>array('response' => '0','user_id'=>"",'user_name'=>"",'disp_name'=>"",'client_id'=>''))));
    }
}

public function forget_password()
{
    $user_name = $_REQUEST['userid'];
    
    
    $Rsc    = mysqli_query($this->conn,"select * from fecreation_master where username='$user_name' and active='1'");
    if(mysqli_num_rows($Rsc))
    {
        $data = mysqli_fetch_assoc($Rsc);
        $otp = rand(1000,9999);
        $num['ReceiverNumber'] = $data['phone'];
        $num['SmsText'] = "Your OTP for logging in is ".$otp;
        $res = $this->send_sms($num);
        mysqli_query($this->conn,"update fecreation_master set otp='$otp',otp_date = now() where username='$user_name'");
        print_r(json_encode(array(0=>array('response' => '1','otp'=>$otp))));
    }
//    else if(mysqli_num_rows($Rsc2))
//    {
//        $data = mysqli_fetch_assoc($Rsc2);
//        mysqli_query($this->conn,"update fe_vendor_master set VocUpdated='0',updated_at=now() where username='$user_name'");
//        print_r(json_encode(array(0=>array('response' => '1','user_id'=>$data['fe_id'],'user_name'=>$data['fe_name'],'vendor_id'=>$data['Vendor_Id'],'user_type'=>'fev'))));
//    }
    else
    {
        print_r(json_encode(array(0=>array('response' => '0','otp'=>""))));
    }
}


public function reset_password()
{
    $user_name = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $confirm_password = $_REQUEST['confirm_password'];
    
    
    $Rsc = mysqli_query($this->conn,"update fecreation_master set password2='$password' where username='$user_name'");
    if($Rsc)
    {
        print_r(json_encode(array(0=>array('response' => '1','otp'=>"$user_name"))));
    }
//    else if(mysqli_num_rows($Rsc2))
//    {
//        $data = mysqli_fetch_assoc($Rsc2);
//        mysqli_query($this->conn,"update fe_vendor_master set VocUpdated='0',updated_at=now() where username='$user_name'");
//        print_r(json_encode(array(0=>array('response' => '1','user_id'=>$data['fe_id'],'user_name'=>$data['fe_name'],'vendor_id'=>$data['Vendor_Id'],'user_type'=>'fev'))));
//    }
    else
    {
        print_r(json_encode(array(0=>array('response' => '0','otp'=>"$user_name"))));
    }
}
public function get_voc()
{
    $userid = $_REQUEST['userid'];
    $userType = $_REQUEST['userType'];
    $vendorId = $_REQUEST['vendorId'];
    
    $voc_master = array();
    
    
    if($userType=='fev')
    {
        $sel_fe = "select * from fe_vendor_master where fe_id='$userid' and VocUpdated='0'";
        $upd_fe = "update fecreation_master set VocUpdated='1',updated_at=now() where fe_id='$userid'";
    }
    else
    {
        $sel_fe = "select * from fecreation_master where fe_id='$userid' and VocUpdated='0'";
        $upd_fe = "update fe_vendor_master set VocUpdated='1',updated_at=now() where fe_id='$userid'";
    }
    $Rsc    = mysqli_query($this->conn,$sel_fe);
    
    if(mysqli_num_rows($Rsc))
    { 
        $Rsc2    = mysqli_query($this->conn,"SELECT `Feedback_Field_Id`,`Feedback_Field_Client_Id`,`Feedback_Field_Name`,`Feedback_Field_Type`,`Feedback_Field_Values`,`Feedback_Field_Validation`,`Feedback_min_range`,`Feedback_max_range`,`Feedback_Field_Sms_Deliver`,`Feedback_Field_Mandatory`,`Feedback_Field_Priority`,`Feedback_Call_Id`,`Feedback_Field_Status`,ff.`created_at`,ff.`created_by`,ff.`updated_at`,ff.`updated_by`,tof.check_type Feedback_Type_Of_Check FROM `feedback_fields` ff
INNER JOIN `types_of_check_master` tof
ON ff.Feedback_Type_Of_Check = tof.check_id");
        
        while($voc_record = mysqli_fetch_assoc($Rsc2))
        {
            $voc = array();
            foreach($voc_record as $key=>$value)
            {
                $voc[utf8_decode(trim($key))] = utf8_decode(trim($value));
            }
            $voc_master[] = $voc;
            //print_r($voc); exit;
        }
        
        mysqli_query($this->conn,$upd_fe);
    }    
    echo json_encode($voc_master);exit;
}

public function get_case()
{
    $userid = $_REQUEST['username'];
    
    $record_master = array();
    
        //$fe_det    = mysqli_fetch_assoc(mysqli_query($this->conn,"select id from fecreation_master where username='$username' limit 1"));
       // $userid = $fe_det['id'];
    
    if(!empty($userid))
    {
        //DeleteData
        $Rsc2    = mysqli_query($this->conn,"SELECT Id DataId,SrNo Ref_Id,Field1 Customer_Name,Field2 MSISDN,Field3 ComplaintNo,Field6 Company_Name,
Field10 Asset_Serial_No,Field9 Side_Id,'AddData' ActionData,'Exi-Com' DataType FROM exicom_tagging_master WHERE sent_status='0' AND fe_id='$userid'");   
        while($single_record = mysqli_fetch_assoc($Rsc2))
        {
            $record = array();
            foreach($single_record as $key=>$value)
            {
                $record[utf8_decode(trim($key))] = utf8_decode(trim($value));
            }
            $record_master[] = $record;
            //print_r($voc); exit;
        }
        mysqli_query($this->conn,"update exicom_tagging_master set sent_status='1' where fe_id='$userid'");
    }   
    echo json_encode($record_master);exit;
}


public function save_tagging()
{
 
    
    $DataId = $_POST['DataId'];
    $Feedback = $_POST['Feedback'];
    $Voc    = $_POST['Voc'];
    $Remark = $_POST['Remark'];
    $Feedback_Date = $_POST['Feedback_Date'];
    $Lat = $_POST['Lat'];
    $Lon = $_POST['Lon'];
    
    $Rsc2    = mysqli_query($this->conn,"SELECT ClientId FROM `exicom_tagging_master` where Id='$DataId' limit 1");
    $client_id_ar = mysqli_fetch_assoc($Rsc2);
    
    
    $client_id = $client_id_ar['ClientId'];
    if(empty($client_id))
    {
        $client_id = 0;
    }
    $update_qr = "update exicom_tagging_master set server_entry_time=now(),Feedback='$Feedback',voc='$Voc',Remarks = '$Remark',Feedback_Date='$Feedback_Date' where Id='$DataId'";
    
    
    
    
    
    
    $Rsc    = mysqli_query($this->conn,$update_qr);
    
    if($Rsc)
    {
        print_r(json_encode(array(0=>array('response' => "1","updateId"=>$DataId))));  
    }
    else
    {
        print_r(json_encode(array(0=>array('response' => "0","updateId"=>""))));
    }
}

public function saveLocation() 
{
    $Username=$_REQUEST['Username'];
    $userType=$_REQUEST['userType'];
    $vendorId=$_REQUEST['vendorId'];
    
    $Lat=$_REQUEST['Lat'];
    $Lon=$_REQUEST['Lon'];
    
 //$upd = mysql_query("update user_master set Latitude='$Lat', Longitude='$Lon', Accuracy='$Accuracy', BateryStatus='$BatteryStatus' where UserName='$Username'"); 
    $InsTrace = mysqli_query($this->conn,"INSERT INTO fe_tracker SET UserID='$Username',UserType='fe',Latitude='$Lat',Longitude='$Lon',Accuracy='$Accuracy',created_at=NOW()"); 
    if($InsTrace)
    {
        print_r(json_encode(array(0=>array('response' => "1","updateId"=>$id)))); 
    }
    else
    {
        print_r(json_encode(array(0=>array('response' => "","updateId"=>""))));
    }
    exit;
}


}
?>