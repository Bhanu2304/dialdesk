<?php
class ProvisionPartDed extends AppModel {
	public $useTable='provision_master_month_deductions';
    public $validate = array(
        
		'Provision_Finance_Year' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Provision Finance Year Not Found'
			)
		),
        	'Provision_Finance_Month' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Provision Finance Month Not Found'
			)
		),
        'Provision_Branch_Name' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Provision Branch Name Not Found'
			)
		),
        
        'Provision_Cost_Center' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Provision Cost Center Not Found'
			)
		),
        'Provision_UsedBy_Month' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Please Fill Provision UserBy'
			)
		),
        'ProvisionBalanceUsed' => array(
			'required'	=> array(
				'rule'		=> 'notBlank',
				'message'	=>	'Please Fill Provision Used'
			)
		)
        
        
    );
}

?>