<?php echo $this->Html->script('admin_creation'); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Mobile Management</a></li>
    <li class="active"><a href="#">Mobile Users</a></li>
</ol>
<div class="page-heading">            
    <h1>Manage Field Executive</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage Field Executive</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('MobUsers',array('action'=>'index','id'=>'index')); ?>
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
                                <?php echo $this->Form->input('Name',array('label'=>false,'id'=>'Name','placeholder'=>'Name','class'=>'form-control','autocomplete'=>'off','required'=>''));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('password',array('label'=>false,'id'=>'password','placeholder'=>'Password','class'=>'form-control','autocomplete'=>'off','required'=>''));?>
                           </div>
                        </div>
                        
                       
                    </div>
                        
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('Code',array('label'=>false,'id'=>'Code','placeholder'=>'Sales Man Code','class'=>'form-control','autocomplete'=>'off','required'=>''));?>
                           </div>
                        </div>
                        
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="password" id="confirm_password" placeholder='Confirm Password' class="form-control" autocomplete='off'  required/>
                           </div>
                        </div>
                      
                    </div>
                       
                    
               
                    

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-8">
                                <div class="btn-toolbar">
                                    <input type="Submit" class="btn btn-web" value="Submit" >
                                    <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php $this->Form->end(); ?>
            </div>
        </div> 
       
        <div class="row">
            <div class="col-md-12"> 
                <?php if(!empty($data)){?>
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>VIEW FIELD EXECUTIVE</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>NAME</th>
                                    <th>CODE</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;foreach($data as $row){?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $row['Mob']['Name'];?></td>
                                        <td><?php echo $row['Mob']['Code'];?></td>
                                        <td><?php if($row['Mob']['Status']=='1') { echo 'Active';} else { echo 'De-Active';} ?></td>
                                       
                                        
                                        <td>
                                            <a  href="#" data-toggle="modal" data-target="#loginUpdate" onclick="view_edit_field('<?php echo $row['Mob']['Id'];?>')" >
                                                <label class="btn btn-xs btn-midnightblue btn-raised">
                                                    <i class="fa fa-edit"></i><div class="ripple-container"></div>
                                                </label>
                                            </a> 
                                            <a href="#" onclick="deleteData('<?php echo $this->webroot;?>MobUsers/delete_agents?Id=<?php echo $row['Mob']['Id'];?>')" >
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
                <h2 class="modal-title">Edit User </h2>
            </div>
           <?php echo $this->Form->create('MobUsers',array('action'=>'updateagent')); ?>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <div id="user-data1" ></div> 
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
               <input type="button" onclick="return editAdminForm1('<?php echo $this->webroot;?>MobUsers/updateagent')"  value="Submit" class="btn-web btn">
            </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
    function editAdminForm1(path,purl,emailurl){ 
    var name=$('#Name').val();
    var Code=$('#login_emailid').val();
    
    var password = $("#login_password").val();
    var confirm_password = $("#login_confirm_password").val();
    
  	
    
    
	 
    if($.trim(name)===""){ 
        $('#Name').focus();
        $("#elMsg").html('Name field is required.').show();  
        return false;
    }
    
    else if($.trim(emailid)===""){
        $('#login_emailid').focus();
        $("#elMsg").html('Email field is required.').show();  
        return false;
    }
    else if(!lengthrange(emailid,100)){
        $('#login_emailid').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    else if (!filter.test($.trim(emailid))) {
        $('#login_emailid').focus();
        $("#elMsg").html(email_err).show(); 
        return false;
    }
    else if($.trim(emailid) !="" && existEmailAdmin(loginid,emailid) !=''){
        $('#login_emailid').focus();
        $("#elMsg").html('<span>This email id is already exist.</span>').show();
        return false;
    }
    else if($.trim(password)===""){
        $('#login_password').focus();
        $("#elMsg").html('Password field is required.').show();  
        return false;
    }
    else if(!lengthrange(password,50)){
        $('#login_password').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    else if($.trim(confirm_password)===""){
        $('#login_confirm_password').focus();
        $("#elMsg").html('Confirm password field is required.').show();  
        return false;
    }
    else if($.trim(password) != $.trim(confirm_password)) {
        $('#login_confirm_password').val('');
        $('#login_confirm_password').focus();
        $("#elMsg").html(pass_err).show();  
        return false;
            }
    else if($.trim(phone)===""){
        $('#login_phone').focus();
        $("#elMsg").html('Phone field is required.').show();  
        return false;
    }
    else if(!$.trim(phone).match(phoneNum)) {
        $('#login_phone').focus();
        $("#elMsg").html(phone_err).show(); 
        return false;
    }
    else if($.trim(phone) !="" && $.trim(phone).charAt(0)==="0") {
        $('#login_phone').focus();
        $("#elMsg").html(phone_err).show(); 
        return false;
    }
//    else if($.trim(phone) !="" && existPhoneAdmin(loginid,phone) !=''){
//        $('#login_phone').focus();
//        $("#elMsg").html('<span>This number is already exist.</span>').show();
//        return false;
//    }
    else if($.trim(designation)===""){
        $('#login_designation').focus();
        $("#elMsg").html('Designation field is required.').show();  
        return false;
    }
    else if(!lengthrange(designation,50)){
        $('#login_designation').focus();
        $("#elMsg").html(length_err).show();  
        return false;
    }
    
    /*else if(aIds.length ==0){
        $("#elMsg").html('Please assign user right.').show(); 
        return false;
    }*/	
    else{
       $.ajax({
            type: "POST",
            url:path,
            data: {loginid:loginid,name:name,email:emailid,phone:phone,designation:designation,page_assign:aIds,password:password,user_active:user_active},
            success: function(data){
                    if(data !=''){
                        $("#close-login-popup").trigger('click');
                        $("#show-login-message").trigger('click');
                        $("#login-text-message").text('Thank you Admin has been updated successfully.');
                    }
            }
	});
        return true;
    }  
}




</script>    