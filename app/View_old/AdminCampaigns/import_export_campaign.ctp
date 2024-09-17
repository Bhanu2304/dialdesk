<script>    
function getClient(){
    $("#campaign_form").submit();	
}
function getCampaign(campid){
    if(campid.value !=""){
        $("#download").show();
        $("#download").html('<a href="download?id='+campid.value+'&cid='+$("#cid").val()+'">(Download)</a><br/><span>Note: After download format plz convert in csv to upload Campaign data.</span')
    }
    else{
        $("#download").hide();
    }
}
</script>
<script>
$(document).ready(function() {
    $('.pegging').DataTable( {
        "pagingType": "full_numbers"
    } );
} );  
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Export Import Campaign</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                 <div style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AdminCampaigns',array('action'=>'import_export_campaign','id'=>'campaign_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?>  
                 
                <?php if(isset($clientid) && !empty($clientid)){?>
                <table class="display table table-bordered table-condensed table-hovered sortableTable">
                    <tr>
                        <td>
                            <h6>Export Campaign Header</h6>
                            <table class="display table table-bordered table-condensed table-hovered sortableTable">
                                <tr>
                                    <td><?php echo $this->Form->input('CampaignName',array('label'=>false,'id'=>'campname','options'=>$Campaign,'empty'=>'Select Campaign','onchange'=>'getCampaign(this)','required'=>true));?></td>
                                </tr>
                                <tr>
                                    <td><div id="download" style="display:none;" ></div></td>
                                </tr>
                            </table>
                            <?php echo $this->Form->hidden('cid',array('label'=>false,'id'=>'cid','value'=>isset($clientid)?$clientid:""));?>                           
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <h6>Import Campaign Data</h6>
                            <?php echo $this->Form->create('AdminCampaigns',array('action'=>'save_Campaign_data','enctype'=>'multipart/form-data')); ?>
                            <table class="display table table-bordered table-condensed table-hovered sortableTable">
                                <tr>
                                    <td>
                                        <table class="display table table-bordered table-condensed table-hovered sortableTable">
                                            <tr>
                                                <td><?php echo $this->Form->input('CampaignName',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign','required'=>true));?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->Form->input('AllocationName',array('label'=>false,'class'=>'form-control','placeholder'=>'Allocation Name','autocomplete'=>'off','required'=>true ));?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php echo $this->Form->File('File',array('label'=>false,'type'=>'File','required'=>true));?> 
                                                    <br/><span style="font-size: 11px;" >(Upload Only CSV File)</span>
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="submit" class="btn btn-raised btn-default btn-primary" value="Upload" >
                                        <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>                           
                                    </td>
                                </tr>
                            </table>
                            <?php echo $this->Form->end(); ?>  
                        </td>
                    </tr>
                </table>

                <?php }?>

            </div>
        </div>
    </div>
</div>





    

          