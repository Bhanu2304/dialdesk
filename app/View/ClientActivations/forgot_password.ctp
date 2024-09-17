<script>
function forgotPassword(){
    $("#fm").remove();
    var user_data = $("#user_data").val();
    
    if($.trim(user_data) ===""){
        $("#user_data").after('<span id="fm" style="color:red;" >'+blank_err+'</span>')
        return false;
    }
    else{
        if($.trim(user_data).match(phoneNum)) {	
            if(existPhone(user_data) !=''){
               sent_forgot_otp(user_data);
		$(".otp").fadeIn(500);
		$("#cover").fadeTo(500, 0.5);
            }
            else{
                 $("#user_data").after('<span id="fm" style="color:red;" >This phone no not exist in database.</span>')
                 return false;
            }
           
        }
        else if (filter.test($.trim(user_data))) {
             if(existEmail(user_data) !=''){
                $("#forgot_password_form").submit();
                return true;
            }
            else{
                 $("#user_data").after('<span id="fm" style="color:red;" >This email not exist in database.</span>')
                 return false;
            }
        }
        else{
            $("#user_data").after('<span id="fm" style="color:red;" >Please enter valid details.</span>')
            return false;
        }
        
        
    }
       
                
}

function existPhone(data){
    var posts = $.ajax({type: 'POST',url:'<?php echo $this->webroot;?>ClientActivations/check_exist_phone',async: false,dataType: 'json',data: {phone_no:data},done: function(response) {return response;}}).responseText;	
    return posts;
}
function existEmail(data){
    var posts = $.ajax({type: 'POST',url:'<?php echo $this->webroot;?>ClientActivations/check_exist_email',async: false,dataType: 'json',data: {email:data},done: function(response) {return response;}}).responseText;	
    return posts;
}

// Send OTP

g_timer = null;

function sent_forgot_otp(){  
    $("#fotp_box").hide().delay(5000).fadeOut();
    $("#fotp_msgbox").html('');
    var phone = $("#user_data").val();
    $.ajax({
            type: "POST",
            url:'<?php echo $this->webroot;?>ClientActivations/send_forgot_otp',
            data: {phone:phone},
            success: function(data){
                $("#fotp_box").show();
                document.getElementById("fotp_box").className = "info";
                $("#fotp_msgbox").html('Your OTP resend successfully.');
                clearTimeout(g_timer);
                startTimer();
            }
    });	
}

function startTimer() {
    g_timer = window.setTimeout(function() {
         $.post('<?php echo $this->webroot;?>ClientActivations/delete_forgot_otp',function(){});
    }, 180000);
}

// Match OTP
function save_forgot_otp(url){
    $("#fotp_box").hide().delay(5000).fadeOut();
    $("#fotp_msgbox").html('');
    
    var otp_data = $("#fotp_data").val();
    var pnone_no = $("#user_data").val();
    if($.trim(otp_data) ===''){
            $("#fotp_box").show();
            document.getElementById("fotp_box").className = "warning";
            $("#fotp_msgbox").html('Please enter correct otp.');
            $("#fotp_data").focus();
            return false;
    }
    else{
        $.ajax({
            type: "POST",
            url:url,
            data: {otpval:otp_data,phone_no:pnone_no},
            success: function(data){
                    if(data !=''){
                        $(".otp").hide();
                        $("#cover").hide();
                        window.location.href =data;
                    }
                    else{
                        $("#fotp_data").focus();
                        $("#fotp_box").show();
                        document.getElementById("fotp_box").className = "error";
                        $("#fotp_msgbox").html('Your OTP mismatch enter correct OTP or resend otp again.');					
                        return false;
                    }
            }
        });
	}
}

</script>


<div class="otp">                       
<h4 style="margin-top:30px;">Client Authentication <i class="fa fa-user detail" aria-hidden="true"></i></h4>  
<hr/>
<p>OTP is sent to your registered phone no so please enter these details to varify your phone no.</p>

<div id="fotp_box" style="display: none;" >
     <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
     <span id="fotp_msgbox"></span>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">OTP</label>	
    <div class="col-sm-8">                                
        <input type="text" class="form-control" id="fotp_data" style="width:200px;" autocomplete="off" placeholder="One Time Password" />
    </div>
</div>

<input  type="button"  onclick="sent_forgot_otp()" class="btn btn-default pull-right" value="Resend" />
<input  type="button" onclick="save_forgot_otp('<?php echo $this->webroot;?>ClientActivations/matchotp_forgot_otp')" class="btn btn-webs pull-right" value="Submit" />

</div>
<div id="cover" ></div>

<div class="container" id="login-form">
    <a href="http://dialdesk.in/" class="login-logo"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Forgot Password</h2>
                </div>
                <p class="forgot-desc">We will send a link on your registered email or one time password (OTP) on your registered mobile to reset your password.</p>
                <div class="panel-body">
                    <form method="post" action="<?php echo $this->webroot;?>ClientActivations/forgot_password" id="forgot_password_form" class="form-horizontal row-border" data-parsley-validate >
                        <div class="pass-error"><?php echo $this->Session->flash();?></div>
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <input type="text" name="user_email" id="user_data" placeholder="Enter Your Mobile Or Email" autocomplete="off" class="form-control" required /> 
                                </div>
                           </div>
                        </div>
                        
                        <div class="panel-footer">
                            <div class="clearfix">
                                <!--
                                <a href="<?php echo $this->webroot;?>client_activations/company_registration" class="btn btn-default pull-left">New User</a>
                                -->
                                <input type="button" onclick="forgotPassword()" class="btn btn-web pull-right" value="Submit" >
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>

