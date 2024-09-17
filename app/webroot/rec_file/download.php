<?php

$mode = addslashes($_GET['mode']);
$filename = addslashes($_GET['filename']);
$agt = addslashes($_GET['agt']);

if($filename!="")
{

	if($mode=="VFO")
	{
		$ctype="application/wav";
		$filename='/opt/recording/vfo/'.$filename;
		$retpath="http://www.dialdesk.co.in/srdetails.aspx";
	}
	else if($mode=="DD")
	{
		$ctype="application/gsm";
		$retpath="http://www.dialdesk.co.in/srdetails.aspx";
		$db_link=mysql_connect("192.168.0.5","root","vicidialnow");
		mysql_select_db("asterisk",$db_link);
		
		$qry = "Select start_time,location from recording_log where lead_id='$filename'";
		$rsc = mysql_query($qry);
		$dt  = mysql_fetch_assoc($rsc);
		
		$dir=substr($dt['start_time'],0,10);
		$dir=str_replace("-","",$dir);
		
		$tmp = explode("/",$dt['location']);
		$tmp_idx = count($tmp)-1;
		$tmp_filename = $tmp[$tmp_idx];
		
		if($tmp_filename!="") { $filename="/opt/recording/inbound/{$dir}/{$tmp_filename}"; } else { $filename=""; }
	}

	else if($mode=="dial")
	{
		$ctype="application/gsm";
		$retpath="http://www.dialdesk.co.in/srdetails.aspx";
		$db_link=mysql_connect("192.168.0.5","root","vicidialnow");
		mysql_select_db("asterisk",$db_link);
		
		 $qry = "Select start_time,location from recording_log where lead_id='$filename'";
		$rsc = mysql_query($qry);
		$dt  = mysql_fetch_assoc($rsc);
		//print_r($dt);die;
		 $dir=substr($dt['start_time'],0,10);
		 $dir=str_replace("-","",$dir); 
		
		$tmp = explode("/",$dt['location']);
		$tmp_idx = count($tmp)-1;
		$tmp_filename = $tmp[$tmp_idx];
		print_r($tmp_filename);die;
		if($tmp_filename!="") { $filename="/opt/recording/inbound/{$dir}/{$tmp_filename}"; } else { $filename=""; }
               // echo $filename; die;
	}

	else if($mode=="nextra")
	{
		$ctype="application/gsm";
		//$retpath="http://www.dialdesk.co.in/srdetails.aspx";
		$db_link=mysql_connect("192.168.0.5","root","vicidialnow");
		mysql_select_db("asterisk",$db_link);
		
		$qry = "Select start_time,location from recording_log where lead_id='$filename'";
		$rsc = mysql_query($qry);
		$dt  = mysql_fetch_assoc($rsc);
		
		$dir=substr($dt['start_time'],0,10);
		$dir=str_replace("-","",$dir);
		
		$tmp = explode("/",$dt['location']);
		$tmp_idx = count($tmp)-1;
		$tmp_filename = $tmp[$tmp_idx];
		
		if($tmp_filename!="") { $filename="/opt/recording/inbound/{$dir}/{$tmp_filename}"; } else { $filename=""; }
	}
		else if($mode=="TIMBL_NW")
	{
		$ctype="application/gsm";
		//$retpath="http://www.dialdesk.co.in/srdetails.aspx";
		$db_link=mysql_connect("192.168.0.5","root","vicidialnow");
		mysql_select_db("asterisk",$db_link);
		
	    $qry = "Select start_time,location,substring_index(filename,'_',4) filename  from recording_log where lead_id='$filename' ORDER BY start_time DESC";
		$rsc = mysql_query($qry);
		$dt  = mysql_fetch_assoc($rsc);
		
		$dir=substr($dt['start_time'],0,10);
		$dir=str_replace("-","",$dir);
		
		//$tmp = explode("/",$dt['location']);
		//$tmp_idx = count($tmp)-1;
		//$tmp_filename = $tmp[$tmp_idx];
		$tmp_filename=$dt['filename']."_".$agt."-all.gsm";
		$filename="/opt/recording/inbound/{$dir}/{$tmp_filename}";
		if($tmp_filename!="") { $filename="/opt/recording/inbound/{$dir}/{$tmp_filename}"; } else { $filename=""; }
	}

	
	else if($mode=="icare")
	{
		$ctype="application/gsm";
		//$retpath="http://www.dialdesk.co.in/srdetails.aspx";
		$db_link=mysql_connect("192.168.0.5","root","vicidialnow");
		mysql_select_db("asterisk",$db_link);
		
		$qry = "Select start_time,location from recording_log where lead_id='$filename'";
		$rsc = mysql_query($qry);
		$dt  = mysql_fetch_assoc($rsc);
		
		$dir=substr($dt['start_time'],0,10);
		$dir=str_replace("-","",$dir);
		
		$tmp = explode("/",$dt['location']);
		$tmp_idx = count($tmp)-1;
		$tmp_filename = $tmp[$tmp_idx];
		
		if($tmp_filename!="") { $filename="/opt/recording/inbound/{$dir}/{$tmp_filename}"; } else { $filename=""; }
	}

	else
	{
		$ctype="application/gsm";
		$filename='/opt/vfo/'.$filename;
		$retpath="http://www.dialdesk.co.in/srdetails.aspx";
	}

	if(file_exists($filename))
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: $ctype");
		
		header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($filename));
		readfile("$filename");
		$msg = "self.close();";
	}
	else
	{
		if($mode=="VFO" || $mode=="DD") { $msg = "alert('No Recording Files !');window.location.href='$retpath';"; }
		else { $msg = "alert('No Recording Files !');"; } 
	}
	echo "<script type=\"text/javascript\">$msg</script>";
	exit;
}

?>