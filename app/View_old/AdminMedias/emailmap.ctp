<?php ?>

<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>

<style>
    .extclass{margin-top: -12px ! important;}
    table.fixed{ 
    table-layout: fixed;
    
}
table.fixed td { 
    overflow: hidden;
}
</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Company Approval</a></li>
    <li class="active"><a href="#">Add/View Email Map</a></li>
</ol>
<div class="page-heading " style="margin-top: -15px;">            
    <h4>Add/View Email Map</h4>
</div>

<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div style="margin-top:-45px;">
            <?php echo $this->Form->create('AdminMedias',array('action'=>'emailmap','id'=>'client_form')); ?>
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
                 <h2>Add Email Details </h2>
                 
            </div>
            <div class="panel-body" style="margin-top:-10px;" >
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                
                <?php 
               
                
                if(isset($mediaArr['EmailMaster']['Id']) && $mediaArr['EmailMaster']['Id'] !=""){
                    echo $this->Form->create('AdminMedias',array('action'=>'update_media_email','id'=>'client_form','data-parsley-validate'));
                }
                else{
                    echo $this->Form->create('AdminMedias',array('action'=>'add_media_email','id'=>'client_form','data-parsley-validate'));  
                }
                ?>  
                
                
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Email Id</span>
                                <?php echo $this->Form->input('email',array('label'=>false,'placeholder'=>'Email Id','value'=>isset($mediaArr['EmailMaster']['email'])?$mediaArr['EmailMaster']['email']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Password</span>
                                <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>'Login Password','value'=>isset($mediaArr['EmailMaster']['password'])?$mediaArr['EmailMaster']['password']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Inbox Host Name</span>
                                <?php echo $this->Form->input('inbox_hostname',array('label'=>false,'placeholder'=>'Inbox Host Name','value'=>isset($mediaArr['EmailMaster']['inbox_hostname'])?$mediaArr['EmailMaster']['inbox_hostname']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Inbox Port </span>
                                <?php echo $this->Form->input('inbox_port',array('label'=>false,'placeholder'=>'Inbox Port','value'=>isset($mediaArr['EmailMaster']['inbox_port'])?$mediaArr['EmailMaster']['inbox_port']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>
                        
                    </div>
                
                    <div class="col-md-12">
                       
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Send Host Name</span>
                                <?php echo $this->Form->input('send_hostname',array('label'=>false,'placeholder'=>'Send Host Name','value'=>isset($mediaArr['EmailMaster']['send_hostname'])?$mediaArr['EmailMaster']['send_hostname']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Send Port</span>
                                <?php echo $this->Form->input('send_port',array('label'=>false,'placeholder'=>'Send Port','value'=>isset($mediaArr['EmailMaster']['send_port'])?$mediaArr['EmailMaster']['send_port']:"",'class'=>'form-control extclass','autocomplete'=>'off','required'=>true));?>
                           </div>
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Status</span>
                                <?php echo $this->Form->input('active',array('label'=>false,'placeholder'=>'Status','options'=>array('1'=>'Active','0'=>'Deactivate'),'empty'=>'Select Status','value'=>isset($mediaArr['EmailMaster']['active'])?$mediaArr['EmailMaster']['active']:"",'class'=>'form-control extclass','required'=>true));?>
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
                             if(isset($mediaArr['EmailMaster']['Id']) && $mediaArr['EmailMaster']['Id'] !=""){
                                echo $this->Form->hidden('id',array('label'=>false,'value'=>isset ($mediaArr['EmailMaster']['Id']) ? $mediaArr['EmailMaster']['Id'] : "",'required'=>true));
                                //echo $this->Form->submit('Update',array('class'=>'btn-web btn'));
                                ?>
                                <input type="submit" value="Update" class="btn-web btn" >
                                <a href="<?php echo $this->webroot?>AdminMedias/emailmap?cid=<?php echo $mediaArr['EmailMaster']['client_id'];?>"><input type="button" value="Add" class="btn-web btn" ></a>
                                <?php
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
                        <h2>VIEW Email Details</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered fixed" >
                            <thead>
                                <tr>
                                    <th style="width:140px;" >Email</th>
                                    <th style="width:100px;" >Password</th>
                                    <th style="width:160px;" >Inbox Host Name</th>
                                    <th style="width:100px;" >Inbox Port</th>
                                    <th style="width:100px;" >Send Host Name</th>
                                    <th style="width:50px;" >Send Port</th>
                                    <th style="width:50px;" >Status</th>
                                    <th style="width:50px;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($AllMediaArr as $row){?>
                                <tr>
                                    <td><?php echo $row['EmailMaster']['email'];?></td>
                                    <td><?php echo $row['EmailMaster']['password'];?></td>
                                    <td><?php echo $row['EmailMaster']['inbox_hostname'];?></td>
                                    <td><?php echo $row['EmailMaster']['inbox_port'];?></td>
                                    <td><?php echo $row['EmailMaster']['send_hostname'];?></td>
                                    <td><?php echo $row['EmailMaster']['send_port'];?></td>
                                    <td><?php echo $row['EmailMaster']['active'];?></td>
                                    <td>
                                        <a href="<?php echo $this->webroot?>AdminMedias/emailmap?id=<?php echo $row['EmailMaster']['Id']."&cid=".$row['EmailMaster']['client_id'];?>" title="Edit"  >
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="material-icons">library_add</i></label>
                                        </a>
                                    </td>
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
 