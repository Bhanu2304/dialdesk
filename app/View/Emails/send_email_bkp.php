<div class="modal-body  ">
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">

<?php
require 'mailer/PHPMailerAutoload.php';

print_r($host); exit;
echo $email     = $host['EmailMaster']['email'];
echo $password  = $host['EmailMaster']['password'];
echo $to_id     = $to;
echo $subject   = $sub;
echo $hostname  = $host['EmailMaster']['send_hostname'];
echo $port      = $host['EmailMaster']['send_port'];

if($host['EmailMaster']['Id']=='2')
{
    $mail = new PHPMailer;
    $mail->isSMTP();
    
    $mail->Host = $hostname;
    $mail->Port = $host['EmailMaster']['send_port'];
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $password;
    $mail->addAddress($to_id);
    $mail->Subject = $subject;
    $mail->msgHTML($message);
    
    if (!$mail->send()) 
        {
            $error = "Mailer Error: " . $mail->ErrorInfo;
            echo '<p id="para">'.$error.'</p>';
        }
    else {
            echo '<p id="para">Message sent!</p>';
        }
}
else
{
    $mail = new PHPMailer;
//$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = $hostname;
    $mail->Port = $port;
    $mail->From = $email;
    $mail->To = $email;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $password;
    $mail->addAddress($to_id);
    $mail->Subject = $subject;
    $mail->msgHTML($message);

    if (!$mail->send()) 
        {
            $error = "Mailer Error: " . $mail->ErrorInfo;
            echo '<p id="para">'.$error.'</p>';
        }
    else {
            echo '<p id="para">Message sent!</p>';
        }
}
?>
            </div>
        </div>
    </div>    
</div>