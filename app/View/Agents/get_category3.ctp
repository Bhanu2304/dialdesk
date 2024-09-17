<?php
if(is_array($category3) && !empty($category3))
{
	echo $this->Form->input('Agents.Category3',array('label'=>false,'options'=>$category3,'class'=>'form-control scen','empty'=>'Select Category'));
}
else
{	echo $this->Form->input('Agents.Category3',array('label'=>false,'class'=>'form-control','options'=>''));}
?>