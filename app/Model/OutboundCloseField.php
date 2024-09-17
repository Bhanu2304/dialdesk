<?php
class OutboundCloseField extends AppModel {
    public $useTable='ob_close_master';
    public $virtualFields = array('max'=>"MAX(Priority)",'max2'=>"MAX(fieldNumber)");
}
?>