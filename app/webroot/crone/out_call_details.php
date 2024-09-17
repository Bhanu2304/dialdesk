<?php 
//$db2 = mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('report-send.php');

$select = "SELECT * FROM out_call_detail_automate";
// -- WHERE IF(CONCAT(HOUR(NOW()),':',MINUTE(NOW()))=report_value,TRUE,FALSE)

$export = mysql_query($select,$db1);

while($row = mysql_fetch_assoc($export))
{
    $condition = "DATE(CallDate)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    
    outcall_details($condition,$row['client'],$row['client_name'],$row['campaign_id'],$row['to'],$row['cc'],$row['remarks'],$db1);
    
}






function outcall_details($condition,$clientId,$clientname,$campaign_id,$emailId,$emailid2,$remarks,$db1)
{

    // $Campaign_parent =  mysql_query("select id,CampaignName from ob_campaign where ClientId ='$clientId' and CampaignStatus='A'",$db1);
    // $Camp_parent = mysql_fetch_assoc($Campaign_parent);
    $category_field1   = get_category_field_header($clientId,$campaign_id,$db1);
    $capcture_field   =  get_chapcher_field($clientId,$campaign_id,$db1);
    $close_capcture_field   = get_close_chapcher_field($clientId,$campaign_id,$db1);

    $total_count      = campaign_count_field($clientId,$campaign_id,$db1);
    $cmp_field   	  = get_fields($total_count,"Field");
    $campaign_field   = get_campaign_fields($campaign_id,$clientId,$cmp_field,$db1);

    $category_field   = get_category_field($clientId,$campaign_id,$db1);

    $callmaster_field = get_field_number($clientId,$campaign_id,$db1);
    $close_callmaster_field = get_close_field_number($clientId,$campaign_id,$db1);
    $allocation_field = get_fields($total_count,"obcd.Field");

    $header_field=array_merge(array('Out Call Id','Call From'),$category_field1,$capcture_field,array('CallDate','Call Action','Call Sub Action','Call Action Remarks','Closer Date','Follow Up Date','Call Created','Reschedule Data'),$close_capcture_field,$campaign_field);
    $values_field=array_merge(array('SrNo','MSISDN'),$category_field,$callmaster_field,array('cmo.CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','callcreated','RescheduleData'),$close_callmaster_field,$allocation_field);

    $values_field = implode(",",$values_field);
    
    $Data = mysql_query("select $values_field from call_master_out cmo LEFT JOIN ob_campaign_data obcd ON cmo.DataId=obcd.id
     WHERE cmo.ClientId = '$clientId' AND DATE(cmo.CallDate)=curdate() GROUP BY obcd.id",$db1);
    //  and DATE(cmo.CallDate)=curdate()DATE(cmo.CallDate)=curdate()

    $html = "<table cellspacing='0' border='1'>";
    $html .= "<thead>";
    $html .= "<tr>";

    foreach($header_field as $hedrow)
    {

        if(is_array($hedrow))
        {
            foreach($hedrow as $head)
            {
                $html .="<th>".$head."</th>";
            }
            
        }else{
            $html .="<th>".$hedrow."</th>";
        }
    
        
    }

    $html .= "</tr>";
    $html .= "</thead>";
    $html .= "<tbody>";

    while($head = mysql_fetch_assoc($Data))
    { 

        $expl = explode('-',$head['callcreated']);
        $html .= "<tr>";
        foreach($head as $key=>$row){
            
            $html .= "<td>".$row."</td>";
        
        }
    
        $html .= "</tr>";
       
    }

   

    $html .= "</tbody>";
    $html .= "</table>";
   

    $to = explode(',',$emailId);
    $cc = explode(',',$emailid2);

    $last_day_date = date('d-m-Y');
    //$ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu');
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "Out Call Details - ($last_day_date)"; 
    $EmailText      ="<div>";
    $EmailText  .= $html;
    $EmailText     .= "<p>This is auto genrated mail.</p>";
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";
    $EmailText    .="</div>";
    // echo $EmailText;die;
    //  return $html; 
        
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc);
    //print_r($emaildata);die;
    $done = send_email($emaildata);

    if($done == '1')
    {
        mysql_query("insert outcall_details_log SET client_name='{$clientname}',campaign_id='$campaign_id',`too`='$emailId',cc='$emailid2',MailStatus='1',MailDateTime=NOW()",$db1);

    }else{

        mysql_query("insert outcall_details_log SET client_name='{$clientname}',campaign_id='$campaign_id',`too`='$emailId',cc='$emailid2',MailStatus='0',MailDateTime=NOW()",$db1);

    }
}
    

        

function campaign_count_field($clientId,$campaign_id,$db1){

    $allocationid = array();
    $Campaign_parent =  mysql_query("select TotalCount from ob_allocation_name where ClientId ='$clientId' and CampaignId = '$campaign_id' and AllocationStatus='A'",$db1);
    $Camp_parent = mysql_fetch_assoc($Campaign_parent);
    
    return $Camp_parent['TotalCount'];

}

function get_category_field_header($client,$campaign_id,$db1)
{
    $ecr = mysql_query("select label,ecrName from obecr_master where Client='$client' and CampaignId='$campaign_id' GROUP BY Label ORDER BY Label ASC",$db1);

    $keyss = array();
    $catheader=array();
    while($k = mysql_fetch_assoc($ecr))
    {
        $keys = array_keys($k);
        $keyss[] = $k['label'];
        $no=$k['label']-1;
                            
        if($k['label'] =='1'){
            $catheader[] = "SCENARIO";
        }
        else {
            $catheader[] = "SUB SCENARIO".$no;
        }
    }

    return $catheader;

}

function get_chapcher_field($ClientId,$CampaignId,$db1){

    $fldheader=array();

    $qry = mysql_query("select FieldName from obfield_master where ClientId='$ClientId' and CampaignId='$CampaignId' and FieldStatus is null order by Priority",$db1);
    while($post = mysql_fetch_assoc($qry))
    {
        $fldheader[] = $post['FieldName'];
    }
    
    return $fldheader;
}

function get_close_chapcher_field($ClientId,$CampaignId,$db1){

    $fldheader=array();
    $qry = mysql_query("select FieldName from ob_close_master where ClientId='$ClientId' and CampaignId='$CampaignId' and FieldStatus is null order by Priority",$db1);

    while($post = mysql_fetch_assoc($qry))
    {
        $fldheader[] = $post['FieldName'];
    }
    
    return $fldheader;
}

function get_fields($field,$type){
    $column=array();
    for($i=1;$i<=$field;$i++){
        $column[]=$type.$i;
    }
    return $column;
}

function get_campaign_fields($id,$ClientId,$field,$db1){

    $field = implode(",",$field);
    $qry = mysql_query("select $field from ob_campaign where ClientId='$ClientId' and id='$id'",$db1);
    
    $fldheader=array();

    while($post = mysql_fetch_assoc($qry))
    {
        $fldheader[] = array_values($post);
    }

    return $fldheader;
}

function get_category_field($ClientId,$CampaignId,$db1){

    $ecr = mysql_query("select label,ecrName from obecr_master where Client='$ClientId' and CampaignId='$CampaignId' GROUP BY Label ORDER BY Label ASC",$db1);

    $keyss = array();
    $catheader=array();
    while($k = mysql_fetch_assoc($ecr))
    {
        $keys = array_keys($k);
        $keyss[] = $k['label'];
        $no=$k['label'];
                            
        if($k['label'] =='1'){
            $catheader[] = "Category1";
        }
        else {
            $catheader[] = "Category".$no;
        }
    }

    return $catheader;
}

function get_field_number($ClientId,$CampaignId,$db1){
    $fldheader=array();
    $qry = mysql_query("select fieldNumber from obfield_master where ClientId='$ClientId' and CampaignId='$CampaignId' and FieldStatus is null order by Priority",$db1);

    while($post = mysql_fetch_assoc($qry))
    {
        $fldheader[] = "cmo.Field".$post['fieldNumber']." as closeField".$post['fieldNumber'];
    }
    
    return $fldheader;
}

function get_close_field_number($ClientId,$CampaignId,$db1){
    $fldheader=array();
    $qry = mysql_query("select fieldNumber from ob_close_master where ClientId='$ClientId' and CampaignId='$CampaignId' and FieldStatus is null order by Priority",$db1);

    while($post = mysql_fetch_assoc($qry))
    {
        $fldheader[] = $post['fieldNumber'];
    }
    
    return $fldheader;
}

             
        
?>