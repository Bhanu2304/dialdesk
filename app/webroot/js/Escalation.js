$(document).ready(function(){
    $("#button").click(function(){
	parent= $("#Parent1").val();
    label = 2;	
	if(parent==''){ return;}
	if(typeof $("#Parent2").val()!=='undefined'){
			parent = $("#Parent2").val();
		 	label =3;
		 }		 
	if(typeof $("#Parent3").val()!=='undefined')
		 {
			parent = $("#Parent3").val();
		 	label =4;
		 }

		 if(typeof $("#Parent4").val()!=='undefined')
		 {
			parent = $("#Parent4").val();
		 	label =5;
		 }

		 if(typeof $("#Parent5").val()!=='undefined')
		 {
			return;
		 } 
	
	$.post("Escalations/getParent",
        {
          parent_id: parent,
          Label: label
        },
        function(data,status)
        {    
            if(data!='')$('#table tr:eq('+(label-1)+')').after("<tr><td>Select Child</td><td>"+data+"</td></tr>");
        });
});});

$(document).ready(function(){
	$("#Parent1").on('change',function(){$.post("Escalations/getParent",
        {
          parent_id: $("#Parent1").val(),
          Label: 2
        },
        function(data,status){i=2;
            		if(data == ''){if(typeof $("#Parent2").val()!=='undefined'){$('#Parent2').remove();$("#table").find("tr:eq("+i+")").remove(); }}
			else {$('#Parent2').replaceWith(data); i=3;}

			if(typeof $("#Parent3").val()!=='undefined')
		 	{
			$('#Parent3').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}
			if(typeof $("#Parent4").val()!=='undefined')
		 	{
			$('#Parent4').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}

			if(typeof $("#Parent5").val()!=='undefined')
		 	{
			$('#Parent5').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}
			
        });});});

$(document).on('change','#Parent2',function(){$.post("Escalations/getParent",
        {
			
          parent_id: this.value,
          Label: 3
        },
        function(data,status){
            i=3;
			if(data == ''){if(typeof $("#Parent3").val()!=='undefined'){$('#Parent3').remove();$("#table").find("tr:eq("+i+")").remove();}}
			else {$('#Parent3').replaceWith(data); i=4;}
			if(typeof $("#Parent4").val()!=='undefined')
		 	{
			$('#Parent4').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}
			if(typeof $("#Parent5").val()!=='undefined')
		 	{
			$('#Parent5').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}

        });});

$(document).on('change','#Parent3',function(){$.post("Escalations/getParent",
        {
			
          parent_id: this.value,
          Label: 4
        },
        function(data,status){
			i=4;
			if(data == ''){if(typeof $("#Parent4").val()!=='undefined'){$('#Parent4').remove();$("#table").find("tr:eq("+i+")").remove();}}
			else {$('#Parent4').replaceWith(data); i = 5;}


			if(typeof $("#Parent5").val()!=='undefined')
		 	{
			$('#Parent5').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}

        });});

$(document).on('change','#Parent4',function(){$.post("Escalations/getParent",
        {
			
          parent_id: this.value,
          Label: 5
        },
        function(data,status){
            
			$('#Parent5').replaceWith(data);

        });});

$(document).ready(function(){
    $("#add_fields").click(function(){
		frm=$("#EscalationFieldSet").val();
		vir = $("#EscalationVirtual").val();
		$("#EscalationVirtual").val(vir+frm+",");
		frmvalue = $("#EscalationFieldSet option:selected").text();
		data =$("#EscalationField").val();
		$("#EscalationField").val(data+" <"+frmvalue+"> ");
		data =$("#EscalationFormat").val();
		//$("#EscalationFormat").val(data+" <"+frmvalue+"> ");
		});});

$(document).ready(function(){
    $("#clear_fields").click(function(){
		$("#EscalationField").val("");
		$("#EscalationFormat").val("");
		$("#EscalationVirtual").val("");
		});});

$(document).ready(function(){
    $("#EscalationTimer").on('change',function(){
		value = $("#EscalationTimer").val();
			$("#datetimepicker").hide();
			$("#datetimepicker1").hide();
			$("#month").hide();
			$("#datetimepicker3").hide();
			$("#week").hide();
			$("#datetimepicker5").hide();	
			$("#datetimepicker6").hide();
			$("#datetimepicker7").hide();
			$("#datetimepicker8").hide();
			$("#datetimepicker9").hide();
		
		if(value == '0'){
			$("#datetimepicker").show();
		}
		else if(value == '1'){
			$("#month").show();
		}
		else if(value == '2'){
			$("#week").show();
		}
		else if(value == '3'){
			$("#datetimepicker1").show();
		}
		else if(value == '4'){
			$("#datetimepicker5").show();
		}
		else if(value == '5'){
			$("#datetimepicker9").show();
		}
		else if(value == '6'){
			$("#datetimepicker6").show();
		}

		});});

function ttf(url,uqry,div)
{
    var xmlhttp = false;
	
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {   //alert(xmlhttp.responseText);
            document.getElementById(div).innerHTML = xmlhttp.responseText;
        }
    }
	
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(uqry);
}

function getType(val,url,catype,slt){
    
    if(catype ==='a'){
        var div="type";
        document.getElementById('subtype').innerHTML = '';
        document.getElementById('subtype1').innerHTML = '';
        document.getElementById('subtype2').innerHTML = '';
    }
    
    if(catype ==='e'){
        var div="etype";
        document.getElementById('esubtype').innerHTML = '';
        document.getElementById('esubtype1').innerHTML = '';
        document.getElementById('esubtype2').innerHTML = '';
    }
    
    if(catype ==='s'){
        var div="stype";
        document.getElementById('ssubtype').innerHTML = '';
        document.getElementById('ssubtype1').innerHTML = '';
        document.getElementById('ssubtype2').innerHTML = '';
    }
    if(catype ==='c'){
        var div="ctype";
        document.getElementById('csubtype').innerHTML = '';
        document.getElementById('csubtype1').innerHTML = '';
        document.getElementById('csubtype2').innerHTML = '';
    }

    if(catype ==='ua'){
        var div="utype";
        document.getElementById('usubtype').innerHTML = '';
        document.getElementById('usubtype1').innerHTML = '';
        document.getElementById('usubtype2').innerHTML = '';
    }
    if(catype ==='ue'){
        var div="uetype";
        document.getElementById('uesubtype').innerHTML = '';
        document.getElementById('uesubtype1').innerHTML = '';
        document.getElementById('uesubtype2').innerHTML = '';
    }
    if(catype ==='us'){
        var div="ustype";
        document.getElementById('ussubtype').innerHTML = '';
        document.getElementById('ussubtype1').innerHTML = '';
        document.getElementById('ussubtype2').innerHTML = '';
    }
    if(catype ==='uc'){
        var div="uctype";
        document.getElementById('ucsubtype').innerHTML = '';
        document.getElementById('ucsubtype1').innerHTML = '';
        document.getElementById('ucsubtype2').innerHTML = '';
    }
    if(catype ==='sas'){
        var div="sastype";
        document.getElementById('sassubtype').innerHTML = '';
        document.getElementById('sassubtype1').innerHTML = '';
        document.getElementById('sassubtype2').innerHTML = '';
    }
    
    
    
    var values = val.split('@@');
    

    
    
    
    
    
    if(val=='')
    {    }
    else
    { 
      //var url = "http://192.168.137.230/dialdesk/escalations/getEcr";
      var uqry = "Label=2&slt="+slt+"&divtype="+catype+"&type=Sub Scenario 1&function=getSubType&parent="+values[0];
      ttf(url,uqry,div);
    }
}

function getSubType(val,url,catype,slt){
    if(catype ==='a'){
        var div="subtype";
        document.getElementById('subtype1').innerHTML = '';
        document.getElementById('subtype2').innerHTML = '';
    }
    if(catype ==='e'){
        var div="esubtype";
        document.getElementById('esubtype1').innerHTML = '';
        document.getElementById('esubtype2').innerHTML = '';
    }
    
    if(catype ==='s'){
        var div="ssubtype";
        document.getElementById('ssubtype1').innerHTML = '';
        document.getElementById('ssubtype2').innerHTML = '';
    }
    if(catype ==='c'){
        var div="csubtype";
        document.getElementById('csubtype1').innerHTML = '';
        document.getElementById('csubtype2').innerHTML = '';
    }
    
    if(catype ==='ua'){
        var div="usubtype";
        document.getElementById('usubtype1').innerHTML = '';
        document.getElementById('usubtype2').innerHTML = '';
    }
    if(catype ==='ue'){
        var div="uesubtype";
        document.getElementById('uesubtype1').innerHTML = '';
        document.getElementById('uesubtype2').innerHTML = '';
    }
    if(catype ==='us'){
        var div="ussubtype";
        document.getElementById('ussubtype1').innerHTML = '';
        document.getElementById('ussubtype2').innerHTML = '';
    }
    if(catype ==='uc'){
        var div="ucsubtype";
        document.getElementById('ucsubtype1').innerHTML = '';
        document.getElementById('ucsubtype2').innerHTML = '';
    }
    if(catype ==='ss'){
        var div="ucsubtype";
        document.getElementById('sssubtype1').innerHTML = '';
        document.getElementById('sssubtype2').innerHTML = '';
    }
    if(catype ==='sas'){
        var div="sassubtype";
        document.getElementById('sassubtype1').innerHTML = '';
        document.getElementById('sassubtype2').innerHTML = '';
    }

    
    
    
    var values = val.split('@@');
        
        
    if(val=='')
    {
    }
    else
    {
      //var url = "http://192.168.137.230/dialdesk/escalations/getEcr";
      var uqry = "Label=3&slt="+slt+"&divtype="+catype+"&type=Sub Scenario 2&function=getSubType1&parent="+values[0];
      ttf(url,uqry,div);
    }
}

function getSubType1(val,url,catype,slt){
    if(catype ==='a'){
        var div="subtype1";
        document.getElementById('subtype2').innerHTML = '';
    }
    if(catype ==='e'){
        var div="esubtype1";
        document.getElementById('esubtype2').innerHTML = '';
    }
    if(catype ==='s'){
        var div="ssubtype1";
        document.getElementById('ssubtype2').innerHTML = '';
    }
    if(catype ==='c'){
        var div="csubtype1";
        document.getElementById('csubtype2').innerHTML = '';
    }
    
     if(catype ==='ua'){
         var div="usubtype1";
         document.getElementById('usubtype2').innerHTML = '';
     }
    if(catype ==='ue'){
        var div="uesubtype1";
        document.getElementById('uesubtype2').innerHTML = '';
    }
    if(catype ==='us'){
        var div="ussubtype1";
        document.getElementById('ussubtype2').innerHTML = '';
    }
    if(catype ==='uc'){
        var div="ucsubtype1";
        document.getElementById('ucsubtype2').innerHTML = '';
    }
    if(catype ==='ss'){
        var div="sssubtype1";
        document.getElementById('sssubtype2').innerHTML = '';
    }
    if(catype ==='sas'){
        var div="sassubtype1";
        document.getElementById('sassubtype2').innerHTML = '';
    }

   
    var values = val.split('@@');
    if(val=='')
    {
        document.getElementById('subtype2').innerHTML = '';
    }
    else
    {
      //var url = "http://192.168.137.230/dialdesk/escalations/getEcr";
      var uqry = "Label=4&slt="+slt+"&divtype="+catype+"&type=Sub Scenario 3&function=getSubType2&parent="+values[0];
      
      //var div = "subtype1";
      ttf(url,uqry,div);
    }
}

function getSubType2(val,url,catype,slt){
    if(catype ==='a'){var div="subtype2";}
    if(catype ==='e'){var div="esubtype2";}
    if(catype ==='s'){var div="ssubtype2";}
    if(catype ==='c'){var div="csubtype2";}
    if(catype ==='ss'){var div="ssubtype2";}
    
     if(catype ==='ua'){var div="usubtype2";}
    if(catype ==='ue'){var div="uesubtype2";}
    if(catype ==='us'){var div="ussubtype2";}
    if(catype ==='uc'){var div="ucsubtype2";}
    if(catype ==='sas'){var div="sassubtype2";}

   
        var values = val.split('@@');
      //var url = "http://192.168.137.230/dialdesk/escalations/getEcr";
      var uqry = "Label=5&slt="+slt+"&divtype="+catype+"&type=Sub Scenario 4&function=getSubType3&parent="+values[0];
      //var div = "subtype2";
      ttf(url,uqry,div);
}


















