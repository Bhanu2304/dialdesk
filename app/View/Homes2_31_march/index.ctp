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
            <?php echo $this->Form->create('Home',array('url'=>'/homes2','id'=>'view_client_form')); ?>
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
      <li class=""><a href="<?php echo $this->webroot?>homes2">Home</a></li>
      <li class="active"><a href="<?php echo $this->webroot?>homes2">Dashboard</a></li>
   </ol>
   <div class="tabl">
      <ul class="nav nav-tabs">
         <li class="<?php echo $tabactive_call;?>" ><a data-toggle="tab" href="#home">Call</a></li>
         <li class="<?php echo $tabactive_bill;?>"><a data-toggle="tab" href="#menu1">Billing</a></li>
      </ul>
      <div class="tab-content">
         <div id="home" class="tab-pane fade in <?php echo $tabactive_call;?>">
            
            <div class="page-heading">
                  <h1 style="font-weight:500;">Dashboard<small></small></h1>
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
                  <?php echo $this->Form->create('Home',array('url'=>'/homes2'));?>
                  <div class="search-dashbord" >
                     <p>
                        <input type="radio" <?php if(isset($viewType) && $viewType==="Today"){echo "checked='checked'";}?> onclick="getType(this.form);" name="view_type" value="Today" />Today
                        <input type="radio" <?php if(isset($viewType) && $viewType==="Yesterday"){echo "checked='checked'";}?> onclick="getType(this.form);" name="view_type" value="Yesterday" />Yesterday
                        <input type="radio" <?php if(isset($viewType) && $viewType==="Weekly"){echo "checked='checked'";}?> onclick="getType(this.form);" name="view_type" value="Weekly" />Weekly
                        <input type="radio" <?php if(isset($viewType) && $viewType==="Monthly"){echo "checked='checked'";}?> onclick="getType(this.form);" name="view_type" value="Monthly" />Monthly
                        <input type="radio" <?php if(isset($viewType) && $viewType==="Custom"){echo "checked='checked'";}?> onclick="getType(this.form);" name="view_type" value="Custom" />Custom  
                     </p>
                     <p>
                        <select onchange="selectCampaign(this.value)" name="callType"  style="width:90px;" >
                           <option <?php if(isset($cType) && $cType==="inbounds"){echo "selected='selected'";}?> value="inbounds">Inbounds</option>
                           
                              <option <?php if(isset($cType) && $cType==="outbounds"){echo "selected='selected'";}?> value="outbounds">Outbounds</option>
                           
                        </select>
                        <!--
                           <select name="campaignid" style="width:90px;display:none;" id="campaignid">
                              <option value="">Select</option>
                              <?php //foreach ($Campaign as $key => $value){?>
                                 <option <?php //if(isset($campaignid) && $campaignid==$key){echo "selected='selected'";}?> value="<?php //echo $key;?>"><?php //echo $value;?></option>
                              <?php //} ?>
                           </select>
                           -->
                        <input type="text" name="fdate" style="width:90px;" value="<?php echo isset($fd)?$fd:"";?>" id="fdate" class="date-picker" placeholder="From" autocomplete="off"/>
                        <input type="text" name="ldate" style="width:90px;" value="<?php echo isset($ld)?$ld:"";?>" id="ldate" class="date-picker" placeholder="To" autocomplete="off"/>
                        <input type="submit" value="Search" /> 
                     </p>
                     </div>
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
                           <div class="col-xs-12">
                              <h2>ACTIVE SERVICES</h2>
                           </div>
                        </div>
                        <div class="row">
                           <?php if(isset($planmaster_arr) && !empty($planmaster_arr)) 
                              { 
                                 foreach($planmaster_arr as $pma) 
                                 {
                                 ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <div class="info-tile info-tile-alt tile-indigo">
                                          <div class="info">
                                             <div class="tile-heading"><span> MY PLAN </span></div>
                                             <div class="tile-body"><span><?php echo $pma['plan_master']['PlanName'];?></span></div>
                                             <div class="tile-heading"><span> SUBSCRIPTION</span></div>
                                             <div class="tile-body"><span><?php if($pma['plan_master']['PeriodType']=='Quater') {echo 'Quarter';} else  echo $pma['plan_master']['PeriodType'];?></span></div>
                                             <div class="tile-heading"><span> SUBSCRIPTION Value</span></div>
                                             <div class="tile-body"><span><?php echo $pma['plan_master']['CreditValue'];?></span></div>
                                             <div class="tile-heading"><span> INBOUND CALL - DAY CHARGES</span></div>
                                             <div class="tile-body"><span><?php echo $pma['plan_master']['InboundCallCharge'];?> Rs./Minutes</span></div>
                                             <div class="tile-heading"><span> OutBOUND CALL CHARGES</span></div>
                                             <div class="tile-body"><span><?php echo $pma['plan_master']['OutboundCallCharge'];?> Rs./Minutes</span></div>
                                             <div class="tile-heading"><span> PAYMENT MODE</span></div>
                                             <div class="tile-body"><span><?php echo " "; ?></span></div>
                                          </div>
                                       </div>
                                    </div>
                                 <?php 
                                 } 
                              } else { echo "<p style='text-align: center;color: red;font-weight: 500; font-size: large; '>Active Plan Not Found!!</p>"; } ?>
                        </div>
                        <?php if($cType ==="outbounds" ) { ?>
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
                                 text: "Connectivity Analysis-Today",
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
                                    text: "Connectivity Analysis-MTD",
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                    
                                    }
                                 }
                                 });
                              </script>
                           </div>
                        </div>  
                       
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
                                 text: "Attempt Analysis- Today",
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
                                 text: "Attempt Analysis- MTD",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                        </div>
                        </div>
                        <?php } ?>
                        
                        
                     

                     <div class="row">
                           <div class="col-md-6" style="margin-top: 10px;">
                              <h2>Ticket By Source</h2>
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <td>Ticket By Source</td>
                                       <td>Open</td>
                                       <td>Close</td>
                                       <td>Overdue (as on date)</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td>Call</td>
                                       <td><?php if(!empty($ticket_by_source_call_array[0][0]['OPEN'])) {echo $ticket_by_source_call_array[0]['OPEN'];} else {echo 0;}?></td>
                                       <td><?php if(!empty($ticket_by_source_call_array[0][0]['CLOSE'])) {echo $ticket_by_source_call_array[0]['CLOSE'];} else {echo 0;}?></td>
                                       <td><?php if(!empty($ticket_by_source_call_array[0][0]['overdue'])) {echo $ticket_by_source_call_array[0]['overdue'];} else {echo 0;}?></td>
                                    </tr>
                                    <tr>
                                       <td>Email</td>
                                       <td>0</td>
                                       <td>0</td>
                                       <td>0</td>
                                    </tr>
                                    <tr>
                                       <td>Whatsapp</td>
                                       <td>0</td>
                                       <td>0</td>
                                       <td>0</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <div class="col-md-6"  style="margin-top: 10px;">
                              <canvas id="myChart" style="width:100%;"></canvas>
                              <script>
                                 var xValues = ["Abandan", "Total"];
                                 var yValues = [<?php echo empty($Abandon)?0:$Abandon; ?>, <?php echo empty($Answered)?0:$Answered; ?>];
                                 var barColors = [
                                 "#2E8B57",
                                 "#90EE90"
                                 ];
                                 
                                 new Chart("myChart", {
                                 type: "pie",
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
                                    text: "Monthly Call Analysis",
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                    
                                    }
                                 }
                                 });
                              </script>
                           </div>
                        </div>
                     
                     <div class="container">
                        <div class="row">
                           <div class="col-md-12"  style="margin-top: 10px;">
                              <canvas id="myChart4" style=""></canvas>
                              <script>
                                 var ctx = document.getElementById("myChart4").getContext('2d');
                                 var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                    
                                    
                                    labels: [<?php foreach($graph_date_wise as $dy) {print_r("'".date_format(date_create($dy[0]['gdate']),"d-M-Y")."',");} ?>],
                                    datasets: [ {
                                                label: 'Abandon',
                                                backgroundColor: "#e84e40",
                                                data: [<?php foreach($graph_date_wise as $dy) {print_r($dy[0]['Abandon'].",");} ?>],
                                             }, {
                                                label: 'Answered',
                                                backgroundColor: "#8bc34a",
                                                data: [<?php foreach($graph_date_wise as $dy) {print_r($dy[0]['Answered'].",");} ?>],
                                             }
                                             ],
                                    },
                                 options: {
                                    tooltips: {
                                       displayColors: true,
                                       callbacks:{
                                       mode: 'x',
                                       },
                                    },
                                    scales: {
                                       xAxes: [{
                                       stacked: true,
                                       display:true,
                                       gridLines: {
                                             display: false,
                                       },
                                       barThickness: 16,  // number (pixels) or 'flex'
                                       maxBarThickness: 18, // number (pixels)
                                       }],
                                                
                                 
                                       yAxes: [{
                                       stacked: true,
                                       beginAtZero: true,
                                       ticks: {
                                                   
                                                   // count : 10,
                                                   
                                                   // callback:((value, index, ticks) => {
                                                   // return value * 100 + '%'; // convert it to percentage
                                                   // }),
                                 
                                                   min: 0,
                                                   max: this.max,// Your absolute max value
                                                   callback: function (value) {
                                                   return (value / this.max * 100).toFixed(0) + '%'; // convert it to percentage
                                                   },
                                 
                                 
                                                },
                                                scaleLabel: {
                                                               display: true,
                                                               labelString: "Percentage"
                                                            }              
                                       ,
                                       type: 'linear',
                                       }]
                                 
                                 
                                 
                                    },
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    legend: { position: 'bottom' },
                                    }
                                 });
                                 
                              </script>
                           </div>
                           <div class="col-md-6"  style="margin-top: 10px;">
                              <canvas id="bar-chart-grouped1" ></canvas>
                              <?php $new_array = array("wk1","wk2","wk3","wk4","mtd");?>
                              <?php $week_labels = "'".implode("','",array_keys($week_divider))."'";    ?> 
                              <script>
                                 new Chart(document.getElementById("bar-chart-grouped1"), 
                                 {
                                    type: 'bar',
                                    data: {
                                    labels: [<?php echo $week_labels; ?>],
                                    datasets: [
                                       <?php 
                                    // $color_master =array(1=>'#008000','2'=>'#006400',3=>'#90EE90',4=>'#90EE90',5=>'#90EE90');
                                    
                                    $color_master =array(1=>"#1E90FF",2=>"#4169E1",3=>"#90EE90",4=>"#006400",5=>"#008000", 6=>"#0074D9", 7=>"#FF4136", 8=>"#2ECC40", 9=>"#FF851B", 10=>"#7FDBFF", 11=>"#B10DC9", 12=>"#FFDC00", 13=>"#001f3f", 14=>"#39CCCC", 15=>"#01FF70", 16=>"#85144b", 17=>"#F012BE", 18=>"#3D9970", 19=>"#111111", 20=>"#AAAAAA");
                                    $week_count = 5; 
                                    $b = 1;
                                    foreach($category_arr as $category) 
                                    {
                                       $values_list = array();
                                       foreach($week_divider as $a) { $values_list[] = empty($week_arr[$a][$category])?0:$week_arr[$a][$category];}  ?>
                                 
                                                { 
                                                   label: "<?php echo $category;?>",
                                                   backgroundColor: "<?php echo $color_master[$b++]; ?>",
                                                   data: [ <?php echo implode(",",$values_list); ?> ]
                                                },
                                 
                                       <?php } ?>
                                       
                                    ]
                                    },
                                    options: {
                                    title: {
                                       display: true,
                                       text: 'Ticket Case Analysis',
                                       fontColor: ['rgba(0,0,0)'],
                                       fontSize:  16,
                                    },
                                       legend: { position: 'right' },
                                    }
                                 });
                              </script>
                           </div>
                           <div class="col-md-6"  style="margin-top: 10px;">
                              <canvas id="T_MTD" style="width:100%;"></canvas>
                                 <script>
                                    var ctx = document.getElementById("T_MTD").getContext('2d');
                                    var myChart_T_MTD = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                       labels: ["Open","Close"],
                                       datasets: [{
                                          label: 'Today',
                                          backgroundColor: "#008000",
                                             data: [<?php foreach($tctm_today_row as $ttr) {print_r($ttr[0][0]['TOpen'].",".$ttr[0][0]['TClose']);};?>],
                                          //data: [345,789],
                                       }, {
                                          label: 'MTD',
                                          backgroundColor: "#90EE90",
                                          data: [<?php foreach($tctm_mtd_row as $tmr) {print_r($tmr[0][0]['TOpen'].",".$tmr[0][0]['TClose']);};?>],
                                          //data: [456,678],
                                       }
                                       ],
                                    },
                                    options: {
                                       tooltips: {
                                       displayColors: true,
                                       callbacks:{
                                       mode: 'x',
                                    },
                                    },
                                    scales: {
                                    xAxes: [{
                                       stacked: true,
                                       gridLines: {
                                       display: false,
                                       }
                                    }],
                                    yAxes: [{
                                       stacked: true,
                                       ticks: {
                                       beginAtZero: true,
                                       },
                                       type: 'linear',
                                    }]
                                    },
                                       responsive: true,
                                       maintainAspectRatio: false,
                                       legend: { position: 'bottom' },
                                    
                                    title: {
                                       display: true,
                                       text: 'Ticket Case Analysis',
                                       fontColor: ['rgba(0,0,0)'],
                                       fontSize:  16,
                                    },
                                       legend: { position: 'top' },
                                    }
                                    });
                                    
                                 </script>
                           </div>
                           <div class="col-md-6"  style="margin-top: 10px;">
                           
                              <canvas id="myChartopenTicket" ></canvas>
                              <script>
                                 var xValues = ["In TAT", "Out of TAT"];
                                 var yValues = [<?php  echo empty($open_RecArr[0][0]['openintat'])?'0':$open_RecArr[0][0]['openintat'];
                                    echo ",";
                                    echo empty($open_RecArr[0][0]['openouttat'])?'0':$open_RecArr[0][0]['openouttat'];?>];
                                 
                                 
                                 
                                 var barColors = [
                                 "#1E90FF",
                                 "#e57438"
                                 ];
                                 
                                 new Chart("myChartopenTicket", {
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
                                    text: "Open Ticket Analysis",
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                    }
                                 }
                                 });
                              </script>
                           </div>
                           <div class="col-md-6" style="margin-top: 10px;">
                              <canvas id="myChartcloseTicket" ></canvas>
                              <script>
                                 var xValues = ["In TAT", "Out of TAT"];
                                 // var yValues = [<?php foreach($close_RecArr as $close_rec) { echo $close_rec[0][0]['intat'].",".$close_rec[0][0]['outtat'] ;};?>];
                                 var yValues = [<?php  echo empty($open_RecArr[0][0]['intat'])?'0':$open_RecArr[0][0]['intat'];
                                    echo ",";
                                    echo empty($open_RecArr[0][0]['outtat'])?'0':$open_RecArr[0][0]['outtat'];?>];
                                       
                                 var barColors = [
                                 "#1E90FF",
                                 "#e57438"
                                 ];
                                 
                                 new Chart("myChartcloseTicket", {
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
                                    text: "Close Ticket Analysis",
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                    }
                                 }
                                 });
                              </script>
                           </div>
                        </div>
                     </div>

                     
                     
               <?php }?>

            </div>
        </div>


      <!-- For Billing Tab start -->

      <div id="menu1" class="tab-pane fade in <?php echo $tabactive_bill;?>">        
            
            <div class="page-heading">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                           <h1 style="font-weight: 500;">Billing Dashboard<small></small></h1>
                     </div>
                  </div>
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
                             
                           </div>
                        </div>
                  <?php }?>
                  <?php if($cType ==="outbounds")
                  {?>
                        <div class="container-fluid">
                           <div data-widget-group="group1">
                           
                              
                           </div>
                        </div>
                  <?php }?>    
                  <?php if($cType ==="outbounds" || $cType ==="inbounds")
                  {?> 
                     
                   
                     <div class="page-heading">
                       
                        <h3><?php echo "From: $fromBilling To: $toBilling"; ?></h3>
                        <p style="font-size:20px;"><b style="color:red;" id="blink">Note:-</b><b>The usage and values are for the complete services offered including Inbound, Outbound, SMS, E-Mail etc.</b></p>
                        <?php if($viewDate2 !=""){?>
                        <span style="margin-left:29%;font-size:20px;"><?php echo $viewDate;?></span>
                        <?php }?>
                        <?php echo $this->Form->create('Home',array('url'=>'/homes2','id'=>'HomeIndexForm2'));?>
                        <div class="search-dashbord" >
                           <p>
                              <input type="radio" <?php if(isset($viewType2) && $viewType2==="Today"){echo "checked='checked'";}?> onclick="getType2(this.form);" name="view_type2" value="Today" />Today
                              <input type="radio" <?php if(isset($viewType2) && $viewType2==="Yesterday"){echo "checked='checked'";}?> onclick="getType2(this.form);" name="view_type2" value="Yesterday" />Yesterday
                              <input type="radio" <?php if(isset($viewType2) && $viewType2==="Weekly"){echo "checked='checked'";}?> onclick="getType2(this.form);" name="view_type2" value="Weekly" />Weekly
                              <input type="radio" <?php if(isset($viewType2) && $viewType2==="Monthly"){echo "checked='checked'";}?> onclick="getType2(this.form);" name="view_type2" value="Monthly" />Monthly
                              <input type="radio" <?php if(isset($viewType2) && $viewType2==="Custom"){echo "checked='checked'";}?> onclick="getType2(this.form);" name="view_type2" value="Custom" />Custom  
                           </p>
                           <p>
                              <!--
                                 <select name="campaignid" style="width:90px;display:none;" id="campaignid">
                                    <option value="">Select</option>
                                    <?php //foreach ($Campaign as $key => $value){?>
                                       <option <?php //if(isset($campaignid) && $campaignid==$key){echo "selected='selected'";}?> value="<?php //echo $key;?>"><?php //echo $value;?></option>
                                    <?php //} ?>
                                 </select>
                                 -->
                              <input type="text" name="fdate2" style="width:90px;" value="<?php echo isset($fd2)?$fd2:"";?>" id="fdate2" class="" placeholder="From" />
                              <input type="text" name="ldate2" style="width:90px;" value="<?php echo isset($ld2)?$ld2:"";?>" id="ldate2" class="" placeholder="To" />
                              <input type="submit" value="Search" /> 
                           </p>
                        </div>
                        <?php echo $this->form->end();?>
                        <div class="options2"></div>
                     </div>
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-xs-12">
                              <?php 
                                 date_default_timezone_set('Asia/Kolkata');
                                 
                                 if (date('m') <= 3) {
                                    $financial_year = (date('Y')-1) . '-' . date('Y');
                                 } else {
                                    $financial_year = date('Y') . '-' . (date('Y') + 1);
                                 }
                                 ?>
                              <h2>Ledger Balance(FY <?php echo $financial_year;?>)</h2>
                           </div>
                        </div>
                        <?php 
                           //print_r($index_ledger_data);exit;
                           $record = array();
                           foreach($index_ledger_data as $client=>$record1) 
                           {  $record=$record1; }//print_r($record);exit;
                              
                              ?>
                                 
                        
                        
                     <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-green">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Opening Balance</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo $record['op2_ledger']; ?></span></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-indigo">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Billed</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo $record['bill2_ledger']; ?></span></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-success">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Paid</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo $record['coll2_ledger']; ?></span></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-danger">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Outstanding</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo round($record['op2_ledger']+$record['bill2_ledger']-$record['coll2_ledger']); ?></span></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12">
                           <h2>Usage Value Balance</h2>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-green">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Opening Balance</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo $record['closing_credit']; ?></span></div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-indigo">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Value added</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo number_format( $record['fr_release_credit'],2); ?></span></div>
                              </div>
                           </div>
                        </div>
                        <?php 
                           $mothfirstdate = date("Y-m-01"); 
                           //$currntdate = date("Y-m-d"); 
                           $currntdate = date('Y-m-d',strtotime("-1 days"));
                           // $currntdate = "2022-07-06";
                           ?>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <a target="_blank" href="<?php echo $this->webroot.'app/webroot/billing_statement/billing_tables.php?FromDate='.$fromConsume.'&ToDate='.$toConsume.'&ClientId='.$ClientId; ?>">
                              <div class="info-tile info-tile-alt tile-success">
                                 <div class="info">
                                    <div class="tile-heading">
                                       <span>Today Consumed Value</span>
                                       <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                    </div>
                                    <br>
                                    <div class="tile-body"><span><?php echo number_format($record['cs_bal'],2); ?></span></div>
                                 </div>
                                 <div class="stats" style="padding-top:0px;padding-bottom:0px;padding-right: 0px;display:none;">
                                    IB  :<?php echo round($cs_pulse['ib']+(int)$today_cs_pulse['ib']); ?> <br>
                                    OB  :<?php echo round($cs_pulse['ob']+(int)$today_cs_pulse['ob']); ?> <br>
                                    SMS :<?php echo round($cs_pulse['sms']+(int)$today_cs_pulse['sms']); ?> <br>
                                    Email: <?php echo round($cs_pulse['email']+(int)$today_cs_pulse['email']); ?> <br>
                                 </div>
                              </div>
                           </a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                           <div class="info-tile info-tile-alt tile-danger">
                              <div class="info">
                                 <div class="tile-heading">
                                    <span>Closing Balance</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                 </div>
                                 <br>
                                 <div class="tile-body"><span><?php echo number_format($record['closing_credit']+$record['fr_release_credit']-$record['cs_bal'],2); ?></span></div>
                              </div>
                           </div>
                        </div>
                     </div>
               <?php }?>

            </div>

        </div>

      <!-- End Here Billing Tab -->
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

   