<?php
class AdminEscalationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('Escalation','EscalationValue','ClientCategory','LogincreationMaster','FieldCreation','Crone','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
       $this->Auth->allow('index','add','view','view_value','delete','getParent');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
    	
    public function index(){
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            
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
            if(isset($this->request->query['id']))
            {
                $id = base64_decode($this->request->query['id']);			
                $this->set('escalation',$this->Escalation->find('all',array('fields'=>array('email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
            }
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminEscalations'];
            $ClientId =$data['clientID'];
        
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
            if(isset($this->request->query['id']))
            {
                $id = base64_decode($this->request->query['id']);			
                $this->set('escalation',$this->Escalation->find('all',array('fields'=>array('email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
            }
            $this->set('clientid',$ClientId);  
        }

                   
    }

    public function getParent(){
        $this->layout="ajax";
        $data = $this->request->data;
        $label ="Parent".$data['Label'];	
        $data = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>$data));
        $this->set('data',$data);
        $this->set('label',$label);
    }

	
    public function add(){
        $this->layout='user';
        $text ='';
         
        if($this->request->is('Post')){
            $data = $this->request->data['AdminEscalations'];
            $cid=$data['cid'];
            $clname =$this->RegistrationMaster->find('first',array('fields'=>array("Company_name"),'conditions'=>array('company_id'=>$cid)));
            $cleintName = $clname['RegistrationMaster']['Company_name'];
            $ClientId = $cid;
            $arrEcr = array();
            $arrCapture = array();
           
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
        $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
    }
	
    public function view(){
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['cid']) && $_REQUEST['cid'] !=""){
            $ClientId =$_REQUEST['cid'];
            $Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
            $this->set('Category',$Category);
            if(isset($this->request->query['id'])){
                $id = base64_decode($this->request->query['id']);				
                $this->set('escalation',$this->Escalation->find('all',array('fields'=>array('id','email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
            }
            $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminEscalations'];
            $ClientId =$data['clientID'];
            
            $Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
            $this->set('Category',$Category);
            if(isset($this->request->query['id'])){
                $id = base64_decode($this->request->query['id']);				
                $this->set('escalation',$this->Escalation->find('all',array('fields'=>array('id','email','smsNo','type'),'conditions'=>array('ClientId'=>$ClientId,'ecrId'=>$id))));
            }
            $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('id','Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));        
            $this->set('clientid',$ClientId);  
        }
    
    
    
    
    
    
    
    
    
}


    public function view_value(){
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
    }	
    public function delete(){
        $this->layout='adminlayout';
        $ClientId = $this->request->query['cid'];
        $Category = $this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
        $id = base64_decode($this->request->query['id']);
        $this->Escalation->updateAll(array('status'=>"'0'"),array('id'=>$id));

        $this->redirect(array('action'=>'view','?'=>array('id'=>$this->request->query['id'],'cid'=>$ClientId)));
    }

}
?>