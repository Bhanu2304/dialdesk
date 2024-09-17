<div class="container" id="login-form">
    <a href="<?php echo $this->webroot;?>Admins " class="login-logo"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
    <div class="row">
    	<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
          	<div class="panel-heading">
                    <h2>Admin Login</h2>
              	</div>
               	<div class="panel-body">
                     <?php echo $this->Form->create('Admins',array('action'=>'index','class'=>'form-signin')); ?>
                        <div style="color:#F00;font-size: 15px;text-align: center;"><?php if(isset($err)){echo $err;}?></div>
                 	<div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <?php echo $this->form->input('UserName',array('label'=>false,'class'=>'form-control','placeholder'=>'Username','autocomplete'=>'off'));?>
                                </div>
                            </div>
                       	</div>
                        
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-key"></i>
                                    </span>
                                    <?php echo $this->form->input('Password',array('label'=>false,'class'=>'form-control','type'=>'password','placeholder'=>'Password','autocomplete'=>'off'));?>
                                </div>
                            </div>
                        </div>
               	</div>
              	<div class="panel-footer">
                    <div class="clearfix">
             		<?php echo $this->form->submit('Login',array('class'=>'btn btn-web pull-right'));?>
                    </div>
          	</div>
                <?php echo $this->Form->end();?>
            </div>        
        </div>
    </div>
</div>