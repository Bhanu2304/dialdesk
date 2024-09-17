<?php 
echo $this->Html->css('parentChild/style');		
echo $this->Html->script('parentChild/jquery-migrate-1.2.1.min');
echo $this->Html->script('parentChild/jquery-ui');
echo $this->Html->script('parentChild/jquery.tree');
?>
<script>
$(document).ready(function() {
    $('.tree').tree_structure({
        'add_option': true,
        'edit_option': true,
        'delete_option': true,
        'confirm_before_delete': true,
        'animate_option': false,
        'fullwidth_option': false,
        'align_option': 'center',
        'draggable_option': true
    });
    
    //$(".messageBox").hide().delay(5000).fadeOut();
});
</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Create Bot</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Create Bot</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="row">
            <div class="col-md-12">               
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Create Bot</h2>
                        <!-- <a style="float:right;" class="btn btn-primary"  data-toggle="modal" data-target="#uploadivr">Upload Bot Fields</a>  -->
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div style="margin-left:18px;color:green;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body" >
                       <?php echo $html;?>
                    </div>
                </div>        
            </div>
        </div>
        
    </div>
</div>

<script>
    function validateIVR(){
        $("#ivr_box").hide().delay(5000).fadeOut();
        $("#ivr_msgbox").html('');
        
        if($("#ivrfile").val() ===""){
            $("#ivr_box").show();
            document.getElementById("ivr_box").className = "error";
            $("#ivr_msgbox").html('Please select ivr file.');
            return false;
        }
        else{
            return true;
        } 
    }
</script>

<div class="modal fade" id="uploadivr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" action="<?php echo $this->webroot;?>Ivrs/upload_ivr_file" method="post"  accept-charset="utf-8" onsubmit="return validateIVR()" id="upload_ivr_form" enctype="multipart/form-data" >                 
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h2 class="modal-title">Upload IVR</h2>
                </div>
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">
                                <div id="ivr_box" style="margin-left:144px;width:355px;" >
                                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <span id="ivr_msgbox"></span>
                                </div>
                                  <div class="form-group">
                                        <label for="addon3" class="col-sm-3 control-label">Upload IVR</label>
                                        <input type="file" multiple="multiple" name="ivrfile[]"  accept="audio/mpeg"  id="ivrfile" >
                                        <div class="col-sm-8 input-group">
                                            <input type="text" readonly id="addon3" class="form-control" placeholder="Select File">
                                            <span class="input-group-btn input-group-sm">
                                                <button class="btn btn-fab btn-fab-mini" type="button">
                                                    <i class="material-icons">attach_file</i>
                                                </button>
                                            </span>
                                           
                                        </div>                                
                                        <span style="margin-left: 160px;">Note - (upload Only mp3,mp4 file)</span>
                                    </div>
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-web" value="Submit" >
                </div>
            </form>
        </div>
    </div>
</div>
