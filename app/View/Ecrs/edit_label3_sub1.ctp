<?php
if(is_array($data) && !empty($data))
{
	$count = count($data);
	$keys = array_keys($data);
	
	for($i=0; $i<$count; $i++)
	{
            echo '<div class="form-group">';
            echo '<label class="col-sm-3 control-label">Sub Scenarios 3</label>';
            echo '<div class="col-sm-6">'.($this->Form->input('Ecrs.'.$keys[$i],array('label'=>false,'value'=>$data[$keys[$i]],'required'=>true,'class'=>'form-control')))."</div>";
            echo "</div>";
		
	}

}
else
{echo "";}
?>