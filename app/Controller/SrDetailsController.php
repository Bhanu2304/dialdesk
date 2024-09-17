<?php
class SrDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('UploadExistingBase','ClientCategory','FieldCreation','CloseLoopMaster','update_closeloop','FieldMaster','FieldValue','EcrMaster','CallMaster','CloseFieldData','CloseFieldDataValue','LogincreationMaster','IncallScenarios','InCallActionCroneJob','InCallActionMatrix','InCallActionSms','CloseStatusHistory','PlanMaster','BalanceMaster','PaymentOrderNo','RegistrationMaster','CloseFieldData','CloseFieldDataValue','CloseUpdate','FieldMaster','FieldValue','EcrMaster','CallMaster','CloseLoopMaster','CloseStatusHistory');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
        $this->Auth->allow(
                'bluedartstatusreturn','bluedartstatusforword',
                'payment_gateway',
                'set_order','getShipmentSlip','authenticatShyplite',
                'update_payment_det','selectCategory','selectCategory1');
    }
	public function bluedartstatusreturn($number){
        $url="http://www.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=NDA61542&awb=awb&numbers=$number&format=html&lickey=j35ustq6qpjrhlqtunpftfuiqssklfl3&verno=1.3&scan=1";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        return $response = curl_exec($ch);
    }
    public function bluedartstatusforword($number){
        $url="http://www.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=NDA61542&awb=awb&numbers=$number&format=html&lickey=j35ustq6qpjrhlqtunpftfuiqssklfl3&verno=1.3&scan=1";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        return $response = curl_exec($ch);
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
        $headervalue1=array();
        foreach($fieldSearch1 as $f1){
            $headervalue1[] = 'CField'.$f1; 
        }
        
        //print_r($headervalue1); exit;
        
        
        $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,$headervalue1,
                array('LeadId','CallType','CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','CaseCloseBy','tat','duedate','callcreated','AbandStatus','AWBNo','TokenNumber','Ret_AWBNo','Ret_TokenNumber','Ret_PikupDate','AreaPincode'));
        
        




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
            $data['CallType !=']='Upload';
            
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
            //echo $this->Session->read("role");exit;
            if($this->Session->read("role") =="agent"){
                $username=$this->Session->read("agent_username");
                $UserArr = $this->LogincreationMaster->find('first',array('conditions' =>array('username'=>$username,'create_id' =>$clientId)));
                $inboundAccess=explode(',',$UserArr['LogincreationMaster']['inbound_access']);
                
                $page1= $this->IncallScenarios->find('all',array('fields'=>array('Label','ecrName'),'conditions'=>array('Client'=>$clientId,'id'=>$inboundAccess)));
                //print_r($page1);exit;
                foreach($page1 as $val){ 
                    $label=$val['IncallScenarios']['Label'];
                    $name =$val['IncallScenarios']['ecrName'];
                    $data1[]["Category{$label}"]=$name; 
                }
                
                if(!empty($data1)){
                   // $data=array_merge($data,$data1);
                }
            }
            
            
           //print_r($data);exit;
            

            $this->Session->write("search",$search);
            $this->Session->write("fieldSearch",$fieldSearch);
            $this->Session->write("data",$data);
            $this->Session->write("headervalue",$headervalue);
            $this->Session->write("headervalue1",$headervalue1);
            
            // 3 months data
            $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
            if (!isset($search['startdate']) || strtotime($search['startdate']) < strtotime($threeMonthsAgo)) {
                $data['date(CallDate) >='] = $threeMonthsAgo;
            }
            #$data['date(CallDate) >='] = $threeMonthsAgo;
            #print_r($data);die;
            $tArr = $this->CallMaster->find('all',array('fields'=>array_merge(array('ClientId'),$fieldSearch),'conditions' =>$data));
            
            
            
            
            

            //$this->set('header',$header_field);
            
            
            //print_r($tArr);die;
            
	   $this->set('ClientId',$ClientId);
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
                
                $tArr = $this->CallMaster->find('all',array('fields'=>array_merge(array('ClientId'),$fieldSearch),'conditions' =>$data));
            
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
			
			$role = $this->Session->read('role');
			if($role=="agent"){
				$userby	=	$role."-".$this->Session->read('agent_username');
			}
			else if($role=="client"){
				$userby	=	$role."-".$this->Session->read('email');
			}
			else if($role=="admin"){
				$userby	=	$role."-".$this->Session->read('admin_email');
			}
            

          
            if($close_date !=""){
                $close_date = date("Y-m-d", strtotime($close_date)).' '.date('H:i:s');
            }

            $pl=$this->CloseLoopMaster->find('first',array('fields'=>array('close_loop','close_loop_category','InCallStatus'),'conditions'=>array('client_id'=>$this->Session->read('companyid'),'id'=>$close_cat1)));
            
            
            
            if(isset($pl['CloseLoopMaster']['close_loop']) && $pl['CloseLoopMaster']['close_loop'] !=""){$data['close_loop']="'".$pl['CloseLoopMaster']['close_loop']."'";}else{unset($data['close_loop']);}
            if(isset($pl['CloseLoopMaster']['close_loop_category']) && $pl['CloseLoopMaster']['close_loop_category'] !=""){$data['CloseLoopCate1']="'".$pl['CloseLoopMaster']['close_loop_category']."'";}else{unset($data['CloseLoopCate1']);}
            if(isset($_REQUEST['close_cat2']) && $_REQUEST['close_cat2'] !=""){$data['CloseLoopCate2']="'".$_REQUEST['close_cat2']."'";}else{unset($data['CloseLoopCate2']);}
            if(isset($_REQUEST['closelooping_remarks']) && $_REQUEST['closelooping_remarks'] !=""){$data['closelooping_remarks']="'".$_REQUEST['closelooping_remarks']."'";}else{unset($data['closelooping_remarks']);}
             
            
            //$data['CloseLoopingDate']="'".$close_date."'";
            $data['FollowupDate']="'".$close_date."'";
            $data['CloseLoopingDate']="'".$date = date('Y-m-d H:i:s')."'";
            $data['UpdateDate']="'".$date = date('Y-m-d H:i:s')."'";
            $data['CloseLoopStatus']="'".$pl['CloseLoopMaster']['InCallStatus']."'";
			$data['CaseCloseBy']="'".$userby."'";
			
			if($this->Session->read('companyid') =="277"){
				
				$SrArr	=	$this->UploadExistingBase->query("SELECT SrNo FROM call_master WHERE Id='$id' LIMIT 1 ;");
				$SrNo	=	$SrArr[0]['call_master']['SrNo'];
				
				$this->UploadExistingBase->query("UPDATE call_master SET 
				close_loop='".$pl['CloseLoopMaster']['close_loop']."',
				CloseLoopCate1='".$pl['CloseLoopMaster']['close_loop_category']."',
				CloseLoopCate2='".$_REQUEST['close_cat2']."',
				CloseLoopingDate='".$date = date('Y-m-d H:i:s')."',
				FollowupDate='".$close_date."',
				closelooping_remarks='".$_REQUEST['closelooping_remarks']."',
				CloseLoopStatus='".$pl['CloseLoopMaster']['InCallStatus']."',
				UpdateDate='".$date = date('Y-m-d H:i:s')."',
				CaseCloseBy='".$userby."'
				WHERE  ClientId='277' AND Category1='Return Request' AND SrNo='$SrNo';");
			}
			else{
				$SrArr	=	$this->UploadExistingBase->query("SELECT SrNo,ClientId FROM call_master WHERE Id='$id' LIMIT 1 ;");
				$SrNo	=	$SrArr[0]['call_master']['SrNo'];
				$CliId	=	$SrArr[0]['call_master']['ClientId'];
				
				$this->UploadExistingBase->query("UPDATE call_master SET 
				close_loop='".$pl['CloseLoopMaster']['close_loop']."',
				CloseLoopCate1='".$pl['CloseLoopMaster']['close_loop_category']."',
				CloseLoopCate2='".$_REQUEST['close_cat2']."',
				CloseLoopingDate='".$date = date('Y-m-d H:i:s')."',
				FollowupDate='".$close_date."',
				closelooping_remarks='".$_REQUEST['closelooping_remarks']."',
				CloseLoopStatus='".$pl['CloseLoopMaster']['InCallStatus']."',
				UpdateDate='".$date = date('Y-m-d H:i:s')."',
				CaseCloseBy='".$userby."'
				WHERE  ClientId='$CliId' AND SrNo='$SrNo';");
			}
			
			
			
            if($this->UploadExistingBase->updateAll($data,array('Id'=>$id,'ClientId' => $this->Session->read('companyid')))){
                
                $orderDetails=$this->CloseLoopMaster->find('first',array('fields'=>array('orderby','orderno'),'conditions'=>array('id'=>$close_cat1,'client_id'=>$this->Session->read('companyid'))));
                
                $CloseDataArr=array(
                    'ClientId'=>$this->Session->read('companyid'),
                    'CallMasterId'=>$id,
                    'CloseLoopId'=>$close_cat1,
                    'CloseLoopCategory'=>$pl['CloseLoopMaster']['close_loop_category'],
                    'CloseLoopSubCategory'=>$_REQUEST['close_cat2'],
                    'Remarks'=>$_REQUEST['closelooping_remarks'],
                    'Status'=>$pl['CloseLoopMaster']['InCallStatus'],
                    'OrderStatus'=>$orderDetails['CloseLoopMaster']['orderby'],
                    'OrderNo'=>$orderDetails['CloseLoopMaster']['orderno'],
                    'FollowUpDate'=>$close_date,
                    'CreateDate'=>$date = date('Y-m-d H:i:s')
                );
                
                $this->CloseStatusHistory->save($CloseDataArr);
               
                // incall action alert start
                
                if(isset($pl['CloseLoopMaster']['close_loop_category']) && $pl['CloseLoopMaster']['close_loop_category'] !=""){$dataArr['call_action_type_name']=$pl['CloseLoopMaster']['close_loop_category'];}else{unset($dataArr['call_action_type_name']);}
                if(isset($_REQUEST['close_cat2']) && $_REQUEST['close_cat2'] !=""){$dataArr['call_action_sub_type_name']=$_REQUEST['close_cat2'];}else{unset($dataArr['call_action_sub_type_name']);}
                $dataArr['clientId']=$this->Session->read('companyid');
                $this->auto_callaction_alert($dataArr,$id);
                // incall action alert end
                
                echo "1";die;
            }
            else{
                echo "";die;
            }		
        }
    }
    
    
    public function auto_callaction_alert($dataArr,$id){
        $callMaster =   $this->CallMaster->find('first',array('conditions'=>array('ClientId'=>$this->Session->read('companyid'),'Id'=>$id)));
        $smsText    =   $this->InCallActionSms->find('first',array('conditions'=>$dataArr));
        $data       =   $this->InCallActionMatrix->find('all',array('conditions'=>$dataArr));
        
        if(!empty($smsText['InCallActionSms']['smsText']))
        {
            if(!empty($data)){
                foreach($data as $row){
                    $result=array(
                        'bpo'=>0,
                        'alertType'=>$row['InCallActionMatrix']['alertType'],
                        'data_id'=>$id,
                        'clientId'=>$row['InCallActionMatrix']['clientId'],
                        'alertOn'=>$row['InCallActionMatrix']['alertOn'],
                        'msg'=>$smsText['InCallActionSms']['smsText'],
                        'personName'=>$row['InCallActionMatrix']['personName'],
                        'email'=>$row['InCallActionMatrix']['email'],
                        'mobileNo'=>$row['InCallActionMatrix']['mobileNo'],
                        'createdate'=>$date = date('Y-m-d H:i:s')
                    );
    
                  $this->InCallActionCroneJob->saveAll($result);       
                }
            }
           
    
            $result1=array(
                'bpo'=>0,
                'alertType'=>'Alert',
                'data_id'=>$id,
                'clientId'=>$callMaster['CallMaster']['ClientId'],
                'alertOn'=>'email',
                'msg'=>$smsText['InCallActionSms']['smsText'],
                'personName'=>'',
                'email'=>$callMaster['CallMaster']['Field4'],
                'mobileNo'=>$callMaster['CallMaster']['MSISDN'],
                'createdate'=>$date = date('Y-m-d H:i:s')
            );
             
            $this->InCallActionCroneJob->saveAll($result1);

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
    
    /*
    public function bluedartapi(){
            $msg="";
            $lastid=$_REQUEST['id'];
            
            $dataArr=$this->CallMaster->find('first',array('conditions'=>array('Id'=>$lastid)));
            $data=$dataArr['CallMaster'];

            $ClientId               =   $data['ClientId'];
            $Category1              =   $data['Category1'];
            $AreaCode               =   $data['AreaCode'];
            $AreaPincode            =   $data['AreaPincode'];
            $AreaPlace              =   $data['AreaPlace'];
            $AreaAddress            =   $data['AreaAddress'];
            $AreaServiceCenterCode  =   $data['AreaServiceCenterCode'];

            if($ClientId =="277" && $Category1 =="Return Request" && $AreaPincode !=""){
                
    
                
                require_once(APP . 'webroot' . DS . 'PHPservice' . DS . 'CallAwbService.php');

                //$CreditReferenceNo  =   "A2";
                $CreditReferenceNo  =   $data['SrNo'];
                //$PickupDate        =   date('Y-m-d',strtotime($data['CallDate']));
                $PickupDate        =   date('Y-m-d');
                $PickupTime        =   date('Hi',strtotime(date('Y-m-d H:i:s')));



                $ItemName           =   trim($data['Field9']);

                $ProDetArr=$this->CallMaster->query("SELECT * FROM `arb_product_master` WHERE ProductName='$ItemName' limit 1 ");
                
                $Breadth=$ProDetArr[0]['arb_product_master']['Breadth'];
                $Height=$ProDetArr[0]['arb_product_master']['Height'];
                $Length=$ProDetArr[0]['arb_product_master']['Length'];
                $Weight=$ProDetArr[0]['arb_product_master']['Weight'];
                $Count=$ProDetArr[0]['arb_product_master']['Count'];
                $Price=$ProDetArr[0]['arb_product_master']['Price'];
                $Taxiable=$Price/18;

                $str                =   $data['Field22'];

                $str1=substr($str, 0, 30);
                $str2=substr($str, 30, 60);
                $str3=substr($str, 60, 90);

                $str1 !=""?$CustomerAddress1=$str1:"";
                $str2 !=""?$CustomerAddress2=$str2:"";
                $str3 !=""?$CustomerAddress3=$str3:"";

                $CustomerEmailID    =   $data['Field8'];
                $CustomerMobile     =   $data['Field2'];
                $CustomerName       =   substr($data['Field1'], 0, 30);
                $CustomerPincode    =   $AreaPincode;
                $CustomerTelephone  =   $data['Field4'];
                $OriginArea         =   $AreaCode;

                $soap = new DebugSoapClient('http://netconnect.bluedart.com/Ver1.8/ShippingAPI/WayBill/WayBillGeneration.svc?wsdl',
                array(
                'trace' 							=> 1,  
                'style'								=> SOAP_DOCUMENT,
                'use'									=> SOAP_LITERAL,
                'soap_version' 				=> SOAP_1_2
                ));

                $soap->__setLocation("http://netconnect.bluedart.com/Ver1.8/ShippingAPI/WayBill/WayBillGeneration.svc");

                $soap->sendRequest = true;
                $soap->printRequest = false;
                $soap->formatXML = true; 

                $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IWayBillGeneration/GenerateWayBill',true);
                $soap->__setSoapHeaders($actionHeader);	
                //"OM COMPUTRONIX D 4/1 1ST FLOOR OKHLA INDUSTRIAL AREA OKHLA PHASE II NEW DELHI 110020 CONTACT 7065503311";

                $params = array(
                'Request' => 
                        array (
                                'Consignee' =>
                                        array (

                                                'ConsigneeAddress1' => 'B122, B Block',
                                                'ConsigneeAddress2' => 'Sector 63, Noida',
                                                'ConsigneeAddress3'=> 'Uttar Pradesh',
                                                'ConsigneeAttention'=> 'Lapguard Service Center',
                                                'ConsigneeMobile'=> '7065503300',
                                                'ConsigneeName'=> 'Lapguard Service Center',
                                                'ConsigneePincode'=> '201301',
                                                'ConsigneeTelephone'=> '7065503300'
                                        )	,
                                'Services' => 
                                        array (
                                                'ActualWeight' => $Weight,
                                                'CollectableAmount' => '',
                                                'Commodity' =>
                                                        array (
                                                                'CommodityDetail1'  => $ItemName,
                                                                'CommodityDetail2' => '',
                                                                'CommodityDetail3' => ''
                                                ),
                                                'CreditReferenceNo' => $CreditReferenceNo,
                                                'DeclaredValue' => $Price,
                                                'Dimensions' =>
                                                        array (
                                                                'Dimension' =>
                                                                        array (
                                                                                'Breadth' => $Breadth,
                                                                                'Count' => $Count,
                                                                                'Height' => $Height,
                                                                                'Length' => $Length)),
                                                        'InvoiceNo' => $CreditReferenceNo,
                                                        'IsForcePickup' => true,
                                                        'IsPartialPickup' => false,
                                                        'IsReversePickup' => false,
                                                        'ItemCount' => $Count, 
                                                        'PackType' => 'L',
                                                        'PickupDate' => $PickupDate,
                                                        'PickupTime' => $PickupTime,
                                                        'PieceCount' => $Count,
                                                        'ProductCode' => 'A',
                                                        'RegisterPickup' => true,

                                                        //'ProductType' => '',
                                                        //'SpecialInstruction' => '1',
                                                        'SubProductCode' => 'P',					
                                        'itemdtl' =>
                                                array (
                                                        'ItemDetails' =>
                                                                array (
                                                         'CGSTAmount'=>'',
                                                         'HSCode'=>'',
                                     'IGSTAmount'=>'',
                                     'Instruction'=>'Nothing' ,
                                     'InvoiceDate'=>$PickupDate ,
                                     'InvoiceNumber'=>$CreditReferenceNo, 
                                     'ItemID'=>$CreditReferenceNo,
                                     'ItemName'=>$ItemName,
                                     'ItemValue'=>$Price,
                                     'Itemquantity'=>$Count,
                                     'PlaceofSupply'=>'',
                                     'ProductDesc1'=>'',
                                     'SGSTAmount'=>'',
                                     'SKUNumber'=>'',
                                     'SellerGSTNNumber'=>'',
                                     'SellerName'=>'OM COMPUTRONIX',
                                     'TaxableAmount'=>$Taxiable,
                                     'TotalValue'=>$Price
                                                         ),
                                                         ),
                                         ),
                                 'Shipper' =>
                                                array(

                                                        'CustomerAddress1' => $CustomerAddress1,
                                                        'CustomerAddress2' => $CustomerAddress2,
                                                        'CustomerAddress3' => $CustomerAddress3,
                                                        'CustomerCode' => '676071',
                                                        'CustomerEmailID' => $CustomerEmailID,
                                                        'CustomerMobile' => $CustomerMobile,
                                                        'CustomerName' => $CustomerName,
                                                        'CustomerPincode' => $CustomerPincode,
                                                        'CustomerTelephone' => $CustomerTelephone,
                                                        'IsToPayCustomer' => true,
                                                        'OriginArea' => 'NDA',
                                                        'Sender' => $CustomerName,
                                                        'VendorCode' => ''
                                                ),
                        ),
                        'Profile' => 
                                  array(
                                        'Api_type' => 'S',
                                        'LicenceKey'=>'j35ustq6qpjrhlqtunpftfuiqssklfl3',
                                        'LoginID'=>'NDA61542',
                                        'Version'=>'1.3')
                                        );

                //echo "<pre>";
                //print_r($params);die;

                $result = $soap->__soapCall('GenerateWayBill',array($params));
               // echo "<pre>";
                //print_r($result);die;
                
                if($result->GenerateWayBillResult->AWBNo !=""){
                    
                    $updArr=array(
                        'AWBNo'=>"'".$result->GenerateWayBillResult->AWBNo."'",
                        'CCRCRDREF'=>"'".$result->GenerateWayBillResult->CCRCRDREF."'",
                        'DestinationArea'=>"'".$result->GenerateWayBillResult->DestinationArea."'",
                        'DestinationLocation'=>"'".$result->GenerateWayBillResult->DestinationLocation."'",
                        'TokenNumber'=>"'".$result->GenerateWayBillResult->TokenNumber."'"
                    );

                    $this->CallMaster->updateAll($updArr,array('Id'=>$lastid));
                    $msg= "Waybill Generation Sucessful";
                    
                } 
                else {
                    $msg= $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;
                }

            }
            
            echo "<pre>";
            print_r($result);die;
        }*/
        
        
        
        public function bluedartapi(){
            $msg="";
            $lastid=$_REQUEST['id'];
            
            $dataArr=$this->CallMaster->find('first',array('conditions'=>array('Id'=>$lastid)));
            $data=$dataArr['CallMaster'];

            $ClientId               =   $data['ClientId'];
            $Category1              =   $data['Category1'];
            $AreaCode               =   $data['AreaCode'];
            $AreaPincode            =   $data['AreaPincode'];
            $AreaPlace              =   $data['AreaPlace'];
            $AreaAddress            =   $data['AreaAddress'];
            $AreaServiceCenterCode  =   $data['AreaServiceCenterCode'];

            if($ClientId =="277" && $Category1 =="Return Request" && $AreaPincode !=""){

                $CreditReferenceNo  =   "REV".$data['SrNo'];
                $PickupDate         =   date('Y-m-d');
                $PickupTime         =   date('Hi',strtotime(date('Y-m-d H:i:s')));
                $ItemName           =   trim($data['Field9']);

                $ProDetArr=$this->CallMaster->query("SELECT * FROM `arb_product_master` WHERE ProductName='$ItemName' limit 1 ");
                
                $Breadth=$ProDetArr[0]['arb_product_master']['Breadth'];
                $Height=$ProDetArr[0]['arb_product_master']['Height'];
                $Length=$ProDetArr[0]['arb_product_master']['Length'];
                $Weight=$ProDetArr[0]['arb_product_master']['Weight'];
                $Count=$ProDetArr[0]['arb_product_master']['Count'];
                $Price=$ProDetArr[0]['arb_product_master']['Price'];
                $Taxiable=$Price/18;

                $customerAddress    =   $data['Field22'];
                $CustomerEmailID    =   $data['Field8'];
                $CustomerMobile     =   $data['Field2'];
                $CustomerName       =   $data['Field1'];
                $CustomerPincode    =   $AreaPincode;
                $CustomerTelephone  =   $data['Field4'];
                $customerCity       =   $data['Field5'];
                $OriginArea         =   $AreaCode;

                $data = array( 'orders'=> [
                    array(
                        "orderId"=> $CreditReferenceNo,
                        "customerName"=> $CustomerName,
                        "customerAddress"=> $customerAddress,
                        "customerCity"=> $customerCity,
                        "customerPinCode"=> $CustomerPincode,
                        "customerContact"=> $CustomerMobile,
                        "orderDate"=> $PickupDate,
                        "modeType"=> "Lite-0.5kg",
                        "orderType"=> "reverse",
                        "totalValue"=> $Price,
                        "categoryName"=> "Computers and Accessories",
                        "packageName"=> $ItemName,
                        "quantity"=> $Count,
                        "packageLength"=> $Length,
                        "packageWidth"=> $Breadth,
                        "packageHeight"=> $Height,
                        "packageWeight"=> $Weight,
                        "sellerAddressId"=> "11294" 
                    )
                ]);
                
                $Order_Data     =   $this->set_order($data);
                $obj            =   json_decode($Order_Data);
                $order_res      =   $obj[0]->success;

                $Shipment_Data  =   $this->getShipmentSlip($CreditReferenceNo);
                $Ship_Res       =   json_decode($Shipment_Data);

                $AWBNo          =   $Ship_Res->awbNo;
                $carrierName    =   $Ship_Res->carrierName;
                $fileName       =   $Ship_Res->fileName;
                $manifestID     =   $Ship_Res->manifestID;
                $status         =   $Ship_Res->status;
                
                //if($status =="success"){
                $updArr=array(
                    'AWBNo'=>"'".$AWBNo."'",
                    'CCRCRDREF'=>"'".$carrierName."'",
                    'DestinationArea'=>"'".$fileName."'",
                    'TokenNumber'=>"'".$AWBNo."'"
                );

                $this->CallMaster->updateAll($updArr,array('Id'=>$lastid));
                $msg= "AWB No Generation Sucessful";
                //}
            }
            echo "<pre>";
            print_r($Ship_Res);die;
        }
        
        function authenticatShyplite() {
        $email      =   "arpit@arbaccessories.in";
        $password   =   "in4mation";

        $timestamp  =   time();
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $secret     =   'fxkCl2FPvVIcE/t21fpk0KjZn3iNpYQTPHAEQRBq9dC+SAa9Gd/8MgKbEFaPcHZAFqGBQpl4QKjSsmZpL+Ojug==';

        $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch = curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "emailID=$email&password=$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
        exit;
        curl_close($ch);
    }
        
    public function set_order($data){
        $Login      =   $this->authenticatShyplite();
        $obj        =   json_decode($Login);
        $secret     =   $obj->userToken;
        $timestamp  =   time();
        $sellerid   =   15196;
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $sign       =   "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken  =   rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch         =   curl_init();

        $data_json = json_encode($data);
      
        $header = array(
            "x-appid: $appID",
            "x-sellerid: $sellerid",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken",
            "Content-Type: application/json",
            "Content-Length: ".strlen($data_json)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/order');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        return $response;
        exit;
        curl_close($ch);
    }
    
    function getShipmentSlip($OrderID) {
        $Login      =   $this->authenticatShyplite();
        $obj        =   json_decode($Login);
        $secret     =   $obj->userToken;
        
        $timestamp  =   time();
        $sellerid   =   15196;
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $sign       =   "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken  =   rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch         =   curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "x-sellerid: $sellerid",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/getSlip?orderID='.urlencode($OrderID));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
        exit;
        curl_close($ch);
    }
        
        
        
	
	
    public function payment_gateway()
    {
        
        if(isset($_REQUEST['id'])){
            $ClientId = $this->Session->read('companyid');
            $this->set('ClientId',$ClientId);
            $this->set('id',$_REQUEST['id']);
            
            $clientId = $this->Session->read('companyid');

            //--------------------------------------------------------------------------
            $id=$_REQUEST['id'];
            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            
            $fieldSearch = $this->FieldMaster->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        foreach($fieldSearch as $f)
        {
            $headervalue[] = 'Field'.$f; 
        } 
        $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,
                array('LeadId','CallType', 'CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated','CloseLoopStatus','CFieldUpdate','AWBNo','Ret_AWBNo'));
        //$fieldSearch = implode(",",$fieldSearch);
            
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
            $this->set('ecr',$ecr);

            $data = $this->CallMaster->find('first',array('fields'=>$fieldSearch,'conditions'=>array('ClientId'=>$clientId,'Id'=>$id),'order'=>array('CallDate'=>'desc')));
            $this->set('history',$data);
            $this->set('headervalue',$headervalue);
       
            $newArr=$this->CloseLoopMaster->query("SELECT clm.close_loop_category,clm.id,clm.orderby,clm.orderno FROM closeloop_master clm
            INNER JOIN call_master cm ON 
            CONCAT(IF(clm.CategoryName1='All',1,CONCAT(IF(clm.CategoryName1 !='',cm.Category1,''),IF(clm.CategoryName2='All',1,CONCAT(IF(clm.CategoryName2 !='',cm.Category2,''),IF(clm.CategoryName3='All',1, CONCAT(IF(clm.CategoryName3 !='',cm.Category3,''),IF(clm.CategoryName4='All',1, CONCAT(IF(clm.CategoryName4 !='',cm.Category4,''),IF(clm.CategoryName5='All',1,IF(clm.CategoryName5 !='',cm.Category5,''))))))))))) = 
            CONCAT(IF(clm.CategoryName1='All',1,CONCAT(clm.CategoryName1,IF(clm.CategoryName2='All',1,CONCAT(clm.CategoryName2,IF(clm.CategoryName3='All',1, CONCAT(clm.CategoryName3,IF(clm.CategoryName4='All',1, CONCAT(clm.CategoryName4,IF(clm.CategoryName5='All',1,clm.CategoryName5) ))))))))) 
            WHERE clm.client_id='$clientId' AND clm.close_loop='manual' AND clm.label='1' AND cm.Id='$id' order by clm.orderno");
            
            /*
            $newArr=$this->CloseLoopMaster->query("SELECT clm.close_loop_category,clm.id,clm.orderby,clm.orderno FROM closeloop_master clm
            INNER JOIN call_master cm
            ON CONCAT(IF(clm.CategoryName1='All',1,CONCAT(cm.Category1,IF(clm.CategoryName2='All',1,CONCAT(cm.Category2,IF(clm.CategoryName3='All',1,
            CONCAT(cm.Category3,IF(clm.CategoryName4='All',1,
            CONCAT(cm.Category4,IF(clm.CategoryName5='All',1,cm.Category5)
            ))))))))) = CONCAT(IF(clm.CategoryName1='All',1,CONCAT(clm.CategoryName1,IF(clm.CategoryName2='All',1,CONCAT(clm.CategoryName2,IF(clm.CategoryName3='All',1,
            CONCAT(clm.CategoryName3,IF(clm.CategoryName4='All',1,
            CONCAT(clm.CategoryName4,IF(clm.CategoryName5='All',1,clm.CategoryName5)
            )))))))))
            WHERE clm.client_id='$clientId' AND clm.close_loop='manual' AND clm.label='1' AND cm.Id='$id' order by clm.orderno");
            */
            
            $this->set('mpcat',$newArr);
            
            
            /*
            $orderArr=array();
            foreach($newArr as $value){
                $orderArr[][$value['clm']['orderby']]=$value['clm']['orderno'];
            }
             
            */
            
            //print_r($orderArr);die;
            
            
            
            
            $CloseUpdateList = $this->CloseStatusHistory->find('list',array('fields'=>array('CloseLoopId'),'conditions'=>array('ClientId'=>$clientId,'CallMasterId'=>$id)));
            $this->set('CloseUpdateList',$CloseUpdateList);
            
            $CsUpdate = $this->CloseStatusHistory->find('all',array('conditions'=>array('ClientId'=>$clientId,'CallMasterId'=>$id)));
            $this->set('CsUpdate',$CsUpdate);
          
           
            
           //--------------------------------------------------------------------------
           
            $fieldValue1 = $this->CloseFieldDataValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $fieldName1 = $this->CloseFieldData->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $CArr = $this->CloseUpdate->find('first',array('conditions'=>array('Id'=>$_REQUEST['id'],'ClientId' =>$clientId)));
            
            $this->set('fieldName1',$fieldName1);
            $this->set('fieldValue1',$fieldValue1);
            $this->set('callId',$_REQUEST['id']);
            $this->set('CArr',$CArr['CloseUpdate']);
            
            
            $AwbRet=$data['CallMaster']["AWBNo"];
            $AwbFor=$data['CallMaster']["Ret_AWBNo"];
            
            //$AwbRet="75409313151";
            //$AwbFor="75409313151";
            
            $ReturnShippingStatus=$this->bluedartstatusreturn($AwbRet);
            $ForwordShippingStatus=$this->bluedartstatusforword($AwbFor);
            $this->set('ReturnShippingStatus',$ReturnShippingStatus);
            $this->set('ForwordShippingStatus',$ForwordShippingStatus);
            $PaymentDetails = $this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id'],'not'=>array('PaymentStatus'=>'Not Paid')),'order'=>array('OrderId'=>'desc')));
            if(empty($PaymentDetails))
            {
                $PaymentDetails = $this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id']),'order'=>array('OrderId'=>'desc')));
                //$this->set('PaymentDetails',$PaymentDetails);
            }
            $this->set('PaymentDetails',$PaymentDetails);
            
            //print_r($this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id'])))); exit;
            
        }
        
         
    }


    
    
    public function update_payment_det()
    {
        $msg = "";
        if($this->request->is('POST'))
        {
            $PaymentOrderNo['CaseId'] = $Id = $this->request->data['id'];
        $PaymentOrderNo['ClientId'] = $ClientId = $this->request->data['ClientId'];
        //$PaymentOrderNo['Category1'] =$Category1 = $this->request->data['Category1'];
        $PaymentOrderNo['CustomerName'] =$CustomerName = $this->request->data['CustomerName'];
        $PaymentOrderNo['SendType'] =$SendType = $this->request->data['SendType'];
        $PaymentOrderNo['EEmailId'] =$EEmailId = $this->request->data['EEmailId'];
        $PaymentOrderNo['MMobile'] =$MMobile = $this->request->data['MMobile'];
        $PaymentOrderNo['OrderAmount'] = $OrderAmount = $this->request->data['OrderAmount'];
        $PaymentOrderNo['Product'] = $Product = $this->request->data['Product'];
        
        $ClientDetails = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
       
        $ClientName = $ClientDetails['RegistrationMaster']['company_name']; 
        $NewClientName = str_replace(" ","_",$ClientName);
        
        
        
        if($this->PaymentOrderNo->save($PaymentOrderNo))
        {
            $OrderId = $this->PaymentOrderNo->getLastInsertID();
            $OrderNo = $NewClientName."_$OrderId";
            
            if($this->PaymentOrderNo->updateAll(array('OrderNo'=>"'$OrderNo'"),array('OrderId'=>$OrderId)))
            {
                
                //$dataJson=array("encypted_request"=>)
                $url = "http://www.paypik.in/app/webroot/api/apis.php?req_id=39";
                
                App::uses('HttpSocket', 'Network/Http');
                $HttpSocket = new HttpSocket();
                
                $response = $HttpSocket->post($url,['apiType'=>$SendType,'CustomerName'=>$CustomerName,'EmailId'=>$EEmailId,'MobileNo'=>$MMobile,'InvoiceAmount'=>$OrderAmount,'OrderNo'=>$OrderNo,'Product'=>$Product]);
                
                if($response=='Order Id already exists')
                {
                    $this->Session->setFlash("Order Id already Exists");
                    $msg = "Order Id already Exists";
                }
                else if($response=='Error while request !')
                {
                    $this->Session->setFlash("Error while request !");
                    $msg = "Error while request !";
                }
                else if($response=='Invalid Email !')
                {
                    $this->Session->setFlash("Invalid Email !");
                    $msg = "Invalid Email !";
                }
                else if($response=='Invalid Mobile No !')
                {
                    $this->Session->setFlash("Invalid Mobile No !");
                    $msg = "Invalid Mobile No !";
                }
                else
                {
                    $updData = array();
                    $responseObj = json_decode($response);
                    $updData['ResponseId'] = $id = "'".addslashes($responseObj->id)."'";
                    $updData['Responseamount'] = $amount = "'".addslashes($responseObj->amount)."'";
                    $updData['country'] = $country = "'".addslashes($responseObj->country)."'";
                    $updData['currency'] = $currency = "'".addslashes($responseObj->currency)."'";
                    $updData['customereMail'] = $customereMail = "'".addslashes($responseObj->customereMail)."'";
                    $updData['meId'] = $meId = "'".addslashes($responseObj->meId)."'";
                    $updData['mediaType'] = $mediaType = "'".addslashes($responseObj->mediaType)."'";
                    $updData['mobileNo'] = $mobileNo = "'".addslashes($responseObj->mobileNo)."'";
                    $updData['ResponseorderId'] = $orderId = "'".addslashes($responseObj->orderId)."'";
                    $updData['token'] = $token = "'".addslashes($responseObj->token)."'";
                    $updData['product'] = $product = "'".addslashes($responseObj->product)."'";
                    $updData['firstName'] = $firstName = "'".addslashes($responseObj->firstName)."'";
                    $updData['lastName'] = $lastName = "'".addslashes($responseObj->lastName)."'";
                    $updData['creationDate'] = $creationDate = "'".addslashes($responseObj->creationDate)."'";
                    $updData['createdBy'] = $createdBy = "'".addslashes($responseObj->createdBy)."'";
                    $updData['expiryDate'] = $expiryDate = "'".addslashes($responseObj->expiryDate)."'";

                    if($this->PaymentOrderNo->updateAll($updData,array('OrderId'=>$OrderId)))
                    {
                        $this->Session->setFlash("Payment Request Send Succesfuly.");
                        $this->set('resend','resend');
                        $msg = "Request Send Succesfuly.";
                    }
                    else
                    {
                        $this->Session->setFlash("Payment Request Not Send! Please Try Again.");
                    }
                }
                
            }
            
            
            
            
        }
        }
        
        


    $act=$this->webroot.'SrDetails';
    $script=$this->webroot.'js/jquery-1.12.4.min.js';
    $url1=$this->webroot.'SrDetails/update_payment_det'; 
    $url2=$this->webroot.'SrDetails'; 
    
    echo "
        <link rel='stylesheet' href='$scrcs1'>                                      
        <script src='$script'></script>
        <script type=\"text/javascript\">
         $( document ).ready(function() {
         myFunction();

         $('#close-loop-popup').trigger('click');
            $('#taggingmsg').trigger('click');
            $('#tagging-text-message').text('$msg');
         });
         
        function myFunction() { 
            if (confirm('$msg Are you sure you want to continue.')) {
                window.location.replace('$url1');
            } else {
                window.location.replace('$url2');
            }
        }
        </script>
        ";
        die;
        
    }

     public function index1() {
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
        $headervalue1=array();
        foreach($fieldSearch1 as $f1){
            $headervalue1[] = 'CField'.$f1; 
        }
        
        //print_r($headervalue1); exit;
        
        
        $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,$headervalue1,
                array('LeadId','CallType','CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','CaseCloseBy','tat','duedate','callcreated','AbandStatus','AWBNo','TokenNumber','Ret_AWBNo','Ret_TokenNumber','Ret_PikupDate','AreaPincode'));
        
        




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
            $data['CallType !=']='Upload';

            //print_r($search);exit;
            
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

            $tArr = $this->CallMaster->find('all',array('fields'=>array_merge(array('ClientId'),$fieldSearch),'conditions' =>$data));
           
            
            
            
            

            //$this->set('header',$header_field);
            
            
            //print_r($tArr);die;
            
	   $this->set('ClientId',$ClientId);
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
                
                $tArr = $this->CallMaster->find('all',array('fields'=>array_merge(array('ClientId'),$fieldSearch),'conditions' =>$data));
            
                $this->set('history',$tArr);
                $this->set('headervalue',$headervalue);
                $this->set('headervalue1',$headervalue1);
                $this->set('showVal',$search);
            }
            
            //$history = $this->CallMaster->find('all',array('conditions'=>array('ClientId'=>$clientId),'order'=>array('CallDate'=>'desc')));	
            //$this->set('history',$history);		
    }

    public function selectCategory(){ 
        //echo  $_REQUEST['parent_id'] ;die;
        $ClientId = $this->Session->read('companyid');
        $id = '';
        if(is_numeric($_REQUEST['parent_id'])){
            $subcategory = $this->ClientCategory->find('first',array('conditions'=>array('parent_id'=>$_REQUEST['parent_id'],'Client'=>$ClientId)));
            //print_r($subcategory);die;
            $id = $subcategory['ClientCategory']['parent_id'];
        }else{
            $subcategory = $this->ClientCategory->find('first',array('conditions'=>array('ecrName'=>$_REQUEST['parent_id'],'Client'=>$ClientId)));
            $id = $subcategory['ClientCategory']['id'];
        }

        if($_REQUEST['parent_id'] == "All"){
            $subcategory = $this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>$_REQUEST['divid'],'Client'=>$ClientId))); 
            $subcategory = array_unique($subcategory);          
            if(!empty($subcategory)){
                $_REQUEST['divid'] ==="2"?$html="<option value=''>Select Sub Scenario 1</option>":"";
                $_REQUEST['divid'] ==="3"?$html="<option value=''>Select Sub Scenario 2</option>":"";
                $_REQUEST['divid'] ==="4"?$html="<option value=''>Select Sub Scenario 3</option>":"";
                $_REQUEST['divid'] ==="5"?$html="<option value=''>Select Sub Scenario 4</option>":"";
                echo $html;
                echo "<option value='All'>All</option>";
                foreach($subcategory as $key=>$value){              
                    echo "<option value='$value'>".$value."</option>";
                }
            }
        }
        
        if(!empty($id))
        {

        if($_REQUEST['parent_id'] !=" "){
            $subcategory =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$id,'Client'=>$ClientId)));           
            if(!empty($subcategory)){
                $_REQUEST['divid'] ==="2"?$html="<option value=''>Select Sub Scenario 1</option>":"";
                $_REQUEST['divid'] ==="3"?$html="<option value=''>Select Sub Scenario 2</option>":"";
                $_REQUEST['divid'] ==="4"?$html="<option value=''>Select Sub Scenario 3</option>":"";
                $_REQUEST['divid'] ==="5"?$html="<option value=''>Select Sub Scenario 4</option>":"";
                echo $html;
                echo "<option value='All'>All</option>";
                foreach($subcategory as $key=>$value){              
                    echo "<option value='$value'>".$value."</option>";
                }
            }
        }
    }
        die;
    }


    public function selectCategory1(){ 
        //echo  $_REQUEST['parent_id'];die;
        //echo $_REQUEST['divid'];die;
        $ClientId = $this->Session->read('companyid');
        $id = '';
        if(is_numeric($_REQUEST['parent_id'])){
            $subcategory = $this->ClientCategory->find('first',array('conditions'=>array('parent_id'=>$_REQUEST['parent_id'],'Client'=>$ClientId)));
            //print_r($subcategory);die;
            $id = $subcategory['ClientCategory']['parent_id'];
        }else{
            $subcategory = $this->ClientCategory->find('first',array('conditions'=>array('ecrName'=>$_REQUEST['parent_id'],'Client'=>$ClientId)));
            $id = $subcategory['ClientCategory']['id'];
        }
        if($_REQUEST['parent_id'] == "All"){
            $subcategory = $this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>$_REQUEST['divid'],'Client'=>$ClientId))); 
            $subcategory = array_unique($subcategory);          
            if(!empty($subcategory)){
                $_REQUEST['divid'] ==="2"?$html="<option value=''>Select Sub Scenario 1</option>":"";
                $_REQUEST['divid'] ==="3"?$html="<option value=''>Select Sub Scenario 2</option>":"";
                $_REQUEST['divid'] ==="4"?$html="<option value=''>Select Sub Scenario 3</option>":"";
                $_REQUEST['divid'] ==="5"?$html="<option value=''>Select Sub Scenario 4</option>":"";
                echo $html;
                echo "<option value='All'>All</option>";
                foreach($subcategory as $key=>$value){              
                    echo "<option value='$value'>".$value."</option>";
                }
            }
        }

        if(!empty($id))
        {

            if($_REQUEST['parent_id'] !=" "){
                $subcategory =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$id,'Client'=>$ClientId)));           
                if(!empty($subcategory)){
                    $_REQUEST['divid'] === "2"?$html="<option value=''>Select Sub Scenario 1</option>":"";
                    $_REQUEST['divid'] === "3"?$html="<option value=''>Select Sub Scenario 2</option>":"";
                    $_REQUEST['divid'] === "4"?$html="<option value=''>Select Sub Scenario 3</option>":"";
                    $_REQUEST['divid'] === "5"?$html="<option value=''>Select Sub Scenario 4</option>":"";
                    echo $html;
                    echo "<option value='All'>All</option>";
                    foreach($subcategory as $key=>$value){              
                        echo "<option value='$value'>".$value."</option>";
                    }
                }
            }
        }
        die;
    }
	
}
?>