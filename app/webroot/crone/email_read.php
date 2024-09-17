<?php


// Email credentials
// $username = 'thakurbhanuchauhan@gmail.com';
// $password = 'Bhanu2304';
$username = 'bhanu.singh@teammas.in';
$password = 'Mas@2304';
$hostname = '{pop.teammas.in:110/pop3/novalidate-cert}INBOX'; 
#$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';

$hostname = '{pop.teammas.in:995/pop3/ssl}INBOX'; // For POP3 with SSL
// OR
$hostname = '{imap.teammas.in:993/imap/ssl}INBOX'; // For IMAP with SSL

#imap_timeout(IMAP_OPENTIMEOUT, 15);
#imap_errors();
#imap_alerts();
#define('IMAP_DEBUG', true);
// Connect to the IMAP server
#$imap_server = '{pop.teammas.in:993/pop3/ssl}INBOX';
#$imap_server = '{pop.teammas.in:993/pop3/ssl/novalidate-cert}INBOX';
$imap_server = '{pop.teammas.in:110/pop3/novalidate-cert}INBOX';
$imap_username = 'bhanu.singh@teammas.in';
$imap_password = 'Mas@2304';







$imap_stream = imap_open($imap_server, $imap_username, $imap_password) or die('Cannot connect to the mail server: ' . imap_last_error());

echo "he;ll";
$emails = imap_search($imap_stream, 'UNSEEN');

if ($emails) {
    // Loop through each email
    foreach ($emails as $email_number) {
        // Fetch the email header
        $header = imap_headerinfo($inbox, $email_number);

        // Fetch the email body
        $body = imap_fetchbody($inbox, $email_number, 1);

        // Output email information
        echo "From: " . $header->fromaddress . "<br>";
        echo "Subject: " . $header->subject . "<br>";
        echo "Body: " . $body . "<br><br>";

        // Mark the email as read
        imap_setflag_full($inbox, $email_number, "\\Seen");
    }
}

// Close the connection
imap_close($inbox);
?>
