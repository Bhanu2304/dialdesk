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
        $this->Auth->allow('index', 'view');
    }

    var $uses = array('LogincreationMaster','PagesMaster','Admin');
        function Controler(){
        App::import('Core', 'Sanitize');
    }

    function beforeRender(){
        $this->getLatestData(); 
    }

    function getLatestData(){
        $page = $this->PagesMaster->find('all'); 
            $menu = array(
                'menus' => array(),
                'parent_menus' => array(),
            );
            
            foreach($page as $row){
                $menu['menus'][$row['PagesMaster']['id']] = $row['PagesMaster'];
                $menu['parent_menus'][$row['PagesMaster']['parent_id']][] = $row['PagesMaster']['id'];
            }
           $this->set('leftMenu',$this->buildLeftMenu(NULL, $menu));
    }
    
    function buildLeftMenu($parent, $menu) {
            $html = "";
            $char=" ";
            
            if($this->Session->read("role") ==="agent"){
                $user  = $this->LogincreationMaster->find('first',array('fields'=>array('user_right'),'conditions' =>array('id' =>$this->Session->read("agent_userid"),'create_id' =>$this->Session->read("companyid"))));
                $right =  explode(",",$user['LogincreationMaster']['user_right']);
            }
            if($this->Session->read("role") ==="admin"){
                $user  = $this->Admin->find('first',array('fields'=>array('user_right'),'conditions' =>array('id' =>$this->Session->read("admin_id"))));
                $right =  explode(",",$user['Admin']['user_right']);
                $adminRights=$user['Admin']['user_right'];
            }
            
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
                        if($adminRights ==''){
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
                        else{
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
            }
            return $html;
        }
  
  
  
  
     
}
