<?php

App::uses('AppModel', 'Model');

class User extends AppModel {
//public $useTable='tbl_user';
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A password is required'
            )
        )
        
    );
	public $useTable="tbl_user";
}

?>