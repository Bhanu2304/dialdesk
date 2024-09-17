<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('AdminEcrs.type',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Sub Type1','class'=>'form-control'));
}
else
{echo $this->Form->input('AdminEcrs.type',array('label'=>false,'options'=>'','id'=>$type,'class'=>'form-control'));}
?>