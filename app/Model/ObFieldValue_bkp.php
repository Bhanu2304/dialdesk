<?php
class ObFieldValue extends AppModel {
	public $useTable='obfield_master_value';
	public $virtualFields = array('value'=>"group_CONCAT(FieldValueName)");

}

?>