<div id="wrapper">
	<div id="register">
  		<?php echo $this->Form->create('ProcessIntegrations',array('action'=>'index','onsubmit'=>'return validateProcessIntegration()')); ?>
      		<h1> Process Integration </h1> 
        	<font style="color:red;"><?php echo $this->Session->flash(); ?></font>
        	<?php 
			if(!empty($data)){
          		$this->form->label('Select Parent Lag');
				echo $this->Form->input('pid', array('label'=>false,'options'=>$data,'empty'=>'Select Parent','id'=>'pid','onchange'=>'addChild("pid")'));
			}
			?>
            <?php echo $this->form->label('Enter Lag');?>
            <?php echo $this->form->input('name',array('label'=>false,'id'=>'name','autocomplete'=>'off'));?>
            <?php echo $this->form->hidden('lastparent',array('label'=>false,'id'=>'lastparent'));?>
          	<div id="erroMsg"></div>	        
           	<p class="signin button"> 
				<input type="submit" value="Submit"/> 
			</p>
		<?php $this->Form->end();?>
	</div>
</div>






