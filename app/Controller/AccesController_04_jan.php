<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AccesController extends AppController {

    public $uses = array( 'UserType','PagesMaster1');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'save','buildMenu', 'buildMenu2', 'branch', 'get_access_id');
        $pages = explode(',', $this->Session->read("page_access"));
        
    }

    public function index() {
        
        
        //$role = $this->Session->read('user-type');
        //$email = $this->Session->read('email');
        
        $menu = array(
            'menus' => array(),
            'parent_menus' => array(),
        );
        
        $menus = $this->PagesMaster1->query("SELECT * FROM pages_master1 ORDER BY parent_id, priority"); 
        
        foreach ($menus as $row) {
        $menu['menus'][$row['pages_master1']['id']] = $row['pages_master1'];
        $menu['parent_menus'][$row['pages_master1']['parent_id']][] = $row['pages_master1']['id'];
        }

        $parent = 0;
        $this->set('UserRight', $this->buildMenu($parent, $menu)); 
        $html = "";

        $sel = "SELECT id,user_type username FROM user_type WHERE  active='1'  ORDER BY id ";
        $users = $this->UserType->query($sel); 
        $this->set('users', $users);
        
        $this->layout = 'user';
                    
    }
    
    public function save() {
        $ride = $this->params['url']['rides'];
        $username = $this->params['url']['user'];

        $ch = explode(",", $ride);
        $q1 = "SELECT id,parent_id from pages_master1 WHERE ";
        foreach ($ch as $ot) {
            $q1.="id='$ot' OR ";
        }
        $q1 = substr($q1, 0, -4);

        $dd = $this->PagesMaster1->query($q1);

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

        
        $check = $this->UserType->query("select id from user_type WHERE user_type='" . $username . "'");
        if ($check[0]['user_type']['id'] == "") {
            $rides = $this->PageaMaster1->query("INSERT INTO user_type set user_type='" . $username . "', access='" . $child . "',parent_access='" . $pp . "'");
        } else {
            $rides = $this->PagesMaster1->query("Update user_type set access='" . $child . "',parent_access='" . $pp . "' WHERE user_type='" . $username . "'");
        }

        $this->set('response', "save");
        $this->layout = null;
    }

    function buildMenu($parent, $menu) {
        $html = "";
        $char = " ";
        if (isset($menu['parent_menus'][$parent])) {
            foreach ($menu['parent_menus'][$parent] as $menu_id) {
                if (!isset($menu['parent_menus'][$menu_id])) { 
                    $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' name='selectAll[]' id='" . $menu['menus'][$menu_id]['id'] . "'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'] . "</label></div></li>";
                }
                if (isset($menu['parent_menus'][$menu_id])) {  
                    $html .= "<li id='a".$menu['menus'][$menu_id]['id']."'><div class='checkbox-primary'><label><input type='checkbox' onchange=".'"show_child('."'".$menu['menus'][$menu_id]['id']."'".')"'."  name='selectAll[]' id='" . $menu['menus'][$menu_id]['id'] . "'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'];
                    $html .= "<ol class='user-tree'>"; 
                    $html .= $this->buildMenu($menu_id, $menu);
                    $html .= "</ol>";
                    $html .= "</label></div></li>";
                }
            }
        }
        return $html;
    }

    function buildMenu2($parent, $menu) {
        $html = "";
        $char = " ";
        //echo json_encode($menu['parent_menus'][$parent])." pppppppp";
        if (isset($menu['parent_menus'][$parent])) {
            foreach ($menu['parent_menus'][$parent] as $menu_id) {
                echo json_encode($menu_id);
                if (!isset($menu['parent_menus'][$menu_id])) {
                    $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' name='selectAll[]'  value='" . $menu['menus'][$menu_id]['id'] . "'> " . $menu['menus'][$menu_id]['page_name'] . "</label></div></li>";
                }
                /* if (isset($menu['parent_menus'][$menu_id])) {
                  $html .= "<li><div class='checkbox-primary'><label><input type='checkbox' name='selectAll[]'  value='".$menu['menus'][$menu_id]['id']."'> ".$menu['menus'][$menu_id]['page_name'];
                  $html .= "<ol class='user-tree'>";
                  $html .= $this->buildMenu($menu_id, $menu);
                  $html .= "</ol>";
                  $html .= "</label></div></li>";
                  } */
            }
        }
        return $html;
    }
	
	
    public function branch(){
		$this->layout='home';

		$users = $this->UserType->query("SELECT id,username FROM tbl_user where UserActive='1' AND username is not null AND role !='admin'  group by username order by username ");
        $this->set('users', $users);
        
        $BranchArray=$this->UserType->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
        $this->set('branchName',$BranchArray); 
		
        if($this->request->is('Post')){ 

			$id				=	$_REQUEST['userid'];
			$branchArrList	=	array();
			$branchArr 		= 	$this->User->query("SELECT Access_Branch FROM tbl_user where id='".$id."'");
			
			if($_REQUEST['Submit'] =="Submit"){
				$branch		=	implode(",",$_REQUEST['branch']);
				$this->UserType->query("UPDATE tbl_user SET Access_Branch='$branch' where id='".$id."'");
				$this->Session->setFlash('<span style="font-weight:bold;color:green;" >Branch rights update successfully.</span>'); 
			}
			
			if($branchArr[0]['tbl_user']['Access_Branch'] !=""){
				$branchArrList	=	explode( ',', $branchArr[0]['tbl_user']['Access_Branch'] );
			}
			
			$this->set('branchArrList', $branchArrList);
			$this->set('id',$id); 
			
			
			
        } 
	}

    public function get_access_id() {
        $user_type = $this->params['url']['user'];
        $rides = $this->PagesMaster1->query("SELECT id,user_type username,access,parent_access FROM user_type WHERE user_type='" . $user_type . "'");
         echo json_encode($rides); exit;
    }    
        
}

?>
