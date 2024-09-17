<?php
class ShopifyIntegrationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','ShopifyConf');
    
    public function beforeFilter() {
        parent::beforeFilter();
            $this->Auth->allow('index');
                if(!$this->Session->check("admin_id")){
                    return $this->redirect(array('controller'=>'admins','action' => 'index'));
        }
    }


    public function index()
    {
        $this->layout='user';

        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A")));
        $this->set('client',$client);
        $this->set('clientid',array());


        $result = $this->ShopifyConf->find('all',
            array(
                'fields'=>array('*,registration_master.company_id','registration_master.company_name'),
                'joins' => array(
                    array(
                        'table' =>'registration_master',
                        'type'=>'Left',
                        'alias'=>'registration_master',
                        'conditions'=>array("ShopifyConf.client_id=registration_master.company_id"),
                        'group' => array('ShopifyConf.client_id'),
                    )
                )
                
            ));

            // $viewListId = $this->ListMaster->find('all',array('group'=>array("ListMaster.client_id")));
            // $this->set('viewListId',$viewListId);
            //print_r($result);die;
        
        if($this->request->is('Post'))
        {
            $data = $this->request->data['ShopifyIntegrations'];
            $clientid =$data['clientID'];
            $api_key =$data['api_key'];
            $api_token =$data['api_token'];
            $domain =$data['domain'];
            $list =$data['list'];
            $dialer_ip =$data['dialer_ip'];
            
            $create_date=date('Y-m-d H:i:s'); 
            $create_user=$this->Session->read('admin_name');

            $result = strstr($domain, '-', true);
            $type = "";
            if ($result !== false) {
                $type = $result;  
            } else {
                $type = $domain;
            }

            $dataArr =['client_id'=> $clientid,'api_key'=> $api_key,'domain'=> $domain,'token'=> $api_token,'dialer_ip'=> $dialer_ip,'list_id'=> $list,'token'=> $api_token,'type'=>$type,'create_date'=> $create_date,'created_by'=> $create_user];
            $save = $this->ShopifyConf->save($dataArr);  
            if($save)
            {
                $currentTime = new DateTime();
                $timezone = new DateTimeZone('Asia/Kolkata'); 
                $currentTime->setTimezone($timezone);

                // Format the current time in the desired format
                $formattedTimestamp = $currentTime->format("Y-m-d\TH:i:sP");
                $check_qry = "select * from  `pancha_cart_abandon` where domain='$type'";
                $exist_domain=$this->ShopifyConf->query($check_qry);
                if(empty($exist_domain))
                {
                    $qry = "INSERT INTO `pancha_cart_abandon` (Domain,NotUse,Createdate) VALUES ('$type','1','$formattedTimestamp')";
                    $dt=$this->ShopifyConf->query($qry);
                }

                $this->Session->setFlash('Add Shopify Details Successfully.');
                $this->redirect(array('controller'=>'ShopifyIntegrations','action'=>'index'));

            }else{
                $this->Session->setFlash('Please Try Again.');
                $this->redirect(array('controller'=>'ShopifyIntegrations','action'=>'index'));
            }
            
        }
          
        $this->set('result',$result);


    
    }
    
        
}
?>