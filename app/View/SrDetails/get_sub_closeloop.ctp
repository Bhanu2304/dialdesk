<?php
if(!empty($clcat)){
	echo $this->Form->input('sub_close_loop_category',array('label'=>false,'options'=>$clcat,'style'=>'width:200px;height:35px;','id'=>'sub_close_loop_category','empty'=>'Select Sub Close Loop'));
}
else{
	echo $this->Form->input('sub_close_loop_category',array('label'=>false,'options'=>'','style'=>'width:200px;height:35px;','id'=>'sub_close_loop_category','empty'=>'Select Sub Close Loop'));

}


?>