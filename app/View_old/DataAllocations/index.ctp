<script>
	function get_campaign(path,client){
		var id=client.value;
		$.ajax({
			type:'post',
			url:path,
			data:{id:id},
			success:function(data){
				$("#DataAllocationsCampaignName").html(data);
			}
		});	
	}
	
	function get_allocation(path,camp){
		var campid=camp.value;
		var clientid=$("#DataAllocationsClientName").val();	
		$.ajax({
			type:'post',
			url:path,
			data:{campid:campid,clientid:clientid},
			success:function(data){
				$("#DataAllocationsAllocationName").html(data);
			}
		});	
	}
	
	function get_count(path,id){
		var AllocatedId=id.value;
		$.ajax({
			type:'post',
			url:path,
			data:{AllocationId:AllocatedId},
			success:function(data){
				$("#DataAllocationsCount").val(data);
			}
		});	
	}
	
	function allocate_data(){
		var all_location_id = document.querySelectorAll('input[name="Agent[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }	
		
		var totalcount=$("#DataAllocationsCount").val();
		var alllocatedno=$("#DataAllocationsAllocated").val();
		
		var var_client     = $("#DataAllocationsClientName").val();
		var var_campaign   = $("#DataAllocationsCampaignName").val();
		var var_allocation = $("#DataAllocationsAllocationName").val();

	
		if($.trim(var_client)===""){
			$('#DataAllocationsClientName').focus();
			$("#erroMsg").html('Select client name.');  
			return false;
		}
		else if($.trim(var_campaign)===""){
			$('#DataAllocationsCampaignName').focus();
			$("#erroMsg").html('Select campaign name.');  
			return false;
		}
		else if($.trim(var_allocation)===""){
			$('#DataAllocationsAllocationName').focus();
			$("#erroMsg").html('Select allocation name.');  
			return false;
		}
		else if($.trim(alllocatedno)===""){
			$('#DataAllocationsAllocated').focus();
			$("#erroMsg").html('Enter allocated number.');  
			return false;
		}
		else if(parseInt(alllocatedno) > parseInt(totalcount)){
			$('#DataAllocationsAllocated').focus();
			$("#erroMsg").html('Enter correct allocated no.');  
			return false;
		}
		else if(aIds.length ==0){
			$('#alloc').focus();
			$("#erroMsg").html('Select agent name.');  
			return false;
		}
		else{
			return true;
		}	
	}
	
function checkCharacter(e,t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;
               
            }
            catch (err) {
                alert(err.Description);
            }
   }

</script>

    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Bound Campaign</a></li>
        <li class="active"><a href="#">Data Allocation</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Data Allocation</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Data Allocation</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                    <?php echo $this->Form->create('DataAllocations',array('action'=>'index','onsubmit'=>'return allocate_data();','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
              			
                         <div class="form-group">
                      		<label class="col-sm-3 control-label"></label>	
                          	<div class="col-sm-6"> 
                                    <div  id="erroMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                         	</div>
                		</div>

                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Client Name</label>	
                          	<div class="col-sm-6">
                            	<?php echo $this->Form->input('ClientName',array('label'=>false,'onchange'=>'get_campaign("'.$this->webroot.'DataAllocations/get_campaign",this)','options'=>$company,'empty'=>'Select Client','class'=>'form-control','required'=>false ));?>
                         	</div>
                		</div>
                      	
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Campaign Name</label>	
                          	<div class="col-sm-6">      
                      			<?php echo $this->Form->input('CampaignName',array('label'=>false,'onchange'=>'get_allocation("'.$this->webroot.'DataAllocations/get_allocation",this)','options'=>'','empty'=>'Select Campaign','class'=>'form-control','required'=>false ));?>
                         	</div>
                		</div>
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Allocation Name</label>	
                          	<div class="col-sm-6">      
                      			<?php echo $this->Form->input('AllocationName',array('label'=>false,'onchange'=>'get_count("'.$this->webroot.'DataAllocations/get_count",this)','options'=>'','empty'=>'Select Allocation','class'=>'form-control','required'=>false ));?>
                         	</div>
                		</div>
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Count</label>	
                          	<div class="col-sm-6">      
                      			 <?php echo $this->Form->input('Count',array('label'=>false,'readonly'=>true,'class'=>'form-control'));?>
                         	</div>
                		</div>
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Allocated</label>	
                          	<div class="col-sm-6">      
                      			 <?php echo $this->Form->input('Allocated',array('label'=>false,'required'=>false,'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>'Allow only number','class'=>'form-control','autocomplete'=>'off' ));?>
                         	</div>
                		</div>
                          
                        <div class="form-group">
							<label id="alloc" class="col-sm-3 control-label">Assign Allocation</label>	
							<div class="col-sm-8">
                                <div class="checkbox checkbox-primary"  style="width:460px; height:200px; overflow: auto;" >
                                	<?php foreach($page_record as $val){ ?>
                                    <label>
                                        <input type="checkbox"  name="Agent[]" value="<?php echo $val['AgentMaster']['id']?>">
                                        <?php echo $val['AgentMaster']['username']?>
                                    </label><br/>
                                    <?php }?>
                                </div>
						
							</div>
						</div>
                        
                     	<div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="btn-toolbar">
                                        <input type="submit" class="btn btn-raised btn-default btn-primary" value="Submit" >
                                    </div>
                                </div>
                            </div>
                    	</div>
                	<?php $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div> <!-- .container-fluid -->


















