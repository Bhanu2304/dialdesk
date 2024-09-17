<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('AppModel', 'Model');
class ObReportHeaderMaster extends AppModel {
    public $useTable='outbound_report_header_master';
}
?>