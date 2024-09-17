<?php 
$name = '';
$textbox = '';
$textArea = '';
$dropDown = '';
$validation = '';
$require = '';
$id = '';
if(!empty($fieldName))
{
    $id = $fieldName['ObfieldCreation']['id'];
    $name = $fieldName['ObfieldCreation']['FieldName'];
    $textbox =  $fieldName['ObfieldCreation']['FieldType']=='TextBox'?'checked = "checked"':'';
    $textArea = $fieldName['ObfieldCreation']['FieldType']=='TextArea'?'checked = "checked"':'';
    $dropDown = $fieldName['ObfieldCreation']['FieldType']=='DropDown'?'checked = "checked"':'';
    $validation = $fieldName['ObfieldCreation']['FieldValidation'];
    $require = $fieldName['ObfieldCreation']['RequiredCheck'];
    $header = '';
    if($fieldName['ObfieldCreation']['FieldType']=='DropDown')
    {
        $header = 'Drop Down Field Values';
    }
}

?>
<?php
        echo $this->Html->css('assets/plugins/codeprettifier/prettify');
        echo $this->Html->css('assets/plugins/dropdown.js/jquery.dropdown');
        echo $this->Html->css('assets/plugins/progress-skylo/skylo');
        echo $this->Html->css('assets/plugins/form-select2/select2');
        echo $this->Html->css('assets/plugins/form-fseditor/fseditor');
        echo $this->Html->css('assets/plugins/bootstrap-tokenfield/css/bootstrap-tokenfield');
        echo $this->Html->css('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min');
        echo $this->Html->css('assets/plugins/card/lib/css/card');
	
        
        echo $this->Html->script('assets/js/jquery-1.10.2.min');
        echo $this->Html->script('assets/js/jqueryui-1.10.3.min');
        echo $this->Html->script('assets/js/bootstrap.min');
        echo $this->Html->script('assets/js/enquire.min');
        echo $this->Html->script('assets/plugins/velocityjs/velocity.min');
        echo $this->Html->script('assets/plugins/velocityjs/velocity.ui.min');
        echo $this->Html->script('assets/plugins/progress-skylo/skylo');
        echo $this->Html->script('assets/plugins/wijets/wijets');
        echo $this->Html->script('assets/plugins/sparklines/jquery.sparklines.min');
        echo $this->Html->script('assets/plugins/codeprettifier/prettify');
        echo $this->Html->script('assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop');
        echo $this->Html->script('assets/plugins/nanoScroller/js/jquery.nanoscroller.min');
        echo $this->Html->script('assets/plugins/dropdown.js/jquery.dropdown');
        echo $this->Html->script('assets/plugins/bootstrap-material-design/js/material.min');
        echo $this->Html->script('assets/plugins/bootstrap-material-design/js/ripples.min');
        echo $this->Html->script('assets/js/application');
        echo $this->Html->script('assets/demo/demo');
        echo $this->Html->script('assets/demo/demo-switcher');
       
        echo $this->Html->script('assets/plugins/quicksearch/jquery.quicksearch.min');
        echo $this->Html->script('assets/plugins/form-typeahead/typeahead.bundle.min');
        echo $this->Html->script('assets/plugins/form-select2/select2.min');
        echo $this->Html->script('assets/plugins/form-autosize/jquery.autosize-min');
        echo $this->Html->script('assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min');
        echo $this->Html->script('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin');
        echo $this->Html->script('assets/plugins/form-fseditor/jquery.fseditor-min');
        echo $this->Html->script('assets/plugins/form-jasnyupload/fileinput.min');
        echo $this->Html->script('assets/plugins/bootstrap-tokenfield/bootstrap-tokenfield.min'); 
        echo $this->Html->script('assets/plugins/jquery-chained/jquery.chained.min');
        echo $this->Html->script('assets/plugins/jquery-mousewheel/jquery.mousewheel.min');
       
        echo $this->Html->script('assets/plugins/card/lib/js/card');
       echo $this->Html->script('assets/main/formcomponents'); 
         
        
?>

<script>
function capture_getDropDown(){
    $('#select').show();   
}
function capture_closeDropDown(){
    $('#select').hide();
}
</script>

<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Update Capture Field</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>          
                <?php echo $this->Form->create('AdminobclientFields',array('action'=>'update',"class"=>"form-horizontal row-border")); ?>
                    <table class="display table table-bordered table-condensed table-hovered sortableTable">
                        <tr>
                            <td>Field Name</td>
                            <td> <?php echo $this->Form->input('FieldName',array('label'=>false,'value'=>$name,'autocomplete'=>'off','required'=>true,'class'=>'form-control'));?></td>
                        </tr>
                        
                        <tr>
                            <td>Choose Field Type</td>
                            <td>
                                <input type="radio" name="data[AdminobclientFields][FieldType]" id="AdminobclientFieldsFieldTypeTextBox" value="TextBox" required="required" onclick="capture_closeDropDown()"> Text Box<br/>
                                <input type="radio" name="data[AdminobclientFields][FieldType]" id="AdminobclientFieldsFieldTypeTextBox" value="TextArea" required="required" onclick="capture_closeDropDown()"> Text Area<br/>
                                <input type="radio" name="data[AdminobclientFields][FieldType]" id="AdminobclientFieldsFieldTypeTextBox" value="DropDown" required="required" onclick="capture_getDropDown()"> Drop Down
                            </td>
                        </tr>
                        
                        <tr id="select" style="display : none">
                            <td>Drop Down Field Values</td>
                            <td style="width:568px;"><input type="text" id="e12" style="width:100% !important" value="" name="down" /></td>
                        </tr>
                        
                        <tr>
                            <td>Choose Field Validation</td>
                            <td>
                                <?php echo $this->Form->Radio('FieldValidation',array('Numeric'=>'Numeric'),array('legend'=>false,'value'=>$validation=='Numeric'?'Numeric':''));?>
                                <?php echo $this->Form->Radio('FieldValidation',array('Char'=>'Char'),array('legend'=>false,'value'=>$validation=='Char'?'Char':''));?>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Check for Field Required</td>
                            <td><?php echo $this->Form->Checkbox('RequiredCheck',array('label'=>false,'checked'=>$require)); ?></td>
                        </tr>
                        
                        <tr>
                       <?php
                        if(isset($fieldValue))
                        {
                            echo '<td>'.$header."</td><td>";
                            foreach($fieldValue as $post):
                                echo $this->Form->input('oldValues.'.$post['ObfieldValue']['id'],array('label'=>false,'value'=>$post['ObfieldValue']['FieldValueName'], 'required'=>true,'class'=>'form-control'))."<br/>";
                            endforeach;
                            echo '<td></tr>';
                        }
                        ?>                   
                    </table>
                    <button class="btn-raised btn-primary btn">Update</button>
                    <div id="hider" style="display:none"><textarea class="form-control fullscreen" name="dd" style="display: none"></textarea></div>
                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                    <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                <?php echo $this->Form->end();?>
              
            </div>
        </div>
    </div>
</div>



