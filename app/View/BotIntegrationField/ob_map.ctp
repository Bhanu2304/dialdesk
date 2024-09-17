<?php ?>

<script>

function getcampaign(client_id){

    if(client_id !=""){
        $.post('<?php echo $this->webroot;?>MasterField/get_campaign',{client_id:client_id},function(data){

            $("#campaign_id").html(data);
 
        });
    }
    else{
         $("#campaign_id").html('');
    }
}

function getClient(){
    $("#client_form").submit();	
} 

</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Out Call Management</a></li>
    <li class="active"><a href="#">Master Ob Field Mapping</a></li>
</ol>
<div class="page-heading">            
    <h1>Master Ob Field Mapping</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
           
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <form action="<?php echo $this->webroot;?>MasterField/ob_map" method="post" id="client_form">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getcampaign(this.value);','options'=>$master_client,'value'=>isset($client_id)?$client_id:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('campaign_id',array('label'=>false,'class'=>'form-control client-box','id'=>'campaign_id','onchange'=>'getClient();','value'=>isset($campaign_id)?$campaign_id:"",'empty'=>'Select Campaign','options'=>$campaign,'required'=>true)); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if(isset($campaign_id) && !empty($campaign_id)){ ?>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Master Field Mapping</h2>
            </div>
            
            <div class="panel-body">
                <form action="<?php echo $this->webroot;?>MasterField/c2p_ob_field_mapping_save" method="post" class='form-horizontal row-border'>
                    <input type="hidden" name="client_id" id='client_id' value='<?php echo $client_id; ?>'>
                    <input type="hidden" name="campaign_id" id='campaign_id' value='<?php echo $campaign_id; ?>'>
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
                                                $req_field = "Field".$field['ObfieldCreation']['fieldNumber'];
                                                $postField = str_replace(' ', '_', strtolower($post['MasterField']['field'])); ?>
                                                <option value="<?php echo $req_field; ?>"<?php foreach($master_fields_mapping as $mapping){ if($postField == strtolower($mapping['MasterFieldMap']['field']) && $req_field == $mapping['MasterFieldMap']['map_field']){echo "selected"; } } ?>><?php echo $field['ObfieldCreation']['FieldName']; ?></option>
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




