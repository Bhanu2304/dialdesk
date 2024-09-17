<script>
    function getClient(){
        $("#client_form").submit();	
    } 
    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="#">Add Campaign List Id</a></li>
</ol>

<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>Add Campaign List Id</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <div class="col-md-6">
                <form action="addcampaignlistid_save" method="post">
                            <div class="col-xs-12">
                                <div class="input-group">							
                                   
                                    <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box','options'=>$client,'required'=>true)); ?>
                                </div>
                                
                                <div class="input-group">							
                                   
                                    <?php echo $this->Form->input('campaign_type',array('label'=>false,'class'=>'form-control','onkeypress'=>'return isNumberKey(event)' ,'placeholder'=>'List Id','required'=>true)); ?>
                                </div>

                                <div class="input-group">

                                             <input type="submit" class="btn-web btn" value="SUBMIT"> 

                                </div>

                            </div>

                           

                        <?php  echo $this->Form->end(); ?>
                 

                </div> 
            </div>
        </div>
        
        <?php if(isset($result) && !empty($result)){ ?>
           
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW CLIENT Campaign List Id</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th> List Id</th>
                                    <th>Client Id</th>
                                    <th>Create Date</th>
                                   <!-- <th>ACTION</th>-->
                                </tr>
                            </thead>
                            <tbody>
                            <?php $ik='1';
                            foreach($result as $ctr) { ?>
                                <tr>
                                    <td><?= $ik++;?></td>
                                    <td><?php echo $ctr['ListMaster']['list_id'];?></td>
                                    <td><?php echo $ctr['registration_master']['company_name'];?></td>
                                    <?php if($ctr['ListMaster']['create_date'] != '') {?>
                                    <td><?php echo date_format(date_create($ctr['ListMaster']['create_date']),'d M Y');?></td>
                                    <?php }else{
                                        echo '<td></td>';
                                    }?>
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