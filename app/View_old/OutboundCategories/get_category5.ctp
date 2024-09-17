<?php
if(is_array($Category5) && !empty($Category5))
{
	echo $this->Form->input('Home.Category5',array('label'=>false,'options'=>$category5,'empty'=>'Select Sub Category'));
}
else
{	echo $this->Form->input('Home.Category5',array('label'=>false,'options'=>''));}
?>