function validateProcessIntegration(){
	var name=$('#name').val();
	
	if($.trim(name)===""){
		$('#name').focus();
		$("#erroMsg").html(blank_err).show();  
		return false;
	}
	else if(!lengthrange(name,100)){
		$("#erroMsg").html(length_err).show();  
		return false;
	}
	else if(!specialchar(name)){
		$("#erroMsg").html(sepchar_err).show();  
		return false;
	}
	else{
		return true;	
	}
}

function addChild(attid){
	var parent=$("#pid").val();
	$("#lastparent").val(parent);
	label = 2;	
	if(parent==''){ return;}
	if(typeof $("#pid2").val()!=='undefined'){
		parent = $("#pid2").val();
		$("#lastparent").val(parent);
		label =3;
	}		 
	if(typeof $("#pid3").val()!=='undefined'){
		parent = $("#pid3").val();
		$("#lastparent").val(parent);
		label =4;
	}

	if(typeof $("#pid4").val()!=='undefined'){
		parent = $("#pid4").val();
		$("#lastparent").val(parent);
		label =5;
	}
	if(typeof $("#pid5").val()!=='undefined'){
		return;
	} 	
	
	$.post("ProcessIntegrations/getParentProcess",{pid: parent,Label:label},function(data,status){
		if(data !='')$('#'+attid).after(data);
  	});
	
}

$(document).ready(function(){
	
	$("#pid").on('change',function(){
		$.post("ProcessIntegrations/getParentProcess",{
          pid: $("#pid").val(),
          Label: 2
        },
        function(data,status){
   			if(data == ''){if(typeof $("#pid2").val()!=='undefined'){$('#pid2').remove();}}
			else {$('#pid2').replaceWith(data);}

			if(typeof $("#pid3").val()!=='undefined'){
				$('#pid3').remove();
		 	}
			if(typeof $("#pid4").val()!=='undefined'){
				$('#pid4').remove();
		 	}
			if(typeof $("#pid5").val()!=='undefined'){
				$('#pid5').remove();
		 	}
			
        });
	});	
});
		
		
$(document).on('change','#pid2',function(){$.post("ProcessIntegrations/getParentProcess",{	
	pid: this.value,
    Label: 3
  	},
    function(data,status){
		if(data == ''){if(typeof $("#pid3").val()!=='undefined'){$('#pid3').remove();}}
		else {$('#pid3').replaceWith(data);}
		if(typeof $("#pid4").val()!=='undefined'){
			$('#pid4').remove();
		}
		if(typeof $("#pid5").val()!=='undefined'){
			$('#pid5').remove();
		}
        });
	});	
	
$(document).on('change','#Parent3',function(){$.post("ProcessIntegrations/getParentProcess",{
	pid: this.value,
   	Label: 4
   	},
  	function(data,status){
		if(data == ''){if(typeof $("#pid4").val()!=='undefined'){$('#pid4').remove();}}
		else {$('#pid4').replaceWith(data);}
		if(typeof $("#pid5").val()!=='undefined'){
			$('#pid5').remove();
		}
    });
});

$(document).on('change','#pid4',function(){$.post("ProcessIntegrations/getParentProcess",{
	pid: this.value,
   	Label: 5
    },
    function(data,status){        
		$('#pid5').replaceWith(data);
	});
});