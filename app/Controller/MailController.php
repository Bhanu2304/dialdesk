<?php
class MailController extends AppController 
{
    public $uses=array('InitialInvoice','Sms_link_transaction');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
   


    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allow('download_proforma');
        $this->Auth->allow('view_proforma_pdf');
        $this->Auth->allow(array('controller' => 'InitialInvoices', 'action' => 'view_proforma_pdf'));


    }
  
 public function index()
 {
    $this->layout='homeLayout';
    $urlpdf = Router::url('/', true).'InitialInvoices/view_proforma_pdf';
    //$urlstmt = Router::url('/', true).'app/webroot/billing_statement';
    $id = 'ODA0MQ';
    $idx = base64_decode($id);
    //echo $idx;die;
    $email ='bhanu.singh@teammas.in';
    $message = 'test invoice';
    //echo "$urlpdf/ProformaInvoice.pdf?id=$id&signID=$idx";die;

    //$file = file_get_contents("$urlpdf/ProformaInvoice.pdf?id=$id&signID=$idx");

    
    //$file_save = file_put_contents('/var/www/html/dialdesk/app/webroot/upload/test.pdf',$file);
    //echo $file_save;die;
    $attachment = '/var/www/html/dialdesk/app/webroot/upload/invoice1.pdf';

    App::uses('sendEmail', 'custom/Email');
    $mail = new sendEmail();
    $mail-> to($email,$message,$attachment);
    if($mail)
    {
        echo 'mail send';
    }else{
        echo 'not save';
    }
    die;
    //return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
 }





}
?>