<style>
font{color:#F00; font-size:14px;}
</style>
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
    var clientid    = $("#client").val();
    var processdate = $("#processdate").val();
    var permanent   = $("#permanent").val();
    var validfrom   = $("#validfrom").val();
    var validtill   = $("#validtill").val();
    var datetime    = $("#datetime").val();
    var remarks    = $("#remarks").val();
    


    if(clientid === ""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select Client.</span>');
        return false;
    }
    else if(processdate === ""){
        $("#pro_error").html('<span class="w_msg err" style="color:red;">Please Enter Process date.</span>');
        return false;
    }
    else if(permanent === ""){
        $("#per_error").html('<span class="w_msg err" style="color:red;">Please Select Type of update.</span>');
        return false;
    }
    else if(validfrom === ""){
        $("#frm_error").html('<span class="w_msg err" style="color:red;">Please Select valid from date.</span>');
        return false;
    }
    else if(validtill === ""){
        $("#til_error").html('<span class="w_msg err" style="color:red;">Please Select valid till date.</span>');
        return false;
    }
    else if((new Date(validfrom).getTime()) > (new Date(validtill).getTime())) {
        $("#til_error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else if(datetime === ""){
        $("#date_error").html('<span class="w_msg err" style="color:red;">Please Select Date & Time.</span>');
        return false;
    }
    else if(remarks === ""){
        $("#re_error").html('<span class="w_msg err" style="color:red;">Please Enter Remarks.</span>');
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

</script>
<ol class="breadcrumb">                                
    <li><a href="#">Home</a></li>
    <li><a>Process Updates</a></li>
    <li class="active"><a href="#">Process Updates</a></li>
</ol>
<div class="page-heading">            
    <h1>Process Updates</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Process Updates</h2>
            </div>
            <div class="panel-body">
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('ProcessUpdates',array('action'=>'index',"class"=>"form-horizontal row-border")); ?> 

                <div class="col-md-6">
                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> Date/Time<font>*</font>
                            <?php echo $this->Form->input('datetime',array('label'=>false,'id'=>'datetime','type'=>'datetime-local','required'=>'true','class'=>'form-control','placeholder'=>'Date/Time','required'=>true)); ?>
                            <br><div id="date_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                         
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            Client Name<font>*</font>
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                            <br><div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                         
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            Process Update<font>*</font>
                            <?php echo $this->Form->input('processdate',array('label'=>false,'type'=>'textarea','rows'=>'3','class'=>'form-control','id'=>'processdate','placeholder'=>'Enter Process Update'));?>
                            <br><div id="pro_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                            
                       
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                          <h4 style="margin-left: 150px;">Types Of Update<font>*</font></h4>
                        
                                <?php $options = ['Permanent' => 'Permanent', 'Temporary' => 'Temporary'];?>
                                <?php echo $this->Form->input('permanent',array('label'=>false,'id'=>'permanent','required'=>'true','class'=>'form-control','options'=>$options,'empty'=>'Select','required'=>true)); ?>
                                <br><div id="per_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                            
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                                
                            <div class="form-group">
                         
                                <div class="col-sm-6">
                                Update Valid from<font>*</font>
                                    <?php //echo $this->Form->input('validfrom',array('label'=>false,'class'=>'form-control date-picker','placeholder'=>'Update Valid from','id'=>'validfrom'));?>
                                    <input type="date" name="validfrom" id="validfrom" class="form-control">
                                    <br><div id="frm_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                </div>
                                <div class="col-sm-6">
                                Update Valid till<font>*</font>
                                    <?php //echo $this->Form->input('validtill',array('label'=>false,'class'=>'form-control date-picker','placeholder'=>'Update Valid till','id'=>'validtill'));?>
                                    <input type="date" name="validtill" id="validtill" class="form-control">
                                    <br><div id="til_error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-4" style="margin-top:10px;">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             
                             <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>ProcessUpdates')"  value="Submit" class="btn-web btn" style="margin-top: 462px;">
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
