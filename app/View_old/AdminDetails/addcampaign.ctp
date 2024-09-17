<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="#">Add Campaign</a></li>
</ol>
<div class="page-heading margin-top-head">            
    <h2>Add Campaign</h2>
    <div >
        <?php echo $this->Form->create('AdminDetails',array('action'=>'addcampaign','id'=>'client_form')); ?>
            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php if(isset($clientid) && !empty($clientid)){ ?>
<?php 
if(isset($campaignid['RegistrationMaster']['campaignid']) && $campaignid['RegistrationMaster']['campaignid'] !=""){ 
    $exp=  explode(",", $campaignid['RegistrationMaster']['campaignid']);
    $campaignName=array();
    for($i=0;$i<count($exp);$i++){
        $campaignName[]=str_replace("'", '', $exp[$i]);
    } 
    $campName=implode(',', $campaignName);
 }
 
 if(isset($campaignid['RegistrationMaster']['GroupId']) && $campaignid['RegistrationMaster']['GroupId'] !=""){ 
    $exp=  explode(",", $campaignid['RegistrationMaster']['GroupId']);
    $GroupName=array();
    for($i=0;$i<count($exp);$i++){
        $GroupName[]=str_replace("'", '', $exp[$i]);
    } 
    $grpName=implode(',', $GroupName);
    //echo $grpName; die;
 }
 
?>
<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>Add Campaign</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <div class="col-md-6">
                        <?php echo $this->Form->create('AdminDetails',array('action'=>'update_campaign','id'=>'client_form','data-parsley-validate'));?>
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Campaign Name','value'=>isset ($campName) ? $campName : "",'required'=>true)); ?>
                                </div>
                                
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php echo $this->Form->input('GroupId',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Group Id','value'=>isset ($grpName) ? $grpName : "",'required'=>true)); ?>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php  echo $this->Form->hidden('client_id',array('label'=>false,'value'=>isset($clientid)?$clientid:"",'required'=>true)); ?> 
                                    
                                    <?php 
                                     if(isset($campaignid['RegistrationMaster']['campaignid']) && $campaignid['RegistrationMaster']['campaignid'] !=""){
                                        echo $this->Form->hidden('id',array('label'=>false,'value'=>isset ($campaignid['RegistrationMaster']['company_id']) ? $campaignid['RegistrationMaster']['company_id'] : "",'required'=>true));
                                        echo $this->Form->submit('Update',array('class'=>'btn-web btn'));
                                    }
                                    else{
                                         echo $this->Form->submit('Submit',array('class'=>'btn-web btn'));
                                    }
                                    ?>    
                                </div>
                            </div>
                        <?php  echo $this->Form->end(); ?>
                    <?php }?>

                </div> 
            </div>
        </div>
        
        <?php if(isset($clientid) && !empty($clientid)){ ?>
             <?php if(isset($campaignid['RegistrationMaster']['campaignid']) && $campaignid['RegistrationMaster']['campaignid'] !=""){ ?>
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW CLIENT CAMPAIGN</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th>Client Campaign</th>
                                    <th>Group Id</th>
                                   <!-- <th>ACTION</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $campName;?></td>
                                    <td><?php echo $grpName;?></td>
                                    <!--
                                    <td>
                                        <a href="<?php echo $this->webroot;?>AdminDetails/delete_did?id=<?php echo $didnumber['DidMaster']['id']?>&cid=<?php echo $didnumber['DidMaster']['client_id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                        </a> 
                                    </td>  
                                    -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            <?php }?>

    </div>
</div>
 <?php }?>