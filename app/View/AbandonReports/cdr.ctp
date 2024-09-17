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
        <li class="active"><a href="#">Analysis Reports</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Analysis Reports</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Analysis Reports</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                    <div class="form-group">
                        
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/aband')">Abandon Report</div>
                        
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/view')">SUMMARY OF CALL REPORT</div>

                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/slot_wise')">Slot wise/Day wise Utilization</div>
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/agent_wise')">Agents wise/Day wise Utilization</div>
                        <!--<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/customer_wise')">Customer wise Density</div>-->
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/skills')">Skilled Report</div>
                        <!--<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/client_live_agent')">Agents Skill Mapped</div>-->
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/roster_view')">Roster Planning</div>
                        <!--<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/skill_wise_excel')">Skill Wise Agents</div>-->
                        <!--<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/abandon_trend')">Abandon Trend</div>-->
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/roaster_manpower')">Manpower</div>
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/agent_apr')">Agent APR</div>
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/ob_internal')">Outbound Report</div>
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/app/webroot/dialer/agent_forecast.php')">Agent Forecasting</div>
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/OutboundReports/index')">Outbound Dashboard Report</div>
                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/revenue_data')">Revenue/Client wise</div>

                        <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/OpdashTest/al_sl_chart')">Al and Sl Dashboard</div>
                        <!--<div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/CdrReports/abandon_call')">Abandon Call/Datewise</div>-->
                        <!-- <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/InboundGroups')">Manage Priority</div> -->
                    
                    </div>

                	<?php $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div> <!-- .container-fluid -->

