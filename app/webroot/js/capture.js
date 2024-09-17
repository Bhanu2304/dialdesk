function view_edit_fields(id){                    
    $.post("ClientFields/edit",{id:id},function(data){
        $("#fields-data").html(data);
    }); 
}
 
 function openCloseFields(id,type){
    if(id =='1'){
        $('#select'+type).show();
        $("#id1").hide();
        $("#id0").show();
    }
    else{
        $('#select'+type).hide();
       $("#id1").show();
        $("#id0").hide();
    }  
}


// For Transaction Fields

function view_trans_edit_fields(id){
    $.post("ClientTranFields/edit",{id:id},function(data){
        $("#fields-data").html(data);
    }); 
}


