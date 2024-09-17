<script>
function resetPassword(){
	if($.trim($("#new_password").val()) !=$.trim($("#confirm_password").val())){
		$("#msg").html('<span style="color:red;font-size:16px;">Your both password do not match.</span>');
		return false;
	}
	else{
		return true;	
	}
}
</script>
<div class="container" id="login-form">
    <a href="http://dialdesk.in/" class="login-logo"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Reset Password</h2>
                </div>
                <div class="panel-body">
                    <form method="post" action="<?php echo $this->webroot;?>ClientActivations/save_password" onsubmit=" return resetPassword()" id="validate-form" class="form-horizontal row-border" data-parsley-validate >
                        <div id="msg" class="reset-pass" ><?php echo $this->Session->flash(); ?></div>
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <input type="password" name="new_password" id="new_password" placeholder="Enter new password" autocomplete="off" class="form-control" required />                                 
                                </div>
                           </div>
                        </div>

                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-type new password" autocomplete="off" class="form-control" required />
                                    <input type="hidden" name="cid" id="cid" value="<?php if(isset($cid)){echo $cid;}?>" />                                  
                                </div>
                           </div>
                        </div>

                       

                        <div class="panel-footer">
                            <div class="clearfix">
                                <a href="<?php echo $this->webroot;?>client_activations/login" class="btn btn-default pull-left">Login</a>
                                <input type="submit"  class="btn btn-web pull-right" value="Submit" >
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>
