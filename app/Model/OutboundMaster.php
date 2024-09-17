<?php

App::uses('AppModel', 'Model');


class OutboundMaster extends AppModel {
	public $useTable="call_master_out";
	public function beforeSave($options = array()) 
	{
		$srno = $this->find('first',array('fields'=>array("getSrnoOut('".$this->data[$this->alias]['ClientId']."')")));
		if(!isset($srno['0']["getSrnoOut('".$this->data[$this->alias]['ClientId']."')"])){$this->data[$this->alias]['SrNo'] = 1;}
		else
		$this->data[$this->alias]['SrNo'] = $srno['0']["getSrnoOut('".$this->data[$this->alias]['ClientId']."')"];
	}
	
	
}

?>