<?php

$mode = addslashes($_GET['mode']);
$filename = addslashes($_GET['filename']);

if($filename!="")
{

	if($mode=="VFO")
	{
		$ctype="application/wav";
		$filename='/opt/vfo/'.$filename;
		$retpath="http://www.dialdesk.co.in/srdetails.aspx";
	}
	else if($mode=="DD")
	{
		$retpath="http://www.dialdesk.co.in/srdetails.aspx";
		$db_link=mysql_connect("192.168.0.5","root","vicidialnow");
		mysql_select_db("asterisk",$db_link);
		
		$qry = "Select location from recording_log where lead_id='$filename'";
		$rsc = mysql_query($qry);
		$dt  = mysql_fetch_assoc($rsc);
		
		$tmp = explode("/",$dt['location']);
		$tmp_idx = count($tmp)-1;
		$tmp_filename = $tmp[$tmp_idx];
		
		if($tmp_filename!="") { $filename='/opt/vfo/'.$tmp_filename; } else { $filename=""; }
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