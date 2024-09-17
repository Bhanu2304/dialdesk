<?php
class InitialInvoice extends AppModel {
	public $useTable='tbl_invoice';
    public $validate = array(
        'company_name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Comapany name is required'
            )
        ),
        'branch_name' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Branch name is required'
			)
		),
        'costCenter' => array(),
        'financeYear' => array(),
        'month' => array(),
        'invoiceDate' => array(),
        'applyTaxCalculation' => array(),
        'invoiceDescription' => array()
    );
}

?>