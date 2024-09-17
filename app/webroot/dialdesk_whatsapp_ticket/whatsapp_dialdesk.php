<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$conn = mysqli_connect("localhost","root","dial@mas123") or die(mysqli_error($conn));
mysqli_select_db($conn,"db_dialdesk") or die("error in database");

$input = json_decode(file_get_contents('php://input'), true);
writeToLog("json".file_get_contents('php://input'));

writeToLog("data"."data1234556667");
if(isset($_REQUEST['RegisterNumber']))
{
  writeToLog("data"."data1234556667");
}
if(isset($_GET['RegisterNumber']))
{
  writeToLog("data"."data1234556667");
}
if(isset($input)) {


    if(empty($input))
        exit();


    $contact = $input['contacts'][0];
    $message = $input['messages'][0];
    $statuses = $input['statuses'][0];

    if(!empty($statuses)) {
        exit();
    }

    if(empty($contact) && empty($message)) {
        exit();
    }

    $name = $contact['profile']['name'];
    $wa_id = $contact['wa_id'];


    $from = $message['from'];
    $session_id = $message['id'];
    $message_id = $message['id'];
    $text =    $message['text']['body'];
    $timestamp =    $message['timestamp'];
    $type = $message['type'];

    writeToLog("from".$from);
    writeToLog("text".$text);


     $qry = "select * from dialdesk_whatsapp_track where wa_id='$wa_id' limit 1";
     $step_status = mysqli_query($conn,$qry);
     $step = mysqli_fetch_assoc($step_status);

    //writeToLog("qry".$step);
    $db_num = $step['wa_id'];
    $db_step = $step['step'];

    if($wa_id!="") {

        if($type=='text') {
            
            if($text == "hi"  || $text == "Hi" || $text == "Hello" || $text == "hello")
            { 
                $NewMSG = "Welcome to DialDesk Can I have your name please?";
                $insert = "INSERT INTO `dialdesk_whatsapp_track` SET wa_id='$wa_id',step='1'";
                $save = mysqli_query($conn,$insert);
            }
            else if($db_step == '1')
            {
                $NewMSG = "Thanks, Pls share the name of your company";
                $insert = "UPDATE dialdesk_whatsapp_track SET step='2' WHERE wa_id='$wa_id'";
                $save = mysqli_query($conn,$insert);
            }
            else if($db_step == '2')
            {
                $NewMSG = "Thanks again, Pls share your concern";
                $insert = "UPDATE dialdesk_whatsapp_track SET step='3' WHERE wa_id='$wa_id'";
                $save = mysqli_query($conn,$insert);
            }
            else if($db_step == '3')
            {
                $NewMSG = "Is there anything else that you may want to add?";
                $insert = "UPDATE dialdesk_whatsapp_track SET step='4' WHERE wa_id='$wa_id'";
                $save = mysqli_query($conn,$insert);
            }
            else if($db_step == '4')
            {
                $NewMSG = "Is there anything else that you may want to add?";
                $insert = "UPDATE dialdesk_whatsapp_track SET step='5' WHERE wa_id='$wa_id'";
                $save = mysqli_query($conn,$insert);
            }
            else if($db_step == '5')
            {
                $NewMSG = "Thanks, Your service request is generated and your service ID is 123456. Our support representative will reach you in next";
                $del = "DELETE FROM MyGuests WHERE wa_id='$wa_id'";
                $save = mysqli_query($conn,$del);
            }

              $data = '{
                "to": "'.$from.'",
                "type": "text",
                "recipient_type": "individual",
                "text": {
                  "body": "'.$NewMSG.'"
                }
              }';
              
$curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://waba.whatsdesk.in/v1/messages.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'API-KEY: xR1MOeD4HBQb9xQKSxpTfAmbAK'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
//echo $response;
    writeToLog("Response".'{"customer_no":"'.$from.'","msg":"'.$text.'","customer_name":"'.$name.'"}'
        );
    writeToLog("Response".$response);

            
            // process text
        } else if($type=='document')  {

        } else if($type=='image')  {

        } else if($type=='video')  {

        }else  {

            // find which type is this

        }


    }

}

function writeToLog($logmessage){

    $myfile = fopen("mylog", "a") or die("Unable to open file!");

    fwrite($myfile, $logmessage . PHP_EOL);

    fclose($myfile);


}
?>