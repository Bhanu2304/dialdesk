<script> 		
function message_type(type){

    $(".w_msg").remove();
    
    if(type =="Interactive"){
        $("#op_error").html('<span class="w_msg err" style="color:red;">Use # for Seprate Option .</span>');
        $('#opt_error').show();
        //return false;
    }else{
        $('#opt_error').hide();
    }
    
}


function submitForm(form,path)
{
    var clientid = $("#client").val();
    var number   = $("#mobile").val();
    var type     = $("#type").val();
    var request  = $("#request").val();
    var response = $("#response").val();
    var option   = $("#option").val();

    if(clientid === ""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select Client.</span>');
        return false;
    }
    else if(number === "" || number.length != "10"){
        $("#num_error").html('<span class="w_msg err" style="color:red;">Please Enter Correct Number.</span>');
        return false;
    }
    else if(type === "" ){
        $("#type_error").html('<span class="w_msg err" style="color:red;">Please Select Type.</span>');
        return false;
    }
    else if(type == "Interactive" && option=== ""){
        $("#type_error").html('<span class="w_msg err" style="color:red;">Please Enter Options.</span>');
        return false;
    }
    else if(request === "" ){
        $("#req_error").html('<span class="w_msg err" style="color:red;">Please Select Request.</span>');
        return false;
    }
    else if(response === "" ){
        $("#res_error").html('<span class="w_msg err" style="color:red;">Please Select Response.</span>');
        return false;
    }
    

var formData = $(form).serialize(); 

$.post(path,formData).done(function(data){

    //$("#"+removeid).trigger('click');
    $("#show-ecr-message").trigger('click');
    $("#ecr-text-message").text('Data save successfully.');

    
});
return true;
}
function hidepopup(){
    location.reload(); 
}

$(function () {
        $("#option").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
 
            $("#lblError").html("");
 
            //Regex for Valid Characters i.e. Alphabets and Numbers.
            var regex = /^[A-Za-z0-9#]+$/;
 
            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#lblError").html("Only Alphabets and Numbers allowed.");
            }
 
            return isValid;
        });
    });
</script>
<ol class="breadcrumb">                                
    <li><a href="#">Home</a></li>
    <li><a>Social Media</a></li>
    <li class="active"><a href="#">Bot Creation</a></li>
</ol>
<div class="page-heading">            
    <h1>Bot Creation</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Create Bot</h2>
            </div>
            <div class="panel-body">
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('BotMaster',array('action'=>'index',"class"=>"form-horizontal row-border")); ?> 

                <div class="col-md-6">
                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                            <br><div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            Please Enter Phone Number
                            <?php echo $this->Form->input('number',array('label'=>false,'type'=>'number','class'=>'form-control','id'=>'mobile'));?>
                            <br><div id="num_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        </div>
                    </div>

                    <!-- <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            Please Enter Gretting Message 
                            <?php //echo $this->Form->input('gretting',array('label'=>false,'placeholder'=>'Welcome Message','class'=>'form-control' ));?>
                        </div>
                    </div> -->
                </div>


                <div class="col-md-6">
                    

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <!-- <i class="ti ti-user" style="font-size: 18px;">Please Select Message Type</i> -->
                                <?php $options = ['Text' => 'Text', 'Interactive' => 'Interactive'];?>
                                <?php echo $this->Form->input('type',array('label'=>false,'id'=>'type','required'=>'true','class'=>'form-control','options'=>$options,'empty'=>'Select Message Type','required'=>true,'onchange'=>"message_type(this.value);")); ?>
                                <br><div id="type_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                            </span> 
                            
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <!-- <i class="ti ti-user" style="font-size: 18px;">:ShipingPhone: <br><br><br> :BillingName: <br><br><br> :Email: <br><br><br> :ProductName:</i> -->
                                <?php echo $this->Form->input('request',array('label'=>false,'id'=>'request','required'=>'true','class'=>'form-control','placeholder'=>'User Request','required'=>true)); ?>
                                <br><div id="req_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                
                            </span> 
                            
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <!-- <i class="ti ti-user" style="font-size: 18px;">:ShipingPhone: <br><br><br> :BillingName: <br><br><br> :Email: <br><br><br> :ProductName:</i> -->
                                <?php echo $this->Form->input('response',array('label'=>false,'id'=>'response','required'=>'true','class'=>'form-control','placeholder'=>'Bot Response','required'=>true)); ?>
                                <br><div id="res_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                            </span> 
                            
                        </div>
                    </div>
                  
                    <div class="col-xs-12" style="display:none;" id="opt_error">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <!-- <i class="ti ti-user" style="font-size: 18px;">:ShipingPhone: <br><br><br> :BillingName: <br><br><br> :Email: <br><br><br> :ProductName:</i> -->
                                <?php echo $this->Form->input('option',array('label'=>false,'id'=>'option','required'=>'true','class'=>'form-control','placeholder'=>'Option','required'=>true)); ?>
                                <br><div id="op_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                <span id="lblError" style="color: red"></span>
                            </span> 
                            
                        </div>
                    </div>
                 

                    
                </div>
                <div class="col-md-8" style="margin-top:10px;">
                    <div class="col-xs-12">
                        
                    </div>
                </div>

                <div class="col-md-4" style="margin-top:10px;">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <!-- <input type="submit" class="btn btn-web pull-left" value="Add"> -->
                             <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>BotMaster')"  value="Submit" class="btn-web btn">
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
            <a class="btn btn-primary btn-lg" id="show-ecr-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                                <div class="modal-header">
                                    
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                
                                </div>
                                <div class="modal-body">
                                    <p id="ecr-text-message"></p>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                                </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
