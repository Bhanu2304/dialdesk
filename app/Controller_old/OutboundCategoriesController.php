<?php
class OutboundCategoriesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('ObEcr');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'getCateogry2',
			'getCateogry3',
			'getCateogry4',
			'getCateogry5',
			'create_type',
			'getChild');
    }
	
	public function index() {
	}
	
	public function getChild()
	{
		$this->layout ='ajax';
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$label = $data['Label'] = addslashes($this->request->data['Label']);
				$label ++;
				//$category = $this->request->data['Ecr'];			
				$data['Client'] = $this->Session->read('companyid');
                                $data['CampaignId'] = $this->Session->read('CampaignId');
				//$data['ecrName'] = addslashes($this->request->data['Parent']);

                                   $parentName=addslashes($this->request->data['Parent']);
                
                                    $pos = strpos($parentName, '@@');

                                    if ($pos === false) {
                                        $data['ecrName']=$parentName;
                                    }
                                    else{ 
                                        $exp=explode('@@',$parentName);
                                        $data['id'] = $exp[0];
                                        $data['ecrName'] = $exp[1];
                                    }
                                
                                
				
				$category1 = $this->ObEcr->find('first',array('fields'=>array('id'),'conditions'=>$data));
			
				$category = array();		
				if(!empty($category1))
					{
						$data = array();
						$data['Label'] = $label;
						$data['Client'] = $this->Session->read('companyid');
						$data['parent_id'] = $category1['ObEcr']['id'];
						$data = $this->ObEcr->find('all',array('fields'=>array('id','ecrName'),'conditions'=>$data));
				
						foreach($data as $post):
							$category[$post['ObEcr']['id'].'@@'.$post['ObEcr']['ecrName']] = $post['ObEcr']['ecrName'];
						endforeach;
					}
			}
			
			$this->set('label',$label);
			$this->set('options',$category);			
		}		
	}

	
}
?>