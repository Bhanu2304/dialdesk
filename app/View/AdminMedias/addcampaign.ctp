<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Company Approval</a></li>
    <li class="active"><a href="#">Add/View Social Media</a></li>
</ol>
<div class="page-heading margin-top-head">            
    <h2>Add/View Social Media</h2>
    <div >
        <?php echo $this->Form->create('AdminMedias',array('action'=>'addcampaign','id'=>'client_form')); ?>
            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>



<?php //if(isset($clientid) && !empty($clientid)){ ?>
<?php 
if(isset($campaignid['RegistrationMaster']['campaignid']) && $campaignid['RegistrationMaster']['campaignid'] !=""){ 
    $exp=  explode(",", $campaignid['RegistrationMaster']['campaignid']);
    $campaignName=array();
    for($i=0;$i<count($exp);$i++){
        $campaignName[]=str_replace("'", '', $exp[$i]);
    } 
    $campName=implode(',', $campaignName);
 }
?>

<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>Add Social Media</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <?php echo $this->Form->create('AdminMedias',array('action'=>'update_campaign','id'=>'client_form','data-parsley-validate'));?>         
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('social_media_type',array('label'=>false,'options'=>array('facebook'=>'Facebook'),'empty'=>'Select Media Type','class'=>'form-control','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('LoginId',array('label'=>false,'placeholder'=>'Login Id','class'=>'form-control','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>'Login Password','class'=>'form-control','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                    </div>
                
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('fb_app_id',array('label'=>false,'placeholder'=>'App Id','class'=>'form-control','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('fb_app_secret',array('label'=>false,'placeholder'=>'App Secret','class'=>'form-control','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('fb_page_id',array('label'=>false,'placeholder'=>'Page Id','class'=>'form-control','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                    </div>
                
                   
       

                <div class="col-md-4">
                    
                    <!--
                         <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Campaign Name','value'=>isset ($campName) ? $campName : "",'required'=>true)); ?>
                                </div>
                               
                            </div>
                            -->

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
                        
                    <?php //}?>

                </div> 
                
                <?php  echo $this->Form->end(); ?>
                
            </div>
        </div>
        
        
        
        <?php if(isset($clientid) && !empty($clientid)){ ?>
             <?php if(isset($campaignid['RegistrationMaster']['campaignid']) && $campaignid['RegistrationMaster']['campaignid'] !=""){ ?>
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW SOCIAL MEDIA</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th>Social Media Type</th>
                                    <th>Login Id</th>
                                    <th>Password</th>
                                    <th>App Id</th>
                                    <th>App Secret</th>
                                    <th>Page Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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