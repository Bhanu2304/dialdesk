<?php
class OutboundCategoriesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('ObEcr','ObCallFlow');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'getCateogry2',
			'getCateogry3',
			'getCateogry4',
			'getCateogry5',
			'create_type',
			'getChild',
		'getResolution');
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

	public function getResolution(){
        $this->layout ='ajax';
            if($this->request->is("POST")){
                if(!empty($this->request->data)){

                    $fields = $this->request->data;
                    $client_id = $this->Session->read('companyid');
                    $campaign_id = $this->Session->read('CampaignId');
                    $data_check = array();
                    //print_r($fields);die;
                    foreach($fields as $key=>$field)
                    {
                        if($key == 'scenario1')
                        {
                            $data_check['category']=$field;

                        }else if($key == 'scenario2')
                        {
                            $field_data =explode('@@',$field);
                            $data_check['type']=$field_data[1];
                        }
                        else if($key == 'scenario3')
                        {
                            $field_data =explode('@@',$field);
                            $data_check['subtype']=$field_data[1];
                        }
                        else if($key == 'scenario4')
                        {
                            $field_data =explode('@@',$field);
                            $data_check['subtype1']=$field_data[1];
                        }
                        else if($key == 'scenario5')
                        {
                            $field_data =explode('@@',$field);
                            $data_check['subtype2']=$field_data[1];
                        }
                   
            
                    }
                    $wheretag = '';
                    if($fields['language'] == 'Hi')
                    {
                        $wheretag .= "and language='Hi'";
                    }
                //$data_check['client_id'] = $client_id;
                //$callflow_data = $this->CallFlow->find('all',array('fields'=>array('id','resolution'),'conditions'=>$data_check));


            $res_qry = "SELECT resolution  FROM obcall_flow WHERE 
                CONCAT(category, IF(`type` IS NOT NULL OR `type` !='',CONCAT(',',`type`),''),
                IF(`subtype` IS NOT NULL OR `subtype` !='',CONCAT(',',subtype),''), IF(`subtype1` IS NOT NULL OR `subtype1` !='',
                CONCAT(',',subtype1),''),
                IF(`subtype2` IS NOT NULL OR `subtype2` !='',CONCAT(',',subtype2),''))='".implode(",",$data_check)."' and clientId='$client_id' and campaignid='$campaign_id' $wheretag limit 1";

                $Res_data = $this->ObCallFlow->query($res_qry);

                if(empty($Res_data))
                {
                    $res_qry2 = "SELECT resolution  FROM obcall_flow WHERE 
                    CONCAT(category, IF(`type` IS NOT NULL OR `type` !='',CONCAT(',',`type`),''),
                    IF(`subtype` IS NOT NULL OR `subtype` !='',CONCAT(',',subtype),''), IF(`subtype1` IS NOT NULL OR `subtype1` !='',
                    CONCAT(',',subtype1),''),
                    IF(`subtype2` IS NOT NULL OR `subtype2` !='',CONCAT(',',subtype2),''))='' and clientId='$client_id' and campaignid='$campaign_id' $wheretag limit 1";

                 $Res_data = $this->ObCallFlow->query($res_qry2);
                }
                //print_r($Res_data);
                echo ("{$Res_data[0]['obcall_flow']['resolution']}");
                
                    
                        
                }die;
              
        }		
        }

	
}
?>