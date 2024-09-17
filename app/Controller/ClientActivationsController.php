<?php
class ClientActivationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','IvrMaster','TmpRegistrationMaster','CityMaster','StateMaster','LogincreationMaster','PagesMaster','ClientReportMaster','LoginLog');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'company_registration',
                        'login',
                        'send_forgot_otp',
                        'matchotp_forgot_otp',
                        'delete_forgot_otp',
			'checkSmtpValidation',
			'check_exist_email',
			'delete_otp_session',
			'check_exist_phone',
			'sendotp',
			'send_sms',
			'matchotp',
			'save_company',
			'check_exist_company',
			'message',
			'verify_email',
			'save_firstForm_data',
			'save_secondForm_data',
			'logout',
			'forgot_password',
			'reset_password',
			'save_password',
			'get_city');
       // $this->moveTohttp();
    }
	
	public function index() {
		$this->layout='homeLayout';	
	}
       





        public function login() {
		$this->layout='homeLayout';
      
if ($this->request->is('post')) {
            
            
             $arrData=$this->RegistrationMaster->find('first',array(
            'conditions' => array(
                    'password' =>$this->request->data['ClientActivations']['password'],
                            'or' => array(
                                    'email' =>$this->request->data['ClientActivations']['emailid'],
                                    'phone_no' =>$this->request->data['ClientActivations']['emailid']
                                    )
                            )	
            ));
             
            /*
            $arrData=$this->RegistrationMaster->find('first',array(
                
                
                'conditions' =>  array (
                    'email' =>$this->request->data['ClientActivations']['emailid'],
                    'password' =>$this->request->data['ClientActivations']['password']
                )

                
            ));*/
            
            
            
            if(!empty($arrData['RegistrationMaster'])){
                if($arrData['RegistrationMaster']['email_verify'] ==="yes"){
             
                    $this->Session->write("companyid",$arrData['RegistrationMaster']['company_id']);
                    $this->Session->write("campaignid",$arrData['RegistrationMaster']['campaignid']);
					$this->Session->write("GroupId",$arrData['RegistrationMaster']['GroupId']);
                    $this->Session->write("companylogo",$arrData['RegistrationMaster']['company_logo']);
                    $this->Session->write("clientstatus",$arrData['RegistrationMaster']['status']);
                    $this->Session->write("companyname",$arrData['RegistrationMaster']['company_name']);
                    $this->Session->write("username",$arrData['RegistrationMaster']['auth_person']);
                    $this->Session->write("email",$arrData['RegistrationMaster']['email']);
                    $this->Session->write("role","client");
                    
                    /* +++++++++++++++++++++++++++++++++++++++++++++++++ */
                    $checkId=$this->ClientReportMaster->find('first',array('conditions'=>array('ClientId'=>$arrData['RegistrationMaster']['company_id'],'Status'=>'A')));
                    $this->Session->write("CheckClientId",$checkId);
                    /* +++++++++++++++++++++++++++++++++++++++++++++++++ */
					$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

					$data['user_id'] = $arrData['RegistrationMaster']['company_id'];
					$data['type'] = "client";
					$data['ip_address'] = $ip;
					$data['user_name'] = $arrData['RegistrationMaster']['company_name'];
					$data['page_name'] = "Login";
					$data['page_url'] = "Login";
					$data['hit_time'] = date("Y-m-d H:i:s");

					$this->LoginLog->save($data);
                    
                    $this->redirect(array('controller' => 'homes2', 'action' => 'index'));
                }
                else{
                    $this->Session->setFlash('Please Activate Account.');
                    $this->redirect(array('action' => 'login'));
                }
            }
            else{
                $arrData=$this->LogincreationMaster->find('first',array(
                'conditions' => array(
                        'password2' =>$this->request->data['ClientActivations']['password'],
                                'or' => array(
                                        'username' =>$this->request->data['ClientActivations']['emailid'],
                                        'phone' =>$this->request->data['ClientActivations']['emailid']
                                        )
                                )	
                )); 

                if (!empty($arrData['LogincreationMaster'])){
                        $clientData=$this->RegistrationMaster->find('first',array(
                                'fields'=>array('company_name','status','company_logo','campaignid'),
                                'conditions' => array('company_id' =>$arrData['LogincreationMaster']['create_id'])	
                                )); 
                       
                        $this->Session->write("company_id",$arrData['LogincreationMaster']['create_id']);
                        $this->Session->write("companyid",$arrData['LogincreationMaster']['create_id']);
                        $this->Session->write("companylogo",$clientData['RegistrationMaster']['company_logo']);
                        $this->Session->write("clientstatus",$clientData['RegistrationMaster']['status']);
                        $this->Session->write("campaignid",$clientData['RegistrationMaster']['campaignid']);
					    $this->Session->write("GroupId",$clientData['RegistrationMaster']['GroupId']);

                        $this->Session->write("companyname",$clientData['RegistrationMaster']['company_name']);
                        $this->Session->write("agent_userid",$arrData['LogincreationMaster']['id']);
                        $this->Session->write("agent_username",$arrData['LogincreationMaster']['username']);
                        $this->Session->write("agent_name",$arrData['LogincreationMaster']['name']);
                        $this->Session->write("agent_rights",$arrData['LogincreationMaster']['user_right']);
                        $this->Session->write("role","agent");
                        
                        /* +++++++++++++++++++++++++++++++++++++++++++++++++ */
                        $checkId=$this->ClientReportMaster->find('first',array('conditions'=>array('ClientId'=>$arrData['LogincreationMaster']['create_id'],'Status'=>'A')));
                        $this->Session->write("CheckClientId",$checkId);
                        /* +++++++++++++++++++++++++++++++++++++++++++++++++ */
						$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

						$data['user_id'] = $arrData['LogincreationMaster']['create_id'];
						$data['type'] = "agent";
						$data['ip_address'] = $ip;
						$data['user_name'] = $clientData['RegistrationMaster']['company_name'];
						$data['page_name'] = "Login";
						$data['page_url'] = "Login";
						$data['hit_time'] = date("Y-m-d H:i:s");

						$this->LoginLog->save($data);
                        
                        //$this->redirect(array('controller' => 'Agents', 'action' => 'index'));
                         $this->redirect(array('controller' => 'homes2', 'action' => 'index'));
                }
                else{   
                    $this->Session->setFlash('Invalid email/mobile or password.');
                    $this->redirect(array('action' => 'login'));
                }
            }
            
        }
	
	}
       
	public function forgot_password(){
            $this->layout='homeLayout';	
            if($this->request->is('post')){
		$data=$this->request->data['user_email'];
                $result = $this->RegistrationMaster->find('first',array('conditions' =>array('or' => array('email' =>$data,'phone_no' =>$data))));
                if(empty($result)){
                        $this->Session->setFlash('<span style="color:red;" >This data not exist in database.</span>');	
                }
                else if(!empty($result) && $result['RegistrationMaster']['email_verify'] ==="no"){
                        $this->Session->setFlash('<span style="color:red;" >Please activate your account then reset password.</span>');	
                }
                else{
                    $id=$result['RegistrationMaster']['company_id'];
                    $name=$result['RegistrationMaster']['auth_person'];
                    $email=$result['RegistrationMaster']['email'];

                    $base = Router::fullbaseUrl().$this->webroot."ClientActivations/reset_password?resetlink=".base64_encode($id);
                    require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
                    $EmailText='';
                    $to=array('Email'=>$email,'Name'=>$email);	
                    $from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                    $reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                    $Sub="Dialdesk: Reset your password link.";
                    $EmailText.="Dear $name,<br/><br/>";
                    $EmailText.="To reset your new password, please click on this link.If your browser does not open it, please copy and paste it in your browser's address bar.<br/><br/>";
                    $EmailText.="<a href='$base'>$base</a><br/><br/>";

                    $emaildata=array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);
                    if(send_email($emaildata)){
                            $this->Session->setFlash('<span style="color:green;">Your password reset link send to your e-mail address.</span>');
                    }
                    else{
                            $this->Session->setFlash('<span style="color:red;">Your email process failed. Try after sometime.</span>');
                    }
                }
		$this->redirect(array('controller' => 'ClientActivations', 'action' => 'forgot_password'));	
            }
	}
        
        
        
	
	public function reset_password(){
		$this->layout='homeLayout';	
		if(isset($_REQUEST['resetlink']) && $_REQUEST['resetlink'] !=""){
			$this->set('cid',base64_decode($_REQUEST['resetlink']));	
		}
		else{
			$this->set('cid','');		
		}
	}
	
	public function save_password(){
		if($this->request->is('post')){
			if(trim($this->request->data['cid']) !=""){
				$cid=$this->request->data['cid'];
				$new_password=$this->request->data['new_password'];
				$confirm_password=$this->request->data['confirm_password'];
				$dataArr=array('password'=>"'".addslashes(trim($new_password))."'");
				$this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$cid));
				$this->Session->setFlash('<span style="color:green;">Your Password Reset Successfully.</span>');
			}
			else{
				$this->Session->setFlash('<span style="color:red;">Sorry your reset password link not correct please click or copy reset password link again from your mail.</span>');	
			}
			$this->redirect(array('controller' => 'ClientActivations', 'action' => 'reset_password'));
		}	
	}
	
	
	
	
		
	public function logout() {

		$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

		$data['user_id'] = $this->Session->read("companyid");
		$data['type'] = $this->Session->read('role');
		$data['ip_address'] = $ip;
		$data['user_name'] = $this->Session->read("companyname");
		$data['page_name'] = "LogOut";
		$data['page_url'] = "LogOut";
		$data['hit_time'] = date("Y-m-d H:i:s");

		$this->LoginLog->save($data);

		//agent session data 
		$this->Session->delete('agent_userid');
		$this->Session->delete('agent_username');
		$this->Session->delete('agent_company');
		$this->Session->delete('company_id');
		//client session data
   		$this->Session->delete('companyid');
		$this->Session->delete('companyname');
                $this->Session->delete('clientstatus');
		$this->Session->delete('username');
		$this->Session->delete('email');
		$this->Session->delete('role');
                $this->Session->delete('agent_rights');
   		$this->Session->destroy();
		$this->redirect(array('action' => 'login'));
    }
	
	public function delete_otp_session() {
		$this->Session->delete('otp');
		die;
    }
	
	public function company_registration() {
            $this->layout='homeLayout';
            $state=$this->StateMaster->find('list',array('fields'=>array("StateName","StateName")));
            $this->set('state',$state);
                
            /*
            $state=$this->StateMaster->find('list',array('fields'=>array("Id","StateName")));
            $city =$this->CityMaster->find('list',array('fields'=>array("CityName","CityName")));
            $this->set('city',$city);  
            */
                
            $companyid=$this->Session->read('tmpid');
            $tmpData=$this->TmpRegistrationMaster->find('first',array('conditions'=>array('company_id'=>$companyid)));
            
            if(!empty($tmpData)){
               // $stateid=$this->StateMaster->find('first',array('fields'=>array("Id"),'conditions'=>array('StateName'=>$tmpData['TmpRegistrationMaster']['state'])));
                //$this->set('stateid',$stateid);
                $this->set('data',$tmpData);
            }
            
	}
	
	public function get_city(){
		$this->layout='ajax';
		if($_REQUEST['id']){
			$city =$this->CityMaster->find('list',array('fields'=>array("CityName","CityName"),'conditions'=>array('StateId'=>$_REQUEST['id'])));
			$this->set('city',$city);
		}
	}
	
	
	public function checkSmtpValidation(){
    	if($_REQUEST['email']){
			require_once(APP . 'Lib' . DS . 'check_smtp' . DS . 'smtp_validateEmail.class.php');
			$email = $_REQUEST['email'];
			$sender = 'user@mydomain.com';
			$SMTP_Validator = new smtp_validateEmail();
			$SMTP_Validator->debug = false;
			$results = $SMTP_Validator->validate(array($email), $sender);	
			if ($results[$email]) {
				echo json_encode($results);die;
			}
			else{
			echo json_encode(array($email=>false));die;
			}
		}
	}
	
	public function check_exist_email(){
		$email = $_REQUEST['email'];
		$result = $this->RegistrationMaster->find('first',array('conditions' =>array('email' => $email)));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
       
	public function check_exist_phone(){
		$phone_no = $_REQUEST['phone_no'];
		$result = $this->RegistrationMaster->find('first',array('conditions' =>array('phone_no' => $phone_no)));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
	
	public function check_exist_company(){
		$company_name = $_REQUEST['company_name'];
		$result = $this->RegistrationMaster->find('first',array('conditions' =>array('company_name' => $company_name)));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        	
	public function sendotp(){
		if(isset($_REQUEST['phone'])){
			//echo $otp = rand(100000,999999);
			echo $otp = "123456";
			$num['ReceiverNumber'] = $_REQUEST['phone'];
			$num['SmsText'] = "Dear Candidate your one time password-OTP is ".$otp."
Ispark";
			$this->send_sms($num);
			$this->Session->write("otp",$otp);
			die; 
		}	
	}
        
        public function send_forgot_otp(){
            if(isset($_REQUEST['phone'])){
                //$otp = rand(100000,999999);
                $otp = "123456";
                $num['ReceiverNumber'] = $_REQUEST['phone'];
                $num['SmsText'] = "Dear Candidate your one time password-OTP is ".$otp."
Ispark";
                $this->send_sms($num);
                $this->Session->write("forgot_otp",$otp);
                die; 
            }	
	}
        public function delete_forgot_otp() {
            $this->Session->delete('forgot_otp');
            die;
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
				'send'=>'Ispark',
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

	
	public function matchotp(){
		if(isset($_REQUEST['otpval'])){
			$otp = $this->Session->read('otp');
			if($_REQUEST['otpval'] ==$otp){
				$this->Session->write("verify_no",'yes');
				echo '1';	
			}
			else{
				echo '';
			}
			die;
		}	
	}
        
        public function matchotp_forgot_otp(){
		if(isset($_REQUEST['otpval'])){
			$otp = $this->Session->read('forgot_otp');
			if($_REQUEST['otpval'] ==$otp){
                            $result = $this->RegistrationMaster->find('first',array('conditions' =>array('phone_no' => $_REQUEST['phone_no'])));
                            $base = Router::fullbaseUrl().$this->webroot."ClientActivations/reset_password?resetlink=".base64_encode($result['RegistrationMaster']['company_id']);          
                            echo $base;	
			}
			else{
                            echo '';
			}
			die;
		}	
	}
	
	public function save_firstForm_data(){
		if(isset($_REQUEST['company_name'])){
			$companyid=$this->Session->read('tmpid');
                        
			//$state=$this->StateMaster->find('first',array('fields' => array( 'StateName'),'conditions'=>array('Id'=>$_REQUEST['state'])));
			//$stateName=$state['StateMaster']['StateName'];
			
			$data=array(
				'sameAs'=>addslashes(trim($_REQUEST['sameas'])),
				'company_name'=>addslashes(trim($_REQUEST['company_name'])),
				'reg_office_address1'=>addslashes(trim($_REQUEST['office_address1'])),
				'reg_office_address2'=>addslashes(trim($_REQUEST['office_address2'])),
				'city'=>addslashes(trim($_REQUEST['city'])),
				'state'=>addslashes(trim($_REQUEST['state'])),
				'gst_no'=>addslashes(trim($_REQUEST['gst_no'])),
				'pincode'=>addslashes(trim($_REQUEST['pincode'])),
				'auth_person'=>addslashes(trim($_REQUEST['authorised_person'])),
				'designation'=>addslashes(trim($_REQUEST['designation'])),
				'phone_no'=>addslashes(trim($_REQUEST['phone'])),
				'email'=>addslashes(trim($_REQUEST['email'])),
				'password'=>addslashes(trim($_REQUEST['password'])),
				'comm_address1'=>addslashes(trim($_REQUEST['comm_address1'])),
				'comm_address2'=>addslashes(trim($_REQUEST['comm_address2'])),
				'comm_city'=>addslashes(trim($_REQUEST['comm_city'])),
				'comm_state'=>addslashes(trim($_REQUEST['comm_state'])),
				'comm_pincode'=>addslashes(trim($_REQUEST['comm_pincode']))
				);
				
				if($this->TmpRegistrationMaster->find('first',array('fields'=>array('company_id'),'conditions'=>array('company_id'=>$companyid)))){	
					$dataArr=array(
						'sameAs'=>"'".addslashes(trim($_REQUEST['sameas']))."'",
						'company_name'=>"'".addslashes(trim($_REQUEST['company_name']))."'",
						'reg_office_address1'=>"'".addslashes(trim($_REQUEST['office_address1']))."'",
						'reg_office_address2'=>"'".addslashes(trim($_REQUEST['office_address2']))."'",
						'city'=>"'".addslashes(trim($_REQUEST['city']))."'",
						'state'=>"'".addslashes(trim($_REQUEST['state']))."'",
						'gst_no'=>"'".addslashes(trim($_REQUEST['gst_no']))."'",
						'pincode'=>"'".addslashes(trim($_REQUEST['pincode']))."'",
						'auth_person'=>"'".addslashes(trim($_REQUEST['authorised_person']))."'",
						'designation'=>"'".addslashes(trim($_REQUEST['designation']))."'",
						'phone_no'=>"'".addslashes(trim($_REQUEST['phone']))."'",
						'email'=>"'".addslashes(trim($_REQUEST['email']))."'",
						'password'=>"'".addslashes(trim($_REQUEST['password']))."'",
						'comm_address1'=>"'".addslashes(trim($_REQUEST['comm_address1']))."'",
						'comm_address2'=>"'".addslashes(trim($_REQUEST['comm_address2']))."'",
						'comm_city'=>"'".addslashes(trim($_REQUEST['comm_city']))."'",
						'comm_state'=>"'".addslashes(trim($_REQUEST['comm_state']))."'",
						'comm_pincode'=>"'".addslashes(trim($_REQUEST['comm_pincode']))."'"
						);	
				
					$this->TmpRegistrationMaster->updateAll($dataArr,array('company_id'=>$companyid));
					echo $companyid;die;
				}
				else{
					$this->TmpRegistrationMaster->save($data);
					$resid=$this->TmpRegistrationMaster->getLastInsertId();
					$this->Session->write('tmpid',$resid);
					echo $this->Session->read('tmpid');die;
				}		
		}
	}
	
	public function save_secondForm_data(){
		if(isset($_REQUEST['contact_person1'])){
			$companyid=$this->Session->read('tmpid');				
			if($this->TmpRegistrationMaster->find('first',array('fields'=>array('company_id'),'conditions'=>array('company_id'=>$companyid)))){	
				$dataArr=array(
						'contact_person1'=>"'".addslashes(trim($_REQUEST['contact_person1']))."'",
						'cp1_designation'=>"'".addslashes(trim($_REQUEST['cp1_designation']))."'",
						'cp1_phone'=>"'".addslashes(trim($_REQUEST['cp1_phone']))."'",
						'cp1_email'=>"'".addslashes(trim($_REQUEST['cp1_email']))."'",
						'contact_person2'=>"'".addslashes(trim($_REQUEST['contact_person2']))."'",
						'cp2_designation'=>"'".addslashes(trim($_REQUEST['cp2_designation']))."'",
						'cp2_phone'=>"'".addslashes(trim($_REQUEST['cp2_phone']))."'",
						'cp2_email'=>"'".addslashes(trim($_REQUEST['cp2_email']))."'",
						'contact_person3'=>"'".addslashes(trim($_REQUEST['contact_person3']))."'",
						'cp3_designation'=>"'".addslashes(trim($_REQUEST['cp3_designation']))."'",
						'cp3_phone'=>"'".addslashes(trim($_REQUEST['cp3_phone']))."'",
						'cp3_email'=>"'".addslashes(trim($_REQUEST['cp3_email']))."'"
						);	
				
					$this->TmpRegistrationMaster->updateAll($dataArr,array('company_id'=>$companyid));
					echo $companyid;die;
				}	
		}
	}
	
	
	
	public function save_company(){
		if ($this->request->is('post')){
			$companyid=$this->Session->read('tmpid');
			$ip = $_SERVER['REMOTE_ADDR'];
			$status='D';
			$email_verify='no';
                        
			$tmpData=$this->TmpRegistrationMaster->find('first',array('conditions'=>array('company_id'=>$companyid)));
			$tmpData = Hash::Remove($tmpData['TmpRegistrationMaster'],'company_id');
			$this->RegistrationMaster->save($tmpData);
			$compid=$this->RegistrationMaster->getLastInsertId();
				
			if (!file_exists(WWW_ROOT.'upload/client_file/client_'.$compid)) {
    			mkdir(WWW_ROOT.'upload/client_file/client_'.$compid, 0777, true);
			}
			$path=WWW_ROOT.'upload/client_file/client_'.$compid.'/';
			
			$data=$this->upload_multiple_file($filename='userfile',$path);
			$data2=$this->upload_multiple_file($filename='userfile2',$path);
			$data3=$this->upload_multiple_file($filename='userfile3',$path);
			$data4=$this->upload_multiple_file($filename='userfile4',$path);
			$data5=$this->upload_multiple_file($filename='userfile5',$path);
                        $data6=$this->upload_multiple_file($filename='userfile6',$path);
                        $data7=$this->upload_multiple_file($filename='userfile7',$path);
				
			$dataArr=array(
				'incorporation_certificate'=>"'".$data."'",
				'pancard'=>"'".$data2."'",
				'bill_address_prof'=>"'".$data3."'",
				'authorized_id_prof'=>"'".$data4."'",
				'auth_person_address_prof'=>"'".$data5."'",
                                'company_logo'=>"'".$data6."'",
                                'other_documents'=>"'".$data7."'",
				'status'=>"'".$status."'",
				'email_verify'=>"'".$email_verify."'",
				'ip'=>"'".$ip."'");	
				
			$this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$compid));

				$this->TmpRegistrationMaster->deleteAll(array('company_id'=>$companyid),false);
				$this->Session->delete('tmpid');
				$this->Session->delete('verify_no');
   				$this->Session->destroy();
				
				$base = Router::fullbaseUrl().$this->webroot."ClientActivations/verify_email?ver=";
				$company_id = base64_encode($compid);
				$name=addslashes(trim($this->params['data']['auth_person']));
				$user_email = addslashes(trim($this->params['data']['email']));
				
				require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
		
				$EmailText='';
				$to=array('Email'=>$user_email,'Name'=>$user_email);	
				$from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
				$reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
				$Sub="Dialdesk: Verify your account.";
				$EmailText.="Dear $name,<br/><br/>";
				$EmailText.="To verify your account, please click on following link.If your browser does not open it, please copy and paste it in your browser's address bar.<br/><br/>";
				$EmailText.="<a href='$base$company_id' style='text-decoration:none;'><button style='cursor:pointer;' >Verify Account</button></a><br/><br/>";
				
                                
                                
				$emaildata=array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);
				if(send_email($emaildata)){
					$this->redirect(array('controller' => 'ClientActivations', 'action' => 'message', '?' => array('t' =>'ess')));
				}
				else{
					$this->redirect(array('controller' => 'ClientActivations', 'action' => 'message', '?' => array('t' =>'ens')));
				}
		}
		
	}
	
	public function upload_multiple_file($filename,$path){
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
			return $data=implode(',',$fileName);
		}
		else{
			$data=NULL;
		}
	}
	
	public function verify_email(){
		if(isset($_REQUEST['ver']) && $_REQUEST['ver'] !=''){
			$compid = base64_decode($_REQUEST['ver']);
			$result = $this->RegistrationMaster->find('first',array('conditions' =>array('company_id' => $compid)));
			if (!empty($result) ) {
				if($result['RegistrationMaster']['email_verify'] !='yes'){                                           
					$this->RegistrationMaster->updateAll(array('email_verify'=>'"yes"'),array('company_id'=>$compid));
					$this->redirect(array('controller' => 'ClientActivations', 'action' => 'message', '?' => array('t' =>'evs')));
				}
				else{
					$this->redirect(array('controller' => 'ClientActivations', 'action' => 'message', '?' => array('t' =>'eau')));
				}
			}
			else{
				$this->redirect(array('controller' => 'ClientActivations', 'action' => 'index'));
			}
		}
		else{
			$this->redirect(array('controller' => 'ClientActivations', 'action' => 'index'));
		}
	}
	
	public function message(){
		$this->layout='homeLayout';
		if(isset($_REQUEST['t']) && $_REQUEST['t']=='ess'){
			$data='<span style="font-size:17px;">Thank you for sign up with us, you will get a link on your email to activate your account.<span>';
		}
		else if(isset($_REQUEST['t']) && $_REQUEST['t']=='ens'){
			$data='<h3>Sorry</h3>Your email process failed. Try after sometime.';
		}
		else if(isset($_REQUEST['t']) && $_REQUEST['t']=='evs'){
			$data='<h3>Thank You</h3>Your account hasbeen verified.';
                        $this->set('type',$_REQUEST['t']);
		}
		else if(isset($_REQUEST['t']) && $_REQUEST['t']=='eau'){
			$data='<h3>Thank You</h3>Your account hasbeen already verified.';
		}
		else{
			$data='';
                       
		}
		$this->set('msg',$data);
                
	}	
}
?>