<script> 		
	function validateExport(){
		$(".w_msg").remove();
		var fdate=$("#fdate").val();
		var ldate=$("#ldate").val();
			
		if ((new Date(fdate).getTime()) <= (new Date(ldate).getTime())) {return true;} 
		else {
			$("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
			return false;
		}
	}
</script>
<script>   
function redirect(path){
    window.location=path;
}
</script>
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">MIS and Report</a></li>
        <li class="active"><a href="#">SLA Reports</a></li>
    </ol>
    <div class="page-heading">            
        <h1>SLA Reports</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>SLA Reports</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                     <div class="form-group">
                        
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/MisReports/export_tat_mis')"> TAT MIS</div>
                     
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/MisReports/export_tagging_mis')"> Tagging MIS</div>
                     
                    <!--<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/MisReports/export_time_wise_mis')"> Time Wise MIS</div>-->
                     
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/MisReports/export_agent_wise_mis')"> Agent Wise MIS</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/MisReports/category_reports')"> Call Scenario MIS</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/MisReports/export_esclation_level_mis')"> Escalation Level MIS</div>
                  <!--  <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/CustomizedMisReports')"> Custmize In Call Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/ObCustomizedMisReports')"> Custmized Out Call Report</div>-->
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/Reports')"> Call Tagging Summary</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/IncallactionReports')"> In Call Action MIS</div>
                    
                     
                    </div>
                	<?php $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div> <!-- .container-fluid -->

