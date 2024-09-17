<?php
if(is_array($data) && !empty($data))
{
	$count = count($data);
	$keys = array_keys($data);
	
	for($i=0; $i<$count; $i++)
	{
           
            echo ($this->Form->input('AdminEcrs.'.$keys[$i],array('label'=>false,'value'=>$data[$keys[$i]],'required'=>true,'class'=>'form-control')));
           
	}
}
else
{echo "";}
?>