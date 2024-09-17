<?php
class PurposeCampaign extends AppModel {
	public $useTable='ob_pr';
	//public $virtualFields = array('name'=>"CONCAT(Plan,'  Label-',Label)");
	public $virtualFields = array('ecr'=>"group_concat(Ecr)",'grp'=>"GROUP_CONCAT( CONCAT(id,'=>',Ecr) SEPARATOR '==>')");
}

?>