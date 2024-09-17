<?php
class UserManagesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','IvrMaster','PagesMaster','LogincreationMaster','IncallScenarios','OutcallCampaign');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow(
			'checkSmtpValidation',
                        'checkexistmail',
                        'check_exist_phone',
                        'edit_exist_email',
                        'edit_exist_phone',
			'save_login_creation',
                        'fetchCategoryTree',
			'check_exist_email',
			'sendotp','send_sms',
			'matchotp',
			'message',
			'view_created_login',
			'edit_login',
			'delete_user',
                        'geturm_chield',
                        'view_edit_login',
			'update_user_access');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
            $this->layout='user';         
            $UserList = $this->LogincreationMaster->find('list',array('fields'=>array('id','username'),'conditions' =>array('create_id' => $this->Session->read('companyid'))));
            $this->set('UserList',$UserList);
            
            $Campaign = $this->OutcallCampaign->find('list',array('fields'=>array('id','CampaignName'),'conditions' =>array('ClientId' => $this->Session->read('companyid'))));
            $this->set('Campaign',$Campaign);
            
            if(isset($_REQUEST['type']) && $_REQUEST['type'] !=""){
                $this->set('type',$_REQUEST['type']);
            }
            else{
               $this->set('type',array()); 
            }
            
            if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
                $UserArr = $this->LogincreationMaster->find('first',array('conditions' =>array('id'=>$_REQUEST['id'],'create_id' => $this->Session->read('companyid'))));
                $page = $this->IncallScenarios->find('all',array('conditions' =>array('Client' => $this->Session->read('companyid'))));
                
                $menu = array(
                    'menus' => array(),
                    'parent_menus' => array(),
                );

                foreach($page as $row){
                    $menu['menus'][$row['IncallScenarios']['id']] = $row['IncallScenarios'];
                    $menu['parent_menus'][$row['IncallScenarios']['parent_id']][] = $row['IncallScenarios']['id'];
                }

                if(empty($UserArr)){
                    
                    $this->set('UserRight',$this->buildMenu(NULL, $menu));
                }
                else{
                    
                    $userAss=explode(',',$UserArr['LogincreationMaster']['inbound_access']);
                    $this->set('UserRight',$this->editBuildMenu(NULL, $menu,$userAss));                  
                }

                $outboundAccess=explode(',',$UserArr['LogincreationMaster']['outbound_access']);
                $this->set('outboundAccess',$outboundAccess);
                $this->set('UserId',$_REQUEST['id']);
            }
	}
        
        
        public function view_edit_login(){
            if ($this->request->is('post')) {
                $this->layout='ajax';
                $data=$this->request->data;
                $data['create_id']=$this->Session->read('companyid');
                $user=$this->LogincreationMaster->find('first',array('conditions' =>$data));
                $userAss=explode(',',$user['LogincreationMaster']['user_right']);
                
                $this->set('get_record',$user);
                
                $page = $this->PagesMaster->find('all');
                
                $menu = array(
                    'menus' => array(),
                    'parent_menus' => array(),
                );

                foreach($page as $row){
                    $menu['menus'][$row['PagesMaster']['id']] = $row['PagesMaster'];
                    $menu['parent_menus'][$row['PagesMaster']['parent_id']][] = $row['PagesMaster']['id'];
                }
                $this->set('UserRight',$this->editBuildMenu(NULL, $menu,$userAss));   
                }   
        }
        
        function editBuildMenu($parent, $menu,$userAss) {
            $html = "";
            $char=" ";
            if (isset($menu['parent_menus'][$parent])) {
                foreach ($menu['parent_menus'][$parent] as $menu_id) {
                    if(in_array($menu['menus'][$menu_id]['id'],$userAss)){$check='checked';}else{$check='';}
                    if (!isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' ".$check."  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['ecrName']."</label></div></li>";
                    }
                    if (isset($menu['parent_menus'][$menu_id])){
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' ".$check."  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['ecrName'];
                        $html .= "<ol class='user-tree'>";
                        $html .= $this->editBuildMenu($menu_id, $menu,$userAss);
                        $html .= "</ol>";
                        $html .= "</label></div></li>";
                    }
                }
            }
            return $html;
        }

          function buildMenu($parent, $menu) {
            $html = "";
            $char=" ";
            if (isset($menu['parent_menus'][$parent])) {
                foreach ($menu['parent_menus'][$parent] as $menu_id) {
                    if (!isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['ecrName']."</label></div></li>";
                    }
                    if (isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['ecrName'];
                        $html .= "<ol class='user-tree'>";
                        $html .= $this->buildMenu($menu_id, $menu);
                        $html .= "</ol>";
                        $html .= "</label></div></li>";
                    }
                }
            }
            return $html;
        }
         
        
	
	public function update_user_access(){
            if ($this->request->is('post')) {
                $data=$this->request->data['UserManages'];
                $id=$data['UserId'];
                $type=$data['CallType'];
                
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }

                if($data['CallType'] =="Inbound"){
                    $inbound_access=implode(',',$this->request->data['selectAll']);
                    $data=array('inbound'=>"'".$data['CallType']."'",'inbound_access'=>"'".$inbound_access."'",'update_user'=>"'".$update_user."'");   
                }
                else if($data['CallType'] =="Outbound"){
                    $outbound_access=implode(',',$this->request->data['selectCam']);
                    $data=array('outbound'=>"'".$data['CallType']."'",'outbound_access'=>"'".$outbound_access."'",'update_user'=>"'".$update_user."'");     
                }
                
                //print_r($data);die;
                
                $this->LogincreationMaster->updateAll($data,array('id'=>$id,'create_id' => $this->Session->read('companyid')));
                $this->Session->setFlash("User Access create successfully.");
                return $this->redirect(array('controller'=>'UserManages','?' =>array('id'=>$id,'type'=>$type)));
            }
	}
        
        
	
	public function delete_user(){
		$id  = $this->request->query['id'];
		$this->LogincreationMaster->delete(array('id'=>$id,'create_id' => $this->Session->read('companyid')));
		$this->redirect(array('action' => 'index'));
	}
	
	public function check_exist_email(){
		$email = $_REQUEST['email'];
		$result = $this->LogincreationMaster->find('first',array('conditions' =>array('username' => $email,'create_id'=>$this->Session->read('companyid'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
        public function checkexistmail(){
		$email = $_REQUEST['getdata'];
		$result = $this->LogincreationMaster->find('first',array('conditions' =>array('username' => $email,'create_id'=>$this->Session->read('companyid'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
        public function check_exist_phone(){
		$phone = $_REQUEST['getdata'];
		$result = $this->LogincreationMaster->find('first',array('conditions' =>array('phone' => $phone,'create_id'=>$this->Session->read('companyid'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
           public function edit_exist_email(){
		$email = $_REQUEST['getdata'];
		$result = $this->LogincreationMaster->find('first',array('conditions' =>array('username' => $email,'id !='=>$_REQUEST['id'],'create_id'=>$this->Session->read('companyid'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
        public function edit_exist_phone(){
		$phone = $_REQUEST['getdata'];
		$result = $this->LogincreationMaster->find('first',array('conditions' =>array('phone' => $phone,'id !='=>$_REQUEST['id'],'create_id'=>$this->Session->read('companyid'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
	
	public function save_login_creation(){
		$this->layout='ajax';
		if($_REQUEST['name']){
			$user_right=implode(',',$_REQUEST['page_assign']);
			$inputData=array(
				'create_id'=>$this->Session->read('companyid'),
				'name'=>addslashes(trim($_REQUEST['name'])),
				'phone'=>addslashes(trim($_REQUEST['phone'])),
				'username'=>addslashes(trim($_REQUEST['email'])),
				'designation'=>addslashes(trim($_REQUEST['designation'])),
				'user_right'=>$user_right,
				'password'=>addslashes(trim($_REQUEST['password'])),
				'password2'=>addslashes(trim($_REQUEST['password'])),
			);

			if($this->LogincreationMaster->save($inputData)){
           
				$name=addslashes(trim($_REQUEST['name']));
				$password=addslashes(trim($_REQUEST['password']));
				$user_email = addslashes(trim($_REQUEST['email']));
				
				require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
		
				$EmailText='';
				$to=array('Email'=>$user_email,'Name'=>$user_email);	
				$from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
				$reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
				$Sub="Dialdesk:Check Your Account.";
				$EmailText.="Dear $name,<br/><br/>";
				$EmailText.="Your account successfully created and your password is-.<br/><br/>";
				$EmailText.="$password<br/><br/>";
				
				$emaildata=array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);
				
				if(send_email($emaildata)){
					echo '1';die;
				}
				else{
					echo '';die;
				}
                                
                               
			}
		
			
		}
	}

}
?>