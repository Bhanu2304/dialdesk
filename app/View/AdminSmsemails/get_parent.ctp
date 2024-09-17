<?php
if(is_array($data) && !empty($data))
{

echo $this->Form->input('Escalation.'.$label,array('label'=>false,'options'=>$data,'empty'=>'Select Child','id'=>$label));

}
?>