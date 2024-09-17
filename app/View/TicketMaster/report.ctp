<?php ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>	
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/jsapi"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/loader.js"></script>

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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>TicketMaster/export_complaint');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>TicketMaster/report');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Operations</a></li>
    <li class="active"><a href="#">Complaint Dashboard</a></li>
</ol>
<div class="page-heading">            
    <h1>Complaint Dashboard</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Complaint Dashboard</h2>
            </div>
            <div class="panel-body">
            <div class="container">
             <div class="col-md-8">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('TicketMaster',array('action'=>'report','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                     
                        <!-- <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div> -->
                       
                    </div>
                <?php $this->Form->end(); ?>
             </div>
                 <div class="col-md-4">  
                 <canvas id="closure_status" ></canvas>
                           <script>
                              var xValues = ["Satisfied", "Not Satisfied"];
                              var yValues = [<?php  echo empty($Closure_status[0][0]['SATISFIED'])?'0':$Closure_status[0][0]['SATISFIED'];
                                 echo ",";
                                 echo empty($Closure_status[0][0]['NOTSATISFIED'])?'0':$Closure_status[0][0]['NOTSATISFIED'];?>];
                              
                              
                              
                              var barColors = [
                              "#3498DB",
                              "#F4D03F"
                              ];
                              
                              new Chart("closure_status", {
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
                                 text: "Whatsapp Ticket Closure",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                 </div>
            </div>

            </div>
        </div> 
        
        <?php if(isset($dataArr) && !empty($dataArr)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Complaint Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
	<tr>
		<th>Issue</th>			
		<th>Open</th>
                <th>Close</th>
                <th>Total</th>
                <th>Within TAT</th>
                <th>Beyond TAT</th>
                <!-- <th>Comapny Name</th> -->
	</tr>
	
</thead>
<tbody>
	<?php foreach($dataArr as $data) { ?>
	<tr>
		<td><?php echo $data['dialdesk_whatsapp_ticket']['concern1']; ?></td>
		<td><?php echo $data['0']['OPEN']; ?></td>
        <td><?php echo $data['0']['CLOSE']; ?></td>
        <td><?php echo $data['0']['Total']; ?></td>
                <td><?php echo $data['0']['INTAT']; ?></td>
                <td><?php echo $data['0']['OUTTAT']; ?></td>
                <!-- <td></td> -->
                
	</tr>		
	<?php } ?>						
</tbody>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




