<script>    
function getClient(){
    $("#cptfields").submit();	
}
function capture_getDropDown(){
    $('#select').show();   
}
function capture_closeDropDown(){
    $('#select').hide();
}
$(document).ready(function() {
    $('#example').DataTable( {
        "pagingType": "full_numbers"
    } );
} ); 
</script>
<?php
     
        //echo $this->Html->css('assets/material-design-iconic-font/css/material-icon');
        //echo $this->Html->css('assets/fonts/font-awesome/css/font-awesome.min');
        //echo $this->Html->css('assets/css/styles');
        //echo $this->Html->css('assets/css/mystyles');
        echo $this->Html->css('assets/plugins/codeprettifier/prettify');
        echo $this->Html->css('assets/plugins/dropdown.js/jquery.dropdown');
        echo $this->Html->css('assets/plugins/progress-skylo/skylo');
        echo $this->Html->css('assets/plugins/form-select2/select2');
        echo $this->Html->css('assets/plugins/form-fseditor/fseditor');
        echo $this->Html->css('assets/plugins/bootstrap-tokenfield/css/bootstrap-tokenfield');
        echo $this->Html->css('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min');
        echo $this->Html->css('assets/plugins/card/lib/css/card');
	
        
        echo $this->Html->script('assets/js/jquery-1.10.2.min');
        //echo $this->Html->script('jquery-1.11.3.min');
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
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Capture Field Creation</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <?php  
                    echo $this->Form->create('AdminclientFields',array('action'=>'index','id'=>'cptfields'));
                    echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true));
                    echo $this->Form->end();
                ?>
                <?php if(isset($clientid) && !empty($clientid)){?>
                <?php echo $this->Form->create('AdminclientFields',array('action'=>'add',"class"=>"form-horizontal row-border")); ?>
                    <table class="display table table-bordered table-condensed table-hovered sortableTable">
                        <tr>
                            <td>Field Name</td>
                            <td><?php echo $this->Form->input('FieldName',array('label'=>false,'placeholder'=>'Field Name','autocomplete'=>'off','required'=>true));?></td>
                        </tr>
                        
                        <tr>
                            <td>Choose Field Type</td>
                            <td>
                                <input type="radio" name="data[AdminclientFields][FieldType]" id="AdminclientFieldsFieldTypeTextBox" value="TextBox" required="required" onclick="capture_closeDropDown()"> Text Box<br/>
                                <input type="radio" name="data[AdminclientFields][FieldType]" id="AdminclientFieldsFieldTypeTextBox" value="TextArea" required="required" onclick="capture_closeDropDown()"> Text Area<br/>
                                <input type="radio" name="data[AdminclientFields][FieldType]" id="AdminclientFieldsFieldTypeTextBox" value="DropDown" required="required" onclick="capture_getDropDown()"> Drop Down
                            </td>
                        </tr>
                        
                        <tr id="select" style="display : none">
                            <td>Drop Down Field Values</td>
                            <td style="width:568px;"><input type="text" id="e12" style="width:100% !important" value="" name="down" /></td>
                        </tr>
                        
                        <tr>
                            <td>Choose Field Validation</td>
                            <td>
                                <input type="radio" name="data[AdminclientFields][FieldValidation]" id="AdminclientFieldsFieldValidation" value="Numeric"> Numeric <br/>
                                <input type="radio" name="data[AdminclientFields][FieldValidation]" id="AdminclientFieldsFieldValidation" value="Char"> Char <br/>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Check for Field Required</td>
                            <td><?php echo $this->Form->Checkbox('RequiredCheck',array('label'=>false));?></td>
                        </tr>
                    </table>
                    <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                    <div id="hider" style="display:none"><textarea class="form-control fullscreen" name="dd" style="display: none"></textarea></div>
                    <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                <?php echo $this->Form->end();?>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<?php if(isset($clientid) && !empty($clientid)){?>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>My Fields</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                 <?php echo $this->Form->create('AdminclientFields',array('action'=>'setPriority','class'=>'form-horizontal row-border')); ?>
                <table id="example" class="display table table-bordered table-condensed table-hovered sortableTable">
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
                <input type="submit" class="btn btn-primary btn-raised"  value="UPDATE PRIORITY" >
                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<?php }?>

