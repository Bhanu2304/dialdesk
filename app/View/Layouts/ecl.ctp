<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <?php
		echo $this->fetch('description');
		echo $this->fetch('author');
                echo $this->Html->meta('icon');
		echo $this->Html->css('parentChild/style');
		//echo $this->Html->css('mystyle');
		//echo $this->Html->css('jquery-ui');		
		echo $this->Html->script('parentChild/jquery-1.11.1.min');
		echo $this->Html->script('parentChild/jquery-migrate-1.2.1.min');
		echo $this->Html->script('parentChild/jquery-ui');
		echo $this->Html->script('parentChild/jquery.tree');
                echo $this->fetch('meta');
                echo $this->fetch('css');
                echo $this->Html->css('assets/material-design-iconic-font/css/material-icon');
		echo $this->Html->css('assets/fonts/font-awesome/css/font-awesome.min');
                echo $this->Html->css('assets/css/styles');
                echo $this->Html->css('assets/plugins/codeprettifier/prettify');
                echo $this->Html->css('assets/plugins/dropdown.js/jquery.dropdown');
                echo $this->Html->css('assets/plugins/progress-skylo/skylo');
                echo $this->Html->css('assets/plugins/form-select2/select2');
                echo $this->Html->css('assets/plugins/form-fseditor/fseditor');
                echo $this->Html->css('assets/plugins/bootstrap-tokenfield/css/bootstrap-tokenfield');
                echo $this->Html->css('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min');
                echo $this->Html->css('assets/plugins/card/lib/css/card');
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
                echo $this->Html->script('assets/plugins/bootstrap-material-design/js/ripples.min');
                echo $this->Html->script('assets/js/application');
                echo $this->Html->script('assets/demo/demo');
                echo $this->Html->script('assets/demo/demo-switcher');
                
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
               //echo $this->Html->script('assets/main/formcomponents');
                echo $this->Html->script('assets/plugins/card/lib/js/card');
		        ?>
        <style>
			#logout{
				color:#FFF;
				padding-top:10px;
				float:right;
				margin-right:20px;
			}
			#logout a{
				text-decoration:none;
				color:#FFF;
			}
        </style>
        <script>
            $(document).ready(function() {
                $('.tree').tree_structure({
                    'add_option': true,
                    'edit_option': true,
                    'delete_option': true,
                    'confirm_before_delete': true,
                    'animate_option': false,
                    'fullwidth_option': false,
                    'align_option': 'center',
                    'draggable_option': true
                });
            });
        </script>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin:400,700,600"/>	
    </head>
	<body class="animated-content infobar-overlay">
        
        
<header id="topnav" class="navbar navbar-bleachedcedar navbar-fixed-top" role="banner">
	<!-- <div id="page-progress-loader" class="show"></div> -->

	<div class="logo-area">
		<a class="navbar-brand navbar-brand-default" href="index.html">
			<?php
                         echo $this->Html->image('logo-icon-white.png', array('alt' => "Paper",'class' => 'show-on-collapse img-logo-white'));
                         echo $this->Html->image('logo-icon-dark.png', array('alt' => "Paper",'class' => 'show-on-collapse img-logo-white'));
                         echo $this->Html->image('logo.png', array('alt' => "Paper",'class' => 'img-white'));
                         echo $this->Html->image('logo.png', array('alt' => "ShortTermIncomeFund",'class' => 'img-dark'));
                        ?>
		</a>

		<span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg stay-on-search">
			<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
				<span class="icon-bg">
					<i class="material-icons">menu</i>
				</span>
			</a>
		</span>
		<span id="trigger-search" class="toolbar-trigger toolbar-icon-bg ov-h">
			<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
				<span class="icon-bg">
					<i class="material-icons">search</i>
				</span>
			</a>
		</span>
		<div id="search-box">
			<input class="form-control" type="text" placeholder="Search..." id="search-input">
		</div>
	</div><!-- logo-area -->

	<ul class="nav navbar-nav toolbar pull-right">

		<li class="toolbar-icon-bg appear-on-search ov-h" id="trigger-search-close">
	        <a class="toggle-fullscreen"><span class="icon-bg">
	        	<i class="material-icons">close</i>
	        </span></a>
	    </li>
		<li class="toolbar-icon-bg hidden-xs" id="trigger-fullscreen">
	        <a href="#" class="toggle-fullscreen"><span class="icon-bg">
	        	<i class="material-icons">fullscreen</i>
	        </span></a>
	    </li>

   		<li class="dropdown toolbar-icon-bg">
			<a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="material-icons">notifications</i></span><span class="badge badge-info"></span></a>
			<div class="dropdown-menu animated notifications">
				<div class="topnav-dropdown-header">
					<span>3 new notifications</span>
					
				</div>
				<div class="scroll-pane">
					<ul class="media-list scroll-content">
						<li class="media notification-success">
							<a href="#">
								<div class="media-left">
									<span class="notification-icon"><i class="material-icons">lock</i></span>
								</div>
								<div class="media-body">
									<h4 class="notification-heading">Privacy settings have been changed.</h4>
									<span class="notification-time">8 mins ago</span>
								</div>
							</a>
						</li>
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
									<span class="notification-icon"><i class="material-icons">settings</i></span>
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

        <li class="dropdown toolbar-icon-bg hidden-xs">
			<a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="material-icons">mail</i></span><span
			class="badge badge-info"></span></a>
			<div class="dropdown-menu animated notifications">
				<div class="topnav-dropdown-header">
					<span>2 new messages</span>
					
				</div>
				<div class="scroll-pane">
					<ul class="media-list scroll-content">
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_01.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Amy Green</strong> <span class="text-gray">‒ Integer vitae libero ac risus egestas placerat.</span></h4>
									<span class="notification-time">2 mins ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_09.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Daniel Andrews</strong> <span class="text-gray">‒ Vestibulum commodo felis quis tortor</span></h4>
									<span class="notification-time">40 mins ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_02.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Jane Simpson</strong> <span class="text-gray">‒ Fusce lobortis lorem at ipsum semper sagittis.</span></h4>
									<span class="notification-time">6 hours ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_03.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Harold Hawkins</strong> <span class="text-gray">‒ Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</span></h4>
									<span class="notification-time">8 days ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_04.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Brian Fisher</strong> <span class="text-gray">‒ Praesent dapibus, neque id cursus faucibus.</span></h4>
									<span class="notification-time">16 hours ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_05.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Dylan Black</strong> <span class="text-gray">‒ Pellentesque fermentum dolor. </span></h4>
									<span class="notification-time">2 days ago</span>
								</div>
							</a>
						</li>
						<li class="media notification-message">
							<a href="#">
								<div class="media-left">
									<img class="img-circle avatar" src="assets/demo/avatar/avatar_06.png" alt="" />
								</div>
								<div class="media-body">
									<h4 class="notification-heading"><strong>Bobby Harper</strong> <span class="text-gray">‒ Sed adipiscing ornare risus. Morbi est est.</span></h4>
									<span class="notification-time">4 days ago</span>
								</div>
							</a>
						</li>
					</ul>
				</div>
				<div class="topnav-dropdown-footer">
					<a href="#">See all messages</a>
				</div>
			</div>
		</li>

		<li class="toolbar-icon-bg" id="trigger-infobar">
			<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
				<span class="icon-bg">
					<i class="material-icons">more_vert</i>
				</span>
			</a>
		</li>
		
	</ul>

</header>

<div id="wrapper">
            <div id="layout-static">
                <div class="static-sidebar-wrapper sidebar-bleachedcedar">
                    <div class="static-sidebar">
                        <div class="sidebar">
	
	<div class="widget stay-on-collapse" id="widget-sidebar">
        <nav role="navigation" class="widget-body">
	<ul class="acc-menu">
		<li class="nav-separator"><span>Navigation</span></li>
		<li><a  class="withripple" href="index.html"><span class="icon">
		<i class="material-icons">home</i></span><span>Dashboard</span><span class="badge badge-teal">2</span></a></li>
		<li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-user"></i></span><span>Client Activation</span></a>
			<ul class="acc-menu">
                         <li><?php echo $this->Html->link('Process Integration',array('controller'=>'ProcessIntegrations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('View Process',array('controller'=>'ProcessIntegrations','action'=>'view_process','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('Add Login',array('controller'=>'LoginCreations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('View Login',array('controller'=>'LoginCreations','action'=>'view_created_login','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('Add Report Matrix',array('controller'=>'MisAndReportMatrixs','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('View Report Matrix',array('controller'=>'MisAndReportMatrixs','action'=>'view_report_matrix','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('Add Close Looping',array('controller'=>'CloseLoopings','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                         <li><?php echo $this->Html->link('View Close Looping',array('controller'=>'CloseLoopings','action'=>'view_close_loop','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>

		<li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-sitemap"></i></span><span>ECR</span></a>
			<ul class="acc-menu">
                           <li><?php echo $this->Html->link('ECR',array('controller'=>'Ecrs','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                           <li><?php echo $this->Html->link('View ECR',array('controller'=>'Ecrs','action'=>'view','full_base' => true),array('class'=>"withripple"));?></li>
                           <li><?php echo $this->Html->link('Edit ECR',array('controller'=>'EcrEdits','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>
                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-pencil-square-o"></i></span><span>Capture Fields</span></a>
			<ul class="acc-menu">
                           <li><?php echo $this->Html->link('Capture Fields',array('controller'=>'ClientFields','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                           <li><?php echo $this->Html->link('View Capture Fields',array('controller'=>'ClientFields','action'=>'view','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>
                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-graduation-cap"></i></span><span>Training</span></a>
			<ul class="acc-menu">
                           <li><?php echo $this->Html->link('Training',array('controller'=>'TrainingMasters','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                           <li><?php echo $this->Html->link('View Training',array('controller'=>'TrainingMasters','action'=>'view','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>
                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-exclamation-circle"></i></span><span>Escalation</span></a>
			<ul class="acc-menu">
                           <li><?php echo $this->Html->link('Escalation',array('controller'=>'Escalations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                           <li><?php echo $this->Html->link('View Escalation',array('controller'=>'Escalations','action'=>'view','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>
                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-sitemap"></i></span><span>IVR</span></a>
			<ul class="acc-menu">
                           <li><?php echo $this->Html->link('IVR',array('controller'=>'Ivrs','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>
                
                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-upload"></i></span><span>SR Details</span></a>
			<ul class="acc-menu">
                            <li><?php echo $this->Html->link('Upload',array('controller'=>'UploadExistingBases','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                            <li><?php echo $this->Html->link('Add SR Details',array('controller'=>'ClientSrCreations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                            <li><?php echo $this->Html->link('View SR Details',array('controller'=>'SrDetails','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>
                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-users"></i></span><span>Agent Creation</span></a>
			<ul class="acc-menu">
                            <li><?php echo $this->Html->link('Add Agent',array('controller'=>'AgentCreations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                           <li><?php echo $this->Html->link('View Agent',array('controller'=>'AgentCreations','action'=>'view_agent','full_base' => true),array('class'=>"withripple"));?></li>
			</ul>
		</li>

                <li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-phone"></i></span><span>Out Bound Campaign</span></a>
                    <ul class="acc-menu">
        		<li><?php echo $this->Html->link('Add Campaign',array('controller'=>'Outbounds','action'=>'add_campaign','full_base' => true),array('class'=>"withripple"));?></li>
    				<li><?php echo $this->Html->link('View Campaign',array('controller'=>'Outbounds','action'=>'view_campaign','full_base' => true),array('class'=>"withripple"));?></li>
                     		<li><?php echo $this->Html->link('Download Campaign',array('controller'=>'ObImports','action'=>'download_campaign','full_base' => true),array('class'=>"withripple"));?></li>
                      		<li><?php echo $this->Html->link('Import Campaign Data',array('controller'=>'ObImports','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                       		<li><?php echo $this->Html->link('Data Allocation',array('controller'=>'DataAllocations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                                <li><?php echo $this->Html->link('Purpose of campaign',array('controller'=>'Outbounds','action'=>'prcampaign','full_base' => true),array('class'=>"withripple"));?></li>
                                <li><?php echo $this->Html->link('OB Calling Field creation',array('controller'=>'ObCreations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                     		<li><?php echo $this->Html->link('View OB Field creation',array('controller'=>'ObCreations','action'=>'view','full_base' => true),array('class'=>"withripple"));?></li>
                     		<li><?php echo $this->Html->link('OB SR Outcome creation',array('controller'=>'ObSrCreations','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                     		<li><?php echo $this->Html->link('Inbound Export Reports',array('controller'=>'IbExportReports','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                     		<li><?php echo $this->Html->link('Outbound Export Reports',array('controller'=>'ObExportReports','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
                    </ul>
                </li>
		
            </ul>
        </nav>
    </div>
</div>
</div>
</div>
<div class="static-content-wrapper">
    <div class="static-content">
        <div class="page-content">
            <?php echo $this->fetch('content'); ?>                
        </div> <!-- #page-content -->
    </div>
<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;">&copy; 2016 DialDesk</h6></li>
        </ul>
    </div>
</footer>

        </div>
    </div>
</div>

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


    </body>
</html>
