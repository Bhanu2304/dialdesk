<?php
class CampaignName extends AppModel {
	public $useTable='ob_campaign';
	
	//public function beforeSave($options = array()) 
//	{
//		$srno = $this->find('first',array('fields'=>array("getSrno('".$this->data[$this->alias]['ClientId']."')")));
//		if(!isset($srno['0']["getSrno('".$this->data[$this->alias]['ClientId']."')"])){$this->data[$this->alias]['SrNo'] = 1;}
//		else
//		$this->data[$this->alias]['SrNo'] = $srno['0']["getSrno('".$this->data[$this->alias]['ClientId']."')"];
//	}

	
}
?>