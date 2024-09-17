var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var phoneNum = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
var blank_err='Please enter details.';
var phone_err='Insert valid number.';
var email_err='Insert valid email.';
var email_exist_err='This email already exist in database.';
var alpha_numeric_err='Allow only numeric and character.';
var letter_err='Allow only characters.';
var length_err='This field is too long.';
var sepchar_err='Special character not allow.';
var pass_err="Your password do not match.";
var allnum_err="Allow only numbers.";


function specialchar(inputtxt){ 
    var letters = /^[0-9a-zA-Z ]+$/; 
	var data=$.trim(inputtxt);   
    if(data.match(letters)){  
		return true;
    }
	else{
		return false;
	}    
}

function alphanumeric(inputtxt){ 
    var letters = /^[0-9a-zA-Z ]+$/; 
	var data=$.trim(inputtxt);   
    if(data.match(letters)){  
		return true;
    }
	else{
		return false;
	}    
}

function allLetter(inputtxt){   
	var letters = /^[A-Za-z ]+$/;
	var data=$.trim(inputtxt);  
  	if(data.match(letters)){  
		return true;
    }
	else{
		return false;
	}    
}

function allNunber(inputtxt){   
	var letters = /^[0-9]+$/; 
	var data=$.trim(inputtxt);  
  	if(data.match(letters)){  
		return true;
    }
	else{
		return false;
	}    
}

function lengthrange(inputtxt,len){
	var data=$.trim(inputtxt);  
	if(data.length <= len){         
		return true;
	}
	else{
		return false;
	}
}

function show_error(input,error){
	$("#"+input).focus();
    $("#"+input).after('<br id="getspace"></span><span class="w_msg" style="color:red;">'+error+'</span>');
}

function goBack() {
	window.history.back();
} 
