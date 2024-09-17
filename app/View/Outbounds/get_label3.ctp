<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('Outbounds.parent3',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Sub Type1'));
}
else
{echo $this->Form->input('Outbounds.parent3',array('label'=>false,'options'=>'','id'=>$type));}
?>