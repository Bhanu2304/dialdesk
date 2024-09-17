<?php
$user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
print_r($geo);die;
echo $country = $geo["geoplugin_countryName"];
 echo $city = $geo["geoplugin_city"];die;
?>