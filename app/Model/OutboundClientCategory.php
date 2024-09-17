<?php
class OutboundClientCategory extends AppModel {
	public $useTable='obecr_master';
	public $virtualFields = array('name'=>"CONCAT(ecrName,'  Label-',Label)",'Ecr'=>"MAX(Label)");
}

?>