<?php
class CostCenterMaster extends AppModel {
//var $virtualFields=array('costcenter'=>"CONCAT(CostCenterMaster.stream,'/',CostCenterMaster.type,'/',CostCenterMaster.branch,'/',CostCenterMaster.id)");
	public $useTable='cost_master';
    public $validate = array(
        'company_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A Company Name is required'
            )		
		),
		
        'branch' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A Branch Name is required'
            )		
		),
        'stream' => array(
		    'required' => array(
                'rule' => 'notBlank',
                'message' => 'A Stream Name is required'
            )
		),
        'process' => array(),
        'category' => array(),
        'type' => array(
		  	'required' => array(
                'rule' => 'notBlank',
                'message' => 'A Type Name is required'
            )
		),
        'client' => array(),
        'total_man_date' => array(),
        'shrinkage' => array(),
        'attrition' => array(),
        'shift' => array(),
        'working_days' => array(),
        'target_mandate' => array(),		
        'over_saldays' => array(),		
        'training_days' => array(),		
        'incentive_allowed' => array(),		
        'training_attrition' => array(),
		'deduction_allowed' => array(),
		'description' => array(),
		'process_manager' => array(),
		'emailid' => array(),
		'contact_no' => array(),
		'po_required' => array(),
		'jcc_no' => array(),
		'grn' => array(),
		'bill_to' => array(),
		'as_client' => array(),
		'b_Address1' => array(),
		'b_Address2' => array(),
		'b_Address3' => array(),
		'b_Address4' => array(),
		'b_Address5' => array(),
		'ship_to' => array(),
		'as_bill_to' => array(),
		'a_address1' => array(),
		'a_address2' => array(),
		'a_address3' => array(),
		'a_address4' => array(),
		'a_address5' => array(),
		'cost_center' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A cost center Name is required'
            )
		)
    );
}

?>