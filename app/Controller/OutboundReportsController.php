<?php
class OutboundReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','CallMasterOut','vicidialUserLog','VicidialListMaster','AbandCallMaster','ObAllocationMaster','ObCampaignDataMaster','vicidialCloserLog');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid"))
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
   public function index(){
        $this->layout='user';
        if($this->request->is("POST")){
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =  $this->request->data['OutboundReports'];
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            //print_r($start_time_start); exit;

         $select = "SELECT * FROM `ob_allocation_name` WHERE ClientId='$clientId'";
        $Obname = $this->ObAllocationMaster->query($select);
        //print_r($Obname);die;
        $data_arr = array();
        $date_arr = array();
        $datetime_arr = array();
        $category_arr = array();
        foreach($Obname as $ob)
        {
            $ob_allo_id = $ob['ob_allocation_name']['id'];

             $select1 = "select *,DATE_FORMAT(CreationDate,'%d-%b-%Y') `date` from `ob_campaign_data` WHERE AllocationId='$ob_allo_id' AND date(CreationDate) BETWEEN '$start_time_start' AND '$start_time_end'";
             $ob_camp_data = $this->ObCampaignDataMaster->query($select1);

           #$select1 = "select COUNT(1),SUM(IF(USER IS NOT NULL,1,0)) overallattempt  from `vicidial_list` WHERE source_id='$ob_allo_id' AND date(entry_date) BETWEEN '$start_time_start' AND '$start_time_end'";

            #$this->VicidialListMaster->useDbConfig = 'db2';
            #$list_master = $this->VicidialListMaster->query($select1);
            //print_r($list_master);
            foreach($ob_camp_data as $ob_data)
            {
                $data_arr['Allocation'] +=1;
                $date_arr[$ob_data['0']['date']]['Allocation'] +=1;
                $datetime_arr[$ob_data['0']['date']] = $ob_data['0']['date'];
       
                
            }

            //print_r($lists);
          
            $select_tag = "select *,DATE_FORMAT(CallDate,'%d-%b-%Y') `date` from `call_master_out` WHERE AllocationId='$ob_allo_id' AND date(CallDate) BETWEEN '$start_time_start' AND '$start_time_end'";
            $ob_tag_data = $this->CallMasterOut->query($select_tag);

            //print_r($ob_tag_data[0][0]);
            foreach($ob_tag_data as $tag_data)
            {
                //print_r($tag_data[0]['date']);
                $data_arr['OAttempt'] +=1;
                
                $data_arr['UAttempt'][$tag_data['call_master_out']['DataId']] =1;
                $data_arr['MAttempt'][$tag_data['call_master_out']['DataId']] +=1;
                if($tag_data['call_master_out']['Category1']=='Connected')
                {
                    $data_arr['UContact'][$tag_data['call_master_out']['DataId']] =1;
                    $data_arr['MContact'][$tag_data['call_master_out']['DataId']] +=1;
                }
                $data_arr[$tag_data['call_master_out']['Category2']][$tag_data['call_master_out']['Category3']] +=1;



                $date_arr[$tag_data[0]['date']]['OAttempt'] +=1;
                $date_arr[$tag_data[0]['date']]['UAttempt'][$tag_data['call_master_out']['DataId']] =1;
                $date_arr[$tag_data[0]['date']]['MAttempt'][$tag_data['call_master_out']['DataId']] +=1;
                if($tag_data['call_master_out']['Category1']=='Connected')
                {
                    $date_arr[$tag_data[0]['date']]['UContact'][$tag_data['call_master_out']['DataId']] =1;
                    $date_arr[$tag_data[0]['date']]['MContact'][$tag_data['call_master_out']['DataId']] +=1;
                }

                $date_arr[$tag_data[0]['date']][$tag_data['call_master_out']['Category2']][$tag_data['call_master_out']['Category3']] +=1;
                $datetime_arr[$ob_data['0']['date']] = $ob_data['0']['date'];
                $category_arr[$tag_data['call_master_out']['Category2']][$tag_data['call_master_out']['Category3']] = $tag_data['call_master_out']['Category3'];

                //print_r($lists);
            }

        }
         #print_r($data_arr); die;
         
        
        $this->set('data',$data_arr); 
        $this->set('date_arr',$date_arr); 
        $this->set('datetime_arr',$datetime_arr); 
        $this->set('category_arr',$category_arr); 
        }
        
    }
    public function export_reports()
    {
        $this->layout='user';
        if($this->request->is("POST")){


        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=outbound_report.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');

        $search     =  $this->request->data['OutboundReports'];
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            //print_r($start_time_start); exit;

        $select = "SELECT * FROM `ob_allocation_name` WHERE ClientId='$clientId'";
        $Obname = $this->ObAllocationMaster->query($select);
        //print_r($Obname);die;
        $data = array();
        $date_arr = array();
        $datetime_arr = array();
        $category_arr = array();
        foreach($Obname as $ob)
        {
            $ob_allo_id = $ob['ob_allocation_name']['id'];

             $select1 = "select *,DATE_FORMAT(CreationDate,'%d-%b-%Y') `date` from `ob_campaign_data` WHERE AllocationId='$ob_allo_id' AND date(CreationDate) BETWEEN '$start_time_start' AND '$start_time_end'";
             $ob_camp_data = $this->ObCampaignDataMaster->query($select1);

           #$select1 = "select COUNT(1),SUM(IF(USER IS NOT NULL,1,0)) overallattempt  from `vicidial_list` WHERE source_id='$ob_allo_id' AND date(entry_date) BETWEEN '$start_time_start' AND '$start_time_end'";

            #$this->VicidialListMaster->useDbConfig = 'db2';
            #$list_master = $this->VicidialListMaster->query($select1);
            //print_r($list_master);
            foreach($ob_camp_data as $ob_data)
            {
                $data['Allocation'] +=1;
                $date_arr[$ob_data['0']['date']]['Allocation'] +=1;
                $datetime_arr[$ob_data['0']['date']] = $ob_data['0']['date'];
       
                
            }

            //print_r($lists);
          
           $select_tag = "select *,DATE_FORMAT(CallDate,'%d-%b-%Y') `date` from `call_master_out` WHERE AllocationId='$ob_allo_id' AND date(CallDate) BETWEEN '$start_time_start' AND '$start_time_end'";
            $ob_tag_data = $this->CallMasterOut->query($select_tag);

            //print_r($ob_tag_data[0][0]);
            foreach($ob_tag_data as $tag_data)
            {
                //print_r($tag_data[0]['date']);
                $data['OAttempt'] +=1;
                
                $data['UAttempt'][$tag_data['call_master_out']['DataId']] =1;
                $data['MAttempt'][$tag_data['call_master_out']['DataId']] +=1;
                if($tag_data['call_master_out']['Category1']=='Connected')
                {
                    $data['UContact'][$tag_data['call_master_out']['DataId']] =1;
                    $data['MContact'][$tag_data['call_master_out']['DataId']] +=1;
                }
                $data[$tag_data['call_master_out']['Category2']][$tag_data['call_master_out']['Category3']] +=1;



                $date_arr[$tag_data[0]['date']]['OAttempt'] +=1;
                $date_arr[$tag_data[0]['date']]['UAttempt'][$tag_data['call_master_out']['DataId']] =1;
                $date_arr[$tag_data[0]['date']]['MAttempt'][$tag_data['call_master_out']['DataId']] +=1;
                if($tag_data['call_master_out']['Category1']=='Connected')
                {
                    $date_arr[$tag_data[0]['date']]['UContact'][$tag_data['call_master_out']['DataId']] =1;
                    $date_arr[$tag_data[0]['date']]['MContact'][$tag_data['call_master_out']['DataId']] +=1;
                }

                $date_arr[$tag_data[0]['date']][$tag_data['call_master_out']['Category2']][$tag_data['call_master_out']['Category3']] +=1;
                $datetime_arr[$ob_data['0']['date']] = $ob_data['0']['date'];
                $category_arr[$tag_data['call_master_out']['Category2']][$tag_data['call_master_out']['Category3']] = $tag_data['call_master_out']['Category3'];

                //print_r($lists);
            }
        }

            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                <tr>
                <th colspan="2">Portfolio Name</th>    
                    <th>MTD</th>  
                <?php foreach($datetime_arr as $key=>$value){  ?>
                    
                  <th><?php echo $key; ?></th>
                <?php } ?>
                </tr>
                <tr>
                <th colspan="2">Allocation</th>    
                <th><?php echo $data['Allocation'];?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo $date_arr[$time]['Allocation'];?></th>
                <?php } ?>    
                </tr>
                <tr>
                <th colspan="2">Overall Attempt</th>
                <th><?php echo $data['OAttempt'];?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo $date_arr[$time]['OAttempt'];?></th>
                <?php } ?>    
                </tr>
                <tr>
                <th colspan="2">Unique Attempt</th>  
                <th><?php echo count($data['UAttempt']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo count($date_arr[$time]['UAttempt']);?></th>
                <?php } ?>    
                </tr>
                <tr>
                <th colspan="2">Multiple </th>  
                <th><?php echo array_sum($data['MAttempt']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo array_sum($date_arr[$time]['MAttempt']);?></th>
                <?php } ?>    
                </tr>
                               
                <tr>
                <th colspan="2">Contactablity ( Unique) </th>  
                <th><?php echo count($data['UContact']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo count($date_arr[$time]['UContact']);?></th>
                <?php } ?>    
                </tr>  
                
                <tr>
                <th colspan="2">Contactablity ( overall) </th>  
                <th><?php echo array_sum($data['MContact']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo array_sum($date_arr[$time]['MContact']);?></th>
                <?php } ?>    
                </tr>  
                 
                
                <?php  $total_nc =array();
                $cat_arr = array();
                        foreach($category_arr as $cat=>$subcat_arr)
                        {
                            #$subcat_arr = array_unique($subcat_arr);
                            $cat_arr[$cat] = $cat;
                            if(!empty($subcat_arr))
                            {
                                $count = count($subcat_arr);
                                $rowspan = 'rowspan="'.$count.'"';
                                $cat_raw =  "<th $rowspan>".$cat.'</th>';
                                foreach($subcat_arr as $sub)
                                {
                                    echo '<tr>';
                                    
                                    
                                    echo $cat_raw;
                                    echo '<th>'.$sub.'</th>';
                                    echo '<th>'.$data[$cat][$sub].'</th>';
                                    $total_nc[$cat] +=  $data[$time][$cat][$sub];
                                    foreach($datetime_arr as $time) { 
                                      echo  '<th>'.$date_arr[$time][$cat][$sub].'</th>';
                                      $total_nc[$time][$cat] +=  $date_arr[$time][$cat][$sub];
                                     } 


                                     $cat_raw = "";
                                    echo '</tr>';                                    
                                }
                            }
                            else
                            {
                                echo '<tr>';
                                    
                                    
                                    echo "<th $rowspan>".$cat.'</th>';
                                    echo '<th>'.$sub.'</th>';
                                    echo '<th>'.$data[$cat][''].'</th>';
                                    $total_nc[$cat] +=  $data[$time][$cat][''];
                                    foreach($datetime_arr as $time) { 
                                      echo  '<th>'.$date_arr[$time][$cat][''].'</th>';
                                      $total_nc[$time][$cat] +=  $date_arr[$time][$cat][''];
                                     }                                     
                                    echo '</tr>';
                            }
                        }


                        
                            echo '<tr>';
                            echo '<th>Total NC</th>';
                            echo '<th></th>';
                            echo '<th>'.$total_nc['Non- workable NC'].'</th>';
                            foreach($datetime_arr as $time) { 
                                echo  '<th>'.$total_nc[$time]['Non- workable NC'].'</th>';
                               }   
                            echo '</tr>';
                        

                        
                            echo '<tr>';
                            echo '<th>Sucess</th>';
                            echo '<th>Sales / Interested / Confimation /paid</th>';
                            echo '<th>'.$total_nc['Sucess'].'</th>';
                            foreach($datetime_arr as $time) { 
                                echo  '<th>'.$total_nc[$time]['Sucess'].'</th>';
                               }   
                            echo '</tr>';
                        
                        
                            echo '<tr>';
                            echo '<th colspan="2">Success (%) on Base Allocation</th>';                            
                            echo '<th>'.(($total_nc['Sucess']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['Sucess']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';
                        
                        
                            echo '<tr>';
                            echo '<th colspan="2">Success (%) on contactable Allocation</th>';                            
                            echo '<th>'.(($total_nc['Sucess']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['Sucess']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';

                            echo '<tr>';
                            echo '<th colspan="2">WIP (%) on Base</th>';                            
                            echo '<th>'.(($total_nc['WIP']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['WIP']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';

                            echo '<tr>';
                            echo '<th colspan="2">Portfolio Failure (%)</th>';                            
                            echo '<th>'.(($total_nc['Portfolio Failure']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['Portfolio Failure']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';

                ?>
                        
                </table> <?php die;
            


      }

    }

    public function agent_apr_new()
    {
        $this->layout='user';
      
    }

    public function agent_apr_new_excel()
    {
        $this->layout='user';    
       
        if($this->request->is("POST")){

            $search   =  $this->request->data['AbandonReports'];
            $firstDay = $search['startdate']." 00:00:00";
            $lastDay   = $search['enddate']." 23:59:59";
            $qry="select date(event_time) event_time, vl.user,vu.full_name,sum(if(lead_id>0 and status is not null,1,0)) calls,sec_to_time(sum(wait_sec+talk_sec+dispo_sec+pause_sec)) login_time,sec_to_time(sum(if(wait_sec>5000,0,wait_sec))) wait_sec,sec_to_time(sum(talk_sec)) talk_sec,sec_to_time(sum(dispo_sec)) dispo_sec,sec_to_time(sum(if(pause_sec>5000,0,pause_sec))) pause_sec,lead_id,status,sec_to_time(sum(dead_sec)) dead_sec from vicidial_agent_log vl join vicidial_users vu on vl.user=vu.user where event_time <= '$lastDay' and event_time >= '$firstDay' and campaign_id IN('Dialdesk') and vl.user_group IN('ADMIN','Agarwal_Pharma','AppleProcess','Boost_M','Dialdesk','DU_Digital','Exicom_Inbond','FB','Jaipur','Naresh','OBD','RupeeRedee','Sales') group by user,date(event_time)";

            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt = $this->vicidialCloserLog->query($qry);
            //print_r($dt); die;
            $this->set('data',$dt);   
        }
    }

}
?>