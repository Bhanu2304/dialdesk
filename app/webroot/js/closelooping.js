function closeLoopValidate(){
    var Category1=$("#Category1").val();
    var close_loop= $("#close_loop").val();
    var create_category= $("#create_category").val();
    var category_label= $("#category_label").val();
    var parent_category= $("#parent_category").val();
    var checkdate= $("#checkdate").val();
    
    var r = document.getElementsByName("InCallStatus")
    var c = -1

    for(var i=0; i < r.length; i++){
       if(r[i].checked) {
          c = i; 
       }
    }
    
    
    
    if(document.getElementById('orderby').checked == true && $.trim($("#orderno").val()) ===""){
        $("#orderno").focus();
        $("#erroMsg").html('Pleae enter order no.');
        return false; 
    }
    else if($.trim(Category1) ===""){
            $("#Category1").focus();
            $("#erroMsg").html('Select scenario.');
            return false;
    }
    else if($.trim(close_loop) ===""){
            $("#close_loop").focus();
            $("#erroMsg").html('Select action type.');
            return false;
    }
    else if($.trim(category_label) ===""){
            $("#category_label").focus();
            $("#erroMsg").html('Select action label.');
            return false;
    }
    else if(category_label ==="1" && $.trim(create_category) ===""){
            $("#create_category").focus();
            $("#erroMsg").html('Create action category.');
            return false;
    }
    else if(category_label ==="2" && $.trim(parent_category) ===""){
            $("#parent_category").focus();
            $("#erroMsg").html('Select Parent action.');
            return false;
    }
    else if(category_label ==="2" && $.trim(create_category) ===""){
            $("#create_category").focus();
            $("#erroMsg").html('Create action category.');
            return false;
    }
    else if(category_label ==="1" && c == -1){
            $("#InCallStatus").focus();
            $("#erroMsg").html('Please select in call status.');
            return false;
    }
    
    else{
        return true;	
    }
}

function checkOption(df,ch,cl){
    $('#'+df).hide();
    if (document.getElementById(ch).checked){$('#'+df).show();}
    else{$('#'+cl).val('');}
}



function getCloseLoopCategory(path,catid,maxid){  
    if($("#"+maxid[0]).val() ==="manual"){
        $("#"+maxid[1]).show();
        $("#dateoption").show();
        $("#editdateoption").show();
    }
    else{
        $("#"+maxid[1]).hide();
        $("#dateoption").hide();
        $("#editdateoption").hide();  
    }
    $.ajax({
        type:'post',
        url:path,
        data:{cat1:$("#"+catid[0]).val(),cat2:$("#"+catid[1]).val(),cat3:$("#"+catid[2]).val(),cat4:$("#"+catid[3]).val(),cat5:$("#"+catid[4]).val(),close_loop:$("#"+maxid[0]).val()},
        success:function(data){
            $("#"+maxid[2]).html(data);   
        }
    });
}

function updateCloseLoop(form,path){
    var formData = $(form).serialize();   
    
    var Category1=$("#editCategory1").val();
    var close_loop= $("#edit_close_loop").val();
    var close_loop_category= $("#edit_close_loop_category").val();
    var category_label= $("#edit_category_label").val();
    var parent_category= $("#edit_parent_category").val();
  
    if($.trim(Category1) ===""){
            $("#editCategory1").focus();
            $("#erroMsg1").html('Select Category.');
            return false;
    }
    else if($.trim(close_loop) ===""){
            $("#edit_close_loop").focus();
            $("#erroMsg1").html('Select close looping.');
            return false;
    }
    else if($.trim(category_label) ===""){
            $("#edit_category_label").focus();
            $("#erroMsg1").html('Select action label.');
            return false;
    }
    else if(category_label ==="1" && $.trim(close_loop_category) ===""){
            $("#edit_close_loop_category").focus();
            $("#erroMsg1").html('Create action category.');
            return false;
    }
    else if(category_label ==="2" && $.trim(parent_category) ===""){
            $("#edit_parent_category").focus();
            $("#erroMsg1").html('Select Parent action.');
            return false;
    }
    else if(category_label ==="2" && $.trim(close_loop_category) ===""){
            $("#edit_close_loop_category").focus();
            $("#erroMsg1").html('Create action category.');
            return false;
    }
   
    else{
        $.post(path,formData).done(function(data){
        if(data ==='1'){
           $("#erroMsg1").html('Already exist in database.');
           return false;
        }
        else if(data ==='2'){
           $("#erroMsg1").html('Already created for manual close looping.');
           return false;
        }
        else if(data ==='3'){
           $("#erroMsg1").html('Already created for system close looping.');
           return false;
        }
        else if(data ==='4'){
          $("#close-loop-popup").trigger('click');
          $("#show-loop-message").trigger('click');
          $("#loop-text-message").text('Close looping update successfully.');
        }
    });	
    }
    
    
    
 
   
}

function hideMsgpopup(){
    location.reload(); 
}
