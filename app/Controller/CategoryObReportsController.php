<?php
class CategoryObReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','vicidialCloserLog','vicidialUserLog','CroneJob','AbandCallMaster','CallMasterOut');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        $flag = false;
        if($this->Session->check("companyid"))
        {
            $flag = true;
        }
        if($this->Session->check("admin_id"))
        {
            $flag = true;
        }
        if(!$flag)
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index() {
        $this->layout='user';
        if($this->request->is("POST")){

            //$campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
            $scenario=$this->request->data['CategoryObReports'];

            $category   =   $scenario['Category'];

            $calldate_Arr = array();
            $parent_Arr = array();
            $child_Arr = array();
            //$contribution = array();


            $dt = date("Y-m-01");
            $calldateqry = "SELECT *,date(CallDate) CallDate FROM `call_master_out` WHERE ClientId='$clientId' AND DATE(CallDate) BETWEEN ('$dt') AND CURDATE()";
            $data_arr=$this->CallMasterOut->query($calldateqry);
            foreach($data_arr as $data)
            {
                $calldate_Arr[$data['0']['CallDate']] = $data['0']['CallDate'];
                $parent_Arr[$data['call_master_out']['Category1']] += 1;
                $parent_data_Arr[$data['0']['CallDate']][$data['call_master_out']['Category1']] += 1;

                $child_Arr[$data['call_master_out']['Category1']][$data['call_master_out']['Category2']] += 1;
                $child_data_Arr[$data['0']['CallDate']][$data['call_master_out']['Category1']][$data['call_master_out']['Category2']] += 1; 

            }

            $this->set('calldate_Arr',$calldate_Arr); 
            $this->set('parent_Arr',$parent_Arr);
            $this->set('parent_data_Arr',$parent_data_Arr);
            $this->set('child_Arr',$child_Arr);
            $this->set('child_data_Arr',$child_data_Arr);
            $this->set('category',$category);
        }
    }
    
    
    public function export_ob_report()
    {
        if($this->request->is("POST")){
 
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=ob_scenario_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
           
                
            $clientId   =   $this->Session->read('companyid');
            $calldate_Arr = array();
            $parent_Arr = array();
            $child_Arr = array();
            $dt = date("Y-m-01");
            $calldateqry = "SELECT *,date(CallDate) CallDate FROM `call_master_out` WHERE ClientId='$clientId' AND DATE(CallDate) BETWEEN ('$dt') AND CURDATE()";
            $data_arr=$this->CallMasterOut->query($calldateqry);
            foreach($data_arr as $data)
            {

                $calldate_Arr[$data['0']['CallDate']] = $data['0']['CallDate'];
                $parent_Arr[$data['0']['CallDate']][$data['call_master_out']['Category1']] += 1;
                $child_Arr[$data['0']['CallDate']][$data['call_master_out']['Category1']][$data['call_master_out']['Category2']] += 1;

            }
            
            ?>
        
            <table cellspacing="0" border="1">
                    <thead>
                        <tr>
                            <th>Scenario</th>			
                            <th>MTD</th>
                        <?php foreach($calldate_Arr as $calldate) {

                            echo "<th>DAY ".date('d',strtotime($calldate))."</th>"; 
                          
                        }?>

                        </tr>
                        
                    </thead>
                        <tbody>
                          <tr>
                            <td>Not Contacted</td>
                                <?php 
                                foreach($parent_Arr as $parent){
                                    $mtd = $parent['Not Contacted'];
                                    $ABC = $ABC+$mtd;
        
                                }
                                echo "<td>".$ABC."</td>";

                                foreach($parent_Arr as $parent){

                                echo "<td>".$parent['Not Contacted']."</td>";
        
                                }
                                ?>
                                 
                            </tr>
                            
                            <tr>
                               <td>Contacted</td>

                            <?php $total = array();
                              foreach($parent_Arr as $parent){

                               $mtd1 = $parent['Contacted'];
                                    $contacted =$contacted+$mtd1;
                              }
                               echo "<td>".$contacted."</td>";
                               foreach($parent_Arr as $parent){

                                echo "<td>".$parent['Contacted']."</td>";

                                $total[] = $parent['Contacted'] + $parent['Not Contacted'];
        
                                }

                            ?>
                            </tr>
                            <tr>
                               <td>Grand Total</td>
                               <td><?php echo $contacted+$ABC; ?></td>
                               <?php foreach ($total as $to){

                                echo '<td>'.$to.'</td>';
                               }?>
                            </tr>
                        </tbody>
            </table>

            <?php
            }
            
           
                
             die;   
       
        
    }
 

}
?>