<?php
if(is_array($options) && !empty($options))
{
	echo $this->Form->input('Agents.Category'.$label,array('label'=>false,'options'=>$options,'class'=>'form-control','required'=>true,'empty'=>'Select Sub Scenarios '.$label=$label-1,'onChange'=>'inbound_getChild(this.id),getdlfaddress(this.value)'));
}
die;
?>