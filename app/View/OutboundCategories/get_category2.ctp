<?php
if(is_array($category2) && !empty($category2))
{
	echo $this->Form->input('Home.Category2',array('label'=>false,'options'=>$category2,'class'=>'form-control scen','empty'=>'Select Sub Category','onChange'=>'getCategory3()'));
}
else
{	echo $this->Form->input('Home.Category2',array('label'=>false,'options'=>''));}
?>