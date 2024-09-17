<?php
class Addbranch extends AppModel {
	public $useTable='branch_master';
    public $validate = array(
        'branch_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A branch name is required'
            )
        ),
		'branch_code' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A branch code is required'
			)
		)		
    );
}

?>