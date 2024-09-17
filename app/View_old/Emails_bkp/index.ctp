
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Email Details</a></li>
    <li class="active"><a href="#">Email Inbox</a></li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Email Details</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
           <?php
foreach($email as $row) 
{
    $hostname = $row['EmailMaster']['inbox_hostname'];
    $username = $row['EmailMaster']['email'];
    $password = $row['EmailMaster']['password'];

    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail: ' . imap_last_error());

    /* grab emails */
    $emails = imap_search($inbox,'NEW'); 
    echo 'inbox('.count($emails).')<br>';
    /* if emails are returned, cycle through each... */
    
    if($emails) 
    {
	/* begin output var */
	$output = '';
	
	/* put the newest emails on top */
	rsort($emails);
	
	/* for every email... */
	foreach($emails as $email_number) 
        {	
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox,$email_number,0);
            $message = imap_fetchbody($inbox,$email_number,2);
		
		/* output the email header information */
            $output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
            $output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
            $output.= '<span class="from">'.$overview[0]->from.'</span>';
            $output.= '<span class="date">on '.$overview[0]->date.'</span>';
            $output.= '</div>';
		
		/* output the email body */
            $output.= '<div class="body">'.$message.'</div>';
	}
	
	//echo $output;
} 

/* close the connection */
imap_close($inbox); 
}   
                    


           ?>
           
            
           
                
           
            
           
            
           
                
           
                
           
            
        </div>
        <div class="panel-footer"></div>
    </div>
   
        
    </div>
    
</div>
 
