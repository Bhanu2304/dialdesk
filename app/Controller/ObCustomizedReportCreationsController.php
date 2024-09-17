<?php
class ObCustomizedReportCreationsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('EcrRecord','ObfieldCreation','FieldMaster','CloseFieldData','ObReportTabMaster','ObReportHeaderMaster','CampaignName','ObecrMaster','CampaignName');
	
	
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
                        'get_sheet_name',
			'update_login');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
            $this->layout='user'; 
            $ClientId = $this->Session->read('companyid');
            
            $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A')));
            //print_r($Campaign);exit;
            $this->set('Campaign',$Campaign);
            
            $ecr = $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));	
            
            $this->set('SheetName',$this->ObecrMaster->find('list',array('fields'=>array("ecrName","Label"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1'))));
            $this->set('ScenarioName',$this->ObecrMaster->find('list',array('fields'=>array("ecrName","Label"),'conditions'=>array('Client'=>$ClientId),'group'=>'Label')));
            $this->set('FieldName',$this->ObfieldCreation->find('list',array('fields'=>array("FieldName","fieldNumber"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
            $this->set('CloseField',$this->CloseFieldData->find('list',array('fields'=>array("FieldName","fieldNumber"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
             
            $this->set('data',$this->ObReportTabMaster->find('all',array('conditions'=>array('client_id'=>$ClientId))));
             
            if ($this->request->is('post')) {
                
                $campaign_id = $this->request->data['campaign'];
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

                        $ExistSheet =   $this->ObReportTabMaster->find('first',array('fields'=>array("id","tab_order"),'conditions'=>$SheetArr));
                        if(empty($ExistSheet)){
                            $SheetArr['tab_order']=$tab_order;
                            $SheetArr['campaign_id']=$campaign_id;
                            if($this->ObReportTabMaster->save($SheetArr)){
                                $tab_id =   $this->ObReportTabMaster->getLastInsertId();
                                foreach($HeadArr as $val){  
                                    $HeaderArr[]=array(
                                        'tab_id'=>$tab_id,
                                        'header_name'=>$this->request->data['Rename_'.$val],
                                        'header_field'=>$val,
                                        'header_order'=>$this->request->data['Order_'.$val],
                                        );   
                                }

                                $this->ObReportHeaderMaster->saveAll($HeaderArr);
                            } 
                        }
                        else{
                            $tab_id     =   $ExistSheet['ObReportTabMaster']['id'];
                            $this->ObReportHeaderMaster->query("delete from report_header_master where tab_id='$tab_id'");
                            $this->ObReportHeaderMaster->query("update report_tab_master set tab_order='$tab_order'  where id='$tab_id'");
                            foreach($HeadArr as $val){  
                                $HeaderArr[]=array(
                                    'tab_id'=>$tab_id,
                                    'header_name'=>$this->request->data['Rename_'.$val],
                                    'header_field'=>$val,
                                    'header_order'=>$this->request->data['Order_'.$val],
                                    );   
                            }

                            $this->ObReportHeaderMaster->saveAll($HeaderArr);
                           
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
                    #print_r($this->request->data);die;
                    #echo $campaign_id;die;
                    if($this->request->data['SheetName'] !=""){
                        $sheet      =   explode("##", $this->request->data['SheetName']);
                        $tab_field  =   $sheet[0];
                        $tab_name   =   $sheet[1];
                        $ExistSheet =   $this->ObReportTabMaster->find('first',array('fields'=>array("id","tab_order"),'conditions'=>array('client_id'=>$ClientId,'campaign_id'=>$campaign_id,'tab_name'=>$tab_name,'tab_field'=>$tab_field)));
                        if(!empty($ExistSheet)){
                            $this->set('selectedData',$this->getexistheader($ExistSheet['ObReportTabMaster']['id']));  
                            $this->set('selectedOrder',$ExistSheet['ObReportTabMaster']['tab_order']);
                        }
                        $this->set('SelectSheetName',$this->request->data['SheetName']); 
                    }
                    
                }
                
                $this->set('campaign_id',$campaign_id);
                
            }

	}
        
        public function get_sheet_name()
        {
            $this->layout='ajax'; 
            $ClientId = $this->Session->read('companyid');
            $campaign = $this->request->data['campaign'];
            
            $this->set('SheetName',$this->ObecrMaster->find('list',array('fields'=>array("ecrName","Label"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaign,'Label'=>'1'))));
            
        }
        
        public function getexistheader($tab_id){
            $ExistSheet =   $this->ObReportHeaderMaster->find('all',array('conditions'=>array('tab_id'=>$tab_id)));          
            foreach($ExistSheet as $row){
                $selectedData[$row['ObReportHeaderMaster']['header_field']]['order']=$row['ObReportHeaderMaster']['header_order'];
                $selectedData[$row['ObReportHeaderMaster']['header_field']]['rename']=$row['ObReportHeaderMaster']['header_name'];
            }
            return $selectedData;
        }
        
        
	public function delete_user(){
            $id  = $this->request->query['id'];
            $this->ObReportTabMaster->delete(array('id'=>$id,'client_id' => $this->Session->read('companyid')));
            $this->ObReportHeaderMaster->deleteAll(array('tab_id'=>$id));
            $this->redirect(array('action' => 'index'));
	}
	

}
?>