function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
  return true;
}

$(document).ready(function(){
    $("#campaign1").on('change',function(){
        $.post("Obecrs/get_label1",{parent_id : $('#campaign1').val(),type : 'parent1'},function(data,status){
            $('#parent1').replaceWith(data);
        });
    });
    
    $("#campaign2").on('change',function(){
        $.post("Obecrs/get_label1",{parent_id : $('#campaign2').val(),type : 'parent2'},function(data,status){
            $('#parent2').replaceWith(data);
            $('#type1').replaceWith('<select name="data[Obecrs][parent]" id="type1" class="form-control" required="required"></select>');
            $('#sub_parent1').replaceWith('<select name="data[Obecrs][sub_parent1]" id="sub_parent1" class="form-control" required="required"></select>');
        });
    });
    
    $("#campaign3").on('change',function(){
        $.post("Obecrs/get_label1",{parent_id : $('#campaign3').val(),type : 'parent3'},function(data,status){
            $('#parent3').replaceWith(data);
            $('#type2').replaceWith('<select name="data[Obecrs][parent]" id="type2" class="form-control" required="required"></select>');
            $('#sub_type1').replaceWith('<select name="data[Obecrs][parent3]" class="form-control" id="sub_type1"></select>');
	});
    });
    
    $("#campaign4").on('change',function(){
	$.post("Obecrs/get_label1",{parent_id : $('#campaign4').val(),type : 'parent4'},function(data,status){
            $('#parent4').replaceWith(data);
            $('#sub_type2').replaceWith('<select name="data[Obecrs][sub_type1]" id="sub_type2" class="form-control" required="required"></select>');
            $('#type3').replaceWith('<select name="data[Obecrs][parent]" id="type3" class="form-control" required="required"></select>');
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
        });
    });
    
     
    $("#parent2").live('click',function(){
	$.post("Obecrs/get_label2",{parent_id : $('#parent2').val(),type : 'type1'},function(data,status){
            $('#type1').replaceWith(data);
	});
        
    });
    
    
    
    
    
});




                
                
                







$(document).ready(function()
{$("#parent3").on('change',function(){$.post("get_label2",{
		parent_id : $('#parent3').val(),
		type : 'type2'
		},
		function(data,status){
			 $('#type2').replaceWith(data);
			 $('#sub_type1').replaceWith('<select name="data[Obecrs][parent3]" id="sub_type1">');
			})})});


$(document).ready(function()
{$("#type2").on('change',function(){$.post("get_label3",{
		parent_id : $('#type2').val(),
		type : 'sub_type1'
		},
		function(data,status){
			 $('#sub_type1').replaceWith(data);
			})})});

$(document).ready(function()
{$("#parent4").on('change',function(){$.post("get_label2",{
		parent_id : $('#parent4').val(),
		type : 'type3'
		},
		function(data,status){
			 $('#type3').replaceWith(data);
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]"></select>');
			})})});

$(document).ready(function()
{$("#type3").on('change',function(){$.post("get_label3",{
		parent_id : $('#type3').val(),
		type : 'sub_type2'
		},
		function(data,status){
			 $('#sub_type2').replaceWith(data);
			})})});


$(document).ready(function()
{$("#sub_type2").on('change',function(){$.post("get_label4",{
		parent_id : $('#sub_type2').val(),
		type : 'sub_type2_2'
		},
		function(data,status){
			 $('#sub_type2_2').replaceWith(data);
			})})});

// end


/*

$(document).ready(function()
{$("#category2").on('change',function(){
	$.post("Obecrs/get_label2",{
		parent_id : $('#category2').val(),
		type : 'type1'
		},
		function(data,status){
			 $('#type1').replaceWith(data);
			})})});








$(document).ready(function()
{$("#category3").on('change',function(){
	$.post("Obecrs/get_label2",{
		parent_id : $('#category3').val(),
		type : 'type2'
		},
		function(data,status){
			 $('#type2').replaceWith(data);
			 $('#sub_type1').replaceWith('<select name="data[Obecr][sub_type1]" id="sub_type1" required="required" class="form-control"></select>');
			})})});

$(document).ready(function()
{$("#category4").on('change',function(){
	$.post("Obecrs/get_label2",{
		parent_id : $('#category4').val(),
		type : 'type3'
		},
		function(data,status){
			 $('#type3').replaceWith(data);
			 $('#sub_type2').replaceWith('<select name="data[Obecr][sub_type1]" class="form-control" id="sub_type2" required="required"></select>');
			 $('#ObecrSubType2').replaceWith('<select name="data[Obecr][sub_type2]" class="form-control" required="required" id="ObecrSubType2"></select>');
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Obecr][sub_type2]"></select>');
			})})});


$(document).ready(function()
{$("body").on('change',"#type2",function(){$.post("Obecrs/get_label3",{
		parent_id : $('#type2').val(),
		type : 'sub_type1'
		},
		function(data,status){
			 $('#sub_type1').replaceWith(data);
			})})});





$(document).ready(function()
{$("body").on('change',"#type3",function(){$.post("Obecrs/get_label3",{
		parent_id : $('#type3').val(),
		type : 'sub_type2'
		},
		function(data,status){
			 $('#sub_type2').replaceWith(data);
			 $('#sub_type2_2').replaceWith('<select required="required" class="form-control" id="sub_type2_2" name="data[Obecr][sub_type2]"></select>');
			})})});




$(document).ready(function()
{$("body").on('change',"#sub_type2",function(){$.post("Obecrs/get_label4",{
		parent_id : $('#sub_type2').val(),
		type : 'sub_type2_2'
		},
		function(data,status){
			 $('#sub_type2_2').replaceWith(data);
			})})});

*/
