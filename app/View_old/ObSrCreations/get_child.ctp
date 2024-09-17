<?php
if(is_array($options) && !empty($options))
{
	echo $this->Form->input('ObSrCreations.Category'.$label,array('label'=>false,'options'=>$options,'empty'=>'Select Sub Category','onChange'=>'getObecrChild(this.id)'));
}
?>