// JavaScript Document
function ob_getDropDown()
{
	var name = document.getElementById("ObCreationsFieldName").value;
	
	try{
		if(document.getElementById("drop").value=='' || document.getElementById("drop").value!=''){ return;}		
	}
	catch(err){}
	var select = "<select name=\"FieldValue\" id=\"FieldValue\">";
	select = select+"<option>Select "+name+"</option>";
	select = select+"</select>";
	

    var table = document.getElementById("table");
    var row = table.insertRow(4);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML =  "Please Fill Drop Down Value";
    cell2.innerHTML =  "<input type='text' id='drop' value='' name='drop' /> <font color=red size=\"1\"><b>(Field Value Shown Below Drop Down)<b><font>";

    row = table.insertRow(5);
    cell1 = row.insertCell(0);
    cell2 = row.insertCell(1);
    cell1.innerHTML = "Action";
    cell2.innerHTML = "<Button name='AddField' onClick='return capture_addField()'>ADD</Button>";

    row = table.insertRow(6);
    cell1 = row.insertCell(0);
    cell2 = row.insertCell(1);
    cell1.innerHTML = "Your Drop Down Field Look Like";
    cell2.innerHTML = select;
	
}// JavaScript Document3

function capture_addField()
{
	var  parent = $('#drop').val();
	$('#drop').val("");
	if(parent == '') return;

	var str = document.getElementById("down").value;
	var coma = '';
	if(str==''){}else{coma=',';}
	document.getElementById("down").value=str+coma+parent;
	
	var option = document.createElement('option');
	option.value=parent;
	option.text = parent;	
	var x = document.getElementById("FieldValue");
	x.add(option);
	return false;
}
function capture_closeDropDown()
{
	if(document.getElementById("drop").value=='' || document.getElementById("drop").value!='')
	{
    	var table = document.getElementById("table");
    	var row  = table.deleteRow(4);
		row  = table.deleteRow(4);
		row  = table.deleteRow(4);
	}

}