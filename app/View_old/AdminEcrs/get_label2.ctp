<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('AdminEcrs.Type',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Type','required'=>true,"class"=>"form-control"));
}
else
{echo $this->Form->input('AdminEcrs.Type',array('label'=>false,'options'=>'','id'=>$type,'required'=>true,"class"=>"form-control"));}
?>