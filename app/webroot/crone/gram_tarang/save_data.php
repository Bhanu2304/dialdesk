<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$db = mysqli_connect("localhost","root","dial@mas123");
mysqli_select_db($db,"db_dialdesk")or die("cannot select DB");


$data_api = [
  ['Program Id' => 1, 'Program Type' => 'Long Term Courses', 'Center Id' => 1, 'Center' => 'Bhubaneswar', 'Course Id' => 1, 'Course' => 'BBA in Digital Marketing'],
  ['Program Id' => 1, 'Program Type' => 'Long Term Courses', 'Center Id' => 2, 'Center' => 'Delhi', 'Course Id' => 2, 'Course' => 'Diploma in Digital Marketing'],
  ['Program Id' => 7, 'Program Type' => 'Short Term Certificate Courses', 'Center Id' => 3, 'Center' => 'Bhubaneswar', 'Course Id' => 5, 'Course' => 'Certificate in Digital Marketing- BBSR'],
  ['Program Id' => 7, 'Program Type' => 'Short Term Certificate Courses', 'Center Id' => 3, 'Center' => 'Bhubaneswar', 'Course Id' => 6, 'Course' => 'Digital Marketing & E-Commerce for Entrepreneurs-BBSR'],
  ['Program Id' => 7, 'Program Type' => 'Short Term Certificate Courses', 'Center Id' => 5, 'Center' => 'Chandigarh', 'Course Id' => 11, 'Course' => 'Certificate in Digital Marketing- Mohali'],
  ['Program Id' => 7, 'Program Type' => 'Short Term Certificate Courses', 'Center Id' => 5, 'Center' => 'Chandigarh', 'Course Id' => 12, 'Course' => 'Digital Marketing & E-Commerce for Entrepreneur- Mohali'],
  ['Program Id' => 7, 'Program Type' => 'Short Term Certificate Courses', 'Center Id' => 4, 'Center' => 'Delhi', 'Course Id' => 33, 'Course' => 'Certificate in Digital Marketing- Delhi'],
];


function findCenterAndProgramIdByCourse($data, $courseName) {
  foreach ($data as $entry) {
      if ($entry['Course'] === $courseName) {
          return [
              'Course Id' => $entry['Course Id'],
              'Center Id' => $entry['Center Id'],
              'Program Id' => $entry['Program Id']
          ];
      }
  }
  return null;
}


#gram-tarang 584

// ------------------------------------------------- for inbound ---------------------------------------- \\

$call_qry = mysqli_query($db,"SELECT * FROM `call_master` WHERE clientid='584' and date(CallDate)= curdate()");
$entry_list = array();
$no_of_records = 0;

while($call_data = mysqli_fetch_assoc($call_qry))
{
    #print_r($call_data);
    $data_id = $call_data['Id'];
    $name = $call_data['Field3'];
    $email = $call_data['Field4'];
    $mobile = $call_data['Field5'];
    $campaign = $call_data['Field6'];
    #$channel = $call_data['Field7'];
    #$source = $call_data['Field8'];
    #$center = $call_data['Field9'];
    #$program_type = $call_data['Field14'];
    #$location = $call_data['Field12'];
    $course_name = $call_data['Field9'];

    $result = findCenterAndProgramIdByCourse($data_api, $course_name);
    if ($result) {

      $location = $result['Course Id'];
      $center = $result['Center Id'];
      $program_type = $result['Program Id'];

    } else {
        
    }

    $check_qry = "select * from gram_tarang where type='ib' and data_id='$data_id' limit 1";
    $check_data = mysqli_query($db,$check_qry);
    $check_arr = mysqli_fetch_assoc($check_data);

    if(empty($check_arr))
    {
        $entry_list[] ="('$data_id','ib','$name','$email','$mobile','$campaign','22','127','$center','$program_type','$location',now())"; 
        $no_of_records +=1; 
    }

    
}

#print_r($entry_list);die;


if(!empty($entry_list))
{
  $ins_merge = implode(",",$entry_list);
  $ins_qry = "insert into gram_tarang(data_id,type,application_name,email,mobile,campaign,channel,source,center,program_type,location,create_date) values $ins_merge;";
  $rsc_ticket = mysqli_query($db,$ins_qry);


  if($rsc_ticket)
  {        
    http_response_code(200);    
    echo $resp =json_encode(array('status'=>True,'total'=>$no_of_records));
    writeToLog("save_data success-> " .$resp); 
  }
  else
  {
    writeToLog("save_data mysql error-> " .mysqli_error($db));
    http_response_code(500);
    echo json_encode(array('status'=>False,'error'=>'internal server error.'));
  }
}


die;

// ------------------------------------------------- for outbound ---------------------------------------- \\

$out_call_qry = mysqli_query($db,"SELECT * FROM `call_master_out` WHERE clientid='499' AND DATE(CallDate)= CURDATE()");
$entry_list_out = array();
$out_no_of_records = 0;

while($out_call_data = mysqli_fetch_assoc($out_call_qry))
{
    #print_r($call_data);
    $data_id = $out_call_data['Id'];
    $name = $out_call_data['Field1'];
    $email = $out_call_data['Field2'];
    $mobile = $out_call_data['MSISDN'];
    $channel = $out_call_data['Field3'];
    $source = $out_call_data['Field4'];
    $center = $out_call_data['Field1'];
    $program_type = $out_call_data['Field2'];
    $location = $out_call_data['Field3'];

    $check_qry_out = "select * from gram_tarang where type='ob' and data_id='$data_id' limit 1";
    $check_out_data = mysqli_query($db,$check_qry_out);
    $check_out_arr = mysqli_fetch_assoc($check_out_data);

    if(empty($check_out_arr))
    {
        $entry_list_out[] ="('$data_id','ob','$name','$email','$mobile','$channel','$source','$center','$program_type','$location',now())"; 
        $out_no_of_records +=1; 
    }

    
}

if(!empty($entry_list_out))
{
  $outs_merge = implode(",",$entry_list_out);
  $outs_qry = "insert into gram_tarang(data_id,type,application_name,email,mobile,channel,source,center,program_type,location,create_date) values $outs_merge;";
  $rsc_out_ticket = mysqli_query($db,$outs_qry);


  if($rsc_out_ticket)
  {        
    http_response_code(200);    
    echo $resp =json_encode(array('status'=>True,'total'=>$out_no_of_records));
    writeToLog("save_data success-> " .$resp); 
  }
  else
  {
    writeToLog("save_data mysql error-> " .mysqli_error($db));
    http_response_code(500);
    echo json_encode(array('status'=>False,'error'=>'internal server error.'));
  }
}

function writeToLog($logmessage){

  $myfile = fopen("log", "a") or die("Unable to open file!");

  fwrite($myfile, $logmessage . PHP_EOL);

  fclose($myfile);


}

?>


