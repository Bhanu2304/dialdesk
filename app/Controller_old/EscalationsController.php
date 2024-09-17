<?php
class EscalationsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('Escalation','EscalationValue','ClientCategory','LogincreationMaster','FieldCreation','Crone','RegistrationMaster','Matrix','SMSText','TimePeriod');
	
    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Session->check('companyid'))
        {
            $this->Auth->allow('index','add','view','view_value','delete','getParent','view_fields','matrix','getEcr','smsText','smsTextCustomer','time_period','save_alert_esclation','delete_matrix','save_customer_smstext','delete_sms','save_smstext','view_edit_data','update_alert_esclation','get_edit_ecr','update_customer_smstext');
	}
	else
	{
            $this->Auth->deny('index','add','view','view_value','delete','getParent','view_fields','matrix','getEcr','smsText','smsTextCustomer','time_period','save_alert_esclation','delete_matrix','save_customer_smstext','delete_sms','save_smstext','view_edit_data','update_alert_esclation','get_edit_ecr','update_customer_smstext');
	}
    }
	
public function index() {
    $this->layout='user';		
    $ClientId = $this->Session->read('companyid');
    
   
                
    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
    $Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
    $Category['All'] = 'All';
    $this->set('Category',$Category);
						
    $data=$this->ClientCategory->find('all',array('fields'=>array('id','Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId)));
    $Ecr = $this->ClientCategory->find('first',array('fields'=>array('Ecr'),'conditions'=>array('Client'=>$ClientId)));
    $this->set('data',$data);
    $field = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName'),'conditions'=>array('ClientId'=>$ClientId)));
		
    $fields_send = array();
    foreach($field as $f):
        $fields_send[$f['FieldCreation']['FieldName']] = $f['FieldCreation']['FieldName'];
    endforeach;

    $field_send1['Capture Field'] = $fields_send;
    $this->set('field_send1',$field_send1);
		
    $fields_send = array(); 

    for($i =1; $i<$Ecr['ClientCategory']['Ecr']; $i++)
    {
        $fields_send['Category'.$i] = 'Category'.$i;
    }
		
    $field_send2['ECR Field'] = $fields_send; $fields_send = array();
    $this->set('field_send2',$field_send2);
    
    /*
    if(isset($this->request->query['id']))
    {
        $id = base64_decode($this->request->query['id']);			
        $this->set('escalation',$this->Escalation->find('all',array('fields'=>array('email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
    }	*/			

		//$this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','ecrName','id','parent_id','Escalation.username'),'joins'=>array(array('table'=>'escalation_master','alias'=>'Escalation','type'=>'Left','conditions'=>array('Escalation.ecrId=ClientCategory.id'))),'conditions'=>array('Client'=>$ClientId))));

    
    
    
    
    
    
    //$Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
    //$this->set('Category',$Category);
    
    if(isset($this->request->query['id']))
    {
        $id = base64_decode($this->request->query['id']);				
	$this->set('escalation',$this->Escalation->find('all',array('fields'=>array('id','email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
    }
    $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
    
    
    
    }

     
    public function view_fields(){
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        
        if(isset($_REQUEST['tab']) && $_REQUEST['tab'] !=""){
             $this->set('tab',$_REQUEST['tab']);
        }
        
        $this->set('data1',$this->Matrix->find('all',array('conditions'=>array('alertType'=>'Alert','clientId'=>$ClientId))));
        $this->set('data2',$this->Matrix->find('all',array('conditions'=>array('alertType !='=>'Alert','clientId'=>$ClientId))));
        $this->set('data3',$this->SMSText->find('all',array('conditions'=>array('clientId'=>$ClientId,'sendType'=>'0'))));
        $this->set('data4',$this->SMSText->find('all',array('conditions'=>array('clientId'=>$ClientId,'sendType'=>'1')))); 

        // View Virtual Fields
        
        $field = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL)));
        $Ecr = $this->ClientCategory->query("SELECT MAX(label) `label` FROM ecr_master WHERE `client` = '$ClientId' limit 1");
        //print_r($Ecr); exit;
    $fields_send = array();
    //$fields_send['MSISDN']="MSISDN";
    //$fields_send['SrNo']="SrNo";
    foreach($field as $f):
        $fields_send[$f['FieldCreation']['FieldName']] = $f['FieldCreation']['FieldName'];
    endforeach;

    $field_send1['Select Fields'] = $fields_send;
    $this->set('field_send1',$field_send1);
		
    $fields_send = array(); 

    for($i =1; $i<=$Ecr['0']['0']['label']; $i++)
    {
        if($i ==1){
        $fields_send['Category'.$i] = 'Scenario';
        }
        else{
            $j=$i-1;
          $fields_send['Category'.$i] = 'Sub Scenario '.$j;  
        }
        
    }
		
    $field_send2['Select Scenario'] = $fields_send; $fields_send = array();
    $this->set('field_send2',$field_send2);
        
        
        // View Category On Client 
        
        $category = $this->ClientCategory->find('list',array('fields'=>array("id","EcrName"),'conditions'=>array('label'=>'1','Client'=>$ClientId)));
        $cat = array();
        foreach($category as $k=>$v){
            $cat[$k.'@@'.$v] = $v;
        }
        //$category = $cat;
        $category = array_merge(array('All@@All'=>'All'), $cat);
        unset($cat);
        $this->set('category',$category);
          
    }
    
     public function view_edit_data(){
        $this->layout='ajax';
        $ClientId = $this->Session->read('companyid');
        if(isset($_REQUEST['id'])){
            if($_REQUEST['type'] ==="tab1"){ 
               $data1=$this->Matrix->find('first',array('conditions'=>array('id'=>$_REQUEST['id'],'clientId'=>$ClientId)));
               $this->set('data1',$data1['Matrix']);
            }
            if($_REQUEST['type'] ==="tab2"){ 
               $data1=$this->Matrix->find('first',array('conditions'=>array('id'=>$_REQUEST['id'],'clientId'=>$ClientId)));
               $this->set('data1',$data1['Matrix']);
            }
            if($_REQUEST['type'] ==="tab3"){ 
               $data2=$this->SMSText->find('first',array('conditions'=>array('id'=>$_REQUEST['id'],'clientId'=>$ClientId,'sendType'=>'0')));
               $this->set('data2',$data2['SMSText']);
            } 
            if($_REQUEST['type'] ==="tab4"){ 
               $data3=$this->SMSText->find('first',array('conditions'=>array('id'=>$_REQUEST['id'],'clientId'=>$ClientId,'sendType'=>'1')));
                $this->set('data3',$data3['SMSText']);
            }
            
            
            $field = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL)));
            $Ecr = $this->ClientCategory->find('first',array('fields'=>array('Ecr'),'conditions'=>array('Client'=>$ClientId)));
 
            $fields_send = array();
            foreach($field as $f):
                $fields_send[$f['FieldCreation']['FieldName']] = $f['FieldCreation']['FieldName'];
            endforeach;

            $field_send1['Capture Field'] = $fields_send;
            $this->set('field_send1',$field_send1);

            $fields_send = array(); 

            for($i =1; $i<$Ecr['ClientCategory']['Ecr']; $i++)
            {
                $fields_send['Category'.$i] = 'Category'.$i;
            }

            $field_send2['ECR Field'] = $fields_send; $fields_send = array();
            $this->set('field_send2',$field_send2);
            
      
            $this->set('type',$_REQUEST['type']);
            
            $category = $this->ClientCategory->find('list',array('fields'=>array("id","EcrName"),'conditions'=>array('label'=>'1','Client'=>$ClientId)));
            $cat = array();
            foreach($category as $k=>$v){
                $cat[$k.'@@'.$v] = $v;
            }
            //$category = $cat;
            $category = array_merge(array('All@@All'=>'All'), $cat);
            unset($cat);
            $this->set('category',$category);
        }
    }
    
    
    
    
    public function save_alert_esclation(){
        if($this->request->is('POST')){
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['Escalations'];
            $data['clientId']=$ClientId;
         
            foreach($data as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('category','type','subtype','subtype1','subtype2');
                if(in_array($k,$keys)){
                    $Arr = explode('@@',$v);
                    $data1[$k] = $Arr[0];
                    $data1[$k.'Name'] = $Arr[1];
                }
            }
            
            
            

            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
            
     
            
            $this->Matrix->save($data1);
            if($data['tabType'] ==="tab1"){
                $this->Session->setFlash("Alearts Define  SuccessFully.");
            }
            if($data['tabType'] ==="tab2"){
                $this->Session->setFlash("Esclation Matrix Define  SuccessFully.");
            }
            return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$data['tabType'])));
        }  
    }
    
    public function update_alert_esclation(){
        if($this->request->is('POST')){
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['Escalations'];
            $data['clientId']=$ClientId;
   
            foreach($data as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('category','type','subtype','subtype1','subtype2');
                if(in_array($k,$keys)){
                    $Arr = explode('@@',$v);
                    $data1[$k] = $Arr[0];
                    $data1[$k.'Name'] = $Arr[1];
                }
            }
            
            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
 
            if(isset($data1['alertType']) && $data1['alertType'] !=""){$dataArr['alertType']="'".$data1['alertType']."'";}else{unset($dataArr['alertType']);}
            if(isset($data1['category']) && $data1['category'] !=""){$dataArr['category']="'".$data1['category']."'";}else{unset($dataArr['category']);}
            if(isset($data1['categoryName']) && $data1['categoryName'] !=""){$dataArr['categoryName']="'".$data1['categoryName']."'";}else{unset($dataArr['categoryName']);}
            if(isset($data1['type']) && $data1['type'] !=""){$dataArr['type']="'".$data1['type']."'";}else{unset($dataArr['type']);}
            if(isset($data1['typeName']) && $data1['typeName'] !=""){$dataArr['typeName']="'".$data1['typeName']."'";}else{unset($dataArr['typeName']);}
            if(isset($data1['subtype']) && $data1['subtype'] !=""){$dataArr['subtype']="'".$data1['subtype']."'";}else{unset($dataArr['subtype']);}
            if(isset($data1['subtypeName']) && $data1['subtypeName'] !=""){$dataArr['subtypeName']="'".$data1['subtypeName']."'";}else{unset($dataArr['subtypeName']);}
            if(isset($data1['subtype1']) && $data1['subtype1'] !=""){$dataArr['subtype1']="'".$data1['subtype1']."'";}else{unset($dataArr['subtype1']);}
            if(isset($data1['subtype1Name']) && $data1['subtype1Name'] !=""){$dataArr['subtype1Name']="'".$data1['subtype1Name']."'";}else{unset($dataArr['subtype1Name']);}
            if(isset($data1['subtype2']) && $data1['subtype2'] !=""){$dataArr['subtype2']="'".$data1['subtype2']."'";}else{unset($dataArr['subtype2']);}
            if(isset($data1['subtype2Name']) && $data1['subtype2Name'] !=""){$dataArr['subtype2Name']="'".$data1['subtype2Name']."'";}else{unset($dataArr['subtype2Name']);}
            if(isset($data1['personName']) && $data1['personName'] !=""){$dataArr['personName']="'".$data1['personName']."'";}else{unset($dataArr['personName']);}
            if(isset($data1['designation']) && $data1['designation'] !=""){$dataArr['designation']="'".$data1['designation']."'";}else{unset($dataArr['designation']);}
            if(isset($data1['email']) && $data1['email'] !=""){$dataArr['email']="'".$data1['email']."'";}else{unset($dataArr['email']);}
            if(isset($data1['mobileNo']) && $data1['mobileNo'] !=""){$dataArr['mobileNo']="'".$data1['mobileNo']."'";}else{unset($dataArr['mobileNo']);}
            if(isset($data1['alertOn']) && $data1['alertOn'] !=""){$dataArr['alertOn']="'".$data1['alertOn']."'";}else{unset($dataArr['alertOn']);}
            if(isset($data1['tat']) && $data1['tat'] !=""){$dataArr['tat']="'".$data1['tat']."'";}else{unset($dataArr['tat']);}
           
           // print_r($dataArr);die;
            
            
            if(!empty($dataArr)){
                $this->Matrix->updateAll($dataArr,array('id'=>$data['id'],'clientId'=>$ClientId));
            }

            if($data['tabType'] ==="tab1"){
                $this->Session->setFlash("Alearts Update  SuccessFully.");
            }
            if($data['tabType'] ==="tab2"){
                $this->Session->setFlash("Esclation Matrix Update  SuccessFully.");
            }
            return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$data['tabType'])));
        }  
    }
    
    
    public function update_customer_smstext(){
        if($this->request->is('POST')){
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['Escalations'];
            $data['clientId']=$ClientId;
 
            foreach($data as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('category','type','subtype','subtype1','subtype2');
                if(in_array($k,$keys))
                {
                    $Arr = explode('@@',$v);
                    $data1[$k] = $Arr[0];
                    $data1[$k.'Name'] = $Arr[1];
                }
            }
        
            if($data['tabType'] ==="tab3"){
                $data1['sendType'] = '0';
            }
            if($data['tabType'] ==="tab4"){
                $data1['sendType'] = '1';
            }
            
            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
            
            if(isset($data1['alertType']) && $data1['alertType'] !=""){$dataArr['alertType']="'".$data1['alertType']."'";}else{unset($dataArr['alertType']);}
            if(isset($data1['category']) && $data1['category'] !=""){$dataArr['category']="'".$data1['category']."'";}else{unset($dataArr['category']);}
            if(isset($data1['categoryName']) && $data1['categoryName'] !=""){$dataArr['categoryName']="'".$data1['categoryName']."'";}else{unset($dataArr['categoryName']);}
            if(isset($data1['type']) && $data1['type'] !=""){$dataArr['type']="'".$data1['type']."'";}else{unset($dataArr['type']);}
            if(isset($data1['typeName']) && $data1['typeName'] !=""){$dataArr['typeName']="'".$data1['typeName']."'";}else{unset($dataArr['typeName']);}
            if(isset($data1['subtype']) && $data1['subtype'] !=""){$dataArr['subtype']="'".$data1['subtype']."'";}else{unset($dataArr['subtype']);}
            if(isset($data1['subtypeName']) && $data1['subtypeName'] !=""){$dataArr['subtypeName']="'".$data1['subtypeName']."'";}else{unset($dataArr['subtypeName']);}
            if(isset($data1['subtype1']) && $data1['subtype1'] !=""){$dataArr['subtype1']="'".$data1['subtype1']."'";}else{unset($dataArr['subtype1']);}
            if(isset($data1['subtype1Name']) && $data1['subtype1Name'] !=""){$dataArr['subtype1Name']="'".$data1['subtype1Name']."'";}else{unset($dataArr['subtype1Name']);}
            if(isset($data1['subtype2']) && $data1['subtype2'] !=""){$dataArr['subtype2']="'".$data1['subtype2']."'";}else{unset($dataArr['subtype2']);}
            if(isset($data1['subtype2Name']) && $data1['subtype2Name'] !=""){$dataArr['subtype2Name']="'".$data1['subtype2Name']."'";}else{unset($dataArr['subtype2Name']);}
            if(isset($data1['senderID']) && $data1['senderID'] !=""){$dataArr['senderID']="'".$data1['senderID']."'";}else{unset($dataArr['senderID']);}
            if(isset($data1['smsText']) && $data1['smsText'] !=""){$dataArr['smsText']="'".$data1['smsText']."'";}else{unset($dataArr['smsText']);}

            if(!empty($dataArr)){
                $this->SMSText->updateAll($dataArr,array('id'=>$data['id'],'clientId'=>$ClientId));
            }

            if($data['tabType'] ==="tab3"){
                $this->Session->setFlash("SMS Text Update SuccessFully.");
            }
            if($data['tabType'] ==="tab4"){
                $this->Session->setFlash("Internal Communications Update SuccessFully.");
            }
            return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$data['tabType'])));
        }  
    }
    
    
    
    
    public function save_customer_smstext(){
        if($this->request->is('POST')){
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['Escalations'];
            $data['clientId']=$ClientId;
 
            foreach($data as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('category','type','subtype','subtype1','subtype2');
                if(in_array($k,$keys))
                {
                    $Arr = explode('@@',$v);
                    $data1[$k] = $Arr[0];
                    $data1[$k.'Name'] = $Arr[1];
                }
            }
        
            $data1['sendType'] = '0';
            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
        
            $this->SMSText->save($data1);
            $this->Session->setFlash("SMS Text Define  SuccessFully.");
            return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$data['tabType'])));
        }  
    }
    
     public function save_smstext(){
        if($this->request->is('POST')){
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['Escalations'];
            $data['clientId']=$ClientId;
      
            foreach($data as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('category','type','subtype','subtype1','subtype2');
                if(in_array($k,$keys))
                {
                    $Arr = explode('@@',$v);
                    $data1[$k] = $Arr[0];
                    $data1[$k.'Name'] = $Arr[1];
                }
            }
            
            $data1['sendType'] = '1'; 
            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
            
            
            //print_r($data1);die;
            

            $this->SMSText->save($data1);
            $this->Session->setFlash("Internal Communications Define  SuccessFully.");
            return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$data['tabType'])));
        }  
    }
    
    public function delete_matrix(){
        $id=$this->request->query['id'];
        $this->Matrix->deleteAll(array('id'=>$id,'clientId' => $this->Session->read('companyid')));
        $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$_REQUEST['tab'])));
    }
    
     public function delete_sms(){
        $id=$this->request->query['id'];
        $this->SMSText->deleteAll(array('id'=>$id,'clientId' => $this->Session->read('companyid')));
        $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$_REQUEST['tab'])));
    }
    

    public function matrix(){
    $this->layout='user';
    $ClientId = $this->Session->read('companyid');
   
    if($this->request->is('POST'))
    {
        $data = $this->request->data['Escalation'];

        foreach($data as $k=>$v)
        {
            $data1[$k] = addslashes($v);
            $keys = array('category','type','subtype','subtype1','subtype2');
            if(in_array($k,$keys))
            {
                $Arr = explode('@@',$v);
                $data1[$k] = $Arr[0];
                $data1[$k.'Name'] = $Arr[1];
            }
        }
        
        $data1['createdate'] = date('Y-m-d H:i:s');
        $data1['createby'] = $ClientId;
        
         $this->Matrix->save($data1);
         $this->Session->setFlash("Added SuccessFully");
         return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields'));
    }
    
    $clientData = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A')));
    $data = $this->Matrix->find('all',array('conditions'=>array('clientId'=>$ClientId)));
    $category = $this->ClientCategory->find('list',array('fields'=>array("id","EcrName"),'conditions'=>array('label'=>'1')));
    
    $cat = array();
    foreach($category as $k=>$v)
    {
        $cat[$k.'@@'.$v] = $v;
    }
    $category = $cat;
    unset($cat);
    //print_r($category); die;
    $this->set('client',$clientData);
    $this->set('category',$category);
    $this->set('data',$data);
    
}

public function smsText()
{
    $this->layout='user';
    $ClientId = $this->Session->read('companyid');
    
    if($this->request->is('POST'))
    {
        $data = $this->request->data['Escalation'];
        
        foreach($data as $k=>$v)
        {
            
           
            
            
            $data1[$k] = addslashes($v);
            $keys = array('category','type','subtype','subtype1','subtype2');
            if(in_array($k,$keys))
            {
                $Arr = explode('@@',$v);
                $data1[$k] = $Arr[0];
                $data1[$k.'Name'] = $Arr[1];
            }
        }
        
        
        $data1['sendType'] = '1'; //1 for sms send to client
        $data1['createdate'] = date('Y-m-d H:i:s');
        $data1['createby'] = $ClientId;
        
         $this->SMSText->save($data1);
         $this->Session->setFlash("Added SuccessFully");
         return $this->redirect(array('controller'=>'Escalations','action'=>'smsText'));
       
    }
    $clientData = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A')));
    $category = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('label'=>'1')));
    $data = $this->SMSText->find('all',array('conditions'=>array('clientId'=>$ClientId,'sendType'=>'1')));
    
    $cat = array();
    foreach($category as $k=>$v)
    {
        $cat[$k.'@@'.$v] = $v;
    }
    $category = $cat;
    unset($cat);
    
    $this->set('client',$clientData);
    $this->set('category',$category);
    $this->set('data',$data);
}
public function smsTextCustomer()
{
    $this->layout='user';
    $ClientId = $this->Session->read('companyid');
    
    if($this->request->is('POST'))
    {
        $data = $this->request->data['Escalation'];
        
        foreach($data as $k=>$v)
        {
            $data1[$k] = addslashes($v);
            $keys = array('category','type','subtype','subtype1','subtype2');
            if(in_array($k,$keys))
            {
                $Arr = explode('@@',$v);
                $data1[$k] = $Arr[0];
                $data1[$k.'Name'] = $Arr[1];
            }
        }
        
        $data1['sendType'] = '0'; //0 for sms send to customer
        $data1['createdate'] = date('Y-m-d H:i:s');
        $data1['createby'] = $ClientId;
        
         $this->SMSText->save($data1);
         $this->Session->setFlash("Added SuccessFully");
         return $this->redirect(array('controller'=>'Escalations','action'=>'smsTextCustomer'));
       
    }
    $clientData = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A')));
    $category = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('label'=>'1')));
    $data = $this->SMSText->find('all',array('conditions'=>array('clientId'=>$ClientId,'sendType'=>'0')));
    
    $cat = array();
    foreach($category as $k=>$v)
    {
        $cat[$k.'@@'.$v] = $v;
    }
    $category = $cat;
    unset($cat);
    
    $this->set('client',$clientData);
    $this->set('category',$category);
    $this->set('data',$data);
}

public function time_period()
{
    $this->layout='user';
    $ClientId = $this->Session->read('companyid');
    
    if($this->request->is('POST'))
    {
        $data = $this->request->data['Escalation'];
        
        foreach($data as $k=>$v)
        {
            $data1[$k] = addslashes($v);
        }
        
        $data1['sendType'] = '1';
        $data1['createdate'] = date('Y-m-d H:i:s');
        $data1['createby'] = $ClientId;
        
         $this->TimePeriod->save($data1);
         $this->Session->setFlash("Added SuccessFully");
         return $this->redirect(array('controller'=>'Escalations','action'=>'smsTextCustomer'));
       
    }
    $clientData = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A')));
    $category = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('label'=>'1')));
    $data = $this->TimePeriod->find('all',array('conditions'=>array('clientId'=>$ClientId)));
    $this->set('client',$clientData);
    $this->set('category',$category);
    $this->set('data',$data);
}

public function getParent()
{
    $ClientId = $this->Session->read('companyid');
    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
    $data = $this->request->data;		
    $data['Client']= $ClientId;
    $label ="Parent".$data['Label'];
		
    $data = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>$data));
    $this->set('data',$data);
    $this->set('label',$label);
    $this->layout="ajax";
}
	
public function add() 
{
    $this->layout='user';
    $text ='';
    $cleintName = $this->Session->read('companyname'); 
    if($this->request->is('Post'))
    {
        $ClientId = $this->Session->read('companyid');$arrEcr = array();$arrCapture = array();
	if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
	$data = $this->request->data['Escalation'];
                        
	$this->set('data1',$data);
	$notify ='';
	$alertEscalation = array('ClientId'=>$ClientId);
			
	if(empty($data['Parent'])){ return;}else{$alertEscalation['ecrId'] = addslashes($data['Parent']);}
	if(isset($data['Parent2'])&& $data['Parent2']!=''){$alertEscalation['ecrId']=addslashes($data['Parent2']);}
	if(isset($data['Parent3'])&& $data['Parent3']!=''){$alertEscalation['ecrId']=addslashes($data['Parent3']);}
	if(isset($data['Parent4'])&& $data['Parent4']!=''){$alertEscalation['ecrId']=addslashes($data['Parent4']);}
	if(isset($data['Parent5'])&& $data['Parent5']!=''){$alertEscalation['ecrId']=addslashes($data['Parent5']);}

	if(empty($data['notification'])){ return;} else{$notify = $alertEscalation['notification'] = addslashes($data['notification']);}

	if(!isset($data['timer']) && $data['timer']==''){ return;}else{$alertEscalation['timer'] = addslashes($data['timer']);}
			
	if($data['timer']=='0'){if(empty($data['dater0'])){ return;}else{$alertEscalation['timer'] = addslashes("Year");$alertEscalation['Year'] = addslashes($data['dater0']);}}
	else if($data['timer']=='1'){if(empty($data['dater1'])){ return;}else{$alertEscalation['timer'] = addslashes("Month"); $alertEscalation['Month'] = addslashes($data['dater1']);}}
	else if($data['timer']=='2'){if(empty($data['dater2'])){ return;}else{$alertEscalation['timer'] = addslashes("Week");$alertEscalation['Week'] = addslashes($data['dater2']);}}
	else if($data['timer']=='3'){if(empty($data['dater3'])){ return;}else{$alertEscalation['timer'] = addslashes("Day");$alertEscalation['Day'] = addslashes($data['dater3']);}}
	else if($data['timer']=='4'){if(empty($data['dater4'])){ return;}else{$alertEscalation['timer'] = addslashes("Hour");$alertEscalation['Hour'] = addslashes($data['dater4']);}}
						
	if(empty($data['type'])){ return;}else{$alertEscalation['type'] = addslashes($data['type']);}
	if($data['type']=='sms'){if(empty($data['sms'])){ return;}else{$alertEscalation['sms'] = addslashes($data['sms']);}}
	else if($data['type']=='email'){if(empty($data['email'])){ return;}else{$alertEscalation['email'] = addslashes($data['email']);}}
	else if($data['type']=='both'){if(empty($data['email'])){ return;}else{$alertEscalation['email'] = addslashes($data['email']);}
        if(empty($data['sms'])){ return;}else{$alertEscalation['sms'] = addslashes($data['sms']);}}
			
	if(empty($data['format'])){ return;}else{$alertEscalation['format'] = addslashes($data['format']);}
	if(empty($data['field'])){ return;}
	else
        {
            $alertEscalation['fields'] = $text = $msg = addslashes($data['field']);
					
            while(strpos($msg,'[tag]') && strpos($msg,'[/tag]'))
            {
                $str = substr($msg,strpos($msg,'[tag]'),( strpos($msg,'[/tag]')-strpos($msg,'[tag]')+6));
		$msg = str_replace($str,'',$msg);
		$str = str_replace('[tag]','',$str);
		$str = str_replace('[/tag]','',$str);
		$str = str_replace('Category','',$str);
						
		$arrEcr[] = $str;
            }
            $i = 0;
            while(strpos($msg,'[tag1]') && strpos($msg,'[/tag1]'))
            {
                $str = substr($msg,strpos($msg,'[tag1]'),( strpos($msg,'[/tag1]')-strpos($msg,'[tag1]')+7));
		$msg = str_replace($str,'',$msg);
		$str = str_replace('[tag1]','',$str);
		$str = str_replace('[/tag1]','',$str);
                                                
		if($ids = $this->FieldCreation->find('first',array('fields'=>array('Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldName'=>$str))))
                {
                    $arrCapture[$i++] = $ids['FieldCreation']['Priority'];                                                    
		}
                $strArr[] = $str;
            }
            $alertEscalation['ecrFields'] = implode(',',$arrEcr);
            $alertEscalation['status'] = "1";
            $alertEscalation['captureFields'] = implode(',',$arrCapture);
            unset($i);

	}
                                   
	$alertEscalation['createdate'] = date('Y-m-d H:i:s');
	if($this->Escalation->save($alertEscalation))
	{
            $escalationID = $this->Escalation->getLastInsertId();

            $data = array();
            $data['escalationId'] = $escalationID;
            $data['ecrFields'] = implode(',',$arrEcr);
            $data['captureFields'] = implode(',',$arrCapture);
            $data['type'] = $notify;
            $data['escalationType'] = $alertEscalation['timer'];
            $data['escalationTime'] = $alertEscalation[$alertEscalation['timer']];
            $job = "* * * * *";
            if($data['escalationType'] == 'Week'){$job = "* * * * ".$alertEscalation[$alertEscalation['timer']];}
					
            else if($data['escalationType'] == 'Year')
            {
                $date = date_create($alertEscalation[$alertEscalation['timer']]);
		
		$mm = intval(date_format($date,'m'));
		$dd = intval(date_format($date,'d'));						
		$hh = intval(date_format($date,'H'));
		$ii = intval(date_format($date,'i'));
						
		$job = ($ii ==''?'* ':$ii.' ').($hh ==''?'* ':$hh.' ').($dd ==''?'* ':$dd.' ').($mm ==''?'* ':$mm.' ').'*';
            }
            else if($data['escalationType'] == 'Month'){$job = "* * ".$alertEscalation[$alertEscalation['timer']]." * *";}
					
            else if($data['escalationType'] == 'Day')
            {
                $date = date_create($alertEscalation[$alertEscalation['timer']]);
		
		$hh = intval(date_format($date,'H'));
		$ii = intval(date_format($date,'i'));

		$job = ($ii ==''?'* ':'*/'.$ii.' ').($hh ==''?'* ':$hh.' ').'* * *';
            }
					
            else if($data['escalationType'] == 'Hour')
            {
                $date = date_create($alertEscalation[$alertEscalation['timer']]);
		$ii = intval(date_format($date,'i'));
		$job = ($ii ==''?'* ':'*/'.$ii.' ').'* * * *';
            }
					
            $data['clientId'] = $ClientId;
            $data['createdate'] = date('Y-m-d H:i:s');
            $this->Crone->save($data);
	$id = $this->Crone->getLastInsertId();
	
	$select = '';
        $conditions = '';
	$mail= false;
        $sms = false;
	$flag = false;
	for($i = 0; $i<count($arrEcr); $i++)
        {
            if($flag) $select .= ',';
            $select .= 'Category'.$arrEcr[$i];
            $flag = true;
	}
	if($select !='' && !empty($arrCapture))
	{
            $select ='Select '.$select.',';
	}
	else
	{
            $select ='Select '.$select;
	}
	
	$flag = false;
        
	for($i = 0; $i<count($arrEcr); $i++)
	{
            if($flag) $select .= ',';
            $select .= 'Field'.$arrEcr[$i];
            $flag = true;
	}
	$txt = "<?php include('connection.php'); \n\n";
                                        
        if($alertEscalation['type']=='email')
        {
            $mail = true;
            $conditions = "and emailSend is null";
            $txt  .= " $".''."filename=\"/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_".$cleintName."_\"".".date('d_m_Y_H_i_s')."."\"_Export.xls\";";
        }
        else if($alertEscalation['type']=='sms')
        {
            $conditions = "and smsSend is null";
            $txt .= " include('function.php'); \n\n"; 
            $sms = true;
        }
                                        else if($alertEscalation['type']=='both')
                                        {
                                            $mail = true; $sms = true;
                                            $conditions = "and emailSend is null and smsSend is null";
                                            $txt .= " include('function.php'); \n\n";
                                            $txt  .= " $".''."filename=\"/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_".$cleintName."_".".date('d_m_Y_H_i_s')."."_Export.xls\";";
                                        };
                                                                                
                                        $txt  .= "\n $".''."sel = mysql_query(\"".$select." from call_master where ClientId = '".$ClientId."'".$conditions." \"); \n";
					
					$txt .= "$".''."text ='".$text."';\n";
					$txt .="$".''."i =0; \n
					while(strpos($".''."text,'[tag]') && strpos($".''."text,'[/tag]'))
					{
						$".''."str = substr($".''."text,strpos($".''."text,'[tag]'),( strpos($".''."text,'[/tag]')-strpos($".''."text,'[tag]')+6));
						$".''."text = str_replace($".''."str,'',$".''."text);
                                                $".''."str = str_replace('[tag]','',$"."str);
                                                $".''."str = str_replace('[/tag]','',$"."str);    
						$".''."header[] = $".''."str;
					}
					while(strpos($".''."text,'[tag1]') && strpos($".''."text,'[/tag1]'))
					{
						$".''."str = substr($".''."text,strpos($".''."text,'[tag1]'),( strpos($".''."text,'[/tag1]')-strpos($".''."text,'[tag1]')+7));
						$".''."text = str_replace($".''."str,'',$".''."text);
                                                $".''."str = str_replace('[tag1]','',$"."str);
                                                $".''."str = str_replace('[/tag1]','',$"."str);    
						$".''."header[] = $".''."str;
					}

					
					";
                                        
                                        
					if($mail)
                                        {
                                           $txt .="\n $".''."text .= \"<table border='2'><tr>\";";
                                           
                                           $txt .= "\n for($".''."i=0; $".''."i<count($".''."header); $".''."i++)";
                                           $txt .= "{";
                                           $txt .= "\n $".''."text .= \"<th>\".$".''."header[$".''."i].\"</th>\";";
                                           $txt .= "}";
                                           
                                           $txt .= "\n $".''."text .= \"</tr>\";";
                                           
                                           $txt .= "while("." $"."Data = mysql_fetch_array($".''."sel)){ \n\n";
                                           $txt .= "\n $".''."text .= \"<tr>\";";
                                           
                                           $txt .= "\n for($".''."i=0; $".''."i<count($".''."header); $".''."i++)";
                                           $txt .= "{";
                                           $txt .= "\n $".''."text .= \"<td>\".$".''."Data[$".''."i].\"</td>\";";
                                           $txt .= "}";
                                           
                                           $txt .= "\n $".''."text .= \"</tr>\";";
                                           $txt .= "}";
                                           $txt .= "\n $".''."text .= \"</table>\";";
                                           
                                           $txt  .= " include('report-send.php'); \n\n";
                                           $txt  .= "file_put_contents("." $".''."filename,"." $".''."text); \n";
                                           //$txt  .= " $".''."filename=\"csv_data/$".''."filename\"; \n";
                                           $txt  .= " $".''."ReceiverEmail=array('Email'=>'".$alertEscalation['email']."',"."'Name'=>'".$alertEscalation['email']."'); \n";
                                           $txt  .= " $".''."SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); \n";
                                           $txt  .= " $".''."ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); \n";
                                           //$txt  .= " $".''."AddBcc=array(\"krishna.kumar@teammas.in\"); \n";
                                           $txt  .= " $".''."Attachment=array("." $".''."filename); \n";
                                           $txt  .= " $".''."Subject=\"DialDesk - Report Export\"; \n";
                                           
                                           $txt  .= " $".''.'EmailText .="<table><tr><td style=\"padding-left:12px;\">Hello '.$alertEscalation['email']."</td></tr>\"; \n";
                                           $txt  .= " $".''.'EmailText .="<tr><td style=\"padding-left:12px;\">Please find the attached Export</td></tr>";';
                                           $txt  .= "\n $"."".'EmailText .="</table>"; ';
                                           $txt  .= "\n $".""."emaildata=array('ReceiverEmail'=>"." $".""."ReceiverEmail,'SenderEmail'=>"." $".""."SenderEmail,'ReplyEmail'=>"." $"."ReplyEmail,'AddCc'=>"." $"."AddCc,'AddBcc'=>"." $"."AddBcc,'Subject'=>"." $".""."Subject,'EmailText'=>"." $".""."EmailText,'Attachment'=>"." $"."Attachment);";
                                           $txt  .= "\n $"."".'done = send_email('." $"."".'emaildata);';
                                           $txt  .= "\n if($".""."done=='1'){ $"."".'msg =  "Mail Sent Successfully !";}';
                                          $txt  .= "\n unlink("." $"."filename);";
                                        }
                                        if($sms)
                                        {
                                            
                                            $txt  .= "\n $".''."num['ReceiverNumber'] = ".$alertEscalation['sms']."; \n";
                                            $txt  .= " $".''."num['SmsText'] = " ." $".''."text; \n";
                                            $txt  .= "\n send_sms("." $".''."num);";
                                        }
                                        //$txt  .="\n }";
                                        $txt  .= "\n mysql_query(\"update call_master set emailSend='"."$".""."done',smsSend='"."$".""."sms' where ClientId = '".$ClientId."'\");";
                                        $txt  .=" ?>";
					$file = '/var/www/html/dialdesk/app/webroot/crone/'.$id.".php";
					$myfile = fopen($file,"w");
					fwrite($myfile, $txt);
					fclose($myfile);
				}

			$txt = "
### ### add job according to client based
$job php /var/www/html/dialdesk/app/webroot/crone/".$id.".php";

			$myfile = file_put_contents('/var/spool/cron/apache', $txt.PHP_EOL , FILE_APPEND);
			//$dir->chmod('/var/www/html/spool/root', 0777, true, array('root'));
    }
    $this->redirect(array('action'=>'index'));
}
	
public function view()
{
    $this->layout='user';		
    $ClientId = $this->Session->read('companyid');
    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
    $Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
    $this->set('Category',$Category);
    if(isset($this->request->query['id']))
    {
        $id = base64_decode($this->request->query['id']);				
	$this->set('escalation',$this->Escalation->find('all',array('fields'=>array('id','email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
    }
    $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
}



public function view_value()
{
    $this->layout='user';
    $ClientId = $this->Session->read('companyid');
    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
}	
public function delete()
{
    $this->layout='user';
    $ClientId = $this->Session->read('companyid');
    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
    $Category = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
    $id = base64_decode($this->request->query['id']);
    $this->Escalation->updateAll(array('status'=>"'0'"),array('id'=>$id));
            
    $this->redirect(array('action'=>'view','?'=>array('id'=>$this->request->query['id'])));
}

public function getEcr()
{
    $this->layout="ajax";
    if($this->request->is('POST'))
    {
        if(!empty($this->request->data))
        {
            $conditions['parent_id'] = $this->request->data['parent'];
            $conditions['Label'] = $this->request->data['Label'];
            $type = $this->request->data['type'];
            
            $category = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
            
            $cat = array();
            foreach($category as $k=>$v)
            {
                $cat[$k.'@@'.$v] = $v;
            }
            $category = $cat;
            
            unset($cat);
    
          
            
            $this->set('data',$category); 
            $this->set('type',$type); 
            $this->set('divtype',$this->request->data['divtype']); 
            $this->set('function',$this->request->data['function']); 
	}
    }
}

public function get_edit_ecr()
{
    $this->layout="ajax";
    if($this->request->is('POST'))
    {
        if(!empty($this->request->data))
        {
            $conditions['parent_id'] = $this->request->data['parent'];
            $conditions['Label'] = $this->request->data['Label'];
            $type = $this->request->data['type'];
            
            $category = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
            
            $cat = array();
            foreach($category as $k=>$v)
            {
                $cat[$k.'@@'.$v] = $v;
            }
            $category = $cat;
            unset($cat);
    
          
            $this->set('data',$category); 
            
            if(isset($this->request->data['slt']) && $this->request->data['slt'] !=""){
                $this->set('slt',$this->request->data['slt']); 
            }
          
            $this->set('type',$type); 
            $this->set('divtype',$this->request->data['divtype']); 
            $this->set('function',$this->request->data['function']); 
	}
    }
}

}
?>