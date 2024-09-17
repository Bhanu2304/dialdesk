<?php ?>
<script> 		
function validateExport(url){
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

</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Master Field Mapping</a></li>
</ol>
<div class="page-heading">            
    <h1>Master Field Mapping</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
           
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <form action="<?php echo $this->webroot;?>MasterField/" method="post" id="client_form">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getClient();','options'=>$master_client,'value'=>isset($client_id)?$client_id:"",'empty'=>'Select Client','required'=>true)); ?>
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
                <h2>Master Field Mapping</h2>
            </div>
            
            <div class="panel-body">
                <form action="<?php echo $this->webroot;?>MasterField/c2p_field_mapping_save" method="post" class='form-horizontal row-border'>
                    <input type="hidden" name="client_id" id='client_id' value='<?php echo $client_id; ?>'>
                    <table cellpadding="2" cellspacing="2" border="0" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Master Fields</th>
                                <th>Field Map</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($master_fields as $post):?>
                        <tr>
                            <td><?=$post['MasterField']['field']?></td>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <select name="<?=$post['MasterField']['field']?>" id="require_field" class='form-control'>
                                            <option value="">Select</option>
                                            <?php if($post['MasterField']['field'] == "Phone") {?>
                                                <option value="MSISDN" selected>MSISDN</option>
                                            <?php continue;}
                                            foreach($require_field as $field){ 
                                                $req_field = "Field".$field['FieldMaster']['fieldNumber'];
                                                $postField = str_replace(' ', '_', strtolower($post['MasterField']['field'])); ?>
                                                <option value="<?php echo $req_field; ?>"<?php foreach($master_fields_mapping as $mapping){ if($postField == strtolower($mapping['MasterFieldMap']['field']) && $req_field == $mapping['MasterFieldMap']['map_field']){echo "selected"; } } ?>><?php echo $field['FieldMaster']['FieldName']; ?></option>
                                            <?php } ?>
                                        </select>
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
                        </form>
                    </div>
                </div> 
            </div>
        </div>
        
    </div>
</div>
<?php } ?>




