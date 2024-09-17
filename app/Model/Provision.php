<?php
class Provision extends AppModel {
	public $useTable='provision_master';
    public $validate = array(
        'cost_center' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A cost center is required'
            )
        ),
		'finance_year' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A finance year is required'
			)
		),
        	'month' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'A finance year is required'
			)
		)
    );
}

?>