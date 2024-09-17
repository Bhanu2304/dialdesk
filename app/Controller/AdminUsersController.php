<?php
class AdminUsersController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('Admin');
	

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
			'update_login',
                        'manage_admin_rights');
		if(!$this->Session->check("admin_id")){
			return $this->redirect(array('controller'=>'Admins','action' => ''));
		}
    }
	
	public function index() {
            $this->layout='user';         
            $result = $this->Admin->find('all',array('order'=>"Admin.name ASC"));
           //print_r($result);die;
            $this->set('data',$result);
            $page1 = $this->PagesMaster1->find('all');
                
            $menu = array(
                'menus' => array(),
                'parent_menus' => array(),
            );
            
            foreach($page1 as $row){
                $menu['menus'][$row['PagesMaster1']['id']] = $row['PagesMaster1'];
                $menu['parent_menus'][$row['PagesMaster1']['parent_id']][] = $row['PagesMaster1']['id'];
            }
            
            
            $UserRight = $this->buildMenu('0', $menu);
            $this->set('UserRight',$UserRight);
            
	}
        
        public function view_edit_login(){
            if ($this->request->is('post')) {
                $this->layout='ajax';
                $data=$this->request->data;
                //$data['user_active']='1';
                $user=$this->Admin->find('first',array('conditions' =>$data));
                $userAss=explode(',',$user['Admin']['user_right']);
                
                $this->set('get_record',$user);
                $this->set('save_record',$userAss);
                
                
                
                
                
                }   
        }
        
        function editBuildMenu($parent, $menu,$userAss) {
            $html = "";
            $char=" ";
            if (isset($menu['parent_menus'][$parent])) {
                foreach ($menu['parent_menus'][$parent] as $menu_id) {
                    if(in_array($menu['menus'][$menu_id]['id'],$userAss)){$check='checked';}else{$check='';}
                    if (!isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' ".$check." id='e" . $menu['menus'][$menu_id]['id'] . "' value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name']."</label></div></li>";
                    }
                    if (isset($menu['parent_menus'][$menu_id])){
                        $html .= "<li id=\"b{$menu['menus'][$menu_id]['id']}\"><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' ".$check." id='be" . $menu['menus'][$menu_id]['id'] . "' onchange=".'"show_child_edit('."'".$menu['menus'][$menu_id]['id']."'".",'b','be'".')"'." value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name'];
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
                        $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' id='" . $menu['menus'][$menu_id]['id'] . "' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name']."</label></div></li>";
                    }
                    if (isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li id=\"a{$menu['menus'][$menu_id]['id']}\"><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]' id='" . $menu['menus'][$menu_id]['id'] . "' onchange=".'"show_child('."'".$menu['menus'][$menu_id]['id']."'".",'a',''".')"'." value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name'];
                        $html .= "<ol class='user-tree'>";
                        $html .= $this->buildMenu($menu_id, $menu); 
                        $html .= "</ol>";
                        $html .= "</label></div></li>";
                        
                        //print_r($menu['parent_menus']);exit;
                        
                    } 
                    
                }
            }
            return $html;
        }
         
        
	
	public function update_login(){
            if ($this->request->is('post')) {
                $id=$_REQUEST['loginid'];
                
                
                
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_id');
                }
                
                $data=array(
                    'name'=>"'".addslashes(trim($_REQUEST['name']))."'",
                    'phone'=>"'".addslashes(trim($_REQUEST['phone']))."'",
                    'username'=>"'".addslashes(trim($_REQUEST['email']))."'",
                    'designation'=>"'".addslashes(trim($_REQUEST['designation']))."'",
                    'password'=>"'".addslashes(trim($_REQUEST['password']))."'",
                    'password2'=>"'".addslashes(trim($_REQUEST['password']))."'",
                    'user_active'=>"'".$_REQUEST['user_active']."'",
                    'update_user'=>"'".$update_user."'"
                    );

                $this->Admin->updateAll($data,array('id'=>$id));
                echo "1";die;
            }
	}
	
	public function delete_user(){
		$id  = $this->request->query['id'];
                
                
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $this->Admin->query("INSERT INTO `tbl_user_history` (
                       id,create_id,`name`,phone,username,designation,user_right,`password`,password2,user_active,
                update_user,update_date) 
                SELECT id,create_id,`name`,phone,username,designation,user_right,`password`,password2,user_active,
                '$update_user',NOW() FROM tbl_user WHERE id=$id");
                
		$this->Admin->delete(array('id'=>$id,'create_id' => $this->Session->read('admin_id')));
		$this->redirect(array('action' => 'index'));
	}
	
	public function check_exist_email(){
		$email = $_REQUEST['email'];
		$result = $this->Admin->find('first',array('conditions' =>array('username' => $email,'create_id'=>$this->Session->read('admin_id'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
        public function checkexistmail(){
		$email = $_REQUEST['getdata'];
		$result = $this->Admin->find('first',array('conditions' =>array('username' => $email,'create_id'=>$this->Session->read('admin_id'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
        public function check_exist_phone(){
		$phone = $_REQUEST['getdata'];
		$result = $this->Admin->find('first',array('conditions' =>array('phone' => $phone,'create_id'=>$this->Session->read('admin_id'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
           public function edit_exist_email(){
		$email = $_REQUEST['getdata'];
		$result = $this->Admin->find('first',array('conditions' =>array('username' => $email,'id !='=>$_REQUEST['id'],'create_id'=>$this->Session->read('admin_id'))));
		if (!empty($result) ) {
			echo '1';die;
		}
		else{
			echo '';die;
		}
	}
        
        public function edit_exist_phone(){
		$phone = $_REQUEST['getdata'];
		$result = $this->Admin->find('first',array('conditions' =>array('phone' => $phone,'id !='=>$_REQUEST['id'],'create_id'=>$this->Session->read('admin_id'))));
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
			//$user_right=implode(',',$_REQUEST['page_assign']);
                        $ch = implode("','",$_REQUEST['page_assign']);
                        
                        $q1 = "SELECT id,parent_id from pages_master1 WHERE id in ('$ch')";
                        
                      // echo $q1 = substr($q1, 0, -4); exit;

                        $dd = $this->PagesMaster1->query($q1);
                            //print_r($dd);exit;
                        $p = array();
                        //$ch = array();
                        $child = "";
                        foreach ($dd as $row) {
                            if ($row['pages_master1']['parent_id'] > 0) {
                                //$p.=$row['pages_master_ispark']['parent_id'].",";
                                array_push($p, $row['pages_master1']['parent_id']);
                                $child.=$row['pages_master1']['id'] . ",";
                                //array_push($ch,$row['pages_master_ispark']['id']);
                            } else {
                                //$p.=$row['pages_master_ispark']['id'].",";
                                array_push($p, $row['pages_master1']['id']);
                            }
                        }

                        $pp = implode(",", array_unique($p)); 
                        
                        if($this->Session->read('role') =="admin"){
                            $update_user=$this->Session->read('admin_id');
                        }
                        
			$inputData=array(
				'create_id'=>$this->Session->read('admin_id'),
				'name'=>addslashes(trim($_REQUEST['name'])),
				'phone'=>addslashes(trim($_REQUEST['phone'])),
				'UserName'=>addslashes(trim($_REQUEST['email'])),
                                'Email'=>addslashes(trim($_REQUEST['email'])),
				'designation'=>addslashes(trim($_REQUEST['designation'])),
				'access'=>$child,
                                'parent_access'=>$pp,
                                //'user_right'=>$user_right,
				'Password'=>addslashes(trim($_REQUEST['password'])),
				'user_active'=>'1',
                                'user_type'=>'admin',
                                'update_user'=>$update_user
			);

			if($this->Admin->save($inputData)){
                                echo '1';die;
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
        
        
        public function manage_admin_rights()
        {
            $this->layout='user';
            $result = $this->Admin->find('all',array('conditions' =>array('user_active' =>'1')));
            $this->set('data',$result);
            
            
            
        }
}
?>