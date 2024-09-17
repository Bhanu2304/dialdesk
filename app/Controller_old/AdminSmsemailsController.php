<?php
class AdminSmsemailsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('Escalation','EscalationValue','ClientCategory','LogincreationMaster','FieldCreation','Crone','RegistrationMaster');
	
	
	 public function beforeFilter() {
        parent::beforeFilter();
			$this->Auth->allow('index','add','view','view_value','getParent');
			if(!$this->Session->check("admin_id")){
				return $this->redirect(array('controller'=>'Admins','action' => 'index'));
			}
			
    }

	public function index() {
		$this->layout='adminlayout';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
		
		if($this->request->is('Post')){
			$ClientId = $this->request->data['AdminSmsemails']['client'];
			$Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
			$Category['All'] = 'All';
			$this->set('Category',$Category);
			$this->set('clid',$ClientId);
						
			$data=$this->ClientCategory->find('all',array('fields'=>array('id','Label','Plan','id','parent_id'),'conditions'=>array('Client'=>$ClientId)));
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
			for($i =1; $i<$Ecr['ClientCategory']['Ecr']; $i++){
				$fields_send['Category'.$i] = 'Category'.$i;
			}
				
			$field_send2['ECR Field'] = $fields_send; $fields_send = array();
			$this->set('field_send2',$field_send2);
			if(isset($this->request->query['id'])){
				$id = base64_decode($this->request->query['id']);			
				$this->set('escalation',$this->Escalation->find('all',array('fields'=>array('email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
			}				
		}
		else{
			$Category=array();
			$data=array();
			$field_send2=array();
			$field_send1=array();
			$this->set('Category',$Category);	
			$this->set('data',$data);
			$this->set('field_send2',$field_send2);
			$this->set('field_send1',$field_send1);
		}
	}

	public function getParent(){
		$data = $this->request->data;	
		$label ="Parent".$data['Label'];
		$data = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>$data));
		$this->set('data',$data);
		$this->set('label',$label);
		$this->layout="ajax";
	}
	
	
	public function add() {
		$this->layout='adminlayout';
		$text ='';
		if($this->request->is('Post')){	
			$data = $this->request->data['AdminSmsemails'];
				
			$ClientId = $data['client'];
			$arrEcr = array();$arrCapture = array();
			
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
			else if($data['timer']=='1'){if(empty($data['dater1'])){ return;}else{$alertEscalation['timer'] = addslashes("Month");$alertEscalation['Month'] = addslashes($data['dater1']);}}
			else if($data['timer']=='2'){if(empty($data['dater2'])){ return;}else{$alertEscalation['timer'] = addslashes("Week");$alertEscalation['Week'] = addslashes($data['dater2']);}}
			else if($data['timer']=='3'){if(empty($data['dater3'])){ return;}else{$alertEscalation['timer'] = addslashes("Day");$alertEscalation['Day'] = addslashes($data['dater3']);}}
			else if($data['timer']=='4'){if(empty($data['dater4'])){ return;}else{$alertEscalation['timer'] = addslashes("Hour");$alertEscalation['Hours'] = addslashes($data['dater4']);}}
						
			if(empty($data['type'])){ return;}else{$alertEscalation['type'] = addslashes($data['type']);}
			if($data['type']=='sms'){if(empty($data['sms'])){ return;}else{$alertEscalation['smsNo'] = addslashes($data['sms']);}}
			else if($data['type']=='email'){if(empty($data['email'])){ return;}else{$alertEscalation['email'] = addslashes($data['email']);}}
			else if($data['type']=='both'){if(empty($data['email'])){ return;}else{$alertEscalation['email'] = addslashes($data['email']);} if(empty($data['sms'])){ return;}else{$alertEscalation['smsNo'] = addslashes($data['sms']);}}
			
			if(empty($data['format'])){ return;}else{$alertEscalation['format'] = addslashes($data['format']);}
			if(empty($data['field'])){ return;}
			else{
					$alertEscalation['fields'] = $text = $msg = addslashes($data['field']);
					
					while(strpos($msg,'[tag]') && strpos($msg,'[/tag]'))
					{
						$str = substr($msg,strpos($msg,'[tag]'),( strpos($msg,'[/tag]')-strpos($msg,'[tag]')+6));
						$msg = str_replace($str,'',$msg);
						$str = str_replace('[tag]','',$str);
						$str = str_replace('[/tag]','',$str);
						$str = str_replace('Category','',$str);
						//if($ids = $this->ClientCategory->find('first',array('fields'=>array('id'),'conditions'=>array('Client'=>$ClientId,'Plan'=>$str))))
						//{
							$arrEcr[] = $str;
						//}
					}
					while(strpos($msg,'[tag1]') && strpos($msg,'[/tag1]'))
					{
						$str = substr($msg,strpos($msg,'[tag1]'),( strpos($msg,'[/tag1]')-strpos($msg,'[tag1]')+8));
						$msg = str_replace($str,'',$msg);
						$str = str_replace('[tag1]','',$str);
						$str = str_replace('[/tag1]','',$str);
						if($ids = $this->FieldCreation->find('first',array('fields'=>array('Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldName'=>$str))))
						{
							$arrCapture[] = $ids['FieldCreation']['Priority'];
						}
					}
					$alertEscalation['ecrFields'] = implode(',',$arrEcr);
					$alertEscalation['captureFields'] = implode(',',$arrCapture);
				}
	
			$alertEscalation['createdate'] = date('Y-m-d H:i:s');
			if($this->Escalation->save($alertEscalation))
			{
				$escalationID = $this->Escalation->getLastInsertId();
//				if($notify == 'alert')
//				{
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
					
					else if($data['escalationType'] == 'Minute')
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
					//$select ='Select '.$select;
					$flag = false;
					for($i = 0; $i<count($arrCapture); $i++)
					{
						if($flag) $select .= ',';
						$select .= 'Field'.$arrEcr[$i];
						$flag = true;
					}
					$txt = "<?php include('connection.php'); \n\n";
					$txt  .= " $".''."sel = mysql_query(\"".$select." from call_master where ClientId = '".$ClientId."' limit 1\"); \n";
					$txt .= " $".''."Data = mysql_fetch_array($".''."sel); \n\n";
					$txt .= "$".''."text ='".$text."';\n";
					$txt .="$".''."i =0; \n
					while(strpos($".''."text,'[tag]') && strpos($".''."text,'[/tag]'))
					{
						$".''."str = substr($".''."text,strpos($".''."text,'[tag]'),( strpos($".''."text,'[/tag]')-strpos($".''."text,'[tag]')+6));
						$".''."text = str_replace($".''."str,$".''."Data[$".''."i],$".''."text);
						$".''."i = $".''."i+1;
					}
					while(strpos($".''."text,'[tag1]') && strpos($".''."text,'[/tag1]'))
					{
						$".''."str = substr($".''."text,strpos($".''."text,'[tag1]'),( strpos($".''."text,'[/tag1]')-strpos($".''."text,'[tag1]')+8));
						$".''."text = str_replace($".''."str,$".''."Data[$".''."i],$".''."text);
						$".''."i = $".''."i+1;
					}

					echo $".''."text;
					?>";
					
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
		$this->Session->setFlash("Add Successfully.");
		$this->redirect(array('action'=>'index'));
	}
	
	
	
	
	
	
	
	public function view(){
		$this->layout='adminlayout';		
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
		if($this->request->is('Post')){
			$ClientId = $this->request->data['AdminSmsemails']['client'];
			$this->set('clid',$ClientId);
			$Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
			$this->set('Category',$Category);
			$this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','Plan','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
		}
		if(isset($this->request->query['id'])){
			$id = base64_decode($this->request->query['id']);	
			$ClientId =base64_decode($this->request->query['cid']);
			$this->set('escalation',$this->Escalation->find('all',array('fields'=>array('email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
			$Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
			$this->set('Category',$Category);
			$this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','Plan','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
			$this->set('clid',$ClientId);	
		}
	}
	
}
?>