<?php
class OutboundCloseValue extends AppModel {
	public $useTable='ob_close_master_value';
	public $virtualFields = array('value'=>"group_CONCAT(FieldValueName)");
}

?>