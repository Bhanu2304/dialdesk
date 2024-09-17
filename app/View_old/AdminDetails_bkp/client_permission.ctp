<script>    
function createClientPlan(){
	$('#client_permission').attr('action', '<?php echo $this->webroot;?>AdminDetails/client_permission');
	$("#client_permission").submit();	
}
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Assign Client Permissions</h5>
            </header>
            <div id="div-1" class="accordion-body collapse in body">
                <?php echo $this->Form->create('AdminDetails',array('action'=>'update_client_permissions','id'=>'client_permission')); ?>
                    <h3>Client Permissions</h3>
                    <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                    <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                        <tr>
                            <td>
                                <?php 
                                echo $this->form->label('Client Name');
                                echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'createClientPlan();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true));
                                ?>
                            </td>
                        </tr>
                        <?php if(isset($clientid) && !empty($clientid)){ ?>
                        <tr>
                            <td>
                                <input type="radio" name="data[AdminDetails][permission]" <?php if(isset($clpermission['RegistrationMaster']['status']) && $clpermission['RegistrationMaster']['status'] =="A" ){echo "checked='checked'";} ?> value="A" > Active <br/>
                                <input type="radio" name="data[AdminDetails][permission]" <?php if(isset($clpermission['RegistrationMaster']['status']) && $clpermission['RegistrationMaster']['status'] =="D" ){echo "checked='checked'";} ?> value="D"  > Deactive
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <textarea name="data[AdminDetails][status_remarks]"><?php echo isset($clpermission['RegistrationMaster']['status_remarks'])?$clpermission['RegistrationMaster']['status_remarks']:"";?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" class="btn-raised btn-primary btn"  value="Submit" ></td>
                        </tr>
                        <?php }?>
                    </table>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
