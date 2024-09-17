<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ERROR);

header('Content-Type: application/json');

mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk') or die('unable to connect'); 

$secret_key = 'dialdesk';

$headers = getallheaders();
$input = file_get_contents("php://input");

if(isset($headers['Auth-Token'])) 
{
    $auth_token = $headers['Auth-Token'];
    $verification_result = verifyAuthToken($auth_token, $secret_key);

    if ($verification_result && $verification_result['status'] === "success") 
    {
        $client_id = $verification_result['client_id'];
    } else 
    {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid Auth-Token']);
        exit();
    }
} else {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid or missing Auth-Token']);
    exit();
}

$data = json_decode($input, true);


$exist_field = array();
$field_numbers = array();
$master_fields_mapping  =   mysql_query("SELECT * FROM `bot_integration_fields` WHERE client_id='$client_id'");
while($mast_field = mysql_fetch_assoc($master_fields_mapping))
{

    $fieldId = $mast_field['field']; 
    $fieldNumber = str_replace('Field', '', $fieldId);
    $field_arr  =   mysql_fetch_assoc(mysql_query("SELECT * FROM `field_master` WHERE ClientId='$client_id' and fieldNumber='$fieldNumber' limit 1"));
    $exist_field[$field_arr['FieldName']] = "";
    $field_numbers[$field_arr['FieldName']] = $mast_field['field'];
}

$missing_fields = array_diff_key($exist_field, $data);
$additional_fields = array_diff_key($data, $exist_field);

if(!empty($missing_fields)) 
{
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing fields detected',
        'missing_fields' => array_keys($missing_fields)
    ]);
    die;
}

if(!empty($additional_fields)) 
{
    echo json_encode([
        'status' => 'error',
        'message' => 'Additional fields detected',
        'additional_fields' => array_keys($additional_fields)
    ]);
    die;
}

$mapped_data = array();
$warnings = [];
foreach($data as $fieldName => $value) 
{
    if(isset($field_numbers[$fieldName])) 
    {
        $fieldNumber = $field_numbers[$fieldName];
        $mapped_data[$fieldNumber] = $value;

    } else {
        $warnings[] = "Field '$fieldName' does not have a corresponding field number.";
    }
}

if (!empty($warnings)) 
{
    echo json_encode([
        'status' => 'warning',
        'message' => 'Field number warnings detected',
        'warnings' => $warnings
    ]);
    die;
}

$qry_last_ticket_no = mysql_query("select Max(SrNo) srno from call_master where ClientId='$client_id'");
$last_ticket_no = mysql_fetch_assoc($qry_last_ticket_no) ;
$SrNo = $last_ticket_no['srno'] + 1;
$calldate=date('Y-m-d H:i:s');
$static_columns = ['clientid', 'TagType', 'SrNo', 'LeadId', 'CallDate', 'AgentId', 'CallType', 'escalation_no'];
$static_values = [$client_id, 'Bot Integration', $SrNo, '0', $calldate, '0', 'WhatsApp', '0'];

$dynamic_columns = array_keys($mapped_data);
$dynamic_values = array_values($mapped_data);

$columns = array_merge($static_columns, $dynamic_columns);
$values = array_merge($static_values, $dynamic_values);

$columns_str = implode(',', $columns);
$values_str = "'" . implode("','", $values) . "'";


$insert_query = "INSERT INTO call_master ($columns_str) VALUES ($values_str)";

if(mysql_query($insert_query)) 
{
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Data inserted successfully',
        'In Call Id' => $SrNo
    ]);

} else {
    
    $error_msg = "Failed to insert data: " . mysql_error();
    writeToLog($error_msg);
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to insert data'
    ]);
}


function secure_compare($a, $b) 
{
    
    if (strlen($a) !== strlen($b)) 
    {
        return false;
    }
    
    $result = 0;
    for ($i = 0; $i < strlen($a); $i++) 
    {
        $result |= ord($a[$i]) ^ ord($b[$i]);
    }
    
    return $result === 0;
}

function verifyAuthToken($received_token, $secret_key) 
{
    $decoded_token = base64_decode($received_token);
    if ($decoded_token === false) 
    {
        return false;  
    }
    
    $parts = explode('|', $decoded_token);
    if (count($parts) !== 2) 
    {
        return false; 
    }

    list($client_id, $received_hmac) = $parts;

    $token_data = $client_id;
    $calculated_hmac = hash_hmac('sha256', $token_data, $secret_key);

    if(secure_compare($calculated_hmac, $received_hmac)) {
        return [
            'status' => 'success',
            'client_id' => $client_id
        ];
    } else {
        return false; 
    }
}


function writeToLog($logmessage)
{
    $logmessage = $logmessage.'['.date('Y_m_d_h_i_s').']';
    $myfile = fopen("log", "a") or die("Unable to open file!");

    fwrite($myfile, $logmessage . PHP_EOL);

    fclose($myfile);

}

mysqli_close($conn);



?>