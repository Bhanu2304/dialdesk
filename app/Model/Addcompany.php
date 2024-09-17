<?php
class Addcompany extends AppModel {
	public $useTable='company_master';
    public $validate = array(
        'company_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A company name is required'
            )
        )		
    );
}

?>