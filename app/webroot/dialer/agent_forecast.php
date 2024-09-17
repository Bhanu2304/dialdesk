<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");
$clientID = $_GET['clientID'];

                if($clientID=='All'){
                    $max = mysql_query("SET GROUP_CONCAT_MAX_LEN=20000",$db);
                    $ClientInfo = mysql_query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM db_dialdesk.registration_master WHERE `status`='A' and is_dd_client='1'",$db); 
                } else{
                    $ClientInfo = mysql_query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM db_dialdesk.registration_master WHERE `status`='A' and is_dd_client='1' and company_id='$clientID'",$db); 
                } 
                $campaignId = mysql_fetch_array($ClientInfo); 
               $campaignId =   "campaign_id in(".$campaignId['campaign_id'].")"; 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />        
        <title> AbandonReports</title>
        <link rel="shortcut icon" href="/dialdesk/css/assets/img/logo-icon-dark.png">
        <link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/material-design-iconic-font/css/material-icon.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/fonts/font-awesome/css/font-awesome.min.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/css/styles.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/css/mystyles.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/codeprettifier/prettify.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/dropdown.js/jquery.dropdown.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/progress-skylo/skylo.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/form-select2/select2.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/form-fseditor/fseditor.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/bootstrap-tokenfield/css/bootstrap-tokenfield.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css"/><link rel="stylesheet" type="text/css" href="/dialdesk/css/assets/plugins/card/lib/css/card.css"/><script type="text/javascript" src="/dialdesk/js/assets/js/jquery-1.10.2.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/js/jqueryui-1.10.3.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/js/bootstrap.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/js/enquire.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/velocityjs/velocity.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/velocityjs/velocity.ui.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/progress-skylo/skylo.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/wijets/wijets.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/sparklines/jquery.sparklines.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/codeprettifier/prettify.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/dropdown.js/jquery.dropdown.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/bootstrap-material-design/js/material.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/js/application.js"></script><script type="text/javascript" src="/dialdesk/js/assets/demo/demo.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/quicksearch/jquery.quicksearch.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/form-typeahead/typeahead.bundle.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/form-select2/select2.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/form-autosize/jquery.autosize-min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/form-fseditor/jquery.fseditor-min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/form-jasnyupload/fileinput.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/jquery-chained/jquery.chained.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/jquery-mousewheel/jquery.mousewheel.min.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/card/lib/js/card.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/bootbox/bootbox.js"></script><script type="text/javascript" src="/dialdesk/js/function.js"></script><script type="text/javascript" src="/dialdesk/js/process_integration.js"></script><script type="text/javascript" src="/dialdesk/js/login_creation.js"></script><script type="text/javascript" src="/dialdesk/js/edit_login.js"></script><script type="text/javascript" src="/dialdesk/js/obup.js"></script><script type="text/javascript" src="/dialdesk/js/obsr.js"></script><script type="text/javascript" src="/dialdesk/js/obfield.js"></script><script type="text/javascript" src="/dialdesk/js/IVR.js"></script><script type="text/javascript" src="/dialdesk/js/build/jquery.datetimepicker.full.js"></script><script type="text/javascript" src="/dialdesk/js/capture.js"></script><script type="text/javascript" src="/dialdesk/js/Escalation.js"></script><script type="text/javascript" src="/dialdesk/js/training.js"></script><script type="text/javascript" src="/dialdesk/js/listCollapse.js"></script><script type="text/javascript" src="/dialdesk/js/getCategories.js"></script>        
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
        <script type="text/javascript" src="/dialdesk/js/assets/plugins/form-parsley/parsley.js"></script><script type="text/javascript" src="/dialdesk/js/assets/main/formvalidation.js"></script>        <script>
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
        <script type="text/javascript" src="/dialdesk/js/assets/plugins/datatables/jquery.dataTables.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/datatables/TableTools.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/jquery-editable/jquery.editable.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/datatables/dataTables.editor.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/datatables/dataTables.editor.bootstrap.js"></script><script type="text/javascript" src="/dialdesk/js/assets/plugins/datatables/dataTables.bootstrap.js"></script><script type="text/javascript" src="/dialdesk/js/assets/main/tableeditable.js"></script>        
         <!-- Date picker script file-->
       
       
     <!--  
        <link rel="stylesheet" href="/dialdesk/datepicker/jquery-ui.css">   
    <script src="/dialdesk/datepicker/jquery-ui.js"></script>
        <script>
            $(function() {
                $( ".date-picker" ).datepicker();
            });
        </script>
        <link rel="stylesheet" href="/dialdesk/date-picker/jquery-ui.css">
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
        <a class="navbar-brand navbar-brand-default" href="/dialdesk/homes">
            <img class="show-on-collapse img-logo-white" alt="Paper" src="/dialdesk/css/assets/img/logo-icon-white.png">
            <img class="show-on-collapse img-logo-dark" alt="Paper" src="/dialdesk/css/assets/img/logo-icon-dark.png">
            <img class="img-white" alt="Paper" src="/dialdesk/css/assets/img/logo.png">
            <img class="img-dark" alt="ShortTermIncomeFund" src="/dialdesk/css/assets/img/logo.png">
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
            <a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="material-icons">notifications</i></span><span class="badge badge-info"></span></a>
            <div class="dropdown-menu animated notifications">
                <div class="topnav-dropdown-header">
                    <span>3 new notifications</span>
                    
                </div>
                <div class="scroll-pane">
                    <ul class="media-list scroll-content">
                        <?php// foreach($notification_data as $not_data)
                      //  {?>
                        <li class="media notification-success">
                            <a href="#">
                                <div class="media-left">
                                    <!-- <span class="notification-icon"><i class="material-icons">lock</i></span> -->
                                    <!-- <span class="notification-time"></span> -->

                                </div>
                                <div class="media-body">
                                    <h5 class="notification-heading"><?php// echo $not_data['ProcessUpdation']['company_name']; ?></h5>
                                    <!-- <span class="notification-time">Process Update - </span> -->
                                    
                                </div>
                                <div class="media-right">
                                    <!-- <span class="notification-badge" onclick="return submitForm('','ProcessUpdates/read_data')">Read</span> -->

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
                                                       <!--
                            <img class="img-responsive img-circle notification" src="/dialdesk/images/avatar_08.png"style="width:100%;margin-top: -8px;">
                            -->
                            <i  class="material-icons"  style="font-size:46px;margin-left:-6px;margin-top:-2px;">account_circle</i>
   
   
                                                    </span>
                        <span class="badge badge-info"></span>
                    </a>
                    <div class="dropdown-menu animated notifications user-account" >
                        
                            <div class="topnav-dropdown-header">
                                                                 <span>Dialdesk</span> 
                                                            </div>
                         
                                        
                        <div class="scroll-pane">
                            <ul class="media-list scroll-content">          
                                                                
                                                    
                                <li class="media notification-success">
                                    <a href="#">
                                        <div class="media-left">
                                             <i class="material-icons fa fa-user"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="notification-heading">superadmin</h4>                       
                                        </div>
                                    </a>
                                </li> 
                             
                                <li class="media notification-success">
                                                                            <a href="/dialdesk/Admins/logout">
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
            
            <li class="nav-separator"><span></span></li>
            <li><a  class="withripple" href="/dialdesk/homes2"><span class="icon">
                    <i class="material-icons">dashboard</i></span><span>Dashboard New</span><span class="badge badge-teal"></span></a>
                </li>
           
            
                         
                


             <li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Exposure Monitoring</span> </a><ul class='acc-menu'><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Exposure</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/ExposureViews/index_ledger2'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Exposure View(New)</span> </a><li><a class='withripple' href='/dialdesk/InitialInvoices/index_ledger2'><span class='icon'></span> <span>View</span> </a><li><a class='withripple' href='/dialdesk/ExposureExports/index_ledger'><span class='icon'></span> <span>Export</span> </a></ul></li><li><a class='withripple' href='/dialdesk/InitialInvoices'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Invoice Creation</span> </a><li><a class='withripple' href='/dialdesk/InitialInvoices/view'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Invoice View & Export</span> </a><li><a class='withripple' href='/dialdesk/InitialInvoices/view_approve_bill'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Invoice View/Approve</span> </a><li><a class='withripple' href='/dialdesk/InitialInvoices/view_approved_bill'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Download Invoice</span> </a><li><a class='withripple' href='/dialdesk/Initial/view_sms_link'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>View SMS Details</span> </a><li><a class='withripple' href='/dialdesk/Initial/sms_link_transcation_detail'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Online Transaction Details</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Mobile Management</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/MobUsers'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Field Executive</span> </a><li><a class='withripple' href='/dialdesk/MobDatas'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Existing Data Upload</span> </a><li><a class='withripple' href='/dialdesk/MobDatas/view_unallocate'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Data Unallocate</span> </a><li><a class='withripple' href='/dialdesk/MobDatas/re_allocate'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Data Reallocate</span> </a><li><a class='withripple' href='/dialdesk/MobDatas/worked_data'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Update And Export</span> </a><li><a class='withripple' href='/dialdesk/MobDatas/executive_tracker'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Executive Tracker</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Bill Risk Mgt</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/BillingRiskExposures'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Risk Exposure (External)</span> </a><li><a class='withripple' href='/dialdesk/BillingRiskExposures/index1'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Risk Exposure (Internal)</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Admin Management</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/AdminUsers'><span class='icon'><i class="fa fa-user"></i></span> <span>Add/View Admin</span> </a><li><a class='withripple' href='/dialdesk/acces'><span class='icon'><i class="fa fa-user" aria-hidden="true"></i></span> <span>Manage Admin Rights</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>OBD Management</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/ObdManagements/addlist'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Add List</span> </a><li><a class='withripple' href='/dialdesk/ObdManagements/DataUpload'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Data Upload</span> </a><li><a class='withripple' href='/dialdesk/ObdManagements/report'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Report</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Company Approval</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/AdminDetails/addcampaignsubtype'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Campaign Subtype</span> </a><li><a class='withripple' href='/dialdesk/InboundGroups'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Manage Priority</span> </a><li><a class='withripple' href='/dialdesk/AdminDetails/addcampaignlistid'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add List Id</span> </a><li><a class='withripple' href='/dialdesk/AdminDetails/view_client'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Client Details</span> </a><li><a class='withripple' href='/dialdesk/AdminDetails/clientdid'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>DID Creation</span> </a><li><a class='withripple' href='/dialdesk/AdminDetails/addcampaign'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add Campaign</span> </a><li><a class='withripple' href='/dialdesk/AdminMedias'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add/View Social Medias</span> </a><li><a class='withripple' href='/dialdesk/AdminMedias/emailmap'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add/View Email Map</span> </a><li><a class='withripple' href='/dialdesk/AdminDetails/view_client_request'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Client Request</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">settings</i></span> <span>In Call Management</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/CustomizedReportCreations'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Customized Report</span> </a><li><a class='withripple' href='/dialdesk/IncallactionAlerts/view_fields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage In Call Actions Alert </span> </a><li><a class='withripple' href='/dialdesk/CloseFields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Close Fields</span> </a><li><a class='withripple' href='/dialdesk/Ivrs'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage IVR</span> </a><li><a class='withripple' href='/dialdesk/Ecrs'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage In Call Scenarios</span> </a><li><a class='withripple' href='/dialdesk/Ecrs/create_tat'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage TAT</span> </a><li><a class='withripple' href='/dialdesk/ClientFields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Required Fields</span> </a><li><a class='withripple' href='/dialdesk/Escalations/view_fields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Alerts & Escalations</span> </a><li><a class='withripple' href='/dialdesk/CloseLoopings'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage In Call Actions</span> </a><li><a class='withripple' href='/dialdesk/WorkFlows'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Work Flow</span> </a><li><a class='withripple' href='/dialdesk/LoginCreations'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage User Logins</span> </a><li><a class='withripple' href='/dialdesk/MisAndReportMatrixs'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage MIS & Reports</span> </a><li><a class='withripple' href='/dialdesk/TrainingMasters'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Training Docs</span> </a><li><a class='withripple' href='/dialdesk/UploadExistingBases'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Upload Existing Customers</span> </a><li><a class='withripple' href='/dialdesk/ClientTranFields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Transaction Manage Fields</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">settings_input_svideo</i></span> <span>Out Call Management</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/ObCustomizedReportCreations'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Customized Report</span> </a><li><a class='withripple' href='/dialdesk/ObReallocations'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Re Allocations</span> </a><li><a class='withripple' href='/dialdesk/ObEscalations/view_fields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Alerts & Escalations</span> </a><li><a class='withripple' href='/dialdesk/ObcloseLoopings'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Out Call Actions</span> </a><li><a class='withripple' href='/dialdesk/ObcloseFields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Out Close Fields</span> </a><li><a class='withripple' href='/dialdesk/ObclientFields'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Required Fields</span> </a><li><a class='withripple' href='/dialdesk/Obecrs'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Out Call Scenarios</span> </a><li><a class='withripple' href='/dialdesk/ObImports'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Allocations</span> </a><li><a class='withripple' href='/dialdesk/Outbounds/add_campaign'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Campaigns</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Agent Creation</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/AgentCreations/view_summary'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Agent Summary Report</span> </a><li><a class='withripple' href='/dialdesk/AgentCreations/view_agent'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>View Agent</span> </a><li><a class='withripple' href='/dialdesk/AgentCreations'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add Agent</span> </a><li><a class='withripple' href='/dialdesk/Roasters'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Roaster Upload</span> </a><li><a class='withripple' href='/dialdesk/Roasters/view'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Roaster View</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">call</i></span> <span>Agent Calling Allocation</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/AbandCallAllocations'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Client Rights Allocation</span> </a><li><a class='withripple' href='/dialdesk/AdmindataAllocations'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>PD Call Allocation</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">messages</i></span> <span>Bluedart Configuration</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/BluedartApis'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Upload Service Address</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Plan Master</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/AdminPlans/view_client_plan'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>View Plan</span> </a><li><a class='withripple' href='/dialdesk/AdminPlans/allocate_plan'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Allocate Plan</span> </a><li><a class='withripple' href='/dialdesk/AdminPlans/reallocate_plan'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Re-Allocate Plan</span> </a><li><a class='withripple' href='/dialdesk/AdminPlans'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Plan Creation</span> </a><li><a class='withripple' href='/dialdesk/AdminPlans/view_pending_plan'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Plan Pending For Approval</span> </a><li><a class='withripple' href='/dialdesk/AdminPlans/plan_for_approval'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Plan Approval</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Account Master</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/ClientAccounts'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>View Account</span> </a><li><a class='withripple' href='/dialdesk/ClientAccounts/add_start_date'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add Activation Date</span> </a><li><a class='withripple' href='/dialdesk/ClientWaivers'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add Balance</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-user"></i></span> <span>Waiver Master</span> </a><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">account_balance_wallet</i></span> <span>Billing Statement</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/BillingReports/get_stmt'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Statement New</span> </a><li><a class='withripple' href='/dialdesk/ClientRevenueReports'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Client Revenue Report</span> </a><li><a class='withripple' href='/dialdesk/Reports/combined_allreport'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Combined All Reports</span> </a><li><a class='withripple' href='/dialdesk/ClientRevenueReports/ledger_statement'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Client Ledger Report</span> </a><li><a class='withripple' href='/dialdesk/BillingReports'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Statement</span> </a><li><a class='withripple' href='/dialdesk/BillingReports/view_bill'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>View Invoice</span> </a><li><a class='withripple' href='/dialdesk/ClientBillReports'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Client Statement</span> </a><li><a class='withripple' href='/dialdesk/ClientBillReports/view_bill'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>View Invoice</span> </a><li><a class='withripple' href='/dialdesk/ClientBillReports/view_padunpad_payment'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>View Paid/Unpaid Payment</span> </a></ul></li><li><a class='withripple' href='/dialdesk/BillPayments/payment_approval'><span class='icon'><i class="fa fa-user"></i></span> <span>Bill Payment</span> </a><li><a class='withripple' href='javascript:;'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Report/Renewal Plan</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/Reports/agentwise'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Agent Wise Call Tagging</span> </a><li><a class='withripple' href='/dialdesk/BillSummarys/add'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Add Bill Summary Auto Mail</span> </a><li><a class='withripple' href='/dialdesk/BillSummarys'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>Renewal Plan/Bill Summary</span> </a><li><a class='withripple' href='/dialdesk/SLAReports/cdr'><span class='icon'><i class="fa fa-angle-right"></i></span> <span>SLA Report</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">phone_in_talk</i></span> <span>In Call Operations</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/closeloops'><span class='icon'><i class="material-icons">account_box</i></span> <span>Update Ticket Status</span> </a><li><a class='withripple' href='/dialdesk/ProcessUpdates/view'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Process Update</span> </a><li><a class='withripple' href='/dialdesk/ProcessUpdates/report'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Process Update Report</span> </a><li><a class='withripple' href='/dialdesk/ProcessUpdates'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Process Update</span> </a><li><a class='withripple' href='/dialdesk/SrDetails'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>In Call Details</span> </a><li><a class='withripple' href='/dialdesk/Agents'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Create Manual Call</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">phone_forwarded</i></span> <span>Out Call Operations</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/ObAttemptwiseReports'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Out Call Attempt Wise</span> </a><li><a class='withripple' href='/dialdesk/MobileUploads'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Upload OB Base</span> </a><li><a class='withripple' href='/dialdesk/MobileUploads/boundreport'><span class='icon'></span> <span>Outcalling Data</span> </a><li><a class='withripple' href='/dialdesk/DialObs'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Import Calling Data</span> </a><li><a class='withripple' href='/dialdesk/ObsrDetails'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Out Call Details</span> </a><li><a class='withripple' href='/dialdesk/ManualOutbounds'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Create Manual Call</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">file_download</i></span> <span>MIS & Reports</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/Slas'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>SLA Reports</span> </a><li><a class='withripple' href='/dialdesk/Slas/cdr'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>CDR Reports</span> </a><li><a class='withripple' href='/dialdesk/Slas/call'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Call Reports</span> </a><li><a class='withripple' href='/dialdesk/Slas/tagging'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Tagging Reports</span> </a><li><a class='withripple' href='/dialdesk/UploadMisFiles/download'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Export MIS File</span> </a><li><a class='withripple' href='/dialdesk/UploadMisFiles'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Upload MIS File</span> </a><li><a class='withripple' href='/dialdesk/BillingReports/billing_reports'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Closing Balance Report</span> </a><li><a class='withripple' href='/dialdesk/IvrReports'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Ivr Reports</span> </a><li><a class='withripple' href='/dialdesk/CorrectiveReport'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Corrective Report</span> </a><li><a class='withripple' href='/dialdesk/AbandonReports/cdr'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Analysis Reports</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">account_box</i></span> <span>User Management</span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/LoginCreations'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage User Logins</span> </a><li><a class='withripple' href='/dialdesk/UserManages'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage User Access</span> </a></ul></li><li><a class='withripple' href='javascript:;'><span class='icon'><i class="material-icons">perm_media</i></span> <span>Social Media </span> </a><ul class='acc-menu'><li><a class='withripple' href='/dialdesk/WatsappTexts'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Whatsapp Text</span> </a><li><a class='withripple' href='/dialdesk/WatsappTexts/view_watsapp'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>View Whatsapp Text</span> </a><li><a class='withripple' href='/dialdesk/WhatsappTemplates'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Message Broadcasting</span> </a><li><a class='withripple' href='/dialdesk/WhatsappTemplates/view'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Message Broadcasting Report</span> </a><li><a class='withripple' href='/dialdesk/HarvestChat'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Bot Data</span> </a><li><a class='withripple' href='/dialdesk/HarvestChat/ticket'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Bot Data </span> </a><li><a class='withripple' href='/dialdesk/HarvestChat/report'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Bot Data Report</span> </a><li><a class='withripple' href='/dialdesk/TicketMaster'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Manage Tickets</span> </a><li><a class='withripple' href='/dialdesk/TicketMaster/report'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Ticket Report</span> </a><li><a class='withripple' href='/dialdesk/TicketMaster/view_non_regnumber'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Non Register Numbers</span> </a><li><a class='withripple' href='/dialdesk/SocialMedia'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Facebook Interactions</span> </a><li><a class='withripple' href='/dialdesk/Emails'><span class='icon'><i class="fa fa-angle-right" aria-hidden="true"></i></span> <span>Email Interactions</span> </a></ul></li>            
            
            
                         
                            
            </ul>
        </nav>
    </div>
</div>
</div>
</div>
<div class="static-content-wrapper">
    <div class="static-content">
        <div class="page-content">
            <script>        
function validateExport(url){
    $(".w_msg").remove();
    
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    
    if(fdate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','/dialdesk/AbandonReports/slot_wise_excel');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','/dialdesk/AbandonReports/ob_internal');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="/dialdesk/homes">Home</a></li>
    <li><a href="#">Agent Forecasting</a></li>
    <li class="active"><a href="#">Agent Forecasting</a></li>
</ol>
<div class="page-heading">            
    <h1>Agent Forecasting</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Agent Forecasting</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"></div>
                <form action="" id="validate-form" class="form-horizontal row-border" data-parsley-validate="data-parsley-validate" method="get" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="input select"><select name="clientID" id="client" required="required" class="form-control">

<option value="">Select Client</option>
<option value="All">All</option>
<option value="301">Akai India</option>
<option value="434">Altum technology</option>
<option value="403">Anand Novelties</option>
<option value="454">Ayurved Pratishthan Nasik Pvt Ltd</option>
<option value="343">Blue Heaven Cosmetics Private Limited</option>
<option value="452">Bmunk Hospitality Pvt Ltd</option>
<option value="201">DLF Estate Developers Limited</option>
<option value="333">FireFly Networks Ltd</option>
<option value="395">Fortum Charge &amp; Drive India Private Limited</option>
<option value="465">G Kumar Electro Pvt Ltd</option>
<option value="103">HIMGIRI ENTERPRISES PVT LTD</option>
<option value="410">International Academy of Sound Healing</option>
<option value="398">Jazz Inn Rooms</option>
<option value="479">JEANNYPR SOFTECH PRIVATE LIMITED</option>
<option value="421">LeadsArk</option>
<option value="389">LoanJunction</option>
<option value="426">M/S KW HOMES PRIVATE LIMITED</option>
<option value="482">MAANYA MOTORCYCLES PRIVATE LIMITED</option>
<option value="339">Nature Essence</option>
<option value="474">Omega seiki pvt ltd</option>
<option value="487">PARAMOUNT PROPBUILD PRIVATE LIMITED</option>
<option value="293">Ready Roti India Pvt Limited (Harvest Gold)</option>
<option value="458">Revosal Inc</option>
<option value="469">Ronerbiz</option>
<option value="241">Rx Infotech P Limited</option>
<option value="455">SASTHI ENTERPRISES PVT LTD</option>
<option value="476">Saumil Gosai &amp; Associates</option>
<option value="489">Shri Sai Entertainments Private Limited</option>
<option value="473">SRF LIMITED</option>
<option value="416">SUJAN INDUSTRIES</option>
<option value="491">TESTRIQ QA LAB LLP</option>
<option value="428">The Cotton Villa</option>
<option value="463">TRAVELPORT HOLIDAYS (INDIA) PRIVATE LIMITED</option>
<option value="492">TRAVELPORT HOLIDAYS_OB</option>
<option value="457">TUCHWARE TECHNOLOGIES PRIVATE LIMITED</option>
<option value="53">TV Tele Shopping</option>
<option value="488">TWENTY4 JEWELLERY INDUSTRIES PRIVATE LIMITED</option>
<option value="383">Upper Crust Foods Pvt Ltd.</option>
<option value="480">VAALVE BATHWARE INDIA LIMITED</option>
<option value="199">Valeo Motherson</option>
<option value="483">Vedika Wearable LLP</option>
<option value="493">VISIONARA GLOBAL</option>
<option value="439">WhiteTree Devices Private Limited</option>
<option value="348">Wiom</option>
</select></div>                        </div>
                        <div class="col-sm-2">
                            <div class="input text"><input name="query_date" placeholder="Start Date" id="fdate" required="required" class="form-control date-picker" autocomplete="off" type="text" /></div>                        </div>
                        <div class="col-sm-2">
                            <div class="input text"><input name="end_date" placeholder="End Date" id="ldate" required="required" class="form-control date-picker" autocomplete="off" type="text" /></div>                        </div>

                        <div class="col-sm-2">
                            <div class="input text"><input name="drop_percent" placeholder="Target drop rate" id="drop_percent" required="required" class="form-control" autocomplete="off" type="text"/></div>                        </div>    
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="hidden" name="group" value="--ALL--">
                            <input type="hidden" name="erlang_type" value="B">
                            <input type="hidden" name="report_display_rate" value="HTML">
                            
                            <input type="submit" name="SUBMIT" class="btn btn-web" value="SUBMIT" >
                        </div>
                        <!--<div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>-->
                        <!--
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        -->
                    </div>
                            </div>
        </div>
        
                <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Agent Forecasting</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                
<?php
$startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

    function factorial($num) {
        $num=floor($num);
        if ($num>0) {
            $result=1;
            for ($x=1; $x<=$num; $x++) {
                $result*=$x;
            }
            return $result;
        } else {
            return 1;
        }
    }
    function erlsum($low, $limit, $erlangs) {
        $result=0;
        for ($n=$low; $n<=$limit; $n++) {
            $result+=pow($erlangs, $n)/(factorial($n));
        }
        return $result;
    }
    function adjustedGoS($erlangs, $GoS, $retry_rate, $lines) {
        $E[0]=$erlangs;
        $P[0]=$GoS;

        for ($q=0; $q<100; $q++) {
            $E[($q+1)]=$erlangs+($retry_rate/100)*$E[$q]*$P[$q];
            $P[($q+1)]=MathZDC((pow($E[($q+1)], $lines)/(factorial($lines))), (erlsum(0, $lines, $E[($q+1)])));

        }
        return $P[100];
    }

if (isset($_GET["group"]))              {$group=$_GET["group"];}
    elseif (isset($_POST["group"]))     {$group=$_POST["group"];}
if (isset($_GET["campaign"]))               {$campaign=$_GET["campaign"];}
    elseif (isset($_POST["campaign"]))      {$campaign=$_POST["campaign"];}
if (isset($_GET["query_date"]))             {$query_date=$_GET["query_date"]." 00:00:00";}
    elseif (isset($_POST["query_date"]))    {$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))               {$end_date=$_GET["end_date"]." 23:59:59";}
    elseif (isset($_POST["end_date"]))      {$end_date=$_POST["end_date"];}
if (isset($_GET["drop_percent"]))               {$drop_percent=$_GET["drop_percent"];}
    elseif (isset($_POST["drop_percent"]))      {$drop_percent=$_POST["drop_percent"];}
if (isset($_GET["shift"]))              {$shift=$_GET["shift"];}
    elseif (isset($_POST["shift"]))     {$shift=$_POST["shift"];}
if (isset($_GET["erlang_type"]))                {$erlang_type=$_GET["erlang_type"];}
    elseif (isset($_POST["erlang_type"]))   {$erlang_type=$_POST["erlang_type"];}
if (isset($_GET["actual_agents"]))              {$actual_agents=$_GET["actual_agents"];}
    elseif (isset($_POST["actual_agents"])) {$actual_agents=$_POST["actual_agents"];}
if (isset($_GET["hourly_pay"]))             {$hourly_pay=$_GET["hourly_pay"];}
    elseif (isset($_POST["hourly_pay"]))    {$hourly_pay=$_POST["hourly_pay"];}
if (isset($_GET["revenue_per_sale"]))               {$revenue_per_sale=$_GET["revenue_per_sale"];}
    elseif (isset($_POST["revenue_per_sale"]))  {$revenue_per_sale=$_POST["revenue_per_sale"];}
if (isset($_GET["sale_chance"]))                {$sale_chance=$_GET["sale_chance"];}
    elseif (isset($_POST["sale_chance"]))   {$sale_chance=$_POST["sale_chance"];}
if (isset($_GET["retry_rate"]))             {$retry_rate=$_GET["retry_rate"];}
    elseif (isset($_POST["retry_rate"]))    {$retry_rate=$_POST["retry_rate"];}
if (isset($_GET["target_pqueue"]))          {$target_pqueue=$_GET["target_pqueue"];}
    elseif (isset($_POST["target_pqueue"])) {$target_pqueue=$_POST["target_pqueue"];}
if (isset($_GET["file_download"]))          {$file_download=$_GET["file_download"];}
    elseif (isset($_POST["file_download"])) {$file_download=$_POST["file_download"];}
if (isset($_GET["submit"]))             {$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))    {$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))             {$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))    {$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["DB"]))             {$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))    {$DB=$_POST["DB"];}
if (isset($_GET["report_display_type"]))                {$report_display_type=$_GET["report_display_type"];}
    elseif (isset($_POST["report_display_type"]))   {$report_display_type=$_POST["report_display_type"];}


$db_source = 'M';



$HTML_text="";
$report_display_type="HTML";


switch ($erlang_type) {
    default:
    case "B":
        $erlB="checked";
        $erlC="";
        break;
    case "C":
        $erlB="";
        $erlC="checked";
        break;
}

$JS_text="<script language='Javascript'>\n";
$JS_onload="onload = function() {\n";





$groups_selected=count($group);

if (!isset($group)) {$group = array();}
if (!isset($drop_percent)) {$drop_percent = '3';}
if (!isset($campaign)) {$campaign = array();}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}

$drop_percent=preg_replace("/[^\.0-9]/", "", $drop_percent);
if ($drop_percent>100) {$drop_percent=100;}
$drop_rate=$drop_percent/100;
$pqueue_target=$target_pqueue/100;

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
    {
    if (in_array("--ALL--", $group))
        {
        $group_string = "--ALL--";
        $group_SQL .= "'$campaign[$i]',";
        $groupQS = "&group[]=--ALL--";
        }
    if ( (strlen($group[$i]) > 0) and (preg_match("/\|$group[$i]\|/",$groups_string)) )
        {
        $group_string .= "$group[$i]|";
        $group_SQL .= "'$group[$i]',";
        $groupQS .= "&group[]=$group[$i]";
        }
    $i++;
    }
if ( (preg_match('/\s\-\-NONE\-\-\s/',$group_string) ) or ($group_ct < 1) )
    {
    $group_SQL = "''";
    }
else
    {
    $group_SQL = preg_replace('/,$/i', '',$group_SQL);
    }
if (strlen($group_SQL)<3) {$group_SQL="''";}

$i=0;
$campaign_string='|';
$campaign_ct = count($campaign);
while($i < $campaign_ct)
    {
    if (in_array("--ALL--", $campaign))
        {
        $campaign_string = "--ALL--";
        $campaign_SQL .= "'$campaign[$i]',";
        $campaignQS = "&campaign[]=--ALL--";
        }
    else if ( (strlen($campaign[$i]) > 0) and (preg_match("/\|$campaign[$i]\|/",$campaigns_string)) )
        {
        $campaign_string .= "$campaign[$i]|";
        $campaign_SQL .= "'$campaign[$i]',";
        $campaignQS .= "&campaign[]=$campaign[$i]";
        }
    $i++;
    }
if ( (preg_match('/\s\-\-NONE\-\-\s/',$campaign_string) ) or ($campaign_ct < 1) )
    {
    $campaign_SQL = "''";
    }
else
    {
    $campaign_SQL = preg_replace('/,$/i', '',$campaign_SQL);
    }
if (strlen($campaign_SQL)<3) {$campaign_SQL="''";}

$stmt="select call_time_id,call_time_name from vicidial_call_times $whereLOGadmin_viewable_call_timesSQL order by call_time_id;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$times_to_print = mysqli_num_rows($rslt);
$i=0;
while ($i < $times_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $call_times[$i] =       $row[0];
    $call_time_names[$i] =  $row[1];
    $i++;
    }

$HEADER.="<script src='chart/Chart.js'></script>\n"; 
$HEADER.="<script language=\"JavaScript\" src=\"vicidial_chart_functions.js\"></script>\n";


if ($groups_selected==0 && $campaigns_selected==0)
    {
    
    }
else
    {
    $campaign_group_stmt="select closer_campaigns from vicidial_campaigns where campaign_id in ($campaign_SQL)";
    if ($DB) {echo "|$campaign_group_stmt|\n";}
    $campaign_group_rslt=mysql_to_mysqli($campaign_group_stmt, $link);
    $campaign_group_SQL="";
    while ($cg_row=mysqli_fetch_row($campaign_group_rslt)) {
        if (strlen(trim($cg_row[0]))>0) {
            $cg_row[0]=preg_replace("/^\s+|\s\-/", "", $cg_row[0]);
            $campaign_group_SQL.="'".preg_replace("/\s/", "','", $cg_row[0])."',";
        }       
    }
    $campaign_group_SQL=$group_SQL.",".$campaign_group_SQL;
    $campaign_group_SQL=preg_replace("/^,|,$/", "", $campaign_group_SQL);

    $campaign_string=preg_replace("/^\||\|$/", "", $campaign_string);
    $group_string=preg_replace("/^\||\|$/", "", $group_string);
    $ASCII_text =" Date range :  $query_date to $end_date\n";
    $ASCII_text.=" Campaigns  :  ".preg_replace("/\|/", ", ", $campaign_string)."\n";
    $ASCII_text.=" In-groups  :  ".preg_replace("/\|/", ", ", $group_string)."\n\n";
#   $ASCII_text.=" Report type:  $erlang_type\n\n";

    $HTML_text.=" Date range :  $query_date to $end_date\n";
    $HTML_text.=" Campaigns  :  ".preg_replace("/\|/", ", ", $campaign_string)."\n";
    $HTML_text.=" In-groups  :  ".preg_replace("/\|/", ", ", $group_string)."";
#   $HTML_text.=" Report type:  $erlang_type\n\n";
    
    $sale_stmt="select distinct status from vicidial_campaign_statuses where campaign_id in ($campaign_SQL) and sale='Y' UNION select status from vicidial_statuses where sale='Y'";
    if ($DB) {echo "|$sale_stmt|\n";}
    $sale_rslt=mysql_to_mysqli($sale_stmt, $link);
    $sales_array=array();
    while ($sale_row=mysqli_fetch_row($sale_rslt)) {
        array_push($sales_array, "$sale_row[0]");
    }

    if ($erlang_type=="B") {
         $hour_stmt="select closecallid, substr(call_date, 1, 13) as start_hour, length_in_sec, substr(call_date+INTERVAL length_in_sec second, 1, 13) as end_hour, if(DATE_FORMAT(call_date, '%Y-%m-%d %H:00:00')!=DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'), UNIX_TIMESTAMP(DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'))-UNIX_TIMESTAMP(call_date), length_in_sec) as length_up_to_next_hour, if(DATE_FORMAT(call_date, '%Y-%m-%d %H:00:00')!=DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'), UNIX_TIMESTAMP(call_date+INTERVAL length_in_sec second)-UNIX_TIMESTAMP(DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00')), 0) as length_running_into_next_hour, status, uniqueid from vicidial_closer_log v where length_in_sec is not null and $campaignId and call_date>='$query_date' and call_date<='$end_date'"; 

            $avg_stmt="select avg(length_in_sec) as avg_length from vicidial_closer_log v where length_in_sec is not null and $campaignId and call_date>='$query_date' and call_date<='$end_date'";

            $wrapup_stmt="select v.uniqueid from vicidial_closer_log v where length_in_sec is not null and user!='VDCL' and $campaignId and call_date>='$query_date' and call_date<='$end_date'";
    } else {
        $hour_stmt="select uniqueid, substr(call_date, 1, 13) as start_hour, length_in_sec, substr(call_date+INTERVAL length_in_sec second, 1, 13) as end_hour, if(DATE_FORMAT(call_date, '%Y-%m-%d %H:00:00')!=DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'), UNIX_TIMESTAMP(DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'))-UNIX_TIMESTAMP(call_date), length_in_sec) as length_up_to_next_hour, if(DATE_FORMAT(call_date, '%Y-%m-%d %H:00:00')!=DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'), UNIX_TIMESTAMP(call_date+INTERVAL length_in_sec second)-UNIX_TIMESTAMP(DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00')), 0) as length_running_into_next_hour, status, uniqueid from vicidial_log where length_in_sec is not null and campaign_id in ($campaign_SQL) and call_date>='$query_date 00:00:00' and call_date<='$end_date 23:59:59' and status!='AFTHRS'"; # *

        $avg_stmt="select avg(length_in_sec) as avg_length from vicidial_log where length_in_sec is not null and campaign_id in ($campaign_SQL) and call_date>='$query_date 00:00:00' and call_date<='$end_date 23:59:59'"; # **

        $wrapup_stmt="select uniqueid from vicidial_log where length_in_sec is not null and user!='VDCL' and campaign_id in ($campaign_SQL) and call_date>='$query_date 00:00:00' and call_date<='$end_date 23:59:59'"; # ***
    }
    if ($DB) {echo "|$hour_stmt|\n";}
    if ($DB) {echo "|$avg_stmt|\n";}
    if ($DB) {echo "|$wrapup_stmt|\n";}

    # $ASCII_text.=$hour_stmt."<BR>\n";
    $hour_rslt=mysql_to_mysqli($hour_stmt, $link); # *
    $erlang_calls=mysqli_num_rows($hour_rslt);

    # PROVIDE ACTUAL STATS
    $avg_rslt=mysql_to_mysqli($avg_stmt, $link); # **
    $avg_row=mysqli_fetch_array($avg_rslt);
    $average_call_length=$avg_row["avg_length"];

    $wrapup_rslt=mysql_to_mysqli($wrapup_stmt, $link); # ***
    $wrapup_calls=mysqli_num_rows($wrapup_rslt);
    $uid_ct=0; 
    $uid_clause="";
    $dispo_secs=0;
    $talk_secs=0;
    while ($wrapup_row=mysqli_fetch_row($wrapup_rslt)) {
        $uid_ct++;
      $uid_clause.="'$wrapup_row[0]',";
        if ($uid_ct%100==0) {
            $uid_clause=preg_replace('/,$/', '', $uid_clause);
            $uid_stmt="select dispo_sec, talk_sec from vicidial_agent_log where uniqueid in ($uid_clause)";
            $uid_rslt=mysql_to_mysqli($uid_stmt, $link);
            while ($uid_row=mysqli_fetch_row($uid_rslt)) {
                $dispo_secs+=$uid_row[0];
                $talk_secs+=$uid_row[1];
            }
            $uid_clause="";
        }
    }
    if (strlen($uid_clause)>0) {
        $uid_clause=preg_replace('/,$/', '', $uid_clause);
        $uid_stmt="select dispo_sec, talk_sec from vicidial_agent_log where uniqueid in ($uid_clause)";
        $uid_rslt=mysql_to_mysqli($uid_stmt, $link);
        while ($uid_row=mysqli_fetch_row($uid_rslt)) {
            $dispo_secs+=$uid_row[0];
            $talk_secs+=$uid_row[1];
        }
        $uid_clause="";
    }
    $avg_dispo_sec=MathZDC($dispo_secs, $wrapup_calls);
    $avg_talk_sec=MathZDC($talk_secs, $wrapup_calls);
    #####


    $wrapup_stmt="select average(dispo_sec) from vicidial_agent_log";

    $erlang_array=array();
    $drops_blocks=0;
    $sales=0;
    while ($hour_row=mysqli_fetch_array($hour_rslt)) {
        $erlang_array["$hour_row[start_hour]"][0]+=$hour_row["length_up_to_next_hour"];
        $erlang_array["$hour_row[start_hour]"][2]++;
        
        if ($hour_row["length_running_into_next_hour"]>0) {
            $erlang_array["$hour_row[end_hour]"][0]+=$hour_row["length_running_into_next_hour"];
            $erlang_array["$hour_row[end_hour]"][2]++;
            if ($hour_row["status"]=="DROP") {
                $erlang_array["$hour_row[end_hour]"][3]++;
            }
        }

        if ($hour_row["status"]=="DROP") {
            $erlang_array["$hour_row[start_hour]"][1]+=$hour_row["length_up_to_next_hour"];
            $erlang_array["$hour_row[end_hour]"][1]+=$hour_row["length_running_into_next_hour"];
            $erlang_array["$hour_row[start_hour]"][3]++;
            $drops_blocks++;
        }

        if (in_array("$hour_row[status]", $sales_array)) {
            $erlang_array["$hour_row[start_hour]"][4]++;
            $sales++;
        }
    }
    $hours_active=count($erlang_array); # Used for giving total erlangs for day, need to divide by number of hours reported, not one hour
    $total_erlangs=MathZDC(($erlang_calls*$average_call_length),(3600*$hours_active));
    $total_blocking=MathZDC($drops_blocks, $erlang_calls);
    $sale_percent=MathZDC($sales, $erlang_calls);

    $rpt_header ="+-----------------+-------+-------+------------+----------+-------------+----------+---------+";
    if ($erlang_type=="B") {$rpt_header.="---------+";}
    if ($erlang_type=="C") {$rpt_header.="------------+------------+";}
    $rpt_header.="------------+------------+------------+----------+-----------+------------+------------+------------+\n";

    $ASCII_text.=$rpt_header;
    # $ASCII_text.=$hour_stmt."<BR>\n";

    # Damn fixed-space formatting, hate ASCII.  HATE.
    if ($erlang_type=="B") {
        if ($actual_agents>0) {
            $ASCII_text.="| ".sprintf("%70s", _QXZ("Actual agents: ")).sprintf("%-131s", "$actual_agents")." |\n";
        }
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Total calls: ")).sprintf("%-131s", "$erlang_calls")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Total drops: ")).sprintf("%-131s", "$drops_blocks")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Desired drop rate: ")).sprintf("%-5.2f", "$drop_percent").sprintf("%-126s", " %")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Total blocking/drop rate: ")).sprintf("%-5.2f", (100*$total_blocking)).sprintf("%-126s", " %")." |\n";  # sprintf("%-131.4f", "$total_blocking")
        # $ASCII_text.="| ".sprintf("%70s", _QXZ("Desired sale rate: ")).sprintf("%-5.2f", "$sale_chance").sprintf("%-126s", " %")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Sale rate: ")).sprintf("%-5.2f", (100*$sale_percent)).sprintf("%-126s", " %")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Average call duration: ")).sprintf("%-131.2f", "$average_call_length")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Erlangs: ")).sprintf("%-131.4f", $total_erlangs)." |\n";
    } 
    if ($erlang_type=="C") {
        if ($actual_agents>0) {
            $ASCII_text.="| ".sprintf("%70s", _QXZ("Actual agents: ")).sprintf("%-147s", "$actual_agents")." |\n";
        }
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Total calls: ")).sprintf("%-147s", "$erlang_calls")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Total drops: ")).sprintf("%-147s", "$drops_blocks")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Desired drop rate: ")).sprintf("%-5.2f", "$drop_percent").sprintf("%-142s", " %")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Total blocking/drop rate: ")).sprintf("%-5.2f", (100*$total_blocking)).sprintf("%-142s", " %")." |\n";  # sprintf("%-147.4f", "$total_blocking")
        # $ASCII_text.="| ".sprintf("%70s", _QXZ("Desired sale rate: ")).sprintf("%-5.2f", "$sale_chance").sprintf("%-142s", " %")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Sale rate: ")).sprintf("%-5.2f", (100*$sale_percent)).sprintf("%-142s", " %")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Average call duration: ")).sprintf("%-147.2f", "$average_call_length")." |\n";
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Erlangs: ")).sprintf("%-147.4f", $total_erlangs)." |\n";
    }

    $HTML_text.="<table width='1024' border='0' cellpadding='0' cellspacing='0'>";
    $HTML_text.="<tr><td width='900'>";
    
    $CSV_text="";
    if ($actual_agents>0) {
        $CSV_text.="\"Actual agents:\",\"$actual_agents\"\n";
    }
    $CSV_text.="\"Total calls:\",\"$erlang_calls\"\n";
    $CSV_text.="\"Total drops:\",\"$drops_blocks\"\n";
    $CSV_text.="\"Desired drop rate:\",\"".sprintf("%5.2f", "$drop_percent")." %\"\n";
    $CSV_text.="\"Total blocking/drop rate:\",\"".sprintf("%-5.2f", (100*$total_blocking))."\"\n";
    # $CSV_text.="\"Desired sale rate:\",\"".sprintf("%5.2f", "$sale_chance")." %\"\n";
    $CSV_text.="\"Sale rate:\",\"".sprintf("%-5.2f", (100*$sale_percent))."\"\n";
    $CSV_text.="\"Average call duration:\",\"".sprintf("%.2f", "$average_call_length")." seconds\"\n";
    $CSV_text.="\"Erlangs:\",\"".sprintf("%.4f", "$total_erlangs")."\"\n";

    
    # ESTIMATED AGENTS
    $lines=1;
    if ($total_blocking>0) {
        $GoS=MathZDC((pow($total_erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $total_erlangs)));
        if ($GoS>$total_blocking) {
            $ASCII_text.="<!-- $lines \n";
            while ($GoS>$total_blocking) {
                $lines++;
                $GoS=MathZDC((pow($total_erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $total_erlangs)));
                if ($retry_rate>0 && $erlang_type=="B") {$GoS=adjustedGoS($total_erlangs, $GoS, $retry_rate, $lines);} #"B" allows for retry rates, "C" does not.
                $ASCII_text.=" $GoS \n";
            }
            $ASCII_text.="\n-->";
        }
        $Pqueue=(pow($total_erlangs, $lines)/(factorial($lines)))/((pow($total_erlangs, $lines)/(factorial($lines)))+((1-MathZDC($total_erlangs, $lines))*erlsum(0, ($lines-1), $total_erlangs)));
        $ASA=MathZDC(($Pqueue*$average_call_length), ($lines-$total_erlangs));
    } else {
        $lines="N/A";
        $Pqueue=0;
        $ASA=0;
    }
    if ($Pqueue>1) {$Pqueue=1;}
    $est_GoS=$GoS;  # Need this here since we moved the output line further down & need to save it.
    $est_lines=$lines;  # Need this here since we moved the output line further down & need to save it.

    if ($erlang_type=="B") {
#       $ASCII_text.="| ".sprintf("%70s", _QXZ("Retry rate: ")).sprintf("%-131s", "$retry_rate %")." |\n"; # B
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Grade of service: ")).sprintf("%-131s", sprintf("%0.2f", (100*$GoS)." %"))." |\n"; # B
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Estimated agents fielding calls: ")).sprintf("%-131s", $lines)." |\n";
    } 
    if ($erlang_type=="C") {
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Estimated queue probability: ")).sprintf("%-5.2f", (100*$Pqueue)).sprintf("%-142s", " %")." |\n"; # C
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Average speed of answering: ")).sprintf("%-147s", sec_convert($ASA,'H'))." |\n"; # C
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Estimated agents fielding calls: ")).sprintf("%-147s", $lines)." |\n";
    }

    if ($erlang_type=="B") {
#       $CSV_text.="\""._QXZ("Retry rate: ")."\",\"$retry_rate %\"\n"; # B
        $CSV_text.="\""._QXZ("Grade of service: ")."\",\"".sprintf("%0.2f", (100*$GoS))." %\"\n"; # B
    }
    if ($erlang_type=="C") {
        $CSV_text.="\""._QXZ("Estimated queue probability: ")."\",\"".sprintf("%.2f", (100*$Pqueue))."%\"\n"; # C
        $CSV_text.="\""._QXZ("Average speed of answering: ")."\",\"".sec_convert($ASA,'H')."\"\n"; # C
    }
    $CSV_text.="\""._QXZ("Estimated agents fielding calls: ")."\",\"$lines\"\n";

    if ($erlang_type=="B") {
##      $HTML_text.="<tr class='records_list_x'><td><font size=1>"._QXZ("Retry rate: ")."</font></td><td><font size=1>$retry_rate %</font></td></tr>"; # B
#       $HTML_text.="<tr class='records_list_x'><td><font size=1>"._QXZ("Grade of service: ")."</font></td><td><font size=1>".sprintf("%0.2f", (100*$GoS))."%</font></td></tr>"; # B
    }
    if ($erlang_type=="C") {
        $HTML_text.="<tr class='records_list_x'><td><font size=1>"._QXZ("Estimated queue probability: ")."</font></td><td><font size=1>".sprintf("%.2f", (100*$Pqueue))."%</font></td></tr>"; # C
        $HTML_text.="<tr class='records_list_y'><td><font size=1>"._QXZ("Average speed of answering: ")."</font></td><td><font size=1>".sec_convert($ASA,'H')."</font></td></tr>"; # C
    }
#   $HTML_text.="<tr class='records_list_y'><td><font size=1>"._QXZ("Estimated agents fielding calls: ")."</font></td><td><font size=1>".$lines."</font></td></tr>";
    #############

    ##### RECOMMENDED AGENTS
    $lines=1;
    if ($erlang_type=="B") {
        $GoS=MathZDC((pow($total_erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $total_erlangs)));
        if ($GoS>$drop_rate) {
            $ASCII_text.="<!-- $lines \n";
            while ($GoS>$drop_rate) {
                $lines++;
                $GoS=MathZDC((pow($total_erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $total_erlangs)));
                if ($retry_rate>0) {$GoS=adjustedGoS($total_erlangs, $GoS, $retry_rate, $lines);}
                $ASCII_text.=" $GoS \n";
            }
            $ASCII_text.="\n-->";
        }
    }
    if ($erlang_type=="C") {
        $Pqueue=(pow($total_erlangs, $lines)/(factorial($lines)))/((pow($total_erlangs, $lines)/(factorial($lines)))+((1-MathZDC($total_erlangs, $lines))*erlsum(0, ($lines-1), $total_erlangs)));
        if ($Pqueue>$pqueue_target) {
            $ASCII_text.="<!-- $lines \n";
            while ($Pqueue>$pqueue_target) {
                $lines++;
                $Pqueue=(pow($total_erlangs, $lines)/(factorial($lines)))/((pow($total_erlangs, $lines)/(factorial($lines)))+((1-MathZDC($total_erlangs, $lines))*erlsum(0, ($lines-1), $total_erlangs)));
                $ASCII_text.=" $GoS \n";
            }
            $ASCII_text.="\n-->";
        }
    }
    
    if ($erlang_type=="B") {
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Recommended agent count: ")).sprintf("%-131s", $lines)." |\n";
    } else {
        $ASCII_text.="| ".sprintf("%70s", _QXZ("Recommended agent count: ")).sprintf("%-147s", $lines)." |\n";
    }
    $CSV_text.="\"Recommended agent count:\",\"$lines\"\n";
    $CSV_text.="\n";    
    #############

    $ASCII_text.=$rpt_header;
    $ASCII_text.= "| "._QXZ("CALLING HOUR", 15)." | "._QXZ("CALLS", 5)." | "._QXZ("SALES", 5)." | "._QXZ("TOTAL TIME", 10)." | "._QXZ("AVG TIME", 8)." | "._QXZ("DROPPED HRS", 11)." | "._QXZ("BLOCKING", 8)." | "._QXZ("ERLANGS", 7);
    if ($erlang_type=="B") {$ASCII_text.=" | "._QXZ("GOS", 7);} # B
    if ($erlang_type=="C") {$ASCII_text.=" | "._QXZ("QUEUE PROB", 10)." | "._QXZ("AVG ANSWER", 10);} # C
    $ASCII_text.=" | "._QXZ("REC AGENTS", 10)." | "._QXZ("EST AGENTS", 10)." | "._QXZ("CALLS/AGNT", 10)." |\n"; #  ." | "._QXZ("REV/CALL", 8)." | "._QXZ("REV/AGENT", 9)." | "._QXZ("TOTAL REV", 10)." | "._QXZ("TOTAL COST", 10)." | "._QXZ("MARGIN", 10)
    $ASCII_text.=$rpt_header;
    $CSV_text.="\""._QXZ("CALLING HOUR")."\",\""._QXZ("CALLS")."\",\""._QXZ("SALES")."\",\""._QXZ("TOTAL TIME")."\",\""._QXZ("AVG TIME")."\",\""._QXZ("DROPPED HRS")."\",\""._QXZ("BLOCKING")."\",\""._QXZ("ERLANGS")."\",";
    if ($erlang_type=="B") {$CSV_text.="\""._QXZ("GRADE OF SERVICE")."\",";} # B
    if ($erlang_type=="C") {$CSV_text.="\""._QXZ("QUEUE PROBABILITY")."\",\""._QXZ("AVERAGE ANSWER SPEED")."\",";} # C
    $CSV_text.="\""._QXZ("REC AGENTS")."\",\""._QXZ("EST AGENTS")."\",\""._QXZ("CALLS/AGNT")."\"\n"; # ,\""._QXZ("REV/CALL")."\",\""._QXZ("REV/AGENT")."\",\""._QXZ("TOTAL REV")."\",\""._QXZ("TOTAL COST")."\",\""._QXZ("MARGIN")."\"
    ksort($erlang_array);

    $HTML_text2="</td><td valign='top'style=\"padding: 3px 0px 0px 10px;\">";
    $HTML_text2.="<BR><table width='130' border='0' cellpadding='2' cellspacing='0'>";
    $HTML_text2.="<tr bgcolor='#000'><td colspan='2' align='center'><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("INBOUND SUMMARY")."</FONT></B></td></tr>";
    if ($actual_agents>0) {
        $HTML_text2.="<tr class='records_list_y'><td><font size=1>"._QXZ("Actual agents: ")."</font></td><td nowrap><font size=1>".$actual_agents."</font></td></tr>";
    }
    $HTML_text2.="<tr class='records_list_x'><td><font size=1>"._QXZ("Total calls: ")."</font></td><td nowrap><font size=1>".$erlang_calls."</font></td></tr>";
    $HTML_text2.="<tr class='records_list_y'><td><font size=1>"._QXZ("Total drops: ")."</font></td><td nowrap><font size=1>".$drops_blocks."</font></td></tr>";
    $HTML_text2.="<tr class='records_list_x'><td><font size=1>"._QXZ("Desired drop rate: ")."</font></td><td nowrap><font size=1>".sprintf("%5.2f", "$drop_percent")." %</font></td></tr>";
    $HTML_text2.="<tr class='records_list_y'><td><font size=1>"._QXZ("Total blocking/drop rate: ")."</font></td><td nowrap><font size=1>".sprintf("%-5.2f", (100*$total_blocking))." %</font></td></tr>";
    # $HTML_text2.="<tr class='records_list_x'><td><font size=1>"._QXZ("Desired sale rate: ")."</font></td><td nowrap><font size=1>".sprintf("%5.2f", "$sale_chance")." %</font></td></tr>";
    $HTML_text2.="<tr class='records_list_x'><td><font size=1>"._QXZ("Sale rate: ")."</font></td><td nowrap><font size=1>".sprintf("%-5.2f", (100*$sale_percent))." %</font></td></tr>";
    $HTML_text2.="<tr class='records_list_y'><td><font size=1>"._QXZ("Average call duration: ")."</font></td><td nowrap><font size=1>".sprintf("%18.2f", "$average_call_length")."</font></td></tr>";
    $HTML_text2.="<tr class='records_list_x'><td><font size=1>"._QXZ("Erlangs: ")."</font></td><td nowrap><font size=1>".sprintf("%18.4f", $total_erlangs)."</font></td></tr>";
    $HTML_text2.="<tr class='records_list_y'><td><font size=1>"._QXZ("Grade of service: ")."</font></td><td nowrap><font size=1>".sprintf("%0.2f", (100*$est_GoS))."%</font></td></tr>"; # B
    $HTML_text2.="<tr class='records_list_x'><td><font size=1>"._QXZ("Estimated agents fielding calls: ")."</font></td><td nowrap><font size=1>".$est_lines."</font></td></tr>";
    $HTML_text2.="<tr class='records_list_y'><td><font size=1>"._QXZ("Recommended agent count: ")."</font></td><td nowrap><font size=1>".$lines."</font></td></tr>";
    $HTML_text2.="</table>";
    $HTML_text2.="</td>";
    $HTML_text2.="</tr></table>"; # For closing the table above that encompasses first report table and chart

    $HTML_text2.="<table width='1024' border='0' cellpadding='2' cellspacing='0'>";
    $HTML_text2.="<TR BGCOLOR=BLACK>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("CALLING HOUR")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("CALLS")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("SALES")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("TOTAL TIME")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("AVG TIME")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("DROPPED HRS")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("BLOCKING")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("ERLANGS")."</FONT></B></TD>\n";

    if ($erlang_type=="B") {
        $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("GRADE OF SERVICE")."</FONT></B></TD>\n"; # B
    }
    if ($erlang_type=="C") {
        $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("QUEUE PROB")."</FONT></B></TD>\n"; # C
        $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("AVG ANSWER")."</FONT></B></TD>\n"; # C
    }

    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("REC AGENTS")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("EST AGENTS")."</FONT></B></TD>\n";
    $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("CALLS PER AGENT")."</FONT></B></TD>\n";
    # $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("REVENUE PER CALL")."</FONT></B></TD>\n";
    # $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("REVENUE PER AGENT")."</FONT></B></TD>\n";
    # $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("TOTAL REVENUE")."</FONT></B></TD>\n";
    # $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("TOTAL COST")."</FONT></B></TD>\n";
    # $HTML_text2.="<TD><B><FONT FACE=\"Arial,Helvetica\" size=1 color=white>"._QXZ("MARGIN")."</FONT></B></TD>\n";
    $HTML_text2.="</TR>\n";


    $bht=0;
    $graph_stats=array();
    $q=0;
    while(list($key, $val)=each($erlang_array)) {
        if ($val[0]>$bht) {$bht=$val[0];}
        if ($q%2==0) {$tdclass="records_list_x";} else {$tdclass="records_list_y";}
        $average_time=round(MathZDC($val[0], $val[2]));
        $blocking=MathZDC($val[3], $val[2]);
        $erlangs=$val[2]*MathZDC($average_time, 3600); # Row is call per hour, therefore call length average must be in hours.
        $sales=$val[4]+0;

        $ASCII_text.="| "; #.substr($key, 0, -2)." - ".substr($key, 5, 2)." ".substr($key, 8, 2)." ".substr($key, 0, 4);
        $ASCII_text.=date("Y-m-d ha", mktime(substr($key, -2), 0, 0, substr($key, 5, 2), substr($key, 8, 2), substr($key, 0, 4)));
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%5s", $val[2]);
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%5s", $sales);
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%10s", sec_convert($val[0],'H'));
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%8s", sec_convert($average_time,'H'));
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%11s", sec_convert($val[1],'H'));
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%8s", sprintf("%01.4f", $blocking));
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%7s", sprintf("%01.4f", $erlangs)); 
        $ASCII_text.=" | ";

        $CSV_text.="\"".date("Y-m-d ha", mktime(substr($key, -2), 0, 0, substr($key, 5, 2), substr($key, 8, 2), substr($key, 0, 4)))."\",\"".$val[2]."\",\"".$sales."\",\"".sec_convert($val[0],'H')."\",\"".sec_convert($average_time,'H')."\",\"".sec_convert($val[1],'H')."\",\"".sprintf("%01.4f", $blocking)."\",\"".sprintf("%01.4f", $erlangs)."\",";

        $HTML_text2.="<tr class='$tdclass'>\n";
        $HTML_text2.="<td><font size=1>".date("Y-m-d ha", mktime(substr($key, -2), 0, 0, substr($key, 5, 2), substr($key, 8, 2), substr($key, 0, 4)))."</font></td>\n";
        $HTML_text2.="<td><font size=1>".$val[2]."</font></td>\n";
        $HTML_text2.="<td><font size=1>".$sales."</font></td>\n";
        $HTML_text2.="<td><font size=1>".sec_convert($val[0],'H')."</font></td>\n";
        $HTML_text2.="<td><font size=1>".sec_convert($average_time,'H')."</font></td>\n";
        $HTML_text2.="<td><font size=1>".sec_convert($val[1],'H')."</font></td>\n";
        $HTML_text2.="<td><font size=1>".sprintf("%01.4f", $blocking)."</font></td>\n";
        $HTML_text2.="<td><font size=1>".sprintf("%01.4f", $erlangs)."</font></td>\n";

        $graph_key=date("Y-m-d ha", mktime(substr($key, -2), 0, 0, substr($key, 5, 2), substr($key, 8, 2), substr($key, 0, 4)));
        $graph_stats["$graph_key"][0]=$val[2];
        $graph_stats["$graph_key"][1]=$val[0];
        $graph_stats["$graph_key"][2]=$average_time;
        $graph_stats["$graph_key"][3]=$val[1];
        $graph_stats["$graph_key"][4]=sprintf("%01.4f", $blocking);
        $graph_stats["$graph_key"][5]=sprintf("%01.4f", $erlangs);
    
        ##### RECOMMENDED AGENTS
        $lines=1;
        if ($erlang_type=="B") {
            $GoS=MathZDC((pow($erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $erlangs)));
            if ($GoS>$drop_rate) {
                $ASCII_text.="<!-- $lines \n";
                while ($GoS>$drop_rate) {
                    $lines++;
                    $GoS=MathZDC((pow($erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $erlangs)));
                    if ($retry_rate>0) {$GoS=adjustedGoS($erlangs, $GoS, $retry_rate, $lines);}
                    $ASCII_text.=" $GoS \n";
                }
                $ASCII_text.="\n-->";
            }
        }
        if ($erlang_type=="C") {
            $Pqueue=(pow($erlangs, $lines)/(factorial($lines)))/((pow($erlangs, $lines)/(factorial($lines)))+((1-MathZDC($erlangs, $lines))*erlsum(0, ($lines-1), $erlangs)));
            if ($Pqueue>$pqueue_target) {
                $ASCII_text.="<!--  \n";
                while ($Pqueue>$pqueue_target) {
                    $lines++;
                    $Pqueue=(pow($erlangs, $lines)/(factorial($lines)))/((pow($erlangs, $lines)/(factorial($lines)))+((1-MathZDC($erlangs, $lines))*erlsum(0, ($lines-1), $erlangs)));
                    $ASCII_text.=" $lines, $erlangs - $Pqueue \n";
                }
                $ASCII_text.="\n-->";
            }
        }
        $recommended_agents=$lines;

        ##### ESTIMATED AGENTS
        $lines=1;
        if ($blocking>0) {
            $GoS=MathZDC((pow($erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $erlangs)));
            if ($GoS>$blocking) {
                $ASCII_text.="<!-- $lines \n";
                while ($GoS>$blocking) {
                    $lines++;
                    $GoS=MathZDC((pow($erlangs, $lines)/(factorial($lines))), (erlsum(0, $lines, $erlangs)));
                    if ($retry_rate>0 && $erlang_type=="B") {$GoS=adjustedGoS($erlangs, $GoS, $retry_rate, $lines);}  # "B" allows for retry rates, "C" does not
                    $ASCII_text.=" $GoS \n";
                }
                $ASCII_text.="\n-->";
            }

            $Pqueue=(pow($erlangs, $lines)/(factorial($lines)))/((pow($erlangs, $lines)/(factorial($lines)))+((1-MathZDC($erlangs, $lines))*erlsum(0, ($lines-1), $erlangs)));
            $ASA=MathZDC(($Pqueue*$average_time), ($lines-$erlangs));
        } else {
            $lines="$recommended_agents";
            $Pqueue=0;
            $ASA=0;
        }
        if ($actual_agents>0) {
            $lines=$actual_agents;
            $Pqueue=0;
            $ASA=0;
        }
        if ($Pqueue>1) {$Pqueue=1;}

        if ($erlang_type=="B") {
            $ASCII_text.=sprintf("%7s", sprintf("%0.2f", (100*$GoS))."%"); 
            $ASCII_text.=" | ";
        }
        if ($erlang_type=="C") {
            $ASCII_text.=sprintf("%10s", sprintf("%.4f", (100*$Pqueue))."%"); 
            $ASCII_text.=" | ";
            $ASCII_text.=sprintf("%10s", sec_convert($ASA,'H')); 
            $ASCII_text.=" | ";
        }
        $ASCII_text.=sprintf("%10s", $recommended_agents);
        $ASCII_text.=" | ";
        if ($erlang_type=="B") {
            $CSV_text.="\"".sprintf("%0.2f", (100*$GoS))."%\","; # B
        }
        if ($erlang_type=="C") {
            $CSV_text.="\"".sprintf("%.6f", (100*$Pqueue))."%\","; # C
            $CSV_text.="\"".sec_convert($ASA,'H')."\","; # C
        }
        $CSV_text.="\"$recommended_agents\",";
        $graph_stats["$graph_key"][7]=$recommended_agents;
        if ($erlang_type=="B") {
            $HTML_text2.="<td><font size=1>".sprintf("%0.2f", (100*$GoS))."%</font></td>\n"; # B
        }
        if ($erlang_type=="C") {
            $HTML_text2.="<td><font size=1>".sprintf("%.4f", (100*$Pqueue))."%</font></td>\n"; # C
            $HTML_text2.="<td><font size=1>".sec_convert($ASA,'H')." </font></td>\n"; # C
        }
        $HTML_text2.="<td><font size=1>$recommended_agents</font></td>\n";

        $ASCII_text.=sprintf("%10s", $lines);
        $ASCII_text.=" | ";
        $ASCII_text.=sprintf("%-10.2f", MathZDC($val[2], $lines));
#       $ASCII_text.=" | ";
#       $ASCII_text.=sprintf("%-8s", "\$".number_format(MathZDC(($revenue_per_sale*$sales), $val[2]),2,".",","));
#       $ASCII_text.=" | ";
#       $ASCII_text.=sprintf("%-9s", "\$".number_format(MathZDC(($revenue_per_sale*$sales), $lines),2,".",","));
#       $ASCII_text.=" | ";
#       $ASCII_text.=sprintf("%-10s", "\$".number_format(($revenue_per_sale*$sales),2,".",","));
#       $ASCII_text.=" | ";
#       $ASCII_text.=sprintf("%-10s", "\$".number_format(($lines*$hourly_pay),2,".",","));
#       $ASCII_text.=" | ";
#       $ASCII_text.=sprintf("%-10s", "\$".number_format((($revenue_per_sale*$sales)-($lines*$hourly_pay)),2,".",","));
        $ASCII_text.=" |\n";

        $graph_stats["$graph_key"][8]=$drop_rate;


        $CSV_text.="\"$lines\",";
        $CSV_text.="\"".sprintf("%.2f", MathZDC($val[2], $lines))."\",";
#       $CSV_text.="\"$".number_format(MathZDC(($revenue_per_sale*$sales), $val[2]),2,".",",")."\",";
#       $CSV_text.="\"$".number_format(MathZDC(($revenue_per_sale*$sales), $lines),2,".",",")."\",";
#       $CSV_text.="\"$".number_format(($revenue_per_sale*$sales),2,".",",")."\",";
#       $CSV_text.="\"$".number_format(($lines*$hourly_pay),2,".",",")."\",";
#       $CSV_text.="\"$".number_format((($revenue_per_sale*$sales)-($lines*$hourly_pay)),2,".",",")."\"\n";

        $graph_stats["$graph_key"][6]=$lines;
        $graph_stats["$graph_key"][8]=$drop_rate;
        $graph_stats["$graph_key"][9]=$sales; 
        $graph_stats["$graph_key"][10]=number_format(MathZDC(($revenue_per_sale*$sales), $val[2]),2,".",""); # Revenue per call 
        $graph_stats["$graph_key"][11]=number_format(MathZDC(($revenue_per_sale*$sales), $lines),2,".",""); # Revenue per agent 
        $graph_stats["$graph_key"][12]=number_format(($revenue_per_sale*$sales),2,".",""); # Total revenue 
        $graph_stats["$graph_key"][13]=number_format(($lines*$hourly_pay),2,".",""); # Cost 
        $graph_stats["$graph_key"][14]=number_format((($revenue_per_sale*$sales)-($lines*$hourly_pay)),2,".",",");
        $graph_stats["$graph_key"][15]=$GoS; 
        $graph_stats["$graph_key"][16]=$Pqueue; # Cost 
        $graph_stats["$graph_key"][17]=$ASA; # Cost 

        $HTML_text2.="<td><font size=1>$lines</font></td>\n";
        $HTML_text2.="<td><font size=1>".sprintf("%.2f", ($val[2]/$lines))."</font></td>\n";
#       $HTML_text2.="<td><font size=1>\$".number_format(MathZDC(($revenue_per_sale*$sales), $val[2]),2,".",",")."</font></td>\n";
#       $HTML_text2.="<td><font size=1>\$".number_format(MathZDC(($revenue_per_sale*$sales), $lines),2,".",",")."</font></td>\n";
#       $HTML_text2.="<td><font size=1>\$".number_format(($revenue_per_sale*$sales),2,".",",")."</font></td>\n";
#       $HTML_text2.="<td><font size=1>\$".number_format(($lines*$hourly_pay),2,".",",")."</font></td>\n";
#       $HTML_text2.="<td><font size=1>\$".number_format((($revenue_per_sale*$sales)-($lines*$hourly_pay)),2,".",",")."</font></td>\n";
        $HTML_text2.="</tr>\n";
        $q++;
    }
    $ASCII_text.=$rpt_header;
    $ASCII_text.=" BHT:  ".sec_convert($bht,'H')." ($bht seconds)\n";

    $HTML_text2.="</table>";

    # USE THIS FOR COMBINED graphs, use pipe-delimited array elements, dataset_name|index|link_name|graph_override
    # You have to hard code the graph name in where it is overridden and mind the data indices.  No other way to do it.
    $multigraph_text="";
    $graph_id++;
    $graph_array=array("ERL_AGENTSdata|7,6|AGENTS|integer|Rec. Agents,Est. Agents", "ERL_BLOCKdata|4,8|BLOCKING/DROPS|percent|Blocking,Desired drop rate", "ERL_CALLSdata|0,9|CALLS|integer|Calls,Sales", "ERL_TIMEdata|2,3,1|TIMES|time|Average time,Dropped time,Total time", "ERL_ERLANGSdata|5|ERLANGS|integer|Erlangs"); # , "ERL_REVENUEdata|10,11|REVENUE|dollar|Revenue per call,Revenue per agent", "ERL_REVENUEdata|12,13|MARGINS|dollar|Total revenue,Total cost"
    if ($erlang_type=="B") {
        array_push($graph_array, "ERL_GOSdata|15|GoS|integer|Grade of Service");
    }
    if ($erlang_type=="C") {
        array_push($graph_array, "ERL_PQUEUEdata|16|QUEUE|percent|Call queue probability");
    }


    $default_graph="line"; # Graph that is initally displayed when page loads
    include("graph_color_schemas.inc"); 


    # CUSTOMIZING COLORS FOR LINE GRAPHS
    $graph_colors=array("216,0,0", "0,0,216", "0,216,0", "216,216,0", "216,0,216", "0,216,216");


    $graph_totals_array=array();
    $graph_totals_rawdata=array();
    for ($q=0; $q<count($graph_array); $q++) {
        $graph_info=explode("|", $graph_array[$q]); 
        $current_graph_total=0;
        $dataset_name=$graph_info[0];
        $dataset_indices=explode(",", $graph_info[1]); 
        $dataset_type=$graph_info[3];
        $dataset_labels=explode(",", $graph_info[4]); 

        $JS_text.="var $dataset_name = {\n";
        $datasets="\t\tdatasets: [\n";

        $labels="\t\tlabels:[";
        while(list($key, $val)=each($graph_stats)) {
            $labels.="\"".preg_replace('/ +/', ' ', $key)."\",";
        }
        reset($graph_stats);
        $labels=preg_replace('/,$/', '', $labels)."],\n";

        for ($d=0; $d<count($dataset_indices); $d++) {
            $dataset_index=$dataset_indices[$d];
            $datasets.="\t\t\t{\n";
            $datasets.="\t\t\t\tlabel: \"$dataset_labels[$d]\",\n";
            $datasets.="\t\t\t\tfill: true,\n";

            $data="\t\t\t\tdata: [";
            $graphConstantsA="\t\t\t\tbackgroundColor: \"rgba(".$graph_colors[($d%count($graph_colors))].",0.5)\",\n";
            $graphConstantsB="\t\t\t\tborderColor: \"rgba(".$graph_colors[($d%count($graph_colors))].",1)\",\n";
            $graphConstantsC="\t\t\t\tfillColor: \"rgba(".$graph_colors[($d%count($graph_colors))].",0.1)\"\n";

            while(list($key, $val)=each($graph_stats)) {
                $val[$dataset_index]=preg_replace("/N\/A/", "0", $val[$dataset_index]);
                $data.="\"".$val[$dataset_index]."\","; 
                $current_graph_total+=$val[$dataset_index];
                $bgcolor=$backgroundColor[($d%count($backgroundColor))];
                $hbgcolor=$hoverBackgroundColor[($d%count($hoverBackgroundColor))];
                $hbcolor=$hoverBorderColor[($d%count($hoverBorderColor))];
            }
            reset($graph_stats);
            $data=preg_replace('/,$/', '', $data)."],\n";
            $datasets.=$data;
            $datasets.=$graphConstantsA.$graphConstantsB.$graphConstantsC; # SEE TOP OF SCRIPT
            $datasets.="\t\t\t},\n";
            $graph_totals_rawdata[$d]=$current_graph_total;
        }   
        $datasets=preg_replace('/,\n$/', "\n", $datasets);

        switch($dataset_type) {
            case "time":
                $chart_options="options: {tooltips: {callbacks: {label: function(tooltipItem, data) {var value = Math.round(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]); return data.datasets[tooltipItem.datasetIndex].label+\": \"+value.toHHMMSS();}}}, legend: { display: true }},";
                break;
            case "percent":
                $chart_options="options: {tooltips: {callbacks: {label: function(tooltipItem, data) {var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]; return data.datasets[tooltipItem.datasetIndex].label+\": \"+ (value*100) + '%';}}}, legend: { display: true }},";
                break;
            case "dollar":
                $chart_options="options: {tooltips: {callbacks: {label: function(tooltipItem, data) {var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]; return data.datasets[tooltipItem.datasetIndex].label+\": \$\"+ value;}}}, legend: { display: true }},";
                break;
            default:
                $chart_options="options: { legend: { display: true}},";
                break;
        }

        $datasets.="\t\t]\n";
        $datasets.="\t}\n";

        $JS_text.=$labels.$datasets;
        $JS_text.="var main_ctx = document.getElementById(\"CanvasID".$graph_id."_".$q."\");\n";
        $JS_text.="var GraphID".$graph_id."_".$q." = new Chart(main_ctx, {type: '$default_graph', $chart_options data: $dataset_name});\n";
    }


    $graph_count=count($graph_array);
    $graph_title=_QXZ("FORECASTING REPORT");
    $hide_graph_choice="yes";
    $override_width=900; $override_height=500;
    include("graphcanvas.inc");
    $HEADER.=$HTML_graph_head;
    $GRAPH.=$graphCanvas;

    
    if ($report_display_type=="HTML")
        {
        $JS_onload.="}\n";
        $JS_text.=$JS_onload;
        $JS_text.="</script>\n";
        require("chart_button.php");

        $MAIN.=$HTML_text.$GRAPH.$HTML_text2.$JS_text;
        }
    else 
        {
        $MAIN.=$ASCII_text;
        }


    $MAIN.="</FORM>";

if ($file_download>0) 
    {
    $FILE_TIME = date("Ymd-His");
    $CSVfilename = "AST_Erlang_report_$US$FILE_TIME.csv";
    $CSV_text=preg_replace('/\n +,/', ',', $CSV_text);
    $CSV_text=preg_replace('/ +\"/', '"', $CSV_text);
    $CSV_text=preg_replace('/\" +/', '"', $CSV_text);

    // We'll be outputting a TXT file
    header('Content-type: application/octet-stream');
    // It will be called LIST_101_20090209-121212.txt
    header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    ob_clean();
    flush();

    echo "$CSV_text";
    }
else 
    {
    header("Content-type: text/html; charset=utf-8");

    echo "$HEADER";
    //require("admin_header.php");
?>
<style type="text/css">
    
    .auraltext
    {
    position: absolute;
    font-size: 0;
    left: -1000px;
    }
.chart_td
    {background-image: url(images/gridline58.gif); background-repeat: repeat-x; background-position: left top; border-left: 1px solid #e5e5e5; border-right: 1px solid #e5e5e5; padding:0; border-bottom: 1px solid #e5e5e5; background-color:transparent;}

.head_style
    {
    background-color: <?php echo $Mmain_bgcolor ?>;
    }
.head_style:hover{background-color: #262626;}

.head_style_selected
    {
    background-color: <?php echo $Mhead_color ?>;
    }
.head_style_selected:hover{background-color: <?php echo $Mhead_color ?>;}

.subhead_style
    {
    background-color: <?php echo $Msubhead_color ?>;
    }
.subhead_style:hover{background-color: white;}

.subhead_style_selected
    {
    background-color: <?php echo $Mselected_color ?>;
    }
.subhead_style_selected:hover{background-color: <?php echo $Mselected_color ?>;}

.adminmenu_style_selected
    {
    background-color: white;
    }
.adminmenu_style_selected:hover{background-color: #E6E6E6;}

.records_list_x
    {
    background-color: #<?php echo $SSstd_row2_background ?>;
    }
.records_list_x:hover{background-color: #E6E6E6;}

.records_list_y
    {
    background-color: #<?php echo $SSstd_row1_background ?>;
    }
.records_list_y:hover{background-color: #E6E6E6;}


.horiz_line
    {
    height: 0px;
    margin: 0px;
    border-bottom: 1px solid #E6E6E6;
    font-size: 1px;
    }
.horiz_line_grey
    {
    height: 0px;
    margin: 0px;
    border-bottom: 1px solid #9E9E9E;
    font-size: 1px;
    }
</style>
<?php

    echo "$MAIN";
    flush();
    }
#   echo $ASCII_text;
    }
?>



            </div>
            <div class="panel-footer"></div>
        </div>
              

    </div>
</div>




  
           
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



    </body>
</html>
