<?php
class ObCustomizedReportCreationsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('EcrRecord','FieldMaster','CloseFieldData','ReportTabMaster','ReportHeaderMaster','CampaignName','ObecrMaster');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow(
			'checkSmtpValidation',
                        'checkexistmail',
                        'check_exist_phone',
                        'edit_exist_email',
                        'edit_exist_phone',
			'save_login_creation',
                        'fetchCategoryTree',
			'check_exist_email',
			'sendotp','send_sms',
			'matchotp',
			'message',
			'view_created_login',
			'edit_login',
			'delete_user',
                        'geturm_chield',
                        'view_edit_login',
			'update_login');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
            $this->layout='user'; 
            $ClientId = $this->Session->read('companyid');
            
            $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A')));
            
            $ecr = $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));	
            
            $this->set('SheetName',$this->EcrRecord->find('list',array('fields'=>array("ecrName","Label"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1'))));
            $this->set('ScenarioName',$this->EcrRecord->find('list',array('fields'=>array("ecrName","Label"),'conditions'=>array('Client'=>$ClientId),'group'=>'Label')));
            $this->set('FieldName',$this->FieldMaster->find('list',array('fields'=>array("FieldName","fieldNumber"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
            $this->set('CloseField',$this->CloseFieldData->find('list',array('fields'=>array("FieldName","fieldNumber"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
             
            $this->set('data',$this->ReportTabMaster->find('all',array('conditions'=>array('client_id'=>$ClientId))));
             
            if ($this->request->is('post')) {
                
                if(isset($this->request->data['Save']) && $this->request->data['Save']=="Submit"){
                    $HeadArr    =   $this->request->data['HeaderName'];
                    if(!empty($HeadArr)){
                        $sheet      =   explode("##", $this->request->data['SheetName']);
                        $tab_field  =   $sheet[0];
                        $tab_name   =   $sheet[1];
                        $tab_order  =   $this->request->data['SheetOrder'];

                        $SheetArr=array(
                            'client_id'=>$ClientId,
                            'tab_name'=>$tab_name,
                            'tab_field'=>$tab_field,
                            );

                        $ExistSheet =   $this->ReportTabMaster->find('first',array('fields'=>array("id","tab_order"),'conditions'=>$SheetArr));
                        if(empty($ExistSheet)){
                            $SheetArr['tab_order']=$tab_order;
                            if($this->ReportTabMaster->save($SheetArr)){
                                $tab_id =   $this->ReportTabMaster->getLastInsertId();
                                foreach($HeadArr as $val){  
                                    $HeaderArr[]=array(
                                        'tab_id'=>$tab_id,
                                        'header_name'=>$this->request->data['Rename_'.$val],
                                        'header_field'=>$val,
                                        'header_order'=>$this->request->data['Order_'.$val],
                                        );   
                                }

                                $this->ReportHeaderMaster->saveAll($HeaderArr);
                            } 
                        }
                        else{
                            $tab_id     =   $ExistSheet['ReportTabMaster']['id'];
                            $this->ReportHeaderMaster->query("delete from report_header_master where tab_id='$tab_id'");
                            $this->ReportHeaderMaster->query("update report_tab_master set tab_order='$tab_order'  where id='$tab_id'");
                            foreach($HeadArr as $val){  
                                $HeaderArr[]=array(
                                    'tab_id'=>$tab_id,
                                    'header_name'=>$this->request->data['Rename_'.$val],
                                    'header_field'=>$val,
                                    'header_order'=>$this->request->data['Order_'.$val],
                                    );   
                            }

                            $this->ReportHeaderMaster->saveAll($HeaderArr);
                           
                        }
                        $this->set('selectedOrder',$tab_order);
                        $this->set('selectedData',$this->getexistheader($tab_id));
                        $this->set('SelectSheetName',$this->request->data['SheetName']);
                        $this->Session->setFlash('<span style="color:green;">Customized report mis structure update successfully.</span>');
                    }
                    else{
                        $this->set('SelectSheetName',$this->request->data['SheetName']);
                        $this->Session->setFlash('<span style="color:red;">Please select at least one option.</span>');
                        //$this->redirect(array('action' => 'index'));
                    }
                    
                }
                else{
                    
                    if($this->request->data['SheetName'] !=""){
                        $sheet      =   explode("##", $this->request->data['SheetName']);
                        $tab_field  =   $sheet[0];
                        $tab_name   =   $sheet[1];
                        $ExistSheet =   $this->ReportTabMaster->find('first',array('fields'=>array("id","tab_order"),'conditions'=>array('client_id'=>$ClientId,'tab_name'=>$tab_name,'tab_field'=>$tab_field)));
                        if(!empty($ExistSheet)){
                            $this->set('selectedData',$this->getexistheader($ExistSheet['ReportTabMaster']['id']));  
                            $this->set('selectedOrder',$ExistSheet['ReportTabMaster']['tab_order']);
                        }
                        $this->set('SelectSheetName',$this->request->data['SheetName']); 
                    }
                    
                }
                
            }

	}
        
        public function getexistheader($tab_id){
            $ExistSheet =   $this->ReportHeaderMaster->find('all',array('conditions'=>array('tab_id'=>$tab_id)));          
            foreach($ExistSheet as $row){
                $selectedData[$row['ReportHeaderMaster']['header_field']]['order']=$row['ReportHeaderMaster']['header_order'];
                $selectedData[$row['ReportHeaderMaster']['header_field']]['rename']=$row['ReportHeaderMaster']['header_name'];
            }
            return $selectedData;
        }
        
        
	public function delete_user(){
            $id  = $this->request->query['id'];
            $this->ReportTabMaster->delete(array('id'=>$id,'client_id' => $this->Session->read('companyid')));
            $this->ReportHeaderMaster->deleteAll(array('tab_id'=>$id));
            $this->redirect(array('action' => 'index'));
	}
	

}
?>