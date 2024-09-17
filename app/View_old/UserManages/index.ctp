<?php ?>
<script>

<?php if(isset($type) && $type !=""){?>    
$(document).ready(function(){
    showAccess('<?php echo $type;?>');
});
<?php }?>       
    
function showAccess(type){
    $('#scenarioRights').hide();
    $('#campaignRights').hide();
    
    if(type =="Inbound"){
        $('#scenarioRights').show();
    }
    else if(type =="Outbound"){
        $('#campaignRights').show();
    }  
}

function getUserAccess(id){
    if(id !=""){
        window.location="<?php echo $this->webroot;?>UserManages?id="+id;
    }
    else{
        window.location="<?php echo $this->webroot;?>UserManages"; 
    }
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage User Logins</a></li>
</ol>
<div class="page-heading">            
    <h1>Manage User Access</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage User Logins</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('UserManages',array('action'=>'update_user_access','id'=>'update_user_access','data-parsley-validate')); ?>
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <div id="erroMsg" style="color:green;font-size: 15px;"><?php echo $this->Session->flash();?></div>      
                            </div>
                        </div>
                    </div>
               
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('UserId',array('label'=>false,'id'=>'name','options'=>$UserList,'value'=>$UserId,'empty'=>'Select User','onchange'=>'getUserAccess(this.value)','class'=>'form-control','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('CallType',array('label'=>false,'id'=>'name','options'=>array('Inbound'=>'In Call Management','Outbound'=>'Out Call Management'),'value'=>$type,'empty'=>'Select Type','onchange'=>'showAccess(this.value)','class'=>'form-control','required'=>true));?>
                           </div>
                        </div>
                        
                        <div class="col-md-12" style="display: none" id="scenarioRights" >
                            <div class="col-xs-12">                              
                                <span>Select Scenarios</span>
                                <hr/>
                                <div class="form-group">
                                   <!-- <label class="col-sm-1 control-label"></label> -->
                                    <div class="col-sm-1">
                                        <div class="assign-right" style="width:600px;height:225px; margin-left:-100px;">
                                            <ol class="user-tree">
                                                <?php echo $UserRight;?>                                    
                                            </ol>
                                        </div>                              
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="display: none" id="campaignRights" >
                            <div class="col-xs-12">                              
                                <span>Select Campaign</span>
                                <hr/>
                                <div class="form-group">
                                   <!-- <label class="col-sm-1 control-label"></label> -->
                                    <div class="col-sm-1">
                                        <div class="assign-right" style="width:600px; margin-left:-100px;">
                                            <ol class="user-tree">
                                                <?php 
                                                    foreach($Campaign as $key=>$val){ 
                                                    if(in_array($key,$outboundAccess)){$check='checked';}else{$check='';}
                                                    ?>
                                                    <li><div class='checkbox-primary'><label><input type='checkbox' name='selectCam[]' <?php echo $check;?>  value='<?php echo $key;?>'> <?php echo $val;?> 
                                                <?php }?>         
                                            </ol>
                                        </div>                              
                                    </div>
                                </div>
                            </div>
                        </div>
               
                            
                    
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-8">
                                    <div class="btn-toolbar">
                                        <input type="submit" class="btn btn-web" value="Submit" style="margin-left: -12px;" >
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php $this->Form->end(); ?>
            </div>
        </div> 
    </div>  
</div>
    
    
    
<!-- Edit Login Message Popup -->
<a class="btn btn-primary btn-lg" id="show-login-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h2 class="modal-title">Message</h2>
                    </div>
                    <div class="modal-body">
                        <p id="login-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<!-- Edit Login Popup -->
<div class="modal fade" id="loginUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Login</h2>
            </div>
           <?php echo $this->Form->create('LoginCreations',array('action'=>'update_login')); ?>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <div id="user-data" ></div> 
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
               <input type="button" onclick="return editLoginForm('<?php echo $this->webroot;?>LoginCreations/update_login')"  value="Submit" class="btn-web btn">
            </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

