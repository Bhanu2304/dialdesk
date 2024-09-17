<?php ?>

<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>

<style>
    .extclass{margin-top: -12px ! important;}
</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Company Approval</a></li>
    <li class="active"><a href="#">Add/View Social Media</a></li>
</ol>
<div class="page-heading " style="margin-top: -15px;">            
    <h4>Add/View Social Media</h4>
</div>

<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div style="margin-top:-45px;">
            <?php echo $this->Form->create('AdminMedias',array('action'=>'index','id'=>'client_form')); ?>
                <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
            <?php  echo $this->Form->end(); ?>
        </div>
       
        
        <?php if(isset($clientid) && !empty($clientid)){ ?>
        <!--
        <div style="margin-top:0px;">
            <a title="Add Facebook Details" href="#"><img alt="find us on facebook" src="//login.create.net/images/icons/user/facebook-c_130x50.png" border=0></a>
          
            <a target="_blank" title="follow me on twitter" href="#"><img alt="follow me on twitter" src="//login.create.net/images/icons/user/twitter_130x50.png" border=0></a>
           
        </div>
        -->

        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            <div class="panel-heading">
                 <h2>Add Facebook Details</h2>
            </div>
            <div class="panel-body" style="margin-top:-10px;" >
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                
                <?php 
                if(isset($mediaArr['SocialMediaMaster']['id']) && $mediaArr['SocialMediaMaster']['id'] !=""){
                    echo $this->Form->create('AdminMedias',array('action'=>'update_media','id'=>'client_form','data-parsley-validate'));
                }
                else{
                    echo $this->Form->create('AdminMedias',array('action'=>'add_media','id'=>'client_form','data-parsley-validate'));  
                }
                ?>         
                    <div class="col-md-12">
                        
                        <?php echo $this->Form->hidden('social_media_type',array('label'=>false,'value'=>'facebook','empty'=>'Select Media Type','class'=>'form-control extclass','required'=>true));?>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Login Id</span>
                                <?php echo $this->Form->input('LoginId',array('label'=>false,'placeholder'=>'Login Id','value'=>isset($mediaArr['SocialMediaMaster']['email'])?$mediaArr['SocialMediaMaster']['email']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Password</span>
                                <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>'Login Password','value'=>isset($mediaArr['SocialMediaMaster']['password'])?$mediaArr['SocialMediaMaster']['password']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;"> App Id</span>
                                <?php echo $this->Form->input('FbAppId',array('label'=>false,'placeholder'=>'App Id','value'=>isset($mediaArr['SocialMediaMaster']['fb_app_id'])?$mediaArr['SocialMediaMaster']['fb_app_id']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">App Secret </span>
                                <?php echo $this->Form->input('fb_app_secret',array('label'=>false,'placeholder'=>'App Secret','value'=>isset($mediaArr['SocialMediaMaster']['fb_app_secret'])?$mediaArr['SocialMediaMaster']['fb_app_secret']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div> 
                    </div>
                
                    
                    <div class="col-md-12">                    
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">First Page Id</span>
                                <?php echo $this->Form->input('fb_page_id1',array('label'=>false,'placeholder'=>'Page Id','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_id1'])?$mediaArr['SocialMediaMaster']['fb_page_id1']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Page Name</span>
                                <?php echo $this->Form->input('fb_page_name1',array('label'=>false,'placeholder'=>'Page Name','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_name1'])?$mediaArr['SocialMediaMaster']['fb_page_name1']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true,));?>
                           </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Access Token</span>
                                <?php echo $this->Form->input('fb_page_token1',array('label'=>false,'placeholder'=>'Access Token','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_token1'])?$mediaArr['SocialMediaMaster']['fb_page_token1']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>                     
                    </div>
                
                        
                    <div class="col-md-12">
                       
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Second Page Id</span>
                                <?php echo $this->Form->input('fb_page_id2',array('label'=>false,'placeholder'=>'Page Id','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_id2'])?$mediaArr['SocialMediaMaster']['fb_page_id2']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Page Name</span>
                                <?php echo $this->Form->input('fb_page_name2',array('label'=>false,'placeholder'=>'Page Name','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_name2'])?$mediaArr['SocialMediaMaster']['fb_page_name2']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Access Token</span>
                                <?php echo $this->Form->input('fb_page_token2',array('label'=>false,'placeholder'=>'Access Token','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_token2'])?$mediaArr['SocialMediaMaster']['fb_page_token2']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>  
                        
                    </div>
                
                
                
                    <div class="col-md-12">
                       
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Third Page Id</span>
                                <?php echo $this->Form->input('fb_page_id3',array('label'=>false,'placeholder'=>'Page Id','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_id3'])?$mediaArr['SocialMediaMaster']['fb_page_id3']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Page Name</span>
                                <?php echo $this->Form->input('fb_page_name3',array('label'=>false,'placeholder'=>'Page Name','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_name3'])?$mediaArr['SocialMediaMaster']['fb_page_name3']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Access Token</span>
                                <?php echo $this->Form->input('fb_page_token3',array('label'=>false,'placeholder'=>'Access Token','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_token3'])?$mediaArr['SocialMediaMaster']['fb_page_token3']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>  
                        
                    </div>
                
                
                    <div class="col-md-12">
                        
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Fourth Page Id</span>
                                <?php echo $this->Form->input('fb_page_id4',array('label'=>false,'placeholder'=>'Page Id','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_id4'])?$mediaArr['SocialMediaMaster']['fb_page_id4']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Page Name</span>
                                <?php echo $this->Form->input('fb_page_name4',array('label'=>false,'placeholder'=>'Page Name','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_name4'])?$mediaArr['SocialMediaMaster']['fb_page_name4']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Access Token</span>
                                <?php echo $this->Form->input('fb_page_token4',array('label'=>false,'placeholder'=>'Access Token','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_token4'])?$mediaArr['SocialMediaMaster']['fb_page_token4']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div> 
                        
                        
                    </div>
                
                    <div class="col-md-12">                  
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Fifth Page Id</span>
                                <?php echo $this->Form->input('fb_page_id5',array('label'=>false,'placeholder'=>'Page Id','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_id5'])?$mediaArr['SocialMediaMaster']['fb_page_id5']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Page Name</span>
                                <?php echo $this->Form->input('fb_page_name5',array('label'=>false,'placeholder'=>'Page Name','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_name5'])?$mediaArr['SocialMediaMaster']['fb_page_name5']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                         <div class="col-md-6">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Access Token</span>
                                <?php echo $this->Form->input('fb_page_token5',array('label'=>false,'placeholder'=>'Access Token','value'=>isset($mediaArr['SocialMediaMaster']['fb_page_token5'])?$mediaArr['SocialMediaMaster']['fb_page_token5']:"",'class'=>'form-control extclass','autocomplete'=>'off'));?>
                           </div>
                        </div> 
                    </div>
         
                
                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php  echo $this->Form->hidden('client_id',array('label'=>false,'value'=>isset($clientid)?$clientid:"",'required'=>true)); ?> 

                            <?php 
                             if(isset($mediaArr['SocialMediaMaster']['id']) && $mediaArr['SocialMediaMaster']['id'] !=""){
                                echo $this->Form->hidden('id',array('label'=>false,'value'=>isset ($mediaArr['SocialMediaMaster']['id']) ? $mediaArr['SocialMediaMaster']['id'] : "",'required'=>true));
                                echo $this->Form->submit('Update',array('class'=>'btn-web btn'));
                            }
                            else{
                                 echo $this->Form->submit('Submit',array('class'=>'btn-web btn'));
                            }
                            ?>    
                        </div>
                    </div>
                </div> 
                <?php  echo $this->Form->end(); ?>
               
            </div>
        </div>
        
                <?php if(!empty($AllMediaArr)){ ?>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($AllMediaArr as $row){?>
                                <tr>
                                    <td><?php echo $row['SocialMediaMaster']['social_media_type'];?></td>
                                    <td><?php echo $row['SocialMediaMaster']['email'];?></td>
                                    <td><?php echo $row['SocialMediaMaster']['password'];?></td>
                                    <td><?php echo $row['SocialMediaMaster']['fb_app_id'];?></td>
                                    <td><?php echo $row['SocialMediaMaster']['fb_app_secret'];?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer"></div>
                </div>
                <?php }?>
            <?php }?>
    </div>
</div>
 