<?php
$cakeDescription = __d('cake_dev', 'DialDesk Agent Calling');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>:
	<?php echo $this->fetch('title'); ?>
    </title>
    <link rel="shortcut icon" href="<?php echo $this->webroot; ?>css/assets/img/logo-icon-dark.png">
    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    
    //echo $this->Html->css('dropdown-menu');
   // echo $this->Html->css('mystyle');
    echo $this->Html->css("jquery-ui"); 
    echo $this->Html->css("jquery-ui-bkp");
    echo $this->Html->script("jquery-1.10.2"); 
    echo $this->Html->script("jquery-ui");
    echo $this->Html->script('dateback');
    echo $this->Html->script('jsfunction');
    echo $this->Html->script('getCategories');
    ?>
    <script type="text/javascript">
        window.onload = function () {
        compactMenu('someID',false,'&plusmn; ');
        compactMenu('someID',true,'&plusmn; ');
        }

        $(function() {
        $( "#tabs" ).tabs();
        });
        $(function() {
        $( "#tabs2" ).tabs();
        });
        $(function() {
        $( "#tabs3" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
        $( "#tabs3 li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
        });
    </script>
    
    <link rel="shortcut icon" href="<?php echo $this->webroot; ?>assets/img/logo-icon-dark.png">
    <link href="<?php echo $this->webroot; ?>assets/css/styles.css" type="text/css" rel="stylesheet"> 
    <link href="<?php echo $this->webroot; ?>assets/css/mystyles.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $this->webroot; ?>assets/material-design-iconic-font/css/material-icon.css" rel="stylesheet">
    <link href="<?php echo $this->webroot; ?>assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">                    
    <link href="<?php echo $this->webroot; ?>assets/plugins/codeprettifier/prettify.css" type="text/css" rel="stylesheet">                
    <link href="<?php echo $this->webroot; ?>assets/plugins/dropdown.js/jquery.dropdown.css" type="text/css" rel="stylesheet">           
    <link href="<?php echo $this->webroot; ?>assets/plugins/progress-skylo/skylo.css" type="text/css" rel="stylesheet">                   
    <link href="<?php echo $this->webroot; ?>assets/plugins/form-daterangepicker/daterangepicker-bs3.css" type="text/css" rel="stylesheet">    
    <link href="<?php echo $this->webroot; ?>assets/plugins/fullcalendar/fullcalendar.css" type="text/css" rel="stylesheet">                   
    <link href="<?php echo $this->webroot; ?>assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $this->webroot; ?>assets/less/card.less" type="text/css" rel="stylesheet"> 
    <link href="<?php echo $this->webroot; ?>assets/plugins/chartist/dist/chartist.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $this->webroot;?>assets/plugins/datatables/dataTables.bootstrap.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $this->webroot;?>assets/plugins/datatables/dataTables.themify.css" type="text/css" rel="stylesheet">


    <!-- Date picker script file-->
    <link rel="stylesheet" href="<?php echo $this->webroot;?>assets/datepicker/jquery-ui.css">
    <script src="<?php echo $this->webroot;?>assets/datepicker/jquery-1.10.2.js"></script>
    <script src="<?php echo $this->webroot;?>assets/datepicker/jquery-ui.js"></script>
    <script>
        $(function() {
        $( ".date-picker" ).datepicker();
        });
    </script>
        
    <script src="<?php echo $this->webroot; ?>assets/js/jquery-1.10.2.min.js"></script>						
    <script src="<?php echo $this->webroot; ?>assets/js/jqueryui-1.10.3.min.js"></script>						
    <script src="<?php echo $this->webroot; ?>assets/js/bootstrap.min.js"></script> 								
    <script src="<?php echo $this->webroot; ?>assets/js/enquire.min.js"></script>
    <script src="<?php echo $this->webroot; ?>assets/js/application.js"></script> 	

    <script src="<?php echo $this->webroot; ?>assets/plugins/velocityjs/velocity.min.js"></script>					
    <script src="<?php echo $this->webroot; ?>assets/plugins/velocityjs/velocity.ui.min.js"></script>
    <script src="<?php echo $this->webroot; ?>assets/plugins/progress-skylo/skylo.js"></script> 					
    <script src="<?php echo $this->webroot; ?>assets/plugins/wijets/wijets.js"></script>     						
    <script src="<?php echo $this->webroot; ?>assets/plugins/sparklines/jquery.sparklines.min.js"></script> 			 
    <script src="<?php echo $this->webroot; ?>assets/plugins/codeprettifier/prettify.js"></script> 				
    <script src="<?php echo $this->webroot; ?>assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>  
    <script src="<?php echo $this->webroot; ?>assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script> 
    <script src="<?php echo $this->webroot; ?>assets/plugins/dropdown.js/jquery.dropdown.js"></script> 
    <script src="<?php echo $this->webroot; ?>assets/plugins/bootstrap-material-design/js/material.min.js"></script> 
    <script src="<?php echo $this->webroot; ?>assets/plugins/bootstrap-material-design/js/ripples.min.js"></script> 
    <!-- Charts -->
    <script src="<?php echo $this->webroot; ?>assets/plugins/charts-flot/jquery.flot.min.js"></script>                
    <script src="<?php echo $this->webroot; ?>assets/plugins/charts-flot/jquery.flot.pie.min.js"></script>             
    <script src="<?php echo $this->webroot; ?>assets/plugins/charts-flot/jquery.flot.stack.min.js"></script>          
    <script src="<?php echo $this->webroot; ?>assets/plugins/charts-flot/jquery.flot.resize.min.js"></script>         
    <script src="<?php echo $this->webroot; ?>assets/plugins/charts-flot/jquery.flot.tooltip.min.js"></script>        
    <script src="<?php echo $this->webroot; ?>assets/plugins/charts-flot/jquery.flot.spline.js"></script>             
    <script src="<?php echo $this->webroot; ?>assets/plugins/easypiechart/jquery.easypiechart.min.js"></script>       
    <script src="<?php echo $this->webroot; ?>assets/plugins/curvedLines-master/curvedLines.js"></script>             
    <script src="<?php echo $this->webroot; ?>assets/plugins/form-daterangepicker/moment.min.js"></script>             
    <!-- Date Range Picker -->
    <script src="<?php echo $this->webroot; ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>              
    <script src="<?php echo $this->webroot; ?>assets/plugins/chartist/dist/chartist.min.js"></script> 
    <script src="<?php echo $this->webroot; ?>assets/main/main-index.js"></script>                                     
    <!-- Validate Plugin / Parsley -->
    <script src="<?php echo $this->webroot; ?>assets/plugins/form-parsley/parsley.js"></script> 					
    <script src="<?php echo $this->webroot; ?>assets/main/formvalidation.js"></script>
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
	
    <!-- Paging Table Script -->
    <script src="<?php echo $this->webroot;?>assets/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo $this->webroot;?>assets/plugins/datatables/TableTools.js"></script>
    <script src="<?php echo $this->webroot;?>assets/plugins/jquery-editable/jquery.editable.js"></script>
    <script src="<?php echo $this->webroot;?>assets/plugins/datatables/dataTables.editor.js"></script>
    <script src="<?php echo $this->webroot;?>assets/plugins/datatables/dataTables.editor.bootstrap.js"></script>
    <script src="<?php echo $this->webroot;?>assets/plugins/datatables/dataTables.bootstrap.js"></script>
    <script src="<?php echo $this->webroot;?>assets/main/tableeditable.js"></script>
    
</head>
<body class="animated-content infobar-overlay">  
    <header id="topnav" class="navbar navbar-bleachedcedar navbar-fixed-top" role="banner">
        <div class="logo-area">
            <a class="navbar-brand navbar-brand-default" href="<?php echo $this->webroot?>Agents">
                <img class="show-on-collapse img-logo-white" alt="Paper" src="<?php echo $this->webroot; ?>assets/img/logo-icon-white.png">
                <img class="show-on-collapse img-logo-dark" alt="Paper" src="<?php echo $this->webroot; ?>assets/img/logo-icon-dark.png">
                <img class="img-white" alt="Paper" src="<?php echo $this->webroot; ?>assets/img/logo.png">
                <img class="img-dark" alt="ShortTermIncomeFund" src="<?php echo $this->webroot; ?>assets/img/logo.png">
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
                <input class="form-control" type="text" placeholder="Search..." id="search-input"></input>
            </div>
        </div><!-- logo-area -->

        <ul class="nav navbar-nav toolbar pull-right">
            <li class="toolbar-icon-bg appear-on-search ov-h" id="trigger-search-close">
                <a class="toggle-fullscreen"><span class="icon-bg">
                    <i class="material-icons">close</i>
                </span></i></a>
            </li>
            <li class="toolbar-icon-bg hidden-xs" id="trigger-fullscreen">
                <a href="#" class="toggle-fullscreen"><span class="icon-bg">
                    <i class="material-icons">fullscreen</i>
                </span></i></a>
            </li>
          
            <li class="dropdown toolbar-icon-bg">
                <a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="material-icons">settings</i></span><span class="badge badge-info"></span></a>
                <div class="dropdown-menu animated notifications">
                    <div class="topnav-dropdown-header">
                        <span>Account Setting</span>
                    </div>
                    <div class="scroll-pane">
                        <ul class="media-list scroll-content"> 
                            <?php if($this->Session->read('agent_company')){?>
                            <li class="media notification-success">
                                <a href="#">
                                    <div class="media-left">
                                         <i class="material-icons fa fa-user"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="notification-heading"><?php echo $this->Session->read('agent_company');?></h4>                       
                                    </div>
                                </a>
                            </li> 
                            <li class="media notification-success">
                                <a href="<?php echo $this->webroot?>ClientActivations/logout">
                                    <div class="media-left">
                                        <i class="material-icons fa fa-power-off"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="notification-heading">Logout</h4>                       
                                    </div>
                                </a>
                            </li> 
                            <?php }?>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer">
                        <a href="#"></a>
                    </div>
                </div>
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
                                    <li><a  class="withripple" href="<?php echo $this->webroot;?>Agents"><span class="icon"><i class="material-icons">home</i></span><span>Dashboard</span><span class="badge badge-teal"></span></a></li>
									<?php $ur=explode(',',$this->Session->read('agent_rights'));?>
									
									<?php if (in_array("1", $ur)){?>
									<li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-sitemap"></i></span><span>Process Integration</span></a>
										<ul class="acc-menu">
										   <li><?php echo $this->Html->link('IVR Creation',array('controller'=>'Ivrs','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
										</ul>
									</li>
									<?php } else if (in_array("2", $ur)){?>
									<li><a  class="withripple" href="javascript:;"><span class="icon"><i class="fa fa-sitemap"></i></span><span>ECR Creation</span></a>
										<ul class="acc-menu">
										   <li><?php echo $this->Html->link('ECR',array('controller'=>'Ecrs','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
										   <li><?php echo $this->Html->link('View ECR',array('controller'=>'Ecrs','action'=>'view','full_base' => true),array('class'=>"withripple"));?></li>
										   <li><?php echo $this->Html->link('Edit ECR',array('controller'=>'EcrEdits','action'=>'index','full_base' => true),array('class'=>"withripple"));?></li>
										</ul>
									</li>
									<?php } //else if (in_array("3", $ur)){?>
								
								
								</ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="static-content-wrapper">
                <div class="static-content">
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->fetch('content'); ?>
					
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
</body>
</html>
