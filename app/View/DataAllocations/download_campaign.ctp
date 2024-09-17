<div id="wrapper">
	<div id="register">
    	<?php echo $this->Form->create('ObImports',array('action'=>'download_campaign')); ?>
 		<fieldset id="fieldset1" >
      		<h1>Download Campaign</h1>
         	<font style="color:red;"><?php echo $this->Session->flash(); ?></font>
         	<div class="form-bottom">
          		<?php echo $this->Form->label('Campaign Name');?>
              	<?php echo $this->Form->input('CampaignName',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign','required'=>true ));?><br/>
                <div id="nn"></div>
               	<div id="note"></div> 	
           	</div>              
     	</fieldset> 
        <?php $this->Form->end(); ?>
	</div>	
</div>