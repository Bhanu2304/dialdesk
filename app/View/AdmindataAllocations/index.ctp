<script>
	function get_campaign(path,client){
		var id=client.value;
		$.ajax({
			type:'post',
			url:path,
			data:{id:id},
			success:function(data){
				$("#AdmindataAllocationsCampaignName").html(data);
			}
		});	
	}
	
	function get_allocation(path,camp){
		var campid=camp.value;
		var clientid=$("#AdmindataAllocationsClientName").val();	
		$.ajax({
			type:'post',
			url:path,
			data:{campid:campid,clientid:clientid},
			success:function(data){
				$("#AdmindataAllocationsAllocationName").html(data);
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
				$("#AdmindataAllocationsCount").val(data);
			}
		});	
	}
	
	function allocate_data(){
		var all_location_id = document.querySelectorAll('input[name="Agent[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }	
		
		var totalcount=$("#AdmindataAllocationsCount").val();
		var alllocatedno=$("#AdmindataAllocationsAllocated").val();
		
		var var_client     = $("#AdmindataAllocationsClientName").val();
		var var_campaign   = $("#AdmindataAllocationsCampaignName").val();
		var var_allocation = $("#AdmindataAllocationsAllocationName").val();

	
		if($.trim(var_client)===""){
			$('#AdmindataAllocationsClientName').focus();
			$("#erroMsg").html('Select client name.');  
			return false;
		}
		else if($.trim(var_campaign)===""){
			$('#AdmindataAllocationsCampaignName').focus();
			$("#erroMsg").html('Select campaign name.');  
			return false;
		}
		else if($.trim(var_allocation)===""){
			$('#AdmindataAllocationsAllocationName').focus();
			$("#erroMsg").html('Select allocation name.');  
			return false;
		}
		else if($.trim(alllocatedno)===""){
			$('#AdmindataAllocationsAllocated').focus();
			$("#erroMsg").html('Enter allocated number.');  
			return false;
		}
		else if(parseInt(alllocatedno) > parseInt(totalcount)){
			$('#AdmindataAllocationsAllocated').focus();
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


<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <div  id="erroMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AdmindataAllocations',array('action'=>'index','onsubmit'=>'return allocate_data();')); ?>
                    <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                        <tr>
                            <td>Select Client</td>
                            <td><?php echo $this->Form->input('ClientName',array('label'=>false,'onchange'=>'get_campaign("'.$this->webroot.'AdmindataAllocations/get_campaign",this)','options'=>$company,'empty'=>'Select Client','class'=>'form-control','required'=>false ));?></td>
                        </tr>
                        <tr>
                            <td>Campaign Name</td>
                            <td><?php echo $this->Form->input('CampaignName',array('label'=>false,'onchange'=>'get_allocation("'.$this->webroot.'AdmindataAllocations/get_allocation",this)','options'=>'','empty'=>'Select Campaign','class'=>'form-control','required'=>false ));?></td>
                        </tr>
                        <tr>
                            <td>Allocation Name</td>
                            <td><?php echo $this->Form->input('AllocationName',array('label'=>false,'onchange'=>'get_count("'.$this->webroot.'AdmindataAllocations/get_count",this)','options'=>'','empty'=>'Select Allocation','class'=>'form-control','required'=>false ));?></td>
                        </tr>
                        <tr>
                            <td>Count</td>
                            <td><?php echo $this->Form->input('Count',array('label'=>false,'readonly'=>true,'class'=>'form-control'));?></td>
                        </tr>
                        <tr>
                            <td>Allocated</td>
                            <td><?php echo $this->Form->input('Allocated',array('label'=>false,'required'=>false,'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>'Allow only number','class'=>'form-control','autocomplete'=>'off' ));?></td>
                        </tr>
                        <tr>
                            <td>Assign Allocation</td>
                            <td style="width:460pxheight:200px; overflow: auto;">
                                <?php foreach($page_record as $val){ ?>
                                    <input type="checkbox"  name="Agent[]" value="<?php echo $val['AgentMaster']['id']?>"> <?php echo $val['AgentMaster']['username']?><br/>
                                <?php }?>
                            </td>
                        </tr>
						<tr>
							<td></td>
							<td> <input type="submit" style="mrgin-left:100px;" class="btn btn-web" value="Submit" ></td>
						</tr>

                    </table>
                   
                <?php $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>



   
















