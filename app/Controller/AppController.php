<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {
    public $components = array(
        'Session','RequestHandler',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'ClientActivations',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'ClientActivations',
                'action' => 'index',
                'home'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            )
        )
    );

    public function beforeFilter(){
        $this->getLog(); 
        $this->Auth->allow('index', 'view');
    }

    var $uses = array('LogincreationMaster','PagesMaster','Admin','UserType','PagesMaster1','LoginLog');
        function Controler(){
        App::import('Core', 'Sanitize');
    }

    function beforeRender(){
        $this->getLatestData(); 
    }

    
    function getLatestData(){
        $menu = array(
                'menus' => array(),
                'parent_menus' => array(),
            );
        
        if($this->Session->read("role") ==="admin"){
            $admin_id = $this->Session->read("admin_id");
            $user_right_det = $this->Admin->find('first',array('conditions'=>"id='$admin_id'"));
            //print_r($user_right_det);exit;
            $user_right_str = $user_right_det['Admin']['access']; 
            $user_right_str .= $user_right_det['Admin']['parent_access']; 
            $user_page_access = implode("','",explode(",",$user_right_str));  
            
            $page = $this->PagesMaster1->find('all',array('conditions'=>"id in ('$user_page_access')",'order'=>"FIELD(PagesMaster1.parent_id,PagesMaster1.priority) ASC"));
            foreach($page as $row){
                $menu['menus'][$row['PagesMaster1']['id']] = $row['PagesMaster1'];
                $menu['parent_menus'][$row['PagesMaster1']['parent_id']][] = $row['PagesMaster1']['id'];
            }
            
            //print_r($menu);exit;
            
            $this->set('rightMenu',$this->buildLeftMenu(0, $menu));
        }
        else
        {
            $page = $this->PagesMaster->find('all'); 
            foreach($page as $row){
                $menu['menus'][$row['PagesMaster']['id']] = $row['PagesMaster'];
                $menu['parent_menus'][$row['PagesMaster']['parent_id']][] = $row['PagesMaster']['id'];
            }
            $this->set('leftMenu',$this->buildLeftMenu(NULL, $menu));
        }
        
            
            
            
            //print_r($this->buildLeftMenu(NULL, $menu)); exit;
           
    }
    
    function buildLeftMenu($parent, $menu) { 
            $html = "";
            $char=" ";
            
            if($this->Session->read("role") ==="agent"){
                $user  = $this->LogincreationMaster->find('first',array('fields'=>array('user_right'),'conditions' =>array('id' =>$this->Session->read("agent_userid"),'create_id' =>$this->Session->read("companyid"))));
                $right =  explode(",",$user['LogincreationMaster']['user_right']);
            }
            if($this->Session->read("role") ==="admin"){
                $admin_id = $this->Session->read("admin_id");
                $user_right_det = $this->Admin->find('first',array('conditions'=>"id='$admin_id'"));
                //$user_acc  = $this->UserType->find('first',array('fields'=>array('access','parent_access'),'conditions' =>array('user_type' =>$userType)));
                $parent_right = explode(",",$user_right_det['Admin']['parent_access']);
                $child_right = explode(",",$user_right_det['Admin']['access']);
                //print_r($user_acc); exit;
                $right =  array_unique(array_merge($parent_right,$child_right));
                $adminRights=$right;
            }
            //print_r($menu); exit;
            if (isset($menu['parent_menus'][$parent])) {
                foreach ($menu['parent_menus'][$parent] as $menu_id) {
                    $pid   = $menu['menus'][$menu_id]['id']; 
                    $purl  = $menu['menus'][$menu_id]['page_url'];
                    $pname = $menu['menus'][$menu_id]['page_name'];
                    $picon = $menu['menus'][$menu_id]['page_icon'];
                    
                    if($purl !=NULL){
                        $pageurl="<li><a class='withripple' href='".$this->webroot.$purl."'><span class='icon'>".$picon."</span> <span>".$pname."</span> </a>";                  
                    }
                    else{
                        $pageurl="<li><a class='withripple' href='javascript:;'><span class='icon'>".$picon."</span> <span>".$pname."</span> </a>"; 
                    } 
                               
                    if($this->Session->read("role") ==="agent"){
                        if(in_array($pid, $right)){
                            if (!isset($menu['parent_menus'][$menu_id])) {
                                $html .=$pageurl;
                            }
                            if (isset($menu['parent_menus'][$menu_id])) {
                                $html .=$pageurl;
                                $html .= "<ul class='acc-menu' style='margin-left:-34px;'>";
                                $html .= $this->buildLeftMenu($menu_id, $menu);
                                $html .= "</ul>";
                                $html .= "</li>";
                            }
                        }
                    }
                    else if($this->Session->read("role") ==="client"){
                        if (!isset($menu['parent_menus'][$menu_id])) {
                            $html .=$pageurl;
                        }
                        if (isset($menu['parent_menus'][$menu_id])) {
                            $html .=$pageurl;
                            $html .= "<ul class='acc-menu'>";
                            $html .= $this->buildLeftMenu($menu_id, $menu);
                            $html .= "</ul>";
                            $html .= "</li>";
                        } 
                    } 
                    else if($this->Session->read("role") ==="admin"){
                        
                        
                            if(in_array($pid, $right)){
                                if (!isset($menu['parent_menus'][$menu_id])) {
                                $html .=$pageurl;
                                }
                                if (isset($menu['parent_menus'][$menu_id])) {
                                    $html .=$pageurl; 
                                    $html .= "<ul class='acc-menu'>";
                                    $html .= $this->buildLeftMenu($menu_id, $menu);
                                    $html .= "</ul>";
                                    $html .= "</li>";
                                } 
                            }
                        
                    }
                }
            }
            return $html;
        }

        function getLog()
        {
            if($this->Session->read("role") ==="admin")
            {
                $admin_id = $this->Session->read("admin_id");
                $role = $this->Session->read("role");
                $name = $this->Session->read("admin_name");
                
                $url = $this->request->here();
                $url1 = explode("/",$url);

                $pages = $this->PagesMaster1->find('first',array('conditions'=>array('page_url'=>$url1[2])));
                $page_name = $pages['PagesMaster1']['page_name'];
                
            }else{

                $admin_id = $this->Session->read("companyid");
                $role = $this->Session->read("role");
                $name = $this->Session->read("companyname");

                $url = $this->request->here();
                $url1 = explode("/",$url);

                $pages = $this->PagesMaster->find('first',array('conditions'=>array('page_url'=>$url1[2])));
                $page_name = $pages['PagesMaster']['page_name'];
            }
            

            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

            

            $data['user_id'] = $admin_id;
            $data['type'] = $role;
            $data['ip_address'] = $ip;
            $data['user_name'] = $name;
            $data['page_name'] = $page_name;
            $data['page_url'] = $url1[2].'/'.$url1[3];
            $data['hit_time'] = date("Y-m-d H:i:s");

            //print_r($data);die;
            if(!empty($admin_id) && !empty($page_name))
            {
                $save = $this->LoginLog->save($data);
            }
            

        }
  
  
  
  
     
}
