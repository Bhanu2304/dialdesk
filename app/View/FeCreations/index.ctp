<script>
function view_fe_edit_login(id){
    $.post("FeCreations/view_edit_login",{id:id},function(data){
        $("#user-data").html(data);
    }); 
}

function editFeLoginForm(path,purl,emailurl){ 
    var name=$('#login_name').val();
    var emailid=$('#login_emailid').val();
    var phone=$('#login_phone').val();
    var designation=$('#login_designation').val();
    var password = $("#login_password").val();
    var confirm_password = $("#login_confirm_password").val();
    var loginid = $("#loginid").val();
    
  	
    var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
    var aIds = [];
    for(var x = 0, l = all_location_id.length; x < l;  x++){
     aIds.push(all_location_id[x].value);
    }
	 
    if($.trim(name)===""){
        $('#login_name').focus();
        $("#elMsg").html('Name field is required.').show();  
        return false;
    }
    else if(!lengthrange(name,50)){
        $('#login_name').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    else if(!specialchar(name)){
        $('#login_name').focus();
        $("#elMsg").html(sepchar_err).show();  
        return false;
    }
    else if(!allLetter(name)){
        $('#login_name').focus();
        $("#elMsg").html(letter_err).show();  	
        return false;
    }
    else if($.trim(emailid)===""){
        $('#login_emailid').focus();
        $("#elMsg").html('Email field is required.').show();  
        return false;
    }
    else if(!lengthrange(emailid,100)){
        $('#login_emailid').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    else if (!filter.test($.trim(emailid))) {
        $('#login_emailid').focus();
        $("#elMsg").html(email_err).show(); 
        return false;
    }
    else if($.trim(emailid) !="" && existFeEmail(loginid,emailid) !=''){
        $('#login_emailid').focus();
        $("#elMsg").html('<span>This email id is already exist.</span>').show();
        return false;
    }
    else if($.trim(password)===""){
        $('#login_password').focus();
        $("#elMsg").html('Password field is required.').show();  
        return false;
    }
    else if(!lengthrange(password,50)){
        $('#login_password').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    else if($.trim(confirm_password)===""){
        $('#login_confirm_password').focus();
        $("#elMsg").html('Confirm password field is required.').show();  
        return false;
    }
    else if($.trim(password) != $.trim(confirm_password)) {
        $('#login_confirm_password').val('');
        $('#login_confirm_password').focus();
        $("#elMsg").html(pass_err).show();  
        return false;
            }
    else if($.trim(phone)===""){
        $('#login_phone').focus();
        $("#elMsg").html('Phone field is required.').show();  
        return false;
    }
    else if(!$.trim(phone).match(phoneNum)) {
        $('#login_phone').focus();
        $("#elMsg").html(phone_err).show(); 
        return false;
    }
    else if($.trim(phone) !="" && $.trim(phone).charAt(0)==="0") {
        $('#login_phone').focus();
        $("#elMsg").html(phone_err).show(); 
        return false;
    }
    else if($.trim(phone) !="" && existFePhone(loginid,phone) !=''){
        $('#login_phone').focus();
        $("#elMsg").html('<span>This number is already exist.</span>').show();
        return false;
    }
    else if($.trim(designation)===""){
        $('#login_designation').focus();
        $("#elMsg").html('Designation field is required.').show();  
        return false;
    }
    else if(!lengthrange(designation,50)){
        $('#login_designation').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    else if(!specialchar(designation)){
        $('#login_designation').focus();
        $("#elMsg").html(sepchar_err).show();  
        return false;
    }
    /*
    else if(aIds.length ==0){
        $("#elMsg").html('Please assign user right.').show(); 
        return false;
    }*/
    else{
       $.ajax({
            type: "POST",
            url:path,
            data: {loginid:loginid,name:name,email:emailid,phone:phone,designation:designation,page_assign:aIds,password:password},
            success: function(data){
                    if(data !=''){
                        $("#close-login-popup").trigger('click');
                        $("#show-login-message").trigger('click');
                        $("#login-text-message").text('Thank you fe login has been updated successfully.');
                    }
            }
	});
        return true;
    }  
}

function hidepopup(){
    location.reload(); 
}

function validateFeCreation(phurl,mailurl){
	var name=$('#name').val();
	var emailid=$('#emailid').val();
	var phone=$('#phone').val();
	var designation=$('#designation').val();
	var eurl=$('#eurl').val();
	var eeurl=$('#eeurl').val();
	var purl=$('#purl').val();
	var phoneverify=$('#phoneverify').val();
	var password = $("#password").val();
	var confirm_password = $("#confirm_password").val();
	
	var next_step = true;
	
	var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
	
	if($.trim(name)===""){
		$('#name').focus();
		$("#erroMsg").html('Name field is required.').show();  
		next_step = false;
	}
	else if(!lengthrange(name,50)){
                $('#name').focus();
		$("#erroMsg").html(length_err).show();  
		next_step = false;
	}
	else if(!specialchar(name)){
                $('#name').focus();
		$("#erroMsg").html(sepchar_err).show();  
		next_step = false;
	}
        else if(!allLetter(name)){
            $('#name').focus();
            $("#erroMsg").html(letter_err).show();  	
            return false;
	}
	else if($.trim(emailid)===""){
		$('#emailid').focus();
		$("#erroMsg").html('Email field is required.').show();  
		next_step = false;
	}
	else if(!lengthrange(emailid,100)){
		$('#emailid').focus();
		$("#erroMsg").html(length_err).show();  
		next_step = false;
	}
	else if (!filter.test($.trim(emailid))) {
		$('#emailid').focus();
		$("#erroMsg").html(email_err).show(); 
		next_step = false;
	}
        else if($.trim(emailid) !="" && existFeData(mailurl,emailid) !=''){
                $('#emailid').focus();
                $("#erroMsg").html('<span>This email id is already exist.</span>').show();
		return false;
	}
	else if($.trim(password)===""){
		$('#password').focus();
		$("#erroMsg").html('Password field is required.').show();  
		next_step = false;
	}
	else if(!lengthrange(password,50)){
		$('#password').focus();
		$("#erroMsg").html(length_err).show();  
		next_step = false;
	}
	else if($.trim(confirm_password)===""){
		$('#confirm_password').focus();
		$("#erroMsg").html('Confirm password field is required.').show();  
		next_step = false;
	}
	else if($.trim(password) != $.trim(confirm_password)) {
		$('#confirm_password').val('');
                $('#confirm_password').focus();
		$("#erroMsg").html(pass_err).show();  
		next_step = false;
		}
	else if($.trim(phone)===""){
		$('#phone').focus();
		$("#erroMsg").html('Phone field is required.').show();  
		next_step = false;
	}
	else if(!$.trim(phone).match(phoneNum)) {
		$('#phone').focus();
		$("#erroMsg").html(phone_err).show(); 
		next_step = false;
	}
        else if($.trim(phone) !="" && $.trim(phone).charAt(0)==="0") {
                $('#phone').focus();
		$("#erroMsg").html(phone_err).show(); 
		return false;
	}
        else if($.trim(phone) !="" && existFeData(phurl,phone) !=''){
                $('#phone').focus();
                $("#erroMsg").html('<span>This number is already exist.</span>').show();
		return false;
	}
	else if($.trim(designation)===""){
		$('#designation').focus();
		$("#erroMsg").html('Designation field is required.').show();  
		next_step = false;
	}
	else if(!lengthrange(designation,50)){
		$('#designation').focus();
		$("#erroMsg").html(length_err).show();  
		next_step = false;
	}
	else if(!specialchar(designation)){
		$('#designation').focus();
		$("#erroMsg").html(sepchar_err).show();  
		next_step = false;
	}
        /*
	else if(aIds.length ==0){
		$("#erroMsg").html('Please assign user right.').show(); 
		next_step = false;
	}*/	
	else{
            if( next_step == true ) {	
                    $.ajax({
                        type: "POST",
                        url: eeurl,
                        data:{email:emailid}, 
                        success:function(data){  
                                if(data ==''){
                                    saveLoginCreation();
                                }
                                else{
                                    $('#emailid').focus();
                                    $("#erroMsg").html(email_exist_err).show();  
                                    return false;
                                }            
                        }   
                });
                return false;
            }
	}
}

function saveLoginCreation(){
	$('#ckloder').show();
	var name=$('#name').val();
	var emailid=$('#emailid').val();
	var phone=$('#phone').val();
	var designation=$('#designation').val();
	var password=$('#password').val();
	var loginurl=$('#loginurl').val();
	var saveurl=$('#saveLogin').val();
	
	var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
	
        var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
	
	$.ajax({
		type: "POST",
		url:saveurl,
		data: {name:name,email:emailid,phone:phone,designation:designation,page_assign:aIds,password:password},
		success: function(data){
			if(data !=''){
				$('#ckloder').hide();
                                $("#show-login-message").trigger('click');
                                $("#login-text-message").text('Thank you fe login has been created successfully.');
                                
				//alert('Login process complete.Password sent successfully on agent mail.');
				//window.location.href = loginurl;
			}
			else{
				$('#ckloder').hide();
                                $("#show-login-message").trigger('click');
                                $("#login-text-message").text('Sorry your email process failed. Try after sometime.');
				//alert('Your email process failed. Try after sometime.');
				//window.location.href = loginurl;
			}
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

function existFeData(path,data){
    var posts = $.ajax({type: 'POST',url: path,async: false,dataType: 'json',data: {getdata:data},done: function(response) {return response;}}).responseText;	
    return posts;
}

function existFePhone(id,data){
    var posts = $.ajax({type: 'POST',url: 'FeCreations/edit_exist_phone',async: false,dataType: 'json',data: {getdata:data,id:id},done: function(response) {return response;}}).responseText;	
    return posts;
}

function existFeEmail(id,data){
    var posts = $.ajax({type: 'POST',url: 'FeCreations/edit_exist_email',async: false,dataType: 'json',data: {getdata:data,id:id},done: function(response) {return response;}}).responseText;	
    return posts;
}



</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Fe Logins</a></li>
</ol>
<div class="page-heading">            
    <h1>Manage Fe Logins</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage Fe Logins</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('FeCreations',array('action'=>'save_login','id'=>'save_login_creation')); ?>
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <div id="erroMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>      
                            </div>
                        </div>
                    </div>
               
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('name',array('label'=>false,'id'=>'name','placeholder'=>'Name','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('password',array('label'=>false,'id'=>'password','placeholder'=>'Password','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->form->input('phone',array('label'=>false,'placeholder'=>'Phone No','maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','id'=>'phone','autocomplete'=>'off'));?>
                           </div>
                        </div>
                    </div>
                        
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('emailid',array('label'=>false,'id'=>'emailid','placeholder'=>'Email Address','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="password" id="confirm_password" placeholder='Confirm Password' class="form-control" autocomplete='off'  />
                           </div>
                        </div>
                        
                         <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->form->input('designation',array('label'=>false,'id'=>'designation','placeholder'=>'Designation','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                    </div>
                    
                    <!--
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">User Right</label>
                                <div class="col-sm-1">
                                    <div class="assign-right" style="width: 595px;margin-left:-100px;">
                                        <ol class="user-tree">
                                            <?php //echo $UserRight;?>                                    
                                        </ol>
                                    </div>                              
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                    

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-8">
                                <div class="btn-toolbar">
                                    <input type="button" onclick="validateFeCreation('<?php echo $this->webroot;?>FeCreations/check_exist_phone','<?php echo $this->webroot;?>FeCreations/checkexistmail')" class="btn btn-web" value="Submit" >
                                    <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="phoneverify" value=""  />
                    <input type="hidden" id="eurl" value="<?php echo $this->webroot;?>FeCreations/checkSmtpValidation"  />
                    <input type="hidden" id="eeurl" value="<?php echo $this->webroot;?>FeCreations/check_exist_email" />
                    <input type="hidden" id="purl" value="<?php echo $this->webroot;?>FeCreations/sendotp" />
                    <input type="hidden" id="loginurl" value="<?php echo $this->webroot;?>FeCreations" />
                    <input type="hidden" id="saveLogin" value="<?php echo $this->webroot;?>FeCreations/save_login_creation" />
                <?php $this->Form->end(); ?>
            </div>
        </div> 
       
        <div class="row">
            <div class="col-md-12"> 
                <?php if(!empty($data)){?>
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>VIEW LOGIN</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>NAME</th>
                                    <th>PHONE NO</th>
                                    <th>EMAIL</th>
                                    <th>DESIGNATION</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;foreach($data as $row){?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $row['FecreationMaster']['name'];?></td>
                                        <td><?php echo $row['FecreationMaster']['phone'];?></td>
                                        <td><?php echo $row['FecreationMaster']['username'];?></td>
                                        <td><?php echo $row['FecreationMaster']['designation'];?></td>
                                        <td>
                                            <a  href="#" data-toggle="modal" data-target="#loginUpdate" onclick="view_fe_edit_login('<?php echo $row['FecreationMaster']['id'];?>')" >
                                                <label class="btn btn-xs btn-midnightblue btn-raised">
                                                    <i class="fa fa-edit"></i><div class="ripple-container"></div>
                                                </label>
                                            </a> 
                                            <a href="#" onclick="deleteData('<?php echo $this->webroot;?>FeCreations/delete_user?id=<?php echo $row['FecreationMaster']['id'];?>')" >
                                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                            </a>
                                        </td>  
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer"></div>
                </div>
                <?php }?>  
            </div>
        </div> 
        
    </div>  
</div>
<!-- Edit Login Message Popup -->
<a class="btn btn-primary btn-lg" id="show-login-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h2 class="modal-title">Message</h2>
                    </div>
                    <div class="modal-body">
                        <p id="login-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<!-- Edit Login Popup -->
<div class="modal fade" id="loginUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Login</h2>
            </div>
           <?php echo $this->Form->create('FeCreations',array('action'=>'update_login')); ?>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <div id="user-data" ></div> 
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
               <input type="button" onclick="return editFeLoginForm('<?php echo $this->webroot;?>FeCreations/update_login')"  value="Submit" class="btn-web btn">
            </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

