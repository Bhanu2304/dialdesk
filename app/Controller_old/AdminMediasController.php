<?php
class AdminMediasController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','SocialMediaMaster','EmailMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
            $this->Auth->allow('add_media','update_media','emailmap','add_media_email','update_media_email');
                if(!$this->Session->check("admin_id")){
                    return $this->redirect(array('controller'=>'admins','action' => 'index'));
		}
    } 

    public function index(){
        $this->layout='user';
        $this->set('client',$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"))));
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && isset($_REQUEST['type']) && $_REQUEST['id'] !="" && $_REQUEST['type'] !="" ){
            $this->set('mediaArr',$this->SocialMediaMaster->find('first',array('conditions'=>array('client_id'=>$_REQUEST['id'],'social_media_type'=>$_REQUEST['type']))));
            $this->set('AllMediaArr',$this->SocialMediaMaster->find('all',array('conditions'=>array('client_id'=>$_REQUEST['id']))));
            $this->set('clientid',$_REQUEST['id']);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminMedias']; 
            $this->set('mediaArr',$this->SocialMediaMaster->find('first',array('conditions'=>array('client_id'=>$data['clientID'],'social_media_type'=>'facebook'))));
            $this->set('AllMediaArr',$this->SocialMediaMaster->find('all',array('conditions'=>array('client_id'=>$data['clientID']))));
            $this->set('clientid',$data['clientID']);
        }
    }
    
        
    public function add_media(){
        if($this->request->is('Post')){
            $data=$this->request->data['AdminMedias'];

            $dataArr=array(
                'client_id'=>$data['client_id'],
                'social_media_type'=>$data['social_media_type'],
                'fb_app_id'=>$data['FbAppId'],
                'fb_app_secret'=>$data['fb_app_secret'],
                'email'=>$data['LoginId'],
                'password'=>$data['password'],
                'fb_page_id1'=>$data['fb_page_id1'],
                'fb_page_name1'=>$data['fb_page_name1'],
                'fb_page_id2'=>$data['fb_page_id2'],
                'fb_page_name2'=>$data['fb_page_name2'],
                'fb_page_id3'=>$data['fb_page_id3'],
                'fb_page_name3'=>$data['fb_page_name3'],
                'fb_page_id4'=>$data['fb_page_id4'],
                'fb_page_name4'=>$data['fb_page_name4'],
                'fb_page_id5'=>$data['fb_page_id5'],
                'fb_page_name5'=>$data['fb_page_name5'], 
                'fb_page_token1'=>$data['fb_page_token1'],
                'fb_page_token2'=>$data['fb_page_token2'],
                'fb_page_token3'=>$data['fb_page_token3'],
                'fb_page_token4'=>$data['fb_page_token4'],
                'fb_page_token5'=>$data['fb_page_token5'],
            );
            
            $this->SocialMediaMaster->save($dataArr);  
           
            $this->Session->setFlash('Your '.$data['social_media_type'].' details save successfully.');
            $this->redirect(array('controller' => 'AdminMedias', 'action' => 'index', '?' => array('id' =>$data['client_id'],'type' =>$data['social_media_type'])));
        }
    }
    
    public function update_media(){
        if($this->request->is('Post')){
            $data=$this->request->data['AdminMedias'];
  
            $dataArr=array(
                'fb_app_id'=>"'".$data['FbAppId']."'",
                'fb_app_secret'=>"'".$data['fb_app_secret']."'",
                'email'=>"'".$data['LoginId']."'",
                'password'=>"'".$data['password']."'",
                'fb_page_id1'=>"'".$data['fb_page_id1']."'",
                'fb_page_name1'=>"'".$data['fb_page_name1']."'",
                'fb_page_id2'=>"'".$data['fb_page_id2']."'",
                'fb_page_name2'=>"'".$data['fb_page_name2']."'",
                'fb_page_id3'=>"'".$data['fb_page_id3']."'",
                'fb_page_name3'=>"'".$data['fb_page_name3']."'",
                'fb_page_id4'=>"'".$data['fb_page_id4']."'",
                'fb_page_name4'=>"'".$data['fb_page_name4']."'",
                'fb_page_id5'=>"'".$data['fb_page_id5']."'",
                'fb_page_name5'=>"'".$data['fb_page_name5']."'",
                'fb_page_token1'=>"'".$data['fb_page_token1']."'",
                'fb_page_token2'=>"'".$data['fb_page_token2']."'",
                'fb_page_token3'=>"'".$data['fb_page_token3']."'",
                'fb_page_token4'=>"'".$data['fb_page_token4']."'",
                'fb_page_token5'=>"'".$data['fb_page_token5']."'",
            );
            
            $this->SocialMediaMaster->updateAll($dataArr,array('client_id'=>$data['client_id']));
            $this->Session->setFlash('Your '.$data['social_media_type'].' details update successfully.');
            $this->redirect(array('controller' => 'AdminMedias', 'action' => 'index', '?' => array('id' =>$data['client_id'],'type' =>$data['social_media_type'])));
        
        }
    }
    
    
    //==================================================================================================================
    
    public function emailmap(){
        $this->layout='user';
        $this->set('client',$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"))));
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && isset($_REQUEST['cid']) && $_REQUEST['id'] !="" && $_REQUEST['cid'] !=""){
            $this->set('mediaArr',$this->EmailMaster->find('first',array('conditions'=>array('client_id'=>$_REQUEST['cid'],'Id'=>$_REQUEST['id']))));
            $this->set('AllMediaArr',$this->EmailMaster->find('all',array('conditions'=>array('client_id'=>$_REQUEST['cid']))));
            $this->set('clientid',$_REQUEST['cid']);  
        }
        
        if(!isset($_REQUEST['id']) && isset($_REQUEST['cid']) && $_REQUEST['id'] =="" && $_REQUEST['cid'] !=""){
            $this->set('mediaArr',array());
            $this->set('AllMediaArr',$this->EmailMaster->find('all',array('conditions'=>array('client_id'=>$_REQUEST['cid']))));
            $this->set('clientid',$_REQUEST['cid']);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminMedias']; 
            $this->set('mediaArr',array());
            $this->set('AllMediaArr',$this->EmailMaster->find('all',array('conditions'=>array('client_id'=>$data['clientID']))));
            $this->set('clientid',$data['clientID']);
        }
    }
    
   
    public function add_media_email(){
        if($this->request->is('Post')){
            $data=$this->request->data['AdminMedias'];
            $exist=$this->EmailMaster->find('first',array('conditions'=>array('client_id'=>$data['client_id'],'email'=>$data['email'])));
              
            if(!empty($exist)){
                $this->Session->setFlash('This email details allready exist in database.');
                $this->redirect(array('controller' => 'AdminMedias', 'action' => 'emailmap', '?' => array('cid' =>$data['client_id'])));
            } 
            else{
            $this->EmailMaster->save($data);
            $lid=$this->EmailMaster->getLastInsertId();
            $this->Session->setFlash('Your email details save successfully.');
            $this->redirect(array('controller' => 'AdminMedias', 'action' => 'emailmap', '?' => array('cid' =>$data['client_id'])));
            }
        }
    }
    
    public function update_media_email(){
        if($this->request->is('Post')){
            $data=$this->request->data['AdminMedias'];
              
            $dataArr=array(
                'email'=>"'".$data['email']."'",
                'password'=>"'".$data['password']."'",
                'inbox_hostname'=>"'".$data['inbox_hostname']."'",
                'inbox_port'=>"'".$data['inbox_port']."'",
                'send_hostname'=>"'".$data['send_hostname']."'",
                'send_port'=>"'".$data['send_port']."'",
                'active'=>"'".$data['active']."'"
            );
            
            $this->EmailMaster->updateAll($dataArr,array('client_id'=>$data['client_id'],'Id'=>$data['id']));
            $this->Session->setFlash('Your email details update successfully.');
            $this->redirect(array('controller' => 'AdminMedias', 'action' => 'emailmap', '?' => array('cid' =>$data['client_id'])));
        
        }
    }
        
		
}
?>