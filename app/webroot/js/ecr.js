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
// for category 2 in second frameset
$(document).ready(function()
{$("#category2").on('change',function(){
	$.post("Ecrs/get_label2",{
		parent_id : $('#category2').val(),
		type : 'type1'
		},
		function(data,status){
			 $('#type1').replaceWith(data);
			})})});
//end

// for category 3 in third frameset
$(document).ready(function()
{$("#category3").on('change',function(){
	$.post("Ecrs/get_label2",{
		parent_id : $('#category3').val(),
		type : 'type2'
		},
		function(data,status){
			 $('#type2').replaceWith(data);
			 $('#sub_type1').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_type1" required="required" class="form-control"></select>');
			})})});
//end

// for category 4 in fourth frameset
$(document).ready(function()
{$("#category4").on('change',function(){
	$.post("Ecrs/get_label2",{
		parent_id : $('#category4').val(),
		type : 'type3'
		},
		function(data,status){
			 $('#type3').replaceWith(data);
			 $('#sub_type2').replaceWith('<select name="data[Ecr][sub_type1]" class="form-control" id="sub_type2" required="required"></select>');
			 $('#EcrSubType2').replaceWith('<select name="data[Ecr][sub_type2]" class="form-control" required="required" id="EcrSubType2"></select>');
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
			})})});

//end

// for type 2 in third frameset
$(document).ready(function()
{$("body").on('change',"#type2",function(){$.post("Ecrs/get_label3",{
		parent_id : $('#type2').val(),
		type : 'sub_type1'
		},
		function(data,status){
			 $('#sub_type1').replaceWith(data);
			})})});


// end


// for type 3 in fourth frameset
$(document).ready(function()
{$("body").on('change',"#type3",function(){$.post("Ecrs/get_label3",{
		parent_id : $('#type3').val(),
		type : 'sub_type2'
		},
		function(data,status){
			 $('#sub_type2').replaceWith(data);
			 $('#sub_type2_2').replaceWith('<select required="required" class="form-control" id="sub_type2_2" name="data[Ecr][sub_type2]"></select>');
			})})});

// end

// for sub type2 2 in fourth frameset
$(document).ready(function()
{$("body").on('change',"#sub_type2",function(){$.post("Ecrs/get_label4",{ 
		parent_id : $('#sub_type2').val(),
		type : 'sub_type2_2'
		},
		function(data,status){
			 $('#sub_type2_2').replaceWith(data);
			})})});

// end