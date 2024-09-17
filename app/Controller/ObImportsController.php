<?php
	class ObImportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('RegistrationMaster','ObFieldValue','CampaignName','TmpObData','Obup','ObAllocationMaster','ObCampaignDataMaster','ListMaster','VicidialListMaster','VicidialLists');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'index',
            'outboundhome',
			'add',
			'download_campaign',
                        'delete_allocation',
                        'get_campaign',
			'download');
    }
	
	public function index() {
            //ini_set('max_execution_time', 0);
           
            
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');			
		$Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A')));
		$this->set('Campaign',$Campaign);
        #$dialer_connection = 4;
        #DialerConnectionPage
        $result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
        $dialer_connection = $result['RegistrationMaster']['DialerConnectionPage'];
    
        $campaignIds = array_values($Campaign); 
        #print_r($campaignIds);die;
        

        if($dialer_connection == 5)
        {
            #$dialer_connection = 2;
            $viewListId = $this->ListMaster->find('list',array('fields'=>array("list_id","list_id"),'conditions'=>array('client_id'=>$ClientId)));
            $this->set('viewListId',$viewListId);
        }else{
            
            #$this->VicidialLists->useDbConfig = 'db2';
            $this->VicidialLists->useDbConfig = 'db'.$dialer_connection;
            $viewListId = $this->VicidialLists->find('list',array('fields'=>array("list_id","list_id"),'conditions'=>array('campaign_id'=>$campaignIds)));
            #print_r($viewListId);die;
            $this->set('viewListId',$viewListId);

        }
        

                /* View Allocation */
                
                
                $allocArr = $this->ObAllocationMaster->find('all',array('conditions'=>array('ClientId' => $this->Session->read('companyid'),'AllocationStatus'=>'A')));
                
                $allocationData=array();
		foreach($allocArr as $row){
                    $allocationData[]=array(
                        'id'=>$row['ObAllocationMaster']['id'],
                        'cmapname'=>$this->get_campaign($ClientId,$row['ObAllocationMaster']['CampaignId']),
                        'allocname'=>$row['ObAllocationMaster']['AllocationName'],
                        'alloctype'=>$row['ObAllocationMaster']['upload_type'],
                        'createdate'=>$row['ObAllocationMaster']['CreateDate']
                    );
		}
                 
                
                //print_r($allocationData);die;
                
                $this->set('viewAlloc',$allocationData);
                

		if($this->request->is("POST") && !empty($this->request->data)){
                    
                        $uploadType=$this->request->data['ObImports']['uploadType'];
                        $listid=$this->request->data['ObImports']['listid'];
			$id=$this->request->data['ObImports']['CampaignName'];
			$alocationName=addslashes(trim($this->request->data['ObImports']['AllocationName']));
			$data['ObCampaignDataMaster']['CreationDate']= date('Y-m-d H:i:s');
			$data['ObCampaignDataMaster']['ClientId']= $ClientId;
			
			$existAlocation=array('ClientId'=>$ClientId,'CampaignId'=>$id,'AllocationName'=>$alocationName);
			if($this->ObAllocationMaster->find('first',array('fields'=>array('id'),'conditions'=>$existAlocation))){
                            $this->Session->setFlash("Already Exists at Given Allocation Name");
                            $this->redirect(array('action' => 'index'));
			}
			
			$csv_file = $this->request->data['ObImports']['File']['tmp_name'];
			$FileTye = $this->request->data['ObImports']['File']['type'];
			$info = explode(".",$this->request->data['ObImports']['File']['name']);
		
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					$cntField=$this->count_field($ClientId,$id);
					
					if(count($filedata) != $cntField){
						$this->Session->setFlash("Does not Match This Campaign Formate.");
						$this->redirect(array('action' => 'index'));
					}
					
					$allocation=array('ClientId'=>$ClientId,'CampaignId'=>$id,'AllocationName'=>$alocationName,'CreateDate'=>date('Y-m-d H:i:s'),'TotalCount'=>count($filedata),'list_id'=>$listid,'upload_type'=>$uploadType);
                                        
                    //if($uploadType ==="pd"){$allocation['list_id']=$listid;}
                                        
					$this->ObAllocationMaster->save($allocation);
					$allocid=$this->ObAllocationMaster->getLastInsertId();
                                       
					$data['ObCampaignDataMaster']['AllocationId']= $allocid;
					
					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
									
						for ($c=0; $c < count($filedata); $c++) {
							$n=$c+1;
							$field="Field$n";
							$data['ObCampaignDataMaster'][$field]=$filedata[$c];
						}
                                               
						$this->ObCampaignDataMaster->saveAll($data);
                                                
                        if($uploadType ==="pd"){                 
                            $list_value="('".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','NEW','{$listid}','$allocid','-4.00','N','1','{$data['ObCampaignDataMaster']['Field1']}'".")";
                            $list_impt_qry="INSERT INTO asterisk.vicidial_list (entry_date,modify_date,`status`,list_id,source_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number) VALUES $list_value";                        
                            #$this->VicidialListMaster->useDbConfig = 'db2';
                            $this->VicidialListMaster->useDbConfig = 'db'.$dialer_connection;
                            $this->VicidialListMaster->query($list_impt_qry);
                        }
					 }
					 
				}

				$this->Session->setFlash('<span style="color:green;">Upload SuccessFully</span>');
				$this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV formate.');
			}
		}	
	}
        
    public function lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner){
    global $link;

    $postalgmt_found=0;
    if ( (eregi("POSTAL",$postalgmt)) && (strlen($postal_code)>4) )
        {
        if (preg_match('/^1$/', $phone_code))
            {
            $stmt="select postal_code,state,GMT_offset,DST,DST_range,country,country_code from asterisk.vicidial_postal_codes where country_code='$phone_code' and postal_code LIKE \"$postal_code%\";";
            $rslt=mysql_query($stmt);
            $pc_recs = mysql_num_rows($rslt);
            if ($pc_recs > 0)
                {
                $row=mysql_fetch_row($rslt);
                $gmt_offset =    $row[2];     $gmt_offset = eregi_replace("\+","",$gmt_offset);
                $dst =            $row[3];
                $dst_range =    $row[4];
                $PC_processed++;
                $postalgmt_found++;
                $post++;
                }
            }
        }
    if ( ($postalgmt=="TZCODE") && (strlen($owner)>1) )
        {
        $dst_range='';
        $dst='N';
        $gmt_offset=0;

        $stmt="select GMT_offset from asterisk.vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' limit 1;";
        $rslt=mysql_query($stmt);
        $pc_recs = mysql_num_rows($rslt);
        if ($pc_recs > 0)
            {
            $row=mysql_fetch_row($rslt);
            $gmt_offset =    $row[0];     $gmt_offset = eregi_replace("\+","",$gmt_offset);
            $PC_processed++;
            $postalgmt_found++;
            $post++;
            }

        $stmt = "select distinct DST_range from asterisk.vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' order by DST_range desc limit 1;";
        $rslt=mysql_query($stmt);
        $pc_recs = mysql_num_rows($rslt);
        if ($pc_recs > 0)
            {
            $row=mysql_fetch_row($rslt);
            $dst_range =    $row[0];
            if (strlen($dst_range)>2) {$dst = 'Y';}
            }
        }

    if ($postalgmt_found < 1)
        {
        $PC_processed=0;
        ### UNITED STATES ###
        if ($phone_code =='1')
            {
            $stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from asterisk.vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
            $rslt=mysql_query($stmt);
            $pc_recs = mysql_num_rows($rslt);
            if ($pc_recs > 0)
                {
                $row=mysql_fetch_row($rslt);
                $gmt_offset =    $row[4];     $gmt_offset = eregi_replace("\+","",$gmt_offset);
                $dst =            $row[5];
                $dst_range =    $row[6];
                $PC_processed++;
                }
            }
        ### MEXICO ###
        if ($phone_code =='52')
            {
            $stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from asterisk.vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
            $rslt=mysql_query($stmt);
            $pc_recs = mysql_num_rows($rslt);
            if ($pc_recs > 0)
                {
                $row=mysql_fetch_row($rslt);
                $gmt_offset =    $row[4];     $gmt_offset = eregi_replace("\+","",$gmt_offset);
                $dst =            $row[5];
                $dst_range =    $row[6];
                $PC_processed++;
                }
            }
        ### AUSTRALIA ###
        if ($phone_code =='61')
            {
            $stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from asterisk.vicidial_phone_codes where country_code='$phone_code' and state='$state';";
            $rslt=mysql_query($stmt);
            $pc_recs = mysql_num_rows($rslt);
            if ($pc_recs > 0)
                {
                $row=mysql_fetch_row($rslt);
                $gmt_offset =    $row[4];     $gmt_offset = eregi_replace("\+","",$gmt_offset);
                $dst =            $row[5];
                $dst_range =    $row[6];
                $PC_processed++;
                }
            }
        ### ALL OTHER COUNTRY CODES ###
        if (!$PC_processed)
            {
            $PC_processed++;
            $stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from asterisk.vicidial_phone_codes where country_code='$phone_code';";
            $rslt=mysql_query($stmt);
            $pc_recs = mysql_num_rows($rslt);
            if ($pc_recs > 0)
                {
                $row=mysql_fetch_row($rslt);
                $gmt_offset =    $row[4];     $gmt_offset = eregi_replace("\+","",$gmt_offset);
                $dst =            $row[5];
                $dst_range =    $row[6];
                $PC_processed++;
                }
            }
        }

    ### Find out if DST to raise the gmt offset ###
    $AC_GMT_diff = ($gmt_offset - $LOCAL_GMT_OFF_STD);
    $AC_localtime = mktime(($Shour + $AC_GMT_diff), $Smin, $Ssec, $Smon, $Smday, $Syear);
        $hour = date("H",$AC_localtime);
        $min = date("i",$AC_localtime);
        $sec = date("s",$AC_localtime);
        $mon = date("m",$AC_localtime);
        $mday = date("d",$AC_localtime);
        $wday = date("w",$AC_localtime);
        $year = date("Y",$AC_localtime);
    $dsec = ( ( ($hour * 3600) + ($min * 60) ) + $sec );

    $AC_processed=0;
    if ( (!$AC_processed) and ($dst_range == 'SSM-FSN') )
        {
        if ($DBX) {print "     Second Sunday March to First Sunday November\n";}
#**********************************************************************
        # SSM-FSN
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on Second Sunday March to First Sunday November at 2 am.
        #     INPUTS:
        #       mm              INTEGER       Month.
        #       dd              INTEGER       Day of the month.
        #       ns              INTEGER       Seconds into the day.
        #       dow             INTEGER       Day of week (0=Sunday, to 6=Saturday)
        #     OPTIONAL INPUT:
        #       timezone        INTEGER       hour difference UTC - local standard time
        #                                      (DEFAULT is blank)
        #                                     make calculations based on UTC time,
        #                                     which means shift at 10:00 UTC in April
        #                                     and 9:00 UTC in October
        #     OUTPUT:
        #                       INTEGER       1 = DST, 0 = not DST
        #
        # S  M  T  W  T  F  S
        # 1  2  3  4  5  6  7
        # 8  9 10 11 12 13 14
        #15 16 17 18 19 20 21
        #22 23 24 25 26 27 28
        #29 30 31
        #
        # S  M  T  W  T  F  S
        #    1  2  3  4  5  6
        # 7  8  9 10 11 12 13
        #14 15 16 17 18 19 20
        #21 22 23 24 25 26 27
        #28 29 30 31
        #
#**********************************************************************

            $USACAN_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 3 || $mm > 11) {
            $USACAN_DST=0;
            } elseif ($mm >= 4 and $mm <= 10) {
            $USACAN_DST=1;
            } elseif ($mm == 3) {
            if ($dd > 13) {
                $USACAN_DST=1;
            } elseif ($dd >= ($dow+8)) {
                if ($timezone) {
                if ($dow == 0 and $ns < (7200+$timezone*3600)) {
                    $USACAN_DST=0;
                } else {
                    $USACAN_DST=1;
                }
                } else {
                if ($dow == 0 and $ns < 7200) {
                    $USACAN_DST=0;
                } else {
                    $USACAN_DST=1;
                }
                }
            } else {
                $USACAN_DST=0;
            }
            } elseif ($mm == 11) {
            if ($dd > 7) {
                $USACAN_DST=0;
            } elseif ($dd < ($dow+1)) {
                $USACAN_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (7200+($timezone-1)*3600)) {
                    $USACAN_DST=1;
                } else {
                    $USACAN_DST=0;
                }
                } else { # local time calculations
                if ($ns < 7200) {
                    $USACAN_DST=1;
                } else {
                    $USACAN_DST=0;
                }
                }
            } else {
                $USACAN_DST=0;
            }
            } # end of month checks
        if ($DBX) {print "     DST: $USACAN_DST\n";}
        if ($USACAN_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'FSA-LSO') )
        {
        if ($DBX) {print "     First Sunday April to Last Sunday October\n";}
#**********************************************************************
        # FSA-LSO
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in April and last Sunday in October at 2 am.
#**********************************************************************

            $USA_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 4 || $mm > 10) {
            $USA_DST=0;
            } elseif ($mm >= 5 and $mm <= 9) {
            $USA_DST=1;
            } elseif ($mm == 4) {
            if ($dd > 7) {
                $USA_DST=1;
            } elseif ($dd >= ($dow+1)) {
                if ($timezone) {
                if ($dow == 0 and $ns < (7200+$timezone*3600)) {
                    $USA_DST=0;
                } else {
                    $USA_DST=1;
                }
                } else {
                if ($dow == 0 and $ns < 7200) {
                    $USA_DST=0;
                } else {
                    $USA_DST=1;
                }
                }
            } else {
                $USA_DST=0;
            }
            } elseif ($mm == 10) {
            if ($dd < 25) {
                $USA_DST=1;
            } elseif ($dd < ($dow+25)) {
                $USA_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (7200+($timezone-1)*3600)) {
                    $USA_DST=1;
                } else {
                    $USA_DST=0;
                }
                } else { # local time calculations
                if ($ns < 7200) {
                    $USA_DST=1;
                } else {
                    $USA_DST=0;
                }
                }
            } else {
                $USA_DST=0;
            }
            } # end of month checks

        if ($DBX) {print "     DST: $USA_DST\n";}
        if ($USA_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'LSM-LSO') )
        {
        if ($DBX) {print "     Last Sunday March to Last Sunday October\n";}
#**********************************************************************
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on last Sunday in March and last Sunday in October at 1 am.
#**********************************************************************

            $GBR_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 3 || $mm > 10) {
            $GBR_DST=0;
            } elseif ($mm >= 4 and $mm <= 9) {
            $GBR_DST=1;
            } elseif ($mm == 3) {
            if ($dd < 25) {
                $GBR_DST=0;
            } elseif ($dd < ($dow+25)) {
                $GBR_DST=0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $GBR_DST=0;
                } else {
                    $GBR_DST=1;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $GBR_DST=0;
                } else {
                    $GBR_DST=1;
                }
                }
            } else {
                $GBR_DST=1;
            }
            } elseif ($mm == 10) {
            if ($dd < 25) {
                $GBR_DST=1;
            } elseif ($dd < ($dow+25)) {
                $GBR_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $GBR_DST=1;
                } else {
                    $GBR_DST=0;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $GBR_DST=1;
                } else {
                    $GBR_DST=0;
                }
                }
            } else {
                $GBR_DST=0;
            }
            } # end of month checks
            if ($DBX) {print "     DST: $GBR_DST\n";}
        if ($GBR_DST) {$gmt_offset++;}
        $AC_processed++;
        }
    if ( (!$AC_processed) and ($dst_range == 'LSO-LSM') )
        {
        if ($DBX) {print "     Last Sunday October to Last Sunday March\n";}
#**********************************************************************
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on last Sunday in October and last Sunday in March at 1 am.
#**********************************************************************

            $AUS_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 3 || $mm > 10) {
            $AUS_DST=1;
            } elseif ($mm >= 4 and $mm <= 9) {
            $AUS_DST=0;
            } elseif ($mm == 3) {
            if ($dd < 25) {
                $AUS_DST=1;
            } elseif ($dd < ($dow+25)) {
                $AUS_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $AUS_DST=1;
                } else {
                    $AUS_DST=0;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $AUS_DST=1;
                } else {
                    $AUS_DST=0;
                }
                }
            } else {
                $AUS_DST=0;
            }
            } elseif ($mm == 10) {
            if ($dd < 25) {
                $AUS_DST=0;
            } elseif ($dd < ($dow+25)) {
                $AUS_DST=0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $AUS_DST=0;
                } else {
                    $AUS_DST=1;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $AUS_DST=0;
                } else {
                    $AUS_DST=1;
                }
                }
            } else {
                $AUS_DST=1;
            }
            } # end of month checks
        if ($DBX) {print "     DST: $AUS_DST\n";}
        if ($AUS_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'FSO-LSM') )
        {
        if ($DBX) {print "     First Sunday October to Last Sunday March\n";}
#**********************************************************************
        #   TASMANIA ONLY
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in October and last Sunday in March at 1 am.
#**********************************************************************

            $AUST_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 3 || $mm > 10) {
            $AUST_DST=1;
            } elseif ($mm >= 4 and $mm <= 9) {
            $AUST_DST=0;
            } elseif ($mm == 3) {
            if ($dd < 25) {
                $AUST_DST=1;
            } elseif ($dd < ($dow+25)) {
                $AUST_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $AUST_DST=1;
                } else {
                    $AUST_DST=0;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $AUST_DST=1;
                } else {
                    $AUST_DST=0;
                }
                }
            } else {
                $AUST_DST=0;
            }
            } elseif ($mm == 10) {
            if ($dd > 7) {
                $AUST_DST=1;
            } elseif ($dd >= ($dow+1)) {
                if ($timezone) {
                if ($dow == 0 and $ns < (7200+$timezone*3600)) {
                    $AUST_DST=0;
                } else {
                    $AUST_DST=1;
                }
                } else {
                if ($dow == 0 and $ns < 3600) {
                    $AUST_DST=0;
                } else {
                    $AUST_DST=1;
                }
                }
            } else {
                $AUST_DST=0;
            }
            } # end of month checks
        if ($DBX) {print "     DST: $AUST_DST\n";}
        if ($AUST_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'FSO-FSA') )
        {
        if ($DBX) {print "     Sunday in October to First Sunday in April\n";}
#**********************************************************************
        # FSO-FSA
        #   2008+ AUSTRALIA ONLY (country code 61)
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in October and first Sunday in April at 1 am.
#**********************************************************************

        $AUSE_DST=0;
        $mm = $mon;
        $dd = $mday;
        $ns = $dsec;
        $dow= $wday;

        if ($mm < 4 or $mm > 10) {
        $AUSE_DST=1;
        } elseif ($mm >= 5 and $mm <= 9) {
        $AUSE_DST=0;
        } elseif ($mm == 4) {
        if ($dd > 7) {
            $AUSE_DST=0;
        } elseif ($dd >= ($dow+1)) {
            if ($timezone) {
            if ($dow == 0 and $ns < (3600+$timezone*3600)) {
                $AUSE_DST=1;
            } else {
                $AUSE_DST=0;
            }
            } else {
            if ($dow == 0 and $ns < 7200) {
                $AUSE_DST=1;
            } else {
                $AUSE_DST=0;
            }
            }
        } else {
            $AUSE_DST=1;
        }
        } elseif ($mm == 10) {
        if ($dd >= 8) {
            $AUSE_DST=1;
        } elseif ($dd >= ($dow+1)) {
            if ($timezone) {
            if ($dow == 0 and $ns < (7200+$timezone*3600)) {
                $AUSE_DST=0;
            } else {
                $AUSE_DST=1;
            }
            } else {
            if ($dow == 0 and $ns < 3600) {
                $AUSE_DST=0;
            } else {
                $AUSE_DST=1;
            }
            }
        } else {
            $AUSE_DST=0;
        }
        } # end of month checks
        if ($DBX) {print "     DST: $AUSE_DST\n";}
        if ($AUSE_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'FSO-TSM') )
        {
        if ($DBX) {print "     First Sunday October to Third Sunday March\n";}
#**********************************************************************
        #     This is s 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on first Sunday in October and third Sunday in March at 1 am.
#**********************************************************************

            $NZL_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 3 || $mm > 10) {
            $NZL_DST=1;
            } elseif ($mm >= 4 and $mm <= 9) {
            $NZL_DST=0;
            } elseif ($mm == 3) {
            if ($dd < 14) {
                $NZL_DST=1;
            } elseif ($dd < ($dow+14)) {
                $NZL_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $NZL_DST=1;
                } else {
                    $NZL_DST=0;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $NZL_DST=1;
                } else {
                    $NZL_DST=0;
                }
                }
            } else {
                $NZL_DST=0;
            }
            } elseif ($mm == 10) {
            if ($dd > 7) {
                $NZL_DST=1;
            } elseif ($dd >= ($dow+1)) {
                if ($timezone) {
                if ($dow == 0 and $ns < (7200+$timezone*3600)) {
                    $NZL_DST=0;
                } else {
                    $NZL_DST=1;
                }
                } else {
                if ($dow == 0 and $ns < 3600) {
                    $NZL_DST=0;
                } else {
                    $NZL_DST=1;
                }
                }
            } else {
                $NZL_DST=0;
            }
            } # end of month checks
        if ($DBX) {print "     DST: $NZL_DST\n";}
        if ($NZL_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'LSS-FSA') )
        {
        if ($DBX) {print "     Last Sunday in September to First Sunday in April\n";}
#**********************************************************************
        # LSS-FSA
        #   2007+ NEW ZEALAND (country code 64)
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect.
        #     Based on last Sunday in September and first Sunday in April at 1 am.
#**********************************************************************

        $NZLN_DST=0;
        $mm = $mon;
        $dd = $mday;
        $ns = $dsec;
        $dow= $wday;

        if ($mm < 4 || $mm > 9) {
        $NZLN_DST=1;
        } elseif ($mm >= 5 && $mm <= 9) {
        $NZLN_DST=0;
        } elseif ($mm == 4) {
        if ($dd > 7) {
            $NZLN_DST=0;
        } elseif ($dd >= ($dow+1)) {
            if ($timezone) {
            if ($dow == 0 && $ns < (3600+$timezone*3600)) {
                $NZLN_DST=1;
            } else {
                $NZLN_DST=0;
            }
            } else {
            if ($dow == 0 && $ns < 7200) {
                $NZLN_DST=1;
            } else {

                $NZLN_DST=0;
            }
            }
        } else {
            $NZLN_DST=1;
        }
        } elseif ($mm == 9) {
        if ($dd < 25) {
            $NZLN_DST=0;
        } elseif ($dd < ($dow+25)) {
            $NZLN_DST=0;
        } elseif ($dow == 0) {
            if ($timezone) { # UTC calculations
            if ($ns < (3600+($timezone-1)*3600)) {
                $NZLN_DST=0;
            } else {
                $NZLN_DST=1;
            }
            } else { # local time calculations
            if ($ns < 3600) {
                $NZLN_DST=0;
            } else {
                $NZLN_DST=1;
            }
            }
        } else {
            $NZLN_DST=1;
        }
        } # end of month checks
        if ($DBX) {print "     DST: $NZLN_DST\n";}
        if ($NZLN_DST) {$gmt_offset++;}
        $AC_processed++;
        }

    if ( (!$AC_processed) and ($dst_range == 'TSO-LSF') )
        {
        if ($DBX) {print "     Third Sunday October to Last Sunday February\n";}
#**********************************************************************
        # TSO-LSF
        #     This is returns 1 if Daylight Savings Time is in effect and 0 if
        #       Standard time is in effect. Brazil
        #     Based on Third Sunday October to Last Sunday February at 1 am.
#**********************************************************************

            $BZL_DST=0;
            $mm = $mon;
            $dd = $mday;
            $ns = $dsec;
            $dow= $wday;

            if ($mm < 2 || $mm > 10) {
            $BZL_DST=1;
            } elseif ($mm >= 3 and $mm <= 9) {
            $BZL_DST=0;
            } elseif ($mm == 2) {
            if ($dd < 22) {
                $BZL_DST=1;
            } elseif ($dd < ($dow+22)) {
                $BZL_DST=1;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $BZL_DST=1;
                } else {
                    $BZL_DST=0;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $BZL_DST=1;
                } else {
                    $BZL_DST=0;
                }
                }
            } else {
                $BZL_DST=0;
            }
            } elseif ($mm == 10) {
            if ($dd < 22) {
                $BZL_DST=0;
            } elseif ($dd < ($dow+22)) {
                $BZL_DST=0;
            } elseif ($dow == 0) {
                if ($timezone) { # UTC calculations
                if ($ns < (3600+($timezone-1)*3600)) {
                    $BZL_DST=0;
                } else {
                    $BZL_DST=1;
                }
                } else { # local time calculations
                if ($ns < 3600) {
                    $BZL_DST=0;
                } else {
                    $BZL_DST=1;
                }
                }
            } else {
                $BZL_DST=1;
            }
            } # end of month checks
        if ($DBX) {print "     DST: $BZL_DST\n";}
        if ($BZL_DST) {$gmt_offset++;}

        $AC_processed++;
        }

    if (!$AC_processed)
        {
        if ($DBX) {print "     No DST Method Found\n";}
        if ($DBX) {print "     DST: 0\n";}
        $AC_processed++;
        }

    return $gmt_offset;
    }
        
        
	
        public function delete_allocation(){
            $id=$this->request->query['id'];
            
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
            
            $this->ObAllocationMaster->query("UPDATE ob_allocation_name SET AllocationStatus='D',update_user='$update_user',update_date='$update_date' WHERE id='$id'");
            
            /*
            if($this->ObAllocationMaster->deleteAll(array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
                $this->ObCampaignDataMaster->deleteAll(array('AllocationId'=>$id));
            }
            */
            $this->redirect(array('action' => 'index'));
        }
        
        public function get_campaign($ClientId,$Campaign){
            $data=$this->CampaignName->find('first',array('fields'=>array("CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));	
            if(!empty($data)){
                return $data['CampaignName']['CampaignName'];
            }
	}
        
        public function get_allocation($ClientId,$allockid){
            $data=$this->ObAllocationMaster->find('first',array('fields'=>array("AllocationName"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$allockid)));	
            if(!empty($data)){
                return $data['ObAllocationMaster']['AllocationName'];
            }
	}
        
	
	public function get_allocation_field($ClientId,$Campaign){
		$data=$this->ObAllocationMaster->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		if(!empty($data)){
                $TotalCount=$data['ObAllocationMaster']['TotalCount'];
		return $TotalCount;
                }
	}
        
        public function count_field($ClientId,$Campaign){
		$data=$this->CampaignName->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		if(!empty($data)){
                $TotalCount=$data['CampaignName']['TotalCount'];
		return $TotalCount;
                }
	}
        
	
	
	public function download_campaign() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');			
		$Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
		$this->set('Campaign',$Campaign);	
	}
	
	public function download() {
		$this->layout='ajax';
		$ClientId = $this->Session->read('companyid');
		$Campaign= $this->request->query['id'];
		$data=$this->CampaignName->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		$TotalCount=$data['CampaignName']['TotalCount'];
		$TotalField=array();
		for($i=1;$i<=$TotalCount;$i++){$TotalField[]="Field$i";}
		$tArr=$this->CampaignName->find('first',array('fields'=>$TotalField,'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		$this->set('Data',$tArr['CampaignName']);
	}
		
	
}

?>