<?php
class OutboundCloseDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('CloseFieldData','CloseFieldDataValue','CloseUpdate','ObField','ObfieldValue','ObecrMaster','CallMasterOut','OutboundCloseLoopMaster','ObCloseFieldData','ObCloseFieldDataValue','ObCloseUpdate');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    
    public function get_sub_closeloop(){
        $this->layout='ajax';
        if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] !=""){
            $ParentId =$_REQUEST['parent_id'];
            $CampaignId =$_REQUEST['CampaignId'];
            $ClientId=$this->Session->read('companyid');
            $clcat =$this->OutboundCloseLoopMaster->find('list',array('fields'=>array("close_loop_category","close_loop_category"),'conditions'=>array('parent_id'=>$ParentId,'label'=>2,'client_id'=>$ClientId,'CampaignId'=>$CampaignId)));  
            $this->set('clcat',$clcat);
        }
    }
    
    public function get_date_picker(){
        $this->layout='ajax';
        if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] !=""){
            $ParentId =$_REQUEST['parent_id'];
            $CampaignId =$_REQUEST['CampaignId'];
            $ClientId=$this->Session->read('companyid');
            $clcat =$this->OutboundCloseLoopMaster->find('first',array('fields'=>array("close_looping_date"),'conditions'=>array('id'=>$ParentId,'client_id'=>$ClientId,'CampaignId'=>$CampaignId)));
            if($clcat['OutboundCloseLoopMaster']['close_looping_date'] !=""){
                echo $clcat['OutboundCloseLoopMaster']['close_looping_date'];
            }
            else{
               echo ""; 
            }
            die; 
        }
    }
    
    public function update_closeloop(){
        if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            $CampaignId =$_REQUEST['CampaignId'];
            
            $close_cat1=$_REQUEST['close_cat1'];
            $close_cat2=$_REQUEST['close_cat2'];
            $close_date=$_REQUEST['close_date'];
                    
            if($close_date !=""){
                $close_date = date("Y-m-d", strtotime($close_date)).' '.date('H:i:s');
            }

            $pl=$this->OutboundCloseLoopMaster->find('first',array('fields'=>array('close_loop','close_loop_category'),'conditions'=>array('client_id'=>$this->Session->read('companyid'),'CampaignId'=>$CampaignId,'id'=>$close_cat1)));
            
            if(isset($pl['OutboundCloseLoopMaster']['close_loop']) && $pl['OutboundCloseLoopMaster']['close_loop'] !=""){$data['close_loop']="'".$pl['OutboundCloseLoopMaster']['close_loop']."'";}else{unset($data['close_loop']);}
            if(isset($pl['OutboundCloseLoopMaster']['close_loop_category']) && $pl['OutboundCloseLoopMaster']['close_loop_category'] !=""){$data['CloseLoopCate1']="'".$pl['OutboundCloseLoopMaster']['close_loop_category']."'";}else{unset($data['CloseLoopCate1']);}
            if(isset($_REQUEST['close_cat2']) && $_REQUEST['close_cat2'] !=""){$data['CloseLoopCate2']="'".$_REQUEST['close_cat2']."'";}else{unset($data['CloseLoopCate2']);}
            if(isset($_REQUEST['closelooping_remarks']) && $_REQUEST['closelooping_remarks'] !=""){$data['closelooping_remarks']="'".$_REQUEST['closelooping_remarks']."'";}else{unset($data['closelooping_remarks']);}
             
            
            
            //$data['CloseLoopingDate']="'".$close_date."'";
            $data['FollowupDate']="'".$close_date."'";
            $data['CloseLoopingDate']="'".$date = date('Y-m-d H:i:s')."'";
            $data['UpdateDate']="'".$date = date('Y-m-d H:i:s')."'";
            
          
            
            if($this->CallMasterOut->updateAll($data,array('Id'=>$id,'ClientId' => $this->Session->read('companyid')))){
                echo "1";die;
            }
            else{
                echo "";die;
            }		
        }
    }
    
	
    public function view_close_fields() {
        if(isset($_REQUEST['id'])){  
            $this->layout='user';
            $clientId = $this->Session->read('companyid');

            //--------------------------------------------------------------------------
            
            $id = $_REQUEST['id'];
            $CampaignId = $_REQUEST['CampaignId'];
            
            $fieldName = $this->ObField->find('all',array('conditions'=>array('ClientId'=>$clientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
  
            $fieldSearch = $this->ObField->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            foreach($fieldSearch as $f)
            {
                $headervalue[] = 'Field'.$f; 
            }
             
            $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,
                array('LeadId','CallType', 'CallDate','close_loop','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated'));
        
            //$fieldSearch = implode(",",$fieldSearch);
              
            $fieldValue = $this->ObfieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $ecr = $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId,'CampaignId'=>$CampaignId,),'group'=>'Label','order'=>array('Label'=>'asc')));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
            $this->set('ecr',$ecr);

            
            $data = $this->CallMasterOut->find('first',array('fields'=>$fieldSearch,'conditions'=>array('ClientId'=>$clientId,'Id'=>$id),'order'=>array('CallDate'=>'desc')));
           
            $this->set('history',$data);
            $this->set('headervalue',$headervalue);
            
           

            $newArr=$this->OutboundCloseLoopMaster->query("SELECT clm.close_loop_category,clm.id FROM outbound_closeloop_master clm
            INNER JOIN call_master_out cm
            ON CONCAT(IF(clm.CategoryName1='All',1,CONCAT(cm.Category1,IF(clm.CategoryName2='All',1,CONCAT(cm.Category2,IF(clm.CategoryName3='All',1,
            CONCAT(cm.Category3,IF(clm.CategoryName4='All',1,
            CONCAT(cm.Category4,IF(clm.CategoryName5='All',1,cm.Category5)
            ))))))))) = CONCAT(IF(clm.CategoryName1='All',1,CONCAT(clm.CategoryName1,IF(clm.CategoryName2='All',1,CONCAT(clm.CategoryName2,IF(clm.CategoryName3='All',1,
            CONCAT(clm.CategoryName3,IF(clm.CategoryName4='All',1,
            CONCAT(clm.CategoryName4,IF(clm.CategoryName5='All',1,clm.CategoryName5)
            )))))))))
            WHERE clm.client_id='$clientId' AND clm.close_loop='manual' AND clm.label='1' AND cm.Id='$id'");

            $this->set('mpcat',$newArr);
            $this->set('CampaignId',$CampaignId);
            
            
           //--------------------------------------------------------------------------
           
            $fieldValue1 = $this->ObCloseFieldDataValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $fieldName1 = $this->ObCloseFieldData->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $CArr = $this->ObCloseUpdate->find('first',array('conditions'=>array('Id'=>$_REQUEST['id'],'ClientId' =>$clientId)));
            
            $this->set('fieldName1',$fieldName1);
            $this->set('fieldValue1',$fieldValue1);
            $this->set('callId',$_REQUEST['id']);
            $this->set('CArr',$CArr['ObCloseUpdate']);
        }	
    }
    
    public function update_srclose_field(){ 
        if($this->request->is("POST")){  
            $ClientId = $this->Session->read('companyid');
            $Id=$this->request->data['Id'];
            $CampaignId=$this->request->data['CampaignId'];
            $data=$this->request->data['OutboundCloseDetails'];

            foreach($data as $kay=>$val){ 
                $dataArr[$kay]="'".$val."'";          
            }
            $dataArr['CFieldUpdate']="'".date('Y-m-d H:i:s')."'";          
                    
            $this->ObCloseUpdate->updateAll($dataArr,array('Id'=>$Id,'ClientId' =>$ClientId));
            $this->Session->setFlash('Your data update successfully.');
            $this->redirect(array('controller'=>'OutboundCloseDetails','action' => 'view_close_fields','?'=>array('id'=>$Id,'CampaignId'=>$CampaignId)));
        }
    }
		
}
?>