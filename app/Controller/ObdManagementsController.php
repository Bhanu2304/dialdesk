<?php
class ObdManagementsController extends AppController{
    //ini_set('max_execution_time', '0');
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','ObdData','ObdList','VicidialListMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('DataUpload','addlist','delete_list','report','export_report');
        // if(!$this->Session->check("admin_id"))
        // {
        //         return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        // }
    }

    public function DataUpload()
    {
        $this->layout = "user";

        $list =$this->ObdList->find('list',array('fields'=>array("list_id","list_id")));
        //$list = $this->ObdList->find('list_id');
        //print_r($list);die;
        $this->set('list',$list); 

        if($this->request->is('Post') && !empty($this->request->data))
        {
            //print_r($this->request->data);die;
            $update_date=date('Y-m-d H:i:s'); 
            $list_id = $this->request->data['ObdManagement']['listid'];
            $csv_file = $this->request->data['ObdManagement']['uploadfile']['tmp_name'];
			$FileTye = $this->request->data['ObdManagement']['uploadfile']['type'];
			$info = explode(".",$this->request->data['ObdManagement']['uploadfile']['name']);
			//print_r($this->request->data); die;
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if(($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 

					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
						
						$this->ObdData->saveAll(array('list_id'=>"$list_id",'msisdn'=>"{$filedata[0]}",'createdate'=>"{$update_date}"));           
					}
					 
				}

				$this->Session->setFlash('<span style="color:green;">CSV Upload SuccessFully</span>');
				$this->redirect(array('controller' => 'ObdManagements','action' => 'DataUpload'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV format.');
			}

        }
    }

    public function addlist()
    {
        $this->layout = "user";

        if($this->request->is("POST"))
        {
            //print_r($this->request->data);die;
            $update_date=date('Y-m-d H:i:s');
            $update_user=$this->Session->read('companyname');
           //print_r($update_user);die;
            $data=$this->request->data['ObdManagement'];

            $list_id     =   $data['listid'];
            $description =   $data['Description'];
  
            $this->ObdList->saveAll(array('list_id'=>"$list_id",'description'=>"$description",'created_by'=>"{$update_user}",'createdate'=>"{$update_date}"));
            $this->Session->setFlash('<span style="color:green;">List Add SuccessFully</span>');  
            $this->redirect(array('controller' => 'ObdManagements','action' => 'addlist'));

            $this->set('data',$data);
         
        }

            $result = $this->ObdList->find('all');

            $this->set('result',$result);
    }

    public function delete_list()
    {
        $this->layout = "user";

        $id  = $this->request->query['id'];
                      
		$this->ObdList->delete(array('id'=>$id));
		$this->redirect(array('action' => 'addlist'));
    }

    public function report() 
    {
        $this->layout='user';
                
        if($this->request->is("POST")){
            
            $search=$this->request->data['ObdManagement'];

            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d 00:00:00",strtotime("$FromDate"));
            $end_time=date("Y-m-d  23:59:59",strtotime("$ToDate"));

        
            $result = $this->ObdList->find('list',array('fields'=>array("list_id")));
            $result = array_unique($result);
            $list = implode(",",$result);
           // print_r($list);die;
            
           $qry = "select * from vicidial_list where entry_date>='$start_time' AND entry_date<'$end_time' AND list_id IN($list)";

            $this->VicidialListMaster->useDbConfig = 'db2';
            $dataArr = $this->VicidialListMaster->query($qry);

            //print_r($dataArr);die;
            $this->set('data',$dataArr);
         
        }

    }
    public function export_report()
    {
        if($this->request->is("POST"))
        {

            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            
            $search=$this->request->data['ObdManagement'];

            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d 00:00:00",strtotime("$FromDate"));
            $end_time=date("Y-m-d  23:59:59",strtotime("$ToDate"));

            $result = $this->ObdList->find('list',array('fields'=>array("list_id")));
            $list = implode(",",$result);
            
            $qry = "select * from vicidial_list where entry_date>='$start_time' AND entry_date<'$end_time' AND list_id IN($list)";

            $this->VicidialListMaster->useDbConfig = 'db2';
            $dataArr = $this->VicidialListMaster->query($qry);
            
            
            ?>
        
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Date</th>			
                    <th>Lead Id</th>
                    <th>User</th>
                    <th>Source Id</th>
                    <th>List Id</th>
                    <th>Phone Number</th>
                    <th>GMT Offset Now</th>
                    <th>Status</th>
                </tr>
                
            </thead>
            <tbody>
                <?php $i=1; foreach($dataArr as $record) { ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date_format(date_create($record['vicidial_list']['entry_date']),'d M Y H:i:s'); ?></td>
                    <td><?php echo $record['vicidial_list']['lead_id']; ?></td>
                    <td><?php echo $record['vicidial_list']['user']; ?></td>
                    <td><?php echo $record['vicidial_list']['source_id']; ?></td>
                    <td><?php echo $record['vicidial_list']['list_id']; ?></td>
                    <td><?php echo $record['vicidial_list']['phone_number']; ?></td>
                    <td><?php echo $record['vicidial_list']['gmt_offset_now']; ?></td>
                    <td><?php echo $record['vicidial_list']['status']; ?></td>
                </tr>		
                <?php } ?>
            </tbody>
        </table>

            <?php
            }
            
           
                
             die;   
       
        
    }



   


}
?>