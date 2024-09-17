<?php
if(is_array($category4) && !empty($category4))
{
	echo $this->Form->input('Agents.Category4',array('label'=>false,'options'=>$category4,'class'=>'form-control scen','empty'=>'Select Category'));
}
else
{	echo $this->Form->input('Agents.Category4',array('label'=>false,'class'=>'form-control','options'=>''));}
?>