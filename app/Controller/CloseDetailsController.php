<?php
class CloseDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('CloseFieldData','CloseFieldDataValue','CloseUpdate','FieldMaster','FieldValue','EcrMaster','CallMaster','CloseLoopMaster','CloseStatusHistory');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function view_close_fields() {
        if(isset($_REQUEST['id'])){  
            $this->layout='user';
            $clientId = $this->Session->read('companyid');
			
			
			$role = $this->Session->read('role');
			if($role=="agent"){
				$userby	=	$role."-".$this->Session->read('agent_username');
			}
			else if($role=="client"){
				$userby	=	$role."-".$this->Session->read('email');
			}
			else if($role=="admin"){
				$userby	=	$role."-".$this->Session->read('admin_email');
			}
			
			

            //--------------------------------------------------------------------------
            $id=$_REQUEST['id'];
			
			$this->set('close_loop_image',$this->CallMaster->query("SELECT * FROM close_loop_image WHERE Call_Master_Id='$id'"));
			
			
			
            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            
            $fieldSearch = $this->FieldMaster->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        foreach($fieldSearch as $f)
        {
            $headervalue[] = 'Field'.$f; 
        } 
        $fieldSearch = array_merge(array('Id','SrNo','MSISDN','Category1','Category2','Category3','Category4','Category5'),$headervalue,
                array('LeadId','CallType', 'CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated','CloseLoopStatus','CFieldUpdate','AWBNo','Ret_AWBNo','Ret_DestinationLocation','OrderNo'));
        //$fieldSearch = implode(",",$fieldSearch);
            
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
            $this->set('ecr',$ecr);

            $data = $this->CallMaster->find('first',array('fields'=>$fieldSearch,'conditions'=>array('ClientId'=>$clientId,'Id'=>$id),'order'=>array('CallDate'=>'desc')));
            $this->set('history',$data);
            $this->set('headervalue',$headervalue);
            
       
            $newArr=$this->CloseLoopMaster->query("SELECT clm.close_loop_category,clm.id,clm.orderby,clm.orderno FROM closeloop_master clm
            INNER JOIN call_master cm ON 
            CONCAT(IF(clm.CategoryName1='All',1,CONCAT(IF(clm.CategoryName1 !='',cm.Category1,''),IF(clm.CategoryName2='All',1,CONCAT(IF(clm.CategoryName2 !='',cm.Category2,''),IF(clm.CategoryName3='All',1, CONCAT(IF(clm.CategoryName3 !='',cm.Category3,''),IF(clm.CategoryName4='All',1, CONCAT(IF(clm.CategoryName4 !='',cm.Category4,''),IF(clm.CategoryName5='All',1,IF(clm.CategoryName5 !='',cm.Category5,''))))))))))) = 
            CONCAT(IF(clm.CategoryName1='All',1,CONCAT(clm.CategoryName1,IF(clm.CategoryName2='All',1,CONCAT(clm.CategoryName2,IF(clm.CategoryName3='All',1, CONCAT(clm.CategoryName3,IF(clm.CategoryName4='All',1, CONCAT(clm.CategoryName4,IF(clm.CategoryName5='All',1,clm.CategoryName5) ))))))))) 
            WHERE clm.client_id='$clientId' AND clm.close_loop='manual' AND clm.label='1' AND cm.Id='$id' order by clm.orderno");
            
            /*
            $newArr=$this->CloseLoopMaster->query("SELECT clm.close_loop_category,clm.id,clm.orderby,clm.orderno FROM closeloop_master clm
            INNER JOIN call_master cm
            ON CONCAT(IF(clm.CategoryName1='All',1,CONCAT(cm.Category1,IF(clm.CategoryName2='All',1,CONCAT(cm.Category2,IF(clm.CategoryName3='All',1,
            CONCAT(cm.Category3,IF(clm.CategoryName4='All',1,
            CONCAT(cm.Category4,IF(clm.CategoryName5='All',1,cm.Category5)
            ))))))))) = CONCAT(IF(clm.CategoryName1='All',1,CONCAT(clm.CategoryName1,IF(clm.CategoryName2='All',1,CONCAT(clm.CategoryName2,IF(clm.CategoryName3='All',1,
            CONCAT(clm.CategoryName3,IF(clm.CategoryName4='All',1,
            CONCAT(clm.CategoryName4,IF(clm.CategoryName5='All',1,clm.CategoryName5)
            )))))))))
            WHERE clm.client_id='$clientId' AND clm.close_loop='manual' AND clm.label='1' AND cm.Id='$id' order by clm.orderno");
            */
            
            $this->set('mpcat',$newArr);
            
            
            /*
            $orderArr=array();
            foreach($newArr as $value){
                $orderArr[][$value['clm']['orderby']]=$value['clm']['orderno'];
            }
             
            */
            
            //print_r($orderArr);die;
            
            
            
            
            $CloseUpdateList = $this->CloseStatusHistory->find('list',array('fields'=>array('CloseLoopId'),'conditions'=>array('ClientId'=>$clientId,'CallMasterId'=>$id)));
            $this->set('CloseUpdateList',$CloseUpdateList);
            
            $CsUpdate = $this->CloseStatusHistory->find('all',array('conditions'=>array('ClientId'=>$clientId,'CallMasterId'=>$id)));
            $this->set('CsUpdate',$CsUpdate);
          
           
            
           //--------------------------------------------------------------------------
           
            $fieldValue1 = $this->CloseFieldDataValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $fieldName1 = $this->CloseFieldData->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $CArr = $this->CloseUpdate->find('first',array('conditions'=>array('Id'=>$_REQUEST['id'],'ClientId' =>$clientId)));
            
            $this->set('fieldName1',$fieldName1);
            $this->set('fieldValue1',$fieldValue1);
            $this->set('callId',$_REQUEST['id']);
            $this->set('CArr',$CArr['CloseUpdate']);
            
            
            $AwbRet=$data['CallMaster']["AWBNo"];
            $AwbFor=$data['CallMaster']["Ret_AWBNo"];
            
            //$AwbRet="75409313151";
            //$AwbFor="75409313151";
            
            //$ReturnShippingStatus=$this->bluedartstatusreturn($AwbRet);
           // $ForwordShippingStatus=$this->bluedartstatusforword($AwbFor);
            
            
            $this->set('ReturnShippingStatus','');
            $this->set('ForwordShippingStatus','');
            
        }	
    }

    public function update_customer_field(){ 
        if($this->request->is("POST")){  
            $ClientId   =   $this->Session->read('companyid');
            $Id         =   $this->request->data['Id'];
            $data       =   $this->request->data['CloseDetails'];
			

            foreach($data as $kay=>$val){ 
                $dataArr[$kay]="'".addslashes($val)."'";          
            }
            
            $this->CallMaster->updateAll($dataArr,array('Id'=>$Id,'ClientId' =>$ClientId));
            $this->Session->setFlash('Your data update successfully.');
            $this->redirect(array('controller'=>'CloseDetails','action' => 'view_close_fields','?'=>array('id'=>$Id)));
        }
    }
    
    public function getcategorylist(){
        $ClientId   =   $this->Session->read('companyid');
        $ecrName    =   $_REQUEST['ecrName'];
        $value      =   $_REQUEST['value'];
        $Label      =   $_REQUEST['Label'];
        
        $conditions =   array('Client'=>$ClientId,'ecrName'=>$ecrName,'Label'=>$Label-1);
        $ecr        =   $this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>$conditions));
        $parent_id  =   isset($Label) && $Label ==="1"?NULL:$ecr['EcrMaster']['id'];

        $data       =   $this->EcrMaster->find('list',array('fields'=>array('id','ecrName'),'conditions'=>array('Client'=>$ClientId,'Label'=>$Label,'parent_id'=>$parent_id)));
        
        if(!empty($data)){
            echo "<option value=''>Select</option>";
            foreach($data as $key=>$val){
                $selected   =   $value ==$val?"selected='selected'":"";
                echo "<option $selected value='$val'>$val</option>";
            }
        }
        else{
            echo "<option value=''>Select</option>";
        }
        die;
    }
    
    public function editcategorylist(){
        $ClientId   =   $this->Session->read('companyid');
        $ecrName    =   $_REQUEST['ecrName'];
        $Label      =   $_REQUEST['Label'];
        
        $conditions =   array('Client'=>$ClientId,'ecrName'=>$ecrName,'Label'=>$Label);
        $ecr        =   $this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>$conditions));
        $parent_id  =   $ecr['EcrMaster']['id'];

        $data       =   $this->EcrMaster->find('list',array('fields'=>array('id','ecrName'),'conditions'=>array('Client'=>$ClientId,'Label'=>$Label+1,'parent_id'=>$parent_id)));
        
        if(!empty($data)){
            echo "<option value=''>Select</option>";
            foreach($data as $key=>$val){
                $selected   =   $value ==$val?"selected='selected'":"";
                echo "<option $selected value='$val'>$val</option>";
            }
        }
        else{
            echo "<option value=''>Select</option>";
        }
        die;
    }
    
    
    
    public function update_srclose_field(){ 
        if($this->request->is("POST")){  
            $ClientId = $this->Session->read('companyid');
            $Id=$this->request->data['Id'];
            $data=$this->request->data['CloseDetails'];
       
            foreach($data as $kay=>$val){ 
                $dataArr[$kay]="'".addslashes($val)."'";          
            }
            $dataArr['CFieldUpdate']="'".date('Y-m-d H:i:s')."'";          
           //print_r($dataArr); exit;         
            $this->CloseUpdate->updateAll($dataArr,array('Id'=>$Id,'ClientId' =>$ClientId));
            $this->Session->setFlash('Your data update successfully.');
            $this->redirect(array('controller'=>'CloseDetails','action' => 'view_close_fields','?'=>array('id'=>$Id)));
        }
    }
    
    public function checkorder(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $CallMasterId=$_REQUEST['CallMasterId'];
            $data=$this->CloseLoopMaster->find('first',array('fields'=>array('orderby','orderno'),'conditions'=>array('id'=>$_REQUEST['id'],'client_id'=>$this->Session->read('companyid'))));
            $orderStatus=$data['CloseLoopMaster']['orderby'];
            $orderNo=$data['CloseLoopMaster']['orderno'];
            if($orderStatus =="Yes"){
                $orderList=$this->CloseLoopMaster->find('list',array('fields'=>array('orderno'),'conditions'=>array('orderby'=>'yes','client_id'=>$this->Session->read('companyid'))));
                $orderUpdateList = $this->CloseStatusHistory->find('list',array('fields'=>array('OrderNo'),'conditions'=>array('ClientId'=>$this->Session->read('companyid'),'CallMasterId'=>$CallMasterId,'OrderStatus'=>'Yes')));
                
                if(!empty($orderUpdateList)){
                    $TotalOrderNo=array_diff($orderList,$orderUpdateList); 
                }
                else{
                  $TotalOrderNo=$orderList;   
                }
                
                if($orderNo ==min($TotalOrderNo)){
                   echo "1";die;
                }
                else{
                    echo "";die;
                }
            }
            else{
                echo "1";die; 
            }
        }
        
    }
    
    
    public function bluedartstatusreturn($number){
        $url="http://www.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=NDA61542&awb=awb&numbers=$number&format=html&lickey=j35ustq6qpjrhlqtunpftfuiqssklfl3&verno=1.3&scan=1";
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        return $response = curl_exec($ch);
    }
    
    public function bluedartstatusforword($number){
        $url="http://www.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=NDA61542&awb=awb&numbers=$number&format=html&lickey=j35ustq6qpjrhlqtunpftfuiqssklfl3&verno=1.3&scan=1";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        return $response = curl_exec($ch);
    }
    
    /*
    public function bluedartapi(){

        $lastid=$_REQUEST['lastid'];

        $msg="";
        $dataArr=$this->CallMaster->query("SELECT * FROM call_master WHERE Id='$lastid' limit 1");
        $data=$dataArr[0]['call_master'];


        //echo "<pre>";
        //print_r($data);die;

        $ClientId           =   $data['ClientId'];
        $Category1          =   $data['Category1'];

        if($ClientId =="277" && $Category1 =="Return Request"){

            require_once(APP . 'webroot' . DS . 'PHPservice' . DS . 'CallAwbService.php');

            $CreditReferenceNo  =  "C".$data['SrNo'];
            //$CreditReferenceNo  =   "A3";
            $PickupDate        =   date('Y-m-d');
            $PickupTime        =   date('Hi',strtotime(date('Y-m-d H:i:s')));

            $ItemName           =   $data['Field9'];

            $ProDetArr=$this->CallMaster->query("SELECT * FROM `arb_product_master` WHERE ProductName='$ItemName' limit 1 ");
            $Breadth=$ProDetArr[0]['arb_product_master']['Breadth'];
            $Height=$ProDetArr[0]['arb_product_master']['Height'];
            $Length=$ProDetArr[0]['arb_product_master']['Length'];
            $Weight=$ProDetArr[0]['arb_product_master']['Weight'];
            $Count=$ProDetArr[0]['arb_product_master']['Count'];
            $Price=$ProDetArr[0]['arb_product_master']['Price'];
            $Taxiable=$Price/18;


            $str                =   $data['Field22'];

            $str1=substr($str, 0, 30);
            $str2=substr($str, 30, 60);
            $str3=substr($str, 60, 90);

            $str1 !=""?$CustomerAddress1=$str1:"";
            $str2 !=""?$CustomerAddress2=$str2:"";
            $str3 !=""?$CustomerAddress3=$str3:"";

            $CustomerEmailID    =   $data['Field8'];
            $CustomerMobile     =   $data['Field2'];
            $CustomerName       =   substr($data['Field1'], 0, 30);
            $CustomerPincode    =   $data['AreaPincode'];
            $CustomerTelephone  =   $data['Field4'];
            $OriginArea         =   $data['AreaCode'];

            $soap = new DebugSoapClient('http://netconnect.bluedart.com/Ver1.8/ShippingAPI/WayBill/WayBillGeneration.svc?wsdl',
            array(
            'trace' 							=> 1,  
            'style'								=> SOAP_DOCUMENT,
            'use'									=> SOAP_LITERAL,
            'soap_version' 				=> SOAP_1_2
            ));

            $soap->__setLocation("http://netconnect.bluedart.com/Ver1.8/ShippingAPI/WayBill/WayBillGeneration.svc");

            $soap->sendRequest = true;
            $soap->printRequest = false;
            $soap->formatXML = true; 

            $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IWayBillGeneration/GenerateWayBill',true);
            $soap->__setSoapHeaders($actionHeader);	
            //"OM COMPUTRONIX D 4/1 1ST FLOOR OKHLA INDUSTRIAL AREA OKHLA PHASE II NEW DELHI 110020 CONTACT 7065503311";

            $params = array(
            'Request' => 
                    array (
                            'Consignee' =>
                                    array (

                                            'ConsigneeAddress1' => $CustomerAddress1,
                                            'ConsigneeAddress2' => $CustomerAddress2,
                                            'ConsigneeAddress3'=> $CustomerAddress3,
                                            'ConsigneeAttention'=> $CustomerName,
                                            'ConsigneeMobile'=> $CustomerMobile,
                                            'ConsigneeName'=> $CustomerName,
                                            'ConsigneePincode'=> $CustomerPincode,
                                            'ConsigneeTelephone'=> $CustomerTelephone
                                    )	,
                            'Services' => 
                                    array (
                                            'ActualWeight' => $Weight,
                                            'CollectableAmount' => '',
                                            'Commodity' =>
                                                    array (
                                                            'CommodityDetail1'  => $ItemName,
                                                            'CommodityDetail2' => '',
                                                            'CommodityDetail3' => ''
                                            ),
                                            'CreditReferenceNo' => $CreditReferenceNo,
                                            'DeclaredValue' => $Price,
                                            'Dimensions' =>
                                                    array (
                                                            'Dimension' =>
                                                                    array (
                                                                            'Breadth' => $Breadth,
                                                                            'Count' => $Count,
                                                                            'Height' => $Height,
                                                                            'Length' => $Length)),
                                                    'InvoiceNo' => $CreditReferenceNo,
                                                    'IsForcePickup' => true,
                                                    'IsPartialPickup' => false,
                                                    'IsReversePickup' => false,
                                                    'ItemCount' => $Count, 
                                                    'PackType' => 'L',
                                                    'PickupDate' => $PickupDate,
                                                    'PickupTime' => $PickupTime,
                                                    'PieceCount' => $Count,
                                                    'ProductCode' => 'A',
                                                    'RegisterPickup' => true,
                                                    //'ProductType' => '',
                                                    //'SpecialInstruction' => '1',
                                                    'SubProductCode' => 'P',					
                                    'itemdtl' =>
                                            array (
                                                    'ItemDetails' =>
                                                            array (
                                                     'CGSTAmount'=>'',
                                                     'HSCode'=>'',
                                 'IGSTAmount'=>'',
                                 'Instruction'=>'Nothing' ,
                                 'InvoiceDate'=>$PickupDate ,
                                 'InvoiceNumber'=>$CreditReferenceNo, 
                                 'ItemID'=>$CreditReferenceNo,
                                 'ItemName'=>$ItemName,
                                 'ItemValue'=>$Price,
                                 'Itemquantity'=>$Count,
                                 'PlaceofSupply'=>'',
                                 'ProductDesc1'=>'',
                                 'SGSTAmount'=>'',
                                 'SKUNumber'=>'',
                                 'SellerGSTNNumber'=>'',
                                 'SellerName'=>'OM COMPUTRONIX',
                                 'TaxableAmount'=>$Taxiable,
                                 'TotalValue'=>$Price
                                                     ),
                                                     ),
                                     ),
                             'Shipper' =>
                                            array(


                                                    'CustomerAddress1' => 'B122, B Block',
                                                    'CustomerAddress2' => 'Sector 63, Noida',
                                                    'CustomerAddress3' => 'Uttar Pradesh',
                                                    'CustomerCode' => '676071',
                                                    'CustomerEmailID' => '',
                                                    'CustomerMobile' => '7065503300',
                                                    'CustomerName' => 'Lapguard Service Center',
                                                    'CustomerPincode' => '201301',
                                                    'CustomerTelephone' => '7065503300',
                                                    'IsToPayCustomer' => false,
                                                    'OriginArea' => 'NDA',
                                                    'Sender' => $CustomerName,
                                                    'VendorCode' => ''
                                            ),
                    ),
                    'Profile' => 
                              array(
                                    'Api_type' => 'S',
                                    'LicenceKey'=>'j35ustq6qpjrhlqtunpftfuiqssklfl3',
                                    'LoginID'=>'NDA61542',
                                    'Version'=>'1.3')
                                    );

            //echo "<pre>";
           // print_r($params);die;
            $result = $soap->__soapCall('GenerateWayBill',array($params));

            //echo "<pre>";
            //print_r($result);die;

            if($result->GenerateWayBillResult->AWBNo !=""){
                $updArr=array(
                    'Ret_AWBNo'=>"'".$result->GenerateWayBillResult->AWBNo."'",
                    'Ret_CCRCRDREF'=>"'".$result->GenerateWayBillResult->CCRCRDREF."'",
                    'Ret_DestinationArea'=>"'".$result->GenerateWayBillResult->DestinationArea."'",
                    'Ret_DestinationLocation'=>"'".$result->GenerateWayBillResult->DestinationLocation."'",
                    'Ret_TokenNumber'=>"'".$result->GenerateWayBillResult->TokenNumber."'",
                    'Ret_PikupDate'=>"'".date("Y-m-d H:i:s")."'",
                );

                if($this->CallMaster->updateAll($updArr,array('Id'=>$lastid))){
                     $msg= "Waybill Generation Sucessful";
                }
            }
            else {
                $msg= $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;
            }

        }
        $this->Session->setFlash("$msg");
        $this->redirect(array('controller'=>'CloseDetails','action' => 'view_close_fields','?'=>array('id'=>$lastid)));
        //return $msg;
    }*/
    
    
    public function bluedartapi(){

        $lastid     =   $_REQUEST['lastid'];
        $msg        =   "";
        $dataArr    =   $this->CallMaster->query("SELECT * FROM call_master WHERE Id='$lastid' limit 1");
        $data       =   $dataArr[0]['call_master'];
        
        $ClientId   =   $data['ClientId'];
        $Category1  =   $data['Category1'];

        if($ClientId =="277" && $Category1 =="Return Request"){

            $CreditReferenceNo  =  "PPD".$data['SrNo'];
            $PickupDate         =   date('Y-m-d');
            $PickupTime         =   date('Hi',strtotime(date('Y-m-d H:i:s')));
            $ItemName           =   $data['Field9'];

            $ProDetArr=$this->CallMaster->query("SELECT * FROM `arb_product_master` WHERE ProductName='$ItemName' limit 1 ");
            $Breadth=$ProDetArr[0]['arb_product_master']['Breadth'];
            $Height=$ProDetArr[0]['arb_product_master']['Height'];
            $Length=$ProDetArr[0]['arb_product_master']['Length'];
            $Weight=$ProDetArr[0]['arb_product_master']['Weight'];
            $Count=$ProDetArr[0]['arb_product_master']['Count'];
            $Price=$ProDetArr[0]['arb_product_master']['Price'];
            $Taxiable=$Price/18;

            $customerAddress    =   $data['Field22'];
            $CustomerEmailID    =   $data['Field8'];
            $CustomerMobile     =   $data['Field2'];
            $CustomerName       =   $data['Field1'];
            $CustomerPincode    =   $data['AreaPincode'];
            $CustomerTelephone  =   $data['Field4'];
            $customerCity       =   $data['Field5'];
            $OriginArea         =   $data['AreaCode'];

                $data = array( 'orders'=> [
                    array(
                        "orderId"=> $CreditReferenceNo,
                        "customerName"=> $CustomerName,
                        "customerAddress"=> $customerAddress,
                        "customerCity"=> $customerCity,
                        "customerPinCode"=> $CustomerPincode,
                        "customerContact"=> $CustomerMobile,
                        "orderDate"=> $PickupDate,
                        "modeType"=> "Lite-0.5kg",
                        "orderType"=> "prepaid",
                        "totalValue"=> $Price,
                        "categoryName"=> "Computers and Accessories",
                        "packageName"=> $ItemName,
                        "quantity"=> $Count,
                        "packageLength"=> $Length,
                        "packageWidth"=> $Breadth,
                        "packageHeight"=> $Height,
                        "packageWeight"=> $Weight,
                        "sellerAddressId"=> "11294" 
                    )
                ]);
                
                $Order_Data     =   $this->set_order($data);
                $obj            =   json_decode($Order_Data);
                $order_res      =   $obj[0]->success;

                $Shipment_Data  =   $this->getShipmentSlip($CreditReferenceNo);
                $Ship_Res       =   json_decode($Shipment_Data);
                
                $AWBNo          =   $Ship_Res->awbNo;
                $carrierName    =   $Ship_Res->carrierName;
                $fileName       =   $Ship_Res->fileName;
                $s3Path         =   $Ship_Res->s3Path[0];
                $manifestID     =   $Ship_Res->manifestID;
                $status         =   $Ship_Res->status;
                
                if($status =="success"){
                    $updArr=array(
                        'Ret_AWBNo'=>"'".$AWBNo."'",
                        'Ret_CCRCRDREF'=>"'".$carrierName."'",
                        'Ret_DestinationArea'=>"'".$fileName."'",
                        'Ret_DestinationLocation'=>"'".$s3Path."'",
                        'Ret_TokenNumber'=>"'".$AWBNo."'",
                        'Ret_PikupDate'=>"'".date("Y-m-d H:i:s")."'"
                    );
                    
                    $this->CallMaster->updateAll($updArr,array('Id'=>$lastid));
                    $msg= "Shipment generate sucessful.Please download label and check shipment.";
                }
                else{
                    $msg= "Order generate sucessful. so pleae create shipment";
                }
        }
        $this->Session->setFlash("$msg");
        $this->redirect(array('controller'=>'CloseDetails','action' => 'view_close_fields','?'=>array('id'=>$lastid)));
    }
    
    function authenticatShyplite() {
        $email      =   "arpit@arbaccessories.in";
        $password   =   "in4mation";

        $timestamp  =   time();
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $secret     =   'fxkCl2FPvVIcE/t21fpk0KjZn3iNpYQTPHAEQRBq9dC+SAa9Gd/8MgKbEFaPcHZAFqGBQpl4QKjSsmZpL+Ojug==';

        $sign = "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken = rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch = curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "emailID=$email&password=$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
        exit;
        curl_close($ch);
    }
        
    public function set_order($data){
        $Login      =   $this->authenticatShyplite();
        $obj        =   json_decode($Login);
        $secret     =   $obj->userToken;
        $timestamp  =   time();
        $sellerid   =   15196;
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $sign       =   "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken  =   rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch         =   curl_init();

        $data_json = json_encode($data);
      
        $header = array(
            "x-appid: $appID",
            "x-sellerid: $sellerid",
            "x-timestamp: $timestamp",
            "Authorization: $authtoken",
            "Content-Type: application/json",
            "Content-Length: ".strlen($data_json)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/order');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        return $response;
        exit;
        curl_close($ch);
    }
    
    function getShipmentSlip($OrderID) {
        $Login      =   $this->authenticatShyplite();
        $obj        =   json_decode($Login);
        $secret     =   $obj->userToken;
        
        $timestamp  =   time();
        $sellerid   =   15196;
        $appID      =   2412;
        $key        =   'dRrzIbKTEtY=';
        $sign       =   "key:". $key ."id:". $appID. ":timestamp:". $timestamp;
        $authtoken  =   rawurlencode(base64_encode(hash_hmac('sha256', $sign, $secret, true)));
        $ch         =   curl_init();

        $header = array(
            "x-appid: $appID",
            "x-timestamp: $timestamp",
            "x-sellerid: $sellerid",
            "Authorization: $authtoken"
        );

        curl_setopt($ch, CURLOPT_URL, 'https://api.shyplite.com/getSlip?orderID='.urlencode($OrderID));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        return $server_output;
        exit;
        curl_close($ch);
    }
    
	
	public function update_closeloop_image(){
		
		$role = $this->Session->read('role');
		if($role=="agent"){
			$userby	=	$role."-".$this->Session->read('agent_username');
		}
		else if($role=="client"){
			$userby	=	$role."-".$this->Session->read('email');
		}
		else if($role=="admin"){
			$userby	=	$role."-".$this->Session->read('admin_email');
		}
			
			
		$call_master_id	=	$_REQUEST['call_master_id'];
		$banner			=	$_FILES['banner']['name'];
		$date 			= 	$call_master_id.date('dmYHis');
		$bannername		=	$date.$banner;
		$bannerpath		=	"/var/www/html/dialdesk/app/webroot/closeloopimage/".$bannername;

		if(move_uploaded_file($_FILES["banner"]["tmp_name"],$bannerpath)){
			$this->CloseLoopMaster->query("INSERT INTO `close_loop_image` SET Call_Master_Id='$call_master_id',Image='$bannername',Path='$bannerpath',Create_By='$userby'");
		}
		
		$this->redirect(array('action' => 'view_close_fields','?'=>array('id'=>$call_master_id)));
    }
	
	public function delete_closeloop_image(){
			
		$Id					=	$_REQUEST['Id'];
		$close_loop_image	=	$this->CloseLoopMaster->query("SELECT * FROM close_loop_image WHERE Id='$Id'");
		
		$call_master_id		=	$close_loop_image[0]['close_loop_image']['Call_Master_Id'];
		$bannername			=	$close_loop_image[0]['close_loop_image']['Image'];
		$bannerpath		=	"/var/www/html/dialdesk/app/webroot/closeloopimage/".$bannername;
		
		if(unlink($bannerpath)){
			$this->CloseLoopMaster->query("DELETE FROM close_loop_image WHERE Id='$Id';");
		}
		
		$this->redirect(array('action' => 'view_close_fields','?'=>array('id'=>$call_master_id)));
    }
		
}
?>