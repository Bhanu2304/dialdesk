<?php
class MyAccountsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','ClientRequestMaster');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('update_client','setting','check_admin_verify','change_password','change_email','check_exist_phone','delete_otp_session','sendotp','change_phone','delete_documents','change_logo','update_contact_details');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		$result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
		$this->set('data',$result['RegistrationMaster']);
                if(isset($_REQUEST['update']) && $_REQUEST['update'] !=""){
                   $this->set('update',$_REQUEST['update']);
                }
	}
        
        public function setting() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		$result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
		$this->set('data',$result['RegistrationMaster']);
	}
	
	public function check_exist_phone(){
		$result = $this->RegistrationMaster->find('first',array('conditions' =>array('phone_no' => $_REQUEST['phone_no'])));
		if(!empty($result)){echo '1';die;}else{echo '';die;}
	}
        
        public function check_admin_verify(){
                $cid  = $this->Session->read('companyid');
		$result = $this->RegistrationMaster->find('first',array('conditions' =>array('status' =>'A','company_id'=>$cid)));
		if(!empty($result)){echo '1';die;}else{echo '';die;}
	}
	
	
	public function delete_otp_session() {
		$this->Session->delete('newotp');
		die;
    }
	
	public function sendotp(){
		if(isset($_REQUEST['phone'])){
			echo $otp = rand(100000,999999);
			$num['ReceiverNumber'] = $_REQUEST['phone'];
			$num['SmsText'] = "Your OTP for logging in is ".$otp.".";
			$this->send_sms($num);
			$this->Session->write("newotp",$otp);
			die; 
		}	
	}
	
	public function send_sms($smsdata){
		$ReceiverNumber=$smsdata['ReceiverNumber'];
		$len=strlen($ReceiverNumber);
		$ReceiverNumber=substr($ReceiverNumber,$len-10,10);

		if(strlen($ReceiverNumber)<11) { $ReceiverNumber='91'.$ReceiverNumber; }

		$SmsText=$smsdata['SmsText'];

		$postdata = http_build_query(
			array(
				'uname'=>'MasCall',
				'pass'=>'M@sCaLl@234',
				'send'=>'mascal',
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
		//return $result = file_get_contents('http://www.unicel.in/SendSMS/sendmsg.php', false, $context);
	}

	public function change_phone(){
		if($this->request->is('Post')){
			$cid  = $this->Session->read('companyid');
			$otp = $this->Session->read('newotp');
			$data = $this->request->data;
                        $existReq = $this->ClientRequestMaster->find('first',array('conditions' =>array('client_id'=>$cid,'request_type' =>'phone','request_status'=>'P')));
			
                        if($data['otpval'] ==$otp){
                            $reqArray=array(
                                'client_id'=>$cid,
                                'request_type'=>'phone',
                                'request_data'=>addslashes(trim($data['phone_no'])),
                                'request_status'=>'P'
                            );
                            
                            if(!empty($existReq)){
                                echo "3";die;
                            }
                            else if($this->ClientRequestMaster->save($reqArray)){
                                echo "2";die;
                            }    
			}
			else{
                            echo "1";die;
			}
		}
	}
        
	
	public function change_password(){
            if($this->request->is('Post')){
                $cid  = $this->Session->read('companyid');
                $data = $this->request->data;
                $exist = $this->RegistrationMaster->find('first',array('conditions' =>array('password' =>$data['currentpass'],'company_id'=>$cid)));
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $update_date=date('Y-m-d H:i:s');
                
                if(empty($exist)){
                    echo "1";die;
                }
                else{
                    $dataArr=array('password'=>"'".addslashes(trim($data['newpass']))."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
                    $this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$cid));
                    echo "2";die;
                }
            }
	}
	
        
        
	public function change_email(){
		if($this->request->is('Post')){
			$cid  = $this->Session->read('companyid');
			$data = $this->request->data;
			$exist = $this->RegistrationMaster->find('first',array('conditions' =>array('email' => $data['newemail'])));
                        $existReq = $this->ClientRequestMaster->find('first',array('conditions' =>array('client_id'=>$cid,'request_type' =>'email','request_status'=>'P')));
			$verify = $this->RegistrationMaster->find('first',array('conditions' =>array('status' =>'A','company_id'=>$cid)));
			
			if(empty($verify)){
                            echo "Email can update after admin varificaiton.";die;
			}
			else if($this->checkSmtpEmail($data['newemail']) !=true){
                            echo "Please enter valid email.";die;
			}
			else if(!empty($exist)){
                            echo "This email id already exist in database.";die;
			}
                        else if(!empty($existReq)){
                            echo "Your request already sent to admin.";die;
			}
			else{
                            $reqArray=array(
                                'client_id'=>$cid,
                                'request_type'=>'email',
                                'request_data'=>addslashes(trim($data['newemail'])),
                                'request_status'=>'P'
                            );
                            if($this->ClientRequestMaster->save($reqArray)){
                                echo "Your request sent to admin please wait till admin response.";die;
                            }
			}
		}
	}
        
        
        public function change_logo(){
            if(isset($_FILES['companylogo'])){   
                $compid  = $this->Session->read('companyid');
                $file_name = $_FILES['companylogo']['name'];
                $file_size =$_FILES['companylogo']['size'];
                $file_tmp =$_FILES['companylogo']['tmp_name'];
                $file_type=$_FILES['companylogo']['type'];
                $file_ext=strtolower(end(explode('.',$_FILES['companylogo']['name'])));
                $expensions= array('jpg','jpeg','gif','png','pdf');
               
                if (!file_exists(WWW_ROOT.'upload/client_file/client_'.$compid)) {
                    mkdir(WWW_ROOT.'upload/client_file/client_'.$compid, 0777, true);
		}
                
		$path=WWW_ROOT.'upload/client_file/client_'.$compid.'/';
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $update_date=date('Y-m-d H:i:s');
                
                if(in_array($file_ext,$expensions)=== true){
                    $rand = rand(100000,999999);
                    move_uploaded_file($file_tmp,$path.$rand.$file_name);
                    $this->RegistrationMaster->updateAll(array('company_logo'=>"'".$rand.$file_name."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'"),array('company_id'=>$compid));
                    echo $rand.$file_name; die;
                }
                else{
                    echo "2";die;
                }
            }
           
            
        }

        public function checkSmtpEmail($email){
		require_once(APP . 'Lib' . DS . 'check_smtp' . DS . 'smtp_validateEmail.class.php');
		$sender = 'user@mydomain.com';
		$SMTP_Validator = new smtp_validateEmail();
		$SMTP_Validator->debug = false;
		$results = $SMTP_Validator->validate(array($email), $sender);	
		return $results[$email];
	}
        
        
        
        public function update_contact_details(){
		$data=$this->request->data;
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $update_date=date('Y-m-d H:i:s');
                
		$dataArr=array(
			'contact_person1'=>"'".addslashes(trim($data['contact_person1']))."'",
			'cp1_designation'=>"'".addslashes(trim($data['cp1_designation']))."'",
			'cp1_phone'=>"'".addslashes(trim($data['cp1_phone']))."'",
			'cp1_email'=>"'".addslashes(trim($data['cp1_email']))."'",
			'contact_person2'=>"'".addslashes(trim($data['contact_person2']))."'",
			'cp2_designation'=>"'".addslashes(trim($data['cp2_designation']))."'",
			'cp2_phone'=>"'".addslashes(trim($data['cp2_phone']))."'",
			'cp2_email'=>"'".addslashes(trim($data['cp2_email']))."'",
			'contact_person3'=>"'".addslashes(trim($data['contact_person3']))."'",
			'cp3_designation'=>"'".addslashes(trim($data['cp3_designation']))."'",
			'cp3_phone'=>"'".addslashes(trim($data['cp3_phone']))."'",
                        'update_user'=>"'".$update_user."'",
                        'update_date'=>"'".$update_date."'",
			'cp3_email'=>"'".addslashes(trim($data['cp3_email']))."'");
			
			$this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$data['company_id']));
			$this->redirect(array('controller' => 'MyAccounts', 'action' => 'index','?'=>array('update'=>'cod')));
	}
        
	
	public function update_client(){
		$data=$this->request->data;
                
                /*
                    $dataArr=array(
			'contact_person1'=>"'".addslashes(trim($data['contact_person1']))."'",
			'cp1_designation'=>"'".addslashes(trim($data['cp1_designation']))."'",
			'cp1_phone'=>"'".addslashes(trim($data['cp1_phone']))."'",
			'cp1_email'=>"'".addslashes(trim($data['cp1_email']))."'",
			'contact_person2'=>"'".addslashes(trim($data['contact_person2']))."'",
			'cp2_designation'=>"'".addslashes(trim($data['cp2_designation']))."'",
			'cp2_phone'=>"'".addslashes(trim($data['cp2_phone']))."'",
			'cp2_email'=>"'".addslashes(trim($data['cp2_email']))."'",
			'contact_person3'=>"'".addslashes(trim($data['contact_person3']))."'",
			'cp3_designation'=>"'".addslashes(trim($data['cp3_designation']))."'",
			'cp3_phone'=>"'".addslashes(trim($data['cp3_phone']))."'",
			'cp3_email'=>"'".addslashes(trim($data['cp3_email']))."'");
			
			$this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$data['company_id']));
                         
                         */
                
                         if($this->Session->read('role') =="client"){
                            $update_user=$this->Session->read('email');
                        }
                        else if($this->Session->read('role') =="agent"){
                            $update_user=$this->Session->read('agent_username');
                        }
                        if($this->Session->read('role') =="admin"){
                            $update_user=$this->Session->read('admin_name');
                        }

                        $update_date=date('Y-m-d H:i:s');
                        
			$compid=$data['company_id'];
			$doc1=$data['incorporation_certificate'];
			$doc2=$data['pancard'];
			$doc3=$data['bill_address_prof'];
			$doc4=$data['authorized_id_prof'];
			$doc5=$data['auth_person_address_prof'];
                        $doc6=$data['other_documents'];
			
			if (!file_exists(WWW_ROOT.'upload/client_file/client_'.$compid)) {
    			mkdir(WWW_ROOT.'upload/client_file/client_'.$compid, 0777, true);
			}
			$path=WWW_ROOT.'upload/client_file/client_'.$compid.'/';
					
			if(!empty($_FILES['userfile']['name'])){
				$data=$this->upload_multiple_file($filename='userfile',$path,$doc1);
				$this->RegistrationMaster->updateAll(array('incorporation_certificate'=>"'".$data."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile2']['name'])){
				$data2=$this->upload_multiple_file($filename='userfile2',$path,$doc2);
				$this->RegistrationMaster->updateAll(array('pancard'=>"'".$data2."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile3']['name'])){
				$data3=$this->upload_multiple_file($filename='userfile3',$path,$doc3);
				$this->RegistrationMaster->updateAll(array('bill_address_prof'=>"'".$data3."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile4']['name'])){
				$data4=$this->upload_multiple_file($filename='userfile4',$path,$doc4);
				$this->RegistrationMaster->updateAll(array('authorized_id_prof'=>"'".$data4."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile5']['name'])){
				$data5=$this->upload_multiple_file($filename='userfile5',$path,$doc5);
				$this->RegistrationMaster->updateAll(array('auth_person_address_prof'=>"'".$data5."'"),array('company_id'=>$compid));
			}
                        if(!empty($_FILES['userfile6']['name'])){
				$data6=$this->upload_multiple_file($filename='userfile6',$path,$doc6);
				$this->RegistrationMaster->updateAll(array('other_documents'=>"'".$data6."'"),array('company_id'=>$compid));
			}
                        
                        $this->RegistrationMaster->updateAll(array('update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'"),array('company_id'=>$compid));
                        
			$this->redirect(array('controller' => 'MyAccounts', 'action' => 'index','?'=>array('update'=>'cld')));
	}
	
	public function upload_multiple_file($filename,$path,$doc){
		$files = $_FILES;
		$fileName=array();
		$cpt = count($_FILES[$filename]['name']);
		if($cpt >0){
			for($i=0; $i<$cpt; $i++){
				$rand = rand(100000,999999);
				$_FILES[$filename]['name']		= $files[$filename]['name'][$i];
				$_FILES[$filename]['type']		= $files[$filename]['type'][$i];
				$_FILES[$filename]['tmp_name']	= $files[$filename]['tmp_name'][$i];
				$_FILES[$filename]['error']		= $files[$filename]['error'][$i];
				$_FILES[$filename]['size']		= $files[$filename]['size'][$i];    
		
				$ary_ext=array('jpg','jpeg','gif','png','pdf'); 
				$ext = substr(strtolower(strrchr($_FILES[$filename]['name'], '.')), 1); 
				if(in_array($ext, $ary_ext)){
					move_uploaded_file($_FILES[$filename]['tmp_name'],$path.$rand.$_FILES[$filename]['name']);
					$fileName[]= $rand.$_FILES[$filename]['name'];
				}
			}
			if($doc !=""){
				return $data=implode(',',array_merge(explode(",",$doc),$fileName));	
			}
			else{
				return $data=implode(',',$fileName);
			}
		}
	}
        
        public function delete_documents(){
            $cid  = $this->Session->read('companyid'); 
            $path=WWW_ROOT.'upload/client_file/client_'.$cid.'/';
            if(isset($_REQUEST['item'])){
                $item=$_REQUEST['item'];
                $exist = $this->RegistrationMaster->find('first',array('fields'=>array($item),'conditions' =>array('company_id'=>$cid)));              
                $imgArr=explode(',', $exist['RegistrationMaster'][$item]);
                foreach($imgArr as $img){
                    unlink($path.$img);
                }
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $update_date=date('Y-m-d H:i:s');
                
                $data=array("$item"=>"'".NULL."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");			
                $this->RegistrationMaster->updateAll($data,array('company_id' =>$cid));			
            }
            $this->redirect(array('controller' => 'MyAccounts', 'action' => 'index'));
        }
		
}
?>