<?php
class Addclient extends AppModel {
	public $useTable='client_master';
    public $validate = array(
        'client_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A branch name is required'
            )
        ),
       'branch_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A branch name is required'
            )
        )

    );
}

?>