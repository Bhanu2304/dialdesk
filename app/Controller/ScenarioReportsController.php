<?php
class ScenarioReportsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','CallMaster','CallMasterOut','EcrMaster','ClientCategory','OutboundClientCategory','vicidialLog','vicidialUserLog','ClientReportMaster','AbandCallMaster','Agent');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $this->vicidialLog->useDbConfig = 'db2';

        $this->Auth->allow('index','export_inbound_scenario','outbound_scenario','export_outbound_scenario');
        if(!$this->Session->check("admin_id")){
                return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }
    }

    public function index(){
        $this->layout = "user";

        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            //$this->set('category',$this->EcrRecord->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>1,'parent_id'=>NULL))));
            //$client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }else{
            $clientId   = $this->Session->read('companyid');
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }


        if($this->request->is('Post')){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=CallTaggingSummary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            //$client = $this->request->data['ScenarioReports']['clientID'];

            $client = $this->Session->read('companyid');
           // $conditions = "and date(CallDate) between '$startdate' and '$enddate'";


            // $data_arr=$this->CallMaster->query("SELECT Category1,Category2,Category3,Category4,Category5 FROM call_master
            //     WHERE ClientId='$client' $conditions GROUP BY Category2,Category3,Category4,Category5");

            $data_ecr=$this->ClientCategory->query("select Label,ecrName,id,parent_id from ecr_master where Client='$client'");
            foreach($data_ecr as $data)
            {
                if($data["ecr_master"]["Label"] == 1)
                {
                    $cat1[$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 2)
                {
                    $cat2[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 3)
                {
                    $cat3[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 4)
                {
                    $cat4[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 5)
                {
                    $cat5[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
            }
            //print_r($cat1);exit;
            $list[] = array();
            foreach($cat1 as $parent1=>$c1)
            {
                $list[$c1] = array();
                foreach($cat2[$parent1] as $parent2=>$c2)
                {
                    $list[$c1][$c2] = array();
                    foreach($cat3[$parent2] as $parent3=>$c3)
                    {
                        $list[$c1][$c2][$c3] = array();
                        foreach($cat4[$parent3] as $parent4=>$c4)
                        {
                            $list[$c1][$c2][$c3][$c4] = array();
                            foreach($cat5[$parent4] as $c5)
                            {
                                $list[$c1][$c2][$c3][$c4][$c5][] = "";
                            }    
                        }   
                    }    
                }
            }

            
            //print_r($list);die;
            $this->set('data_ecr',$list);

            $this->set('companyid',$client); 
            // $this->set('data_arr',$data_arr); 
            
            
        }
    }
    

    
    
    public function export_inbound_scenario(){
        $this->layout = "user";
        if($this->request->is('Post')){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=inbound_scenario.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
 


            //$client = $this->request->data['ScenarioReports']['clientID'];
            $conditions = "and date(CallDate) between '$startdate' and '$enddate'";

            //print_r($this->request->data);die;
            $client = $this->Session->read('companyid');

            $data_ecr=$this->ClientCategory->query("select Label,ecrName,id,parent_id from ecr_master where Client='$client'");
            foreach($data_ecr as $data)
            {
                if($data["ecr_master"]["Label"] == 1)
                {
                    $cat1[$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 2)
                {
                    $cat2[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 3)
                {
                    $cat3[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 4)
                {
                    $cat4[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
                if($data["ecr_master"]["Label"] == 5)
                {
                    $cat5[$data["ecr_master"]["parent_id"]][$data["ecr_master"]["id"]] = $data["ecr_master"]["ecrName"];
                }
            }
            //print_r($cat1);exit;
            $list[] = array();
            foreach($cat1 as $parent1=>$c1)
            {
                $list[$c1] = array();
                foreach($cat2[$parent1] as $parent2=>$c2)
                {
                    $list[$c1][$c2] = array();
                    foreach($cat3[$parent2] as $parent3=>$c3)
                    {
                        $list[$c1][$c2][$c3] = array();
                        foreach($cat4[$parent3] as $parent4=>$c4)
                        {
                            $list[$c1][$c2][$c3][$c4] = array();
                            foreach($cat5[$parent4] as $c5)
                            {
                                $list[$c1][$c2][$c3][$c4][$c5][] = "";
                            }    
                        }   
                    }    
                }
            }

            ?>        
            <table border="1">
                <tr>
                    <th>Scenario</th>
                    <th>Sub Scenario</th>
                    <th>Sub Scenario 2</th>
                    <th>Sub Scenario 3</th>
                    <th>Sub Scenario 4</th>
                </tr>
                <?php foreach($list as $key=>$data){
                        $html = "<tr>";
                        $html .=  "<td>".$key."</td>";
                        if(empty($data))
                        {
                            echo '</tr>';
                            continue;
                        }

                        foreach($data as $d=>$key1)
                        {   
                            
                            if(empty($key1))
                            {
                                echo $html."<td>".$d."</td>".'</tr>';
                                continue;
                            }
                            $html2 =$html.  "<td>".$d."</td>";
                            foreach($key1 as $d1=>$key2)
                            {
                                
                                if(empty($key2))
                                {
                                    echo $html2."<td>".$d1."</td>".'</tr>';
                                    continue;
                                }
                                $html3 =$html2.  "<td>".$d1."</td>";
                                foreach($key2 as $d2=>$key3)
                                {
                                    
                                    
                                    if(empty($key3))
                                    {
                                        echo $html3."<td>".$d2."</td>".'</tr>';
                                        continue;
                                    }
                                    $html4 =$html3.  "<td>".$d2."</td>";
                                    foreach($key3 as $d3=>$key4)
                                    {
                                        echo $html4. "<td>".$d3."</td></tr>";
                                    }
                                }
                            }
                        }
                        
                        echo "</tr>";

                    }?>
                </table><?php die;
               
        }
    }
    

    public function export_outbound_scenario(){
        $this->layout = "user";
        if($this->request->is('Post')){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=outbound_scenario.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            //print_r($this->request->data);die;
            
            $campaign = $this->request->data['ScenarioReports']['campaign'];
            $client = $this->Session->read('companyid');
            

            // echo "select Label,ecrName,id,parent_id from obecr_master where Client='$client' and campaignId='$campaign'";die;
            $data_ecr=$this->ClientCategory->query("select Label,ecrName,id,parent_id from obecr_master where Client='$client' and campaignId='$campaign'");

            foreach($data_ecr as $data)
            {
                if($data["obecr_master"]["Label"] == 1)
                {
                    $cat1[$data["obecr_master"]["id"]] = $data["obecr_master"]["ecrName"];
                }
                if($data["obecr_master"]["Label"] == 2)
                {
                    $cat2[$data["obecr_master"]["parent_id"]][$data["obecr_master"]["id"]] = $data["obecr_master"]["ecrName"];
                }
                if($data["obecr_master"]["Label"] == 3)
                {
                    $cat3[$data["obecr_master"]["parent_id"]][$data["obecr_master"]["id"]] = $data["obecr_master"]["ecrName"];
                }
                if($data["obecr_master"]["Label"] == 4)
                {
                    $cat4[$data["obecr_master"]["parent_id"]][$data["obecr_master"]["id"]] = $data["obecr_master"]["ecrName"];
                }
                if($data["obecr_master"]["Label"] == 5)
                {
                    $cat5[$data["obecr_master"]["parent_id"]][$data["obecr_master"]["id"]] = $data["obecr_master"]["ecrName"];
                }
            }
            //print_r($cat1);exit;
            $list[] = array();
            foreach($cat1 as $parent1=>$c1)
            {
                $list[$c1] = array();
                foreach($cat2[$parent1] as $parent2=>$c2)
                {
                    $list[$c1][$c2] = array();
                    foreach($cat3[$parent2] as $parent3=>$c3)
                    {
                        $list[$c1][$c2][$c3] = array();
                        foreach($cat4[$parent3] as $parent4=>$c4)
                        {
                            $list[$c1][$c2][$c3][$c4] = array();
                            foreach($cat5[$parent4] as $c5)
                            {
                                $list[$c1][$c2][$c3][$c4][$c5][] = "";
                            }    
                        }   
                    }    
                }
            } ?>
            <table border="1">
                <tr>
                    <th>Scenario</th>
                    <th>Sub Scenario</th>
                    <th>Sub Scenario 2</th>
                    <th>Sub Scenario 3</th>
                    <th>Sub Scenario 4</th>
                </tr>
                <?php foreach($list as $key=>$data){
                        $html = "<tr>";
                        $html .=  "<td>".$key."</td>";
                        if(empty($data))
                        {
                            echo '</tr>';
                            continue;
                        }

                        foreach($data as $d=>$key1)
                        {   
                            
                            if(empty($key1))
                            {
                                echo $html."<td>".$d."</td>".'</tr>';
                                continue;
                            }
                            $html2 =$html.  "<td>".$d."</td>";
                            foreach($key1 as $d1=>$key2)
                            {
                                
                                if(empty($key2))
                                {
                                    echo $html2."<td>".$d1."</td>".'</tr>';
                                    continue;
                                }
                                $html3 =$html2.  "<td>".$d1."</td>";
                                foreach($key2 as $d2=>$key3)
                                {
                                    
                                    
                                    if(empty($key3))
                                    {
                                        echo $html3."<td>".$d2."</td>".'</tr>';
                                        continue;
                                    }
                                    $html4 =$html3.  "<td>".$d2."</td>";
                                    foreach($key3 as $d3=>$key4)
                                    {
                                        echo $html4. "<td>".$d3."</td></tr>";
                                    }
                                }
                            }
                        }
                        
                        echo "</tr>";

                    }?>
                </table><?php die;    
            
               
        }
    }
    


}
?>