<?php
if(!empty($data) && is_array($data))
{
echo $this->Form->input('ProcessIntegrations.'.$label,array('label'=>false,'options'=>$data,'empty'=>'Select Sub Parent Leg','id'=>$label,'onchange'=>'addChild("'.$label.'")'));
}
?>