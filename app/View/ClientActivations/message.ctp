<div class="container" id="login-form">
    <a href="http://dialdesk.in/" class="login-logo"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo $msg;?>
                    <?php if(isset($type) && $type =="evs"){?>
                    	<div class="panel-footer">
                    <div class="clearfix">
             		<a href="<?php echo $this->webroot;?>client_activations/login" class="btn btn-web pull-left" >Login</a>
                    </div>
          	</div>
                    <?php }?>
                </div>
            </div> 
        </div>
    </div>
</div>
