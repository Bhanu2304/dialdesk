<?php 
$name = '';
$textbox = '';
$textArea = '';
$dropDown = '';
$validation = '';
$require = '';
$id = '';
if(!empty($fieldName)){
    $id = $fieldName['OutboundCloseField']['id'];
    $name = $fieldName['OutboundCloseField']['FieldName'];
    $textbox =  $fieldName['OutboundCloseField']['FieldType']=='TextBox'?'checked = "checked"':'';
    $textArea = $fieldName['OutboundCloseField']['FieldType']=='TextArea'?'checked = "checked"':'';
    $dropDown = $fieldName['OutboundCloseField']['FieldType']=='DropDown'?'checked = "checked"':'';
    
    $numeric       =  $fieldName['OutboundCloseField']['FieldValidation']=='Numeric'?'checked = "checked"':'';
    $char          = $fieldName['OutboundCloseField']['FieldValidation']=='Char'?'checked = "checked"':'';
    $alphaNumeric  = $fieldName['OutboundCloseField']['FieldValidation']=='Alphanumeric'?'checked = "checked"':'';
    $Datepicker  = $fieldName['OutboundCloseField']['FieldValidation']=='Datepicker'?'checked = "checked"':'';
    
    $dropField=$fieldName['OutboundCloseField']['FieldType'];
    
    $validation = $fieldName['OutboundCloseField']['FieldValidation'];
    $require = $fieldName['OutboundCloseField']['RequiredCheck'];
    $header = '';
    if($fieldName['OutboundCloseField']['FieldType']=='DropDown'){
        $header = 'Field Values';
    }
}

?>
<?php echo $this->Form->create('ObcloseFields',array('action'=>'update','class'=>"form-horizontal row-border")); ?>
    <div class="modal-body">
        <div class="panel-body detail">
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Field Name</label>
                        <div class="col-sm-6">
                             <?php echo $this->Form->hidden('CampaignId',array('label'=>false,'id'=>'campaignid','value'=>isset($cmid)? $cmid :""));?>
                            <?php echo $this->Form->input('FieldName',array('label'=>false,'value'=>$name,'autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Field Type</label>
                        <div class="col-sm-9">
                            <div class="radio radio-inline radio-primary redio-left"  >
                                <label>
                                    <input type="radio" name="data[ObcloseFields][FieldType]" id="ClientFieldsFieldTypeTextBox" <?php echo $textbox;?> value="TextBox" required="required" onclick="openCloseFields('0','edit')">
                                    Text Box
                                </label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
                                <label>
                                    <input type="radio" name="data[ObcloseFields][FieldType]" id="ClientFieldsFieldTypeTextBox" <?php echo $textArea;?> value="TextArea" required="required" onclick="openCloseFields('0','edit')">
                                    Text Area
                                </label>
                            </div>
                            <div class="radio radio-inline radio-primary redio-left">
                                <label>
                                    <input type="radio" name="data[ObcloseFields][FieldType]" id="ClientFieldsFieldTypeTextBox" <?php echo $dropDown;?> value="DropDown" required="required" onclick="openCloseFields('1','edit')">
                                    Drop Down
                                </label>
                            </div>
                        </div>
                    </div>
   
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Field Validation</label>
                        <div class="col-sm-9">
                            
                            <div class="radio radio-inline radio-primary redio-left">
				<label>
                                    <input type="radio" name="data[ObcloseFields][FieldValidation]" id="ClientFieldsFieldValidation" <?php echo $numeric;?>  value="Numeric"> Numeric
				</label>
                          </div>
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ObcloseFields][FieldValidation]" id="ClientFieldsFieldValidation" <?php echo $char;?> value="Char"> Character
				</label>
                          </div>
                            
                          <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ObcloseFields][FieldValidation]" id="ClientFieldsFieldValidation" <?php echo $alphaNumeric;?> value="Alphanumeric"> Alphanumeric
				</label>
                          </div>
                            
                            <div class="radio radio-inline radio-primary redio-left" >
				<label>
                                    <input type="radio" name="data[ObcloseFields][FieldValidation]" id="ClientFieldsFieldValidation" <?php echo $Datepicker;?> value="Datepicker"> Datepicker
				</label>
                          </div>
                            
                          
          
                            
                        </div>    
                    </div>
  
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Field Required</label>
                        <div class="col-sm-8">
                            <div class="checkbox checkbox-inline checkbox-black checkbox-left">
                                <label>
                                    <?php echo $this->Form->Checkbox('RequiredCheck',array('label'=>false,'checked'=>$require)); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <?php
                    if(isset($fieldValue)){
                        echo '<div class="form-group"><label class="col-sm-3 control-label">'.$header."</label></div>";
                        foreach($fieldValue as $post):
                            echo '<div class="form-group" >';
                            echo '<label class="col-sm-3 control-label">&nbsp;</label>';
                            echo '<div class="col-sm-6">';
                            echo $this->Form->input('oldValues.'.$post['ObfieldValue']['id'],array('label'=>false,'value'=>$post['ObfieldValue']['FieldValueName'], 'required'=>true,'class'=>'form-control'));
                            echo "</div></div>";
                        endforeach;
                    }
                    ?>
                    
                    <div id="selectedit" style="display:none;">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">&nbsp;</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="e12" style="width:100% !important" value="" name="down" /> 
                                <span>Use comma to enter multiple value.</span>
                            </div>
                        </div>
                    </div>

                    <?php if($dropField ==="DropDown"){?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-6" id="id1">
                           Add New <label class='btn btn-xs btn-midnightblue btn-raised' onclick="openCloseFields('1','edit')" ><i class='fa fa-plus'></i></label> 
                        </div>
                        <div class="col-sm-6" id="id0" style="display:none;">
                           Hide <label class='btn btn-xs btn-midnightblue btn-raised' onclick="openCloseFields('0','edit')" ><i class='fa fa-minus'></i></label> 
                        </div>
                    </div>
                    <?php }?>
                    
                    

                </div>
            </div>
        </div>   
    </div>
    <div class="modal-footer">
        <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn-web btn">Submit</button>
        <div id="hider" style="display:none"><textarea class="form-control fullscreen" name="dd" style="display: none"></textarea></div>
        <input type="hidden" value="<?php echo $id; ?>" name="id">
    </div>
     
<?php echo $this->Form->end(); ?> 
<script src="<?php echo $this->webroot;?>js/assets/js/application.js"></script>
<script src="<?php echo $this->webroot;?>js/assets/main/formcomponents.js"></script>


