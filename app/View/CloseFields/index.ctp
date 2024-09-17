<?php ?>
<script>
function edit_close_fields(id){                    
    $.post("CloseFields/edit",{id:id},function(data){
        $("#close-fields-data").html(data);
    }); 
}
</script>


<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Close Fields</a></li>
</ol>
<div class="page-heading">                                           
<h1>Manage Close Fields</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Manage Close Fields</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('CloseFields',array('action'=>'add',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Name</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('FieldName',array('label'=>false,'placeholder'=>'Field Name','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Type</label>
                        <div class="col-sm-10">
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[CloseFields][FieldType]" id="ClientFieldsFieldTypeTextBox" value="TextBox" required="required" onclick="openCloseFields('0','add');">
                                    Text Box
				</label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[CloseFields][FieldType]" id="ClientFieldsFieldTypeTextBox" value="TextArea" required="required" onclick="openCloseFields('0','add');">
                                    Text Area
				</label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[CloseFields][FieldType]" id="ClientFieldsFieldTypeTextBox" value="DropDown" required="required" onclick="openCloseFields('1','add');">
                                    Drop Down
				</label>
                            </div>
                        </div>
                    </div>
                
                    <div id="selectadd" style="display : none">
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
                                    <input type="radio" name="data[CloseFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Numeric"> Numeric
				</label>
                          </div>
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[CloseFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Char"> Character
				</label>
                          </div>
                            
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[CloseFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Alphanumeric"> Alphanumeric
				</label>
                          </div>
                            
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[CloseFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Datepicker"> Datepicker
				</label>
                          </div>
                            
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[CloseFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Timepicker"> Timepicker
				</label>
                          </div>
                          
                            
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Make This Mandatory</label>
                        <div class="col-sm-10">
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
                    <h2>View Close Fields</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div style="color:green;margin-left:20px;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <div class="panel-body">
                    <?php
                    foreach($fieldName as $post):
                        echo '<div class="form-group"><label class="col-sm-2 control-label">';
                        if($post['CloseField']['FieldType']=='DropDown'){echo "Select ";}
                        echo $post['CloseField']['FieldName'].'</label><div class="col-sm-4">';

                        $req = false;
                        $type = 'text';
                        $fun = "";

                        if($post['CloseField']['RequiredCheck']==1)
                        {
                                $req = true;
                        }
                        if($post['CloseField']['FieldValidation']=='Numeric')
                        {
                                $type = 'Number';
                                $fun = "return isNumberKey(event)";
                        }

                        if($post['CloseField']['FieldType']=='TextBox')
                        {
                                echo $this->Form->input($post['CloseField']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['CloseField']['FieldType']=='TextArea')
                        {
                                echo $this->Form->textArea($post['CloseField']['FieldName'],array('label'=>false,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['CloseField']['FieldType']=='DropDown')
                        {
                                $option = array();
                                $options = explode(',',$fieldValue[$post['CloseField']['id']]);
                                $count = count($options);

                                for( $i=0; $i<$count; $i++)
                                {
                                        $option[$options[$i]] = $options[$i];
                                }
                                echo $this->Form->input($post['CloseField']['FieldName'],array('label'=>false,'empty'=>'Select '.$post['CloseField']['FieldName'],'options'=>$option,'required'=>$req,'class'=>'form-control'));
                        }
                        echo '</div><div class="col-sm-6">';
                        $id = base64_encode($post['CloseField']['id']);
                        //echo $this->Html->link('edit',array('controller'=>'ClientFields','action'=>'edit','?'=>array('id'=>$id)),array('class'=>'btn-raised btn-primary btn'));
                        //echo $this->Html->link('DELETE',array('controller'=>'ClientFields','action'=>'delete_clientfields','?'=>array('id'=>$post['CloseField']['id'])),array('class'=>'btn-raised btn-primary btn','onclick'=>"deleteData()"));
                        ?> 
                 
                        
                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#fieldsUpdate" onclick="edit_close_fields('<?php echo $post['CloseField']['id'];?>')" >
                            <label class="btn btn-xs btn-midnightblue btn-raised">
                                <i class="fa fa-edit"></i><div class="ripple-container"></div>
                            </label>
                        </a> 
                
                        <a href="<?php echo $this->webroot;?>CloseFields/delete_clientfields?id=<?php echo $post['CloseField']['id'];?>" >
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
            <h2>UPDATE Close FIELDS PRIORITY</h2>
            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
        </div>
        <div data-widget-controls="" class="panel-editbox"></div>
<div class="panel-body scrolling">
    <?php echo $this->Form->create('CloseFields',array('action'=>'setPriority','class'=>'form-horizontal row-border')); ?>
        <table cellpadding="2" cellspacing="2" border="0" class="table table-striped table-bordered datatables">
            <thead>
            <tr>
                <th>FIELDS NAME</th>
                <th>FIELDS TYPE</th>
                <th>FIELDS VALIDATION</th>
                <th>REQUIRED</th>
                <th>PRIORITY</th>
            </tr>
            </thead>
            <tbody>
            <?php	foreach($data as $post): ?>
            <tr>
                <td><?=$post['CloseField']['FieldName']?></td>
                <td><?=$post['CloseField']['FieldType']?></td>
                <td><?=$post['CloseField']['FieldValidation']?></td>
                <td><?php if($post['CloseField']['RequiredCheck']==1){echo "Yes";} else{ echo "No";}?></td>
                <td>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input($post['CloseField']['id'],array('label'=>false,'onKeyPress'=>'','autocomplete'=>'off','placeholder'=>'Priority','value'=>$post['CloseField']['Priority'],'class'=>'form-control')); ?>
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
 
    </div>
</div>

<!-- Edit Capture Fields -->
<div class="modal fade" id="fieldsUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;width: 825px;left:100px;">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Close Fields</h2>
            </div>
            <div id="close-fields-data"></div>
        </div>
    </div>
</div>

<?php 
echo $this->Html->script('assets/main/formcomponents');
echo $this->Html->script('assets/main/dialdesk');
?>