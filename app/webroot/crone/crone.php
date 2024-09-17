<?php
	
$txt = "
### ### add job according to client based
*/1 * * * * php /var/www/html/dialdesk/app/webroot/crone/job.php";
 
 
 //system('crontab -u apache -e');
 
 //system('crontab -u exampleuser -r');
 
//system('crontab -u www-data -e');
//escapeshellcmd('chmod g+w /var/spool/cron/root');
$myfile = file_put_contents('/var/spool/cron/apache', $txt.PHP_EOL , FILE_APPEND);
echo readfile('/var/spool/cron/apache');
chmod('/var/spool/cron/apache2',0600);
//chmod('/var/spool/cron/apache2',0600);
?>