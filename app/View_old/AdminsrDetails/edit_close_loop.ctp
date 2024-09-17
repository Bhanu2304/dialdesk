<script>
function validUpdateCloseLooping(){
	var type=$("#type").val();
	var sub_type= $("#sub_type").val();
	var close_loop= $("#close_loop").val();
	var close_loop_category= $("#close_loop_category").val();
	var close_loop_sub_category= $("#close_loop_sub_category").val();

	if($.trim(type) ===""){
		$("#type").focus();
		$("#erroMsg").html('Please select type.');
		return false;
	}
	else if($.trim(sub_type) ===""){
		$("#sub_type").focus();
		$("#erroMsg").html('Please select sub type.');
		return false;
	}
	else if($.trim(close_loop) ===""){
		$("#close_loop").focus();
		$("#erroMsg").html('Please select close loop.');
		return false;
	}
	else if($.trim(close_loop_category) ===""){
		$("#close_loop_category").focus();
		$("#erroMsg").html('Please insert close loop category.');
		return false;
	}
	else{
		return true;	
	}
}

function subCategoroy(path){
	var type=$("#type").val();
	$.ajax({
		type:'post',
		url:path,
		data:{id:type},
		success:function(data){
			$("#sub_type").html(data);	
		}
	});
}
</script>
<div id="wrapper">
	<div id="register">
  		<?php echo $this->Form->create('CloseLoopings',array('action'=>'update_close_loop','onsubmit'=>'return validUpdateCloseLooping()')); ?>
      	<h1> Update Close Looping </h1>
      	<div id="erroMsg"></div> 
      	<table>
            <tr>
                <td>
                    <?php echo $this->form->label('Type');?>
                    <?php echo $this->Form->input('type', array('label'=>false,'options'=>$category,'empty'=>'Select Type',
					'onclick'=>'subCategoroy("'.$this->webroot.'CloseLoopings/get_sub_type");','value'=>$get_close_loop['CloseLoopMaster']['type'],'id'=>'type')); ?>
                </td>
            </tr>
             <tr>
                <td>
                    <?php echo $this->form->label('Sub Type');?>
                 	<?php echo $this->Form->input('sub_type', array('label'=>false,'options'=>$subcat,'value'=>$get_close_loop['CloseLoopMaster']['sub_type'],'id'=>'sub_type')); ?>
                </td>
            </tr>
            <tr>
            	<td>
            		<?php echo $this->form->label(' Close Loop');?><br/>
                 	<select name="close_loop" id="close_loop">
                   		<option value="">Select Close Looping</option>
						<option <?php if($get_close_loop['CloseLoopMaster']['close_loop']=="system") echo 'selected="selected"'; ?> value="system">System</option>
                       	<option <?php if($get_close_loop['CloseLoopMaster']['close_loop']=="manual") echo 'selected="selected"'; ?> value="manual">Manual</option>
                   	</select> 
            	</td>
            </tr>
            
           	<tr>
            	<td>
            		<?php echo $this->form->label('Select Parent');?><br/>
                	<select name="data[CloseLoopings][parent_id]">
            			<option value="0">Select Parent</option>
						<?php foreach($data as $cl) { ?>
                  		<option  <?php if($get_close_loop['CloseLoopMaster']['parent_id']==$cl["id"]) echo 'selected="selected"'; ?>  
                        value="<?php echo $cl["id"] ?>"><?php echo $cl["name"]; ?></option>
               			 <?php } ?>
					</select><br/>
            	</td>
            </tr>
            
            <tr>
            	<td>
            		<?php echo $this->form->label('Close Loop Category');?><br/>
                 	<?php echo $this->Form->input('close_loop_category', array('label'=>false,
					'value'=>$get_close_loop['CloseLoopMaster']['close_loop_category'],'id'=>'close_loop_category')); ?>
            	</td>
            </tr>

            </table>
            <input type="hidden" name="id"  value="<?php echo $get_close_loop['CloseLoopMaster']['id'];?>" />
            <p class="signin button"> 
				<input type="submit" value="Update" /> 
			</p>	        
		<?php $this->Form->end();?>
	</div>
</div>
