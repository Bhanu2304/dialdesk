<?php echo $this->Html->script('assets/main/dialdesk');?>
<script>
    function submitForm(form,path){

            var priority = $("#priority_data").val();
            var group_id = $("#group_id").val();
            //var formData = $(form).serialize(); 


            if(priority == ""){
                $('#priority').focus();
                $("#elMsg").html('Priority field is required.').show();  
                return false;
            }
            else if(priority.length > 2){
                $('#priority').focus();
                $("#elMsg").html('Please Enter Less Than 100.').show();  
                return false;
            }

            $.ajax({
            type: "POST",
            url:path,
            data: {group_id:group_id,priority:priority},
            success: function(data){
                    if(data !=''){
                        $("#close-login-popup").trigger('click');
                        $("#show-ecr-message").trigger('click');
                        $("#ecr-text-message").text('Data update successfully.');
                    }
            }
	});
            return true;
}
function hidepopup(){
    location.reload(); 
}
</script>
<script> 		
function validateExport(url){
    
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>InboundGroups/export_report');
        }
       else{
            $('#validate-form').attr('action','<?php echo $this->webroot;?>InboundGroups/report');
        }
        $('#validate-form').submit();
        return true;
    }

</script>
<script>

function show_details(id,name,priority)
{
    $("#group_id").val(id);
    $("#group_name").val(name);
    $("#priority_data").val(priority);
}
function allowonlynumber(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
            return true;

    }
    catch (err) {
        alert(err.Description);
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a>Inbound Groups</a></li>
    <li class="active"><a href="#">Inbound Groups</a></li>
</ol>
<div class="page-heading margin-top-head">            
    <h2>Inbound Groups</h2>
    
</div>
<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
        <div class="panel-heading">
                        <h2>Inbound Groups</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body">
                    <div class="col-sm-2" style="margin-top:-8px;">
                    <?php echo $this->Form->create('InboundGroups',array('action'=>'export_report','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export">
                            <?php $this->Form->end(); ?>
                            
                        </div>
                    <?php if(isset($dataArr) && !empty($dataArr)){?>
                     <?php echo $this->Form->create('InboundGroup',array('action'=>'update_priority')); ?> 
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                   <th>Sr No.</th>
                                    <th>Company Name</th>
                                    <th>Group Id</th>
                                    <th>Group Name</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php $i =0; foreach($shortArr as $data1=>$key1)
                        {
                            // echo $key1;
                            
                            foreach($dataArr[$key1] as $now){  
                                #print_r($data);exit;
                                ?>

                                    <tr>
                                       <td><?php echo ++$i;?></td>
                                        <td><?php echo $now['0']['company_name']; ?></td>
                                        <td><?php echo $now['vicidial_inbound_groups']['group_id'];?></td>
                                        <td><?php echo $now['vicidial_inbound_groups']['group_name'];?></td>
                                        <td><?php echo $now['vicidial_inbound_groups']['queue_priority']; ?></td>

                                        
                                       
                                        <td>
                                            <a  href="#" data-toggle="modal" data-target="#update" onclick="show_details('<?php echo $now['vicidial_inbound_groups']['group_id'];?>','<?php echo $now['vicidial_inbound_groups']['group_name'];?>','<?php echo $now['vicidial_inbound_groups']['queue_priority'];?>')" >
                                                <label class="btn btn-xs btn-midnightblue btn-raised">
                                                    <i class="fa fa-edit"></i><div class="ripple-container"></div>
                                                </label>
                                            </a>
                                        </td>   
                                    </tr>
                          <?php  } 
                        }   ?>
                            </tbody>
                        </table>
                           
                        <?php } ?>
                    </div>
                    
        </div>

    </div>
</div>
<!-- Edit priority Popup -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Inbound Groups </h2>
            </div>
           <?php echo $this->Form->create('InboundGroup',array('action'=>'index',"class"=>"form-horizontal row-border")); ?>
            <div class="modal-body">
            <p><div id="elMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div></p>
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                        <div class="col-md-12">
                            <div class="col-xs-2">
                                <div class="input-group">
                                    Group Id
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group">
                                <?php echo $this->form->input('name',array('label'=>false,'id'=>'group_id','class'=>'form-control', 'readonly'=>true));?>
                            </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    Group Name
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group">							
                                    <?php echo $this->form->input('emailid',array('label'=>false,'id'=>'group_name','class'=>'form-control','readonly'=>true));?>
                            </div>
                            </div>
                            </div>
                            <div class="col-md-12">
                            <div class="col-xs-2">
                                <div class="input-group">
                                    Priority
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group">							
                                    <?php echo $this->form->input('priority',array('label'=>false,'id'=>'priority_data',"onkeypress"=>"return allowonlynumber(event,this)",'class'=>'form-control','autocomplete'=>'off',"maxlength"=>"99"));?>
                            </div>
                            </div>

                            
                            
                            </div>
                        </div>
                        
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
               <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>InboundGroups/index')"  value="Submit" class="btn-web btn">
            </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
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