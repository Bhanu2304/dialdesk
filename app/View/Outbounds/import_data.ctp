<div id="wrapper">
	<div id="campaign">
		<?php echo $this->Form->create('Outbounds',array('action'=>'add_campaign','enctype'=>'multipart/form-data')); ?>
   		<fieldset id="fieldset1" >
    		<h1>Import Data</h1>
         	<font style="color:red;"><?php echo $this->Session->flash(); ?></font>
        	<div class="form-bottom">
				<table>      	
                   <tr>
						<td>Campaign Name<font style="color:red">*</font></td>
					    <td><?php echo $this->Form->input('CampaignName',array('label'=>false,'autocomplete'=>'off','required'=>true));?></td>
				   </tr>
					<tr>
						<td>Import Data File<font style="color:red">*</font></td>
						<td><?php echo $this->Form->file('File.',array('label'=>false,'type'=>'file','multiple'=>false,'required'=>true));?></td>
					</tr>
					<tr>
						<td colspan=2><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="Create" >
						</p>
						</td>
					</tr>
				</table>
     		</div>              
		</fieldset> 
		<?php echo $this->Form->end(); ?>

		
</div>
						
</div>