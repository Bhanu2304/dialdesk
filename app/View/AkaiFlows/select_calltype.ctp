<?php
if($type == 'Resolution')
{
    echo '<label class="col-sm-2 control-label">'.$type.'</label>';
    echo '<div class="col-sm-4">';
    echo $this->Form->textArea('resolution',array('label'=>false,'class'=>'form-control','rows'=>'8','cols'=>'70','value'=>$data,'required'=>true));
    echo '</div>';
}else{

    echo '<label class="col-sm-2 control-label">'.$type.'</label>';
    echo '<div class="col-sm-4">';
    echo $this->Form->input('Calltype',array('label'=>false,'options'=>$data,'empty'=>'Select','class'=>'form-control','onchange'=>$function."(this.value,'".$Label."')"));

    echo '</div>';
}


?>