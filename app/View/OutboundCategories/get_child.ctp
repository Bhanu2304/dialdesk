<?php
if(is_array($options) && !empty($options))
{
	echo $this->Form->input('ManualOutbounds.Category'.$label,array('label'=>false,'class'=>'form-control scen','options'=>$options,'empty'=>'Select Sub Scenarios'.$label=($label-1),'required'=>true,'onChange'=>'outbound_getChild(this.id)'));
}die;
?>