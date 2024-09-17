<?php
class LoginCreationsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','IvrMaster','PagesMaster','LogincreationMaster');
	
	
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
			'update_login');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
            $this->layout='user';         
            $result = $this->LogincreationMaster->find('all',array('conditions' =>array('create_id' => $this->Session->read('companyid'))));
            $this->set('data',$result);
            $page = $this->PagesMaster->find('all');
                
            $menu = array(
                'menus' => array(),
                'parent_menus' => array(),
            );
            
            foreach($page as $row){
                $menu['menus'][$row['PagesMaster']['id']] = $row['PagesMaster'];
                $menu['parent_menus'][$row['PagesMaster']['parent_id']][] = $row['PagesMaster']['id'];
            }
            $this->set('UserRight',$this->buildMenu(NULL, $menu));
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
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' ".$check."  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name']."</label></div></li>";
                    }
                    if (isset($menu['parent_menus'][$menu_id])){
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' ".$check."  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name'];
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
                        $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name']."</label></div></li>";
                    }
                    if (isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name'];
                        $html .= "<ol class='user-tree'>";
                        $html .= $this->buildMenu($menu_id, $menu);
                        $html .= "</ol>";
                        $html .= "</label></div></li>";
                    }
                }
            }
            return $html;
        }
         
        
	
	public function update_login(){
            if ($this->request->is('post')) {
                $id=$_REQUEST['loginid'];
                $user_right=implode(',',$_REQUEST['page_assign']);
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $data=array(
                    'name'=>"'".addslashes(trim($_REQUEST['name']))."'",
                    'phone'=>"'".addslashes(trim($_REQUEST['phone']))."'",
                    'username'=>"'".addslashes(trim($_REQUEST['email']))."'",
                    'designation'=>"'".addslashes(trim($_REQUEST['designation']))."'",
                    'password'=>"'".addslashes(trim($_REQUEST['password']))."'",
                    'password2'=>"'".addslashes(trim($_REQUEST['password']))."'",
                    'user_right'=>"'".$user_right."'",
                    'update_user'=>"'".$update_user."'"
                    );

                $this->LogincreationMaster->updateAll($data,array('id'=>$id,'create_id' => $this->Session->read('companyid')));
                echo "1";die;
            }
	}
	
	public function delete_user(){
		$id  = $this->request->query['id'];
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $this->LogincreationMaster->query("INSERT INTO `logincreation_master_history` (
                id,create_id,`name`,phone,username,designation,user_right,`password`,password2,active,inbound,inbound_access,outbound,
                outbound_access,update_user,update_date) 
                SELECT id,create_id,`name`,phone,username,designation,user_right,`password`,password2,active,inbound,inbound_access,outbound,
                outbound_access,'$update_user',NOW() FROM logincreation_master WHERE id=$id");
                
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
                        
                        if($this->Session->read('role') =="client"){
                            $update_user=$this->Session->read('email');
                        }
                        else if($this->Session->read('role') =="agent"){
                            $update_user=$this->Session->read('agent_username');
                        }
                        if($this->Session->read('role') =="admin"){
                            $update_user=$this->Session->read('admin_name');
                        }
                        
			$inputData=array(
				'create_id'=>$this->Session->read('companyid'),
				'name'=>addslashes(trim($_REQUEST['name'])),
				'phone'=>addslashes(trim($_REQUEST['phone'])),
				'username'=>addslashes(trim($_REQUEST['email'])),
				'designation'=>addslashes(trim($_REQUEST['designation'])),
				'user_right'=>$user_right,
				'password'=>addslashes(trim($_REQUEST['password'])),
				'password2'=>addslashes(trim($_REQUEST['password'])),
                                'update_user'=>$update_user
			);

			if($this->LogincreationMaster->save($inputData)){
           
				$name=addslashes(trim($_REQUEST['name']));
				$password=addslashes(trim($_REQUEST['password']));
				$user_email = addslashes(trim($_REQUEST['email']));
				
				require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
		
				$EmailText='';
				$to=array('Email'=>$user_email,'Name'=>$user_email);	
				$from=array('Email'=>"ispark@teammas.in",'Name'=>'Dialdesk');
				$reply=array('Email'=>"ispark@teammas.in",'Name'=>'Dialdesk');
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