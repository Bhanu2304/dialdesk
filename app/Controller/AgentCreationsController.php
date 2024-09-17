<?php
class AgentCreationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('AgentMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_agent','delete_agents','updateagent','view_summary','export_summary','export_agent');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
	
    public function index() {
        $this->layout='user';
        if($this->request->is("POST")){
             //print_r($this->request->data);die;
            $name=$this->request->data['AgentCreations']['Agentid'];		
            $password=$this->request->data['AgentCreations']['Agentpassword'];
            $displayname=$this->request->data['AgentCreations']['displayname'];
            $processname=$this->request->data['AgentCreations']['processname'];
            $dateofjoining=$this->request->data['AgentCreations']['dateofjoining'];
            $workmode=$this->request->data['AgentCreations']['workmode'];
            $address=$this->request->data['AgentCreations']['address'];
            $category=$this->request->data['AgentCreations']['category'];
            $email_id=$this->request->data['AgentCreations']['email_id'];
            $dateofjoining_arr = explode("-",$dateofjoining);
            $dateofjoining_rev = array_reverse($dateofjoining_arr);
            $dateofjoining = implode("-",$dateofjoining_rev);
            $user_id=$this->Session->read('admin_id');

            if($this->AgentMaster->find('first',array('fields'=>array('id'),'conditions'=>array('username'=>$name,'status'=>'A')))){
                $this->Session->setFlash("Login Id already exists.");
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->AgentMaster->save(array('created_by'=>$user_id,'displayname'=>$displayname,'username'=>$name,'password'=>$password,'processname'=>$processname,'dateofjoining'=>$dateofjoining,'workmode'=>$workmode,'category'=>$category,'email'=>$email_id, 'status'=>'A'));
                $this->Session->setFlash("New Login ID Created");
               $this->redirect(array('action' => 'view_agent'));
            }
        }	
    }
    
    public function updateagent() {
        $this->layout='user';
        if($this->request->is("POST")){
            $id             =   $this->request->data['id'];
            $name           =   $this->request->data['AgentCreations']['username'];		
            $password       =   $this->request->data['AgentCreations']['password'];
            $displayname    =   $this->request->data['AgentCreations']['displayname'];
            $processname    =   $this->request->data['AgentCreations']['processname'];
            $dateofjoining  =   $this->request->data['AgentCreations']['dateofjoining'];
            $dateofleaving  =   $this->request->data['AgentCreations']['dateofleaving'];
            $workmode       =   $this->request->data['AgentCreations']['workmode'];
            $address        =   $this->request->data['AgentCreations']['address'];
            $category       =   $this->request->data['AgentCreations']['category'];
            $email       =   $this->request->data['AgentCreations']['email'];
            $dateofjoining_arr = explode("-",$dateofjoining);
            $dateofjoining_rev = array_reverse($dateofjoining_arr);
            $dateofjoining = implode("-",$dateofjoining_rev);

            $dateofleaving_arr = explode("-",$dateofleaving);
            $dateofleaving_rev = array_reverse($dateofleaving_arr);
            $dateofleaving = implode("-",$dateofleaving_rev);
            
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }

            $update_date    =   date('Y-m-d H:i:s');

            $data=array(
                'displayname'=>"'".$displayname."'",
                //'password'=>"'".$password."'",
                'update_user'=>"'".$update_user."'",
                'update_date'=>"'".$update_date."'",
               
                'displayname'=>"'".$displayname."'",
                'processname'=>"'".$processname."'",
                'dateofjoining'=>"'".$dateofjoining."'",
                'workmode'=>"'".$workmode."'",
                'address'=>"'".$address."'",
                'category'=>"'".$category."'",
                'email'=>"'".$email."'",
                );
                if(!empty($password))
                {
                    $data['password'] = "'".$password."'";
                }
                if(!empty($dateofleaving))
                {
                    $data['dateofleaving']= "'".$dateofleaving."'";
                }
            
            $this->AgentMaster->updateAll($data,array('id'=>$id));die;
        }	
    }

    public function view_agent(){
        $this->layout='user';
        
            $processname =$this->AgentMaster->find('list',array('fields'=>array("processname","processname"),'conditions'=>array('status'=>'A')));
            // print_r($processname);die;
            // array_unique($processname);
            $processname = array('All'=>'All')+$processname;

            $this->set('client',$processname); 
            if($this->request->is("POST")){

                $search     =   $this->request->data['Agent'];
                $processname	=  $search['processname'];
                //print_r($search);die;

                // $this->set('data',$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A','processname'=>$processname))));
                if($processname== 'All')
                {
                    $this->set('data',$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A'))));
                }
                else
                {
                    $this->set('data',$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A','processname'=>$processname))));
                }

                $this->set('processname',$processname);
                

            }


    }

    public function view_agent_old(){
        $this->layout='user';
        $this->set('data',$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A'))));
    }
	
    public function delete_agents(){
        
        if($this->Session->read('role') =="client"){
            $update_user=$this->Session->read('email');
        }
        else if($this->Session->read('role') =="agent"){
            $update_user=$this->Session->read('agent_username');
        }
        if($this->Session->read('role') =="admin"){
            $update_user=$this->Session->read('admin_name');
        }

        $update_date=date('Y-m-d H:i:s');
        
        $status="I";
        $data=array('status'=>"'".$status."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
        $this->AgentMaster->updateAll($data,array('id'=>$this->request->query['id']));
        $this->redirect(array('action' => 'view_agent'));
    }
		
    public function view_summary() {
        $this->layout='user';
       

        $qry= "SELECT * FROM agent_master where category is not null and  status='A' and processname != 'Temporary'";
        $Agent_data = $this->AgentMaster->query($qry);
        //print_r($Agent_data);die;
        $dataArr  = array();
        $category = array();
        $process  = array();
        //print_r($tArr);
        foreach($Agent_data as $data)
        {

            $category[] = $data['agent_master']['category'];
            $process[] = $data['agent_master']['processname'];
            $dataArr[$data['agent_master']['category']][$data['agent_master']['processname']] += 1;

        }

        $category = array_unique($category);
        $process = array_unique($process);
        sort($category);
        $this->set('process',$process);
        $this->set('category',$category);
        $this->set('dataArr',$dataArr);
        
		
    }
    public function export_summary()
    {
        if($this->request->is("POST")){
            
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=Agent_summary_reports.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
           
            
            $search=$this->request->data['agent_master'];
            $qry = "SELECT * FROM agent_master where category is not null  and status='A' order by category,processname,displayname";
            //$data=$this->AgentMaster->query($qry);
            $Agent_data = $this->AgentMaster->query($qry);

            $dataArr  = array();
            $category = array();
            $process  = array();
            //print_r($tArr);
            foreach($Agent_data as $data)
            {
    
                $category[] = $data['agent_master']['category'];
                $process[] = $data['agent_master']['processname'];
                $dataArr[$data['agent_master']['category']][$data['agent_master']['processname']] += 1;
    
            }
    
            $category = array_unique($category);
            $process = array_unique($process);
            sort($category);
            
            
            ?>
        
            <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered" >
            
        <?php   echo  $currDateTime = date("d-m-y h:i:s "); ?>
        
            <tr>
                    <th>Category</th>
                        <?php  foreach($process as $pro)
                        {
                        echo "<th>".$pro."</th>";
                        }
                          ?>
                    <th>Total</th>
                 
                </tr> 

                <?php  $h_total = array();
                foreach($category as $cat)
                {
    
    
                    echo "<tr>";
                    echo "<th>".$cat."</th>";
                    $grand_total = 0; 
                    
                    foreach($process as $pro)
                    {
                    
                    echo "<td>".$dataArr[$cat][$pro]."</td>";
                    $grand_total += $dataArr[$cat][$pro];
                    $h_total[$pro] += $dataArr[$cat][$pro];
                    }
                    echo "<th>".$grand_total."</th>";
                     
                    echo "</tr>";

                }?>
                <tr>
                    <th>Total</th>
                    <?php  $grand_total = 0;
                    foreach($process as $pro)
                    {
                        echo '<td>'.$h_total[$pro].'</td>';
                        $grand_total +=$h_total[$pro];
                    }
                    echo "<th>".$grand_total."</th>";
                    ?>
                </tr>
                </table>
                <br><br><br>
                <b><caption>Agent Details</caption></b>
                <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered" >
                <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Category</th>
                            <th>Process Name</th>
                            <th>Name of Agent</th>
                            <th>Agent Id</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($Agent_data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['agent_master']['category'];?></td>
                            <td><?php echo $row['agent_master']['processname'];?></td>
                            <td><?php echo $row['agent_master']['displayname'];?></td>
                            <td><?php echo  $row['agent_master']['username'];?></td>
                        </tr>

                    <?php }?>

                    </tbody>
        
        
	
                </table>
                <?php
                } ?>
                
                
                
               <?php  
                die;   
       
     }

     public function export_agent()
     {
         if($this->request->is("POST")){
 
             header("Content-Type: application/vnd.ms-excel; name='excel'");
             header("Content-type: application/octet-stream");
             header("Content-Disposition: attachment; filename=Agents_data.xls");
             header("Pragma: no-cache");
             header("Expires: 0");
 
             $search     =   $this->request->data['Agent'];
             $processname	=  $search['processname'];
             //print_r($search);die;
 
             // $this->set('data',$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A','processname'=>$processname))));
             if($processname== 'All')
             {
                 $data = $this->AgentMaster->find('all',array('conditions'=>array('status'=>'A')));
             }
             else
             {
               $data =$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A','processname'=>$processname)));
             }
          
 
             ?>
             <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                 <thead>
                         <tr>
                             <th>SrNo</th>
                             <th>Name of Agent</th>
                             <th>Login Id</th>
                             <th>Password</th>
                             <th>Process Name</th>
                             <th>Date of Joining</th>
                             <th>Category</th>
                             <th>Work Mode</th>
                             <th>Address</th>
                             <th>Date of Leaving </th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                         $i=1;
                         foreach($data as $row){?>
                         <tr>
                             <td><?php echo $i++;?></td>
                             
                             <td><?php echo $row['AgentMaster']['displayname'];?></td>
                             <td><?php echo $row['AgentMaster']['username'];?></td>
                             <td><?php echo $row['AgentMaster']['password2'];?></td>
                             <td><?php echo $row['AgentMaster']['processname'];?></td>
                             <td><?php $dateofjoining_arr = explode("-",$row['AgentMaster']['dateofjoining']);
                                         $dateofjoining_rev = array_reverse($dateofjoining_arr);
                                         $dateofjoining = implode("-",$dateofjoining_rev); 
                                          echo $dateofjoining;?></td>
                             <td><?php echo $row['AgentMaster']['category'];?></td>
                             <td><?php echo $row['AgentMaster']['workmode'];?></td>
                             <td><?php echo $row['AgentMaster']['address'];?></td>
                             <td><?php $dateofleaving_arr = explode("-",$row['AgentMaster']['dateofleaving']);
                                         $dateofleaving_rev = array_reverse($dateofleaving_arr);
                                         $dateofleaving = implode("-",$dateofleaving_rev); 
                                          echo $dateofleaving;?></td>
                             <!-- <td> <a href="<?php //echo $this->webroot;?>AgentCreations/delete_agents?id=<?php //echo $row['AgentMaster']['id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                 <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                 </a> 
                               
      
                             
                                 
                             </td>   -->
                         </tr>
 
                     <?php }?>
 
                     </tbody>
         
         
     
                 </table> <?php
 
             
 
         }die;
 
     }

     
}

?>