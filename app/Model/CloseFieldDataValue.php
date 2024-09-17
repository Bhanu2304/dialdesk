<?php
class CloseFieldDataValue extends AppModel {
	public $useTable='close_field_value';
	public $virtualFields = array('value'=>"group_CONCAT(FieldValueName)");
}

?>