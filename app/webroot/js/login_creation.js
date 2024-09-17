function view_edit_login(id){
    $.post("LoginCreations/view_edit_login",{id:id},function(data){
        $("#user-data").html(data);
    }); 
}

function editLoginForm(path,purl,emailurl){ 
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
    else if($.trim(emailid) !="" && existEmail(loginid,emailid) !=''){
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
    else if($.trim(phone) !="" && existPhone(loginid,phone) !=''){
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
    else if(aIds.length ==0){
        $("#elMsg").html('Please assign user right.').show(); 
        return false;
    }	
    else{
       $.ajax({
            type: "POST",
            url:path,
            data: {loginid:loginid,name:name,email:emailid,phone:phone,designation:designation,page_assign:aIds,password:password},
            success: function(data){
                    if(data !=''){
                        $("#close-login-popup").trigger('click');
                        $("#show-login-message").trigger('click');
                        $("#login-text-message").text('Thank you user login has been updated successfully.');
                    }
            }
	});
        return true;
    }  
}

function hidepopup(){
    location.reload(); 
}

function validateLoginCreation(phurl,mailurl){
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
        else if($.trim(emailid) !="" && existData(mailurl,emailid) !=''){
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
        else if($.trim(phone) !="" && existData(phurl,phone) !=''){
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
                                $("#login-text-message").text('Thank you user login has been created successfully.');
                                
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

function existData(path,data){
    var posts = $.ajax({type: 'POST',url: path,async: false,dataType: 'json',data: {getdata:data},done: function(response) {return response;}}).responseText;	
    return posts;
}

function existPhone(id,data){
    var posts = $.ajax({type: 'POST',url: 'LoginCreations/edit_exist_phone',async: false,dataType: 'json',data: {getdata:data,id:id},done: function(response) {return response;}}).responseText;	
    return posts;
}

function existEmail(id,data){
    var posts = $.ajax({type: 'POST',url: 'LoginCreations/edit_exist_email',async: false,dataType: 'json',data: {getdata:data,id:id},done: function(response) {return response;}}).responseText;	
    return posts;
}


