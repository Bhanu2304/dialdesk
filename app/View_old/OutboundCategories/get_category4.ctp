<?php
if(is_array($category4) && !empty($category4))
{
	echo $this->Form->input('Home.Category4',array('label'=>false,'options'=>$category4,'empty'=>'Select Sub Category'));
}
else
{	echo $this->Form->input('Home.Category4',array('label'=>false,'options'=>''));}
?>