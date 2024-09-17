<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('Obecr.parent',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Sub Scenarios 1','required'=>true,"class"=>"form-control"));
}
else
{echo $this->Form->input('Obecr.parent',array('label'=>false,'options'=>'','id'=>$type,'required'=>true,"class"=>"form-control"));}
?>