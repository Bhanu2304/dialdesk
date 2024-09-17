<?php
# 
# dbconnect_mysqli.php    version 2.12
#
# database connection settings and some global web settings
#
# Copyright (C) 2016  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES:
# 130328-0022 - Converted ereg to preg functions
# 130802-0957 - Changed to PHP mysqli functions, added 
# 131101-0713 - Fixed slave server setting
# 131210-1746 - Added ability to define slave server with port number, issue #687
# 150216-1529 - Removed non-latin set to 0
# 150313-0913 - Added ExpectedDBSchema conf file value
# 150626-2120 - Modified mysqli_error() to mysqli_connect_error() where appropriate
# 160325-1434 - Changes for sidebar update
#


	#defaults for DB connection
	$VARDB_server = '192.168.10.5';
	$VARDB_port = '3306';
	$VARDB_user = 'root';
	$VARDB_pass = 'vicidialnow';
	$VARDB_custom_user = 'custom';
	$VARDB_custom_pass = 'custom1234';
	$VARDB_database = 'asterisk';
	$WeBServeRRooT = '/usr/local/apache2/htdocs';



$server_string = $VARDB_server;
if ( ($use_slave_server > 0) and (strlen($slave_db_server)>1) )
	{
	if (preg_match("/\:/", $slave_db_server)) 
		{
		$temp_slave_db = explode(':',$slave_db_server);
		$server_string =	$temp_slave_db[0];
		$VARDB_port =		$temp_slave_db[1];
		}
	else
		{
		$server_string = $slave_db_server;
		}
	}
$link=mysqli_connect($server_string, "$VARDB_user", "$VARDB_pass", "$VARDB_database", $VARDB_port);

if (!$link) 
	{
 //   die("MySQL connect ERROR: |$server_string|$VARDB_user|$VARDB_pass|$VARDB_database|$VARDB_port|$temp_slave_db[0]|$temp_slave_db[1]|$slave_db_server|$use_slave_server|" . mysqli_error('mysqli'));
    die("MySQL connect ERROR:  " . mysqli_connect_error());
	}

$local_DEF = 'Local/';
$conf_silent_prefix = '7';
$local_AMP = '@';
$ext_context = 'default';
$recording_exten = '8309';
$WeBRooTWritablE = '1';
# $non_latin = '0';	# set to 1 for UTF rules, overridden by system_settings
$flag_channels=0;
$flag_string = 'VICIast20';
$Msubhead_color =	'#E6E6E6';
$Mselected_color =	'#C6C6C6';
$Mhead_color =		'#A3C3D6';
$Mmain_bgcolor =	'#015B91';

?>
