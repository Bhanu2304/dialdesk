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
   
function showDetails(sender_id){
    $.post('<?php echo $this->webroot;?>SocialMedia/get_user_details',{sender_id:sender_id},function(result){
        if(result){
            $("#show-post-details").trigger('click'); 
            
            $("#user_details").html(result);
        } 
    });
}

function userPostDetails(sender_id,access_token,message_id){    
    $.post('<?php echo $this->webroot;?>SocialMedia/get_user_details',{sender_id:sender_id},function(result){
        if(result){ 
            $("#show-post-box").trigger('click'); 
            $("#showfbcomm").html(result);
            $("#access_token").val(access_token);
            $("#message_id").val(message_id);
            $("#post_id").val(sender_id);  
        } 
    });
}

function replyPost(){
    var token = $("#access_token").val();
    var message_id= $("#message_id").val();
    var post_id= $("#post_id").val();
    
    FB.api('/'+message_id+'/comments?access_token=','POST',{access_token:token, message:$("#reply_post").val()}, function(response){
        if(response){
            $.post('<?php echo $this->webroot;?>SocialMedia/update_feedback',{postid:post_id,posttype:'comment'},function(result){
                if(result){
                    $("#close-post-box").trigger('click');
                    $("#show-message-box").trigger('click');
                    $("#msg-text-message").text('Relpy your post/comment successfully.');
                    $("#access_token").val('');
                    $("#message_id").val('');
                    $("#reply_post").val('');
                } 
            });
        }
    });
}

/*  Get User Poset Details  */
function getUserPost(sender_id){
    $.post('<?php echo $this->webroot;?>SocialMedia/get_user_post',{sender_id:sender_id},function(result){
        if(result){
            $("#userpost").html(result);
        } 
    });
}


function caseClose(postid,posttype){  
    $.post('<?php echo $this->webroot;?>SocialMedia/case_close',{postid:postid,posttype:posttype},function(result){
        if(result){ 
           window.location.reload();
        } 
    });
}


function fbDetails(fb_page_id,fb_page_name,access_token){
    window.location='<?php echo $this->webroot;?>SocialMedia?pageid='+fb_page_id;
     
    /*
    //$("#loadingimg").show();
    var client_id="<?php echo $smd['SocialMediaMaster']['client_id'];?>";
    var fb_app_id="<?php echo $smd['SocialMediaMaster']['fb_app_id'];?>";
    var fb_app_secret="<?php echo $smd['SocialMediaMaster']['fb_app_secret'];?>";
   
    $.post('<?php echo $this->webroot;?>crone/fb_details.php',{client_id:client_id,fb_app_id:fb_app_id,fb_app_secret:fb_app_secret,fb_page_id:fb_page_id,fb_page_name:fb_page_name,access_token:access_token},function(result){
        if(result){ 
           //window.location='<?php echo $this->webroot;?>SocialMedia?pageid='+fb_page_id;
        } 
    });*/
}

</script>

<a class="btn btn-primary btn-lg" id="show-message-box" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                        <!--
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        -->
                        <div id="" onclick="get_Called()"></div>
                    </div>
                    <div class="modal-body">
                        <p id="msg-text-message" ></p>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<span id="show-post-box" data-toggle="modal" data-target="#sendpost"></span>
<div class="modal fade" id="sendpost"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:115px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4  class="modal-title">Reply Post/Comment</h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    
                    <div class="form-group" >
                        <div class="col-sm-12" id="showfbcomm" style="height:250px;overflow:scroll;" >
                            
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" id="access_token" >
                            <input type="hidden" id="message_id" >
                            <input type="hidden" id="post_id" >
                            <textarea id="reply_post" style="width:568px;height:140px;margin-top:30px;" class="form-control" ></textarea>
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-post-box" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="button" onclick="replyPost()"  value="Submit" class="btn-web btn">
            </div>
        </div>
    </div>
</div>




<span id="show-post-details" data-toggle="modal" data-target="#viewpost"></span>
<div class="modal fade" id="viewpost"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:115px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4  class="modal-title">Post History</h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div style="height:600px;overflow:scroll;">
                        <div id="user_details"  ></div>
                    </div>
                </div>   
            </div>
           
        </div>
    </div>
</div>




<div class="container-fluid" style="margin-top:-30px;" >
    <div data-widget-group="group1"> 
        <div style="font-size: 15px;color:green;padding-bottom: 10px;"><?php echo $this->Session->flash();?></div>
        
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>User Interactions</h2>
                <div class="panel-ctrls"></div>
            </div>
            
            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">PAGE DETAILS <img id="loadingimg" style="display:none;" src="<?php echo $this->webroot;?>images/loading.gif" ></div>
                    
                    <div class="panel-body">
                       
                        <ul class="media-list">
                            <?php for($i=1;$i<=5;$i++){ ?>
                            <?php if($smd['SocialMediaMaster']['fb_page_id'.$i] !=""){?>
                            <li class="media" style="border:1px solid gray;border-radius:10px;text-align: center;font-size: 13px;color: #424242;cursor: pointer;" >                               
                                <a onclick="fbDetails('<?php echo $smd['SocialMediaMaster']['fb_page_id'.$i];?>','<?php echo $smd['SocialMediaMaster']['fb_page_name'.$i];?>','<?php echo $smd['SocialMediaMaster']['fb_page_token'.$i];?>')" href="#"> <?php echo $smd['SocialMediaMaster']['fb_page_name'.$i];?></a>
                                
                            </li>
                            <?php }?>
                            <?php }?>
                            <hr/>
                            <li class="media" style="border:1px solid gray;border-radius:10px;text-align: center;font-size: 13px;color: #424242;cursor: pointer;" >                               
                                <a href="<?php echo $this->webroot;?>SocialMedia/conversation"> Chat Messenger</a>
                            </li>
                             
                        </ul>
                    </div>  
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">USERS DETAILS</div>
                    <div class="panel-body">
                        <div style="height:550px;overflow:scroll;width:282px;">
                        <ul class="media-list">
                            <?php if(!empty($userDetails)){?>
                            <?php foreach ($userDetails as $userRow){?>
                            <?php //if($userRow['social_media_feedback']['status'] =="OPEN"){$openStatus="color:red;";}else{$openStatus="color:gray;";}?>
                            <li class="media">
                                <div class="media-body" style="width:300px;">
                                    <div class="media">
                                        <a class="pull-left" href="#" onclick="getUserPost('<?php echo $userRow['social_media_feedback']['sender_id'];?>')" >
                                            <img class="media-object img-circle" style="max-height:40px;margin-top: -6px;" src="<?php echo $this->webroot;?>facebook_images/userlogo.png" />
                                        </a>
                                        <div class="media-body" style="cursor: pointer;" onclick="getUserPost('<?php echo $userRow['social_media_feedback']['sender_id'];?>')" >
                                            <span style="margin-top:10px;" ><?php echo $userRow['social_media_feedback']['sender_name'];?></span> <span style="background-color: #a2abb2;border-radius: 10px;color: #fff;display: inline-block;font-size: 12px;font-weight: bold;line-height: 1;min-width: 10px;padding: 3px 7px;text-align: center;vertical-align: baseline;white-space: nowrap;"><?php echo $userRow[0]['totalCount'];?></span><br/>                        
                                            <small class="text-muted" >
                                                <?php 
                                                $timestamp = strtotime($userRow[0]['update_time']);
                                                echo date("l jS M Y g:ia", $timestamp);
                                                ?>
                                                <br/>                    
                                            </small>
                                            <a href="#" onclick="caseClose('<?php echo $userRow['social_media_feedback']['sender_id'];?>','comment')" title="Close"  >
                                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">delete_forever</i></label>
                                            </a>
                                            <!--
                                            <span style="<?php echo $openStatus;?>"> <?php echo $userRow['social_media_feedback']['status'];?></span>
                                            -->
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
                        RECENT POST/COMMENT HISTORY
                    </div>
                    <div class="panel-body" id="userpost">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




