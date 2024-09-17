<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('Outbounds.parent1',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Type','required'=>true));
}
else
{echo $this->Form->input('Outbounds.parent1',array('label'=>false,'options'=>'','id'=>$type,'required'=>true));}
?>