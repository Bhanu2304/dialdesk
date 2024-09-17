<?php
class EcrsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ClientCategory','ecrNameMaster','EcrMaster','TatMaster','TimePeriod');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid'))
		{
        $this->Auth->allow(
			'index',
			'add',
                        'savetat',
                        'start_time',
                        'end_time',
                        'start_day',
                        'end_day',
			'view',
			'create_category',
                        'create_tat',
                        'update_category',
			'create_type',
                        'update_type',
			'create_sub_type1',
                        'update_sub_type1',
			'create_sub_type2',
                        'update_sub_type2',
			'create_sub_type3',
                        'update_sub_type3',
			'get_label2',
                        'edit_label2',
                        'edit_label2_sub1',
                        'edit_label2_sub2',
			'get_label3',
                        'edit_label3',
                        'edit_label3_sub1',
                        'edit_label3_sub2',
                        'delete_ecr',
			'get_label4',
                        'existScenario',
                        'edit_label4_sub1');
		}
		else
		{$this->Auth->deny('index',
			'add',
			'view');}
    }
	
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
        $this->set('Category',$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1'))));
       
        if(isset($_REQUEST['cms']) && $_REQUEST['cms'] !=""){
            $this->set('cms',$_REQUEST['cms']);
        }
        $ecdata =$this->EcrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId),'group'=>'Label'));
        $this->set('ecrcat1',$ecdata);
        $this->set('ecrcat2',$ecdata);
        $this->set('ecrcat3',$ecdata);
        $this->set('ecrcat4',$ecdata);
        $this->set('ecrcat5',$ecdata);   
    }
    
    public function create_tat(){
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        
        /*
        $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
        $ecdata =$this->EcrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId),'group'=>'Label'));
        $this->set('ecrcat1',$ecdata);
        $this->set('ecrcat2',$ecdata);
        $this->set('ecrcat3',$ecdata);
        $this->set('ecrcat4',$ecdata);
        $this->set('ecrcat5',$ecdata); 
        */
       
       
        $page = $this->TatMaster->find('all',array('conditions'=>array('Client'=>$ClientId)));
                
        $menu = array(
            'menus' => array(),
            'parent_menus' => array(),
        );

        foreach($page as $row){
            $menu['menus'][$row['TatMaster']['id']] = $row['TatMaster'];
            $menu['parent_menus'][$row['TatMaster']['parent_id']][] = $row['TatMaster']['id'];
        }

        $str = "";
        $this->set('UserRight',$this->buildMenu(NULL, $menu,$str));
            
    }
    
    
   function buildMenu($parent, $menu,$str) {
            $html = "";
            $char=" ";
            $hor = "";
            $wst = "";
            $wet = "";
            $clockhours = "";
            $customkhours = "";
            $flag = true;
           
            if (isset($menu['parent_menus'][$parent])) {
                foreach ($menu['parent_menus'][$parent] as $menu_id) {
                    $existid=$this->existChild($menu['menus'][$menu_id]['id']);
                    $eid =$menu['menus'][$menu_id]['id'];
                    
                    $tatData=$this->TimePeriod->find('first',array('conditions'=>array('ecr_id'=>$eid,'clientId'=>$this->Session->read('companyid'))));
                    if(!empty($tatData)){
                        $hor=isset($tatData['TimePeriod']['time_Hours'])? $tatData['TimePeriod']['time_Hours']:"";
                        $wty=isset($tatData['TimePeriod']['working_type'])? $tatData['TimePeriod']['working_type']:"";
                        $wst=isset($tatData['TimePeriod']['start_time'])? $tatData['TimePeriod']['start_time']:"";
                        $wet=isset($tatData['TimePeriod']['end_time'])? $tatData['TimePeriod']['end_time']:"";
                        $wsd=isset($tatData['TimePeriod']['start_day'])? $tatData['TimePeriod']['start_day']:"";
                        $wed=isset($tatData['TimePeriod']['end_day'])? $tatData['TimePeriod']['end_day']:"";
                        
                        $clockhours =  $tatData['TimePeriod']['working_type']=='Clock Hours'?'checked = "checked"':'';
                        $customkhours =  $tatData['TimePeriod']['working_type']=='Custom Hours'?'checked = "checked"':'';
                        if($wty ==="Custom Hours"){
                             $script= $this->webroot."js/assets/js/jquery-1.10.2.min.js";
                            echo "
                                <script src='$script'></script>
                                <script type=\"text/javascript\">                   
                                 $( document ).ready(function() {
                                    editgetwcat('$wty','$eid','$wsd','$wed'); 
                                   
                                 });
                                </script>
                                ";
                      }
      
                    }

                    if (!isset($menu['parent_menus'][$menu_id])) {
                        if(!empty($existid)){
                        $html .= "<li><span class='textlabel'>".$menu['menus'][$menu_id]['ecrName']."<span></li>";
                        }
                        else{
                           $html .= "<li><span class='textlabel'>".$menu['menus'][$menu_id]['ecrName'].
                                   "<span> "
                                   . "<input type='hidden'  name='ecrid[".$eid."]'  value='".$str.$eid."'> "
                                   . "<input type='text' value='$hor' name='hour$eid'  class='textbox' placeholder='Hours' > "
                                   . "Clock Hours <input type='radio' $clockhours  value='Clock Hours' onchange='getwcat(this.value,$eid)' name='wtype$eid' > "
                                   . "Custom Hours <input type='radio' $customkhours  value='Custom Hours' onchange='getwcat(this.value,$eid)' name='wtype$eid' > "
                                   . "<input type='text' value='$wst' name='wstime$eid' id='wstime$eid' style='display:none;width:65px;' class='timepicker' placeholder='Start Time' > "
                                   . "<input type='text' value='$wet' name='wetime$eid' onchange='getwhour(this.value,$eid)' id='wetime$eid' style='display:none;width:65px;' class='timepicker' placeholder='End Time'> "
                                   . "<select name='wsday$eid' id='wsday$eid' style='display:none;'></select> "
                                   . "<select name='weday$eid' id='weday$eid' style='display:none;'></select></li>";
                        
                        }
                    }
                    if (isset($menu['parent_menus'][$menu_id])) {
                        $html .= "<li><span style='color:#616161;'>".$menu['menus'][$menu_id]['ecrName'];
                        $html .= "<ul class='ecrtree'>";
                        
                        $str2 =$menu['menus'][$menu_id]['id']."@@";
                        $str3 =$str.$str2; 
                        
                        $html .= $this->buildMenu($menu_id, $menu,$str3);
                        $html .= "</ul>";
                        $html .= "</span></li>";
                    }
                }
            }
            return $html;
        }
        

        public function start_day(){
            ?> 
             <option value=''>Start Days</option>
             <option <?php if($_REQUEST['sd'] ==="Monday"){echo "selected='selected'";}?> value='Monday'>Monday</option>
             <option <?php if($_REQUEST['sd'] ==="Tuesday"){echo "selected='selected'";}?> value='Tuesday'>Tuesday</option>
             <option <?php if($_REQUEST['sd'] ==="Wednesday"){echo "selected='selected'";}?> value='Wednesday'>Wednesday</option>
             <option <?php if($_REQUEST['sd'] ==="Thirsday"){echo "selected='selected'";}?> value='Thirsday'>Thirsday</option>
             <option <?php if($_REQUEST['sd'] ==="Friday"){echo "selected='selected'";}?> value='Friday'>Friday</option>
             <option <?php if($_REQUEST['sd'] ==="Saturday"){echo "selected='selected'";}?> value='Saturday'>Saturday</option>
             <option <?php if($_REQUEST['sd'] ==="Sunday"){echo "selected='selected'";}?> value='Sunday'>Sunday</option>
            <?php die;         
        }
        
        public function end_day(){
             ?> 
             <option value=''>End Days</option>
             <option <?php if($_REQUEST['ed'] ==="Monday"){echo "selected='selected'";}?> value='Monday'>Monday</option>
             <option <?php if($_REQUEST['ed'] ==="Tuesday"){echo "selected='selected'";}?> value='Tuesday'>Tuesday</option>
             <option <?php if($_REQUEST['ed'] ==="Wednesday"){echo "selected='selected'";}?> value='Wednesday'>Wednesday</option>
             <option <?php if($_REQUEST['ed'] ==="Thirsday"){echo "selected='selected'";}?> value='Thirsday'>Thirsday</option>
             <option <?php if($_REQUEST['ed'] ==="Friday"){echo "selected='selected'";}?> value='Friday'>Friday</option>
             <option <?php if($_REQUEST['ed'] ==="Saturday"){echo "selected='selected'";}?> value='Saturday'>Saturday</option>
             <option <?php if($_REQUEST['ed'] ==="Sunday"){echo "selected='selected'";}?> value='Sunday'>Sunday</option>
            <?php die;
        }
        
        
        
        
        
        public function start_time(){
            echo "<option value=''>Start Time</option>";
            for($i=1;$i<=24;$i++){
                ?>              
                <option <?php if($_REQUEST['st'] ==$i){echo "selected='selected'";}?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                <?php 
            }die;
        }
        
        public function end_time(){
            echo "<option value=''>End Time</option>";
            for($i=1;$i<=24;$i++){
                ?>              
                <option <?php if($_REQUEST['et'] ==$i){echo "selected='selected'";}?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                <?php 
            }die;
        }

        public function savetat(){
            $data=$this->request->data['ecrid'];
            $ClientId = $this->Session->read('companyid');
            
             $keys = array_keys($data);
             
             foreach($keys as $k)
             {
                $dataY = explode("@@",$data[$k]);
                $i=1;
                $j=1;
                foreach($dataY as $d)
                {
                    $dataZ[$k]['Category'.$i.'Id'] = $d;
                    $ecr = $this->EcrMaster->find('first',array('fields'=>'ecrName','conditions'=>array('id'=>$d,'client'=>$ClientId)));
                    $dataZ[$k]['Category'.$i++] = $ecr['EcrMaster']['ecrName'];
                }
                
                foreach($dataY as $m)
                {
                    $dataZ1[$k]['Category'.$j.'Id'] ="'".$m."'";  
                    $ecr = $this->EcrMaster->find('first',array('fields'=>'ecrName','conditions'=>array('id'=>$m,'client'=>$ClientId)));
                    $dataZ1[$k]['Category'.$j++] ="'".$ecr['EcrMaster']['ecrName']."'";  
                }
                
             }
             
             
             
             
            //print_r($dataZ); exit;
            
            foreach($keys as $id){
                $exist=$this->TimePeriod->find('first',array('fields'=>'id','conditions'=>array('ecr_id'=>$id,'clientId'=>$ClientId)));
                if(empty($exist)){
                    $dataArr=$dataZ[$id];
                   
                    if(isset($this->request->data['hour'.$id]) && $this->request->data['hour'.$id] !=""){$dataArr['time_Hours']=$this->request->data['hour'.$id];}else{unset($dataArr['time_Hours']);}
                    if(isset($this->request->data['wtype'.$id]) && $this->request->data['wtype'.$id] !=""){$dataArr['working_type']=$this->request->data['wtype'.$id];}else{unset($dataArr['working_type']);}
                    if(isset($this->request->data['wstime'.$id]) && $this->request->data['wstime'.$id] !=""){$dataArr['start_time']=$this->request->data['wstime'.$id];}else{unset($dataArr['start_time']);}
                    if(isset($this->request->data['wetime'.$id]) && $this->request->data['wetime'.$id] !=""){$dataArr['end_time']=$this->request->data['wetime'.$id];}else{unset($dataArr['end_time']);}
                    if(isset($this->request->data['wsday'.$id]) && $this->request->data['wsday'.$id] !=""){$dataArr['start_day']=$this->request->data['wsday'.$id];}else{unset($dataArr['start_day']);}
                    if(isset($this->request->data['weday'.$id]) && $this->request->data['weday'.$id] !=""){$dataArr['end_day']=$this->request->data['weday'.$id];}else{unset($dataArr['end_day']);}        
                    
                   $dataArr['clientId']=$ClientId;
                   $dataArr['ecr_id']=$id;
                   
                   

                    if($dataArr['working_type'] !=""){
                        $this->TimePeriod->saveAll($dataArr);
                    }
                }
                else{
                    $dataArr1=$dataZ1[$id];
                    if(isset($this->request->data['hour'.$id]) && $this->request->data['hour'.$id] !=""){$dataArr1['time_Hours']="'".$this->request->data['hour'.$id]."'";}else{unset($dataArr1['time_Hours']);}
                    if(isset($this->request->data['wtype'.$id]) && $this->request->data['wtype'.$id] !=""){$dataArr1['working_type']="'".$this->request->data['wtype'.$id]."'";}else{unset($dataArr1['working_type']);}
                    if(isset($this->request->data['wstime'.$id]) && $this->request->data['wstime'.$id] !=""){$dataArr1['start_time']="'".$this->request->data['wstime'.$id]."'";}else{unset($dataArr1['start_time']);}
                    if(isset($this->request->data['wetime'.$id]) && $this->request->data['wetime'.$id] !=""){$dataArr1['end_time']="'".$this->request->data['wetime'.$id]."'";}else{unset($dataArr1['end_time']);}
                    if(isset($this->request->data['wsday'.$id]) && $this->request->data['wsday'.$id] !=""){$dataArr1['start_day']="'".$this->request->data['wsday'.$id]."'";}else{unset($dataArr1['start_day']);}
                    if(isset($this->request->data['weday'.$id]) && $this->request->data['weday'.$id] !=""){$dataArr1['end_day']="'".$this->request->data['weday'.$id]."'";}else{unset($dataArr1['end_day']);}
                    
                    if(isset($this->request->data['wtype'.$id]) && $this->request->data['wtype'.$id] ==="Clock Hours"){
                        $dataArr1['start_time']="'".NULL."'";
                        $dataArr1['end_time']="'".NULL."'";
                        $dataArr1['start_day']="'".NULL."'";
                        $dataArr1['end_day']="'".NULL."'";  
                    }
                    
                    $dataArr1['modifydate']="'".date('Y-m-d H:i:s')."'";  
                    if(!empty($dataArr1)){
                        
                        if($dataArr1['working_type'] !=""){
                             $this->TimePeriod->updateAll($dataArr1,array('ecr_id'=>$id,'clientId'=>$ClientId));
                        }
                        
                       
                    }
                }
            }
            $this->Session->setFlash('Tat save successfully.');
            $this->redirect(array('action'=>'create_tat'));
        }

        
        public function existChild($id){
            $ClientId = $this->Session->read('companyid');
            return $this->TatMaster->find('first',array('conditions'=>array('parent_id'=>$id,'Client'=>$ClientId)));
        }
        public function getlist($data,$str)
        {
//            $keys = array_keys($data);
//            $str2= $str;
//            foreach($keys as $k)
//            {
//               
//               $str3 = $str2.",".$k;
//                
//                if(is_array($data[$k]))
//                {
//                   $str2 .=  $this->getlist($data[$k],$str3);
//                }
//                else
//                {
//                    $str4 .=$str3."@@"; 
//                }
//                echo $str4;
//                echo "<br>";
//            }
//            return $str4;
        }
        




















        public function create_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $ClientId=$this->Session->read('companyid');
                
                $data['Label'] = '1';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $this->Session->read('companyid');
                $data['ecrName'] = addslashes($this->request->data['Ecr']['category']);
                
                if(empty($this->existScenario($data['Client'],$data['ecrName'],$data['Label'],NULL))){
                    $this->ClientCategory->save($data);
                    $this->Session->setFlash("Scenario created successfully.");
                }
                else{
                   $this->Session->setFlash("<span style='color:red;'>Scenario allready exist in database.</span>"); 
                }
            }
            $this->redirect(array('action'=>'index','?'=>array('cms'=>'0')));
        }
    }
    
    public function update_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data = $this->request->data['Ecrs'];
                $keys = array_keys($data);
                $count = count($data);
                for($i = 0; $i<$count; $i++){
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                }
            }
        }
        echo "1";die;
    }
	
    public function create_type(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data['Label'] = '2';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $this->Session->read('companyid');
                $data['ecrName'] = addslashes($this->request->data['Ecr']['type']);
                $data['parent_id'] = addslashes($this->request->data['Ecr']['category']);
                
                if(empty($this->existScenario($data['Client'],$data['ecrName'],$data['Label'],$data['parent_id']))){
                    $this->ClientCategory->save($data);
                    $this->Session->setFlash("Sub scenario 1 created successfully.");
                }
                else{
                   $this->Session->setFlash("<span style='color:red;'>Sub scenario 1 allready exist in database.</span>"); 
                }
            }
            $this->redirect(array('action'=>'index','?'=>array('cms'=>'1')));
        }
    }
    
    public function update_type(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $data = $this->request->data['Ecrs']; 
                $this->set('data',$this->request->data);
                $keys = array_keys($data);
                $count = count($data);
                for($i = 0; $i<$count; $i++){
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                }
            }
        }
        echo "1";die;
    }

	public function create_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '3';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['parent_id'] = addslashes($this->request->data['Ecr']['Type']);			
			$data['ecrName'] = addslashes($this->request->data['Ecr']['sub_type1']);
                        if(empty($this->existScenario($data['Client'],$data['ecrName'],$data['Label'],$data['parent_id']))){
                            $this->ClientCategory->save($data);
                            $this->Session->setFlash("Sub scenario 2 created successfully.");
                        }
                        else{
                           $this->Session->setFlash("<span style='color:red;'>Sub scenario 2 allready exist in database.</span>"); 
                        }
  
			}
			$this->redirect(array('action'=>'index','?'=>array('cms'=>'2')));
		}
	}
        
        public function update_sub_type1()
	{
            if($this->request->is("POST"))
            {
                if(!empty($this->request->data))
                {
                    $data = $this->request->data['Ecrs'];
                    $this->set('data',$this->request->data);
                    $keys = array_keys($data);
                    $count = count($data);
                    for($i = 0; $i<$count; $i++)
                    {
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                    }
                }

            }
        echo "1";die;
	}

	public function create_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '4';
			$data['createdate'] = date('Y-m-d H:i:s');			
			$data['Client'] = $this->Session->read('companyid');
			$data['ecrName'] = addslashes($this->request->data['Ecr']['sub_type2']);
			$data['parent_id'] = addslashes($this->request->data['Ecr']['sub_type1']);
                        if(empty($this->existScenario($data['Client'],$data['ecrName'],$data['Label'],$data['parent_id']))){
                            $this->ClientCategory->save($data);
                            $this->Session->setFlash("Sub scenario 3 created successfully.");
                        }
                        else{
                           $this->Session->setFlash("<span style='color:red;'>Sub scenario 3 allready exist in database.</span>"); 
                        }
			
			}
			$this->redirect(array('action'=>'index','?'=>array('cms'=>'3')));
		}
	}
        
        public function update_sub_type2(){
            if($this->request->is("POST")){
                if(!empty($this->request->data)){
                        $data = $this->request->data['Ecrs'];
                        $this->set('data',$this->request->data);
                        $keys = array_keys($data);
                        $count = count($data);
                        for($i = 0; $i<$count; $i++)
                        {
                            $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                        }
                }
            }
            echo "1";die;
	}

	public function create_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '5';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['parent_id'] = addslashes($this->request->data['Ecr']['sub_type2']);			
			$data['ecrName'] = addslashes($this->request->data['Ecr']['sub_type3']);
                        if(empty($this->existScenario($data['Client'],$data['ecrName'],$data['Label'],$data['parent_id']))){
                            $this->ClientCategory->save($data);
                            $this->Session->setFlash("Sub scenario 4 created successfully.");
                        }
                        else{
                           $this->Session->setFlash("<span style='color:red;'>Sub scenario 4 allready exist in database.</span>"); 
                        }
			}
			$this->redirect(array('action'=>'index','?'=>array('cms'=>'4')));
		}
	}
        
        public function update_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['Ecrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			
		}
            echo "1";die;
	}
	
	public function get_label2()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        public function edit_label2()
	{
            $this->layout="ajax";
            if($this->request->is('POST'))
            {
                if(!empty($this->request->data))
                {
                $conditions['Label'] = '2';
                $conditions['parent_id'] = $this->request->data['parent_id'];
                $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                $this->set('data',$subType); 

                }
            }
	}
        
        public function edit_label2_sub1(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '2';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                    $this->set('type',$type); 
                }
            }
	}
        
        public function edit_label2_sub2(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '3';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                    $this->set('type',$type); 
                }
            }
	}
        
        

	public function get_label3()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        public function edit_label3(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '3';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                }
            }
	}
        
        public function edit_label3_sub1(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '4';
                    $conditions['parent_id'] = $this->request->data['parent_id'];			
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 			
                }
            }
	}
        
        public function edit_label3_sub2(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '4';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                    $this->set('data',$subType); 
                    $this->set('type',$type); 
                }
            }
	}
	
	public function get_label4()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '4';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        public function edit_label4_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '5';
			$conditions['parent_id'] = $this->request->data['parent_id'];			
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			}
		}
	}
	
        public function delete_ecr(){
            $id=$this->request->query['id'];
            $cid=$this->Session->read('companyid');
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }
            
            
            
            $this->ClientCategory->query("
            INSERT INTO `ecr_master_history` (`id`,`ecrName`,`parent_id`,`Label`,`Client`,`createdate`,`update_user`,`update_date`) 
            SELECT `id`,`ecrName`,`parent_id`,`Label`,`Client`,`createdate`,'$update_user',NOW() 
            FROM ecr_master WHERE id='$id' AND Client='$cid'"       
            );
            
            $this->ClientCategory->query("
                INSERT INTO `ecr_master_history` (`id`,`ecrName`,`parent_id`,`Label`,`Client`,`createdate`,`update_user`,`update_date`) 
                SELECT `id`,`ecrName`,`parent_id`,`Label`,`Client`,`createdate`,'$update_user',NOW() 
                FROM ecr_master WHERE parent_id='$id' AND Client='$cid'"       
                );
            

            if($this->ClientCategory->deleteAll(array('id'=>$id,'Client' => $this->Session->read('companyid')))){
                $this->ClientCategory->deleteAll(array('parent_id'=>$id,'Client' => $this->Session->read('companyid')));
            }
            $this->redirect(array('action' => 'index'));
        }
        
        public function existScenario($client,$name,$label,$parentid){
            return $exist = $this->EcrMaster->find('first',array('fields'=>'id','conditions'=>array('Client'=>$client,'ecrName'=>$name,'Label'=>$label,'parent_id'=>$parentid)));  
        }
	
	
}
?>