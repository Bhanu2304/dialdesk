<?php ?>

<style>
        div.toggler				{ border:1px solid #ccc; background:url(gmail2.jpg) 10px 12px #eee no-repeat; cursor:pointer; padding:10px 32px; }
div.toggler .subject	{ font-weight:bold; }
div.read					{ color:#666; }
div.toggler .from, div.toggler .date { font-style:italic; font-size:11px; }
div.body					{ padding:10px 20px; }
    </style>
    <script>
        window.addEvent('domready',function() {
	var togglers = $$('div.toggler');
	if(togglers.length) var gmail = new Fx.Accordion(togglers,$$('div.body'));
	togglers.addEvent('click',function() { this.addClass('read').removeClass('unread'); });
	togglers[0].fireEvent('click'); //first one starts out read
});
    </script>
    
   
   <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
    <thead>
        <tr>
            <!--<th>Read<br/>Unread</th>-->
            <th>Subject</th>
            <th>From</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $output = '';
    foreach($emails as $row) {
        /* get information specific to this email */

            $id = $row['MailMaster']['clientId'];
            $email_number = $row['MailMaster']['Id'];
            $url=$this->webroot."Agents?postid=".$email_number."&posttype=emailbox";
            
            /* output the email header information */
        //$output .= '<tr><td>'.($row['MailMaster']['mail_status'] ? 'unread' : 'read').'</td>';
        $output.=  '<td><span class="subject">'.$row['MailMaster']['mail_subject'].'</span></td>';
        $output.=  '<td><span class="from">'.$row['MailMaster']['mail_from'].'</span></td>';
        $output.=  '<td><span class="date">'.$row['MailMaster']['createdate'].'</span></td>';
        $output.=  '<td><span class="date">'.$row['MailMaster']['status'].'</span></td>';
        $output.=  '<td>
                    <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate1" onclick="view_client_open_mails('.$id.',\''.$email_number.'\',\'tab1\')" >
                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">open_in_new</i></label>
                    </a>
                    <a  href="'.$url.'" title="ADD SR" >                      
                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">library_add</i></label>
                    </a>
                    </td>';
        $output.=  '</tr>';
            /* output the email body */
        //$output.= '<div class="body">'.$message.'</div>';
    }

    echo $output;
    ?>

    </tbody>
</table>

    
