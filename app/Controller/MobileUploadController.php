    <?php
class MobileUploadController extends AppController 
{
    public $uses=array('RegistrationMaster','InitialInvoice','BillMaster','InitialInvoiceTmp','Addclient','Addbranch','Addcompany','CostCenterMaster',
        'AddInvParticular','Particular','AddInvDeductParticular','DeductParticular','Access','User','EditAmount',
        'Provision','PONumber','NotificationMaster','ProvisionPart','ProvisionPartDed','CostCenterMaster','BalanceMaster','PlanMaster','SendInformation','Sms_link_transaction','MobileUpload');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
		

public function beforeFilter()
{
    parent::beforeFilter();
    if(!$this->Session->read("role") ==="admin"){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
        else if(empty($this->Session->read("admin_id"))){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    else
    {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('index_ledger','index_ledger2','add_bill','add2','view_approved_bill','get_view_approved','index','add_part','add_part1','delete_particular2','view','add','billApproval','get_view','view_bill','view_approve_bill','genrate_bill','genrate_bill','edit_bill','update_bill','update_bill',
            'branch_view','branch_viewbill','edit_bill','update_bill','view_pdf1','view_pdf','download','view_admin','dashboard','check_po','view_grn','check_grn',
            'approve_ahmd','view_ahmd','view_invoice','edit_invoice','download_grn','approve_grn','edit_forgrn','approve_po','view_forpo','view_pdf','billApproval',
            'reject_invoice','update_invoice','update_po','update_grn','billApproval','view_pdfgrn','view_pdfgrn1','view_pdfgrn2','get_costcenter','get_gst_type',
                    'get_service_no','view_status_change_request','delete_invoice','get_provision_months','send_payment_data','paymentResponse','view_sms_link','sms_link_transcation_detail','mobile_upload');

            
            
            if(1){$this->Auth->allow('index','get_costcenter','get_gst_type','get_service_no');$this->Auth->allow('add','check_po_number');$this->Auth->allow('billApproval');$this->Auth->allow('branch_viewbill');}
            if(1){$this->Auth->allow('view');$this->Auth->allow('view_bill');$this->Auth->allow('genrate_bill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(1){$this->Auth->allow('branch_view');$this->Auth->allow('branch_viewbill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(1){$this->Auth->allow('download');$this->Auth->allow('view_pdf');$this->Auth->allow('view_pdf1');}
            if(1){$this->Auth->allow('download_proforma');$this->Auth->allow('view_proforma_pdf');$this->Auth->allow('view_proforma_letter_pdf');}
            if(1){$this->Auth->allow('edit_proforma','view_proforma','approve_proforma','move_approve_proforma');$this->Auth->allow('update_proforma','reject_proforma'); }
            if(1){$this->Auth->allow('download_proforma_branch');}
            if(1){$this->Auth->allow('view_admin');$this->Auth->allow('view_forpo');$this->Auth->allow('update_po');}
            if(1){$this->Auth->allow('dashboard');}
            if(1){$this->Auth->allow('check_po');$this->Auth->allow('approve_po');}
            if(1){$this->Auth->allow('view_grn');$this->Auth->allow('edit_forgrn');$this->Auth->allow('update_grn');}
            if(1){$this->Auth->allow('check_grn');$this->Auth->allow('approve_grn');}
            if(1){$this->Auth->allow('download_grn','view_pdf','view_pdf1'); $this->Auth->allow('view_pdfgrn');$this->Auth->allow('view_pdfgrn1','view_pdfgrn2');}
            if(1){$this->Auth->allow('approve_ahmd');}
            if(1){$this->Auth->allow('view_ahmd');}
            if(1){$this->Auth->allow('view_status_change_request'); }
            if(1){$this->Auth->allow('view_invoice');$this->Auth->allow('edit_invoice');$this->Auth->allow('update_invoice');
            
            if(1){$this->Auth->allow('delete_invoice'); }
            
            $this->Auth->allow("apply_service_tax","get_provision_months"); $this->Auth->allow("apply_tax_cal"); $this->Auth->allow("apply_krishi_tax"); $this->Auth->allow('reject_invoice');}
            
    }
    $this->Auth->allow("apply_service_tax","get_provision_months");
    if ($this->request->is('ajax'))
    {
            $this->render('contact-ajax-response', 'ajax');
    }
    
    $this->InitialInvoice->useDbConfig = 'dbNorth';
    $this->BillMaster->useDbConfig = 'dbNorth';
    $this->InitialInvoiceTmp->useDbConfig = 'dbNorth';
    $this->Addclient->useDbConfig = 'dbNorth';
    $this->Addbranch->useDbConfig = 'dbNorth';
    $this->Addcompany->useDbConfig = 'dbNorth';
    $this->CostCenterMaster->useDbConfig = 'dbNorth';
    $this->AddInvParticular->useDbConfig = 'dbNorth';
    
    $this->Particular->useDbConfig = 'dbNorth';
    $this->AddInvDeductParticular->useDbConfig = 'dbNorth';
    $this->DeductParticular->useDbConfig = 'dbNorth';
    $this->Access->useDbConfig = 'dbNorth';
    $this->User->useDbConfig = 'dbNorth';
    
    
    $this->Provision->useDbConfig = 'dbNorth';
    $this->PONumber->useDbConfig = 'dbNorth';
    $this->ProvisionPart->useDbConfig = 'dbNorth';
    $this->ProvisionPartDed->useDbConfig = 'dbNorth';
    //$this->User->useDbConfig = 'dbNorth';
    //$this->EditAmount->useDbConfig = 'dbNorth';
    
}



public function mobile_upload(){
    $this->layout='user';
    $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));
    $this->set('transcation_data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));
    
}

public function sms_link_transcation_detail(){
    $this->layout='user';    
    $this->set('transcation_data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));
    
}





}
?>