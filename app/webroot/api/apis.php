<?php
include_once 'functions.php';
$db = new BGV_Functions();
$db->deConnect();
ini_set('display_errors','Off');
error_reporting(E_ALL);

$req_id = $_REQUEST['req_id'];

switch($req_id)
{

case 1:
{        
$db->login_check();
break;
}

case 2: 
{
$db->forget_password();
break;
}
case 3: 
{
$db->reset_password();
break;
}

case 4:
{
$db->get_case();
break;
}

case 5: 
$db->save_tagging();
break;

case 6: 
{
$db->saveLocation();
break;
}

}
?>