<?php ?>

<script>
//g_timer = null;

window.fbAsyncInit = function() {
FB.init({
  appId      : '<?php echo $smd['SocialMediaMaster']['fb_app_id'];?>',
  xfbml      : true,
  cookie  : true,
  version    : 'v2.8'
});

//clearTimeout(g_timer);
//startTimer();
};

(function(d, s, id){
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement(s); js.id = id;
 js.src = "//connect.facebook.net/en_US/sdk.js";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
   
/*
function startTimer() {
    g_timer = window.setTimeout(function() {
        $.post('<?php echo $this->webroot;?>app/webroot/crone/get_facebook_interaction.php',function(){});
        location.reload(); 
    }, 60000);
}
*/
   
function loginStatus(id,post_id){
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            
           document.getElementById('messageid').value=id;
           document.getElementById('postid').value=post_id;
           $("#show-post-box").trigger('click'); 
        }
        else {
            $("#show-message-box").trigger('click');
            $("#msg-text-message").text('Please first login then relpy your post/comment.');
        }
    });
}

function getUserId(){
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            var uid = response.authResponse.userID;
            $("#fbuserid").text(uid);  
        }
        else {
            $("#show-message-box").trigger('click');
            $("#msg-text-message").text('Please first login then get user id.');
        }
    });
}


function showDetails(sender_id){
    $.post('<?php echo $this->webroot;?>SocialMedia/get_user_details',{sender_id:sender_id},function(result){
        if(result){
            $("#show-post-details").trigger('click'); 
            
            $("#user_details").html(result);
        } 
    });
}

function showDetails1(sender_id){
    $.post('<?php echo $this->webroot;?>SocialMedia/get_user_details',{sender_id:sender_id},function(result){
        if(result){ 
            $("#showfbcomm").html(result);
        } 
    });
}


function getConversation(pageid,access_token){
    $("#loadingimg").show();
          
        var url="https://graph.facebook.com/"+pageid+"/conversations?fields=id,message_count,updated_time,senders,messages{message,from,to,created_time}&access_token="+access_token;
        $.post('<?php echo $this->webroot;?>SocialMedia/getConversation',{url:url,pageid:pageid,access_token:access_token},function(result){
            window.location.href='<?php echo $this->webroot;?>SocialMedia/conversation?pageid='+pageid;
        }); 
  
}

function getMessage(parent_id,access_token){
    $.post('<?php echo $this->webroot;?>SocialMedia/get_message',{parent_id:parent_id,access_token:access_token},function(result){
        if(result){ 
            $("#user_message").html(result);
        } 
    });
}
   
function userReply(thread,access_token){  
    var text_message = document.getElementById('text_message').value;
    var pageid='<?php echo $pageid;?>';
  
    FB.api("/"+ thread +"/messages", "POST", {access_token: access_token,message:text_message},
        function (response) {
            if (response && !response.error) {

                $.post('<?php echo $this->webroot;?>SocialMedia/update_feedback',{postid:thread,posttype:'messenger',},function(result){
                    if(result){
                        getConversation(pageid,access_token);
                    } 
                });  
            }
        }
    );
           
}

function caseClose(postid,posttype){
    $.post('<?php echo $this->webroot;?>SocialMedia/case_close',{postid:postid,posttype:posttype},function(result){
        if(result){ 
           window.location.reload();
        } 
    });
}

</script>

<div class="container-fluid" style="margin-top:10px" >
    <div data-widget-group="group1"> 
        <div style="float:left;margin-top:-15px;" >
            <a href="<?php echo $this->webroot;?>SocialMedia"><input type="button" value="BACK" class="btn btn-web" ></a>
        </div>
        <br/><br/>
        
        <div class="panel panel-default" id="panel-inline">

            <div class="panel-heading">
                <h2>User Conversation</h2>
                <div class="panel-ctrls"></div>
            </div>
            
            <div class="panel-body no-padding">
                <div class="col-md-3">
                    <div class="panel panel-primary" >
                        <div class="panel-heading">PAGE DETAILS <img id="loadingimg" style="display:none;" src="<?php echo $this->webroot;?>images/loading.gif" ></div>
                        <div class="panel-body">
                            <ul class="media-list">
                                <?php for($i=1;$i<=5;$i++){ ?>
                                <?php if($smd['SocialMediaMaster']['fb_page_id'.$i] !=""){?>
                                <li class="media" style="border:1px solid gray;border-radius:10px;text-align: center;font-size: 13px;color: #424242;cursor: pointer;" >
                                    <div onclick="getConversation('<?php echo $smd['SocialMediaMaster']['fb_page_id'.$i];?>','<?php echo $smd['SocialMediaMaster']['fb_page_token'.$i];?>')"> <?php echo $smd['SocialMediaMaster']['fb_page_name'.$i];?></div>
                              </li>
                              <?php }?>
                              <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">USERS</div>
                        <div class="panel-body">
                             <div style="height:550px;overflow:scroll;width:282px;">
                            <ul class="media-list">
                            <?php if(!empty($data)){?>
                            <?php foreach ($data as $userArr){?>
                                 <?php if($userArr['facebook_conversation_master']['update_status'] =="OPEN"){$openStatus="color:red;";}else{$openStatus="color:gray;";}?>
                                <li class="media">
                                    <div class="media-body" style="width:300px;">
                                        <div class="media">
                                            
                                            <a class="pull-left" href="#" onclick="getMessage('<?php echo $userArr['facebook_conversation_master']['conversation_id'];?>','<?php echo $userArr['facebook_conversation_master']['access_token'];?>')" >
                                                <img class="media-object img-circle" style="max-height:40px;margin-top: -6px;" src="<?php echo $this->webroot;?>facebook_images/userlogo.png" />
                                            </a>
                                            <div class="media-body" style="cursor: pointer;" onclick="getMessage('<?php echo $userArr['facebook_conversation_master']['conversation_id'];?>','<?php echo $userArr['facebook_conversation_master']['access_token'];?>')" >
                                                <span style="margin-top:10px;" ><?php echo $userArr['facebook_conversation_master']['user_name'];?></span> <span style="background-color: #a2abb2;border-radius: 10px;color: #fff;display: inline-block;font-size: 12px;font-weight: bold;line-height: 1;min-width: 10px;padding: 3px 7px;text-align: center;vertical-align: baseline;white-space: nowrap;"><?php echo $userArr['facebook_conversation_master']['message_count'];?></span>
                                                <br/>                        
                                                <small class="text-muted">
                                                    <?php 
                                                    $timestamp = strtotime($userArr['facebook_conversation_master']['update_time']);
                                                    echo date("l jS M Y g:ia", $timestamp);
                                                    ?>
                                                    <br/>
                                                    <span style="<?php echo $openStatus;?>" ><?php echo $userArr['facebook_conversation_master']['update_status'];?></span>
                                                    <br/>
                                                    <a target="_blank" href="<?php echo $this->webroot;?>Agents?postid=<?php echo $userArr['facebook_conversation_master']['id'];?>&posttype=messenger" title="ADD SR"  >
                                                        <span><label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">library_add</i></label></span>
                                                    </a>
                                                    <a href="#" onclick="caseClose('<?php echo $userArr['facebook_conversation_master']['id'];?>','messenger')" title="Close"  >
                                                        <span><label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">delete_forever</i></label></span>
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php }?>
                            <?php }?>
                                </ul>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="panel panel-info">
                    <div class="panel-heading">
                        RECENT CHAT HISTORY
                    </div>
                    <div class="panel-body" id="user_message">
                   
                    </div>
                       
                        
                </div>
            </div>
   
            <div class="panel-footer"></div>
            </div>
        </div>
    </div>     
</div>

