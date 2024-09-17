<?php
class AdminsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('Admin','ProcessUpdation','LoginLog');
	
    public function beforeFilter() {
        parent::beforeFilter();
	$this->Auth->allow('login','logout');
    }

    public function index() 
    {
        $this->layout='adminlogin';

        $this->Admin->query("flush hosts");
        if($this->request->is('post')) 
        {
            $this->Admin->query("flush hosts"); 
            $Arr=array('UserName'=>$this->params['data']['UserName'],'Password'=>$this->params['data']['Password'],'user_active'=>'1');
            $arrData=$this->Admin->find('first',array('conditions'=>$Arr));
            
            $conditions = array('read_status' => 0);
            $Noti_count = $this->ProcessUpdation->find('count',array('conditions'=>$conditions));
            $notification_data = $this->ProcessUpdation->find('all',array(
                'order' => array('ProcessUpdation.id' => 'desc'),
                'conditions' => array('read_status' => 0)
            ));
            
            //print_r($notification_data);exit;
            
            if(!empty($arrData['Admin'])){
                $this->Session->write('notification_data',$notification_data);
                $this->Session->write("admin_id",$arrData['Admin']['id']);
                $this->Session->write("admin_name",$arrData['Admin']['UserName']);
                $this->Session->write("admin_email",$arrData['Admin']['Email']);
                $this->Session->write("role","admin");
                $this->Session->write("Noti_count",$Noti_count);

                $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

                $data['user_id'] = $arrData['Admin']['id'];
                $data['type'] = 'admin';
                $data['ip_address'] = $ip;
                $data['user_name'] = $arrData['Admin']['UserName'];
                $data['page_name'] = 'Login';
                $data['page_url'] =  'Login';
                $data['hit_time'] = date("Y-m-d H:i:s");

                $this->LoginLog->save($data);

                $this->redirect(array('controller' => 'homes2', 'action' => 'index'));
            }
            else {   
                $this->set('err','Invalid username or password.');
            }
        }
    }
	
    public function logout() 
    {

        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

        $data['user_id'] = $this->Session->read("admin_id");
        $data['type'] = 'admin';
        $data['ip_address'] = $ip;
        $data['user_name'] = $this->Session->read("admin_name");
        $data['page_name'] = 'LogOut';
        $data['page_url'] =  'LogOut';
        $data['hit_time'] = date("Y-m-d H:i:s");

        $this->LoginLog->save($data);

        $this->Session->delete('admin_id');
        $this->Session->delete('admin_name');
        $this->Session->delete('admin_email');
        $this->Session->delete('role');
        $this->Session->delete('companyid');
        $this->Session->delete('clientstatus');
        $this->Session->delete('campaignid');
        $this->Session->delete('notification_data');
        $this->Session->delete('Noti_count');
        $this->Session->destroy();
        $this->redirect(array('action'=>'index'));
    }
		
}
?>