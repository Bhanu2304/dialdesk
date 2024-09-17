<?php ?>
<script>
    function obcapture_getDropDown(){
        $('#select').show();   
    }
    function obcapture_closeDropDown(){
        $('#select').hide();
    }
</script>
<script>
function getCampaignId(id){
    //$("#campaignid").val(id.value);
    $("#campaign_form").submit();
}
</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">Out Call Management</a></li>
    <li class="active"><a href="#">Manage Required Fields</a></li>                    
</ol> 
<div class="page-heading">                                           
<h1>Manage Required Fields</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        
        <?php echo $this->Form->create('ObclientFields',array('action'=>'index2','id'=>'campaign_form',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
            <div class="form-group" style="margin-top:-23px;">
                <div class="col-sm-4">
                    <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'value'=>isset($cmid)? $cmid :"",'onchange'=>'getCampaignId(this);','empty'=>'Select Campaign','class'=>'form-control'));?>
                </div>

            </div><br/>
        <?php echo $this->Form->end(); ?>
     
    <?php if(isset($cmid) && $cmid !=""){?>
     
    <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Manage Required Fields</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                
                <?php echo $this->Form->create('ObclientFields',array('action'=>'add',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Name</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->hidden('CampaignId',array('label'=>false,'id'=>'campaignid','value'=>isset($cmid)? $cmid :""));?>
                            <?php echo $this->Form->input('FieldName',array('label'=>false,'placeholder'=>'Field Name','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Type</label>
                        <div class="col-sm-10">
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[ObclientFields][FieldType]" id="ObclientFieldsFieldTypeTextBox" value="TextBox" required="required" onclick="obcapture_closeDropDown()">
                                    Text Box
				</label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[ObclientFields][FieldType]" id="ObclientFieldsFieldTypeTextBox" value="TextArea" required="required" onclick="obcapture_closeDropDown()">
                                    Text Area
				</label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[ObclientFields][FieldType]" id="ObclientFieldsFieldTypeTextBox" value="DropDown" required="required" onclick="obcapture_getDropDown()">
                                    Drop Down
				</label>
                            </div>
                        </div>
                    </div>
                    <div id="select" style="display : none">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Drop Down Field Values</label>
                        <div class="col-sm-6">
                       <input type="text" id="e12" style="width:100% !important" value="" name="down" />
                     </div>
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Validation</label>
                        <div class="col-sm-10">
                          <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[ObclientFields][FieldValidation]" id="ObclientFieldsFieldValidation" value="Numeric">Numeric
				</label>
                          </div>
                          <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[ObclientFields][FieldValidation]" id="ObclientFieldsFieldValidation" value="Char">Char
				</label>
                          </div>
                            
                         <div class="radio radio-inline radio-primary redio-left">
                            <label>
                                <input type="radio" name="data[ObclientFields][FieldValidation]" id="ObclientFieldsFieldValidation" value="Alphanumeric">Alphanumeric
                            </label>
                          </div>
                            
                         <div class="radio radio-inline radio-primary redio-left">
                            <label>
                                <input type="radio" name="data[ObclientFields][FieldValidation]" id="ObclientFieldsFieldValidation" value="Datepicker">Datepicker
                            </label>
                          </div>
                          
                            
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Required</label>
                        <div class="col-sm-6">
                          <div class="checkbox checkbox-inline checkbox-black checkbox-left">
				<label>
                                    <?php echo $this->Form->Checkbox('RequiredCheck',array('label'=>false));?> 
				</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">                        
                        <div class="col-sm-2"></div><div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="ADD" >
                          <div id="hider" style="display:none"><textarea class="form-control fullscreen" name="dd" style="display: none"></textarea></div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        
        
        
        
              
        <?php if(!empty($fieldName)){?>
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>View Required Fields</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div style="color:green;margin-left:20px;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <div class="panel-body">
                    <?php
                    foreach($fieldName as $post):
                        echo '<div class="form-group"><label class="col-sm-2 control-label">';
                        if($post['ObfieldCreation']['FieldType']=='DropDown'){echo "Select ";}
                        echo $post['ObfieldCreation']['FieldName'].'</label><div class="col-sm-4">';

                        $req = false;
                        $type = 'text';
                        $fun = "";

                        if($post['ObfieldCreation']['RequiredCheck']==1)
                        {
                                $req = true;
                        }
                        if($post['ObfieldCreation']['FieldValidation']=='Numeric')
                        {
                                $type = 'Number';
                                $fun = "return isNumberKey(event)";
                        }

                        if($post['ObfieldCreation']['FieldType']=='TextBox')
                        {
                                echo $this->Form->input($post['ObfieldCreation']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['ObfieldCreation']['FieldType']=='TextArea')
                        {
                                echo $this->Form->textArea($post['ObfieldCreation']['FieldName'],array('label'=>false,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['ObfieldCreation']['FieldType']=='DropDown')
                        {
                                $option = array();
                                $options = explode(',',$fieldValue[$post['ObfieldCreation']['id']]);
                                $count = count($options);

                                for( $i=0; $i<$count; $i++)
                                {
                                        $option[$options[$i]] = $options[$i];
                                }
                                echo $this->Form->input($post['ObfieldCreation']['FieldName'],array('label'=>false,'empty'=>'Select '.$post['ObfieldCreation']['FieldName'],'options'=>$option,'required'=>$req,'class'=>'form-control'));
                        }
                        echo '</div><div class="col-sm-6">';
                        $id = base64_encode($post['ObfieldCreation']['id']);
                        //echo $this->Html->link('edit',array('controller'=>'ClientFields','action'=>'edit','?'=>array('id'=>$id)),array('class'=>'btn-raised btn-primary btn'));
                        //echo $this->Html->link('DELETE',array('controller'=>'ClientFields','action'=>'delete_clientfields','?'=>array('id'=>$post['FieldCreation']['id'])),array('class'=>'btn-raised btn-primary btn','onclick'=>"deleteData()"));
                        ?> 
                 
                        
                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#fieldsUpdate" onclick="view_edit_fields('<?php echo $post['ObfieldCreation']['id'];?>','<?php echo $cmid;?>')" >
                            <label class="btn btn-xs btn-midnightblue btn-raised">
                                <i class="fa fa-edit"></i><div class="ripple-container"></div>
                            </label>
                        </a> 
                
                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObclientFields/delete_clientfields?id=<?php echo $post['ObfieldCreation']['id'];?>&CampaignId=<?php echo $cmid;?>')" >
                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                        </a><br/><br/><br/><br/>
                    <?php
        
        
        
        
        echo "</div></div>";

endforeach;

?>
                
            </div>
        </div> 
        
        <?php }?>
        
        
        
        
        
        
   <?php if(!empty($data)){?> 
    <div class="panel panel-default" data-widget='{"draggable": "false"}'>
        <div class="panel-heading">
            <h2>UPDATE Required FIELDS PRIORITY</h2>
            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
        </div>
        <div data-widget-controls="" class="panel-editbox"></div>
<div class="panel-body">
    <?php echo $this->Form->create('ObclientFields',array('action'=>'setPriority','class'=>'form-horizontal row-border')); ?>
    <?php echo $this->Form->hidden('CampaignId',array('label'=>false,'id'=>'campaignid','value'=>isset($cmid)? $cmid :""));?>
        <table cellpadding="2" cellspacing="2" border="0" class="table table-striped table-bordered datatables">
            <thead>
            <tr>
                <th>Field Name</th>
                <th>Field Type</th>
                <th>Field Validation</th>
                <th>Required</th>
                <th>Priority</th>
            </tr>
            </thead>
            <tbody>
            <?php	foreach($data as $post): ?>
            <tr>
                <td><?=$post['ObfieldCreation']['FieldName']?></td>
                <td><?=$post['ObfieldCreation']['FieldType']?></td>
                <td><?=$post['ObfieldCreation']['FieldValidation']?></td>
                <td><?php if($post['ObfieldCreation']['RequiredCheck']==1){echo "Yes";} else{ echo "No";}?></td>
                <td>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input($post['ObfieldCreation']['id'],array('label'=>false,'onKeyPress'=>'','autocomplete'=>'off','placeholder'=>'Priority','value'=>$post['ObfieldCreation']['Priority'],'class'=>'form-control')); ?>
                        </div>
                    </div>
                        
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="form-group">
         
            <div class="col-sm-3">
                <input type="submit" class="btn btn-web"  value="Submit" >
            </div>
         
        </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>  
     <?php }?>
            
   <?php }?>      
    </div>
</div>

<script>
function view_edit_fields(id,cmid){                    
    $.post("ObclientFields/edit",{id:id,cmid:cmid},function(data){
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
</script>

<!-- Edit Capture Fields -->
<div class="modal fade" id="fieldsUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;width: 825px;left:100px;">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Required Fields</h2>
            </div>
            <div id="fields-data"></div>
        </div>
    </div>
</div>



<?php echo $this->Html->script('assets/main/formcomponents'); ?>