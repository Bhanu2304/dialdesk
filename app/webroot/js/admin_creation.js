function view_edit_admin(id){
    $.post("AdminUsers/view_edit_login",{id:id},function(data){
        $("#user-data").html(data);
    }); 
}

function view_edit_field(id){ //alert(id);
    $.post("MobUsers/view_edit_field",{Id:id},function(data){
        $("#user-data1").html(data);
    }); 
}


function validateAdminCreation(phurl,mailurl){
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
        else if($.trim(emailid) !="" && existDataAdmin(mailurl,emailid) !=''){
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
        else if($.trim(phone) !="" && existDataAdmin(phurl,phone) !=''){
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
	
	else if(aIds.length ==0){
		$("#erroMsg").html('Please assign user right.').show(); 
		next_step = false;
	}	
	else{
            if( next_step == true ) {	
                    $.ajax({
                        type: "POST",
                        url: eeurl,
                        data:{email:emailid}, 
                        success:function(data){  
                                if(data ==''){
                                    saveAdminCreation();
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

function saveAdminCreation(){
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
                                $("#login-text-message").text('Thank you Admin login has been created successfully.');
                                
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


function existDataAdmin(path,data){
    var posts = $.ajax({type: 'POST',url: path,async: false,dataType: 'json',data: {getdata:data},done: function(response) {return response;}}).responseText;	
    return posts;
}

function existPhoneAdmin(id,data){
    var posts = $.ajax({type: 'POST',url: 'AdminUsers/edit_exist_phone',async: false,dataType: 'json',data: {getdata:data,id:id},done: function(response) {return response;}}).responseText;	
    return posts;
}

function existEmailAdmin(id,data){
    var posts = $.ajax({type: 'POST',url: 'AdminUsers/edit_exist_email',async: false,dataType: 'json',data: {getdata:data,id:id},done: function(response) {return response;}}).responseText;	
    return posts;
}


