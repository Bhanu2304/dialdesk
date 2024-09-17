<?php
class SrDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('UploadExistingBase','ClientCategory','FieldCreation','CloseLoopMaster','update_closeloop','FieldMaster','FieldValue','EcrMaster','CallMaster','CloseFieldData','CloseFieldDataValue','LogincreationMaster','IncallScenarios');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function index() {
        $this->layout='user';
	$clientId = $this->Session->read('companyid');
                
        $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        $fieldSearch = $this->FieldMaster->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        foreach($fieldSearch as $f)
        {
            $headervalue[] = 'Field'.$f; 
        }
        
        
        
        $fieldName1 = $this->CloseFieldData->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        $fieldSearch1 = $this->CloseFieldData->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        
        foreach($fieldSearch1 as $f1){
            $headervalue1[] = 'CField'.$f1; 
        }
        
        
        $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,$headervalue1,
                array('LeadId','CallType','CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated'));
        //$fieldSearch = implode(",",$fieldSearch);
        
        $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
        $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		
        
        $this->set('Category1',$this->EcrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>1),'order'=>array('ecrName'=>'asc'))));
        $this->set('Category2',$this->EcrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>2),'order'=>array('ecrName'=>'asc'))));
        $this->set('Category3',$this->EcrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>3),'order'=>array('ecrName'=>'asc'))));
        $this->set('Category4',$this->EcrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>4),'order'=>array('ecrName'=>'asc'))));
        $this->set('Category5',$this->EcrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>5),'order'=>array('ecrName'=>'asc'))));
        
        
        $this->set('CloseStatus',$this->CloseLoopMaster->find('list',array('fields'=>array('close_loop_category','close_loop_category'),'conditions'=>array('parent_id'=>NULL,'label'=>1,'client_id'=>$clientId),'order'=>array('close_loop_category'=>'asc'),'group'=>array('close_loop_category'))));
        
        $this->set('fieldName',$fieldName);
        $this->set('fieldName1',$fieldName1);
        $this->set('fieldValue',$fieldValue);
        $this->set('ecr',$ecr);
        
        if($this->request->is("POST")){  
            $ClientId = $this->Session->read('companyid');
            $search=$this->request->data['IbExportReports'];
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $data['ClientId']=$ClientId;
            
            if(isset($search['startdate']) && $search['startdate'] !=""){$data['date(CallDate) >=']=date("Y-m-d",$startdate);}else{unset($data['date(CallDate) >=']);}
            if(isset($search['enddate']) && $search['enddate'] !=""){$data['date(CallDate) <=']=date("Y-m-d",$enddate);}else{unset($data['date(CallDate) <=']);}
            if(isset($search['firstsr']) && $search['firstsr'] !=""){$data['SrNo >=']=$search['firstsr'];}else{unset($data['SrNo >=']);}
            if(isset($search['lastsr']) && $search['lastsr'] !=""){$data['SrNo <=']=$search['lastsr'];}else{unset($data['SrNo <=']);}
            if(isset($search['MSISDN']) && $search['MSISDN'] !=""){$data['MSISDN']=$search['MSISDN'];}else{unset($data['MSISDN']);}
            if(isset($search['Category1']) && $search['Category1'] !=""){$data['Category1']=$search['Category1'];}else{unset($data['Category1']);}
            if(isset($search['Category2']) && $search['Category2'] !=""){$data['Category2']=$search['Category2'];}else{unset($data['Category2']);}
            if(isset($search['Category3']) && $search['Category3'] !=""){$data['Category3']=$search['Category3'];}else{unset($data['Category3']);}
            if(isset($search['Category4']) && $search['Category4'] !=""){$data['Category4']=$search['Category4'];}else{unset($data['Category4']);}
            if(isset($search['Category5']) && $search['Category5'] !=""){$data['Category5']=$search['Category5'];}else{unset($data['Category5']);}
            
            if(isset($search['Category1']) && $search['Category1'] ==="All"){unset($data['Category1']);}
            if(isset($search['Category2']) && $search['Category2'] ==="All"){unset($data['Category2']);}
            if(isset($search['Category3']) && $search['Category3'] ==="All"){unset($data['Category3']);}
            if(isset($search['Category4']) && $search['Category4'] ==="All"){unset($data['Category4']);}
            if(isset($search['Category5']) && $search['Category5'] ==="All"){unset($data['Category5']);}
            
            if(isset($search['CloseLoopCate1']) && $search['CloseLoopCate1'] !=""){$data['CloseLoopCate1']=$search['CloseLoopCate1']=='0'?null:$search['CloseLoopCate1'];}else{unset($data['CloseLoopCate1']);}
            
            
            
             
            /*
            $category_field   = $this->get_category_field($ClientId);
            $capcture_field   = $this->get_chapcher_field($ClientId);
            $callmaster_field = $this->get_fields(count($capcture_field),"Field");
            $header_field=array_merge(array('MSISDN'),$category_field,$capcture_field,array('CallDate'));
            $values_field=array_merge(array('MSISDN'),$category_field,$callmaster_field,array('CallDate'));
            */
            
            //if($data['Category3'] =="All"){$data['Category3']=''}
            
            if($this->Session->read("role") =="agent"){
                $username=$this->Session->read("agent_username");
                $UserArr = $this->LogincreationMaster->find('first',array('conditions' =>array('username'=>$username,'create_id' =>$clientId)));
                $inboundAccess=explode(',',$UserArr['LogincreationMaster']['inbound_access']);
                $page1= $this->IncallScenarios->find('all',array('fields'=>array('Label','ecrName'),'conditions'=>array('Client'=>$clientId,'id'=>$inboundAccess)));

                foreach($page1 as $val){ 
                    $label=$val['IncallScenarios']['Label'];
                    $name =$val['IncallScenarios']['ecrName'];
                    $data1[]["Category{$label}"]=$name; 
                }
                
                if(!empty($data1)){
                    $data=array_merge($data,$data1);
                }
            }

            $this->Session->write("search",$search);
            $this->Session->write("fieldSearch",$fieldSearch);
            $this->Session->write("data",$data);
            $this->Session->write("headervalue",$headervalue);
            $this->Session->write("headervalue1",$headervalue1);

            $tArr = $this->CallMaster->find('all',array('fields'=>$fieldSearch,'conditions' =>$data));
           
            
            
            

            //$this->set('header',$header_field);
            
            
            //print_r($tArr);die;
            
            $this->set('history',$tArr);
            $this->set('headervalue',$headervalue);
            $this->set('headervalue1',$headervalue1);
            $this->set('showVal',$search);
            }
            
            
            if(isset($_REQUEST['status']) && $_REQUEST['status'] =="success"){
                
                $search = $this->Session->read('search');
                $fieldSearch = $this->Session->read('fieldSearch');
                $data = $this->Session->read('data');
                $headervalue = $this->Session->read('headervalue');
                $headervalue1 = $this->Session->read('headervalue1');
                
                $tArr = $this->CallMaster->find('all',array('fields'=>$fieldSearch,'conditions' =>$data));
            
                $this->set('history',$tArr);
                $this->set('headervalue',$headervalue);
                $this->set('headervalue1',$headervalue1);
                $this->set('showVal',$search);
            }
        
            //$history = $this->CallMaster->find('all',array('conditions'=>array('ClientId'=>$clientId),'order'=>array('CallDate'=>'desc')));	
            //$this->set('history',$history);		
    }
    
    public function view_details() {
        if(isset($_REQUEST['id'])){  
            $this->layout='ajax';
            $clientId = $this->Session->read('companyid');
            $id=$_REQUEST['id'];

            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            
            $fieldSearch = $this->FieldMaster->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        foreach($fieldSearch as $f)
        {
            $headervalue[] = 'Field'.$f; 
        } 
        $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,
                array('LeadId','CallType', 'CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated'));
        //$fieldSearch = implode(",",$fieldSearch);
            
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
            $this->set('ecr',$ecr);

            $data = $this->CallMaster->find('first',array('fields'=>$fieldSearch,'conditions'=>array('ClientId'=>$clientId,'Id'=>$id),'order'=>array('CallDate'=>'desc')));
            $this->set('history',$data);
            $this->set('headervalue',$headervalue);
            

            $newArr=$this->CloseLoopMaster->query("SELECT clm.close_loop_category,clm.id FROM closeloop_master clm
            INNER JOIN call_master cm
            ON CONCAT(IF(clm.CategoryName1='All',1,CONCAT(cm.Category1,IF(clm.CategoryName2='All',1,CONCAT(cm.Category2,IF(clm.CategoryName3='All',1,
            CONCAT(cm.Category3,IF(clm.CategoryName4='All',1,
            CONCAT(cm.Category4,IF(clm.CategoryName5='All',1,cm.Category5)
            ))))))))) = CONCAT(IF(clm.CategoryName1='All',1,CONCAT(clm.CategoryName1,IF(clm.CategoryName2='All',1,CONCAT(clm.CategoryName2,IF(clm.CategoryName3='All',1,
            CONCAT(clm.CategoryName3,IF(clm.CategoryName4='All',1,
            CONCAT(clm.CategoryName4,IF(clm.CategoryName5='All',1,clm.CategoryName5)
            )))))))))
            WHERE clm.client_id='$clientId' AND clm.close_loop='manual' AND clm.label='1' AND cm.Id='$id'");

            $this->set('mpcat',$newArr);

        }	
    }
    
    
    

    public function getECRName($name,$clientId,$pid){
        $data =$this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>array('parent_id'=>$pid,'Client'=>$clientId,'ecrName'=>$name))); 
        if(!empty($data)){
        return $data['EcrMaster']['id'];
        }

    }
    
     public function getECRId($name,$clientId){
        $data =$this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>array('Client'=>$clientId,'ecrName'=>$name))); 
        if(!empty($data)){
        return $data['EcrMaster']['id'];
        }

    }
    
        
    public function update_closeloop(){
        if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            $close_cat1=$_REQUEST['close_cat1'];
            $close_cat2=$_REQUEST['close_cat2'];
            $close_date=$_REQUEST['close_date'];
          
            
            
            if($close_date !=""){
                $close_date = date("Y-m-d", strtotime($close_date)).' '.date('H:i:s');
            }

            $pl=$this->CloseLoopMaster->find('first',array('fields'=>array('close_loop','close_loop_category'),'conditions'=>array('client_id'=>$this->Session->read('companyid'),'id'=>$close_cat1)));
            
            if(isset($pl['CloseLoopMaster']['close_loop']) && $pl['CloseLoopMaster']['close_loop'] !=""){$data['close_loop']="'".$pl['CloseLoopMaster']['close_loop']."'";}else{unset($data['close_loop']);}
            if(isset($pl['CloseLoopMaster']['close_loop_category']) && $pl['CloseLoopMaster']['close_loop_category'] !=""){$data['CloseLoopCate1']="'".$pl['CloseLoopMaster']['close_loop_category']."'";}else{unset($data['CloseLoopCate1']);}
            if(isset($_REQUEST['close_cat2']) && $_REQUEST['close_cat2'] !=""){$data['CloseLoopCate2']="'".$_REQUEST['close_cat2']."'";}else{unset($data['CloseLoopCate2']);}
            if(isset($_REQUEST['closelooping_remarks']) && $_REQUEST['closelooping_remarks'] !=""){$data['closelooping_remarks']="'".$_REQUEST['closelooping_remarks']."'";}else{unset($data['closelooping_remarks']);}
             
            
            //$data['CloseLoopingDate']="'".$close_date."'";
            $data['FollowupDate']="'".$close_date."'";
            $data['CloseLoopingDate']="'".$date = date('Y-m-d H:i:s')."'";
            $data['UpdateDate']="'".$date = date('Y-m-d H:i:s')."'";
            
            if($this->UploadExistingBase->updateAll($data,array('Id'=>$id,'ClientId' => $this->Session->read('companyid')))){
                echo "1";die;
            }
            else{
                echo "";die;
            }		
        }
    }

    public function get_sub_closeloop(){
        $this->layout='ajax';
        if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] !=""){
            $ParentId =$_REQUEST['parent_id'];
            $ClientId=$this->Session->read('companyid');
            $clcat =$this->CloseLoopMaster->find('list',array('fields'=>array("close_loop_category","close_loop_category"),'conditions'=>array('parent_id'=>$ParentId,'label'=>2,'client_id'=>$ClientId)));
            $this->set('clcat',$clcat);  
        }
    }
    
    public function get_date_picker(){
        $this->layout='ajax';
        if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] !=""){
            $ParentId =$_REQUEST['parent_id'];
            $ClientId=$this->Session->read('companyid');
            $clcat =$this->CloseLoopMaster->find('first',array('fields'=>array("close_looping_date"),'conditions'=>array('id'=>$ParentId,'client_id'=>$ClientId)));
            if($clcat['CloseLoopMaster']['close_looping_date'] !=""){
                echo $clcat['CloseLoopMaster']['close_looping_date'];
            }
            else{
               echo ""; 
            }
            die; 
        }
    }
	
	
	
	
	
	
}
?>