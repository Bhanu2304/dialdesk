<?php
class SocialMediaController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('SocialMediaFeedback','SocialMediaMaster','MailMaster');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','update_feedback','get_user_details','case_close','getConversation','conversation','get_message','get_user_post');
    }
	
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $this->set('smd',$this->SocialMediaMaster->find('first',array('condition'=>array('client_id'=>$ClientId,'social_media_type'=>'facebook'))));
        
        if($_REQUEST['pageid'] && $_REQUEST['pageid'] !=""){
            $userDetails=$this->SocialMediaFeedback->query("SELECT sender_id,sender_name,COUNT(id) totalCount,max(sender_time) update_time,status FROM social_media_feedback WHERE client_id='{$ClientId}' AND page_id='{$_REQUEST['pageid']}'  GROUP BY sender_name");
            $this->set('userDetails',$userDetails);
            $this->set('pageid',$_REQUEST['pageid']);
        }
        else{
            $this->set('userDetails',array());
            $this->set('data',array()); 
            $this->set('pageid',array());
        } 
    }

    public function get_user_post(){
        $ClientId = $this->Session->read('companyid');
        if($_REQUEST['sender_id'] && $_REQUEST['sender_id'] !=""){
            $data=$this->SocialMediaFeedback->query("SELECT * FROM social_media_feedback WHERE client_id='{$ClientId}' AND sender_id ='{$_REQUEST['sender_id']}' ORDER BY sender_time DESC");
            ?>
            <ul class="media-list"  style="overflow: scroll;height:550px;width:365px;" >
            <?php $i = 1;foreach($data as $row){?>
                <li class="media">
                    <div class="media-body">
                        <?php if($row['social_media_feedback']['status'] =="OPEN"){$backcolor="background-color:#F5F5F5;"; }else{$backcolor="";}?>
                        <div class="media" style="<?php echo $backcolor; ?>" >
                            <a class="pull-left" href="#"  onclick="showDetails(<?php echo $row['social_media_feedback']['id'];?>)" >
                                <img width="50" class="media-object img-circle " src="<?php echo $this->webroot;?>facebook_images/userlogo.png" />
                            </a>
                             
                            
                            <div class="media-body" style="width:570px;" >
                                <span style="cursor: pointer;" onclick="showDetails(<?php echo $row['social_media_feedback']['id'];?>)"> <?php echo $row['social_media_feedback']['message']?></span>
                                <br />
                               
                                
                                <small class="text-muted" style="cursor: pointer;" onclick="showDetails(<?php echo $row['social_media_feedback']['id'];?>)" >
                                    <?php 
                                    echo $row['social_media_feedback']['sender_name']. '<br/>';
                                    $timestamp = strtotime($row['social_media_feedback']['sender_time']);
                                    echo date("l jS M Y g:ia", $timestamp).' | ';
                                    echo $row['social_media_feedback']['status'];
                                    ?>
                                </small>
                                <br />
                                
                                <a title="Reply" onclick="userPostDetails('<?php echo $row['social_media_feedback']['id'];?>','<?php echo $row['social_media_feedback']['access_token'];?>','<?php echo $row['social_media_feedback']['message_id'];?>')"   href="#">
                                    <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-send"></i></label>
                                </a>
                                    
                                <a target="_blank" href="<?php echo $this->webroot;?>Agents?postid=<?php echo $row['social_media_feedback']['id'];?>&posttype=comment" title="ADD SR"  >
                                    <label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">library_add</i></label>
                                </a>
                                <hr />
                            </div>
                        </div>
                    </div>
                </li>
                
            <?php }?>
            </ul>
        <?php
        }
        die;
    }
    
    
    public function get_user_details(){
        $ClientId = $this->Session->read('companyid');
        if($_REQUEST['sender_id'] && $_REQUEST['sender_id'] !=""){
            
            $dtArr=$this->SocialMediaFeedback->query("SELECT * FROM social_media_feedback WHERE client_id='{$ClientId}' AND id='{$_REQUEST['sender_id']}' ORDER BY id DESC");
            $replyid=$dtArr[0]['social_media_feedback']['reply_id'];
            $senderid=$dtArr[0]['social_media_feedback']['sender_id'];
            if($replyid !=""){
            $data=$this->SocialMediaFeedback->query("SELECT * FROM social_media_feedback WHERE client_id='{$ClientId}' AND reply_id='{$replyid}' ORDER BY id ASC");
            }
            else{
              $data=$this->SocialMediaFeedback->query("SELECT * FROM social_media_feedback WHERE client_id='{$ClientId}' AND sender_id='{$senderid}' ORDER BY id ASC");  
            }
            ?>
               
                <table  cellpadding="0" cellspacing="0" border="0" class="table" >
                    <thead>
                        <!--
                        <tr>
                            <th>Sender</th>
                            <th>Type</th>
                            <th>Message</th>
                            <th>Time</th>
                        </tr>
                        -->
                    </thead>
                    
                    <tbody>
                        <?php $i = 1;foreach($data as $row){?>
                            <tr>
                               <td>
                                    <?php echo $row['social_media_feedback']['sender_name'];?>
                                </td>
                                <td>
                                    <?php //echo $row['social_media_feedback']['sender_type'];?>
                                </td>
                                <td>
                                    <?php if($row['social_media_feedback']['picture'] !=""){?>
                                    <img src="<?php echo $row['social_media_feedback']['picture'];?>" style="width:200px;" ><br/><br/>
                                    <?php }?>
                                    <?php echo $row['social_media_feedback']['message'];?>
                                </td>
                                
                                <td>
                                    <?php
                                    $timestamp = strtotime($row['social_media_feedback']['sender_time']);
                                    echo date("l jS M Y g:ia", $timestamp);
                                    ?>
                                </td>
                            </tr>
                            
                           
                        <?php }?>

                    </tbody>
                </table>
                
            <?php
        }die;
    }
    
    
    
    public function update_feedback(){
        if(isset($_REQUEST['postid']) && isset($_REQUEST['posttype']) && $_REQUEST['postid'] !="" && $_REQUEST['posttype'] !=""){
            $status="UNDER PROCESS";
            $data=array('status'=>"'".$status."'");
            if($_REQUEST['posttype'] =="comment"){
                $this->SocialMediaFeedback->updateAll($data,array('id'=>$_REQUEST['postid'],'client_id' => $this->Session->read('companyid')));
            }
            else if($_REQUEST['posttype'] =="messenger"){
                $this->SocialMediaFeedback->query("UPDATE facebook_conversation_master SET update_status='$status',sr_update_time=NOW()  WHERE conversation_id='{$_REQUEST['postid']}'");  
            }
            echo "1";die;
        }
    }
    
    public function case_close(){
        if(isset($_REQUEST['postid']) && isset($_REQUEST['posttype']) && $_REQUEST['postid'] !="" && $_REQUEST['posttype'] !=""){
            $status="CLOSE";
            $data=array('status'=>"'".$status."'");
            if($_REQUEST['posttype'] =="comment"){
                $this->SocialMediaFeedback->updateAll($data,array('sender_id'=>$_REQUEST['postid'],'client_id' => $this->Session->read('companyid')));
            }
            else if($_REQUEST['posttype'] =="messenger"){
                $this->SocialMediaFeedback->query("UPDATE facebook_conversation_master SET update_status='$status',sr_update_time=NOW()  WHERE id='{$_REQUEST['postid']}'");  
            }
            else if($_REQUEST['posttype'] =="emailbox"){
                $this->MailMaster->updateAll($data,array('Id'=>$_REQUEST['postid']));
            }
        }
        echo "1";die; 
    }
    
    
    
    
    public function conversation() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $this->set('smd',$this->SocialMediaMaster->find('first',array('condition'=>array('client_id'=>$ClientId,'social_media_type'=>'facebook'))));
        if(isset($_REQUEST['pageid']) && $_REQUEST['pageid'] !=""){
            $data=$this->SocialMediaFeedback->query("SELECT * FROM facebook_conversation_master WHERE page_id='{$_REQUEST['pageid']}'");
            $this->set('data',$data); 
            $this->set('pageid',$_REQUEST['pageid']); 
        }   
    }


    public function getConversation(){ 
        

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$_REQUEST['url']);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch); 
        curl_close($ch);
       
        $dataArr=json_decode($result);
         
        foreach ($dataArr->data as $row1){
            $conversation_id=$row1->id;
            $message_count=$row1->message_count;
            $senders=$row1->senders->data;
            $user_name=$senders[0]->name;
            $user_id=$senders[0]->id;
            $date_source = strtotime($row1->updated_time);
            $updated_time = date('Y-m-d H:i:s', $date_source);
            
            $existData=$this->SocialMediaFeedback->query("SELECT id FROM facebook_conversation_master WHERE conversation_id='$conversation_id' limit 1");
            if(empty($existData)){
                $this->SocialMediaFeedback->query("INSERT INTO facebook_conversation_master(page_id,access_token,conversation_id,message_count,user_name,user_id,update_time,update_status)VALUES('{$_REQUEST['pageid']}','{$_REQUEST['access_token']}','$conversation_id','$message_count','$user_name','$user_id','$updated_time','OPEN')");
            }
            else{
                $this->SocialMediaFeedback->query("UPDATE facebook_conversation_master SET update_time='$updated_time',message_count='$message_count'  WHERE conversation_id='$conversation_id'");  
            }
            
            
            foreach ($row1->messages->data as $row2){
               //$message=$row2->message;
               $message=preg_replace("/[\s-]+/", " ", $row2->message);
               $message_id= $row2->id;
               $from = $row2->from->name;
               $to = $row2->to->data[0]->name;
               $date_source1 = strtotime($row2->created_time);
               $created_time = date('Y-m-d H:i:s', $date_source1);
               
                
               
               $existData1=$this->SocialMediaFeedback->query("SELECT id FROM facebook_message_master WHERE message_id='$message_id' limit 1");
               if(empty($existData1)){
                    $this->SocialMediaFeedback->query("INSERT INTO facebook_message_master(parent_conversation_id,message,message_id,from_user,to_user,create_time)VALUES('$conversation_id','$message','$message_id','$from','$to','$created_time')");
               }
               
            }
    
        }
        die;
  
        
    }
    
    
    public function get_message(){
        if($_REQUEST['parent_id'] && $_REQUEST['parent_id'] !=""){
            $data=$this->SocialMediaFeedback->query("SELECT * FROM facebook_message_master WHERE parent_conversation_id='{$_REQUEST['parent_id']}' order by create_time desc");  
            ?>
            <ul class="media-list"  style="overflow: scroll;height:500px;width:365px" >
            <?php $i = 1;foreach($data as $row){?>
                <li class="media">
                    <div class="media-body">
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img width="50" class="media-object img-circle " src="<?php echo $this->webroot;?>facebook_images/userlogo.png" />
                            </a>
                            <div class="media-body" style="width:300px;">
                                <?php echo $row['facebook_message_master']['message']?>
                                <br />
                                <small class="text-muted" >
                                    <?php echo $row['facebook_message_master']['from_user']?>
                                    <br />
                                    <?php
                                    $timestamp = strtotime($row['facebook_message_master']['create_time']);
                                    echo date("l jS M Y g:ia", $timestamp);
                                    ?>
                                </small>
                                <hr />
                            </div>
                        </div>
                    </div>
                </li>
                
            <?php }?>
            </ul>
            <div class="panel-footer" >
                <div class="input-group">
                    <input type="text" class="form-control" id="text_message" placeholder="Enter Message" />
                    <span class="input-group-btn"  >
                        <button class="btn btn-info" onclick="userReply('<?php echo $row['facebook_message_master']['parent_conversation_id']?>','<?php echo $_REQUEST['access_token']?>')" type="button">SEND</button>
                    </span>
                </div>
            </div>
        <?php
        }
        die;
    }
    
    
    
    
    
     
}

?>