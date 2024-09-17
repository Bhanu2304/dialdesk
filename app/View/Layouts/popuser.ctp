<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title> <?php echo $this->fetch('title'); ?></title>
        <link rel="shortcut icon" href="<?php echo $this->webroot; ?>css/assets/img/logo-icon-dark.png">
        <?php
        echo $this->fetch('description');
        echo $this->fetch('author'); 
        
        echo $this->Html->css('assets/material-design-iconic-font/css/material-icon');
        echo $this->Html->css('assets/fonts/font-awesome/css/font-awesome.min');
        echo $this->Html->css('assets/css/styles');
        echo $this->Html->css('assets/css/mystyles');
        echo $this->Html->css('assets/plugins/codeprettifier/prettify');
        echo $this->Html->css('assets/plugins/dropdown.js/jquery.dropdown');
        echo $this->Html->css('assets/plugins/progress-skylo/skylo');
        echo $this->Html->css('assets/plugins/form-select2/select2');
        echo $this->Html->css('assets/plugins/form-fseditor/fseditor');
        echo $this->Html->css('assets/plugins/bootstrap-tokenfield/css/bootstrap-tokenfield');
        echo $this->Html->css('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min');
        echo $this->Html->css('assets/plugins/card/lib/css/card');
        
        echo $this->Html->script('assets/js/jquery-1.10.2.min');
        echo $this->Html->script('assets/js/jqueryui-1.10.3.min');
        echo $this->Html->script('assets/js/bootstrap.min');
        echo $this->Html->script('assets/js/enquire.min');
        echo $this->Html->script('assets/plugins/velocityjs/velocity.min');
        echo $this->Html->script('assets/plugins/velocityjs/velocity.ui.min');
        echo $this->Html->script('assets/plugins/progress-skylo/skylo');
        echo $this->Html->script('assets/plugins/wijets/wijets');
        echo $this->Html->script('assets/plugins/sparklines/jquery.sparklines.min');
        echo $this->Html->script('assets/plugins/codeprettifier/prettify');
        echo $this->Html->script('assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop');
        echo $this->Html->script('assets/plugins/nanoScroller/js/jquery.nanoscroller.min');
        echo $this->Html->script('assets/plugins/dropdown.js/jquery.dropdown');
       echo $this->Html->script('assets/plugins/bootstrap-material-design/js/material.min');
       // echo $this->Html->script('assets/plugins/bootstrap-material-design/js/ripples.min');
        echo $this->Html->script('assets/js/application');
        echo $this->Html->script('assets/demo/demo');
       // echo $this->Html->script('assets/demo/demo-switcher');
        echo $this->Html->script('assets/plugins/quicksearch/jquery.quicksearch.min');
        echo $this->Html->script('assets/plugins/form-typeahead/typeahead.bundle.min');
        echo $this->Html->script('assets/plugins/form-select2/select2.min');
        echo $this->Html->script('assets/plugins/form-autosize/jquery.autosize-min');
        echo $this->Html->script('assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min');
        echo $this->Html->script('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin');
        echo $this->Html->script('assets/plugins/form-fseditor/jquery.fseditor-min');
        echo $this->Html->script('assets/plugins/form-jasnyupload/fileinput.min');
        echo $this->Html->script('assets/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min');
        echo $this->Html->script('assets/plugins/jquery-chained/jquery.chained.min');
        echo $this->Html->script('assets/plugins/jquery-mousewheel/jquery.mousewheel.min');
        echo $this->Html->script('assets/plugins/card/lib/js/card');
        echo $this->Html->script('assets/plugins/bootbox/bootbox');
        
        echo $this->Html->script('function');
        echo $this->Html->script('process_integration');
        echo $this->Html->script('login_creation');
        echo $this->Html->script('edit_login');
        echo $this->Html->script('obup');
        echo $this->Html->script('obsr');
        echo $this->Html->script('obfield');
        echo $this->Html->script('IVR');
        echo $this->Html->script('build/jquery.datetimepicker.full');
		
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
       // echo $this->Html->script('angular.min');
        echo $this->Html->script('capture');
        echo $this->Html->script('Escalation');
	echo $this->Html->script('training');
	echo $this->Html->script('listCollapse');
	echo $this->Html->script('getCategories');
        ?>
        
        <script>
          $(function() {
            $( "#tabs" ).tabs();
          });
        </script>
    <script>
  $(function() {
    $( "#tabs2" ).tabs();
  });
  </script>
<script>
  $(function() {
    $( "#tabs3" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs3 li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  });
  </script>
	<script type="text/javascript">
            window.onload = function () {
                compactMenu('someID',false,'&plusmn; ');
                compactMenu('someID',true,'&plusmn; ');
            }
	</script>
        
        <!-- Validate Plugin / Parsley -->
        <?php
        echo $this->Html->script('assets/plugins/form-parsley/parsley');
        echo $this->Html->script('assets/main/formvalidation');
        ?>
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
          
        function deleteData(url){
            bootbox.confirm("Are you sure you want to delete?", function(result){
                if(result ==true){
                    window.location.href =url;
                }
            });   
        }

        function submitForm(id,path)
        {
        //var formData = $(form).serialize(); 

        $.post(path,{ process_id: id }).done(function(data){

            //$("#"+removeid).trigger('click');
            location.reload(); 
            // $("#show-ecr-message").trigger('click');
            // $("#ecr-text-message").text('Data save successfully.');

            
        });
        return true;
        }
        
        </script>

        
        
        <!-- Paging Table Script -->
        <?php
        echo $this->Html->script('assets/plugins/datatables/jquery.dataTables');
        echo $this->Html->script('assets/plugins/datatables/TableTools');
        echo $this->Html->script('assets/plugins/jquery-editable/jquery.editable');
        echo $this->Html->script('assets/plugins/datatables/dataTables.editor');
        echo $this->Html->script('assets/plugins/datatables/dataTables.editor.bootstrap');
        echo $this->Html->script('assets/plugins/datatables/dataTables.bootstrap');
        echo $this->Html->script('assets/main/tableeditable');
        ?>
        
         <!-- Date picker script file-->
       
	   
	 <!--  
        <link rel="stylesheet" href="<?php echo $this->webroot;?>datepicker/jquery-ui.css">	
	<script src="<?php echo $this->webroot;?>datepicker/jquery-ui.js"></script>
        <script>
            $(function() {
                $( ".date-picker" ).datepicker();
            });
        </script>
        <link rel="stylesheet" href="<?php echo $this->webroot;?>date-picker/jquery-ui.css">
        -->
          <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
          <script>
          $( function() {
            $( ".date-picker" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,yearRange: '-100:+0' });
          });
          </script>
          
 
             
          
    </head>
	<body class="animated-content infobar-overlay sidebar-scroll">
        
        
<header id="topnav" class="navbar navbar-bleachedcedar navbar-fixed-top" role="banner">
	<!-- <div id="page-progress-loader" class="show"></div> -->

        <div class="logo-area">
		<a class="navbar-brand navbar-brand-default" href="<?php echo $this->webroot?>homes">
			<img class="show-on-collapse img-logo-white" alt="Paper" src="<?php echo $this->webroot?>css/assets/img/logo-icon-white.png">
			<img class="show-on-collapse img-logo-dark" alt="Paper" src="<?php echo $this->webroot?>css/assets/img/logo-icon-dark.png">
			<img class="img-white" alt="Paper" src="<?php echo $this->webroot?>css/assets/img/logo.png">
			<img class="img-dark" alt="ShortTermIncomeFund" src="<?php echo $this->webroot?>css/assets/img/logo.png">
		</a>

		<span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg stay-on-search">
			<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
				<span class="icon-bg">
					<i class="material-icons">menu</i>
				</span>
			</a>
		</span>
                <span id="trigger-fullscreen" class="toolbar-trigger toolbar-icon-bg ov-h">
			<a href="#" class="toggle-fullscreen"><span class="icon-bg">
	        	<i class="material-icons">fullscreen</i>
	        </span></a>
		</span>
                <!--
		<span id="trigger-search" class="toolbar-trigger toolbar-icon-bg ov-h">
			<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
				<span class="icon-bg">
					<i class="material-icons">search</i>
				</span>
			</a>
		</span>
		<div id="search-box">
			<input class="form-control" type="text" placeholder="Search..." id="search-input"></input>
		</div>
                -->
            
	</div>
        
        
        
        

	<ul class="nav navbar-nav toolbar pull-right">

		<li class="toolbar-icon-bg appear-on-search ov-h" id="trigger-search-close">
	        <a class="toggle-fullscreen"><span class="icon-bg">
	        	<i class="material-icons">close</i>
	        </span></a>
	    </li>
            <!--
		<li class="toolbar-icon-bg hidden-xs" id="trigger-fullscreen">
	        <a href="#" class="toggle-fullscreen"><span class="icon-bg">
	        	<i class="material-icons">fullscreen</i>
	        </span></a>
	    </li>
            -->
            
       
                
                <li class="dropdown toolbar-icon-bg">
			<a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="material-icons">notifications</i></span><span class="badge badge-info"><?php //echo $Noti_count   = $this->Session->read('Noti_count'); ?></span></a>
			<div class="dropdown-menu animated notifications">
				<div class="topnav-dropdown-header">
					<span>3 new notifications<?php $notification_data   = $this->Session->read('notification_data');?></span>
					
				</div>
				<div class="scroll-pane">
					<ul class="media-list scroll-content">
                        <?php// foreach($notification_data as $not_data)
                      //  {?>
						<li class="media notification-success">
							<a href="#">
								<div class="media-left">
									<!-- <span class="notification-icon"><i class="material-icons">lock</i></span> -->
                                    <!-- <span class="notification-time"><?php //echo date_format(date_create($not_data['ProcessUpdation']['date_time']),'d M Y'); ?></span> -->

								</div>
								<div class="media-body">
									<h5 class="notification-heading"><?php// echo $not_data['ProcessUpdation']['company_name']; ?></h5>
									<!-- <span class="notification-time">Process Update - <?php //echo date_format(date_create($not_data['ProcessUpdation']['process_date']),'d M Y'); ?></span> -->
                                    
								</div>
                                <div class="media-right">
                                    <!-- <span class="notification-badge" onclick="return submitForm('<?php //echo $not_data['ProcessUpdation']['id']; ?>','<?php //echo $this->webroot;?>ProcessUpdates/read_data')">Read</span> -->

								</div>
							</a>
						</li>
                        <?php// } ?>
						<li class="media notification-info">
							<a href="#">
								<div class="media-left">
									<span class="notification-icon"><i class="material-icons">shopping_cart</i></span>
								</div>
								<div class="media-body">
									<h4 class="notification-heading">A new order has been placed.</h4>
									<span class="notification-time">24 mins ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-teal">
							<a href="#">
								<div class="media-left">
									<span class="notification-icon"><i class="material-icons">perm_contact_calendar</i></span>
								</div>
								<div class="media-body">
									<h4 class="notification-heading">New event started!</h4>
									<span class="notification-time">16 hours ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-indigo">
							<a href="#">
								<div class="media-left">
									<span class="notification-icon"><i class="material-icons">settings</i></i></span>
								</div>
								<div class="media-body">
									<h4 class="notification-heading">New app settings updated.</h4>
									<span class="notification-time">2 days ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-danger">
							<a href="#">
								<div class="media-left">
									<span class="notification-icon"><i class="material-icons">comment</i></span>
								</div>
								<div class="media-body">
									<h4 class="notification-heading">Jessi commented your post.</h4>
									<span class="notification-time">4 days ago</span>
								</div>
							</a>
						</li>
					</ul>
				</div>
				<div class="topnav-dropdown-footer">
					<a href="#">See all notifications</a>
				</div>
			</div>
		</li>
            
        <li class="dropdown toolbar-icon-bg">
                    <a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'>
                        <span class="icon-bg">
                            <?php if($this->Session->read('companylogo') !=""){?>
                            <img class="img-responsive img-circle notification" src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $this->Session->read('companyid')."/".$this->Session->read('companylogo');?>"style=" margin-left:-5px;width: 100%;height:30px;">
                            <?php }else{?>
                           <!--
                            <img class="img-responsive img-circle notification" src="<?php echo $this->webroot;?>images/avatar_08.png"style="width:100%;margin-top: -8px;">
                            -->
                            <i  class="material-icons"  style="font-size:46px;margin-left:-6px;margin-top:-2px;">account_circle</i>
   
   
                            <?php }?>
                        </span>
                        <span class="badge badge-info"></span>
                    </a>
                    <div class="dropdown-menu animated notifications user-account" >
                        
                            <div class="topnav-dropdown-header">
                                 <?php if($this->Session->read('role') =="admin"){?>
                                <span>Dialdesk</span> 
                                <?php }else{?>
                                 <span><?php echo $this->Session->read('companyname');?></span> 
                                <?php }?>
                            </div>
                         
						                
                        <div class="scroll-pane">
                            <ul class="media-list scroll-content"> 			
                                <?php if($this->Session->read('role') =="client"){?>
                                <li class="media notification-success">
                                    <a href="<?php echo $this->webroot?>MyAccounts">
                                        <div class="media-left">
                                             <i class="material-icons fa fa-user"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="notification-heading">My Account</h4>  
                                        </div>
                                    </a>
                                    
                                    <a href="<?php echo $this->webroot?>MyWallets">
                                        <div class="media-left">
                                             <i class="material-icons">account_balance_wallet</i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="notification-heading">My Wallet</h4>  
                                        </div> 
                                    </a>
                                    
                                </li>
                                <?php }else if($this->Session->read('role') =="agent"){?>					
                                <li class="media notification-success">
                                    <a href="#">
                                        <div class="media-left">
                                             <i class="material-icons fa fa-user"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="notification-heading"><?php echo $this->Session->read('agent_name');?></h4>                       
                                        </div>
                                    </a>
                                </li> 
				<?php }?>
                                
                                <?php if($this->Session->read('role') =="admin"){?>					
                                <li class="media notification-success">
                                    <a href="#">
                                        <div class="media-left">
                                             <i class="material-icons fa fa-user"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="notification-heading"><?php echo $this->Session->read('admin_name');?></h4>                       
                                        </div>
                                    </a>
                                </li> 
				<?php }?>
             
                                <li class="media notification-success">
                                    <?php if($this->Session->read('role') =="admin"){?>
                                        <a href="<?php echo $this->webroot?>Admins/logout">
                                    <?php }else{?>
                                        <a href="<?php echo $this->webroot?>ClientActivations/logout">
                                    <?php }?>
                                        <div class="media-left">
                                            <i class="material-icons fa fa-power-off"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="notification-heading">Logout</h4>                       
                                        </div>
                                    </a>
                                </li> 				
                            </ul>
                        </div>
                       
                    </div>
                </li>
           

        
                <!--
		<li class="toolbar-icon-bg" id="trigger-infobar">
			<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
				<span class="icon-bg">
					<i class="material-icons">more_vert</i>
				</span>
			</a>
		</li>
                -->
		
	</ul>

</header>
            
   

<div id="wrapper">

            <div id="layout-static">
  
<div class="static-content-wrapper">
    <div class="static-content">
        <div class="page-content">
            <?php echo $this->fetch('content'); ?>  
           
        </div> <!-- #page-content -->
    </div>
    <?php if($this->Session->read('companyid') != '') { ?>
    <footer role="contentinfo">
        <div class="clearfix">
            <ul class="list-unstyled list-inline pull-left">
                <li><h6>&copy; 2016 DialDesk</h6></li>
                
            </ul>
        </div>
    </footer>
    <?php }?>



        </div>
    </div>
</div>

<!--
<div class="infobar-wrapper scroll-pane">
    <div class="infobar scroll-content">
    
    <ul class="nav nav-tabs material-nav-tabs stretch-tabs icon-tabs">
        <li ><a href="#tab-7-1" data-toggle="tab">
                Your Profile Completion
            </a>
        </li>   
    </ul>
    
<div class="tab-content">
    <div class="tab-pane active" id="tab-7-1">
        <table class="table table-settings ">
                <tbdody>
                    <tr>
                        <td>
                            <h5>Profile Completion</h5>
                            <p>Sets alerts to get notified when changes occur to get new alerming items</p>
                            <div class="progress">    
                                    <div style="width: 20%" class="progress-bar progress-bar-primary"></div>
                                  </div>
                        </td>
                        <td><span class="text-success"><i class="material-icons">check</i></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Bank Account</h5>
                            <p>You have not added your bank Details</p>
                        </td>
                        <td><span class="text-danger"><i class="material-icons">report_problem</i></span></td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Bank Verified</h5>
                            <p>You bank account has not been verified yet</p>                            
                        </td>
                        <td>
                            <span class="text-danger"><i class="material-icons">report_problem</i></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Warnings</h5>
                            <p>You will get warnning only some specific setttings or alert system</p>
                        </td>
                        <td>
                            <span class="togglebutton toggle-warning"><label><input type="checkbox" checked=""> </label></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Sidebar</h5>
                            <p>You can hide/show use with sidebar collapsw settings</p>
                        </td>
                        <td><span class="togglebutton toggle-success"><label><input type="checkbox" checked=""> </label></span></td>
                    </tr>
                </tbdody>
            </table>

        </div>
        <div class="tab-pane active" id="tab-7-2">

            <div class="widget">
                <div class="widget-heading">Recent Activities</div>
                <div class="widget-body">
                    <ul class="timeline">
                        <li class="timeline-purple">
                            <div class="timeline-icon"><i class="material-icons">add</i></div>
                            <div class="timeline-body">
                                <div class="timeline-header">
                                    <span class="author">Jana Pena is now Follwing you</span>
                                    <span class="date">2 min ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-primary">
                            <div class="timeline-icon"><i class="material-icons">textsms</i></div>
                            <div class="timeline-body">
                                <div class="timeline-header">
                                    <span class="author">Percy liaye Like your picture</span>
                                    <span class="date">6 min ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-green">
                            <div class="timeline-icon"><i class="material-icons">done</i></div>
                            <div class="timeline-body">
                                <div class="timeline-header">
                                    <span class="author">Leon miles make your presentation for new project</span>
                                    <span class="date">2 hours ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-danger">
                            <div class="timeline-icon"><i class="material-icons">favorite</i></div>
                            <div class="timeline-body">
                                <div class="timeline-header">
                                    <span class="author">Lake wile like your comment</span>
                                    <span class="date">5 hours ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-sky">
                            <div class="timeline-icon"><i class="material-icons">attach_money</i></div>
                            <div class="timeline-body">
                                <div class="timeline-header">
                                    <span class="author">The Mountain Ambience paid your payment</span>
                                    <span class="date">9 hours ago</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="widget">
                <div class="widget-heading">Contacts</div>
                <div class="widget-body">
                    <ul class="media-list contacts">
                        <li class="media notification-message">
                            <div class="media-left">
                                <img class="img-circle avatar" src="assets/demo/avatar/avatar_01.png" alt="" />
                            </div>
                            <div class="media-body">
                              <span class="text-gray">Jon Owens</span>
                                <span class="contact-status text-success">Online</span>
                            </div>
                        </li>
                        <li class="media notification-message">
                            <div class="media-left">
                                <img class="img-circle avatar" src="assets/demo/avatar/avatar_02.png" alt="" />
                            </div>
                            <div class="media-body">
                                <span class="text-gray">Nina Huges</span>
                                <span class="contact-status text-muted">Offline</span>
                            </div>
                        </li>
                        <li class="media notification-message">
                            <div class="media-left">
                                <img class="img-circle avatar" src="assets/demo/avatar/avatar_03.png" alt="" />
                            </div>
                            <div class="media-body">
                                <span class="text-gray">Austin Lee</span>
                                <span class="contact-status text-danger">Busy</span>
                            </div>
                        </li>
                        <li class="media notification-message">
                            <div class="media-left">
                                <img class="img-circle avatar" src="assets/demo/avatar/avatar_04.png" alt="" />
                            </div>
                            <div class="media-body">
                                <span class="text-gray">Grady Hines</span>
                                <span class="contact-status text-warning">Away</span>
                            </div>
                        </li>
                        <li class="media notification-message">
                            <div class="media-left">
                                <img class="img-circle avatar" src="assets/demo/avatar/avatar_06.png" alt="" />
                            </div>
                            <div class="media-body">
                                <span class="text-gray">Adrian Barton</span>
                                <span class="contact-status text-success">Online</span>
                            </div>
                        </li>
                    </ul>                                
                </div>
            </div>


            </div>
        </div>

    </div>

</div>
-->

    </body>
</html>
