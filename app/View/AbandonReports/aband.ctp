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
        <li class="active"><a href="#">Abandon Report</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Abandon Report</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Abandon Report</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                    <div class="form-group">
                        
                       
                        
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports')">Abandon Report</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/customer_wise')">Customer/Date wise Density</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/AbandonReports/abandon_trend')">Abandon Trend</div>
                    <div class="col-sm-3"><input type="radio" name="MENU" onclick="redirect('/dialdesk/CdrReports/abandon_call')">Abandon Call/Datewise</div>
                        
                    
                    </div>

                	<?php $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div> <!-- .container-fluid -->

