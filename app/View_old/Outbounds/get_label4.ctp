<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('Outbounds.parent4',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Sub Type 2'));
}
else
{echo $this->Form->input('Outbounds.parent4',array('label'=>false,'options'=>'','id'=>$type));}
?>