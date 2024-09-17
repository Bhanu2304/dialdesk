<?php
$User_Name  =   "Ramesh k";
$exp        =   explode(" ", trim($User_Name));
$First_Name =   current($exp);

if(end($exp) !=""){
    $Last_Name  =   end($exp);
}
else{
   $Last_Name  =   current($exp); 
}

echo $Last_Name;


die;

date('y');

$NewId=1;
$len = strlen($NewId); 
$NewNumber = '00000';
$NewNumber = substr_replace($NewNumber,'',0,$len+1); 
$NewGenId = $NewNumber.$NewId;
?>