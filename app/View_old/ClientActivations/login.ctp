<div class="container" id="login-form">
    <a href="http://dialdesk.in/" class="login-logo"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
    <div class="row">
    	<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
          	<div class="panel-heading">
                    <h2>User Login</h2>
              	</div>
               	<div class="panel-body">
                    <?php echo $this->Form->create('ClientActivations',array('action'=>'login','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="login-error">
                        <?php echo $this->Session->flash(); ?>
                    </div>
                 	<div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <?php echo $this->Form->input('emailid',array('label'=>false,'placeholder'=>'Email / Mobile no','class'=>'form-control','required'=>true));?>
                                </div>
                            </div>
                       	</div>
                        
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-key"></i>
                                    </span>
                                    <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>'Password','class'=>'form-control','required'=>true));?>
                                </div>
                            </div>
                        </div>
     
                        <div class="form-group mb-md">
                            <div class="col-xs-12" style="margin-left: 20px;">
                                <a href="<?php echo $this->webroot;?>ClientActivations/forgot_password" class="pull-left">Forgot password?</a>
                            </div>
                	</div>
               	</div>
              	<div class="panel-footer">
                    <div class="clearfix">
             		<a href="<?php echo $this->webroot;?>client_activations/company_registration" class="btn btn-default pull-left">SIGN UP</a>
                        <input type="submit"  class="btn btn-web pull-right" value="Login" >
                    </div>
          	</div>
                <?php echo $this->Form->end();?>
            </div>        
        </div>
    </div>
</div>


