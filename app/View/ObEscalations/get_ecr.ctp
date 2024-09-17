<?php
if(is_array($data) && !empty($data))
{
    $data = array_merge(array('All@@All'=>'All'),$data);
    
    if($Label ==2){$newType="category"; $type='Scenario';}	
    if($Label ==3){$newType="type"; 	  $type='Sub Scenario 1';}
    if($Label ==4){$newType="subtype";  $type='Sub Scenario 2';}
    if($Label ==5){$newType="subtype1"; $type='Sub Scenario 3';}
    if($Label ==6){$newType="subtype2"; $type='Sub Scenario 4';}
    
    echo '<label class="col-sm-2 control-label">'.$type.'</label>';
    echo '<div class="col-sm-2">';
    echo $this->Form->input('ObEscalations.'.$newType,array('label'=>false,'options'=>$data,'empty'=>'Select','class'=>'form-control','onchange'=>$function."(this.value,'".$this->webroot."ObEscalations/getEcr','".$Label."','".$divtype."','')"));
    echo '</div>';
}

?>