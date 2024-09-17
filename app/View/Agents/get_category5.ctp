<?php
if(is_array($Category5) && !empty($Category5))
{
	echo $this->Form->input('Agents.Category5',array('label'=>false,'options'=>$category5,'class'=>'form-control scen','empty'=>'Select Category'));
}
else
{	echo $this->Form->input('Agents.Category5',array('label'=>false,'class'=>'form-control','options'=>''));}
?>