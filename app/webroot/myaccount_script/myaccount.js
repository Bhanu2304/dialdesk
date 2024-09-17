function changePassword(){
    $("#ms").remove();
    var currentpass=$("#currentpass").val();
    var newpass=$("#newpass").val();
    var confirmpass=$("#confirmpass").val();
    
    if($.trim(currentpass) ===""){
        $("#currentpass").after('<span style="color:red;" id="ms">Please enter current password.</span>');
        $("#currentpass").focus();
        return false;
    }
    else if($.trim(newpass) ===""){
        $("#newpass").after('<span style="color:red;" id="ms">Please enter new password.</span>');
        $("#newpass").focus();
        return false;
    }
    else if($.trim(confirmpass) ===""){
        $("#confirmpass").after('<span style="color:red;" id="ms">Please enter confirm password.</span>');
        $("#confirmpass").focus();
        return false;
    }
    else if($.trim(newpass) != $.trim(confirmpass)){
        $("#confirmpass").after('<span style="color:red;" id="ms">Confirm password not match.</span>');
        $("#confirmpass").focus();
         $("#confirmpass").val('');
        return false;
    }
    else{
        $.post("/dialdesk/MyAccounts/change_password",{currentpass:currentpass,newpass:newpass},function(data){ 
            if(data =="1"){
                $("#currentpass").after('<span style="color:red;" id="ms">Current password not exist in database.</span>');
                $("#currentpass").focus();
            }
            else if(data =="2"){
                $("#mge-error").html('<span style="color:green">Password change successfully.</span>').delay(5000).fadeOut();
                $("#currentpass").val('');
                $("#newpass").val('');
                $("#confirmpass").val('');
            }         
        });
    }  
}

function changeEmail(){
    var newemail=$("#newemail").val();
    $("#ms").remove();

    if($.trim(newemail) ===""){
        $("#newemail").after('<span style="color:red;" id="ms">Please enter valid email.</span>');
        $("#newemail").focus();
        return false;
    }
    else if (!filter.test($.trim(newemail))) {
        $("#newemail").after('<span style="color:red;" id="ms">Please enter valid email.</span>');
        $("#newemail").focus();
        return false;
    }
    else if ($.trim($("#cp1email").val()) === $.trim(newemail)) {
        $("#newemail").after('<span style="color:red;" id="ms">This email id already used in contact details.</span>');
        $("#newemail").focus();
        return false;
    }
    else{
        $('#loder1').show();
        $.post("/dialdesk/MyAccounts/change_email",{newemail: newemail,},function(data){              
            $("#newemail").after('<span style="color:red;" id="ms"><br/>'+data+'</span>');
             $("#newemail").val('');
            $("#ms").delay(5000).fadeOut();
            $('#loder1').hide();
        });
    }  
}

function changePhone(){
    $("#ms").remove();
    if($.trim($("#otpval").val()) ===""){
        $("#otpval").after('<span style="color:red;" id="ms">Please enter valid password.</span>');
        $("#otpval").focus();
        return false;
    }
    else{
        $.post("/dialdesk/MyAccounts/change_phone",{phone_no: $("#newphno").val(),otpval:$("#otpval").val()},function(data){
            if(data ==="1"){
                $("#otpval").after('<span style="color:red;" id="ms">OTP not match please resend.</span>');
                return false;
            }
            else if(data ==="2"){
                $.post($("#delete_newotp").val(),function(){});
                $("#otpval").val('')
                $("#newnumber").val('')
                $(".otpdiv").hide();
                $("#coverdiv").hide();
                $("#msgpopup").trigger('click');
                $("#show-message").html('<span style="color:green;" id="ms">Your request sent to admin for phone no update.</span>');
            }
            else if(data ==="3"){
               $.post($("#delete_newotp").val(),function(){});
                $("#otpval").val('')
                $("#newnumber").val('')
                $(".otpdiv").hide();
                $("#coverdiv").hide();
                $("#msgpopup").trigger('click');
                $("#show-message").html('<span style="color:green;" id="ms">Your request already sent for admin.</span>');
            }
        });  
    }
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

function saveContact1(){
	$(".w_msg").remove();
	$("#getspace").remove();
	var contact_person1 = $("#contact_person1").val();
	var cp1_phone = $("#cp1_phone").val();
	var cp1_email = $("#cp1_email").val();
	
	if($.trim(contact_person1) ==='') {
		show_error('contact_person1',blank_err);
       	return false;
	}
	else if($.trim(cp1_phone) ==='') {
		show_error('cp1_phone',blank_err);
		return false;
	}
	else if(!$.trim(cp1_phone).match(phoneNum)) {
		show_error('cp1_phone',phone_err);
		return false;
	}
        else if($.trim(cp1_phone) !="" && $.trim(cp1_phone).charAt(0)==="0") {
		show_error('cp1_phone',phone_err);
		return false;
	}
        else if($.trim($("#cphone").val()) === $.trim(cp1_phone)){
		show_error('cp1_phone','This phone no already used in registration.');
		return false;
	}
	else if($.trim(cp1_email) ==='') {
		show_error('cp1_email',blank_err);
		return false;
	}
	else if (!filter.test($.trim(cp1_email))) {
		show_error('cp1_email',email_err);
		return false;
	}
        else if($.trim($("#cemail").val()) === $.trim(cp1_email)){
		show_error('cp1_email','This email id already used in registration.');
		return false;
	}
	else{
		$("#contact_details_form").submit();	
	}		
}

function saveContact2(){
	$(".w_msg").remove();
	$("#getspace").remove();
	var cp2_phone = $("#cp2_phone").val();
	var cp2_email = $("#cp2_email").val();
	
	if($.trim(cp2_phone) !="" && !$.trim(cp2_phone).match(phoneNum)) {
		show_error('cp2_phone',phone_err);
		return false;
	}
        if($.trim(cp2_phone) !="" && !$.trim(cp2_phone).match(phoneNum)) {
		show_error('cp2_phone',phone_err);
		return false;
	}
        else if($.trim(cp2_phone) !="" && $.trim(cp2_phone).charAt(0)==="0") {
		show_error('cp2_phone',phone_err);
		return false;
	}
	else if($.trim(cp2_email) !="" && !filter.test($.trim(cp2_email))) {
		show_error('cp2_email',email_err);
		return false;
	}
	else{
		$("#contact_details_form").submit();	
	}		
}

function saveContact3(){
	$(".w_msg").remove();
	$("#getspace").remove();
	
	var cp3_phone = $("#cp3_phone").val();
	var cp3_email = $("#cp3_email").val();
	
	if($.trim(cp3_phone) !="" && !$.trim(cp3_phone).match(phoneNum)) {
		show_error('cp3_phone',phone_err);
		return false;
	}
        else if($.trim(cp3_phone) !="" && $.trim(cp3_phone).charAt(0)==="0") {
		show_error('cp3_phone',phone_err);
		return false;
	}
	else if($.trim(cp3_email) !="" && !filter.test($.trim(cp3_email))) {
		show_error('cp3_email',email_err);
		return false;
	}
	else{
            $("#contact_details_form").submit();	
	}		
}

function saveUpload(){
	$("#myaccount_form").submit();
}

function sendNumber(otpurl,existurl,verifyurl){
     $("#ms").remove();
	$(".w_msg").remove();
	$("#getspace").remove();
	var newnumber=$("#newnumber").val();
        
        if(adminVerify(verifyurl,newnumber) ===''){
            $("#newnumber").after('<span style="color:red;" id="ms">Phone can update after admin varificaiton.</span>');
            return false;
	}
	else if($.trim(newnumber) ==='') {
            $("#newnumber").after('<span style="color:red;" id="ms">Please enter valid phone no.</span>');
            return false;
	}
	else if($.trim(newnumber) !="" && !$.trim(newnumber).match(phoneNum)) {
            $("#newnumber").after('<span style="color:red;" id="ms">Please enter valid phone no.</span>');
            return false;
	}
        else if($.trim(newnumber) !="" && $.trim(newnumber).charAt(0)==="0") {
            $("#newnumber").after('<span style="color:red;" id="ms">Please enter valid phone no.</span>');
            return false;
	}
	else if($.trim(newnumber) !="" && existPhone(existurl,newnumber) !=''){
            $("#newnumber").after('<span style="color:red;" id="ms">This number is already exist.</span>');
            return false;
	}
        else if($.trim(newnumber) !="" && $.trim($("#contact1_phoneno").val()) === $.trim(newnumber)){
            $("#newnumber").after('<span style="color:red;" id="ms">This phone no already used in contact details.</span>');
            return false;
	}
	else{
            $("#close-change-phone").trigger('click');
            $("#newphno").val(newnumber);
            $(".otpdiv").fadeIn(500);
            $("#coverdiv").fadeTo(500, 0.5);
            sent_otp(otpurl);  
	}		
}

g_timer = null;

function sent_otp(url){
    var phone=$("#newphno").val();
    $.ajax({
            type: "POST",
            url:url,
            data:{phone:phone},
            success: function(data){
                clearTimeout(g_timer);
                startTimer();
            }
    });	
}


function startTimer() {
    g_timer = window.setTimeout(function() {
         $.post($("#delete_newotp").val(),function(){});
    }, 180000);
}





function existPhone(path,data){
	var posts = $.ajax({type: 'POST',url: path,async: false,dataType: 'json',data: { phone_no:data},done: function(response) {return response;}}).responseText;	
	return posts;
}

function adminVerify(path,data){
	var posts = $.ajax({type: 'POST',url: path,async: false,dataType: 'json',data: { get_data:data},done: function(response) {return response;}}).responseText;	
	return posts;
}

function closePopup(){
    $.post($("#delete_newotp").val(),function(){});
    $("#otpval").val('')
    $("#newnumber").val('')
    $(".otpdiv").hide();
    $("#coverdiv").hide(); 
}



                                    