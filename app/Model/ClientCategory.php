<?php
class ClientCategory extends AppModel {
	public $useTable='ecr_master';
	public $virtualFields = array('name'=>"CONCAT(ecrName,'  Label-',Label)",'Ecr'=>"MAX(Label)");
}

?>