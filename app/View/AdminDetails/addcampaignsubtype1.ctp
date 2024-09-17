<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="#">Add Campaign Sub Type</a></li>
</ol>

<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>Add Campaign Sub Type</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <div class="col-md-6">
                <form action="addcampaignsubtype_save" method="post">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                   
                                    <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box','options'=>$client,'required'=>true)); ?>
                                </div>
                                
                                <div class="input-group">							
                                   
                                    <?php echo $this->Form->input('campaign_type',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Campaign Type','required'=>true)); ?>
                                </div>

                                <div class="input-group">

                                             <input type="submit" class="btn-web btn" value="SUBMIT"> 

                                </div>

                            </div>

                           

                        <?php  echo $this->Form->end(); ?>
                 

                </div> 
            </div>
        </div>
        
        <?php if(isset($campaign_type_record) && !empty($campaign_type_record)){ ?>
           
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW CLIENT CAMPAIGN</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th> Campaign Type</th>
                                    <th>Client Id</th>
                                   <!-- <th>ACTION</th>-->
                                </tr>
                            </thead>
                            <tbody>
                            <?php $ik='1';
                            foreach($campaign_type_record as $ctr) { ?>
                                <tr>
                                    <td><?= $ik++;?></td>
                                    <td><?php echo $ctr['ObCampaignDataTypeMaster']['CampaignType'];?></td>
                                    <td><?php echo $ctr['registration_master']['company_name'];?></td>
                                    <!--
                                    <td>
                                        <a href="<?php echo $this->webroot;?>AdminDetails/delete_did?id=<?php echo $didnumber['DidMaster']['id']?>&cid=<?php echo $didnumber['DidMaster']['client_id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                        </a> 
                                    </td>  
                                    -->
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            <?php }?>

    </div>
</div>