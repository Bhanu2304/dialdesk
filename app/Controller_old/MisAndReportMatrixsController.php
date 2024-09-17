<?php
class MisAndReportMatrixsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ReportMatrixMaster','MisReportMatrixMaster','ReportMaster');
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow(
			'save_report_matrix',
			'view_report_matrix',
			'delete_report_matrix');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
		$this->layout='user';
		$report = $this->ReportMaster->find('list',array('fields'=>array("report_name","report_name")));
		$this->set('report',$report);
		if(isset($this->request->query['id'])){
			$id=$this->request->query['id'];
			$clientid=$this->Session->read('companyid');
			$result = $this->ReportMatrixMaster->find('first',array('conditions' =>array('id' =>$id,'client_id' =>$clientid)));	
			$this->set('matrixArr',$result);
                        
                        
		}
                
    
                
               $result1 = $this->ReportMatrixMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'))));
		$this->set('data',$result1);
                
	}
	
	public function save_report_matrix(){
		if($this->request->is('Post')){
			if($this->request->data['daywise'] !=""){
				$report_val=$this->request->data['daywise'];
			}
			if($this->request->data['hourwise'] !=""){
                            
                            $Exp        =   explode(":", $this->request->data['hourwise']);
                            $H          =   current($Exp) !='00'?ltrim(current($Exp), '0'):'0';
                            $M          =   end($Exp) !='00'?ltrim(end($Exp), '0'):'0';

                            $report_val_data =   $H.':'.$M;
                            $report_val =   str_replace(' ', '', $report_val_data);
                            
                            //$report_val =   str_replace(' ', '', $this->request->data['hourwise']);            
			}
			if($this->request->data['monthwise'] !=""){
                            $monthdate=$this->request->data['monthwise']; 
                            $report_val = date("Y-m-d", strtotime($monthdate));       
			}
			$data=array(
					'client_id'=>$this->Session->read('companyid'),
					'user_name'=>$this->request->data['user_name'],
					'user_designation'=>$this->request->data['user_designation'],
					'user_mobile'=>$this->request->data['user_mobile'],
					'user_email'=>$this->request->data['user_email'],
					'report'=>$this->request->data['MisAndReportMatrixs']['report'],
					'report_type'=>$this->request->data['report_type'],
					'report_value'=>$report_val,
					'send_type'=>implode(",",$this->request->data['send_type']),
				);
					
			if($_POST['submit'] && $_POST['submit']=='Update'){
				$data=array(
					'client_id'=>"'".$this->Session->read('companyid')."'",
					'user_name'=>"'".$this->request->data['user_name']."'",
					'user_designation'=>"'".$this->request->data['user_designation']."'",
					'user_mobile'=>"'".$this->request->data['user_mobile']."'",
					'user_email'=>"'".$this->request->data['user_email']."'",
					'report'=>"'".$this->request->data['MisAndReportMatrixs']['report']."'",
					'report_type'=>"'".$this->request->data['report_type']."'",
					'report_value'=>"'".$report_val."'",
					'send_type'=>"'".implode(",",$this->request->data['send_type'])."'"
				);
				$this->ReportMatrixMaster->updateAll($data,array('id'=>$this->request->data['updateid'],'client_id' => $this->Session->read('companyid')));
			}
			else{
				$this->ReportMatrixMaster->save($data);
			}
			$this->redirect(array('controller' => 'MisAndReportMatrixs'));
		}
	}

	public function view_report_matrix(){
		$this->layout='user';
		$result = $this->ReportMatrixMaster->find('all',array('conditions' =>array('client_id' => $this->Session->read('companyid'))));
		$this->set('data',$result);
	}
	
	public function delete_report_matrix(){
		$id  = $this->request->query['id'];
		$this->ReportMatrixMaster->delete(array('id'=>$id,'client_id' => $this->Session->read('companyid')));
		$this->redirect(array('action' => 'index'));
	}
}
?>