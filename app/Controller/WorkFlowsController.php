<?php
class WorkFlowsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('WorkFlow','Ivr','ClientCategory','ecrNameMaster','EcrMaster','TatMaster','TimePeriod','WorkflowMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Session->check('companyid')){
            $this->Auth->allow('save_workflow','buildMenu','delete_workflow');
        }
    }
	
    public function index(){    
        $this->layout='user';
	$ClientId = $this->Session->read('companyid');     
        $page = $this->WorkflowMaster->find('all',array('conditions'=>array('clientId'=>$ClientId)));
        
        $menu = array(
            'menus' => array(),
            'parent_menus' => array(),
        );
           
        foreach($page as $row){
            $menu['menus'][$row['WorkflowMaster']['id']] = $row['WorkflowMaster'];
            $menu['parent_menus'][$row['WorkflowMaster']['parent_id']][] = $row['WorkflowMaster']['id'];
        }
        $this->set('UserRight',$this->buildMenu(0, $menu));           
    }

    function buildMenu($parent, $menu) {
        $html = "";
        $edit = "";
        $char=" ";
        $wfstime = "";
        $wfetime = "";
        $dialdesk = "";
        $internal = "";
        $wfnum = "";
 
        if (isset($menu['parent_menus'][$parent])) {
            foreach ($menu['parent_menus'][$parent] as $menu_id) {
                $eid        =   $menu['menus'][$menu_id]['id'];
                $workflow   =   $this->WorkFlow->find('first',array('conditions'=>array('clientId'=>$this->Session->read('companyid'),'parent_id' =>$eid))); 
                if(!empty($workflow)){
                    $wfstime=isset($workflow['WorkFlow']['start_time'])? $workflow['WorkFlow']['start_time']:"";
                    $wfetime=isset($workflow['WorkFlow']['end_time'])? $workflow['WorkFlow']['end_time']:"";
                    $wftype=isset($workflow['WorkFlow']['transferType'])? $workflow['WorkFlow']['transferType']:"";
                    $wfnum=isset($workflow['WorkFlow']['Numbers'])? $workflow['WorkFlow']['Numbers']:"";
                    
                    $dialdesk =  $workflow['WorkFlow']['transferType']=='DialDesk'?'checked = "checked"':'';
                    $internal =  $workflow['WorkFlow']['transferType']=='Internal'?'checked = "checked"':'';
                    if($wftype ==="Internal"){
                        $script= $this->webroot."js/assets/js/jquery-1.10.2.min.js";
                        echo " 
                            <script src='$script'></script>
                            <script type=\"text/javascript\">                   
                             $( document ).ready(function() {
                                editNumber('$wftype','$eid'); 
                             });
                            </script>
                            ";
                    }
                    if($wftype !=""){  
                        $edit = "<label style='margin-left:10px;margin-top:5px;' onclick='deleteWorkFlow($eid)'  class='btn btn-xs tn-midnightblue btn-raised'><i class='fa fa-trash'></i></label>";
                    }
                }
                
                if (!isset($menu['parent_menus'][$menu_id])) {
                   $html .= "<li><span class='textlabel'>".$menu['menus'][$menu_id]['Msg'].
                           "<span> "
                           . "<input type='hidden'  name='parentid[]'  value='$eid'> "
                           . "<input type='text' value='$wfstime' name='wfstime$eid'  class='textbox timepicker' placeholder='Start' > "
                           . "<input type='text' value='$wfetime' name='wfetime$eid'  class='textbox timepicker1' placeholder='End' > "
                           . "Dialdesk <input type='radio' $dialdesk  value='DialDesk' onchange='getNumber(this.value,$eid)' name='wftype$eid' > "
                           . "Internal <input type='radio' $internal  value='Internal' onchange='getNumber(this.value,$eid)' name='wftype$eid' > "
                           . "<input type='text' value='$wfnum' name='wfnumber$eid' id='wfnumber$eid' style='display:none;' placeholder='Phone NO'> $edit </li>"; 
                }
                if (isset($menu['parent_menus'][$menu_id])) {
                    $html .= "<li><span class='textlabel'>".$menu['menus'][$menu_id]['Msg'].
                           "<span> "
                           . "<input type='hidden'  name='parentid[]'  value='$eid'> "
                           . "<input type='text' value='$wfstime' name='wfstime$eid'  class='textbox timepicker' placeholder='Start' > "
                           . "<input type='text' value='$wfetime' name='wfetime$eid'  class='textbox timepicker1' placeholder='End' > "
                           . "Dialdesk <input type='radio' $dialdesk  value='DialDesk' onchange='getNumber(this.value,$eid)' name='wftype$eid' > "
                           . "Internal <input type='radio' $internal  value='Internal' onchange='getNumber(this.value,$eid)' name='wftype$eid' > "
                           . "<input type='text' value='$wfnum' name='wfnumber$eid' id='wfnumber$eid' style='display:none;' placeholder='Phone NO'> $edit"; 
                    $html .= "<ul class='ecrtree'>";
                    $html .= $this->buildMenu($menu_id, $menu);
                    $html .= "</ul>";
                    $html .= "</span></li>";
                }
            }
        }
        return $html;
    }
       
    public function save_workflow(){
        $data=$this->request->data['parentid'];
        $ClientId = $this->Session->read('companyid');
        foreach($data as $id){
            $exist=$this->WorkFlow->find('first',array('fields'=>'id','conditions'=>array('parent_id'=>$id,'clientId'=>$ClientId)));
            if(empty($exist)){
                $dataArr['clientId']=$ClientId;
                $dataArr['parent_id']=$id;
                if(isset($this->request->data['wfstime'.$id]) && $this->request->data['wfstime'.$id] !=""){$dataArr['start_time']=$this->request->data['wfstime'.$id];}else{unset($dataArr['start_time']);}
                if(isset($this->request->data['wfetime'.$id]) && $this->request->data['wfetime'.$id] !=""){$dataArr['end_time']=$this->request->data['wfetime'.$id];}else{unset($dataArr['end_time']);}
                if(isset($this->request->data['wftype'.$id]) && $this->request->data['wftype'.$id] !=""){$dataArr['transferType']=$this->request->data['wftype'.$id];}else{unset($dataArr['transferType']);}
                if(isset($this->request->data['wfnumber'.$id]) && $this->request->data['wfnumber'.$id] !=""){$dataArr['Numbers']=$this->request->data['wfnumber'.$id];}else{unset($dataArr['Numbers']);}
                $dataArr['createdate']=date('Y-m-d H:i:s');
                if($dataArr['transferType'] !=""){
                    $this->WorkFlow->saveAll($dataArr); 
                }
            }
            else{              
                if(isset($this->request->data['wfstime'.$id]) && $this->request->data['wfstime'.$id] !=""){$dataArr1['start_time']="'".$this->request->data['wfstime'.$id]."'";}else{unset($dataArr1['start_time']);}
                if(isset($this->request->data['wfetime'.$id]) && $this->request->data['wfetime'.$id] !=""){$dataArr1['end_time']="'".$this->request->data['wfetime'.$id]."'";}else{unset($dataArr1['end_time']);}
                if(isset($this->request->data['wftype'.$id]) && $this->request->data['wftype'.$id] !=""){$dataArr1['transferType']="'".$this->request->data['wftype'.$id]."'";}else{unset($dataArr1['transferType']);}
                if(isset($this->request->data['wfnumber'.$id]) && $this->request->data['wfnumber'.$id] !=""){$dataArr1['Numbers']="'".$this->request->data['wfnumber'.$id]."'";}else{unset($dataArr1['Numbers']);}
                
                if(isset($this->request->data['wftype'.$id]) && $this->request->data['wftype'.$id] ==="DialDesk"){
                    $dataArr1['Numbers']="'".NULL."'";
                }

                if(!empty($dataArr1)){
                    if($dataArr1['transferType'] !=""){
                        $this->WorkFlow->updateAll($dataArr1,array('parent_id'=>$id,'clientId'=>$ClientId));
                    }
                }
            }
        }
        $this->Session->setFlash('Work Flow save successfully.');
        $this->redirect(array('action'=>'index'));
    }
    
    public function delete_workflow(){
        $id=$this->request->query['id'];
        $this->WorkFlow->deleteAll(array('parent_id'=>$id,'clientId' => $this->Session->read('companyid')));
        $this->redirect(array('action' => 'index'));
    }
	
}
?>