<?php
class FieldValue extends AppModel {
    public $useTable='field_master_value';
    public $virtualFields = array('value'=>"group_CONCAT(FieldValueName order by FieldValueName)");
}

?>