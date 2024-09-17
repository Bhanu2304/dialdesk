<?php
class Training extends AppModel {
	public $useTable='training_master';
	public $virtualFields = array('Client'=>"getClientName(ClientId)");
}

?>