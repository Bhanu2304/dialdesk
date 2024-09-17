<?php
if(is_array($data) && !empty($data))
{
echo $this->Form->input('AdminObecrs.parent4',array('label'=>false,'options'=>$data,'id'=>$type,'empty'=>'Select Sub Type 2',"class"=>"form-control"));
}
else
{echo $this->Form->input('AdminObecrs.parent4',array('label'=>false,'options'=>'','id'=>$type,"class"=>"form-control"));}
?>