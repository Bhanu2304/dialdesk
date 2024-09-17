<?php
include("include/connection.php");
include("include/session-check.php");

$row  = 1;
$html = '';

if(isset($_POST['mode'])) { $mode = addslashes($_POST['mode']); }
if(isset($_POST['DataId'])) { $DataId = addslashes($_POST['DataId']); }
if(isset($_POST['fe'])) { $fe = addslashes($_POST['fe']); }
if(isset($_POST['oldefe'])) { $oldefe = addslashes($_POST['oldefe']); }
if(isset($_POST['code'])) { $code = addslashes($_POST['code']); }
if(isset($_POST['stat'])) { $stat = addslashes($_POST['stat']); }
if(isset($_POST['planId'])) { $planId = addslashes($_POST['planId']); }
if(isset($_POST['parent'])) { $parent = addslashes($_POST['parent']); }
if(isset($_POST['clus'])) { $clus = addslashes($_POST['clus']); }
if(isset($_POST['calltype'])) { $call = addslashes($_POST['calltype']); }
if(isset($_POST['statuscall'])) { $statuscall = addslashes($_POST['statuscall']); }
if(isset($_POST['Voc'])) { $Voc = addslashes($_POST['Voc']); }
if(isset($_POST['Issue3'])) { $Issue3 = addslashes($_POST['Issue3']); }
if(isset($_POST['agentid'])) { $fagent1 = addslashes($_POST['agentid']); }
if(isset($_POST['alloc'])) { $alloc = addslashes($_POST['alloc']); }
if(isset($_POST['Allocationid'])) { $Allocationid = addslashes($_POST['Allocationid']); }

switch($mode)
{
	
	case 'SHOWDSP' :
	     $qry="SELECT * FROM voc WHERE CallStatus='$clus' GROUP BY CallType";
		 $rsc = mysql_query($qry);
		$tcselect = "<select name=\"CallType\" id=\"CallType\" required=\"true\" onChange=\"ShowStat(this.value); ShowCallBack(this.value);\" class=\"form-control\"  style=\"width:150px;\">";
		
		$tcselect =$tcselect."<option value=\"\">--Select Type--</option>";
		while($dt=mysql_fetch_assoc($rsc))
		{ 
		$tcselect = $tcselect."<option value=\"{$dt['CallType']}\">{$dt['CallType']}</option>";
		}
    	$tcselect = $tcselect."</select>"; 
        $html = $tcselect;
   break;

 
   case 'SHOWSTAT' :
	     $qry="SELECT * FROM voc WHERE CallStatus='$clus'";
		 $rsc = mysql_query($qry);
		$tcselect = "<select name=\"VOC\" id=\"VOC\"  onChange=\"ShowIssue3(this.value);\"  class=\"form-control\"  style=\"width:150px;\">";
		
		$tcselect =$tcselect."<option value=\"\">--Select Type--</option>";
		while($dt=mysql_fetch_assoc($rsc))
		{ 
		$tcselect = $tcselect."<option value=\"{$dt['Voc']}\">{$dt['Voc']}</option>";
		}
    	$tcselect = $tcselect."</select>"; 
        $html = $tcselect;
   break;

   case 'SHOWISSUE3' :
	     $qry="SELECT * FROM voc WHERE CallStatus = '$statuscall' ";
		 $rsc = mysql_query($qry);
		$tcselect = "<select name=\"Issue3\" id=\"Issue3\"  class=\"form-control\"  style=\"width:150px;\">";
		
		$tcselect =$tcselect."<option value=\"\">--Select Type--</option>";
		while($dt=mysql_fetch_assoc($rsc))
		{ 
		 $tcselect = $tcselect."<option value=\"{$dt['Issue3']}\">{$dt['Issue3']}</option>";
		}
    	$tcselect = $tcselect."</select>"; 
        $html = $tcselect;
   break;
   
   case 'SHOWSubSTAT' :
     // echo "select dateRequire from voc where CallStatus = '$statuscall' and CallType = '$call' and Voc = '$clus'"; exit;
	 $qury = mysql_query("select dateRequire from voc where CallStatus = '$statuscall' and CallType = '$call'");
            $dateStatus = mysql_fetch_assoc($qury);
           
		if($dateStatus['dateRequire']=='yes') {
		$tcselect = $tcselect."<input type =\"text\" name=\"Fdate\" id=\"Fdate\" class=\"input-tooltip\" style=\"width:150px;\"  onClick=\"javascript:NewCssCal ('Fdate{$id}','yyyyMMdd','arrow',true,'24',true)\" readonly>
		<img src=\"img/cal.gif\" onClick=\"javascript:NewCssCal ('Fdate{$id}','yyyyMMdd','arrow',true,'24',true)\"   style=\"cursor:pointer\"/>
		</td>";
		}
        $html = $tcselect;
		
   break;
    case 'slelectall' :
	     $qry="select * from allocation_name where Id='$Allocationid' limit 1";
		 $rsc = mysql_query($qry);
		$tcselect = "<select name=\"Pmtype\" id=\"Pmtype\" required=\"yes\"  style=\"width:150px;\" class=\"form-control\"  >";
		
		$tcselect =$tcselect."<option value=\"\">--Select Type--</option>";
		while($dt=mysql_fetch_assoc($rsc))
		{ 
                    $tcselect .= '<option value="'.$dt['PMType'].'" selected>'.$dt['PMType'].'</option>';
                    $pmType = $dt['PMType'];
		}
                if($pmType=='Mannual')
                {
                    $tcselect .= '<option value="PD">PD</option>';
                }
                else
                {$tcselect .= '<option value="Mannual">Mannual</option>';}
    	$tcselect = $tcselect."</select>"; 
        $html = $tcselect;
   break;

   case 'SELCOUNT':		
	   $cnt= "SELECT COUNT(AgentId) as count FROM allocation_master WHERE AgentId = '$fagent1' and AllocationId = '$alloc' and CallCount = '0'";
    $h= mysql_fetch_array(mysql_query($cnt));
    $html = $h['count'];
		break;	
		
	 case 'SHOWCALL' :
             //echo "select dateRequire from voc where CallStattus = '$statuscall' and CallType = '$call' and Voc = '$clus'"; exit;
             $qury = mysql_query("select dateRequire from voc where CallStattus = '$clus' and CallType = '$call' and Voc = '$voc'");
            $dateStatus = mysql_fetch_array(mysql_query($qury));
		if($dateStatus['dateRequire']=='yes') {
		$tcselect = $tcselect."<input type =\"text\" name=\"Fdate\" id=\"Fdate\" class=\"input-tooltip\" style=\"width:150px;\" readonly>
		<img src=\"img/cal.gif\" onClick=\"javascript:NewCssCal ('Fdate{$id}','yyyyMMdd','arrow',true,'24',true)\"   style=\"cursor:pointer\"/>
		</td>";
		}
                
        $html = $tcselect;
		
   break;	


}

header("Content-type: text/html");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
echo $html;
?>

