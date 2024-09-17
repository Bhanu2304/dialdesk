<?php
class LoginLogController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','CallMasterOut','vicidialUserLog','VicidialListMaster','AbandCallMaster','LoginLog');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        // if(!$this->Session->check("companyid"))
        // {
        //     return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        // }
    }
    
   public function index(){
        $this->layout='user';

        if($this->request->is("POST"))
        {
            //print_r($this->request->data);die;
            $search     =  $this->request->data['LoginLog'];
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            

            $select = "SELECT * FROM `login_log` WHERE date(hit_time) between '$start_time_start' AND '$start_time_end' AND user_name IS NOT NULL AND page_name IS NOT NULL";
            $log_data = $this->LoginLog->query($select);
        
            $this->set('data',$log_data); 

        }
        
    }
    public function export_log()
    {
        $this->layout='user';
        if($this->request->is("POST")){


            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=login_log.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $search     =  $this->request->data['LoginLog'];
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $select = "SELECT * FROM `login_log` WHERE date(hit_time) between '$start_time_start' AND '$start_time_end' AND user_name IS NOT NULL AND page_name IS NOT NULL";
            $log_data = $this->LoginLog->query($select);

            ?>
                <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>IP Address</th>
                        <th>Page Name</th>
                        <th>Page Url</th>
                        <th>Hit Time</th>
                    </tr>

                    <?php $i =1;
                    foreach($log_data as $d)
                    {
                        echo "<tr>";
                        echo "<td>".$i++."</td>";
                        echo "<td>".$d['login_log']['user_name']."</td>";
                        echo "<td>".$d['login_log']['type']."</td>";
                        echo "<td>".$d['login_log']['ip_address']."</td>";
                        echo "<td>".$d['login_log']['page_name']."</td>";
                        echo "<td>".$d['login_log']['page_url']."</td>";
                        echo "<td>".date_format(date_create($d['login_log']['hit_time']),'d M Y H:i:s')."</td>";
                        echo "</tr>";
                    }
                    ?>    
                </table>
            <?php die;

        }

    }

}
?>