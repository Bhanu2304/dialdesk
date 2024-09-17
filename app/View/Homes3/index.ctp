<?php //echo $viewType2;exit; ?>
<style>
   .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    color: #9e9e9e;
    background-color: #000!important;
    border: 1px solid #eeeeee;
    border-bottom-color: transparent;
    cursor: default;
}
.nav-tabs {
    width: 12%!important;
}
.nav > li > a { padding: 14px 18px!important;}
</style>
<script>
   $( function() {
   $( "#fdate2" ).datepicker({
       dateFormat: 'yy-mm-dd',
       changeYear: false,
       changeMonth:false,
        'minDate': new Date(<?php echo date('Y'); ?>,<?php echo date('m'); ?>-1,1),
        'maxDate': new Date(<?php echo date('Y'); ?>,<?php echo date('m'); ?>-1,<?php echo date('t'); ?>)
       
   });
   $( "#ldate2" ).datepicker({
       dateFormat: 'yy-mm-dd',
       changeYear: false,
       changeMonth:false,
       'minDate': new Date(<?php echo date('Y'); ?>,<?php echo date('m'); ?>-1,1),
        'maxDate': new Date(<?php echo date('Y'); ?>,<?php echo date('m'); ?>-1,<?php echo date('t'); ?>)
   });
   } );
   //https://bootstrap-datepicker.readthedocs.io/en/latest/options.html#beforeshowyear
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<?php  if($this->Session->read('role') =="admin")
{
   $companyid=$this->Session->read('companyid');     
   ?>
      <script>
         function viewClient(){
            $("#view_client_form").submit();	
         } 
      </script>
      <!-- <script type="text/javascript" src="https://www.google.com/jsapi"></script> -->
      <style>
         thead > tr > td {background: cornflowerblue;color: white;font-weight: 500;}
         .odd,.even {    border: 1px solid cornflowerblue;background:blue!important;color: white;}
      </style>
      <div style="margin-left:20px;">
         <div class="form-group">
            <?php echo $this->Form->create('Home',array('url'=>'/homes3','id'=>'view_client_form')); ?>
            <div class="col-sm-3">
               <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control', 'onchange'=>'viewClient();','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
            </div>
            <div class="col-sm-4" style="margin-top:10px;">
               <?php echo '<span style="color:red;" class="">Registration Date:-'.$activation_date.'</span>';?>
            </div>
            <?php echo $this->Form->end(); ?>
         </div>
      </div>
      <br/>
      <br/>
<?php }?>
<?php  if($this->Session->read('companyid') !="")
{?>
<?php if($this->Session->read('clientstatus') ==="A")
{
?>
      <script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/jsapi"></script>
      <script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/loader.js"></script>
      <script type="text/javascript">
         google.load('visualization', '1', {'packages':['columnchart','piechart','table']});
         $(document).ready(function () { 
            document.getElementById("fdate").disabled = true;
            document.getElementById("ldate").disabled = true;
            document.getElementById("fdate2").disabled = true;
            document.getElementById("ldate2").disabled = true;
            <?php if(isset($viewType) && $viewType==="Custom"){?>
            document.getElementById("fdate").disabled = false;
            document.getElementById("ldate").disabled = false;
            <?php }?>
            <?php if(isset($viewType2) && $viewType2==="Custom"){?>
            document.getElementById("fdate2").disabled = false;
            document.getElementById("ldate2").disabled = false;
            <?php }?>
         });
         
         
         function getType(form){
            $("#fdate").val('');
            $("#ldate").val('');
            document.getElementById("fdate").disabled = true;
            document.getElementById("ldate").disabled = true;
            
            var rates = document.getElementsByName('view_type');
         
            var rate_value;
            for(var i = 0; i < rates.length; i++){
               if(rates[i].checked){
                     rate_value = rates[i].value;
                     if(rate_value =="Custom"){
                        document.getElementById("fdate").disabled = false;
                        document.getElementById("ldate").disabled = false;
                     }
                     else
                     {
                        form.submit();
                     }
                     // $("#tabactive").addClass("active"); 
               }
            }	
         }
         
         
         function getType2(form){
            $("#fdate2").val('');
            $("#ldate2").val('');
            document.getElementById("fdate2").disabled = true;
            document.getElementById("ldate2").disabled = true;
            
            var rates = document.getElementsByName('view_type2');
         
            var rate_value;
            for(var i = 0; i < rates.length; i++){
               if(rates[i].checked){
                     rate_value = rates[i].value;
                     if(rate_value =="Custom"){
                        document.getElementById("fdate2").disabled = false;
                        document.getElementById("ldate2").disabled = false;
                     }
                     else
                     {
                        form.submit();
                     }
                     
               }
            }	
         }
         
         
         
         
         function getTypeCount(category,type){  
            var qry="<?php echo $qry;?>";
            // alert(category+''+type);
            //var Head = category+'Head';
            var Body = category+'Body';
            //document.getElementById(Head).innerHTML=category+'-'+type;
            //document.getElementById(Body).innerHTML=category+type;
            Body = '#'+category+'Body';
            var view ='#'+category+'Body123';
            
            $.post("Homes/getTypeCount",{Category:category,Type:type,qry:qry},function(data){
               $(view).show();
               $(Body).html(data);
            });
         
         }
         /*
         function selectCampaign(type){
            $("#campaignid").hide();
            if(type ==='outbounds'){
               $("#campaignid").show();
            }  
         }*/
         <?php //if(isset($callType) && $callType ==="outbounds"){?>
         /*
         $(document).ready(function(){
            selectCampaign('<?php //echo $callType;?>');
         });*/
         <?php //}?>
      </script>
      <style>
         .scenario{                
         border: 1px solid gray;
         text-align: center;
         width: 180px;
         margin-left: 130px;
         }
         .wrap {
         width: 100%;
         }
         .wrap table {
         width: 100%;
         table-layout: fixed;
         }
         table tr td {
         padding: 5px;
         border: 1px solid #eee;
         /*width: 200px;*/
         word-wrap: break-word;
         }
         table.head tr td {
         background: #eee;
         }
         .inner_table {
         height: 160px;
         overflow-y: auto;
         }
      </style>
      <?php 
         $cType="inbounds";
         if(isset($callType)){$cType=$callType;}

         // basant       
         
         if(empty($viewType) && empty($viewType2))
         {
            $tabactive_call = "active";
            $tabactive_bill = "";
            
         }
         elseif(empty($viewType))
         {
            $tabactive_call = "";
            $tabactive_bill = "active";
            
         }
         elseif(empty($viewType2))
         {
            $tabactive_call = "active";
            $tabactive_bill = "";
            
         }
         else
         {
            $tabactive_call = "active";
            $tabactive_bill = "";
            
         }
         
      ?>
   <ol class="breadcrumb">
      <li class=""><a href="<?php echo $this->webroot?>homes">Home</a></li>
      <li class="active"><a href="<?php echo $this->webroot?>homes">Dashboard 3</a></li>
   </ol>
   <div class="tabl">
      <ul class="nav nav-tabs">
         <li class="<?php echo $tabactive_call;?>" ><a data-toggle="tab" href="#home">Call</a></li>
         <li class="<?php echo $tabactive_bill;?>"><a data-toggle="tab" href="#menu1">Billing</a></li>
      </ul>
      <div class="tab-content">
         <div id="home" class="tab-pane fade in <?php echo $tabactive_call;?>">
            
            <div class="page-heading">
                  <h1 style="font-weight:500;">Dashboard 3<small></small></h1>
                  <?php if($viewDate !=""){?>
                  <span style="margin-left:29%;font-size:20px;"><?php echo $viewDate;?></span>
                  <?php }?>
                  <?php if(isset($viewType) && !empty($viewType))
                     { if(isset($viewType) && $viewType==="Custom")
                        { 
                           ?>            
                     <span style="margin-left:9%;font-size:20px;color:red;">Showing Result: From <?php echo date_format(date_create($fd),"d-M-Y");?> To <?php echo date_format(date_create($ld),"d-M-Y");?></span>
                     <?php 
                           } 
                           else 
                           { 
                              ?>
                     <!-- <span style="margin-left:29%;font-size:20px;">Showing Result: <?php //echo $viewType;?></span> -->
                     <?php
                           if($viewType=="Today"){
                           
                              $curdate = date_format(date_create(date('Y-m-d')),"d-M-Y"); 
                           ?>
                     <span style="margin-left:9%;font-size:20px;color:red;">Showing Result: <?php echo $curdate;?></span>
                     <?php
                           }
                           if($viewType=="Yesterday"){   
                                          
                              $curdate = date_format(date_create(date('Y-m-d',strtotime("-1 days"))),"d-M-Y"); 
                                          
                              ?>
                     <span style="margin-left:9%;font-size:20px;color:red;">Showing Result: <?php echo $curdate;?></span>
                     <?php   
                           }
                           if($viewType=="Weekly"){             
                              
                              
                              $curdate = date_format(date_create(date('Y-m-d')),"d-M-Y"); 
                              
                              $end = date_format(date_create(date('Y-m-d',strtotime("-6 days"))),"d-M-Y");
                              ?>
                     <span style="margin-left:9%;font-size:20px;color:red;">Showing Result: From <?php echo $end;?> To <?php echo $curdate;?></span>
                     <?php
                           }
                           if($viewType=="Monthly"){            
                              $curdate = date_format(date_create(date('Y-m-d')),"d-M-Y");              
                           
                              $end = date_format(date_create(date('Y-m-d',strtotime("-30 days"))),"d-M-Y");
                              ?>
                     <span style="margin-left:9%;font-size:20px;color:red;">Showing Result: From <?php echo $end;?> To <?php echo $curdate;?></span>
                  <?php
                     }
                     
                     } 
                     }
                     ?>
                  <?php echo $this->Form->create('Home',array('url'=>'/homes3'));?>
                  
                     <?php echo $this->form->end();?>
                     <div class="options"></div>
                  </div>
                  <?php  if($cType ==="inbounds")
                  {?>
                        <div class="container-fluid">
                           <div data-widget-group="group1">
                              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                 <div class="info-tile info-tile-alt tile-green">
                                    <div class="info">
                                       <div class="tile-heading"><span>Opening Balance</span><br>
                                          as on 1<sup>st</sup> of Current Month
                                       </div>
                                       <br>
                                       <div class="tile-body"><span><?php echo $opening_balance; ?></span></div>
                                    </div>
                                    <div class="stats">
                                       <div class="tile-content">
                                          <div id="dashboard-sparkline-indigo"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                 <div class="info-tile info-tile-alt tile-danger">
                                    <div class="info">
                                       <div class="tile-heading"><span>Consumed Value</span><br>MTD</div>
                                       <br>
                                       <div class="tile-body"><span><?php echo empty($consumed)?0:$consumed; ?></span></div>
                                    </div>
                                    <div class="stats" style="padding-top:0px;padding-bottom:0px;padding-right: 0px;">
                                       IB  :<?php echo round($cs_pulse['ib']+(int)$today_cs_pulse['ib']); ?> <br>
                                       OB  :<?php echo round($cs_pulse['ob']+(int)$today_cs_pulse['ob']); ?> <br>
                                       SMS :<?php echo round($cs_pulse['sms']+(int)$today_cs_pulse['sms']); ?> <br>
                                       Email: <?php echo round($cs_pulse['email']+(int)$today_cs_pulse['email']); ?> <br>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                 <div class="info-tile info-tile-alt tile-success">
                                    <div class="info">
                                       <div class="tile-heading"><span>Collection</span><br>MTD</div>
                                       <br>
                                       <div class="tile-body"><span><?php echo $coll_bal; ?></span></div>
                                    </div>
                                    <div class="stats">
                                       <div class="tile-content">
                                          <div id="dashboard-sparkline-indigo"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                                 <div class="info-tile info-tile-alt tile-danger">
                                    <div class="info">
                                       <div class="tile-heading"><span>Closing Balance</span><br>as on Date</div>
                                       <br>
                                       <div class="tile-body"><span><?php echo round($opening_balance-$consumed); ?></span></div>
                                    </div>
                                    <div class="stats">
                                       <div class="tile-content">
                                          <div id="dashboard-sparkline-indigo"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-indigo">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Ans. Calls</span></div>
                                          <div class="tile-body"><span><?php echo empty($Answered)?0:$Answered; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-indigo"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-danger">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Aband Calls</span></div>
                                          <div class="tile-body "><span><?php echo empty($Abandon)?0:$Abandon; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-gray"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-primary">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Tagged Calls</span></div>
                                          <div class="tile-body "><span><?php echo empty($totalTag)?0:$totalTag; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-primary"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-green">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Aband Call Back</span></div>
                                          <div class="tile-body "><span><?php echo empty($AbandCallBack)?0:$AbandCallBack; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-primary"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                             
                            
                           </div>
                        </div>
                  <?php }?>
                  <?php if($cType ==="outbounds")
                  {?>
                        <div class="container-fluid">
                           <div data-widget-group="group1">
                              <div class="row">
                                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-indigo">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Ans. Calls</span></div>
                                          <div class="tile-body"><span><?php echo empty($obAnswered)?0:$obAnswered; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-indigo"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-danger">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Aband Calls</span></div>
                                          <div class="tile-body "><span><?php echo empty($obAbandon)?0:$obAbandon; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-gray"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-primary">
                                       <div class="info">
                                          <div class="tile-heading"><span>Total Tagged Calls</span></div>
                                          <div class="tile-body "><span><?php echo empty($obTotalTag)?0:$obTotalTag; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-primary"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                           </div>
                        </div>
                  <?php }?>    
                  <?php if($cType ==="outbounds" || $cType ==="inbounds")
                  {?> 
                     <div class="container">
                        
                        
                        <div class="row">
                           <div class="col-md-6" style="margin-top: 10px;">
                           <canvas id="connectivity_analysis_today" ></canvas>
                           <script>
                              var xValues = ["Calls Connected", "Calls Not Connected", "Calls Not Attempted"];
                              var yValues = [<?php  echo empty($connectivity_today[0][0]['Answered'])?'0':$connectivity_today[0][0]['Answered'];
                                 echo ",";
                                 echo empty($connectivity_today[0][0]['NotAnswered'])?'0':$connectivity_today[0][0]['NotAnswered'];
                                 ?>];
                              
                              
                              
                              var barColors = [
                              "#00695C",
                              "#009688",
                              "#80CBC4"
                              ];
                              
                              new Chart("connectivity_analysis_today", {
                              type: "doughnut",
                              data: {
                                 labels: xValues,
                                 datasets: [{
                                 backgroundColor: barColors,
                                 
                                 data: yValues
                                 }]
                              },
                              options: {
                                 title: {
                                 display: true,
                                 text: "OB Connectivity Analysis-Today",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                           </div>

                           <div class="col-md-6"  style="margin-top: 10px;">
                              <canvas id="connectivity_analysis_mtd" style="width:100%;"></canvas>
                              <script>
                                 var xValues = ["Calls Connected", "Calls Not Connected", "Calls Not Attempted"];
                                 var yValues = [<?php  echo empty($connectivity_mtd[0][0]['Answered'])?'0':$connectivity_mtd[0][0]['Answered'];
                                 echo ",";
                                 echo empty($connectivity_mtd[0][0]['NotAnswered'])?'0':$connectivity_mtd[0][0]['NotAnswered'];
                                 ?>];
                                 var barColors = [
                                 "#1976D2",
                                 "#A93226",
                                 "#8BC34A"
                                 ];
                                 
                                 new Chart("connectivity_analysis_mtd", {
                                 type: "doughnut",
                                 data: {
                                    labels: xValues,
                                    fontColor: 'red',
                                    datasets: [{
                                    backgroundColor: barColors,
                                    data: yValues,
                                    
                                    }],
                                    
                                 },
                                 
                                 options: {
                                    title: {
                                    display: true,
                                    text: "OB Connectivity Analysis-MTD",
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                    
                                    }
                                 }
                                 });
                              </script>
                           </div>
                        </div>
                     </div>
                     
                     <div class="container">
                        <div class="row">
                                 
                           <div class="col-md-6"  style="margin-top: 10px;">
                           
                           <canvas id="attempt_analysis_today"></canvas>
                           <script>
                              var xValues = ["1st Attempt", "2nd Attempt","3rd Attempt"];
                              var yValues = [<?php  echo empty($attempt_analysis_today[0][0]['attempt1'])?'0':$attempt_analysis_today[0][0]['attempt1'];
                                 echo ",";
                                 echo empty($attempt_analysis_today[0][0]['attempt2'])?'0':$attempt_analysis_today[0][0]['attempt2'];
                                 echo ",";
                                 echo empty($attempt_analysis_today[0][0]['attempt3'])?'0':$attempt_analysis_today[0][0]['attempt3'];?>];
                              
                              
                              
                              var barColors = [
                              "#641E16",
                              "#C0392B",
                              "#E6B0AA"
                              ];
                              
                              new Chart("attempt_analysis_today", {
                              type: "pie",
                              data: {
                                 labels: xValues,
                                 datasets: [{
                                 backgroundColor: barColors,
                                 
                                 data: yValues
                                 }]
                              },
                              options: {
                                 title: {
                                 display: true,
                                 text: "OB Attempt Analysis- Today",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                        </div>

                        <div class="col-md-6"  style="margin-top: 10px;">
                           
                        <canvas id="attempt_analysis_mtd"></canvas>
                           <script>
                              var xValues = ["1st Attempt", "2nd Attempt","3rd Attempt"];
                              var yValues = [<?php  echo empty($attempt_analysis_mtd[0][0]['attempt1'])?'0':$attempt_analysis_mtd[0][0]['attempt1'];
                                 echo ",";
                                 echo empty($attempt_analysis_mtd[0][0]['attempt2'])?'0':$attempt_analysis_mtd[0][0]['attempt2'];
                                 echo ",";
                                 echo empty($attempt_analysis_mtd[0][0]['attempt3'])?'0':$attempt_analysis_mtd[0][0]['attempt3'];?>];
                              
                              var barColors = [
                              "#01579B",
                              "#03A9F4",
                              "#81D4FA"
                              ];
                              
                              new Chart("attempt_analysis_mtd", {
                              type: "pie",
                              data: {
                                 labels: xValues,
                                 datasets: [{
                                 backgroundColor: barColors,
                                 
                                 data: yValues
                                 }]
                              },
                              options: {
                                 title: {
                                 display: true,
                                 text: "OB Attempt Analysis- MTD",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                        </div>

                        </div>
                     </div>

                     <div class="container">
                        <div class="row">
                                 
                           <div class="col-md-6"  style="margin-top: 10px;">
                           
                           <canvas id="connected_voc_success"></canvas>
                           <script>
                              var xValues = ["Today","MTD"];
                              var yValues = [<?php  echo empty($voc_today[0][0]['Today'])?'0':$voc_today[0][0]['Today'];
                                 echo ",";
                                 echo empty($voc_mtd[0][0]['MTD'])?'0':$voc_mtd[0][0]['MTD'];?>];
                              
                              
                              
                              var barColors = [
                              "#689F38",
                              "#AED581"
                              ];
                              
                              new Chart("connected_voc_success", {
                              type: "pie",
                              data: {
                                 labels: xValues,
                                 datasets: [{
                                 backgroundColor: barColors,
                                 
                                 data: yValues
                                 }]
                              },
                              options: {
                                 title: {
                                 display: true,
                                 text: "Connected VoC - Success",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                        </div>

                        <div class="col-md-6"  style="margin-top: 10px;">
                           
                        
                        
                        </div>

                        </div>
                     </div>



                     <div class="container">
                     
               <?php }?>

            </div>
        </div>



<?php 
}
else
{?>
      <style>
         .clientmsg {
         padding: 20px;
         background-color: #2196f3; /* Red */
         color: white;
         margin-bottom: 15px;
         }
         .closebtn {
         margin-left: 15px;
         color: white;
         font-weight: bold;
         float: right;
         font-size: 22px;
         line-height: 20px;
         cursor: pointer;
         transition: 0.3s;
         }
         .closebtn:hover {
         color: black;
         }
      </style>
      <div class="clientmsg">
         <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
         Your profile is under review by our team for further proceedings , We will notify you on same within 24 Hours. Please contact our sales representative or call us @ 001 - 61105555 to know more.
      </div>
<?php }?>
<?php }?>
<script type="text/javascript">
   var blink = document.getElementById('blink');
   setInterval(function() {
     blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
   }, 500);
</script>




     
      <!-- <div id="menu1" class="tab-pane fade in <?php //echo $tabactive_bill;?>">
         <h3>Billing</h3>
         
      </div> -->
   </div>

</div>

   