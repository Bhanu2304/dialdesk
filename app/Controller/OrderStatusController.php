<?php
class OrderStatusController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses = array('VicidialListMaster','vicidialCloserLog','RegistrationMaster');
    public function beforeFilter() {
        parent::beforeFilter();
		
        $this->Auth->allow(
			'index',
            'export_data');

        $this->VicidialListMaster->useDbConfig = 'db2';
		
    }
	
    public function index()
    {
		$this->layout='user';
        // echo "hello";die;
        // $farm_didi_arr = $this->VicidialListMaster->find('all',array('fields'=>array("modify_date","phone_number","last_name"),array('conditions'=>array('list_id'=>2220,'user IS NOT NULL','user !=' => ''))));
        $qry = "SELECT modify_date,phone_number,last_name,address3,called_count,comments FROM  vicidial_list  WHERE list_id ='202401' AND (USER IS NOT NULL && USER!='') and comments!='' and  DATE(modify_date) >= '2024-01-30' order by modify_date desc";
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $farm_didi_arr=$this->vicidialCloserLog->query($qry);
        $tag_data = array();
        foreach($farm_didi_arr as $farm)
        {
            $order_id = $farm['vicidial_list']['address3'];
            $tag_qry = "SELECT * FROM farm_didi  WHERE order_id='$order_id'";
            $tag_data_arr = $this->RegistrationMaster->query($tag_qry); 
            if(!empty($tag_data_arr))
            {
                #print_r($tag_data_arr);die;
                $tag_status = $tag_data_arr[0]['farm_didi']['tag_status'];
                $tag['tag_status'] = $tag_status;
            }else{
                $tag['tag_status'] = " ";
            }
            #print_r($tag_data_arr);die;
            
      

            $tag_data[] = array_merge($farm,$tag);
        }
        #print_r($tag_data);die;
        $this->set('tag_data',$tag_data);
		$this->set('farm_didi_arr',$farm_didi_arr);
            
	}

    public function export_data()
    {
        if($this->request->is("POST")){
            
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=order_status.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $qry = "SELECT modify_date,phone_number,last_name,address3,called_count,comments FROM  vicidial_list  WHERE list_id ='202401' AND (USER IS NOT NULL && USER!='') and comments!='' and DATE(modify_date) >= '2024-01-30' order by modify_date desc";
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $farm_didi_arr=$this->vicidialCloserLog->query($qry); 
            $tag_data = array();
            foreach($farm_didi_arr as $farm)
            {
                $order_id = $farm['vicidial_list']['address3'];
                $tag_qry = "SELECT * FROM farm_didi  WHERE order_id='$order_id'";
                $tag_data_arr = $this->RegistrationMaster->query($tag_qry); 
                if(!empty($tag_data_arr))
                {
                    #print_r($tag_data_arr);die;
                    $tag_status = $tag_data_arr[0]['farm_didi']['tag_status'];
                    $tag['tag_status'] = $tag_status;
                }else{
                    $tag['tag_status'] = " ";
                }
                #print_r($tag_data_arr);die;
                
        

                $tag_data[] = array_merge($farm,$tag);
            }?>
        
            <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered" >
                <tr>
                    <th>SrNo.</th>
                    <th>Order Id</th>
                    <th>Phone No.</th>
                    <th>Call Date</th>
                    <th>Status</th>
                    <th>Call Count</th>
                    <th>Tag Status</th>
                    <th>Response</th>
                </tr>

                <?php $i=1;
                foreach($tag_data as $data):
                    #print_r($data);die;
                    #if($data['vicidial_list']['called_count'] == '4' && $data['vicidial_list']['last_name']== '' && $data['vicidial_list']['comments'] =='1'){
                    if($data['vicidial_list']['called_count'] == '4' && $data['vicidial_list']['last_name']== '' && $data['vicidial_list']['comments'] =='1' || $data['vicidial_list']['last_name']== '1' && $data['vicidial_list']['comments'] =='1' || $data['vicidial_list']['last_name']== '2' && $data['vicidial_list']['comments'] =='1'){
                    }else{
                        echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>".$data['vicidial_list']['address3']."</td>";
                            echo "<td>".$data['vicidial_list']['phone_number']."</td>";
                            #echo "<td>".$data['vicidial_list']['modify_date']."</td>";
                            echo "<td>".date_format(date_create($data['vicidial_list']['modify_date']),'d M Y')."</td>";
                            #echo "<td>" . ($data['vicidial_list']['last_name'] == '1' ? 'Yes' : 'No') . "</td>";
                            echo "<td>" . ($data['vicidial_list']['last_name'] == '1' ? 'Yes' : ($data['vicidial_list']['last_name'] == '2' ? 'No' : 'N/A')) . "</td>";
                            echo "<td>".$data['vicidial_list']['called_count']."</td>";
                            echo "<td>".$data['tag_status']."</td>";
                            echo "<td>".$data['vicidial_list']['comments']."</td>";

                        echo "</tr>";
                    }

                endforeach;
                ?>
                </table>
                
                <?php
                }  die;   
       
    }
	
    

    

        
        
       
        
	
}
?>