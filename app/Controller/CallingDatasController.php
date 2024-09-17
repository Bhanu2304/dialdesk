<?php
class CallingDatasController extends AppController{
	public $uses=array('CallingData');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow(
			'add');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'CallingDatas','action' => 'add'));
		}
    }


	public function add(){
    $this->layout = false;
    $response = array('status'=>'failed', 'message'=>'HTTP method not allowed');
if($this->request->is('post')){
        //prnt_r($_POST);
        //get data from request object
	 //die;
        $data = $this->request->input('json_decode', true); print_r($data); die;
        if(empty($data)){
            $data = $this->request->data;
        }
        
        //response if post data or form data was not passed
        $response = array('status'=>'failed', 'message'=>'Please provide form data');
            
        if(!empty($data)){
            //call the model's save function
            if($this->CallingData->save($data)){
                //return success
                $response = array('status'=>'success','message'=>'Product successfully created');
            } else{
                $response = array('status'=>'failed', 'message'=>'Failed to save data');
            }
        }
    }
        
    $this->response->type('application/json');
    $this->response->body(json_encode($response));
    return $this->response->send();
}
	
}
?>