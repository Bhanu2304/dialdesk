<?php //echo $viewType2;exit; ?>
<style>
   .nav-tabs>li.active>a,
   .nav-tabs>li.active>a:hover,
   .nav-tabs>li.active>a:focus {
      color: #9e9e9e;
      background-color: #000 !important;
      border: 1px solid #eeeeee;
      border-bottom-color: transparent;
      cursor: default;
   }

   .nav-tabs {
      width: 12% !important;
   }

   .nav>li>a {
      padding: 14px 18px !important;
   }
</style>
<script>
   $(function () {
      $("#fdate2").datepicker({
         dateFormat: 'yy-mm-dd',
         changeYear: false,
         changeMonth: false,
         'minDate': new Date( <?php echo date('Y'); ?> , <?php echo date('m'); ?> -1, 1),
         'maxDate': new Date( <?php echo date('Y'); ?> , <?php echo date('m'); ?> -1, <?php echo date('t'); ?> )

      });
      $("#ldate2").datepicker({
         dateFormat: 'yy-mm-dd',
         changeYear: false,
         changeMonth: false,
         'minDate': new Date( <?php echo date('Y'); ?> , <?php echo date('m'); ?> -1, 1),
         'maxDate': new Date( <?php echo date('Y'); ?> , <?php echo date('m'); ?> -1, <?php echo date('t'); ?> )
      });
   });
   //https://bootstrap-datepicker.readthedocs.io/en/latest/options.html#beforeshowyear
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<?php  if($this->Session->read('role') =="admin")
{
   $companyid=$this->Session->read('companyid');     
   ?>
<script>
   function viewClient() {
      $("#view_client_form").submit();
   }
</script>
<!-- <script type="text/javascript" src="https://www.google.com/jsapi"></script> -->
<style>
   thead>tr>td {
      background: cornflowerblue;
      color: white;
      font-weight: 500;
   }

   .odd,
   .even {
      border: 1px solid cornflowerblue;
      background: blue !important;
      color: white;
   }
</style>

<?php }?>
<?php  if($this->Session->read('companyid') !="")
{?>
<?php if($this->Session->read('clientstatus') ==="A")
{
?>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/jsapi"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/loader.js"></script>
<script type="text/javascript">
   google.load('visualization', '1', {
      'packages': ['columnchart', 'piechart', 'table']
   });
   $(document).ready(function () {
      document.getElementById("fdate").disabled = true;
      document.getElementById("ldate").disabled = true;
      document.getElementById("fdate2").disabled = true;
      document.getElementById("ldate2").disabled = true; <?php
      if (isset($viewType) && $viewType === "Custom") {
         ?>
         document.getElementById("fdate").disabled = false;
         document.getElementById("ldate").disabled = false; <?php
      } ?>
      <?php
      if (isset($viewType2) && $viewType2 === "Custom") {
         ?>
         document.getElementById("fdate2").disabled = false;
         document.getElementById("ldate2").disabled = false; <?php
      } ?>
   });


   function getType(form) {
      $("#fdate").val('');
      $("#ldate").val('');
      document.getElementById("fdate").disabled = true;
      document.getElementById("ldate").disabled = true;

      var rates = document.getElementsByName('view_type');

      var rate_value;
      for (var i = 0; i < rates.length; i++) {
         if (rates[i].checked) {
            rate_value = rates[i].value;
            if (rate_value == "Custom") {
               document.getElementById("fdate").disabled = false;
               document.getElementById("ldate").disabled = false;
            } else {
               form.submit();
            }
            // $("#tabactive").addClass("active"); 
         }
      }
   }


   function getType2(form) {
      $("#fdate2").val('');
      $("#ldate2").val('');
      document.getElementById("fdate2").disabled = true;
      document.getElementById("ldate2").disabled = true;

      var rates = document.getElementsByName('view_type2');

      var rate_value;
      for (var i = 0; i < rates.length; i++) {
         if (rates[i].checked) {
            rate_value = rates[i].value;
            if (rate_value == "Custom") {
               document.getElementById("fdate2").disabled = false;
               document.getElementById("ldate2").disabled = false;
            } else {
               form.submit();
            }

         }
      }
   }




   function getTypeCount(category, type) {
      var qry = "<?php echo $qry;?>";
      // alert(category+''+type);
      //var Head = category+'Head';
      var Body = category + 'Body';
      //document.getElementById(Head).innerHTML=category+'-'+type;
      //document.getElementById(Body).innerHTML=category+type;
      Body = '#' + category + 'Body';
      var view = '#' + category + 'Body123';

      $.post("Homes/getTypeCount", {
         Category: category,
         Type: type,
         qry: qry
      }, function (data) {
         $(view).show();
         $(Body).html(data);
      });

   }


</script>
<style>
   .scenario {
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
   <li class="active"><a href="<?php echo $this->webroot?>homes">Dashboard</a></li>
</ol>

      

         <div class="page-heading">
            <h1 style="font-weight:500;">Dashboard<small></small></h1>
           
            <?php echo $this->Form->create('Home',array('url'=>'/Dashboards'));?>
            <div class="search-dashbord">
               <p>
                  <input type="radio" <?php if(isset($viewType) && $viewType==="Today"){echo "checked='checked'";}?>
                     onclick="getType(this.form);" name="view_type" value="Today" style="margin-left: 36px;"/>Today
                  <input type="radio" <?php if(isset($viewType) && $viewType==="Custom"){echo "checked='checked'";}?>
                     onclick="getType(this.form);" name="view_type" value="Custom" />Custom
               </p>
               <p>
               
                  <input type="text" name="fdate" style="width:90px;" value="<?php echo isset($fd)?$fd:"";?>" id="fdate"
                     class="date-picker" placeholder="From" autocomplete="off" />
                  <input type="text" name="ldate" style="width:90px;" value="<?php echo isset($ld)?$ld:"";?>" id="ldate"
                     class="date-picker" placeholder="To" autocomplete="off" />
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
               <div class="row">
                                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-green">
                                       <div class="info">
                                          <div class="tile-heading"><span>Non - Active Client</span></div>
                                          <div class="tile-body"><span><?php echo empty($non_active)?0:$non_active; ?></span></div>
                                       </div>
                                       <div class="stats">
                                          <div class="tile-content">
                                             <div id="dashboard-sparkline-indigo"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="info-tile info-tile-alt tile-green">
                                       <div class="info">
                                          <div class="tile-heading"><span>Campaign_Id Not Mapped</span></div>
                                          <div class="tile-body "><span><?php echo empty($non_mapped_client)?0:$non_mapped_client; ?></span></div>
                                       </div>
                  </div></div></div>



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
                        
                        <div class="stats">
                           <div class="tile-content">
                              <div id="dashboard-sparkline-indigo"></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                     <div class="info-tile info-tile-alt tile-danger">
                        
                        <div class="stats">
                           <div class="tile-content">
                              <div id="dashboard-sparkline-gray"></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                     <div class="info-tile info-tile-alt tile-primary">
                        
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
            <div class="col-md-2"  style="margin-top: 10px;">
                              
                              </div>
                           <div class="col-md-8" style="margin-top: 10px;">
                              <h2 class="text-center">Retail Wise Dashboard</h2>
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <td>Client Name</td>
                                       <td>Calls</td>
                                       
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php 
                                    foreach($client_info_arr as $a){?>
                                       <tr>
                                       
                                       <td><?php echo $a['RegistrationMaster']['company_name']; ?></td>
                                       <td><?php echo '0'; ?></td>  
                                    
                                    </tr>
                                   <?php  } ?> 

                                   
                         

                                    
                                    
                                 </tbody>
                              </table>
                           </div>
                           <div class="col-md-2"  style="margin-top: 10px;">
                              
                           </div>
              
            </div>

            <?php }?>

         </div>
      

      <?php 
}
else
{?>
      <style>
         .clientmsg {
            padding: 20px;
            background-color: #2196f3;
            /* Red */
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
         Your profile is under review by our team for further proceedings , We will notify you on same within 24 Hours.
         Please contact our sales representative or call us @ 001 - 61105555 to know more.
      </div>
      <?php }?>
      <?php }?>
      <script type="text/javascript">
         var blink = document.getElementById('blink');
         setInterval(function () {
            blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
         }, 500);
      </script>

