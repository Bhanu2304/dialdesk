<?php ?>

<script>tinymce.init({ selector:'textarea' });</script>

<!--
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
-->  

<?php echo $this->Form->create('emails',array('class'=>'form-horizontal','action'=>'send_mail')); ?> 

<?php 
$id = $emails['MailMaster']['clientId'];
if(strstr($emails['MailMaster']['mail_from'],'<')){
    $to = substr_replace($emails['MailMaster']['mail_from'],'',0,strpos($emails['MailMaster']['mail_from'],'<'));
    $to = str_replace('<','',str_replace('>','',$to));
}
else{
    $to = $emails['MailMaster']['mail_from'];
}
$sub = $emails['MailMaster']['mail_subject'];


?>


<div class="form-group" style="margin-top: -20px;" >
    <!--
    <label class="col-sm-2 control-label">Subject</label>
    <div class="col-sm-10" style="margin-top:20px;">
       <?php echo $emails['MailMaster']['mail_subject']; ?>
    </div> 
   
    <label class="col-sm-2 control-label">From</label>
    <div class="col-sm-10" style="margin-top:20px;">
       <?php echo $emails['MailMaster']['mail_from']; ?>
    </div> 
    <label class="col-sm-2 control-label">Date</label>
    <div class="col-sm-10" style="margin-top:20px;">
       <?php echo $emails['MailMaster']['createdate']; ?>
    </div> 
     
    <label class="col-sm-2 control-label">Message</label>
    <div class="col-sm-10" style="margin-top:20px;" id="div1">
       <?php echo quoted_printable_decode($emails['MailMaster']['mail_message']); ?>
    </div> 
    -->
    <?php echo quoted_printable_decode($emails['MailMaster']['mail_message']); ?>
   
</div>


 

<div class="form-group">    
    <label class="col-sm-2 control-label">&nbsp;</label>
    <div class="col-sm-8">
    <span onclick="replyUsers('<?php echo isset($emails['MailMaster']['reply_to'])?$emails['MailMaster']['reply_to']:"";?>','','','reply')" ><label class="btn btn-xs tn-midnightblue btn-raised">Reply <i class="material-icons">reply</i></label></span>
    <span onclick="replyUsers('<?php echo isset($emails['MailMaster']['reply_to'])?$emails['MailMaster']['reply_to']:"";?>','<?php echo isset($emails['MailMaster']['mail_cc'])?$emails['MailMaster']['mail_cc']:"";?>','<?php echo isset($emails['MailMaster']['mail_bc'])?$emails['MailMaster']['mail_bc']:"";?>','replyall')" ><label class="btn btn-xs tn-midnightblue btn-raised">Reply All <i class="material-icons">reply_all</i></label></span>
    <span onclick="replyUsers('','','','forward')" ><label class="btn btn-xs tn-midnightblue btn-raised">Forward <i class="material-icons">forward</i></label></span>
    </div>
</div>



<div id="show-to-cc-bcc" style="display:none;" >
    <div class="form-group">    
        <label class="col-sm-2 control-label">To</label>
        <div class="col-sm-8">
            <input type="hidden" class="form-control" id="sendtype" name="sendtype">
            <input type="hidden" class="form-control" id="MailerName" name="MailerName" value="<?php echo isset($emails['MailMaster']['mailer_name'])?$emails['MailMaster']['mailer_name']:"";?>" >
            <input class="form-control" id="toemail" name="toemail" placeholder="To">
        </div>
    </div>

    <div class="form-group">    
        <label class="col-sm-2 control-label">Cc</label>
        <div class="col-sm-8">
            <input class="form-control" id="ccemail" value="<?php echo isset($emails['MailMaster']['mail_cc'])?$emails['MailMaster']['mail_cc']:"";?>" name="ccemail" placeholder="Cc">
        </div>
    </div>
    
    <!--
    <div class="form-group">    
        <label class="col-sm-2 control-label">Bcc</label>
        <div class="col-sm-8">
            <input class="form-control" id="bccemail" value="<?php echo isset($emails['MailMaster']['mail_cc'])?$emails['MailMaster']['mail_bc']:"";?>" name="bccemail" placeholder="Bcc">
        </div>
    </div>
    -->

<div class="form-group" >    
    <label class="col-sm-2 control-label">Body</label>
    <div class="col-sm-8">
        <!--
        <textarea class="form-control" name="message" id="message" placeholder="Reply Mail" required="" ></textarea>
       -->
        <?php echo $this->Form->textarea('message',array('label'=>false,'placeholder'=>'Add Details','autocomplete'=>'off','id'=>'message','class'=>'form-control'));?>
    </div>
</div>


<div class="form-group"  > 
    <label class="col-sm-2 control-label"></label>
    <div class="col-sm-2">
        <input type="submit"  name="submit" value="submit" class="form-control" />
    </div>
    <div class="col-sm-2"> 
        <input type="hidden"  name="mailmasterid" value="<?php echo $emails['MailMaster']['Id']; ?>" class="form-control" />
        <input type="hidden"  name="id" value="<?php echo $id; ?>" class="form-control" />
        <input type="hidden"  name="to" value="<?php echo $to; ?>" class="form-control" />
        <input type="hidden"  name="subject" value="<?php echo $sub; ?>" class="form-control" />
    </div>
</div>
    
</div>
<?php echo $this->Form->end(); ?>
             