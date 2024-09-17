<?php
class EcrMaster extends AppModel{
	public $useTable='ecr_master';
	public $virtualFields = array('ecr'=>"group_concat(ecrName)",'grp'=>"GROUP_CONCAT( CONCAT(id,'=>',ecrName) SEPARATOR '==>')");
	
}
?>