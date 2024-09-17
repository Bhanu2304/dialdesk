<?php if(isset($msg)) echo "<h1>".$msg."</h1>"; ?>
<?php echo $this->Form->create(array('url'=>'movedirectly')); ?>
<input type ='radio' name="callType" value="inbound" id="callType"/>Inbound <br />
<input type ='radio' name="callType" value="outbound" id="callType2"/>Outbound

<div id = 'a'></div><?php echo $this->Form->input('clientName',array('label'=>false,'options'=>$clientName,'empty'=>'Select Client','style'=>'display:none')); ?>
<div id = 'b'></div><?php echo $this->Form->input('Campaign',array('label'=>false,'options'=>'','empty'=>'Select Campaign','style'=>'display:none')); ?>
<div id = 'c'></div><?php echo $this->Form->input('Allocation',array('label'=>false,'options'=>'','empty'=>'Select Allocation','style'=>'display:none')); ?>

<?php echo $this->Form->end('submit'); ?>

