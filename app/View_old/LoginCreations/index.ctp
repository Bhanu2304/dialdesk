<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage User Logins</a></li>
</ol>
<div class="page-heading">            
    <h1>Manage User Logins</h1>
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
                <?php echo $this->Form->create('LoginCreations',array('action'=>'save_login','id'=>'save_login_creation')); ?>
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <div id="erroMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>      
                            </div>
                        </div>
                    </div>
               
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('name',array('label'=>false,'id'=>'name','placeholder'=>'Name','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('password',array('label'=>false,'id'=>'password','placeholder'=>'Password','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->form->input('phone',array('label'=>false,'placeholder'=>'Phone No','maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','id'=>'phone','autocomplete'=>'off'));?>
                           </div>
                        </div>
                    </div>
                        
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('emailid',array('label'=>false,'id'=>'emailid','placeholder'=>'Email Address','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="password" id="confirm_password" placeholder='Confirm Password' class="form-control" autocomplete='off'  />
                           </div>
                        </div>
                        
                         <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->form->input('designation',array('label'=>false,'id'=>'designation','placeholder'=>'Designation','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                    </div>
                       
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">User Right</label>
                                <div class="col-sm-1">
                                    <div class="assign-right" style="width: 595px;margin-left:-100px;">
                                        <ol class="user-tree">
                                            <?php echo $UserRight;?>                                    
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
                                    <input type="button" onclick="validateLoginCreation('<?php echo $this->webroot;?>LoginCreations/check_exist_phone','<?php echo $this->webroot;?>LoginCreations/checkexistmail')" class="btn btn-web" value="Submit" >
                                    <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="phoneverify" value=""  />
                    <input type="hidden" id="eurl" value="<?php echo $this->webroot;?>LoginCreations/checkSmtpValidation"  />
                    <input type="hidden" id="eeurl" value="<?php echo $this->webroot;?>LoginCreations/check_exist_email" />
                    <input type="hidden" id="purl" value="<?php echo $this->webroot;?>LoginCreations/sendotp" />
                    <input type="hidden" id="loginurl" value="<?php echo $this->webroot;?>LoginCreations" />
                    <input type="hidden" id="saveLogin" value="<?php echo $this->webroot;?>LoginCreations/save_login_creation" />
                <?php $this->Form->end(); ?>
            </div>
        </div> 
       
        <div class="row">
            <div class="col-md-12"> 
                <?php if(!empty($data)){?>
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>VIEW LOGIN</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>NAME</th>
                                    <th>PHONE NO</th>
                                    <th>EMAIL</th>
                                    <th>DESIGNATION</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;foreach($data as $row){?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $row['LogincreationMaster']['name'];?></td>
                                        <td><?php echo $row['LogincreationMaster']['phone'];?></td>
                                        <td><?php echo $row['LogincreationMaster']['username'];?></td>
                                        <td><?php echo $row['LogincreationMaster']['designation'];?></td>
                                        <td>
                                            <a  href="#" data-toggle="modal" data-target="#loginUpdate" onclick="view_edit_login('<?php echo $row['LogincreationMaster']['id'];?>')" >
                                                <label class="btn btn-xs btn-midnightblue btn-raised">
                                                    <i class="fa fa-edit"></i><div class="ripple-container"></div>
                                                </label>
                                            </a> 
                                            <a href="#" onclick="deleteData('<?php echo $this->webroot;?>LoginCreations/delete_user?id=<?php echo $row['LogincreationMaster']['id'];?>')" >
                                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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

