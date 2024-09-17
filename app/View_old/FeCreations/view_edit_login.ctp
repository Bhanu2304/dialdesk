<?php ?>
<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <div id="elMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
            <input type="hidden" id="loginid" value="<?php echo isset ($get_record['FecreationMaster']['id']) ? $get_record['FecreationMaster']['id'] : "";?>" >
        </div>
    </div>
</div>
               
<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
           <?php echo $this->form->input('name',array('label'=>false,'id'=>'login_name','class'=>'form-control','value'=>isset ($get_record['FecreationMaster']['name']) ? $get_record['FecreationMaster']['name'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
</div>

<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <?php echo $this->form->input('emailid',array('label'=>false,'id'=>'login_emailid','class'=>'form-control','value'=>isset ($get_record['FecreationMaster']['username']) ? $get_record['FecreationMaster']['username'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
 </div>

<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
             <?php echo $this->form->input('password',array('label'=>false,'id'=>'login_password','class'=>'form-control','value'=>isset ($get_record['FecreationMaster']['password2']) ? $get_record['FecreationMaster']['password2'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
</div>

<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <input type="password" id="login_confirm_password" value="<?php if(isset($get_record['FecreationMaster']['password2'])){ echo $get_record['FecreationMaster']['password2'];}?>" class="form-control"   />
       </div>
    </div>
</div>

<div class="col-md-12">
    <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <?php echo $this->form->input('phone',array('label'=>false,'id'=>'login_phone','maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','value'=>isset ($get_record['FecreationMaster']['phone']) ? $get_record['FecreationMaster']['phone'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
</div>
  
<div class="col-md-12">
     <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon" >
                <i class="ti ti-user"></i>
            </span>
           <?php echo $this->form->input('designation',array('label'=>false,'class'=>'form-control','id'=>'login_designation','value'=>isset ($get_record['FecreationMaster']['designation']) ? $get_record['FecreationMaster']['designation'] : "",'autocomplete'=>'off'));?>
       </div>
    </div>
</div>

<!--
<div class="col-md-12">
     <div class="col-xs-12">
        <div class="input-group" >							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <h4>User Right</h4>
       </div>
    </div>
</div>

<div class="col-md-12">
     <div class="col-xs-12">
        <div class="input-group">							
            <span class="input-group-addon">
                <i class="ti ti-user"></i>
            </span>
            <ol class="user-tree assign-right" >
                <?php //echo $UserRight;?>
            </ol>
       </div>
    </div>
</div>
-->
               








                           

