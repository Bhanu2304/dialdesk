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
    <li><a >Company Approval</a></li>
    <li class="active"><a href="#">Shopify Integration</a></li>
</ol>

<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>Shopify Integration</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                
                <!-- <form action="addcampaignlistid_save" method="post"> -->
                <?php echo $this->Form->create('ShopifyIntegrations',array('action'=>'index','id'=>'client_form','data-parsley-validate'));?>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span >Client</span>
                                <!-- <input type="text" name="PlanName" placeholder="Plan Name" required="" class="form-control extclass"> -->
                                <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box','options'=>$client,'empty'=>'Select Client','required'=>true)); ?>
                           </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span >Api Key</span>
                                <!-- <input type="text" name="PlanName" placeholder="Plan Name" required="" class="form-control extclass"> -->
                                <?php echo $this->Form->input('api_key',array('label'=>false,'placeholder'=>'Api Key','class'=>'form-control','required'=>true)); ?>
                           </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span >Token</span>
                                <!-- <input type="text" name="PlanName" placeholder="Plan Name" required="" class="form-control extclass"> -->
                                <?php echo $this->Form->input('api_token',array('label'=>false,'placeholder'=>'Token','class'=>'form-control','required'=>true)); ?>
                           </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Domain</span>
                                <?php echo $this->Form->input('domain',array('label'=>false,'placeholder'=>'Domain','class'=>'form-control','required'=>true)); ?>
                           </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>List Id</span>
                                <?php echo $this->Form->input('list',array('label'=>false,'placeholder'=>'List Id','class'=>'form-control','required'=>true)); ?>
                           </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Dialer Ip</span>
                                <?php echo $this->Form->input('dialer_ip',array('label'=>false,'placeholder'=>'Dialer Ip','class'=>'form-control','required'=>true)); ?>
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="col-xs-12">
                                <div class="input-group">                           
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                <input type="submit" value="Submit" class="btn-web btn">   
                                </div>
                            </div>
                        </div> 
                    </div>


                <?php  echo $this->Form->end(); ?>
                 

           
            </div>
        </div>
        
        <?php if(isset($result) && !empty($result)){ ?>
           
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW Shopify Integration</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Id</th>
                                    <th>Api Key</th>
                                    <th>Token</th>
                                    <th>Domain</th>
                                    <th>List Id</th>
                                    <th>Dialer Ip</th>
                                    <th>Create Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $ik='1';
                            foreach($result as $ctr) {  ?>
                                <tr>
                                    <td><?= $ik++;?></td>
                                    <td><?php echo $ctr['registration_master']['company_name'];?></td>
                                    <td><?php echo $ctr['ShopifyConf']['api_key'];?></td>
                                    <td><?php echo $ctr['ShopifyConf']['token'];?></td>
                                    <td><?php echo $ctr['ShopifyConf']['domain'];?></td>
                                    <td><?php echo $ctr['ShopifyConf']['list_id'];?></td>
                                    <td><?php echo $ctr['ShopifyConf']['dialer_ip'];?></td>
                                    <?php if($ctr['ShopifyConf']['create_date'] != '') {?>
                                    <td><?php echo date_format(date_create($ctr['ShopifyConf']['create_date']),'d M Y');?></td>
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