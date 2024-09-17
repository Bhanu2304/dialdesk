<?php
class ObEcr extends AppModel {
	public $useTable='obecr_master';
        public $virtualFields = array('ecr'=>"group_concat(ecrName)");
	
}

?>