<?php
class EmailsController extends AppController
{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('EmailMaster','PlanMaster','Waiver','BalanceMaster','MailMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('view_client_mail','view_client_open_mail','send_mail','update_client_plan','get_client_name','allocate_plan','import_mail');
	
        /*
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
        */
        
    }

    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        //$this->set('emails',$this->MailMaster->query('SELECT clientId,SUM(IF(mail_status=1,1,0))`unread`,SUM(IF(mail_status=0,1,0))`read` FROM client_mails GROUP BY clientId'));
        $emailArr=$this->MailMaster->query("SELECT clientId,SUM(IF(mail_status=1,1,0))`unread`,SUM(IF(mail_status=0,1,0))`read`,email FROM client_mails,user_email_server_details where user_email_server_details.Id=client_mails.clientId and user_email_server_details.client_id='$ClientId' GROUP BY clientId");
        //print_r($emailArr);die;
        
        $this->set('emailArr',$emailArr);
       
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $Id = $_REQUEST['id'];
            //$this->set('emails',$this->MailMaster->find('all',array('conditions'=>array('clientId'=>$Id),'order'=>array('createdate'=>'desc')))); 
            
            $emails=$this->MailMaster->query("SELECT * FROM client_mails WHERE clientId='$Id' ORDER BY createdate DESC");
            //echo "<pre>";
            //print_r($emails);
            //echo "</pre>";
            $this->set('emails',$emails);
            
        }
        else{
            $this->set('emails',array());
        }
        
        //$this->import_mail();
        
    }
     
    public function import_mail(){
        
        $email = $this->EmailMaster->find('all',array('conditions'=>array("active"=>1)));
        
        $i = 0;
        foreach($email as $host) 
        {
            $hostname = $host['EmailMaster']['inbox_hostname'];
            $username = $host['EmailMaster']['email'];
            $password = $host['EmailMaster']['password'];
            $id = $host['EmailMaster']['Id'];
    
            $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail: ' . imap_last_error());
    
            $emails = imap_search($inbox,'ALL');
        
            //rsort($emails);
        
            foreach($email as $row) 
            {
                $hostname = $row['EmailMaster']['inbox_hostname'];
                $username = $row['EmailMaster']['email'];
                $password = $row['EmailMaster']['password'];
                $id = $row['EmailMaster']['Id'];
    
                $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail: ' . imap_last_error());
            //echo    $n_msgs = imap_num_msg($inbox).'<br/><br/>';
                $emails = imap_search($inbox,'NEW');
                //$emails = imap_search($inbox,'NEW');
                foreach($emails as $email_number) 
                {
                    
                    $overview = imap_fetch_overview($inbox,$email_number,0);
                    $header = imap_headerinfo($inbox, $email_number);
                    //$raw_body = imap_body($inbox, $email_number); 
                    //print_r($header); exit;
                    //echo '<br/><br/>';
                    //print_r($raw_body);
                    //exit;
        $structure = imap_fetchstructure($inbox, $email_number);
        $attachments = array();
        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
            $message = imap_fetchbody($inbox,$email_number,2);

            if($part->encoding == 3) {
                $message = imap_base64($message);
            } else if($part->encoding == 1) {
                $message = imap_8bit($message);
            } else {
                $message = imap_qprint($message);
            }
            
            for($i = 0; $i < count($structure->parts); $i++) 
            {

		$attachments[$i] = array(
			'is_attachment' => false,
			'filename' => '',
			'name' => '',
			'attachment' => ''
		);
		
		if($structure->parts[$i]->ifdparameters) {
			foreach($structure->parts[$i]->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$attachments[$i]['is_attachment'] = true;
					$attachments[$i]['filename'] = $object->value;
				}
			}
		}
		
		if($structure->parts[$i]->ifparameters) {
			foreach($structure->parts[$i]->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$attachments[$i]['is_attachment'] = true;
					$attachments[$i]['name'] = $object->value;
				}
			}
		}
		
		if($attachments[$i]['is_attachment']) {
			$attachments[$i]['attachment'] = imap_fetchbody($connection, $message_number, $i+1);
			if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
				$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
			}
			elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
				$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
			}
		}
            }
        }
        //print_r($attachments); exit;
        //print_r($overview[0]);       echo '<br/>';  
        $output= '<div class="toggle'.($overview[0]->seen ? 'read' : 'unread').'">';
        $output.= '<span class="from">From: '.utf8_decode(imap_utf8($overview[0]->from)).'</span>';
        $output.= '<span class="date">on '.utf8_decode(imap_utf8($overview[0]->date)).'</span>';
       
       $cc = '';
        foreach($header->cc as $c)
        {
          $cc[]=  $c->mailbox.'@'.$c->host;
        }
        $ccAddress = implode(',',$cc);
       
        if(!empty($cc))
        {
            $output.= '<br/><span class="cc">cc: '.$header->ccaddress.'</span>';
        }
        
        $bc = '';
        foreach($header->bc as $c)
        {
          $bc[]=  $c->mailbox.'@'.$c->host;
        }
        $bcAddress = implode(',',$bc);
        
        
        if(!empty($bc))
        {
            $output.= '<br/><span class="bc">bc: '.$header->bcaddress.'</span>';
        }
        
        $rt = '';
        foreach($header->reply_to as $c)
        {
          $rt[]=  $c->mailbox.'@'.$c->host;
        }
        $rtAddress = implode(',',$rt);
        
        if(!empty($rt))
        {
            $output.= '<br/><span class="rt">rt: '.$header->reply_toaddress.'</span>';
        }
       
        $output.= '<br /><span class="subject">Subject('.$part->encoding.'): '.utf8_decode(imap_utf8($overview[0]->subject)).'</span> ';
        $output.= '</div>';

         $output.= '<div class="body">'.$message.'</div><hr />';
        $uid= $overview[0]->uid; 
        if(!$this->MailMaster->find('first',array('conditions'=>array('uid'=>$uid,'clientId'=>$id)))) 
        {
            $Mail[$i]['mail_message'] = $output;
            $Mail[$i]['clientId'] = $id;
            $Mail[$i]['uid'] = $uid;
            $Mail[$i]['mail_cc'] = $ccAddress;
            $Mail[$i]['mail_bc'] = $bcAddress;
            $Mail[$i]['reply_to'] = $rtAddress;
            $Mail[$i]['mail_status']  = ($overview[0]->seen ? '0' : '1');
            $Mail[$i]['mail_subject'] =  $overview[0]->subject;

            if(strstr($overview[0]->from,'<'))
            {
                $to = trim(substr_replace($overview[0]->from,'',0,strpos($overview[0]->from,'<')));
                $Mail[$i]['mail_from'] = trim(str_replace('<','',str_replace('>','',$to)));
                
                $to = trim(substr_replace($overview[0]->from,'',strpos($overview[0]->from,'<'),strpos($overview[0]->from,'>')));
                $Mail[$i]['mailer_name'] = trim(str_replace('<','',str_replace('>','',$to)));

            }
            else
            {
                $Mail[$i]['mailer_name']    =  $overview[0]->from;
                $Mail[$i]['mail_from']    =  $overview[0]->from;
            } 
            $Mail[$i]['createdate'] =  date('y-m-d H:i:s');
            $Mail[$i]['mail_date'] =  $overview[0]->date;

            $i++;
        } 
    }              
}
            imap_close($inbox); 
        }
        
        //print_r($Mail);die;
        
        
        //var_dump($overview); exit;
        $this->MailMaster->saveAll($Mail);
        echo "1";die;
    }












    /*
    public function view_client_mail(){
        $this->layout="ajax";
        if($this->request->is('Post'))
        {
            $Id = $this->request->data['id'];
            $this->set('emails',$this->MailMaster->find('all',array('conditions'=>array('clientId'=>$Id),'order'=>array('id'=>'desc'))));                
        }
    }*/
	
    public function view_client_open_mail(){
        if($this->request->is('Post')){
            $cid = $this->Session->read('companyid');
            $Id = $this->request->data['id'];
            $email_no = $this->request->data['email_no'];
            $Mail = $this->MailMaster->find('first',array('conditions'=>array('Id'=>$email_no)));

            $clientId = $Mail['MailMaster']['clientId'];
            $uid[] = $Mail['MailMaster']['clientId'];
            if($con = $this->EmailMaster->find('first',array('conditions'=>array('Id'=>$clientId,'client_id'=>$cid,"active"=>1))))
            {
                $hostname = $con['EmailMaster']['inbox_hostname'];
                $username = $con['EmailMaster']['email'];
                $password = $con['EmailMaster']['password'];

                $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail: ' . imap_last_error());
                imap_setflag_full($inbox,implode(",", $uid), "\\Seen \\Flagged");

                imap_expunge($inbox);
                imap_close($inbox);
            }
            else
            {
                echo "Details Not Found";
            }
            $this->set('emails',$Mail);
        }
    }
	
    
    public function send_mail(){
        $this->layout="user";
        if($this->request->is('Post')){
            require_once(APP . 'Lib' . DS . 'mailer' . DS . 'PHPMailerAutoload.php');

            $reply_to=$this->request->data['toemail'];
            $ccemail=$this->request->data['ccemail'];
            $cc=  explode(",",$ccemail);
            
            //$bccemail=$this->request->data['bccemail'];
            //$bc=  explode(",",$bccemail);
            
            $sendtype=$this->request->data['sendtype'];
            $MailerName=$this->request->data['MailerName'];
            $Id = $this->request->data['id'];

            if($sendtype =="reply"){
                $st="RE: ";
            }
            else if($sendtype =="replyall"){
                $st="RE: ";
            }
            else if($sendtype =="forward"){
                $st="Fwd: ";
            }
            
 
            $mailmasterid=$this->request->data['mailmasterid'];
            $msgArr=$this->MailMaster->find('first',array('conditions'=>array('Id'=>$mailmasterid)));
            $PreviousMsg=$msgArr['MailMaster']['mail_message'];

            $to = $this->request->data['to'];
            $sub = $this->request->data['subject'];
            $message = $this->request->data['emails']['message'];
            $host = $this->EmailMaster->find('first',array('conditions'=>array('Id'=>$Id)));
            
            $email     = $host['EmailMaster']['email'];
            $password  = $host['EmailMaster']['password'];
            $to_id     = $to;
            $subject   = $sub;
            $hostname  = $host['EmailMaster']['send_hostname'];
            $port      = $host['EmailMaster']['send_port'];
             
           
           // echo $email;die;
            
            
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = $hostname;
            $mail->Port = $port;
            $mail->From = $email;
            $mail->FromName = $email;
            $mail->To = $email;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = $email;
            $mail->Password = $password;
            $mail->AddAddress ($reply_to);

            if(!empty($reply_to)){   
                $mail->AddReplyTo($email);
            }
            if(!empty($cc)){
                foreach($cc as $ccval){
                    $mail->AddCC($ccval);
                }
            }
            /*
            if(!empty($bc)){
                foreach($bc as $bcval){
                    $mail->AddBCC($bcval);
                }
            }*/
            
            $mail->Subject = $st.$subject;
            $mail->msgHTML($message.$PreviousMsg);

            if(!$mail->send()){
                $error = "Mailer Error: " . $mail->ErrorInfo;
                $this->Session->setFlash($error);
                return $this->redirect(array('controller'=>'Emails','action' => 'index'));    
            }
            else{                     
                $status="UNDER PROCESS";
                $data=array('status'=>"'".$status."'");                  
                $this->MailMaster->updateAll($data,array('Id'=>$mailmasterid));
                $this->Session->setFlash('Message sent successfully!');
                return $this->redirect(array('controller'=>'Emails','action' => 'index'));
            } 
            
        }
    }		
}
?>