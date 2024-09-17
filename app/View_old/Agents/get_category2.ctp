<?php
if(is_array($category2) && !empty($category2))
{
	echo $this->Form->input('Agents.Category2',array('label'=>false, 'class'=>'form-control','options'=>$category2,'empty'=>'Select Category','onChange'=>'getCategory3()'));
}
else
{	echo $this->Form->input('Agents.Category2',array('label'=>false,'class'=>'form-control','options'=>''));}
?>