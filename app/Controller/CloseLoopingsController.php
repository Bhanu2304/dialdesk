<?php
class CloseLoopingsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ClientCategory','CloseLoopMaster','PlanMaster','BalanceMaster','PaymentOrderNo','RegistrationMaster');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow(
                'get_sub_type',
                'save_close_loop',
                'view_close_loop',
                'edit_close_loop',
                'edit',
                'editSelectCategory',
                'closeloop',
                'selectCategory',
                'delete_close_loop',
                'fetchCategoryTree',
                'get_parent_closeloop',
                'scenarios_name',
                'get_parent',
                'get_parent_action',
                'edit_parent_action',
                'payment_gateway',
                'update_close_loop','update_payment_det','index2');
        
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
  
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $getPlan = $this->BalanceMaster->find('first',array('conditions'=>"clientId='$ClientId'"));
        $PlanId = $getPlan['BalanceMaster']['PlanId'];
        
        $PaymentGateway = $this->PlanMaster->find('first',array('conditions'=>"Id='$PlanId'"));
        $this->set('PaymentGatewayType',$PaymentGateway['PaymentGateWayType']);
        $this->set('PaymentGateWayCharge',$PaymentGateway['PaymentGateWayCharge']);
        
        
        $this->set('category',$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId))));
        $result = $this->CloseLoopMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'))));
        
        $arrData=array();
        foreach($result as $row){
             $parent_id=$this->get_parent($row['CloseLoopMaster']['parent_id'],$ClientId);
            $arrData[]=array(
                'id'=>$row['CloseLoopMaster']['id'],
                'CategoryName1'=>$row['CloseLoopMaster']['CategoryName1'],
                'CategoryName2'=>$row['CloseLoopMaster']['CategoryName2'],
                'CategoryName3'=>$row['CloseLoopMaster']['CategoryName3'],
                'CategoryName4'=>$row['CloseLoopMaster']['CategoryName4'],
                'CategoryName5'=>$row['CloseLoopMaster']['CategoryName5'],
                'close_loop'=>$row['CloseLoopMaster']['close_loop'],
                'close_loop_category'=>$parent_id !=""?$parent_id:$row['CloseLoopMaster']['close_loop_category'],
                'close_loop_sub_category'=>$parent_id !=""?$row['CloseLoopMaster']['close_loop_category']:"",
                'close_looping_date'=>$row['CloseLoopMaster']['close_looping_date']
            );
        }
        $this->set('data',$arrData);  
    }

    public function index2() {
        $this->layout='popuser';
        #$ClientId = $_GET['client_id'];
        $ClientId = $this->Session->read('companyid');
        $getPlan = $this->BalanceMaster->find('first',array('conditions'=>"clientId='$ClientId'"));
        #print_r($getPlan);die;
        $PlanId = $getPlan['BalanceMaster']['PlanId'];
        
        $PaymentGateway = $this->PlanMaster->find('first',array('conditions'=>"Id='$PlanId'"));
        $this->set('PaymentGatewayType',$PaymentGateway['PaymentGateWayType']);
        $this->set('PaymentGateWayCharge',$PaymentGateway['PaymentGateWayCharge']);
        
        
        $this->set('category',$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId))));
        $result = $this->CloseLoopMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'))));
        
        $arrData=array();
        foreach($result as $row){
             $parent_id=$this->get_parent($row['CloseLoopMaster']['parent_id'],$ClientId);
            $arrData[]=array(
                'id'=>$row['CloseLoopMaster']['id'],
                'CategoryName1'=>$row['CloseLoopMaster']['CategoryName1'],
                'CategoryName2'=>$row['CloseLoopMaster']['CategoryName2'],
                'CategoryName3'=>$row['CloseLoopMaster']['CategoryName3'],
                'CategoryName4'=>$row['CloseLoopMaster']['CategoryName4'],
                'CategoryName5'=>$row['CloseLoopMaster']['CategoryName5'],
                'close_loop'=>$row['CloseLoopMaster']['close_loop'],
                'close_loop_category'=>$parent_id !=""?$parent_id:$row['CloseLoopMaster']['close_loop_category'],
                'close_loop_sub_category'=>$parent_id !=""?$row['CloseLoopMaster']['close_loop_category']:"",
                'close_looping_date'=>$row['CloseLoopMaster']['close_looping_date']
            );
        }
        $this->set('data',$arrData);  
    }
    
    
    
    public function get_parent($parent_id,$ClientId){
        $data=$this->CloseLoopMaster->find('first',array('fields'=>array("close_loop_category"),'conditions'=>array('label'=>1,'id'=>$parent_id,'client_id'=>$ClientId)));
        return $data['CloseLoopMaster']['close_loop_category'];
    }
    
    public function get_parent_action(){
        if(isset($_REQUEST['close_loop'])){
            $ClientId = $this->Session->read('companyid');
            $parent_category = $this->CloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('close_loop'=>$_REQUEST['close_loop'],'label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId)));
        ?>
            <option value="">Select Parent Action</option>
            <?php foreach($parent_category as $key=>$catval){?>
                <option value="<?php echo $key;?>"><?php echo $catval;?></option>
            <?php }?>
        <?php
        }
        die;
    }
    
    public function edit_parent_action(){
        if(isset($_REQUEST['close_loop'])){
            $ClientId = $this->Session->read('companyid');
            $parent_category = $this->CloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('close_loop'=>$_REQUEST['close_loop'],'label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId)));
        ?>
            <option value="">Select Parent Action</option>
            <?php foreach($parent_category as $key=>$catval){?>
                <option <?php if(isset($_REQUEST['sd']) && $_REQUEST['sd'] ==$key){echo "selected='selected'";}?> value="<?php echo $key;?>"><?php echo $catval;?></option>
            <?php }?>
        <?php
        }
        die;
    }

    





    public function save_close_loop(){
        if($this->request->is('Post')){
            $clientid=$this->Session->read('companyid');
            $data=$this->request->data;

            
            if($data['parent_id'] ==""){
               unset($data['parent_id']); 
            }
            
            if($data['orderno'] ==""){
               unset($data['orderno']); 
            }
            

            //$clc  = $data['close_loop_category'][0];
            //$clsc = $data['close_loop_category'][1];
            
            $data['client_id']=$clientid;
            //$data['close_loop_category']=$clc;
            //$data['close_loop_sub_category']=$clsc;
            
            $exist=$data;
            unset($exist['close_loop_sub_category']);
            unset($exist['close_looping_date']);
            
            $exsystem=$exist;
            $exsystem['close_loop']="manual";
            unset($exsystem['close_loop_category']);
           
            $exmanual=$exsystem;
            $exmanual['close_loop']="system";
            
            if($data['Category1'] !=""){$data['CategoryName1']=$this->scenarios_name($data['Category1'],$clientid);}
            if($data['Category2'] !=""){$data['CategoryName2']=$this->scenarios_name($data['Category2'],$clientid);}
            if($data['Category3'] !=""){$data['CategoryName3']=$this->scenarios_name($data['Category3'],$clientid);}
            if($data['Category4'] !=""){$data['CategoryName4']=$this->scenarios_name($data['Category4'],$clientid);}
            if($data['Category5'] !=""){$data['CategoryName5']=$this->scenarios_name($data['Category5'],$clientid);}
           
             
            if($this->CloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exist))){
                $this->Session->setFlash("Already exist in database.");
                $this->redirect(array('action' => 'index'));
            }
            else if($data['close_loop']=="system" && $this->CloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exsystem))){
                    $this->Session->setFlash("Already created for manual In Call Action.");
                    $this->redirect(array('action' => 'index'));
            }
            else if($data['close_loop']=="manual" && $this->CloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exmanual))){
                    $this->Session->setFlash("Already created for system In Call Action.");
                    $this->redirect(array('action' => 'index'));
            }
            else{
                $this->CloseLoopMaster->save($data);
                $this->Session->setFlash("<span class='green'>In Call Action created successfully.</span>");
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    public function getCategory($id,$cid){
        if($id ==="All"){
            return "All";
        }
        else{
            $data=$this->ClientCategory->find('first',array('fields'=>array("ecrName"),'conditions'=>array('id'=>$id,'Client'=>$cid))); 
            if(!empty($data)){
                return $data['ClientCategory']['ecrName'];
            }
        }
    }
    
    public function delete_close_loop(){
        $id=$this->request->query['id'];
        $this->CloseLoopMaster->deleteAll(array('id'=>$id,'client_id' => $this->Session->read('companyid')));
        $this->redirect(array('action' => 'index'));
    }
    
    
    public function selectCategory(){  
        $ClientId = $this->Session->read('companyid');
        if($_REQUEST['parent_id'] !="0"){
            $subcategory =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$_REQUEST['parent_id'],'Client'=>$ClientId)));           
            if(!empty($subcategory)){
                $_REQUEST['divid'] ==="2"?$html="<option value=''>Select Sub Scenario 1</option>":"";
                $_REQUEST['divid'] ==="3"?$html="<option value=''>Select Sub Scenario 2</option>":"";
                $_REQUEST['divid'] ==="4"?$html="<option value=''>Select Sub Scenario 3</option>":"";
                $_REQUEST['divid'] ==="5"?$html="<option value=''>Select Sub Scenario 4</option>":"";
                echo $html;
                echo "<option value='All'>All</option>";
                foreach($subcategory as $key=>$value){              
                    echo "<option value='$key'>".$value."</option>";
                }
            }
        }
        die;
    }
    
      public function editSelectCategory(){  
        $ClientId = $this->Session->read('companyid');
        if($_REQUEST['parent_id'] !="0"){
            $subcategory =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$_REQUEST['parent_id'],'Client'=>$ClientId)));           
            if(!empty($subcategory)){?>
                <option <?php if($_REQUEST['cid'] =="All"){?> selected="selected"<?php }?> value="All" >All</option>
                <?php 
                foreach($subcategory as $key=>$value){  
                    ?>
                    <option <?php if($_REQUEST['cid'] ==$key){?> selected="selected"<?php }?> value="<?php echo $key;?>" ><?php echo $value;?></option>
                    <?php
                }die;
            }
        } 
        die;
    }
    
    public function edit(){
        if(isset($_REQUEST['id'])){
            $ClientId = $this->Session->read('companyid');
            $this->set('result',$this->CloseLoopMaster->find('first',array('conditions' =>array('id' =>$_REQUEST['id'],'client_id' =>$ClientId))));
            $this->set('category',$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId))));
            $this->set('parent_category',$this->CloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId))));
        }
    }
    
    public function update_close_loop(){
        if($this->request->is('Post')){
            $clientid=$this->Session->read('companyid');
            $data=$this->request->data;
            
             if($data['parent_id'] ==""){
               unset($data['parent_id']); 
            }
            
            if($data['orderno'] ==""){
               unset($data['orderno']); 
            }
            
           
            
                
           // $clc        = $data['close_loop_category'][0];
            //$clsc       = $data['close_loop_category'][1];
            $closeId    = $data['id'];
            
            $data['client_id']=$clientid;
            //$data['close_loop_category']=$clc;
            //$data['close_loop_sub_category']=$clsc;
            
           $c1= isset($data['Category1'])?$data['Category1']:"";
           $c2= isset($data['Category2'])?$data['Category2']:"";
           $c3= isset($data['Category3'])?$data['Category3']:"";
           $c4= isset($data['Category4'])?$data['Category4']:"";
           $c5= isset($data['Category5'])?$data['Category5']:"";
           
           $cn1= isset($data['Category1'])?$this->scenarios_name($data['Category1'],$clientid):"";
           $cn2= isset($data['Category2'])?$this->scenarios_name($data['Category2'],$clientid):"";
           $cn3= isset($data['Category3'])?$this->scenarios_name($data['Category3'],$clientid):"";
           $cn4= isset($data['Category4'])?$this->scenarios_name($data['Category4'],$clientid):"";
           $cn5= isset($data['Category5'])?$this->scenarios_name($data['Category5'],$clientid):"";
            
            $orderby= isset($data['orderby'])?$data['orderby']:"";
            $orderno= isset($data['orderno'])?$data['orderno']:"";
            $InCallStatus= isset($data['InCallStatus'])?$data['InCallStatus']:"";
            
            
            $dataArr=array(
                'Category1'=>"'".$c1."'",
                'Category2'=>"'".$c2."'",
                'Category3'=>"'".$c3."'",
                'Category4'=>"'".$c4."'",
                'Category5'=>"'".$c5."'",
                'CategoryName1'=>"'".$cn1."'",
                'CategoryName2'=>"'".$cn2."'",
                'CategoryName3'=>"'".$cn3."'",
                'CategoryName4'=>"'".$cn4."'",
                'CategoryName5'=>"'".$cn5."'",
                'close_loop'=>"'".$data['close_loop']."'",
                //'parent_id'=>"'".$data['parent_id']."'",
                //'label'=>"'".$data['label']."'",
                'close_loop_category'=>"'".$data['close_loop_category']."'",
                'orderby'=>"'".$orderby."'",
                'orderno'=>"'".$orderno."'",
                'InCallStatus'=>"'".$InCallStatus."'",
                'close_looping_date'=>"'".$data['close_looping_date']."'" 
            );
            
             
            $exist=$data;
            unset($exist['id']);
            unset($exist['close_loop_sub_category']);
            unset($exist['close_looping_date']);
            $exist['id !=']=$closeId;
            
            $exsystem=$exist;
            unset($exsystem['id']);
            unset($exsystem['close_loop_category']);
            $exsystem['close_loop']="manual";
            $exsystem['id !=']=$closeId;
           
            $exmanual=$exsystem;
            unset($exmanual['id']);
            $exmanual['id !=']=$closeId;
            $exmanual['close_loop']="system";

            if($this->CloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exist))){
                echo "1";die;
            }
            else if($data['close_loop']=="system" && $this->CloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exsystem))){
                echo "2";die;
            }
            else if($data['close_loop']=="manual" && $this->CloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exmanual))){
                echo "3";die;
            }
            else{
                $this->CloseLoopMaster->updateAll($dataArr,array('id'=>$closeId,'client_id' =>$clientid));
                echo "4";die;
            }
        }
  
    }
    
    
    
    
    public function scenarios_name($id,$cid){
        if($id ==="All"){
            return "All";
        }
        else{
            $data=$this->ClientCategory->find('first',array('fields'=>array("ecrName"),'conditions'=>array('id'=>$id,'Client'=>$cid))); 
            if(!empty($data)){
                return $data['ClientCategory']['ecrName'];
            }
        }
    }
    
    
    public function getEcrid($cat,$lab,$cid){
        $Category =$this->ClientCategory->find('first',array('fields'=>array("id"),'conditions'=>array('ecrName'=>$cat,'Label'=>$lab,'Client'=>$cid)));
        return $Category['ClientCategory']['id'];   
    }
    
    
    
	
    public function get_sub_type(){
        if($_REQUEST['id']){
            $ClientId = $this->Session->read('companyid');
            $subcategory =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$_REQUEST['id'],'Client'=>$ClientId)));
            echo "<option value=''>Select Sub Type</option>";
            foreach($subcategory as $key=>$value){              
                echo "<option value='$key'>".$value."</option>";
            }
            die;
        }
    }

    public function get_parent_closeloop(){
        $this->layout='ajax';
        if($_REQUEST['close_loop']){
            $ClientId = $this->Session->read('companyid');
            $data=array(
                'Category1'=>$_REQUEST['cat1'],
                'Category2'=>$_REQUEST['cat2'],
                'Category3'=>$_REQUEST['cat3'],
                'Category4'=>$_REQUEST['cat4'],
                'Category5'=>$_REQUEST['cat5'],
                'close_loop'=>$_REQUEST['close_loop'],
                'parent_id'=>NULL,
                'client_id'=>$this->Session->read('companyid')
            );  
            $this->set('data',$this->CloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>$data)));
        }
    }
	
    

    public function edit_close_loop(){
            $this->layout='user';
            $ClientId = $this->Session->read('companyid');
            $Category =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>NULL,'Client'=>$ClientId)));
            $result = $this->CloseLoopMaster->find('first',array('conditions' =>array('id' => $this->request->query['id'],'client_id' => $this->Session->read('companyid'))));
            $subcat=$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$result['CloseLoopMaster']['type'],'Client'=>$ClientId)));
            $loopcat = $this->fetchCategoryTree();
            $this->set('data',$loopcat);
            $this->set('subcat',$subcat);
            $this->set('category',$Category);
            $this->set('get_close_loop',$result);
    }

    public function fetchCategoryTree($parent =0, $spacing = '', $user_tree_array = '') {
            if (!is_array($user_tree_array))
                    $user_tree_array = array();
                    $cid=$this->Session->read('companyid');
                    $qry="SELECT id,close_loop_category FROM closeloop_master WHERE 1 AND parent_id = $parent AND client_id=$cid ORDER BY id ASC";
                    $query=$this->CloseLoopMaster->query($qry);
                    if (count($query) > 0) {
                            foreach($query as $row){
                                    $user_tree_array[] = array("id" => $row['closeloop_master']['id'], "name" => $spacing . $row['closeloop_master']['close_loop_category']);
                                    $user_tree_array = $this->fetchCategoryTree($row['closeloop_master']['id'], $spacing.'&nbsp;&nbsp;', $user_tree_array);
                            }
                    }
                    return $user_tree_array;
    }
    

    public function payment_gateway()
    {
        if(isset($_REQUEST['id'])){
            $ClientId = $this->Session->read('companyid');
            $this->set('ClientId',$ClientId);
            $this->set('id',$_REQUEST['id']);
            $this->set('result',$this->CloseLoopMaster->find('first',array('conditions' =>array('id' =>$_REQUEST['id'],'client_id' =>$ClientId))));
            $this->set('category',$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId))));
            $this->set('parent_category',$this->CloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId))));
            //$this->set('PaymentDetails',$this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id']),'order'=>array('OrderId'=>'desc'))));
            $PaymentDetails = $this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id']),'not'=>array('PaymentStatus'=>'Not Paid')));
            if(empty($PaymentDetails))
            {
                $PaymentDetails = $this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id']),'order'=>array('OrderId'=>'desc')));
                //$this->set('PaymentDetails',$PaymentDetails);
            }
            $this->set('PaymentDetails',$PaymentDetails);
            
            //print_r($this->PaymentOrderNo->find('first',array('conditions'=>array('CaseId'=>$_REQUEST['id'])))); exit;
            
        }
        
        
    }
    
    public function update_payment_det()
    {
        $msg = "";
        if($this->request->is('POST'))
        {
            $PaymentOrderNo['CaseId'] = $Id = $this->request->data['id'];
        $PaymentOrderNo['ClientId'] = $ClientId = $this->request->data['ClientId'];
        //$PaymentOrderNo['Category1'] =$Category1 = $this->request->data['Category1'];
        $PaymentOrderNo['CustomerName'] =$CustomerName = $this->request->data['CustomerName'];
        $PaymentOrderNo['SendType'] =$SendType = $this->request->data['SendType'];
        $PaymentOrderNo['EEmailId'] =$EEmailId = $this->request->data['EEmailId'];
        $PaymentOrderNo['MMobile'] =$MMobile = $this->request->data['MMobile'];
        $PaymentOrderNo['OrderAmount'] = $OrderAmount = $this->request->data['OrderAmount'];
        $PaymentOrderNo['Product'] = $Product = $this->request->data['Product'];
        
        $ClientDetails = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
       
        $ClientName = $ClientDetails['RegistrationMaster']['company_name']; 
        $NewClientName = str_replace(" ","_",$ClientName);
        
        
        
        if($this->PaymentOrderNo->save($PaymentOrderNo))
        {
            $OrderId = $this->PaymentOrderNo->getLastInsertID();
            $OrderNo = $NewClientName."_$OrderId";
            
            if($this->PaymentOrderNo->updateAll(array('OrderNo'=>"'$OrderNo'"),array('OrderId'=>$OrderId)))
            {
                
                //$dataJson=array("encypted_request"=>)
                $url = "http://www.paypik.in/app/webroot/api/apis.php?req_id=39";
                
                App::uses('HttpSocket', 'Network/Http');
                $HttpSocket = new HttpSocket();
                
                $response = $HttpSocket->post($url,['apiType'=>$SendType,'CustomerName'=>$CustomerName,'EmailId'=>$EEmailId,'MobileNo'=>$MMobile,'InvoiceAmount'=>$OrderAmount,'OrderNo'=>$OrderNo,'Product'=>$Product]);
                
                if($response=='Order Id already exists')
                {
                    $this->Session->setFlash("Order Id already Exists");
                    $msg = "Order Id already Exists";
                }
                else if($response=='Error while request !')
                {
                    $this->Session->setFlash("Error while request !");
                    $msg = "Error while request !";
                }
                else if($response=='Invalid Email !')
                {
                    $this->Session->setFlash("Invalid Email !");
                    $msg = "Invalid Email !";
                }
                else if($response=='Invalid Mobile No !')
                {
                    $this->Session->setFlash("Invalid Mobile No !");
                    $msg = "Invalid Mobile No !";
                }
                else
                {
                    $updData = array();
                    $responseObj = json_decode($response);
                    $updData['ResponseId'] = $id = "'".addslashes($responseObj->id)."'";
                    $updData['Responseamount'] = $amount = "'".addslashes($responseObj->amount)."'";
                    $updData['country'] = $country = "'".addslashes($responseObj->country)."'";
                    $updData['currency'] = $currency = "'".addslashes($responseObj->currency)."'";
                    $updData['customereMail'] = $customereMail = "'".addslashes($responseObj->customereMail)."'";
                    $updData['meId'] = $meId = "'".addslashes($responseObj->meId)."'";
                    $updData['mediaType'] = $mediaType = "'".addslashes($responseObj->mediaType)."'";
                    $updData['mobileNo'] = $mobileNo = "'".addslashes($responseObj->mobileNo)."'";
                    $updData['ResponseorderId'] = $orderId = "'".addslashes($responseObj->orderId)."'";
                    $updData['token'] = $token = "'".addslashes($responseObj->token)."'";
                    $updData['product'] = $product = "'".addslashes($responseObj->product)."'";
                    $updData['firstName'] = $firstName = "'".addslashes($responseObj->firstName)."'";
                    $updData['lastName'] = $lastName = "'".addslashes($responseObj->lastName)."'";
                    $updData['creationDate'] = $creationDate = "'".addslashes($responseObj->creationDate)."'";
                    $updData['createdBy'] = $createdBy = "'".addslashes($responseObj->createdBy)."'";
                    $updData['expiryDate'] = $expiryDate = "'".addslashes($responseObj->expiryDate)."'";

                    if($this->PaymentOrderNo->updateAll($updData,array('OrderId'=>$OrderId)))
                    {
                        $this->Session->setFlash("Request Send Succesfuly.");
                        $this->set('resend','resend');
                        $msg = "Request Send Succesfuly.";
                    }
                    else
                    {
                        $this->Session->setFlash("Request Not Send! Please Try Again.");
                    }
                }
                
            }
            
            
            
            
        }
        }
        
        


    $act=$this->webroot.'CloseLoopings';
    $script=$this->webroot.'js/jquery-1.12.4.min.js';
    $url1=$this->webroot.'CloseLoopings/update_payment_det'; 
    $url2=$this->webroot.'CloseLoopings'; 
    
    echo "
        <link rel='stylesheet' href='$scrcs1'>                                      
        <script src='$script'></script>
        <script type=\"text/javascript\">
         $( document ).ready(function() {
         myFunction();

         $('#close-loop-popup').trigger('click');
            $('#taggingmsg').trigger('click');
            $('#tagging-text-message').text('$msg');
         });
         
        function myFunction() { 
            if (confirm('$msg Are you sure you want to continue.')) {
                window.location.replace('$url1');
            } else {
                window.location.replace('$url2');
            }
        }
        </script>
        ";
        die;
        
    }

    
}
?>