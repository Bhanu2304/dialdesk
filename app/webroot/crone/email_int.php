<?php
$con1           =   mysql_connect("localhost","root","dial@mas123",'db_dialdesk');
$Sel = mysql_query("SELECT email_no+1 cnt FROM db_dialdesk.email_data order by id desc limit 1");
$Dat = mysql_fetch_row($Sel);
echo $Cnt = $Dat[0];
echo "<br>"; 

  $username = 'contact@perfectimpression.in'; 
  #$password = 'renuka@123';
  $password = 'TPIF@2023!'; 
//$hostname = 'mail.perfectimpression.in';
$hostname = "{mail.perfectimpression.in:993/imap/ssl/novalidate-cert}INBOX"; 
$port = 993; 
 $imap = imap_open($hostname,$username,$password) or die('Cannot connect to mail: ' . imap_last_error());
//echo imap_body($imap, $Cnt);
 //echo    $n_msgs = imap_num_msg($imap).'<br/><br/>';
 if( $imap ) {

      $num = imap_num_msg($imap);
      if( $num >0 ) {
          $body_parts = explode('+91',imap_qprint(imap_body($imap, $Cnt)));
          $Namarr =  explode(',',$body_parts[0]);
          $phone_number =  trim(substr(trim($body_parts[1]),0,10)) ;
          $name = mysql_real_escape_string(trim(strstr($Namarr[1], "inquired", true)));
          $Dt = explode(':',quoted_printable_decode(imap_fetchbody($imap,$Cnt,1))); 
          print_r($Dt);
          $City = trim(str_replace('User State','',$Dt[2]));
          $Email = trim(strstr($Dt[9], "Send", true));
          $Req = addslashes(trim(strstr($Dt[4], "Search", true)));
          
          //echo "insert into db_dialdesk.email_data set client_id='469',email_no='$Cnt',contact_no='$phone_number',Name='$name',city='$City',email='$Email',requierment='$Req'";
          //echo "<br>";
  
          if($phone_number!='') {
            
              $Ins = mysql_query("insert into db_dialdesk.email_data set client_id='469',email_no='$Cnt',contact_no='$phone_number',Name='$name',city='$City',email='$Email',requierment='$Req'",$con1) or die(mysql_error()) ; 
  
          }else if(!empty($Dt[0])){
            echo "!empty" ;
            $Ins = mysql_query("update db_dialdesk.email_data set email_no='$Cnt' order by id desc limit 1",$con1) or die(mysql_error());  

          }else{
            echo "empty";
          }
      }
      imap_close($imap);
 
 }

?>