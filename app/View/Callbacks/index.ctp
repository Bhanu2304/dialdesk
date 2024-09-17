<script>
   $(function () {
      $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
   });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<?php  if($this->Session->read('role') =="admin")
{
    
   ?>
      <script>
         function viewClient(){
            $("#view_client_form").submit();	
         }

         function validateExport(url){
            $(".w_msg").remove();
            
            var client_id=$("#client_id").val();
            var fdate=$("#fdate").val();
            var ldate=$("#ldate").val();
            
            if(client_id ===""){
               $("#error").html('<span class="w_msg err" style="color:red;">Please select client.</span>');
               return false;
            }

            else if(fdate ===""){
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
                     $('#view_client_form').attr('action','<?php echo $this->webroot;?>Callbacks/');
               }
               if(url ==="view"){
                     $('#view_client_form').attr('action','<?php echo $this->webroot;?>Callbacks/');
               }
               $('#view_client_form').submit();
               return true;
            }
         }
      </script>
      <!-- <script type="text/javascript" src="https://www.google.com/jsapi"></script> -->
<ol class="breadcrumb">                                
   <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
   <li><a href="#">Agent Calling Allocation</a></li>
   <li class="active"><a href="#">CallBack Details</a></li>
</ol>
<div class="page-heading">            
   <h1>CallBack Details</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
         <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
               <h2>CallBack Details</h2>
            </div>
            <div class="panel-body">
            <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
         <div class="form-group">
            <?php echo $this->Form->create('Home',array('url'=>'/callbacks','id'=>'view_client_form')); ?>
            <div class="col-sm-3">
               <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client_id','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
            </div>
            <div class="col-sm-2">
                  <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1'));?>
            </div>
            <div class="col-sm-2">
                  <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker1'));?>
            </div>
            <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                  <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
            </div>
            
            <?php echo $this->Form->end(); ?>
         </div>
      </div>
      </div>
      <br/>
      <br/>
<?php }?>

<div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2> View CallBack Details </h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Client</th>
                            <th>Agent </th>
                            <th>Phone </th>
                            <th>CallBack Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($callbacklist as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            
                            <td><?php echo $row['rm']['company_name'];?></td>
                            <td><?php echo "{$row['am']['displayname']}  ({$row['am']['username']})";?></td>
                            <td><?php echo $row['cm']['phone_no'];?></td>
                            <?php 
                            $callback_time = $row['cm']['callback_time'];
                            $strtotime = strtotime($callback_time);
                            $datefmt = "d M Y H:i";
                            $new_date = date($datefmt,$strtotime);
                            
                            
                            ?>
                            <td><?php echo $new_date;?></td>
                             
                        </tr>

                    <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>



     
      <!-- <div id="menu1" class="tab-pane fade in <?php //echo $tabactive_bill;?>">
         <h3>Billing</h3>
         
      </div> -->
      
   </div>

</div>

   