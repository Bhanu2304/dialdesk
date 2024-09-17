<?php
echo $this->Html->css('parentChild/style');
echo $this->Html->script('parentChild/jquery-1.11.1.min');
echo $this->Html->script('parentChild/jquery-migrate-1.2.1.min');
echo $this->Html->script('parentChild/jquery-ui');
echo $this->Html->script('parentChild/jquery.tree');      
?>
<script>
$(document).ready(function() {
	$('.tree').tree_structure({
    	'add_option': true,
        'edit_option': true,
        'delete_option': true,
       	'confirm_before_delete': true,
     	'animate_option': false,
       	'fullwidth_option': false,
        'align_option': 'center',
      	'draggable_option': true
	});
});

function ivrDetails(){
	$("#ivr_form").submit();
}
</script>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin:400,700,600"/>
<div class="row-fluid">
	<div class="span12">
		<div class="box dark">
  			<header>
 				<div class="icons"><i class="icon-edit"></i></div>
          		<h5>IVR</h5>
			</header>
    		<div id="div-1" class="accordion-body collapse in body">
                    <?php echo $this->Form->create('AdminIvrs',array('action'=>'index','id'=>'ivr_form')); ?>
                        <?php echo $this->Form->input('client',array('label'=>false,'options'=>$client,'onchange'=>'ivrDetails();','empty'=>'Select Client','required'=>true));?>
                        <?php echo $html; ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
	</div>
</div>