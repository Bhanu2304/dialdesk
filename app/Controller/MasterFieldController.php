<?php
class MasterFieldController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses = array('EcrRecord','CallRecord','MasterField','MasterFieldMap','FieldMaster','AbandCallMaster','RegistrationMaster','CampaignName','ObfieldCreation');
    
    public function beforeFilter() {
        parent::beforeFilter();
            $this->Auth->allow('ob_map','c2p_field_mapping_save','get_campaign','c2p_ob_field_mapping_save','dashboard');
                if(!$this->Session->check("admin_id")){
                    return $this->redirect(array('controller'=>'admins','action' => 'index'));
        }
    }

    public function index()
    {
        $this->layout='user';
        $master_client = $this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A"),'order'=> array('company_name'=>'asc')));
        
        $this->set('master_client',$master_client);
        
        if($this->request->is('Post')){
            
            $clientID=$this->request->data['clientID'];
          
            $master_fields_mapping = $this->MasterFieldMap->find('all',array('conditions'=>array('client_id'=>$clientID)));
            $master_fields = $this->MasterField->find('all');
            #print_r($master_fields);die;
            $fieldName = $this->FieldMaster->find('all',array('fields'=>array('fieldNumber','FieldName'),'conditions'=>array('ClientId'=>$clientID,'FieldStatus'=>NULL),'order'=>array('Priority')));

            $this->set('require_field',$fieldName);
            $this->set('master_fields',$master_fields);
            $this->set('master_fields_mapping',$master_fields_mapping);
            $this->set('client_id',$clientID);
        }
    }

    public function c2p_field_mapping_save()
    {
        if($this->request->is('Post')){
            
            $data =$this->request->data;
            $client_id = $data['client_id'];
            unset($data['client_id']);
            unset($data['DataTables_Table_0_length']);

            if($this->Session->read('role') =="admin"){
                $create_user=$this->Session->read('admin_id');
            }

            $create_date=date('Y-m-d H:i:s');
            $delete_all = $this->MasterFieldMap->deleteAll(array('client_id' => $client_id,'type'=>'ib'));

            $recordsToSave= array();
            foreach ($data as $key => $value) {

                $recordsToSave[] = array(
                    'client_id' => $client_id, 
                    'field' => $key,
                    'map_field' => $value,
                    'type' => "ib",
                    'created_by' => $create_user,
                    'created_at' => $create_date
                );

            }

            $save = $this->MasterFieldMap->saveMany($recordsToSave);

            #$url = WWW_ROOT.'crone/monnai_ib.php';
            #$this->webroot
            $url = "http://localhost/dialdesk/app/webroot/crone/monnai_ib.php?client_id=$client_id";
            $file_contents = file_get_contents($url);
            #print_r($file_contents);die;
            $this->Session->setFlash('Field Map Successfully.');
            $this->redirect(array('controller' => 'MasterField', 'action' => 'index'));
        }
    }

    public function ob_map()
    {
        $this->layout='user';
        $master_client = $this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A"),'order'=> array('company_name'=>'asc')));
        
        $this->set('master_client',$master_client);
        
        if($this->request->is('Post')){
            #print_r($this->request->data);die;
            $clientID=$this->request->data['clientID'];
            $campaign_id=$this->request->data['campaign_id'];

            $campaign=$this->CampaignName->find('list',array('fields'=>array('id','CampaignName'),'conditions'=>array('ClientId'=>$clientID,'CampaignStatus'=>'A')));
            $this->set('campaign',$campaign);

            
            $master_fields_mapping = $this->MasterFieldMap->find('all',array('conditions'=>array('client_id'=>$clientID,'type'=>'ob','campaign_id'=>$campaign_id)));
            $master_fields = $this->MasterField->find('all');
            #print_r($master_fields);die;
           
            $fieldName = $this->ObfieldCreation->find('all',array('fields'=>array('fieldNumber','FieldName'),'conditions'=>array('ClientId'=>$clientID,'CampaignId'=>$campaign_id,'FieldStatus'=>NULL),'order'=>array('Priority')));
            

            $this->set('require_field',$fieldName);
            $this->set('master_fields',$master_fields);
            $this->set('master_fields_mapping',$master_fields_mapping);
            $this->set('client_id',$clientID);
            $this->set('campaign_id',$campaign_id);
        }
    }

    public function get_campaign()
    {
        $this->layout='ajax';
        if(isset($_REQUEST['client_id'])){
        
            $client_id =   $_REQUEST['client_id'];
            $Campaign=$this->CampaignName->find('list',array('fields'=>array('id','CampaignName'),'conditions'=>array('ClientId'=>$client_id,'CampaignStatus'=>'A')));
            
            if(!empty($Campaign)){
                echo "<option value='' >Select Campaign</option>";
                foreach($Campaign as $key=>$val){
                    echo "<option value='$key'>$val</option>";
                }
            }
            else{
                echo "<option value='' >Select Campaign</option>";
            }
            die;
        }
    }

    
    public function c2p_ob_field_mapping_save()
    {
        if($this->request->is('Post')){
            #print_r($this->request->data);die;
            $data =$this->request->data;
            $client_id = $data['client_id'];
            $campaign_id = $data['campaign_id'];
            unset($data['client_id']);
            unset($data['DataTables_Table_0_length']);
            unset($data['campaign_id']);

            if($this->Session->read('role') =="admin"){
                $create_user=$this->Session->read('admin_id');
            }

            $create_date=date('Y-m-d H:i:s');
            $delete_all = $this->MasterFieldMap->deleteAll(array('client_id' => $client_id,'type'=>'ob','campaign_id'=>$campaign_id));

            $recordsToSave= array();
            foreach ($data as $key => $value) {

                $recordsToSave[] = array(
                    'client_id' => $client_id,
                    'campaign_id' => $campaign_id, 
                    'field' => $key,
                    'map_field' => $value,
                    'type' => "ob",
                    'created_by' => $create_user,
                    'created_at' => $create_date
                );

            }

            $save = $this->MasterFieldMap->saveMany($recordsToSave);
            
            $url = "http://localhost/dialdesk/app/webroot/crone/monnai_ob.php?client_id=$client_id";
            $file_contents = file_get_contents($url);
            
            $this->Session->setFlash('Field Map Successfully.');
            $this->redirect(array('controller' => 'MasterField', 'action' => 'ob_map'));
        }
    }

    public function dashboard() {
        $this->layout='user';
       

        $qry= "SELECT SUM(IF(`Name` !='',1,0)) `total_name`,SUM(IF(`Address` !='',1,0)) `total_address`,SUM(IF(`City` !='',1,0)) `total_city`,SUM(IF(`Phone` !='',1,0)) `total_phone`,SUM(IF(`Email` !='',1,0)) `total_email`,SUM(IF(`State` !='',1,0)) `total_state`,SUM(IF(`Pincode` !='',1,0)) `total_pincode` FROM master_data";
        $master_data1 = $this->MasterFieldMap->query($qry);

        $this->MasterFieldMap->useDbConfig = 'db3';
        $master_data2 = $this->MasterFieldMap->query($qry);

        $total_name = $master_data1[0][0]['total_name'] + $master_data2[0][0]['total_name'];
        $total_address = $master_data1[0][0]['total_address'] + $master_data2[0][0]['total_address'];
        $total_city = $master_data1[0][0]['total_city'] + $master_data2[0][0]['total_city'] ;
        $total_phone = $master_data1[0][0]['total_phone'] + $master_data2[0][0]['total_phone'] ;
        $total_email = $master_data1[0][0]['total_email'] + $master_data2[0][0]['total_email'] ;
        $total_state = $master_data1[0][0]['total_state'] + $master_data2[0][0]['total_state'];
        $total_pincode = $master_data1[0][0]['total_pincode'] + $master_data2[0][0]['total_pincode'] ;

        $this->set('total_name',$total_name);
        $this->set('total_address',$total_address);
        $this->set('total_city',$total_city);
        $this->set('total_phone',$total_phone);
        $this->set('total_email',$total_email);
        $this->set('total_state',$total_state);
        $this->set('total_pincode',$total_pincode);
        
		
    }
    
}
?>