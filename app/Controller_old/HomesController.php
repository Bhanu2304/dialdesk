<?php
class HomesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ClientCategory','UploadExistingBase','RegistrationMaster','vicidialCloserLog','vicidialUserLog','CallMaster','CallRecord','CampaignName','CallMasterOut','ClientReportMaster','AbandCallMaster');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow();
                /*
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'index'));
		}*/
    }
    //$this->Session->check("companyid")
    //$this->Session->write("role","admin");
    
	public function index() {
            $this->layout='user';
               
            //$Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$this->Session->read('companyid'))));
            //$this->set('Campaign',$Campaign);
            
            if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
                $this->set('client',$client);
               
                if($this->request->is('Post')){
                    $data=$this->request->data['Homes'];
                    if(!empty($data)){
                        $status =$this->RegistrationMaster->find('first',array('fields'=>array("status","campaignid"),'conditions'=>array('company_id'=>$data['clientID'])));
                        $this->Session->write("companyid",$data['clientID']);
                        $this->Session->write("clientstatus",$status['RegistrationMaster']['status']);
                        $this->Session->write("campaignid",$status['RegistrationMaster']['campaignid']);
                      
                    }
                    /*
                    else{
                        $this->Session->delete('companyid');
                        $this->Session->delete('clientstatus');
                        $this->Session->delete('campaignid');  
                    }*/
                }
            }
            
              
                
		$ClientId = $this->Session->read('companyid');
		$start = date('Y-m-d');
                
                $callType=$this->request->data['callType'];
                //$campaignid=$this->request->data['campaignid'];
                $fd=$this->request->data['fdate'];
                $ld=$this->request->data['ldate'];
		if (isset($this->request->data['view_type'])){
			$view_type=$this->request->data['view_type'];
			if($view_type=="Today"){
				$conditions = "and date(CallDate) = curdate()";
                                $Abandconditions = "and date(AbandDate) = curdate()";
                                $conditions1 = "and date(cm.CallDate) = curdate()";
				$view_date="date(t2.call_date) = curdate()";
                                $condArr['date(CallDate)']=date('Y-m-d');
			}
			if($view_type=="Yesterday"){
				$yesterday=date('Y-m-d', strtotime('-1 day'));
                                $condArr['date(CallDate)']=$yesterday;
				$conditions = "and date(CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                                $Abandconditions = "and date(AbandDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
                                $conditions1 = "and date(cm.CallDate) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
				$view_date="date(t2.call_date) = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
			}
			if($view_type=="Weekly"){
        		$end = date('Y-m-d', strtotime('-6 day'));
                                $condArr['date(CallDate) <=']=date('Y-m-d');
                                $condArr['date(CallDate) >=']=$end;
				$conditions = "and date(CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                                $Abandconditions = "and date(AbandDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
                                $conditions1 = "and date(cm.CallDate) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
				$view_date="date(t2.call_date) between SUBDATE(CURDATE(),INTERVAL 6 DAY) and CURDATE()";
			}
			if($view_type=="Monthly"){
				$end = date('Y-m-d', strtotime('-30 day'));
                                $condArr['date(CallDate) <=']=date('Y-m-d');
                                $condArr['date(CallDate) >=']=$end;
                                
				//$conditions = "and date(CallDate) between SUBDATE(CURDATE(),INTERVAL 30 DAY) and CURDATE()";
                                //$conditions1 = "and date(cm.CallDate) between SUBDATE(CURDATE(),INTERVAL 30 DAY) and CURDATE()";
				//$view_date="date(t2.call_date) between SUBDATE(CURDATE(),INTERVAL 30 DAY) and CURDATE()";
                                
                                
                                $conditions ="and MONTH(CallDate) = MONTH(CURDATE()) and YEAR(CallDate) = YEAR(CURDATE())";
                                $Abandconditions ="and MONTH(AbandDate) = MONTH(CURDATE()) and YEAR(AbandDate) = YEAR(CURDATE())";
                                $conditions1 ="and MONTH(cm.CallDate) = MONTH(CURDATE()) and YEAR(cm.CallDate) = YEAR(CURDATE())";
                                $view_date ="MONTH(t2.call_date) = MONTH(CURDATE()) and YEAR(t2.call_date) = YEAR(CURDATE())";
                                
                                
                                 
                                
			}
			if($view_type=="Custom"){
				$fdate = date('Y-m-d', strtotime($this->request->data['fdate']));
				$ldate = date('Y-m-d', strtotime($this->request->data['ldate']));
                                
                                $condArr['date(CallDate) >=']=$fdate;
                                $condArr['date(CallDate) <=']=$ldate;
                                
				$conditions = "and date(CallDate) between '$fdate' and '$ldate'";
                                $Abandconditions = "and date(AbandDate) between '$fdate' and '$ldate'";
                                $conditions1 = "and date(cm.CallDate) between '$fdate' and '$ldate'";
                                $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
                                        /*
					if($this->request->data['fdate'] !="" && $this->request->data['ldate'] !=""){
					$view_date=$fdate."&nbsp;-&nbsp;".$ldate;
					}
					else{
						$view_date="";
					}*/
			}
                        
                        if($callType ==="outbounds"){
                            $this->view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld);
                            
                        }else{
                            $this->view_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld,$Abandconditions);
                        }
                        
		}
		else{
			$conditions = "and date(CallDate) = curdate()";
                        $conditions1 = "and date(cm.CallDate) = curdate()";
                        $curDate=date('Y-m-d');
                        $view_date = "date(t2.call_date)='$curDate'";
                        $condArr['date(CallDate)']=$curDate;
                       
                        if($callType ==="outbounds"){
                            $this->view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType); 
                        }else{
                            $this->view_deshbord($conditions1,$conditions,$view_type='Today',$view_date,$condArr,$callType);
                        }
                        
		}
                
		
	}
	
	public function view_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld,$Abandconditions){
            
		$ClientId = $this->Session->read('companyid');
                $this->set('callType',$callType);
                $this->set('fd',$fd);
                $this->set('ld',$ld);
                $this->set('viewType',$view_type);

                $tatqry="SELECT cm.Category1, IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
 IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
  IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
  AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`, IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,
  1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`, 
  IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)
   `openintat`, DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,
   tt.time_hours FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId 
   AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = 
   CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,''))
                 WHERE cm.ClientId='$ClientId' $conditions1 AND cm.CallType !='Upload'  ORDER BY cm.Category1 ASC";
                
                $clmaster=$this->CallMaster->query($tatqry);
               
                
                foreach($clmaster as $row){
                    $key = $row[cm]['Category1'];
                    if(key_exists($key, $newcategory))
                    {
                        $newcategory[$key]['MTD'] +=1;
                        $newcategory[$key]['intat'] +=$row[0]['intat'];
                        $newcategory[$key]['outtat'] +=$row[0]['outtat'];
                        $newcategory[$key]['openintat'] +=$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] +=$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                       
                    }
                    else
                    {
                        $newcategory[$key]['MTD'] =1;
                        $newcategory[$key]['intat'] =$row[0]['intat'];
                        $newcategory[$key]['outtat'] =$row[0]['outtat'];
                        $newcategory[$key]['openintat'] =$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] =$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                    }
                    
                    $total +=1;
                    //$DataArr[] = $row[CallDate]['CloseLoopDate'];
                 }
                 
                 
                 
                 
                //$html .= "<table border='1'>";
                //$html .= "<tr><th><b>Summary</b></th>";
                //$html .= "<th><b>MTD</b></th>";
                //$html .= "<th><b>%</b></th>";
                
                
                
                $this->set('newcategory',$newcategory);
                $this->set('tatTotal',$total);
                 /*
                $keys = array_keys($newcategory);
                $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                foreach($header as $k1=>$v1){
                    foreach($keys as $k){
                        $html .= "<tr><th><b>Total ".$k.' '.$v1."</b></th>";
                        $html .= "<th><b>".$newcategory[$k][$k1]."</b></th>"; 
                        $html .= "<th><b>".round($newcategory[$k][$k1]*100/$total,2)."%</b></th>";
                        
                      
                        $html .= "</tr>";     
                    }
                }*/
                
                //echo $html; 
                
                //exit;
                
                
                
                //**********************************************************************************************
                
                
		$Category =$this->ClientCategory->find('all',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId)));
			$ecrArr=array();
			foreach($Category as $row)
                        {
                            $data = $this->fetchChildTree($parent =$row['ClientCategory']['id'],$user_tree_array = '');
                            $ecrArr[]=array(
                                'parent'=>array('id'=>$row['ClientCategory']['id'],'name'=>$row['ClientCategory']['ecrName']),
                                'sub'=>$data,
                            );	
			}
                        
                         
                        
			$this->set('ecr',$ecrArr);
			
                       $Campagn  = $this->Session->read('campaignid');
                       
                       
                       
                        if(!empty($Campagn))
                        {
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                        
                        
                   $qry = "SELECT COUNT(*) `Total`,
                    SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
                    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`
                   FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'";
                    
                    
                    $this->set('Category1',$this->CallMaster->query("SELECT Category1,GROUP_CONCAT(if(Category2 is null,Category1,Category2),'@@',countNo)`count`,SUM(countNo) `Total` FROM (SELECT Category1,Category2,COUNT(1)`countNo` FROM call_master
                    where ClientId='$ClientId' $conditions AND CallType !='Upload'  GROUP BY Category1,Category2)AS tab GROUP BY Category1"));
                    
                    
                    
                    $this->set('closeloop',$this->CallMaster->query("SELECT Category1,Category2,COUNT(Category1) `cate1`,CloseLoopCate1,CloseLoopCate2,COUNT(CloseLoopCate1)
                    FROM call_master where ClientId='$ClientId' $conditions AND CallType !='Upload' GROUP BY Category1,Category2,CloseLoopCate1,CloseLoopCate2"));
                    
                    $this->vicidialCloserLog->useDbConfig = 'db2';
                    $dt= $this->vicidialCloserLog->query($qry);

                    $this->set('qry',$conditions);
                    $condArr['ClientId']=$ClientId;
   
                    $this->set('fd',$fd);
                    $this->set('ld',$ld);
                
                    $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$ClientId' $conditions AND TagStatus IS NULL");
                    $this->set('Abandon',$TACC[0][0]['AbandCount']);
                    
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag FROM call_master where ClientId='$ClientId' $conditions AND CallType !='Upload'");
                    $this->set('totalTag',$tc[0][0]['totaltag']);
                    
                    $TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='$ClientId' $conditions AND TagStatus='yes'");
                    $this->set('AbandCallBack',$TACB[0][0]['AbandCallBack']);
             
                    
                    
                    
                    
                    $this->set('Answered',$dt[0][0]['Answered']);
                     
                    
                    
            }
            $this->set('clientid',$ClientId);
	}
        
        
        public function view_outbound_deshbord($conditions1,$conditions,$view_type,$view_date,$condArr,$callType,$fd,$ld){
             
		$ClientId = $this->Session->read('companyid');
                $this->set('callType',$callType);
                $this->set('fd',$fd);
                $this->set('ld',$ld);
                $this->set('viewType',$view_type);
                //$this->set('campaignid',$campaignid);
                
                $tatqry="SELECT cm.Category1,
                IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
                IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
                IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`,

                IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,1,
                IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`,

                IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `openintat`,

                DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,tt.time_hours FROM call_master cm
                INNER JOIN tbl_time tt ON cm.ClientId = tt.ClientId 
                 WHERE cm.ClientId='$ClientId' $conditions1";
                
                $clmaster=$this->CallMaster->query($tatqry);
               
                
                foreach($clmaster as $row){
                    $key = $row[cm]['Category1'];
                    if(key_exists($key, $newcategory))
                    {
                        $newcategory[$key]['MTD'] +=1;
                        $newcategory[$key]['intat'] +=$row[0]['intat'];
                        $newcategory[$key]['outtat'] +=$row[0]['outtat'];
                        $newcategory[$key]['openintat'] +=$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] +=$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                       
                    }
                    else
                    {
                        $newcategory[$key]['MTD'] =1;
                        $newcategory[$key]['intat'] =$row[0]['intat'];
                        $newcategory[$key]['outtat'] =$row[0]['outtat'];
                        $newcategory[$key]['openintat'] =$row[0]['openintat'];
                        $newcategory[$key]['openouttat'] =$row[0]['openouttat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        //$category[$key][$row[CallDate]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                    }
                    
                    $total +=1;
                    //$DataArr[] = $row[CallDate]['CloseLoopDate'];
                 }
                 
                 
                 
                 
                //$html .= "<table border='1'>";
                //$html .= "<tr><th><b>Summary</b></th>";
                //$html .= "<th><b>MTD</b></th>";
                //$html .= "<th><b>%</b></th>";
                
                
                
                $this->set('newcategory',$newcategory);
                 /*
                $keys = array_keys($newcategory);
                $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                foreach($header as $k1=>$v1){
                    foreach($keys as $k){
                        $html .= "<tr><th><b>Total ".$k.' '.$v1."</b></th>";
                        $html .= "<th><b>".$newcategory[$k][$k1]."</b></th>"; 
                        $html .= "<th><b>".round($newcategory[$k][$k1]*100/$total,2)."%</b></th>";
                        
                      
                        $html .= "</tr>";     
                    }
                }*/
                
                //echo $html; 
                
                //exit;
                
                
                
                //**********************************************************************************************
                
                
		$Category =$this->ClientCategory->find('all',array('fields'=>array("id","ecrName"),'conditions'=>array('Label'=>1,'Client'=>$ClientId)));
			$ecrArr=array();
			foreach($Category as $row)
                        {
                            $data = $this->fetchChildTree($parent =$row['ClientCategory']['id'],$user_tree_array = '');
                            $ecrArr[]=array(
                                'parent'=>array('id'=>$row['ClientCategory']['id'],'name'=>$row['ClientCategory']['ecrName']),
                                'sub'=>$data,
                            );	
			}
                        
                         
                        
			$this->set('ecr',$ecrArr);
			
                        $Campagn  = $this->Session->read('campaignid');
                        if(!empty($Campagn)){
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                     $qry = "SELECT COUNT(*) `Total`,
                    SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B') and t2.user !='vdcl' ,1,0)) `Answered`,
                    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'";
                    
                    
                    $this->set('Category1',$this->CallMaster->query("SELECT Category1,GROUP_CONCAT(if(Category2 is null,Category1,Category2),'-',countNo)`count`,SUM(countNo) `Total` FROM (SELECT Category1,Category2,COUNT(1)`countNo` FROM call_master
                    where ClientId='$ClientId' $conditions  GROUP BY Category1,Category2)AS tab GROUP BY Category1"));
                    
                    
                    
                    $this->set('closeloop',$this->CallMaster->query("SELECT Category1,Category2,COUNT(Category1) `cate1`,CloseLoopCate1,CloseLoopCate2,COUNT(CloseLoopCate1)
                    FROM call_master where ClientId='$ClientId' $conditions GROUP BY Category1,Category2,CloseLoopCate1,CloseLoopCate2"));
                    
                    $this->vicidialCloserLog->useDbConfig = 'db2';
                    $dt= $this->vicidialCloserLog->query($qry);
                    
                     
                    
                    $this->set('qry',$conditions);
                    
                    
                    $condArr['ClientId']=$ClientId;
                                        
                    $obTotalTag=count($this->CallMasterOut->find('all',array('fields'=>array('Id'),'conditions'=>$condArr)));
                    $this->set('obTotalTag',$obTotalTag);
                    
                    
                    $this->set('obAnswered','');
                    $this->set('obAbandon','');
                   
                        } 
                        
                $this->set('clientid',$ClientId);
	}

	public function fetchChildTree($parent,$user_tree_array) {
		if (!is_array($user_tree_array))
			$user_tree_array = array();
			$ClientId = $this->Session->read('companyid');
			$query =$this->ClientCategory->find('all',array('fields'=>array("id","ecrName"),'conditions'=>array('parent_id'=>$parent,'Client'=>$ClientId)));
			if (count($query) > 0) {
				foreach($query as $row){
					$id=$row['ClientCategory']['id'];
					$name=$row['ClientCategory']['ecrName'];
					$user_tree_array[] = array("id" => $id, "name" =>$name);
				}
			}
			return $user_tree_array;
	}

public function getTypeCount()
{
    if($this->request->is('Post'))
    {
        $category1 = $this->request->data['Category'];
        $category2 = $this->request->data['Type'];
        $condition = $this->request->data['qry'];
        
        
      
        
        
        $clientId = $this->Session->read('companyid');
        $cat2 = $this->CallMaster->query("SELECT Category2, GROUP_CONCAT(if(Category3 is null or Category3='',Category2,Category3),'@@',countNo)`count`,SUM(countNo) `Total` FROM (SELECT Category2,Category3,COUNT(1)`countNo` FROM call_master
WHERE ClientId='$clientId' AND Category1='$category1' AND Category2='$category2' $condition GROUP BY Category2,Category3)AS tab GROUP BY Category2");
       
        
        $count = 0; $Total = 0;
        foreach($cat2 as $c):
    //$data[$count]['tab'] = $c['ClientCategory']['id'];
    $data[$count]['ecrName'] = str_replace(' ','',trim($c['tab']['Category2'])).rand(1000,10000);
    $data[$count]['count'] = $c['0']['count'];
    $Total += $c['0']['Total'];
    $count++;
    endforeach;
    $this->set('Total',$Total);
    $this->set('data',$data);
    //$this->set('qry',$cat2);
    $this->set('subscenarioname',$category2);
    $this->set('scenarioname',$category1);
    
    
    }
}
		
}
?>