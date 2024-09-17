<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('AppModel', 'Model');
class AgentMaster extends AppModel {
	public $useTable='agent_master';
	
	
	public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
		$this->data[$this->alias]['password2'] = $this->data[$this->alias]['password'];
        $passwordHasher = new BlowfishPasswordHasher();
        $this->data[$this->alias]['password'] = $passwordHasher->hash(
            $this->data[$this->alias]['password']
        );
		
		$this->data[$this->alias]['createdate'] = date('Y-m-d H:i:s');
    }
    return true;
}
}

?>