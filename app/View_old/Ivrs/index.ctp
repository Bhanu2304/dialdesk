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
    <li class="active"><a href="#">Manage IVR</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Manage IVR</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="row">
            <div class="col-md-12">               
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Manage IVR</h2>
                        <a style="float:right;" class="btn btn-primary"  data-toggle="modal" data-target="#uploadivr">Upload IVR Fiels</a> 
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
        <?php if(isset($ivrfile) && $ivrfile !=""){?>
        <div class="row">
            <div class="col-md-12">               
                <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>View IVR File</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>FILE NAME</th>
                                <th>DATE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;foreach($ivrfile as $row){?>
                                <tr >
                                    <td><?php echo $i++;?></td>
                                    <td><?php echo $row['IvrFileMaster']['file_name'];?></td>
                                    <td><?php echo $row['IvrFileMaster']['create_date'];?></td>
                                    <td class="center">
                                        <a  href="<?php echo $this->webroot;?>Ivrs/download_ivr_file?file=<?php echo $row['IvrFileMaster']['file_name'];?>">
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label>
                                        </a>           
                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Ivrs/delete_ivr_file?id=<?php echo $row['IvrFileMaster']['id'];?>')" >
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                        </a>
                                        
                                    </td>  
                                </tr>
                            <?php }?>

                        </tbody>
                    </table>
                </div>
                <div class="panel-footer"></div>
            </div>        
            </div>
        </div>
        <?php }?> 
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
