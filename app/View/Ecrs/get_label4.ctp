<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('Ecr.sub_type2',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Sub Scenarios 3',"class"=>"form-control"));
}
else
{echo $this->Form->input('Ecr.sub_type2',array('label'=>false,'options'=>'','id'=>$type,"class"=>"form-control"));}
?>