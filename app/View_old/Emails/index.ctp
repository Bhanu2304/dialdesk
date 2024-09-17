<?php ?>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
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
<style>
    #tab1{display: none;}
    #tab2{display: none;}
    #tab3{display: none;}
    #tab4{display: none;}
</style>
<script>
    
    
    
function view_client_mails(id,type){
    window.location.href ="<?php echo $this->webroot;?>Emails?id="+id;
    
    /*
    $.post("<?php echo $this->webroot;?>Emails/view_client_mail",{id:id,type:type},function(data){
        $("#ae-data").html(data);
    }); 
    */
}




function view_client_open_mails(id,email_no,type)
{
    $.post("<?php echo $this->webroot;?>Emails/view_client_open_mail",{id:id,email_no:email_no,type:type},function(data){
    $("#ae-data1").html(data);
    }); 
}

function reloadmail(){
    $.post("<?php echo $this->webroot;?>Emails/import_mail",function(data){
        location.reload();
    }); 
}

function caseClose(postid,posttype){
    $.post('<?php echo $this->webroot;?>SocialMedia/case_close',{postid:postid,posttype:posttype},function(result){
        if(result){ 
           window.location.reload();
        } 
    });
}

function run_import_mail(){
    $("#loadingimg").show();
    $.post('<?php echo $this->webroot;?>Emails/import_mail',function(result){
        if(result){ 
           window.location.reload();
        } 
    });
}

function replyUsers(emailid,emailcc,emailbcc,type){
    $("#show-to-cc-bcc").show();
    $("#sendtype").val(type);
   
    if(type =="reply"){
        $("#toemail").val(emailid);
        $("#ccemail").val(''); 
        //$("#bccemail").val(''); 
    }
    else if(type =="replyall"){
        $("#toemail").val(emailid);
        $("#ccemail").val(emailcc);
        //$("#bccemail").val(emailbcc);
    }
    else{
       $("#toemail").val('');
       $("#ccemail").val(''); 
      // $("#bccemail").val(''); 
    } 
}

</script>

<style>
.table th,td{font-size: 11px;}
.circle {
  border-radius: 50%;
  font-size:15px;
  color: #fff;
  text-align: center;
  background: #878787;
}
table.fixed{ 
    table-layout: fixed;
    width:600px;
}
table.fixed td { 
    overflow: hidden;
}
</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>">Home</a></li>
    <li><a >Social Media</a></li>
    <li class="active"><a href="#">Mail Inbox</a> <a href="" style="" >Reload</a></li>
    <li><a onclick="reloadmail()" style="float:right" >Reload</a> </li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div style="font-size: 15px;color:green;padding-bottom: 10px;"><?php echo $this->Session->flash();?></div>
        <div class="col-md-4" >
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Mail Box</h2>
                    <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>

                    </div>
                </div>
                <div class="panel-body">
                    
                    <a href="#" onclick="run_import_mail()" title="Reload Mial">
                        <label class="btn btn-xs tn-midnightblue btn-raised">RELOAD MAIL<i class="material-icons">autorenew</i></label>
                    </a>
                    <img id="loadingimg" style="display:none;" src="<?php echo $this->webroot;?>images/loading.gif" >
                    
                    <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped">
                        <?php 
                        foreach($emailArr as $row) {
                        $id = $row['client_mails']['clientId'];
                        ?>
                        <tr>
                            <td style="cursor:pointer;" onclick="view_client_mails('<?php echo $id;?>','tab1'),run_import_mail()" >
                                <?php echo $row['user_email_server_details']['email'];?>
                                <span class="circle"><?php echo ($row['0']['read']+$row['0']['unread']);?></span>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
               
        <div class="col-md-8">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Mail Details</h2>
                    <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>

                    </div>
                </div>
               <div class="panel-body no-padding">
                    <div style="height: 700px;overflow: scroll;">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>From</th>
                            <th>Sub</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                         </thead>
                        <tbody>
                        <?php 
                        $i=1;
                        foreach($emails as $row){
                            $id = $row['client_mails']['clientId'];
                            $email_number = $row['client_mails']['Id'];
                            $url=$this->webroot."Agents?postid=".$email_number."&posttype=emailbox";
                        ?>
                       
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['client_mails']['mail_from'];?></td>
                            <td><?php echo $row['client_mails']['mail_subject'];?></td>
                            <td><?php echo $row['client_mails']['createdate'];?></td>
                            <td><?php echo $row['client_mails']['status'];?></td>
                            <td>
                                <a title="Open/Reply"  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate1" onclick="view_client_open_mails('<?php echo $id;?>','<?php echo $email_number;?>')" >
                                    <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-send"></i></label>
                                </a>

                                <a target="_blank" href="<?php echo $url;?>" title="ADD SR"  >
                                    <label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">library_add</i></label>
                                </a>
                                <a href="#" onclick="caseClose('<?php echo $email_number;?>','emailbox')" title="Close"  >
                                    <span><label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">delete_forever</i></label></span>
                                </a>
                                
                            </td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    </div>
                
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="modal fade" id="esclationUpdate1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:45px;left:50px;width:750px;" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">View Mail</h4>
            </div>
           
            <div class="modal-body" >
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <div id="ae-data1" style="overflow:scroll;height: 600px;" ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



