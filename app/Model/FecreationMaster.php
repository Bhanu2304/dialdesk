<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('AppModel', 'Model');
class FecreationMaster extends AppModel{
	public $useTable='fecreation_master';
	//public $virtualFields = array('status'=>"group_CONCAT(phone,',',emailid)");
public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
        $passwordHasher = new BlowfishPasswordHasher();
        $this->data[$this->alias]['password'] = $passwordHasher->hash(
            $this->data[$this->alias]['password']
        );
    }
    return true;
}

}
?>