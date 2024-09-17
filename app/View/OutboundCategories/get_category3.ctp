<?php
if(is_array($category3) && !empty($category3))
{
	echo $this->Form->input('Home.Category3',array('label'=>false,'options'=>$category3,'class'=>'form-control scen','empty'=>'Select Sub Category'));
}
else
{	echo $this->Form->input('Home.Category3',array('label'=>false,'options'=>''));}
?>