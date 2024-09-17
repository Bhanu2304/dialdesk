<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Required Fields</a></li>
</ol>
<div class="page-heading">                                           
<h1>Manage Required Fields</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Manage Required Fields</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('ClientFields',array('action'=>'add',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Name</label>
                        <div class="col-sm-10">
                            <?php echo $this->Form->input('FieldName',array('label'=>false,'placeholder'=>'Field Name','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                        <!-- <div class="col-sm-6"></div> -->
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Field Type</label>
                        <div class="col-sm-10">
                            <div class="radio radio-inline radio-primary redio-left">
				                <label>
                                    <input type="radio" name="data[ClientFields][FieldType]" id="ClientFieldsFieldTypeTextBox" value="TextBox" required="required" onclick="openCloseFields('0','add');">
                                    Text Box
				                </label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
				                <label>
                                    <input type="radio" name="data[ClientFields][FieldType]" id="ClientFieldsFieldTypeTextBox" value="TextArea" required="required" onclick="openCloseFields('0','add');">
                                    Text Area
				                </label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
				                <label>
                                    <input type="radio" name="data[ClientFields][FieldType]" id="ClientFieldsFieldTypeTextBox" value="DropDown" required="required" onclick="openCloseFields('1','add');">
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
                                    <input type="radio" name="data[ClientFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Numeric"> Numeric
				</label>
                          </div>
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ClientFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Char"> Character
				</label>
                          </div>
                            
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ClientFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Alphanumeric"> Alphanumeric
				</label>
                          </div>
                            
                        <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ClientFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Datepicker"> Datepicker
				</label>
                          </div>
                            
                        <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ClientFields][FieldValidation]" id="ClientFieldsFieldValidation" value="Phone"> Phone
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
                    <h2>View Required Fields</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div style="color:green;margin-left:20px;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <div class="panel-body">
                    <?php
                    foreach($fieldName as $post):
                        echo '<div class="form-group"><label class="col-sm-2 control-label">';
                        if($post['FieldCreation']['FieldType']=='DropDown'){echo "Select ";}
                        echo $post['FieldCreation']['FieldName'].'</label><div class="col-sm-4">';

                        $req = false;
                        $type = 'text';
                        $fun = "";

                        if($post['FieldCreation']['RequiredCheck']==1)
                        {
                                $req = true;
                        }
                        if($post['FieldCreation']['FieldValidation']=='Numeric')
                        {
                                $type = 'Number';
                                $fun = "return isNumberKey(event)";
                        }

                        if($post['FieldCreation']['FieldType']=='TextBox')
                        {
                                echo $this->Form->input($post['FieldCreation']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['FieldCreation']['FieldType']=='TextArea')
                        {
                                echo $this->Form->textArea($post['FieldCreation']['FieldName'],array('label'=>false,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['FieldCreation']['FieldType']=='DropDown')
                        {
                                $option = array();
                                $options = explode(',',$fieldValue[$post['FieldCreation']['id']]);
                                $count = count($options);

                                for( $i=0; $i<$count; $i++)
                                {
                                        $option[$options[$i]] = $options[$i];
                                }
                                echo $this->Form->input($post['FieldCreation']['FieldName'],array('label'=>false,'empty'=>'Select '.$post['FieldCreation']['FieldName'],'options'=>$option,'required'=>$req,'class'=>'form-control'));
                        }
                        echo '</div><div class="col-sm-6">';
                        $id = base64_encode($post['FieldCreation']['id']);
                        //echo $this->Html->link('edit',array('controller'=>'ClientFields','action'=>'edit','?'=>array('id'=>$id)),array('class'=>'btn-raised btn-primary btn'));
                        //echo $this->Html->link('DELETE',array('controller'=>'ClientFields','action'=>'delete_clientfields','?'=>array('id'=>$post['FieldCreation']['id'])),array('class'=>'btn-raised btn-primary btn','onclick'=>"deleteData()"));
                        ?> 
                 
                        
                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#fieldsUpdate" onclick="view_edit_fields('<?php echo $post['FieldCreation']['id'];?>')" >
                            <label class="btn btn-xs btn-midnightblue btn-raised">
                                <i class="fa fa-edit"></i><div class="ripple-container"></div>
                            </label>
                        </a> 
                
                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ClientFields/delete_clientfields?id=<?php echo $post['FieldCreation']['id'];?>')" >
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
<div class="panel-body scrolling">
    <?php echo $this->Form->create('ClientFields',array('action'=>'setPriority','class'=>'form-horizontal row-border')); ?>
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
                <td><?=$post['FieldCreation']['FieldName']?></td>
                <td><?=$post['FieldCreation']['FieldType']?></td>
                <td><?=$post['FieldCreation']['FieldValidation']?></td>
                <td><?php if($post['FieldCreation']['RequiredCheck']==1){echo "Yes";} else{ echo "No";}?></td>
                <td>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input($post['FieldCreation']['id'],array('label'=>false,'onKeyPress'=>'','autocomplete'=>'off','placeholder'=>'Priority','value'=>$post['FieldCreation']['Priority'],'class'=>'form-control')); ?>
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
                <h2 class="modal-title">Edit Fields</h2>
            </div>
            <div id="fields-data"></div>
        </div>
    </div>
</div>

<?php 
echo $this->Html->script('assets/main/formcomponents');
echo $this->Html->script('assets/main/dialdesk');
?>