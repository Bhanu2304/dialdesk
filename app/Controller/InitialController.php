<?php
class InitialController extends AppController 
{
    public $uses=array('SendInformation','Sms_link_transaction');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
   
  

public function beforeFilter()
{
    parent::beforeFilter();
    // if(!$this->Session->read("role") ==="admin"){
    //         return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
    //     }
    //     else if(empty($this->Session->read("admin_id"))){
    //         return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
    //     }
    // else
    // {
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));

            $this->Auth->allow('smssend','paymentResponse','send_payment_data','payment_url','view_sms_link','sms_link_transcation_detail');
 
    
    $this->RegistrationMaster->useDbConfig = 'dbdialdesk';
    $this->User->useDbConfig = 'dbNorth';
    $this->EditAmount->useDbConfig = 'dbNorth';
    
}




public function paymentResponse()
{
                    //echo "Payment Response";
                   

    
                //App::import('Vendor', 'AES');

    
                    $aggregator_id = "paygate";
                if(isset($_POST) && isset($_POST['code']) )
                {
                    
                    $post = $_POST;

                    
                    //$merchant_key = "pxEIPNVQg3LCIZUdWx4pNQ4bItLydFrEJ+8ajhjUfEY=";
                    
                    
                    
                    $return_elements = array();
                    
                    //Transaction Response
                    //$post['txn_response']				= isset($post['txn_response']) ? $post['txn_response'] : '';
                    	

                    if($post['merchantId'] == 'PGTESTPAYUAT') {
                        $return_elements['txn_response']['protocol'] = 'Fake';
                    } else {
                        $return_elements['txn_response']['protocol'] = 'Genuine';
                    }


                        //print_r($txn_response);die;
                    //$txn_response_arr					= explode('|', $txn_response);
                    $return_elements['txn_response']['code'] 			= isset($post['code']) ? $post['code'] : '';
                    $return_elements['txn_response']['merchantId'] 			= isset($post['merchantId']) ? $post['merchantId'] : '';
                    $return_elements['txn_response']['order_no'] 		= isset($post['transactionId']) ? $post['transactionId'] : '';
                    $return_elements['txn_response']['amount'] 			= isset($post['amount']) ? $post['amount'] : '';
                        
                    $return_elements['pg_details']['providerReferenceId'] = isset($post['providerReferenceId']);
                    
                        
                    //   echo "<pre>";
                    //   print_r($return_elements);die;
                }
                else
                {
                    //header("Location: ".$base_url); exit;
                }


                echo'<HTML>
                    <HEAD>
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    
                        <meta charset="utf-8" />

                        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

                        <title>Payment Service Provider | Merchant Accounts</title>
                        <style>
                            .has-success .form-control, .has-success .control-label, .has-success .radio, .has-success .checkbox, .has-success .radio-inline, .has-success .checkbox-inline {
                            color: #1cb78c !important;
                            }
                            .has-success .help-block {
                            color: #1cb78c !important;
                            border-color: #1cb78c !important;
                            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #1cb78c;
                            }
                            .has-error .form-control, .has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline {
                            color: #f0334d;
                            border-color: #f0334d;
                            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #f0334d;
                            }
                            table {
                            color: #333; /* Lighten up font color */
                            font-family: "Raleway", Helvetica, Arial, sans-serif;
                            font-weight: bold;
                            width: 640px;
                            border-collapse: collapse;
                            border-spacing: 0;
                            }
                            td, th {
                            border: 1px solid #CCC;
                            height: 30px;
                            } /* Make cells a bit taller */
                            th {
                            background: #F3F3F3; /* Light grey background */
                            font-weight: bold; /* Make sure theyre bold */
                            font-color: #1cb78c !important;
                            }
                            td {
                            background: #FAFAFA; /* Lighter grey background */
                            text-align: left;
                            padding: 2px;/* Center our text */
                            }
                            label {
                            font-weight: normal;
                            display: block;
                            }
                        </style>
                    </HEAD>
                    <BODY>
                        <div class="container cs-border-light-blue">
                            <!-- first line -->
                            <div class="row pad-top  text-center"></div>
                            <!-- end first line -->

                            <div class="jumbotron text-center">
                            <img src="https://dialdesk.co.in/dialdesk/css/assets/img/logo.png">
                                <h1 class="display-3">Thank You!</h1>
                                <p class="lead">Your payment is <strong>'.$return_elements['txn_response']['amount'].'</strong> Here is the details for it.</p>
                                <hr>
                                <div class="row text-center">

                                <div class="col-md-6  col-sm-offset-3">

                                <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Response</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Order Number</td> 
                                    <td>'.$return_elements['txn_response']['order_no'].'</td>            
                                </tr>
                                <tr>
                                <td>Amount</td>
                                <td>'.$return_elements['txn_response']['amount'].'</td>
                                    
                                </tr>
                                <tr>
                                    <td>Transaction Status</td>
                                    <td>'.$return_elements['txn_response']['code'].'</td>
                                    
                                </tr>
                                <tr>
                                        <td>Transaction Date and Time</td>
                                        <td>'.date('d-m-Y H:i:s').'</td>
                                </tr>
                                </tbody>
                        </table>
                            <p class="lead">'; ?>
                                <button class="btn btn-primary btn-sm"  onclick="window.open('', '_self', ''); window.close();" role="button">Please Close this window</button>
                    <?php echo '</p>
                                
                                </div>

                                </div>
                                
                            </div>


                        </div>
                        </form>
                    </BODY>
                    </HTML>';

               
               
            
                            $ord_no = $return_elements['txn_response']['order_no'];
                            $amnt = $return_elements['txn_response']['amount'];
                            $trans_pg = $return_elements['txn_response']['pg_ref'];
                            $trans_ag = $return_elements['txn_response']['ag_ref'];
                            $trans_status = $return_elements['txn_response']['status'];
                            $trans_date_time = $return_elements['txn_response']['txn_date'].' '.$return_elements['txn_response']['txn_time'];
                            $protocol = $return_elements['txn_response']['protocol'];
                            
                            if(!empty($ord_no))
                            {

                                $dataX = array(
                                    'ord_number' =>   $ord_no, 
                                    'amount' =>   $amnt, 
                                    'trans_pg' =>   $trans_pg, 
                                    'trans_ag' =>   $trans_ag, 
                                    'trans_status' =>   $trans_status, 
                                    'trans_date_time' =>   $trans_date_time, 
                                    'protocol' =>   $protocol, 
                                );
                                    //print_r($dataX);exit;
                                $this->Sms_link_transaction -> save($dataX);

                                $udate = date('Y-m-d h:i:s');

                                $dataY = array('status'=>$trans_status,'update_date'=>$udate);
                                $this->SendInformation->updateAll($dataY,array('order_no' =>$ord_no));

                            }

 }

public function view_sms_link(){
    $this->layout='user';
    $this->set('data',$this->SendInformation->find('all',array('order'=>array('id'=>'desc'))));
    $this->set('transcation_data',$this->Sms_link_transaction->find('all',array('order'=>array('id'=>'desc'))));
    
}

public function sms_link_transcation_detail(){
    $this->layout='user';  
    $data = $this->Sms_link_transaction->query("SELECT * FROM `sms_link_transaction` AS slt INNER JOIN `sms_information` si ON slt.ord_number = si.order_no ORDER BY slt.id DESC");  
    
    $this->set('transcation_data',$data);
    
}



}
?>