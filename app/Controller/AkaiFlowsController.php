<?php
class AkaiFlowsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','ClientCategory','FieldCreation','CampaignName','ClientCategory','ObclientCategory','FieldValue','EcrMaster','ObCallFlow','CloseFieldData','CloseFieldDataValue','LogincreationMaster','IncallScenarios','InCallActionCroneJob','InCallActionMatrix','InCallActionSms','CloseStatusHistory','PlanMaster','BalanceMaster','PaymentOrderNo','RegistrationMaster','CloseFieldData','CloseFieldDataValue','CloseUpdate','FieldMaster','FieldValue','EcrMaster','CallMaster','CloseLoopMaster','CloseStatusHistory','AkaiCallFlow','CallFlow');
	
    public function beforeFilter(){
        parent::beforeFilter();
        
        if($this->Session->check("companyid")){
                // return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
                $this->Auth->allow('index','obresolution','call_flow_report','delete_resolution','getobEcr','delete_ob_resolution');
        }else{
            $this->Auth->allow('call_flow_report');
            $this->Auth->deny('index','obresolution');
        }
        
    }

    
    public function index() {
        $this->layout='user';

	    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);

        $ClientId = $this->Session->read('companyid');
        $category = $this->ClientCategory->find('list',array('fields'=>array("id","EcrName"),'conditions'=>array('label'=>'1','Client'=>$ClientId)));
        $cat = array();
        foreach($category as $k=>$v){
            $cat[$k.'@@'.$v] = $v;
        }
        //$category = $cat;
        $category = array_merge(array('All@@All'=>'All'), $cat);
        unset($cat);
        $this->set('category',$category);

        $this->set('data',$this->CallFlow->find('all',array('conditions'=>array('client_id'=>$ClientId))));


        if($this->request->is('POST')){

            // 
            //print_r($this->request->data);die;
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['AkaiFlows'];
            $dataesc = $this->request->data['Escalations'];
            $resolution = $this->request->data['body'];

            $data['client_id']=$ClientId;
 
            foreach($data as $k=>$v){
                if($v != 'All@@All')
                {
                    $data1[$k] = addslashes($v);
                    $keys = array('category','type','subtype','subtype1','subtype2');
                    if(in_array($k,$keys))
                    {
                        $Arr = explode('@@',$v);
                        $data1[$k] = $Arr[1];
                        // $data1[$k.'Name'] = $Arr[1];
                    }
                }
                
            }
            foreach($dataesc as $k=>$v)
            {
                if($v != 'All@@All')
                {

                
                    $data1[$k] = addslashes($v);
                    $keys = array('category','type','subtype','subtype1','subtype2');
                    if(in_array($k,$keys))
                    {
                        $Arr = explode('@@',$v);
                        $data1[$k] = $Arr[1];
                    }
                }
                
            }
            #$resolution = substr($resolution, 0, -229);
            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
            $data1['resolution'] = $resolution;
            //$this->CallFlow->query("CHARACTER SET utf8");
            $this->CallFlow->save($data1);
            $this->Session->setFlash("Resolution Add SuccessFully.");
            $this->redirect(array('controller'=>'AkaiFlows','action'=>'index'));

        }  
    
       	
    }

    public function obresolution() {
        $this->layout='user';

	    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);

        $ClientId = $this->Session->read('companyid');
        $Campaign=$this->CampaignName->find('list',array('fields'=>array('id','CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A')));

        $cat = array();
        foreach($Campaign as $k=>$v){
            $cat[$k.'@@'.$v] = $v;
        }

		$Campaign = $cat;
        unset($cat);

        $this->set('Campaign',$Campaign);
        // echo $ClientId;die;
        $this->set('data',$this->ObCallFlow->find('all',array('conditions'=>array('clientId'=>$ClientId))));

        if($this->request->is('POST')){
            
            //print_r($this->request->data);die;
            $ClientId = $this->Session->read('companyid');
            $data = $this->request->data['AkaiFlows'];
            $dataesc = $this->request->data['ObEscalations'];
            $resolution = $this->request->data['body'];

            $data['clientId']=$ClientId;
            
            foreach($data as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('campaignid');
                if(in_array($k,$keys))
                {
                    $Arr = explode('@@',$v);
                    $data1[$k] = $Arr[1];
                    $data1[$k.'Name'] = $Arr[1];
                    $data1[$k] = $Arr[0];
                }
            }
            
            foreach($dataesc as $k=>$v){
                $data1[$k] = addslashes($v);
                $keys = array('category','type','subtype','subtype1','subtype2');
                if(in_array($k,$keys))
                {
                    $Arr = explode('@@',$v);
                    if($Arr[1] != 'All')
                    {
                        $data1[$k] = $Arr[1];
                    }
                    
                }
            }
            #$resolution = substr($resolution, 0, -229);
            $data1['createdate'] = date('Y-m-d H:i:s');
            $data1['createby'] = $ClientId;
            $data1['resolution'] = $resolution;
        
            $this->ObCallFlow->save($data1);
            $this->Session->setFlash("Resolution Add SuccessFully.");
            $this->redirect(array('controller'=>'AkaiFlows','action'=>'obresolution'));
            // return $this->redirect(array('controller'=>'Escalations','action'=>'view_fields','?'=>array('tab'=>$data['tabType'])));
        }  


    
       	
    }
    public function view()
    {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');

        $calltype = $this->AkaiCallFlow->find('list',array('fields'=>array('field1','field1'),'conditions'=>array('client_id'=>$ClientId)));

        $this->set('calltype',$calltype);
    }

    public function getobEcr()
    {
        $this->layout="ajax";
        if($this->request->is('POST'))
        {
            if(!empty($this->request->data))
            {
                if($this->request->data['Label']=='1')
                {
                    
                    $conditions['campaignid'] = $this->request->data['parent'];
                    $conditions['Label'] = $this->request->data['Label'];
                }
                
                else
                {
                    $conditions['parent_id'] = $this->request->data['parent'];
                }
                //$conditions['Label'] = $this->request->data['Label'];
                $type = $this->request->data['type'];
                
                $category = $this->ObclientCategory->find('all',array('fields'=>array('id','ecrName','Label'),'conditions'=>$conditions));
                
                $cat_master = array();
                $Label = "";
                foreach($category as $cat)
                {
                    $cat_master[$cat['ObclientCategory']['id'].'@@'.$cat['ObclientCategory']['ecrName']] = $cat['ObclientCategory']['ecrName'];
                    $Label = $cat['ObclientCategory']['Label'];
                }
                $Label = $Label+1;
                $category = $cat_master;
                
                unset($cat);
        
            
                
                $this->set('data',$category); 
                $this->set('type',$type); 
                $this->set('Label',$Label); 
                $this->set('function',$this->request->data['function']);
                $this->set('divtype',$this->request->data['divtype']);	
        }
        }
    }

    function delete_resolution()
    {
        $id=$this->request->query['id'];
        $this->CallFlow->deleteAll(array('id'=>$id,'client_id' => $this->Session->read('companyid')));
        $this->redirect(array('controller'=>'AkaiFlows','action'=>'index'));
    }

    function delete_ob_resolution()
    {
        $id=$this->request->query['id'];
        $this->ObCallFlow->deleteAll(array('id'=>$id,'clientId' => $this->Session->read('companyid')));
        $this->redirect(array('controller'=>'AkaiFlows','action'=>'obresolution'));
    }

    public function call_flow_report()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $this->set('client',$client); 
        }
        if($this->request->is("POST"))
        {
            $client_id = $this->request->data['AkaiFlows']['clientID'];

            $in_qry = "Select getClientName(client_id) client,language,category,type,subtype,subtype1,subtype2,resolution,createdate from call_flow where client_id='$client_id'";
            $in_data = $this->CallFlow->query($in_qry);

            $out_qry = "Select getClientName(clientId) client,campaignidName,language,category,type,subtype,subtype1,subtype2,resolution from obcall_flow where clientId='$client_id'";
            $out_data = $this->CallFlow->query($out_qry);

            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=knowledge_master.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            $is_inbound =false;
            $is_outbound =false;
            if(!empty($in_data))
            {
                $is_inbound =true;
            }
            if(!empty($out_data))
            {
                $is_outbound =true;
            }
            ?>
            <?php if($is_inbound){?>
                    <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered">
                            <tr>
                            <th colspan="9" rowspan="2" style='font-size:11pt;background-color:#ff3300'>Inbound</th>
                            </tr>
                            <tr></tr>
                            <tr>
                                <th>Client Name</th>
                                <th>Language</th>
                                <th>Scenario</th>
                                <th>Scenario1</th>
                                <th>Scenario2</th>
                                <th>Scenario3</th>
                                <th>Scenario4</th>
                                <th>Resolution</th>
                                <th>Create Date</th>
                            </tr>

                            <?php 
                            foreach($in_data as $d)
                            {
                                echo "<tr>";
                                echo "<td>".$d[0]['client']."</td>";
                                if($d['call_flow']['language'] == "En"){ echo "<td>English</td>";}else{echo "<td>Hindi</td>";}
                                echo "<td>".$d['call_flow']['category']."</td>";
                                echo "<td>".$d['call_flow']['type']."</td>";
                                echo "<td>".$d['call_flow']['subtype']."</td>";
                                echo "<td>".$d['call_flow']['subtype1']."</td>";
                                echo "<td>".$d['call_flow']['subtype2']."</td>";
                                echo "<td>"."{$d['call_flow']['resolution']}"."</td>";
                                echo "<td>".date_format(date_create($d['call_flow']['createdate']),'d M Y H:i:s')."</td>";
                                echo "</tr>";

                            }
                            ?>    
                    </table>
                <?php }?>
                    <br><br>
                    <?php if($is_outbound){?>
                    <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered">
                    <tr>
                      <th colspan="10" rowspan="2" style='font-size:11pt;background-color:#ff3300'>Outbound</th>
                    </tr>
                    <tr></tr>
                    <tr>
                        <th>Client Name</th>
                        <th>Campaign Name</th>
                        <th>Language</th>
                        <th>Scenario</th>
                        <th>Scenario1</th>
                        <th>Scenario2</th>
                        <th>Scenario3</th>
                        <th>Scenario4</th>
                        <th>Resolution</th>
                        <th>Create Date</th>
                    </tr>

                    <?php 
                    foreach($out_data as $o)
                    {
                        echo "<tr>";
                        echo "<td>".$o[0]['client']."</td>";
                        echo "<td>".$o['obcall_flow']['campaignidName']."</td>";
                        if($o['obcall_flow']['language'] == "En"){ echo "<td>English</td>";}else{echo "<td>Hindi</td>";}
                        echo "<td>".$o['obcall_flow']['category']."</td>";
                        echo "<td>".$o['obcall_flow']['type']."</td>";
                        echo "<td>".$o['obcall_flow']['subtype']."</td>";
                        echo "<td>".$o['obcall_flow']['subtype1']."</td>";
                        echo "<td>".$o['obcall_flow']['subtype2']."</td>";
                        echo "<td>".$o['obcall_flow']['resolution']."</td>";
                        echo "<td>".date_format(date_create($o['obcall_flow']['createdate']),'d M Y H:i:s')."</td>";
                        echo "</tr>";
                    }
                    ?>    
                </table>
                    <?php }?>
            <?php
           die; 
        }
    }

    
	
}
?>