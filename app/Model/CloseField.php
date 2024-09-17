<?php
class CloseField extends AppModel {
	public $useTable='close_field';
	public $virtualFields = array('max'=>"MAX(Priority)",'max2'=>"MAX(fieldNumber)");
}

?>