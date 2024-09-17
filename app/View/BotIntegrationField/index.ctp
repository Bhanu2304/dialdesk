<?php ?>
<script> 		
    function validateExport(url)
    {
        $(".w_msg").remove();
        
        
        var client_id=$("#client").val();
        var fdate=$("#fdate").val();
        var ldate=$("#ldate").val();

        if(client_id ===""){
            $("#error").html('<span class="w_msg err" style="color:red;">Please select client.</span>');
            return false;
        }
        else if(fdate ===""){
            $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
            return false;
        }
        else if(ldate ===""){
            $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
            return false;
        }
        else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
            $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
            return false;
        }
        else{
            if(url ==="download"){
                $('#validate-form').attr('action','<?php echo $this->webroot;?>C2PWallet/export_tentative');
            }
            if(url ==="view"){
                $('#validate-form').attr('action','<?php echo $this->webroot;?>C2PWallet/c2p_tentative_statement');
            }
            $('#validate-form').submit();
            return true;
        }
    }


    function getClient(){
        $("#client_form").submit();	
    } 

    function handleFieldSelection(checkbox) 
    {
        var fieldName = checkbox.getAttribute('data-field-name');
        var fieldId = checkbox.value;
        
        if (checkbox.checked) 
        {
        
            var container = document.getElementById('selected-fields-container');
            var selectedFieldDiv = document.createElement('div');
            selectedFieldDiv.className = 'selected-field';
            selectedFieldDiv.id = 'selected_' + fieldId;
            
            selectedFieldDiv.innerHTML = `<div class="form-group"><label>${fieldName}</label></div>`;
            
            container.appendChild(selectedFieldDiv);
        } else {
            
            var selectedFieldDiv = document.getElementById('selected_' + fieldId);
            if(selectedFieldDiv) 
            {
                selectedFieldDiv.remove();
            }
        }
        updateFieldNumbers();
    }

    function updateFieldNumbers() 
    {
        
        var selectedFields = document.querySelectorAll('#selected-fields-container .selected-field');
        
        selectedFields.forEach(function(field, index) 
        {
            var label = field.querySelector('label');
            label.innerHTML = (index + 1) + ". " + label.innerHTML.replace(/^\d+\.\s*/, '');
        });
    }

</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Bot Integration Field Mapping</a></li>
</ol>
<div class="page-heading">            
    <h1>Bot Integration Field Mapping</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <form action="<?php echo $this->webroot;?>BotIntegrationField/" method="post" id="client_form">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box','onchange'=>'getClient();','options'=>$master_client,'value'=>isset($client_id)?$client_id:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if(isset($client_id) && !empty($client_id)){ ?>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Bot Integration Field Mapping</h2>
            </div>
            
            <div class="panel-body">
                <form action="<?php echo $this->webroot;?>BotIntegrationField/field_save" method="post" class='form-horizontal row-border'>
                    <input type="hidden" name="client_id" id='client_id' value='<?php echo $client_id; ?>'>
                
                    <a href="<?php echo $this->webroot;?>BotIntegrationField/show_webhook?client_id=<?php echo $client_id; ?>" title="Show Webhook Detail" style="float:right;">
                        <label class="btn btn-web btn-xs tn-midnightblue btn-raised" style="background-color:#37474f;color:#eceff1;">Show Webhook Detail {...}</label>
                    </a>
                    <table cellpadding="2" cellspacing="2" border="0" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Field Selection</th>
                                <th>Fields</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($require_field)): ?>
                                <tr>
                                    <td style="width: 50%;">
                                        <div class="row" style="margin-left: 24px;">
                                            <?php foreach($require_field as $field):
                                                $req_field = "Field".$field['FieldMaster']['fieldNumber']; 
                                                $isChecked = isset($exist_field[$req_field]) ? 'checked' : '';?>
                                                <div class="form-group">
                                                    <input type="checkbox" class="field-checkbox" name="selected_fields[]" value="<?php echo $req_field; ?>" id="<?php echo $req_field; ?>" onchange="handleFieldSelection(this)" data-field-name="<?php echo $field['FieldMaster']['FieldName']; ?>"
                                                    <?php echo $isChecked; ?>>
                                                    <label for="<?php echo $req_field; ?>">
                                                        <?php echo $field['FieldMaster']['FieldName']; ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td style="width: 50%;">
                                        <div class="row" id="selected-fields-container" style="margin-left: 24px;">
                                            <?php if (!empty($exist_field)): ?>
                                                <?php foreach ($exist_field as $fieldId => $fieldName): ?>
                                                    <div class="form-group" id="selected_<?php echo $fieldId; ?>">
                                                        <label>
                                                            <?php echo $fieldName; ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                
                            <?php else: ?>
                                <p>No fields found.</p>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                    
                        <div class="col-sm-3">
                            <input type="submit" class="btn btn-web"  value="Submit" >
                        </div>
                    
                    </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
        
    </div>
</div>
<?php } ?>




