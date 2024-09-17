<?php
class ObcloseLoopingsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ClientCategory','OutboundClientCategory','CloseLoopMaster','OutboundCloseLoopMaster','CampaignName');
	
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
                'update_close_loop',
                'index2');
        
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
  
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A'))));
        
        
        if($this->request->is('Post')){
            $campaignid=$this->request->data['ObcloseLoopings']['campaign'];
            $this->set('cmid',$campaignid);
        
            $this->set('category',$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId,'CampaignId'=>$campaignid))));
            $result = $this->OutboundCloseLoopMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'),'CampaignId'=>$campaignid)));

            $arrData=array();
            foreach($result as $row){
                 $parent_id=$this->get_parent($row['OutboundCloseLoopMaster']['parent_id'],$ClientId,$campaignid);
                $arrData[]=array(
                    'id'=>$row['OutboundCloseLoopMaster']['id'],
                    'CategoryName1'=>$row['OutboundCloseLoopMaster']['CategoryName1'],
                    'CategoryName2'=>$row['OutboundCloseLoopMaster']['CategoryName2'],
                    'CategoryName3'=>$row['OutboundCloseLoopMaster']['CategoryName3'],
                    'CategoryName4'=>$row['OutboundCloseLoopMaster']['CategoryName4'],
                    'CategoryName5'=>$row['OutboundCloseLoopMaster']['CategoryName5'],
                    'close_loop'=>$row['OutboundCloseLoopMaster']['close_loop'],
                    'close_loop_category'=>$parent_id !=""?$parent_id:$row['OutboundCloseLoopMaster']['close_loop_category'],
                    'close_loop_sub_category'=>$parent_id !=""?$row['OutboundCloseLoopMaster']['close_loop_category']:"",
                    'close_looping_date'=>$row['OutboundCloseLoopMaster']['close_looping_date']
                );
            }
            $this->set('data',$arrData);  
        }
        else if(isset($_REQUEST['CampaignId']) && $_REQUEST['CampaignId'] !=""){
            $campaignid=$_REQUEST['CampaignId'];
            $this->set('cmid',$_REQUEST['CampaignId']);
        
            $this->set('category',$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId,'CampaignId'=>$campaignid))));
            $result = $this->OutboundCloseLoopMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'),'CampaignId'=>$campaignid)));

            $arrData=array();
            foreach($result as $row){
                 $parent_id=$this->get_parent($row['OutboundCloseLoopMaster']['parent_id'],$ClientId,$campaignid);
                $arrData[]=array(
                    'id'=>$row['OutboundCloseLoopMaster']['id'],
                    'CategoryName1'=>$row['OutboundCloseLoopMaster']['CategoryName1'],
                    'CategoryName2'=>$row['OutboundCloseLoopMaster']['CategoryName2'],
                    'CategoryName3'=>$row['OutboundCloseLoopMaster']['CategoryName3'],
                    'CategoryName4'=>$row['OutboundCloseLoopMaster']['CategoryName4'],
                    'CategoryName5'=>$row['OutboundCloseLoopMaster']['CategoryName5'],
                    'close_loop'=>$row['OutboundCloseLoopMaster']['close_loop'],
                    'close_loop_category'=>$parent_id !=""?$parent_id:$row['OutboundCloseLoopMaster']['close_loop_category'],
                    'close_loop_sub_category'=>$parent_id !=""?$row['OutboundCloseLoopMaster']['close_loop_category']:"",
                    'close_looping_date'=>$row['OutboundCloseLoopMaster']['close_looping_date']
                );
            }
            $this->set('data',$arrData);  
        }
    }

    public function index2() {
        $this->layout='popuser';
        $ClientId = $this->Session->read('companyid');
        $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A'))));
        
        
        if($this->request->is('Post')){
            $campaignid=$this->request->data['ObcloseLoopings']['campaign'];
            $this->set('cmid',$campaignid);
        
            $this->set('category',$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId,'CampaignId'=>$campaignid))));
            $result = $this->OutboundCloseLoopMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'),'CampaignId'=>$campaignid)));

            $arrData=array();
            foreach($result as $row){
                 $parent_id=$this->get_parent($row['OutboundCloseLoopMaster']['parent_id'],$ClientId,$campaignid);
                $arrData[]=array(
                    'id'=>$row['OutboundCloseLoopMaster']['id'],
                    'CategoryName1'=>$row['OutboundCloseLoopMaster']['CategoryName1'],
                    'CategoryName2'=>$row['OutboundCloseLoopMaster']['CategoryName2'],
                    'CategoryName3'=>$row['OutboundCloseLoopMaster']['CategoryName3'],
                    'CategoryName4'=>$row['OutboundCloseLoopMaster']['CategoryName4'],
                    'CategoryName5'=>$row['OutboundCloseLoopMaster']['CategoryName5'],
                    'close_loop'=>$row['OutboundCloseLoopMaster']['close_loop'],
                    'close_loop_category'=>$parent_id !=""?$parent_id:$row['OutboundCloseLoopMaster']['close_loop_category'],
                    'close_loop_sub_category'=>$parent_id !=""?$row['OutboundCloseLoopMaster']['close_loop_category']:"",
                    'close_looping_date'=>$row['OutboundCloseLoopMaster']['close_looping_date']
                );
            }
            $this->set('data',$arrData);  
        }
        else if(isset($_REQUEST['CampaignId']) && $_REQUEST['CampaignId'] !=""){
            $campaignid=$_REQUEST['CampaignId'];
            $this->set('cmid',$_REQUEST['CampaignId']);
        
            $this->set('category',$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId,'CampaignId'=>$campaignid))));
            $result = $this->OutboundCloseLoopMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'),'CampaignId'=>$campaignid)));

            $arrData=array();
            foreach($result as $row){
                 $parent_id=$this->get_parent($row['OutboundCloseLoopMaster']['parent_id'],$ClientId,$campaignid);
                $arrData[]=array(
                    'id'=>$row['OutboundCloseLoopMaster']['id'],
                    'CategoryName1'=>$row['OutboundCloseLoopMaster']['CategoryName1'],
                    'CategoryName2'=>$row['OutboundCloseLoopMaster']['CategoryName2'],
                    'CategoryName3'=>$row['OutboundCloseLoopMaster']['CategoryName3'],
                    'CategoryName4'=>$row['OutboundCloseLoopMaster']['CategoryName4'],
                    'CategoryName5'=>$row['OutboundCloseLoopMaster']['CategoryName5'],
                    'close_loop'=>$row['OutboundCloseLoopMaster']['close_loop'],
                    'close_loop_category'=>$parent_id !=""?$parent_id:$row['OutboundCloseLoopMaster']['close_loop_category'],
                    'close_loop_sub_category'=>$parent_id !=""?$row['OutboundCloseLoopMaster']['close_loop_category']:"",
                    'close_looping_date'=>$row['OutboundCloseLoopMaster']['close_looping_date']
                );
            }
            $this->set('data',$arrData);  
        }
    }
    
    
    
    public function get_parent($parent_id,$ClientId,$campaignid){
        $data=$this->OutboundCloseLoopMaster->find('first',array('fields'=>array("close_loop_category"),'conditions'=>array('label'=>1,'id'=>$parent_id,'client_id'=>$ClientId,'CampaignId'=>$campaignid)));
        return $data['OutboundCloseLoopMaster']['close_loop_category'];
    }
    
    public function get_parent_action(){
        if(isset($_REQUEST['close_loop'])){
            $ClientId = $this->Session->read('companyid');
            $parent_category = $this->OutboundCloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('close_loop'=>$_REQUEST['close_loop'],'label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId,'CampaignId'=>$_REQUEST['CampaignId'])));
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
            $parent_category = $this->OutboundCloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('close_loop'=>$_REQUEST['close_loop'],'label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId,'CampaignId'=>$_REQUEST['CampaignId'])));
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
            
            $CampaignId=$data['CampaignId'];
            
            //print_r($data);die;
            
            if($data['parent_id'] ==""){
               unset($data['parent_id']); 
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
            
            if($data['Category1'] !=""){$data['CategoryName1']=$this->scenarios_name($data['Category1'],$clientid,$CampaignId);}
            if($data['Category2'] !=""){$data['CategoryName2']=$this->scenarios_name($data['Category2'],$clientid,$CampaignId);}
            if($data['Category3'] !=""){$data['CategoryName3']=$this->scenarios_name($data['Category3'],$clientid,$CampaignId);}
            if($data['Category4'] !=""){$data['CategoryName4']=$this->scenarios_name($data['Category4'],$clientid,$CampaignId);}
            if($data['Category5'] !=""){$data['CategoryName5']=$this->scenarios_name($data['Category5'],$clientid,$CampaignId);}
           
            
            if($this->OutboundCloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exist))){
                $this->Session->setFlash("Already exist in database.");
                $this->redirect(array('action' => 'index','?'=>array('CampaignId'=>$CampaignId)));
            }
            else if($data['close_loop']=="system" && $this->OutboundCloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exsystem))){
                    $this->Session->setFlash("Already created for manual Out Call Action.");
                    $this->redirect(array('action' => 'index','?'=>array('CampaignId'=>$CampaignId)));
            }
            else if($data['close_loop']=="manual" && $this->OutboundCloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exmanual))){
                    $this->Session->setFlash("Already created for system Out Call Action.");
                    $this->redirect(array('action' => 'index','?'=>array('CampaignId'=>$CampaignId)));
            }
            else{
                $this->OutboundCloseLoopMaster->save($data);
                $this->Session->setFlash("<span class='green'>Out Call Action created successfully.</span>");
                $this->redirect(array('action' => 'index','?'=>array('CampaignId'=>$CampaignId)));
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
        $CampaignId=$this->request->query['CampaignId'];
        $this->OutboundCloseLoopMaster->deleteAll(array('id'=>$id,'client_id' => $this->Session->read('companyid'),'CampaignId'=>$CampaignId));
         $this->redirect(array('action' => 'index','?'=>array('CampaignId'=>$CampaignId)));
    }
    
    
    public function selectCategory(){  
        $ClientId = $this->Session->read('companyid');
        if($_REQUEST['parent_id'] !="0"){
            $subcategory =$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$_REQUEST['parent_id'],'Client'=>$ClientId,'CampaignId'=>$_REQUEST['CampaignId'])));            
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
            $subcategory =$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$_REQUEST['parent_id'],'Client'=>$ClientId,'CampaignId'=>$_REQUEST['CampaignId'])));           
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
            $this->set('result',$this->OutboundCloseLoopMaster->find('first',array('conditions' =>array('id' =>$_REQUEST['id'],'client_id' =>$ClientId,'CampaignId'=>$_REQUEST['CampaignId']))));
            $this->set('category',$this->OutboundClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId,'CampaignId'=>$_REQUEST['CampaignId']))));
            $this->set('parent_category',$this->OutboundCloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('label'=>1,'parent_id'=>NULL,'client_id'=>$ClientId,'CampaignId'=>$_REQUEST['CampaignId']))));
            $this->set('CampaignId',$_REQUEST['CampaignId']);   
        }
    }
    
    public function update_close_loop(){
        if($this->request->is('Post')){
            $clientid=$this->Session->read('companyid');
            $data=$this->request->data;
            
             $CampaignId=$data['CampaignId'];
             
             
            
             if($data['parent_id'] ==""){
               unset($data['parent_id']); 
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
           
           $cn1= isset($data['Category1'])?$this->scenarios_name($data['Category1'],$clientid,$CampaignId):"";
           $cn2= isset($data['Category2'])?$this->scenarios_name($data['Category2'],$clientid,$CampaignId):"";
           $cn3= isset($data['Category3'])?$this->scenarios_name($data['Category3'],$clientid,$CampaignId):"";
           $cn4= isset($data['Category4'])?$this->scenarios_name($data['Category4'],$clientid,$CampaignId):"";
           $cn5= isset($data['Category5'])?$this->scenarios_name($data['Category5'],$clientid,$CampaignId):"";
           
           
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

            if($this->OutboundCloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exist))){
                echo "1";die;
            }
            else if($data['close_loop']=="system" && $this->OutboundCloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exsystem))){
                echo "2";die;
            }
            else if($data['close_loop']=="manual" && $this->OutboundCloseLoopMaster->find('first',array('fields'=>array('id'),'conditions'=>$exmanual))){
                echo "3";die;
            }
            else{
                $this->OutboundCloseLoopMaster->updateAll($dataArr,array('id'=>$closeId,'client_id' =>$clientid,'CampaignId'=>$CampaignId));
                echo "4";die;
            }
        }
  
    }
    
    
    
    
    public function scenarios_name($id,$cid,$campaignid){
        if($id ==="All"){
            return "All";
        }
        else{
            $data=$this->OutboundClientCategory->find('first',array('fields'=>array("ecrName"),'conditions'=>array('id'=>$id,'Client'=>$cid,'CampaignId'=>$campaignid))); 
            if(!empty($data)){
                return $data['OutboundClientCategory']['ecrName'];
            }
        }
    }
    
    
    
    
    //============================================================================================================
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
  
    
    
    
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
    

    
    
    

    
}
?>