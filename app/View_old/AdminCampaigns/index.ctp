<script>    
function getClient(){
    $("#campaign_form").submit();	
}
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Add Campaign</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                 <div style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AdminCampaigns',array('action'=>'index','id'=>'campaign_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?>  
                
                <?php if(isset($clientid) && !empty($clientid)){?>
                    <?php echo $this->Form->create('AdminCampaigns',array('action'=>'add_campaign','enctype'=>'multipart/form-data')); ?>
                        <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                            <tr>    
                                <td>Campaign Name</td>
                                <td><?php echo $this->Form->input('CampaignName',array('label'=>false,'placeholder'=>'campaign name','autocomplete'=>'off','required'=>true ));?></td>
                            </tr>
                            <tr>  
                                <td>Upload Campaign Header</td>
                                <td>
                                    <?php echo $this->Form->file('File.',array('label'=>false,'type'=>'file','id'=>'addon3','multiple'=>false,'required'=>true));?>
                                    <br/><span style="font-size:11px;">(Upload Only CSV File)</span>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" class="btn btn-raised btn-default btn-primary" value="Upload" >
                        <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                    <?php echo $this->Form->end(); ?>
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
                <h5>View Campaign</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <table class="pagination display table table-bordered table-condensed table-hovered sortableTable" cellspacing="0" width="100%"  >
                    <thead>
                    <tr>
                        <th>CAMPAIGN</th>
                        <th>FIELD</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($campaign_header as $row){?>
                    <tr >
                       <td><?php echo $row['campaign'];?></td>
                       <td><?php echo implode(",",$row['field']);?></td>    
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php }?>



    

          