<?php
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$get_client_id = $_GET['client_id'];
$where_get_tag = "";
if(!empty($get_client_id))
{
    $where_get_tag .= "and client_id = '$get_client_id'";
}


#echo "hello";die;
$fields_and_values = array(); 
$fields_qry    =   mysql_query("SELECT client_id,field,map_field FROM master_fields_map where type='ib' $where_get_tag  order by client_id");
while($fields_map=mysql_fetch_assoc($fields_qry))
{

    $client_id = $fields_map['client_id'];
    $field = $fields_map['field'];
    $field_map = $fields_map['map_field'];

    $fields_and_values[$client_id][$field] = $field_map;
}

foreach ($fields_and_values as $client_id => $field_value_pairs)
{
    
    $whereClause = " where ClientId='$client_id' and OtherStatus IS NULL";
    $selectColumns = "ClientId,id,";

    foreach($field_value_pairs as $field => $value)
    {
        if(!empty($value))
        {
            $selectColumns .= "`$value` as `$field`, ";
        }
        
    }
       
    #$whereClause = rtrim($whereClause, "AND ");
    $check_back_data    =   mysql_query("SELECT client_id FROM master_data where type='ib' and client_id='$client_id' limit 1");
    $check_back=mysql_fetch_assoc($check_back_data);

    if(empty($check_back))
    {
        $whereClause  .= " and DATE(CallDate) >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
    }else{
 
        $whereClause  .= " and DATE(CallDate)=CURDATE()";
    }

    $selectColumns = rtrim($selectColumns, ", ");
    
    $call_master = mysql_query("SELECT $selectColumns FROM call_master  $whereClause ");
    while($master=mysql_fetch_assoc($call_master))
    {
        $client_id = $master['ClientId'];
        $data_id = $master['id'];
        $type = "ib";
        $Name = $master['Name'];
        $Address = $master['Address'];
        $City = $master['City'];
        $Phone = $master['Phone'];
        $Email = $master['Email'];
        $State = $master['State'];
        $Pincode = $master['Pincode'];

        $insert_sql = mysql_query("INSERT INTO master_data (client_id,data_id,type,Name,Address,City,Phone,Email,State,Pincode,created_at) VALUES ('$client_id','$data_id','$type','$Name','$Address','$City','$Phone','$Email','$State','$Pincode',now())");
        if($insert_sql)
        {
            mysql_query("UPDATE `call_master` SET OtherStatus='1' WHERE ClientId='$client_id' AND Id='$data_id'");
        }
    }


}

?>


