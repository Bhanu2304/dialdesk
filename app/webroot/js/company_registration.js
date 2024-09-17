$(document).ready(function() {
	$('.registration-form fieldset:first-child').fadeIn('slow');
	
	// previous button code-------------
    $('.registration-form .btn-previous').on('click', function() {
    	$(this).parents('fieldset').fadeOut(400, function() {
    		$(this).prev().fadeIn();
    	});
    });
	
	sameAs();
	
	var numberverify = $("#numberverify").val();
	var view_contact2 = $("#view_contact2").val();
	var view_contact3 = $("#view_contact3").val();
	
	if(numberverify ==="yes"){		
		document.getElementById("phone").readOnly = true;		
	}
	else{
		document.getElementById("phone").readOnly = false;
	}
	
	if(view_contact2 !=""){	
		showSecondContact();
	}
	if(view_contact3 !=""){	
		showThirdContact();
	}

	
});

function savepage1(){
	$(".w_msg").remove();
	$("#getspace").remove();
        
        var re = /[0-9]/;
        var re1 = /[a-z]/;
        
	var company_name = $("#company_name").val();
	var company_url = $("#company_url").val();
	var office_address1 = $("#office_address1").val();
	var city = $("#city").val();
	var state = $("#state").val();
	var pincode = $("#pincode").val();
	var authorised_person = $("#authorised_person").val();
	var phone = $("#phone").val();
	var email = $("#email").val();	
	var password = $("#password").val();
	var confirmpass = $("#confirmpass").val();
	var comm_pincode = $("#comm_pincode").val();
	
	if($.trim(company_name)===""){
		show_error('company_name',blank_err);
		return false;
	}
	else if($.trim(company_name) !="" && existCompany(company_url,company_name) !=''){
		show_error('company_name','Company name already exist in database.');
		return false;
	}
	else if($.trim(office_address1) ==='') {
		show_error('office_address1',blank_err);
		return false;
	}
	else if($.trim(city) ==='') {
		show_error('city',blank_err);
		return false;
	}
        else if($.trim(state) ==='') {
		show_error('state',blank_err);
		return false;
	}
	else if($.trim(pincode) ==='') {
		show_error('pincode',blank_err);
		return false;
	}
	else if($.trim(pincode).length < 6) {
		show_error('pincode','Enter valid pincode.');
		return false;
	}
	else if($.trim(authorised_person) ==='') {
		show_error('authorised_person',blank_err);
       	return false;
	}
	else if(!allLetter(authorised_person)) {
		show_error('authorised_person',letter_err);
       	return false;
	}
	else if($.trim(phone) ==='') {
		show_error('phone',blank_err);
		return false;
	}
	else if(!$.trim(phone).match(phoneNum)) {
		show_error('phone',phone_err);
		return false;
	}
        else if($.trim(phone).charAt(0)==="0") {
		show_error('phone',phone_err);
		return false;
	}
	else if($.trim(email) ==='') {
		show_error('email',blank_err);
		return false;
	}
	else if (!filter.test($.trim(email))) {
		show_error('email',email_err);
		return false;
	}
	else if($.trim(password) ==='') {
		show_error('password',blank_err);
		return false;
	}
        else if($.trim(password).length < 6) {
		show_error('password','Password must contain at least six characters!');
		return false;
	}
        else  if(!re.test($.trim(password))) {
            show_error('password','Password must contain at least one number (0-9)!');
            return false;
	}
        else  if(!re1.test($.trim(password))) {
            show_error('password','Password must contain at least one lowercase letter (a-z)!');
            return false;
	}
	else if($.trim(confirmpass) ==='') {
		show_error('confirmpass',blank_err);
		return false;
	}
	else if($.trim(password) != $.trim(confirmpass)) {
		show_error('confirmpass',pass_err);
		return false;
	}
	else if($.trim(comm_pincode) !="" && $.trim(comm_pincode).length < 6) {
		show_error('comm_pincode','Enter valid pincode.');
		return false;
	}
	else{
		return true;
	}		
}

function savepage2(){
	$(".w_msg").remove();
	$("#getspace").remove();
	var contact_person1 = $("#contact_person1").val();
	var contact_person2 = $("#contact_person2").val();
	var contact_person3 = $("#contact_person3").val();
	
	var cp1_phone = $("#cp1_phone").val();
	var cp2_phone = $("#cp2_phone").val();
	var cp3_phone = $("#cp3_phone").val();
	
	var cp1_email = $("#cp1_email").val();
	var cp2_email = $("#cp2_email").val();
	var cp3_email = $("#cp3_email").val();
	
	var smtpUrl = $("#smtpUrl").val();
     
	if($.trim(contact_person1) ==='') {
		show_error('contact_person1',blank_err);
       	return false;
	}
	else if(!allLetter(contact_person1)){
		show_error('contact_person1',letter_err);
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
        else if($.trim(cp1_phone).charAt(0)==="0") {
		show_error('cp1_phone',phone_err);
		return false;
	}   
        else if($.trim($("#phone").val()) === $.trim(cp1_phone)){
		show_error('cp1_phone','Please Enter any other mobile no.');
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
       else if($.trim($("#email").val()) === $.trim(cp1_email)){
		show_error('cp1_email','Please Enter any other email address.');
		return false;
	}
	else if($.trim(contact_person2) !="" && !allLetter(contact_person2)) {
		show_error('contact_person2',letter_err);
		return false;
	}
	else if($.trim(cp2_phone) !="" && !$.trim(cp2_phone).match(phoneNum) || $.trim(cp2_phone).charAt(0)==="0") {
		show_error('cp2_phone',phone_err);
		return false;
	}
	else if($.trim(cp2_email) !="" && !filter.test($.trim(cp2_email))) {
		show_error('cp2_email',email_err);
		return false;
	}
	else if($.trim(contact_person3) !="" && !allLetter(contact_person3)) {
		show_error('contact_person3',letter_err);
		return false;
	}
	else if($.trim(cp3_phone) !="" && !$.trim(cp3_phone).match(phoneNum) || $.trim(cp3_phone).charAt(0)==="0") {
		show_error('cp3_phone',phone_err);
		return false;
	}
	else if($.trim(cp3_email) !="" && !filter.test($.trim(cp3_email))) {
		show_error('cp3_email',email_err);
		return false;
	}
	else{
		return true;		
	}	
}

function go_to_next1(){
  //saveFirstFormInSession();
	if( savepage1()) {
            $('#ckloder').show();	
		var smtpUrl = $("#smtpUrl").val();
		var existemail = $("#existemail").val();	
		var email = $("#email").val();	
		var phone_no = $("#phone").val();
		var check_exist_phone=$("#check_exist_phone").val();
		var verify_email1 = $("#verify_email1").val();
		
		$.post(check_exist_phone,{phone_no: phone_no},function(data){
			if(data !=''){
				show_error('phone','This phone no already exist in database.');
				return false;
			}
			else{
				$.post(existemail,{email: email},function(data1){
					if(data1 !=''){
						show_error('email',email_exist_err);
						return false;
					}
					else{
                                            $('#ckloder').show();
                                            executeDataUsingTime();

                                            //checkPhoneVarification();
                                            /*
						if(email !=verify_email1){
							chechSmtp(smtpUrl,email);
						}
						else{
							checkPhoneVarification();
						}
                                            */
					}
					
				});
			}
  		});	
	}
        
      
}

function go_to_next2(){
    if(savepage2()){
        saveSecondFormInSession();
    }
}

function go_to_next3(){
    $(".w_msg").remove();
    $("#getspace").remove();
    $("#step3_box").hide().delay(5000).fadeOut();
    $("#step3_msgbox").html('');
    
    var email = $("#email").val();
    var terms_condition = document.getElementById('terms_condition');
    var upload_doc = $("#upload_doc").val();
    var upload_doc2 = $("#upload_doc2").val();
    var upload_doc3 = $("#upload_doc3").val();
    var upload_doc4 = $("#upload_doc4").val();
    var upload_doc5 = $("#upload_doc5").val();    
    var uploadArray = [];
      
    if(upload_doc  !=""){uploadArray.push(upload_doc);}
    if(upload_doc2 !=""){uploadArray.push(upload_doc2);}
    if(upload_doc3 !=""){uploadArray.push(upload_doc3);}
    if(upload_doc4 !=""){uploadArray.push(upload_doc4);}
    if(upload_doc5 !=""){uploadArray.push(upload_doc5);}
    
    
    
    
     
    if(uploadArray.length ==0) {
        $("#step3_box").show();
        document.getElementById("step3_box").className = "error";
        $("#step3_msgbox").html('At least one document is mandatory to upload.');
        return false;
    }
    else if(!terms_condition.checked){
        $("#step3_box").show();
        document.getElementById("step3_box").className = "error";
        $("#step3_msgbox").html('Accept Terms & Contitions to Sign Up.');
            
            $("#thide").after('<span class="w_msg" style="color:red;">Accept Terms & Contitions to Sign Up.</span>');
    }
    else{
            $('#ckloder_submit').show();
            $('#company_register_form').submit();
            return false;
    }	
}

// Send OTP

g_timer = null;

function sent_otp(){ 
    $("#otp_box").hide().delay(5000).fadeOut();
    $("#otp_msgbox").html('');
    var url = $("#url").val();
    var phone = $("#phone").val();	
    $.ajax({
            type: "POST",
            url:url,
            data: {phone:phone},
            success: function(data){
                $("#otp_box").show();
                document.getElementById("otp_box").className = "info";
                $("#otp_msgbox").html('Your OTP resend successfully.');
                clearTimeout(g_timer);
                startTimer();
            }
    });	
}

function startTimer() {
    g_timer = window.setTimeout(function() {
         $.post($("#delete_otp").val(),function(){});
    }, 180000);
}

function executeDataUsingTime() {
    g_timer = window.setTimeout(function() {
        $('#ckloder').hide();
        checkPhoneVarification();
    },10000);
}





function existCompany(company_url,company_name){
	var posts = $.ajax({type: 'POST',url: company_url,async: false,dataType: 'json',data: { company_name:company_name},done: function(response) {return response;}}).responseText;	
	return posts;
}

function start_time(){
	var delete_otp=$("#delete_otp").val();
	$("#replace").show();
	$("#show_resend_otp_link").hide();
(function () {
    var timeLeft = 180,
        cinterval;

    var timeDec = function (){
        timeLeft--;
        document.getElementById('countdown').innerHTML = '<font color="#FF0000">'+timeLeft+'</font>';
        if(timeLeft === 0){
			$("#replace").hide();
			$("#show_resend_otp_link").show();
			document.getElementById('countdown').innerHTML = '<font color="#FF0000">180</font>';
			$.post(delete_otp,function(){});
            clearInterval(cinterval);
        }
    };

    cinterval = setInterval(timeDec, 1000);
})();
}

// Match OTP
function save_otp(url){
	$(".w_msg").remove();
	$("#getspace").remove();
        $("#otp_box").hide().delay(5000).fadeOut();
        $("#otp_msgbox").html('');
	var otp_data = $("#otp_data").val();
	if($.trim(otp_data) ===''){
                $("#otp_box").show();
                document.getElementById("otp_box").className = "warning";
                $("#otp_msgbox").html('Please enter correct otp.');
		$("#otp_data").focus();
		return false;
	}
	else{
		$.ajax({
			type: "POST",
			url:url,
			data: {otpval:otp_data},
			success: function(data){
				if(data !=''){
					$("#numberverify").val("yes");
					$(".otp").hide();
					$("#cover").hide();	
					//savepage1();
					//go_to_next1()
					//$("#fieldset1").fadeIn();	
					saveFirstFormInSession();		
				}
				else{
					$("#otp_data").focus();
                                        $("#otp_box").show();
                                        document.getElementById("otp_box").className = "error";
                                        $("#otp_msgbox").html('Your OTP mismatch enter correct OTP or resend otp again.');					
					$("#otpval").val('');
					$("#otpval").focus();	
					return false;
				}
			}
		});
	}
}

/*
function chechSmtp(smtpUrl,email){
	$('#ckloder').show();			
	$.ajax({
		type: "POST",
		url: smtpUrl,
		dataType:'json',
		data:{email:email}, 
		success:function(data){         
			if(data[email]){
				$('#ckloder').hide();
				$("#verify_email1").val(email);
				checkPhoneVarification()							 
			}
			else{
				$('#ckloder').hide();				
				show_error('email',email_err);
				return false;
			}            
		}   
	});				
}
*/

function checkPhoneVarification(){
	var numberverify = $("#numberverify").val();
	if(numberverify !="yes"){
		sent_otp();
		document.getElementById("phone").readOnly = true;
		$(".otp").fadeIn(500);
		$("#cover").fadeTo(500, 0.5);
	}
	else{
		saveFirstFormInSession();
	}
}


function saveFirstFormInSession(){
	var company_name=$("#company_name").val();
	var office_address1=$("#office_address1").val();
	var office_address2=$("#office_address2").val();
	var city=$("#city").val();
	var state=$("#state").val();
	var gst_no=$("#gst_no").val();
	var pincode=$("#pincode").val();
	var authorised_person=$("#authorised_person").val();
	var designation=$("#designation").val();
	var phone=$("#phone").val();
	var email=$("#email").val();
	var password=$("#password").val();
	var comm_address1=$("#comm_address1").val();
	var comm_address2=$("#comm_address2").val();
	var comm_city=$("#comm_city").val();
	var comm_state=$("#comm_state").val();
	var comm_pincode=$("#comm_pincode").val();
	var save_first_form_url=$("#save_first_form_url").val();
	var sameas=$('#sameas:checked').val();
	
	$.ajax({
	type: "POST",
	url: save_first_form_url,
	data:{company_name:company_name,office_address1:office_address1,office_address2:office_address2,city:city,state:state,gst_no:gst_no,pincode:pincode,authorised_person:authorised_person,designation:designation,phone:phone,email:email,password:password,comm_address1:comm_address1,comm_address2:comm_address2,comm_city:comm_city,comm_state:comm_state,comm_pincode:comm_pincode,sameas:sameas}, 
	success:function(data){
		if(data !=''){ 
			$('#ckloder').hide();	
			$("#fieldset1").fadeOut('400',function(){$("#fieldset2").fadeIn();});    
		}
		}   
	});	
}

function saveSecondFormInSession(){
	var contact_person1=$("#contact_person1").val();
	var cp1_designation=$("#cp1_designation").val();
	var cp1_phone=$("#cp1_phone").val();
	var cp1_email=$("#cp1_email").val();
	var contact_person2=$("#contact_person2").val();
	var cp2_designation=$("#cp2_designation").val();
	var cp2_phone=$("#cp2_phone").val();
	var cp2_email=$("#cp2_email").val();
	var contact_person3=$("#contact_person3").val();
	var cp3_designation=$("#cp3_designation").val();
	var cp3_phone=$("#cp3_phone").val();
	var cp3_email=$("#cp3_email").val();
	var save_second_form_url=$("#save_second_form_url").val();
	
	$.ajax({
	type: "POST",
	url: save_second_form_url,
	data:{contact_person1:contact_person1,cp1_designation:cp1_designation,cp1_phone:cp1_phone,cp1_email:cp1_email,contact_person2:contact_person2,cp2_designation:cp2_designation,cp2_phone:cp2_phone,cp2_email:cp2_email,contact_person3:contact_person3,cp3_designation:cp3_designation,cp3_phone:cp3_phone,cp3_email:cp3_email}, 
	success:function(data){
		if(data !=''){ 
			$('#ckloder1').hide();
			$("#fieldset2").fadeOut('400',function(){$("#fieldset3").fadeIn();});   
		}
		}   
	});	
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

function sameAs(){
	var sameas = document.getElementById('sameas');
    if (sameas.checked){
		$("#comm_address1").val($("#office_address1").val());
	    $("#comm_address2").val($("#office_address2").val());
	    $("#comm_state").val($("#state").val());
		 $("#comm_city").val($("#city").val());
	    $("#comm_pincode").val($("#pincode").val());
    }else{
		$("#comm_address1").val('');
	    $("#comm_address2").val('');
	    $("#comm_city").val('');
	    $("#comm_state").val('');
	    $("#comm_pincode").val('');
	}	
}

function showSecondContact(){
	 $('#cp1').toggle();
}

function showThirdContact(){	
	 $('#cp2').toggle();
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

function closePopup(){
    var delete_otp=$("#delete_otp").val();
    $(".otp").hide();
    $("#cover").hide();
    document.getElementById("phone").readOnly = false;
    $.post(delete_otp,function(){});       
}

