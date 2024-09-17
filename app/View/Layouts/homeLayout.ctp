<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo $this->Html->charset(); ?>
	<title> <?php echo $this->fetch('title'); ?> </title>
        <link rel="shortcut icon" href="<?php echo $this->webroot; ?>css/assets/img/logo-icon-dark.png">
	<?php
	echo $this->fetch('description');
        echo $this->fetch('author');
        //echo $this->Html->meta('icon');
        
        echo $this->Html->css('assets/css/styles');
        echo $this->Html->css('assets/css/mystyles');
        echo $this->Html->css('assets/plugins/progress-skylo/skylo');
        echo $this->Html->css('assets/material-design-iconic-font/css/material-icon');
        echo $this->Html->css('assets/fonts/font-awesome/css/font-awesome.min');
        
	echo $this->Html->script('jquery-1.11.3.min');
	echo $this->Html->script('jquery-migrate-1.2.1.min');
	echo $this->Html->script('jquery.nivo.slider.pack_B49ABFE6');
	echo $this->Html->script('company_registration');
	echo $this->Html->script('function');
	echo $this->Html->script('process_integration');		
	
        echo $this->Html->script('assets/js/jquery-1.10.2.min');
        echo $this->Html->script('assets/js/enquire.min');
        echo $this->Html->script('assets/js/jqueryui-1.10.3.min');
        echo $this->Html->script('assets/js/bootstrap.min');
        echo $this->Html->script('assets/js/application');
        echo $this->Html->script('assets/plugins/velocityjs/velocity.ui.min');
        echo $this->Html->script('assets/plugins/progress-skylo/skylo');
        echo $this->Html->script('assets/plugins/wijets/wijets');
        echo $this->Html->script('assets/plugins/sparklines/jquery.sparklines.min');
        echo $this->Html->script('assets/plugins/codeprettifier/prettify');
        echo $this->Html->script('assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop');
        echo $this->Html->script('assets/plugins/nanoScroller/js/jquery.nanoscroller.min');
        echo $this->Html->script('assets/plugins/dropdown.js/jquery.dropdown');
        echo $this->Html->script('assets/plugins/bootstrap-material-design/js/material.min');
        echo $this->Html->script('assets/plugins/bootstrap-material-design/js/ripples.min');
        echo $this->Html->script('assets/plugins/velocityjs/velocity.min');
        #echo $this->Html->script('assets/demo/demo');
        echo $this->Html->script('assets/demo/demo-switcher');
        
        echo $this->Html->script('assets/plugins/form-parsley/parsley');
        echo $this->Html->script('assets/main/formvalidation');
        ?>
        <link type='text/css' href='http://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500' rel='stylesheet'>
         <!-- Validate Plugin / Parsley -->
        <script>
        	window.ParsleyConfig = {
                  successClass: 'has-success'
                , errorClass: 'has-error'
                , errorElem: '<span></span>'
                , errorsWrapper: '<span class="help-block"></span>'
                , errorTemplate: "<div></div>"
                , classHandler: function(el) {
                    return el.$element.closest(".form-group");
                }
            };
        </script>
        
        <script>
            /*
            $(document).bind("contextmenu",function(e) {
                e.preventDefault();
            });

            $(document).keydown(function(e){
                if(e.which === 123){
                   return false;
                }
            });
            */
        </script>
          
	</head>
        <body class="focused-form animated-content" >
		<?php echo $this->fetch('content'); ?> 
	</body>
</html>






















