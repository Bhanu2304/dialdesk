<?php ?>
<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <div id="elMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
            <input type="hidden" id="loginid" value="<?php echo isset ($get_record['Admin']['id']) ? $get_record['Admin']['id'] : "";?>" >
        </div>
    </div>
</div>
               
<div class="col-md-12">
    <div class="col-xs-2">
        <div class="input-group">
            Name
        </div>
    </div>
    <div class="col-xs-4">
        <div class="input-group">
           <?php echo $this->form->input('name',array('label'=>false,'id'=>'login_name','class'=>'form-control','value'=>isset ($get_record['Admin']['name']) ? $get_record['Admin']['name'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
    <div class="col-xs-2">
        <div class="input-group">
            Email
        </div>
    </div>
    <div class="col-xs-4">
        <div class="input-group">							
            <?php echo $this->form->input('emailid',array('label'=>false,'id'=>'login_emailid','class'=>'form-control','value'=>isset ($get_record['Admin']['Email']) ? $get_record['Admin']['Email'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
</div>



<div class="col-md-12">
    <div class="col-xs-2">
        <div class="input-group">
            Password
        </div>
    </div>
    <div class="col-xs-4">
        <div class="input-group">							
             <?php echo $this->form->input('password',array('label'=>false,'id'=>'login_password','class'=>'form-control','value'=>isset ($get_record['Admin']['Password']) ? $get_record['Admin']['Password'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
    <div class="col-xs-2">
        <div class="input-group">
            Confirm Password
        </div>
    </div>
    <div class="col-xs-4">
        <div class="input-group">							
            <input type="password" id="login_confirm_password" value="<?php if(isset($get_record['Admin']['password2'])){ echo $get_record['Admin']['password2'];}?>" class="form-control"   />
       </div>
    </div>
</div>



<div class="col-md-12">
    <div class="col-xs-2">
        <div class="input-group">
            Phone
        </div>
    </div>
    <div class="col-xs-4">
        <div class="input-group">							
            <?php echo $this->form->input('phone',array('label'=>false,'id'=>'login_phone','maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','value'=>isset ($get_record['Admin']['phone']) ? $get_record['Admin']['phone'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
    <div class="col-xs-2">
        <div class="input-group">
            Designation
        </div>
    </div>
     <div class="col-xs-4">
        <div class="input-group">							
           <?php echo $this->form->input('designation',array('label'=>false,'class'=>'form-control','id'=>'login_designation','value'=>isset ($get_record['Admin']['designation']) ? $get_record['Admin']['designation'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
</div>
  
<div class="col-md-12">
    <div class="col-xs-2">
        <div class="input-group">
            Status
        </div>
    </div>
    <div class="col-xs-4">
        <div class="input-group">							
            <?php echo $this->form->input('user_active',array('label'=>false,'id'=>'user_active','class'=>'form-control','options'=>array('1'=>'Active','0'=>'DeActive'),'value'=>isset ($get_record['Admin']['user_active']) ? $get_record['Admin']['user_active'] : ""));?>
       </div>
    </div>
    
</div>












                           

