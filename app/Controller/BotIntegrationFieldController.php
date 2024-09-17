<?php
class BotIntegrationFieldController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses = array('EcrRecord','CallRecord','BotIntegration','BotIntegrationToken','FieldMaster','RegistrationMaster','CampaignName','ObfieldCreation');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('ob_map','field_save','show_webhook');
        if(!$this->Session->check("admin_id"))
        {
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
          
            $master_fields_mapping = $this->BotIntegration->find('all',array('conditions'=>array('client_id'=>$clientID),'order' => array('id' => 'DESC')));
            $exist_field = array();
            if(!empty($master_fields_mapping))
            {
                
                foreach($master_fields_mapping as $mast_field)
                {
                    
                    $fieldId = $mast_field['BotIntegration']['field']; 
                    $fieldNumber = str_replace('Field', '', $fieldId);
                    $field_arr = $this->FieldMaster->find('first',array('conditions'=>array('ClientId'=>$clientID,'fieldNumber'=>$fieldNumber)));
                    $exist_field[$mast_field['BotIntegration']['field']] = $field_arr['FieldMaster']['FieldName'];
                }
            }

            #print_r($exist_field);die;

            $require_field = $this->FieldMaster->find('all',array('fields'=>array('fieldNumber','FieldName'),'conditions'=>array('ClientId'=>$clientID,'FieldStatus'=>NULL),'order'=>array('Priority')));

            $this->set('require_field',$require_field);
            $this->set('exist_field',$exist_field);
            $this->set('client_id',$clientID);
        }
    }

    public function field_save()
    {
        if($this->request->is('Post')){
            
            
            $data =$this->request->data;
            #print_r($data);die;
            $client_id = $data['client_id'];
            
            unset($data['client_id']);
            //unset($data['DataTables_Table_0_length']);

            if($this->Session->read('role') =="admin"){
                $create_user=$this->Session->read('admin_id');
            }

            $create_date=date('Y-m-d H:i:s');
            $delete_all = $this->BotIntegration->deleteAll(array('client_id' => $client_id));

            $recordsToSave= array();
            $i = 1;
            foreach ($data['selected_fields'] as  $value) 
            {

                $recordsToSave[] = array(
                    'client_id' => $client_id, 
                    'field' => $value,
                    'priority' => $i++,
                    'created_by' => $create_user,
                    'created_at' => $create_date
                );

            }

            $save = $this->BotIntegration->saveMany($recordsToSave);

            #BotIntegrationToken
            $secret_key = 'dialdesk';
            $token = $this->generateAuthToken($client_id,$secret_key);

            
            $token_arr = $this->BotIntegrationToken->find('first',array('conditions' => array('client_id'=>$client_id)));
            if(empty($token_arr))
            {
                $data_token =['client_id'=> $client_id,'token'=> $token,'created_at' => $create_date,'created_by'=> $create_user];
                $this->BotIntegrationToken->save($data_token);

            }else{

                $updateStatus=array('token'=>"'".$token."'",'updated_at'=>"'".$create_date."'",'updated_by'=> $create_user);
                $this->BotIntegrationToken->updateAll($updateStatus,array('client_id'=>$client_id));
            }

            $this->Session->setFlash('Bot Field Map Successfully.');
            $this->redirect(array('controller' => 'BotIntegrationField', 'action' => 'index'));
        }
    }

    
    public function show_webhook()
    {
        $this->layout='user';
        
        $client_id  = $this->request->query['client_id'];
        $master_client = $this->RegistrationMaster->find('first',array('conditions'=>array('status'=>"A","company_id"=>$client_id)));

        $master_fields_mapping = $this->BotIntegration->find('all',array('conditions'=>array('client_id'=>$client_id),'order' => array('id' => 'DESC')));
        
        $token_arr = $this->BotIntegrationToken->find('first',array('conditions' => array('client_id'=>$client_id)));
        $exist_field = array();
        $request_data = array();
        if(!empty($master_fields_mapping))
        {
            
            foreach($master_fields_mapping as $mast_field)
            {
                
                $fieldId = $mast_field['BotIntegration']['field']; 
                $fieldNumber = str_replace('Field', '', $fieldId);
                $field_arr = $this->FieldMaster->find('first',array('conditions'=>array('ClientId'=>$client_id,'fieldNumber'=>$fieldNumber)));
                $request_data[$field_arr['FieldMaster']['FieldName']] = "";
                $exist_field[$mast_field['BotIntegration']['field']] = $field_arr['FieldMaster']['FieldName'];
            }
        }

        #$request_data = array_values($exist_field); 
        #print_r($request_data);die;
        
        $this->set('token_arr',$token_arr);
        $this->set('master_client',$master_client);
        $this->set('exist_field',$exist_field);
        $this->set('request_data',$request_data);
        $this->set('client_id',$client_id);
        
        
    }

    function generateAuthToken($client_id, $secret_key)
    {
       
        $timestamp = time();
        $random_data = bin2hex(openssl_random_pseudo_bytes(2));
        $token_data = $client_id;
        $token = hash_hmac('sha256', $token_data, $secret_key);
        $auth_token = base64_encode($token_data . '|' . $token);
    
        return $auth_token;
    }

    
}
?>