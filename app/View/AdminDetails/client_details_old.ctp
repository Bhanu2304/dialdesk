<script>
var req="This field is required";
var pho="This field is required";
var ema="This field is required";
	
function updateClient(){
	$("#space").hide();
	$("#err").hide();
	if($.trim($("#company_name").val()) ===""){show_error('company_name',req);return false;}
	else if($.trim($("#pincode").val()) ===""){show_error('pincode',req);return false;}
	else if($.trim($("#state").val()) ===""){show_error('state',req);return false;}
	else if($.trim($("#city").val()) ===""){show_error('city',req);return false;}
	else if($.trim($("#reg_office_address1").val()) ===""){show_error('reg_office_address1',req);return false;}
	else if($.trim($("#auth_person").val()) ===""){show_error('auth_person',req);return false;}
	else if($.trim($("#contact_person1").val()) ===""){show_error('contact_person1',req);return false;}
	else if($.trim($("#cp1_phone").val()) ===""){show_error('cp1_phone',req);return false;}
	else if($.trim($("#cp1_email").val()) ===""){show_error('cp1_email',req);return false;}
	else if($.trim($("#is_dd").val()) ===""){show_error('is_dd',req);return false;}
	else{return true;}
}

function show_error(data_id,msg){
	$("#"+data_id).focus();
	$("#"+data_id).after("<br/ id='space'><span id='err' style='color:red;'>"+msg+"</span>");
}

function getCity(path,state,city){
	var state=$("#"+state).val();
	$.ajax({
		type:'post',
		url:path,
		data:{id:state},
		success:function(data){
			$("#"+city).html(data);
		}
	});
}

function checkCharacter(e,t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;
               
            }
            catch (err) {
                alert(err.Description);
            }
}
</script>
<style>
.title{color: #903}
.imgset{width:100px;height:100px;cursor:pointer;margin-left:35px;}

</style>

<link rel="stylesheet" type="text/css" media="screen" href="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" />
<style type="text/css">
    a.fancybox img {
        border: none;
        box-shadow: 0 1px 7px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }

	.form-group select {
    -webkit-appearance: auto;
    -moz-appearance: auto;
    appearance: auto;
}
</style>
<!--
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js"></script>
<script type="text/javascript">
    $(function($){
        var addToAll = false;
        var gallery = true;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();
</script>
-->
<?php 
if(isset($type) && $type=="edit"){$readonly=false;}else{$readonly=true;}

if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status']=="A" || $data['RegistrationMaster']['status']=="D" || $data['RegistrationMaster']['status']=="H" || $data['RegistrationMaster']['status']=="CL"){
    $readonly1=true;
}
else{
  $readonly1=false;  
}

?>
<style>
font{color:#F00; font-size:14px;}
</style>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="#">Client Details</a></li>
</ol>
<div class="page-heading">            
    <h1>Client Details</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2><?php if($readonly ==true){?>View Client Details<?php }else{?>Update Client Details<?php }?></h2>
            </div>
            <div class="panel-body">

                <form action="<?php echo $this->webroot;?>AdminDetails/update_client" method="post" onsubmit="return updateClient();"  accept-charset="utf-8" id="client_form" enctype="multipart/form-data">
            	<span class="title">Company Details-</span>
           		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
        			<tr>
                		<td>
                        	<?php echo $this->Form->hidden('company_id',array('label'=>false,'value'=>isset ($data['RegistrationMaster']['company_id']) ? $data['RegistrationMaster']['company_id'] : ""));?> 
                      		
                                <span style="color: #616161;">Company Name<font>*</font></span>
      						<?php echo $this->Form->input('company_name',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly1,'value'=>isset ($data['RegistrationMaster']['company_name']) ? $data['RegistrationMaster']['company_name'] : ""));?> 
                     	
                        	<span style="color: #616161;">Pin code<font>*</font></span> 
      						<?php echo $this->Form->input('pincode',array('label'=>false,'class'=>'form-control','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'6','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['pincode']) ? $data['RegistrationMaster']['pincode'] : ""));?> 
							
							<span style="color: #616161;">GST No.<font></font></span>
      						<?php echo $this->Form->input('gst_no',array('label'=>false,'class'=>'form-control','maxlength'=>'15','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['gst_no']) ? $data['RegistrationMaster']['gst_no'] : ""));?> 
						</td>
                      
                     	<td>
                        	<span style="color: #616161;">State<font>*</font></span>
							<?php echo $this->Form->input('state',array('label'=>false,'class'=>'form-control','options'=>$state,'style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($stateid['StateMaster']['Id']) ? $stateid['StateMaster']['Id'] : "",'id'=>'state','empty'=>'Select State','onchange'=>'getCity("'.$this->webroot.'AdminDetails/get_city","state","city");'));?>  
  
                     		<span style="color: #616161;">City<font>*</font></span>
				<?php echo $this->Form->input('city',array('label'=>false,'class'=>'form-control','id'=>'city','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['city']) ? $data['RegistrationMaster']['city'] : ""));?>   
                    	</td>
                        
                     	<td>
                      		<span style="color: #616161;">Reg Office Address 1<font>*</font></span> 
      						<?php echo $this->Form->textarea('reg_office_address1',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:220px;height:85px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['reg_office_address1']) ? $data['RegistrationMaster']['reg_office_address1'] : ""));?> 
                     	</td>
                        
                      	<td>
                       		<span style="color: #616161;">Reg Office Address 2</span> 
      						<?php echo $this->Form->textarea('reg_office_address2',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:220px;height:85px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['reg_office_address2']) ? $data['RegistrationMaster']['reg_office_address2'] : ""));?> 
                    	</td>
                 	</tr>
				</table>
                <span class="title">Client Details-</span>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                		<td>
                      		<span style="color: #616161;">Client Name<font>*</font></span> 
      						<?php echo $this->Form->input('auth_person',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:150px;','readonly'=>$readonly1,'value'=>isset ($data['RegistrationMaster']['auth_person']) ? $data['RegistrationMaster']['auth_person'] : ""));?> 
                     	</td>
                     	<td>
                     		
                                <span style="color: #616161;">Designation</span> 
      						<?php echo $this->Form->input('designation',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:150px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['designation']) ? $data['RegistrationMaster']['designation'] : ""));?> 
                    	</td>   
                     	<td>
                     		 <span style="color: #616161;">Phone No<font>*</font></span> 
      						<?php echo $this->Form->input('phone_no',array('label'=>false,'class'=>'form-control','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','style'=>'width:175px;','readonly'=>$readonly1,'value'=>isset ($data['RegistrationMaster']['phone_no']) ? $data['RegistrationMaster']['phone_no'] : ""));?> 
                    	</td> 
                      	<td>
                       		<span style="color: #616161;">Email<font>*</font></span>  
      						<?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:150px;','readonly'=>$readonly1,'value'=>isset ($data['RegistrationMaster']['email']) ? $data['RegistrationMaster']['email'] : ""));?> 
                     	</td>    
                     	<td>
                       		<span style="color: #616161;">Password<font>*</font></span> 
      						<?php echo $this->Form->input('password',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:150px;','readonly'=>'readonly','value'=>isset ($data['RegistrationMaster']['password']) ? $data['RegistrationMaster']['password'] : ""));?> 
                     	</td>  
                 	</tr>
				</table>
                <span class="title">Communication Details-</span>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                		<td>
                      		<span style="color: #616161;">State<font>*</font></span>  
         					<?php echo $this->Form->input('comm_state',array('label'=>false,'class'=>'form-control','options'=>$state,'style'=>'width:160px;','readonly'=>$readonly,'value'=>isset ($stateid['StateMaster']['Id']) ? $stateid['StateMaster']['Id'] : "",'id'=>'comm_state','empty'=>'Select State','onchange'=>'getCity("'.$this->webroot.'AdminDetails/get_city","comm_state","comm_city");'));?>  
      					</td>
                     	<td>
                     		<span style="color: #616161;">City<font>*</font></span> 
                      		<?php echo $this->Form->input('comm_city',array('label'=>false,'class'=>'form-control','id'=>'comm_city','style'=>'width:160px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['city']) ? $data['RegistrationMaster']['city'] : ""));?>   
                        </td>   
                     	<td>
                     		<span style="color: #616161;">Pincode<font>*</font></span>    
      						<?php echo $this->Form->input('comm_pincode',array('label'=>false,'class'=>'form-control','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'6','style'=>'width:140px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['comm_pincode']) ? $data['RegistrationMaster']['comm_pincode'] : ""));?> 
                    	</td> 
                      	<td>
                      		<span style="color: #616161;">Communication Office Address 1<font>*</font></span>  
      						<?php echo $this->Form->textarea('comm_address1',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:200px;height:85px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['comm_address1']) ? $data['RegistrationMaster']['comm_address1'] : ""));?> 
                     	</td>
                      	<td>
                       		<span style="color: #616161;">Communication Office Address 2</span> 
      						<?php echo $this->Form->textarea('comm_address2',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:200px;height:85px;','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['comm_address2']) ? $data['RegistrationMaster']['comm_address2'] : ""));?> 
                    	</td>
                 	</tr>
				</table>
                	<span class="title">Contact Person Details-</span>
                  <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                		<td>
                      		<span style="color: #616161;">Contact Person 1<font>*</font></span> 
      						<?php echo $this->Form->input('contact_person1',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['contact_person1']) ? $data['RegistrationMaster']['contact_person1'] : ""));?> 
                     	</td>
                     	<td>
                     		<span style="color: #616161;">Designation</span> 
      						<?php echo $this->Form->input('cp1_designation',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp1_designation']) ? $data['RegistrationMaster']['cp1_designation'] : ""));?> 
                    	</td>   
                     	<td>
                     		<span style="color: #616161;">Phone No<font>*</font></span>    
      						<?php echo $this->Form->input('cp1_phone',array('label'=>false,'class'=>'form-control','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp1_phone']) ? $data['RegistrationMaster']['cp1_phone'] : ""));?> 
                    	</td> 
               			<td>
                     		<span style="color: #616161;">Email<font>*</font></span>  
      						<?php echo $this->Form->input('cp1_email',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp1_email']) ? $data['RegistrationMaster']['cp1_email'] : ""));?> 
                    	</td>
                 	</tr>
                    
                 	<tr>
                		<td>
                      		<span style="color: #616161;">Contact Person 2<font>*</font></span>  
      						<?php echo $this->Form->input('contact_person2',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['contact_person2']) ? $data['RegistrationMaster']['contact_person2'] : ""));?> 
                     	</td>
                     	<td>
                     		<span style="color: #616161;">Designation</span>   
      						<?php echo $this->Form->input('cp2_designation',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp2_designation']) ? $data['RegistrationMaster']['cp2_designation'] : ""));?> 
                    	</td>   
                     	<td>
                     		<span style="color: #616161;">Phone No<font>*</font></span> 
      						<?php echo $this->Form->input('cp2_phone',array('label'=>false,'class'=>'form-control','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp2_phone']) ? $data['RegistrationMaster']['cp2_phone'] : ""));?> 
                    	</td> 
               			<td>
                     		<span style="color: #616161;">Email<font>*</font></span> 
      						<?php echo $this->Form->input('cp2_email',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp2_email']) ? $data['RegistrationMaster']['cp2_email'] : ""));?> 
                    	</td>
                 	</tr>
                    
                    
                                <tr>
                		<td>
                      		<span style="color: #616161;">Contact Person 3<font>*</font></span>  
      						<?php echo $this->Form->input('contact_person3',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['contact_person3']) ? $data['RegistrationMaster']['contact_person3'] : ""));?> 
                     	</td>
                     	<td>
                     		<span style="color: #616161;">Designation</span>    
      						<?php echo $this->Form->input('cp3_designation',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp3_designation']) ? $data['RegistrationMaster']['cp3_designation'] : ""));?> 
                    	</td>   
                     	<td>
                     		<span style="color: #616161;">Phone No<font>*</font></span>  
      						<?php echo $this->Form->input('cp3_phone',array('label'=>false,'class'=>'form-control','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp3_phone']) ? $data['RegistrationMaster']['cp3_phone'] : ""));?> 
                    	</td> 
               			<td>
                     		<span style="color: #616161;">Email<font>*</font></span> 
      						<?php echo $this->Form->input('cp3_email',array('label'=>false,'class'=>'form-control','maxlength'=>'255','readonly'=>$readonly,'value'=>isset ($data['RegistrationMaster']['cp3_email']) ? $data['RegistrationMaster']['cp3_email'] : ""));?> 
                    	</td>
                 	</tr>
				</table>
               
               <span class="title">Document Details-</span>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                   		
                        <tr>
                            <td><span style="color: #616161;">Incorporation Certificate<font>*</font></span></td>
                            <td><span style="color: #616161;">Pan Card<font>*</font></span></td>
                            <td><span style="color: #616161;">Billing Address Proof<font>*</font></span></td>
                            <td><span style="color: #616161;">Client Id Proof<font>*</font></span></td>
                            <td><span style="color: #616161;">Client Address Proof<font>*</font></span></td>
                        </tr>
                         <?php if(isset($type) && $type=="edit"){?> 
                         <tr>
                             <td><input type="file" multiple="multiple" name="userfile[]" id="upload_doc" style="width:150px;" /></td> 
                             <td><input type="file" multiple="multiple" name="userfile2[]" id="upload_doc2" style="width:150px;" /></td>
                             <td><input type="file" multiple="multiple" name="userfile3[]" id="upload_doc3" style="width:150px;" /></td> 
                             <td><input type="file" multiple="multiple" name="userfile4[]" id="upload_doc4"  style="width:150px;" /></td>
                             <td><input type="file" multiple="multiple" name="userfile5[]" id="upload_doc5"  style="width:150px;" /></td>
                        </tr>
                        <?php }?> 
                       
                    
                    
                    
                		<td>
                        	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
								<?php 
                              	if(isset($data['RegistrationMaster']['incorporation_certificate']) && $data['RegistrationMaster']['incorporation_certificate'] !=""){
                                foreach(explode(",",$data['RegistrationMaster']['incorporation_certificate']) as $row1){?>
                                
                                <?php 
                                $extension = explode('.',$row1);
                                $extension = $extension[count($extension)-1]; 
                                ?>

                                

                              	<tr>
                                	<td>
                                    	<?php if($data['RegistrationMaster']['incorporation_certificate'] !=""){?>
                                    	<a target="_blank" href="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row1;?>">
                                         
                                            <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row1;?>"  />
                                            <?php }?>

                                         	
                                       	</a>
                                        <?php }else{?>
                                        <img class="fancybox imgset" src="<?php echo $this->webroot;?>img_not_available.png"  />
                                        <?php }?>
                                    </td>
                           
         						</tr>      
                              	<?php }}?>
                  			</table>  
      					</td>
                        
                        <td>
                        	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
								<?php 
                              	if(isset($data['RegistrationMaster']['pancard']) && $data['RegistrationMaster']['pancard'] !=""){
                                foreach(explode(",",$data['RegistrationMaster']['pancard']) as $row2){?>
                                 <?php 
                                $extension = explode('.',$row2);
                                $extension = $extension[count($extension)-1]; 
                                ?>
                              	<tr>
                                	<td>
                                         <a target="_blank" href="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row2;?>">
                                            <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                            <img class="fancybox imgset" src="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row2;?>"  />
                                            <?php }?>
                                             </a>
                                    </td>
                           
         						</tr>
                              	<?php }}?>
                  			</table>  
      					</td>
                        
                        <td>
                        	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            	
								<?php 
                              	if(isset($data['RegistrationMaster']['bill_address_prof']) && $data['RegistrationMaster']['bill_address_prof'] !=""){
                                foreach(explode(",",$data['RegistrationMaster']['bill_address_prof']) as $row3){?>
                                 <?php 
                                $extension = explode('.',$row3);
                                $extension = $extension[count($extension)-1]; 
                                ?>
                              	<tr>
                                	<td>
                                        <a target="_blank" href="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row3;?>">
                                         <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>  
                                             <img class="fancybox imgset" src="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row3;?>"  />
                                         <?php }?>
                                        </a>
                                    </td>
                           
         						</tr>
                                 
                              	<?php }}?>
                  			</table>  
      					</td>
                        
                        <td>
                        	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            	
								<?php 
                              	if(isset($data['RegistrationMaster']['authorized_id_prof']) && $data['RegistrationMaster']['authorized_id_prof'] !=""){
                                foreach(explode(",",$data['RegistrationMaster']['authorized_id_prof']) as $row4){?>
                                 <?php 
                                $extension = explode('.',$row4);
                                $extension = $extension[count($extension)-1]; 
                                ?>
                              	<tr>
                                	<td>
                                         <a target="_blank" href="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row4;?>">
                                            <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                            <img class="fancybox imgset" src="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row4;?>"  />
                                               <?php }?>
                                            </a>
                                    </td>
                           
         						</tr>
                               
                              	<?php }}?>
                  			</table>  
      					</td>
                        
                        <td>
                        	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            	
								<?php 
                              	if(isset($data['RegistrationMaster']['auth_person_address_prof']) && $data['RegistrationMaster']['auth_person_address_prof'] !=""){
                                foreach(explode(",",$data['RegistrationMaster']['auth_person_address_prof']) as $row5){?>
                                 <?php 
                                $extension = explode('.',$row5);
                                $extension = $extension[count($extension)-1]; 
                                ?>
                              	<tr>
                                	<td>
                                         <a target="_blank" href="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row5;?>">
                                            <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                            <img class="fancybox imgset" src="<?php echo $this->webroot;?>upload/client_file/client_<?php echo $data['RegistrationMaster']['company_id']."/".$row5;?>"  />
                                            <?php }?>
                                            </a>
                                    </td>
                           
         						</tr>
                                 
                              	<?php }}?>
                  			</table>
                           
      					</td>
                        
                 	</tr>



                     
                       



				</table>


                         <table class="table table-striped table-bordered">
                            <tr>
                                <td>
                                    <input type="radio" name="data[permission]"   <?php if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status'] =="A" ){echo "checked='checked'";} ?> value="A" > Active <br/>
									<input type="radio" name="data[permission]"   <?php if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status'] =="H" ){echo "checked='checked'";} ?> value="H"  > Hold	<br/>							
                                    <input type="radio" name="data[permission]"   <?php if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status'] =="D" ){echo "checked='checked'";} ?> value="D"  > Deactive <br/>
									<input type="radio" name="data[permission]"   <?php if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status'] =="CL" ){echo "checked='checked'";} ?> value="CL"  > Close
                                </td>
                                <td>
                                    <input type="radio" name="data[CsatSms]"   <?php if(isset($data['RegistrationMaster']['CsatSms']) && $data['RegistrationMaster']['CsatSms'] =="1" ){echo "checked='checked'";} ?> value="1" > CSAT Yes <br/>
                                    <input type="radio" name="data[CsatSms]"   <?php if(isset($data['RegistrationMaster']['CsatSms']) && $data['RegistrationMaster']['CsatSms'] =="0" ){echo "checked='checked'";} ?> value="0"  > CSAT No
                                </td>
        
								<td>
									<span style="color: #616161;">Is Dialdesk<font>*</font></span> 
									
									<select name="data[is_dd]"  id="is_dd" class="form-control">
										<option value="">Choose Option</option>
										<option <?php if(isset($data['RegistrationMaster']['is_dd_client']) && $data['RegistrationMaster']['is_dd_client'] =="0" ){echo "selected='selected'";} ?> value="0">No</option>
										<option <?php if(isset($data['RegistrationMaster']['is_dd_client']) && $data['RegistrationMaster']['is_dd_client'] =="1" ){echo "selected='selected'";} ?> value="1">Yes</option>
									</select>
								</td>
								<td>
									<?php //print_r($data); die;?>
									<span style="color: #616161;">Billing Email Id<font>*</font></span> 
                                    <input id="billing_email_id" type="text" placeholder="Billing Email Id" name="data[billing_email_id]" class="form-control"   value="<?php echo $data['RegistrationMaster']['billing_email_id'];?>" > 
                                    
                                </td>
                                
                                
                            </tr>
                            <tr>
								<td>
									<textarea class="form-control" name="data[status_remarks]" <?php if(isset($type) && $type !="edit"){?> readonly <?php }?> ><?php echo isset($data['RegistrationMaster']['status_remarks'])?$data['RegistrationMaster']['status_remarks']:"";?></textarea>
								</td>

								<td>
									<?php //print_r($data); die;?>
									<span style="color: #616161;">Category<font>*</font></span> 
                                    <select name="data[client_category]"  id="client_category" class="form-control">
										<option value="">Choose Option</option>
										<option <?php if(isset($data['RegistrationMaster']['client_category']) && $data['RegistrationMaster']['client_category'] =="A" ){echo "selected='selected'";} ?> value="A">A</option>
										<option <?php if(isset($data['RegistrationMaster']['client_category']) && $data['RegistrationMaster']['client_category'] =="B" ){echo "selected='selected'";} ?> value="B">B</option>
										<option <?php if(isset($data['RegistrationMaster']['client_category']) && $data['RegistrationMaster']['client_category'] =="C" ){echo "selected='selected'";} ?> value="C">C</option>
										<option <?php if(isset($data['RegistrationMaster']['client_category']) && $data['RegistrationMaster']['client_category'] =="D" ){echo "selected='selected'";} ?> value="D">D</option>
									</select>
                                    
                                </td>
							</tr>
                        </table>
                
               
           		<?php if(isset($type) && $type=="edit"){?>
                       
                <input onclick="goBack()" type="button" value="Back" class="btn-web btn"  />
                <input type="submit" value="Update" class="btn-web btn"  />
                <?php }else{?>
                <input onclick="goBack()" type="button" value="Back" class="btn-web btn"  />
                <a style="text-decoration:none;" href="<?php echo $this->webroot;?>AdminDetails/client_details?id=<?php echo $data['RegistrationMaster']['company_id'];?>&type=edit">
                 	<input  type="button" value="Edit" class="btn-web btn"  />
               	</a>
                <a style="text-decoration:none;" href="<?php echo $this->webroot;?>AdminPlans/?id=<?php echo $data['RegistrationMaster']['company_id'];?>">
                 	<input  type="button" class="btn-web btn" value="Go To Plan >>"  />
               	</a> 
               
                <?php }?>
                <?php echo $this->Form->hidden('incorporation_certificate',array('label'=>false,'value'=>isset ($data['RegistrationMaster']['incorporation_certificate']) ? $data['RegistrationMaster']['incorporation_certificate'] : ""));?> 
                <?php echo $this->Form->hidden('pancard',array('label'=>false,'value'=>isset ($data['RegistrationMaster']['pancard']) ? $data['RegistrationMaster']['pancard'] : ""));?> 
                <?php echo $this->Form->hidden('bill_address_prof',array('label'=>false,'value'=>isset ($data['RegistrationMaster']['bill_address_prof']) ? $data['RegistrationMaster']['bill_address_prof'] : ""));?> 
                <?php echo $this->Form->hidden('authorized_id_prof',array('label'=>false,'value'=>isset ($data['RegistrationMaster']['authorized_id_prof']) ? $data['RegistrationMaster']['authorized_id_prof'] : ""));?> 
                <?php echo $this->Form->hidden('auth_person_address_prof',array('label'=>false,'value'=>isset ($data['RegistrationMaster']['auth_person_address_prof']) ? $data['RegistrationMaster']['auth_person_address_prof'] : ""));?> 
        	
                    </form>
                
            </div>
        </div>
    </div>
</div>







