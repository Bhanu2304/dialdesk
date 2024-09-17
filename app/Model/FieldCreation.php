<?php
class FieldCreation extends AppModel {
	public $useTable='field_master';
	public $virtualFields = array('max'=>"MAX(Priority)",'max2'=>"MAX(fieldNumber)");
}

?>