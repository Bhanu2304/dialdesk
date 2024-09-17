<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('AdminobecrEdits.type',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Type','class'=>'form-control'));
}
else
{echo $this->Form->input('AdminobecrEdits.type',array('label'=>false,'options'=>'','id'=>$type,'class'=>'form-control'));}
?>