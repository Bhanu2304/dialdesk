// JavaScript Document
function ecr_category()
{
	var  parent = $('#EcrText').val();
	if(parent == '') return;

	var category = document.getElementById("EcrCategory").value;
		
	var option = document.createElement('option');
	option.value=parent;
	option.text = parent;	
	var x = document.getElementById("EcrCategory");
	x.add(option);
	
	var table = document.getElementById("table");
    var row = table.insertRow(1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = parent;
    cell2.innerHTML = category;		

}
function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
// for category 1 in  frameset
$(document).ready(function()
{$("#campaign1").on('change',function(){
	//alert();
	$.post("get_label1",{
		parent_id : $('#campaign1').val(),
		type : 'parent1'
		},
		function(data,status){
			//alert(status);
			 $('#parent1').replaceWith(data);
			})})});
//end

// for category 2 in second frameset
$(document).ready(function()
{$("#campaign2").on('change',function(){
	$.post("get_label1",{
		parent_id : $('#campaign2').val(),
		type : 'parent2'
		},
		function(data,status){
			 $('#parent2').replaceWith(data);
			 $('#type1').replaceWith('<select name="data[Outbounds][parent]" id="type1" required="required"></select>');
			 $('#sub_parent1').replaceWith('<select name="data[Outbound][sub_parent1]" id="sub_parent1" required="required"></select>');
			})})});
//end

// for category 3 in third frameset
$(document).ready(function()
{$("#campaign3").on('change',function(){
	$.post("get_label1",{
		parent_id : $('#campaign3').val(),
		type : 'parent3'
		},
		function(data,status){
			 $('#parent3').replaceWith(data);
			 $('#type2').replaceWith('<select name="data[Outbounds][parent]" id="type2" required="required"></select>');
			 $('#sub_type1').replaceWith('<select name="data[Outbounds][parent3]" id="sub_type1"></select>');
			 //$('#sub_type2').replaceWith('<select required="required" id="sub_type2_2" name="data[Outbound][sub_type2]"></select>');
			})})});

//end

// for category 4 in fourth frameset
$(document).ready(function()
{$("#campaign4").on('change',function(){
	$.post("get_label1",{
		parent_id : $('#campaign4').val(),
		type : 'parent4'
		},
		function(data,status){
			 $('#parent4').replaceWith(data);
			 $('#sub_type2').replaceWith('<select name="data[Outbound][sub_type1]" id="sub_type2" required="required"></select>');
			 $('#type3').replaceWith('<select name="data[Outbounds][parent]" id="type3" required="required"></select>');
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]"></select>');
			})})});

//end

// for type 1 in second frameset
$(document).ready(function()
{$("#parent2").live('change',function(){
	
	$.post("get_label2",{
		parent_id : $('#parent2').val(),
		type : 'type1'
		},
		function(data,status){
			 $('#type1').replaceWith(data);
			})})});


// end


// for type 2 in third frameset
$(document).ready(function()
{$("#parent3").live('change',function(){$.post("get_label2",{
		parent_id : $('#parent3').val(),
		type : 'type2'
		},
		function(data,status){
			 $('#type2').replaceWith(data);
			 $('#sub_type1').replaceWith('<select name="data[Outbounds][parent3]" id="sub_type1">');
			})})});


// end

// for type 2 in third frameset
$(document).ready(function()
{$("#type2").live('change',function(){$.post("get_label3",{
		parent_id : $('#type2').val(),
		type : 'sub_type1'
		},
		function(data,status){
			 $('#sub_type1').replaceWith(data);
			})})});


// end

// for type 3 in fourth frameset
$(document).ready(function()
{$("#parent4").live('change',function(){$.post("get_label2",{
		parent_id : $('#parent4').val(),
		type : 'type3'
		},
		function(data,status){
			 $('#type3').replaceWith(data);
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]"></select>');
			})})});

// end

// for type 2 in third frameset
$(document).ready(function()
{$("#type3").live('change',function(){$.post("get_label3",{
		parent_id : $('#type3').val(),
		type : 'sub_type2'
		},
		function(data,status){
			 $('#sub_type2').replaceWith(data);
			})})});

// end

// for type 2 in fourth frameset
$(document).ready(function()
{$("#sub_type2").live('change',function(){$.post("get_label4",{
		parent_id : $('#sub_type2').val(),
		type : 'sub_type2_2'
		},
		function(data,status){
			 $('#sub_type2_2').replaceWith(data);
			})})});

// end