<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$db = mysqli_connect("localhost","root","dial@mas123");
mysqli_select_db($db,"db_dialdesk")or die("cannot select DB");


#gram-tarang 584

$gram_qry = mysqli_query($db,"SELECT * FROM `gram_tarang` WHERE status='0' ");
$entry_list = array();
$no_of_records = 0;

while($call_data = mysqli_fetch_assoc($gram_qry))
{
    #print_r($call_data);
    $application_name = $call_data['application_name'];
    $email = $call_data['email'];
    $mobile = $call_data['mobile'];
    $channel = $call_data['channel'];
    $source = $call_data['source'];
    $center = $call_data['center'];
    $program_type = $call_data['program_type'];
    $location = $call_data['location'];

    $entry_list[] =  array('id' => $call_data['id'],'AuthToken' => "IMPACT-16-04-2024",'Source' => "impact",'FirstName' => $application_name,'Email' => $email,'MobileNumber' => $mobile,
        'LeadSource' => $source,'leadChannel' => $channel,'Course' => $location,'Center' => $program_type,'Location' => $location
    );

    
}

foreach($entry_list as $entry)
{
    $update_id = $entry['id'];
    unset($entry['id']);
    $json_output = json_encode($entry);

    writeToLog("json data ->" . $json_output);

    $url = "https://thirdpartyapi.extraaedge.com/api/SaveRequest";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $json_output,
        CURLOPT_HTTPHEADER => array(
            'AuthToken: IMPACT-16-04-2024',
            'Source: impact',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        writeToLog("send_data cURL Error # ->" . $err);

    } else {


        $update_query = "UPDATE `gram_tarang` SET response = '$response',status='1'  WHERE id = $update_id";
        $update_data =  mysqli_query($db, $update_query);

        if($update_data)
        {        

        }
        else
        {
            writeToLog("send_data mysql error-> " .mysqli_error($db));

        }

    
    }
   
}



function writeToLog($logmessage){

  $myfile = fopen("log", "a") or die("Unable to open file!");

  fwrite($myfile, $logmessage . PHP_EOL);

  fclose($myfile);


}

?>


