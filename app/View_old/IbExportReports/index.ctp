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

    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Export Report</a></li>
        <li class="active"><a href="#">Inbound Reports</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Inbound Export Reports</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Inbound Reports</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                    <?php echo $this->Form->create('IbExportReports',array('action'=>'download','onsubmit'=>'return validateExport()','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
              			

                        <div class="form-group">
                      		<label class="col-sm-3 control-label"></label>	
                          	<div class="col-sm-6">      
                      			<div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                         	</div>
                		</div>

                            <div class="form-group">
                      		<label class="col-sm-3 control-label">Start Date</label>	
                          	<div class="col-sm-6">      
                      			<?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'start date','id'=>'fdate','class'=>'form-control date-picker','required'=>true ));?>
                         	</div>
                		</div>
                       
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">End Date</label>	
                          	<div class="col-sm-6">      
                      			<?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'end date','id'=>'ldate','class'=>'form-control date-picker','required'=>true));?>
                         	</div>
                		</div>
                           
                     	<div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="btn-toolbar">
                                        <input type="submit" class="btn btn-raised btn-default btn-primary" value="Export" >
                                    </div>
                                </div>
                            </div>
                    	</div>
                	<?php $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div> <!-- .container-fluid -->

