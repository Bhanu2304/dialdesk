<?php
if(is_array($options) && !empty($options))
{
	echo $this->Form->input('ClientSrCreations.Category'.$label,array('label'=>false,'options'=>$options,'empty'=>'Select Sub Category','onChange'=>'getEcrChild(this.id)'));
}
?>