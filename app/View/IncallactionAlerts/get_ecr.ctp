<?php
if(is_array($data) && !empty($data))
{
    $data = array_merge(array('All@@All'=>'All'),$data);
    
    if($type ==="Sub Scenario 1"){$newType="type";}
    if($type ==="Sub Scenario 2"){$newType="subtype";}
    if($type ==="Sub Scenario 3"){$newType="subtype1";}
    if($type ==="Sub Scenario 4"){$newType="subtype2";}
    
    echo '<label class="col-sm-2 control-label">'.$type.'</label>';
    echo '<div class="col-sm-2">';
    echo $this->Form->input('Escalations.'.$newType,array('label'=>false,'options'=>$data,'empty'=>'Select','class'=>'form-control','onchange'=>$function."(this.value,'".$this->webroot."escalations/getEcr','".$divtype."')"));
    echo '</div>';
}

?>