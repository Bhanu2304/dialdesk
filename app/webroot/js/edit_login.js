function validateEditLogin(){
	var next_step = true;
	var name=$('#name').val();
	var designation=$('#designation').val();
	var all_location_id = document.querySelectorAll('input[name="selectAll[]"]:checked');
    var aIds = [];
        for(var x = 0, l = all_location_id.length; x < l;  x++){
         aIds.push(all_location_id[x].value);
        }
		
		
	if($.trim(name)===""){
		$('#name').focus();
		$("#erroMsg").html('Name field is required.').show();  
		return false;
	}
	else if(!lengthrange(name,50)){
                $('#name').focus();
		$("#erroMsg").html(length_err).show();  
		return false;
	}
	else if(!specialchar(name)){
                $('#name').focus();
		$("#erroMsg").html(sepchar_err).show();  
		return false;
	}
        else if(!allLetter(name)){
            $('#name').focus();
            $("#erroMsg").html(letter_err).show();  	
            return false;
	}
	else if($.trim(designation)===""){
		$('#designation').focus();
		$("#erroMsg").html('Designation field is required.').show();  
		return false;
	}
	else if(!specialchar(designation)){
		$('#designation').focus();
		$("#erroMsg").html(sepchar_err).show();  
		return false;
	}
	else if(!lengthrange(designation,50)){
		$('#designation').focus();
		$("#erroMsg").html(length_err).show();  
		return false;
	}
	else if(aIds.length ==0){
		$("#erroMsg").html('Please assign user right.').show(); 
		return false;
	}	
	else{
		return true;
	}
}

