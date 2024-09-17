<?php
class ObfieldCreation extends AppModel {
	public $useTable='obfield_master';
        public $virtualFields = array('max'=>"MAX(Priority)",'max2'=>"MAX(fieldNumber)");
}

?>