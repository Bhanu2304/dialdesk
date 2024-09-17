<div class="container" id="registration-form">
    <a href="<?php echo $this->webroot;?>" class="login-logo"><img src="<?php echo $this->webroot;?>assets/img/logo.png"></a>
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Company Registration</h2>
		</div>
                <div class="panel-body"> 
                    <form action="<?php echo $this->webroot;?>ClientActivations/save_company" method="post" accept-charset="utf-8" id="company_register_form" class="registration-form form-horizontal row-border" enctype="multipart/form-data" data-parsley-validate >
                        <fieldset id="fieldset1" >
                            <h4 style="text-align: center;">Step: 1 / 3</h4>
                            <div class="col-md-4">
                               <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('company_name',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['company_name']) ? $data['TmpRegistrationMaster']['company_name'] : "",'id'=>'company_name','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Company Name'));?> 
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('reg_office_address1',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['reg_office_address1']) ? $data['TmpRegistrationMaster']['reg_office_address1'] : "",'id'=>'office_address1','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Reg Office Address 1'));?>
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('reg_office_address2',array('label'=>false,'maxlength'=>'250','value'=>isset ($data['TmpRegistrationMaster']['reg_office_address2']) ? $data['TmpRegistrationMaster']['reg_office_address2'] : "",'id'=>'office_address2','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Reg Office Address 2')); ?>
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('state',array('label'=>false,'options'=>$state,'value'=>isset ($data['TmpRegistrationMaster']['state']) ? $data['TmpRegistrationMaster']['state'] : "",'id'=>'state','empty'=>'Select State','autocomplete'=>'off','class'=>'form-control'));?>
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('city',array('label'=>false,'id'=>'city','value'=>isset ($data['TmpRegistrationMaster']['city']) ? $data['TmpRegistrationMaster']['city'] : "",'autocomplete'=>'off','placeholder'=>'City','class'=>'form-control'));?>
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('pincode',array('label'=>false,'maxlength'=>'6','onkeypress'=>'return checkCharacter(event,this)','value'=>isset ($data['TmpRegistrationMaster']['pincode']) ? $data['TmpRegistrationMaster']['pincode'] : "",'id'=>'pincode','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Pincode'));?>
                                    </div>
                                </div> 
                            </div>
                           
                            <div class="col-md-4">
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <?php echo $this->Form->input('auth_person',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['auth_person']) ? $data['TmpRegistrationMaster']['auth_person'] : "",'id'=>'authorised_person','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Authorised Person'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                      
                                        <?php echo $this->Form->input('designation',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['designation']) ? $data['TmpRegistrationMaster']['designation'] : "",'id'=>'designation','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Designation'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                             
                                        <?php echo $this->Form->input('phone_no',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','value'=>isset ($data['TmpRegistrationMaster']['phone_no']) ? $data['TmpRegistrationMaster']['phone_no'] : "",'id'=>'phone','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Mobile No'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                              
                                        <?php echo $this->Form->input('email',array('label'=>false,'value'=>isset ($data['TmpRegistrationMaster']['email']) ? $data['TmpRegistrationMaster']['email'] : "",'id'=>'email','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Email'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                              
                                        <?php echo $this->Form->input('password',array('label'=>false,'value'=>isset ($data['TmpRegistrationMaster']['password']) ? $data['TmpRegistrationMaster']['password'] : "",'id'=>'password','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Password'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                              
                                        <input type="password" id="confirmpass" placeholder="Confirm Password" class="form-control" autocomplete='off' value="<?php if(isset($data['TmpRegistrationMaster']['password'])){echo $data['TmpRegistrationMaster']['password'];}?>"    />
                                    </div>
                                </div>
                                
                            </div>
                           
                            <div class="col-md-4"> 
                                <div class="col-xs-12" style="padding-top: 17px;">
                                    <label class="col-sm-6 control-label">Same As Reg Office</label>	
                                    <div class="col-sm-6">
                                        <div class="checkbox checkbox-black" >
                                            <label><input type="checkbox" onClick="sameAs()" <?php if(isset($data['TmpRegistrationMaster']['sameAs']) && $data['TmpRegistrationMaster']['sameAs']==="check"){echo "checked";}?> value="check" id="sameas"  > </label>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                   
                                        <?php echo $this->Form->input('comm_address1',array('label'=>false,'maxlength'=>'250','value'=>isset ($data['TmpRegistrationMaster']['comm_address1']) ? $data['TmpRegistrationMaster']['comm_address1'] : "",'id'=>'comm_address1','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Communication Office Address 1')); ?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                   
                                        <?php echo $this->Form->input('comm_address2',array('label'=>false,'maxlength'=>'250','value'=>isset ($data['TmpRegistrationMaster']['comm_address2']) ? $data['TmpRegistrationMaster']['comm_address2'] : "",'id'=>'comm_address2','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Communication Office Address 2')); ?>
                                    </div>
                                </div>
                               
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('comm_state',array('label'=>false,'options'=>$state,'empty'=>'Select State','class'=>'form-control','id'=>'comm_state','autocomplete'=>'off'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                                                                 
                                        <?php echo $this->Form->input('comm_city',array('label'=>false,'placeholder'=>'City','class'=>'form-control','id'=>'comm_city','autocomplete'=>'off'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                                                                 
                                        <?php echo $this->Form->input('comm_pincode',array('label'=>false,'maxlength'=>'6','onkeypress'=>'return checkCharacter(event,this)','value'=>isset ($data['TmpRegistrationMaster']['comm_pincode']) ? $data['TmpRegistrationMaster']['comm_pincode'] : "",'id'=>'comm_pincode','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Pincode'));?>
                                      
                                    </div>
                                </div><br/><br/>     
                                
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>                                                                                                                                                                                                                        
                                     <div style="margin-top: 35px;margin-left:-233%;" >Note -  All field is menditeary.</div>
                                </div>
                         
                            </div>
                            
                           
                            
                            
                           
                                     
                            <div class="prenext-btn">
                                <a href="<?php echo $this->webroot;?>client_activations/login" class="btn btn-default pull-left">Login</a>
                                <input type="button"class="btn btn-next btn-web " id="goNext1" onclick="go_to_next1()"  value="Next" >
                                <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder" />  
                            </div> 
                            
                            
                        </fieldset> 
                 	                   			
                        <fieldset id="fieldset2">
                            <h4 style="text-align: center;">Step: 2 / 3</h4>
                            
                            <div class="col-md-4">
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('contact_person1',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['contact_person1']) ? $data['TmpRegistrationMaster']['contact_person1'] : "",'id'=>'contact_person1','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Contact Person 1'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp1_designation',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['cp1_designation']) ? $data['TmpRegistrationMaster']['cp1_designation'] : "",'id'=>'cp1_designation','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Designation'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp1_phone',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','value'=>isset ($data['TmpRegistrationMaster']['cp1_phone']) ? $data['TmpRegistrationMaster']['cp1_phone'] : "",'id'=>'cp1_phone','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Mobile No'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp1_email',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['cp1_email']) ? $data['TmpRegistrationMaster']['cp1_email'] : "",'id'=>'cp1_email','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Email'));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('contact_person2',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['contact_person2']) ? $data['TmpRegistrationMaster']['contact_person2'] : "",'id'=>'contact_person2','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Contact Person 2'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp2_designation',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['cp2_designation']) ? $data['TmpRegistrationMaster']['cp2_designation'] : "",'id'=>'cp2_designation','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Designation'));?>
                                    </div>
                                </div>
                                       
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp2_phone',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','value'=>isset ($data['TmpRegistrationMaster']['cp2_phone']) ? $data['TmpRegistrationMaster']['cp2_phone'] : "",'id'=>'cp2_phone','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Mobile No'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp2_email',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['cp2_email']) ? $data['TmpRegistrationMaster']['cp2_email'] : "",'id'=>'cp2_email','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Email'));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('contact_person3',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['contact_person3']) ? $data['TmpRegistrationMaster']['contact_person3'] : "",'id'=>'contact_person3','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Contact Person 3'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp3_designation',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['cp3_designation']) ? $data['TmpRegistrationMaster']['cp3_designation'] : "",'id'=>'cp3_designation','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Designation'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp3_phone',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','value'=>isset ($data['TmpRegistrationMaster']['cp3_phone']) ? $data['TmpRegistrationMaster']['cp3_phone'] : "",'id'=>'cp3_phone','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Mobile No'));?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>                                                                                                                                                                                          
                                        <?php echo $this->Form->input('cp3_email',array('label'=>false,'maxlength'=>'255','value'=>isset ($data['TmpRegistrationMaster']['cp3_email']) ? $data['TmpRegistrationMaster']['cp3_email'] : "",'id'=>'cp3_email','autocomplete'=>'off','class'=>'form-control','placeholder'=>'Email'));?>
                                    </div>
                                </div>
                                
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>                                                                                                                                                                                                                        
                                     <div style="margin-top: 35px;margin-left:-233%;" >Note - Details for at least one person is mandatory.</div>
                                </div>
                                
                            </div>
                    

                            <div class="prenext-btn">
                                <input type="button" class="btn btn-previous btn-web"  value="Prev" >   
                                <input type="button" class="btn btn-next btn-web" id="goNext2" onclick="go_to_next2()"  value="Next" >
                                <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder1" />
                            </div>
                           <!--
                            <h5 style="margin-left:35px;position: relative;top:30px;" >Note - Details for at least one person is mandatory.</h5>
                            -->
                        </fieldset>
			              
   
      
                        <fieldset id="fieldset3">
                            <h4 style="text-align: center;">Step: 3 / 3</h4>
                            
                            <div class="col-md-12">
                                 <div class="col-xs-12">
                                      <div id="step3_box" style="width:500px;margin-left: 115px;" >
                                         <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                         <span id="step3_msgbox"></span>
                                    </div>
                                 </div>
                            </div>
                           
                             <div class="col-md-6">
                                 <div class="col-xs-12">
                                    <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile[]" id="upload_doc"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Incorporation Certificate">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>
                                 
                                 <div class="col-xs-12">
                                    <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile2[]" id="upload_doc2"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="PAN Card">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>
                             </div>
                            
                             <div class="col-md-6">
                                 <div class="col-xs-12">
                                    <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile3[]" id="upload_doc3"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Billing Address Proof">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>
                                 
                                 <div class="col-xs-12">
                                    <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile4[]" id="upload_doc4"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Authorized Person ID">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>
                             </div>
                            
                             <div class="col-md-6">
                                 <div class="col-xs-12">
                                     <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile5[]" id="upload_doc5"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Authorized Person Address Proof">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>
                                 
                             </div>
                            
                             <div class="col-md-6">
                                <div class="col-xs-12">
                                     <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile6[]" id="upload_doc6"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Upload Company Logo">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>  
                            </div>
                            
                            
                            <div class="col-md-6">
                                <div class="col-xs-12">
                                     <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label"></label>
                                    <input type="file" multiple="multiple" name="userfile7[]" id="upload_doc7"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Upload Other Documents">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                </div>  
                            </div>
                            
                            <div class="col-md-6">
                                <div class="col-xs-12">
                                    <div class="checkbox checkbox-black" style="padding-top:22px;margin-left: 115px;" >
                                        <label>
                                           <a href="#" style="font-size:13px;text-decoration:underline;">Terms & Conditions</a>
                                           <input type="checkbox"  id="terms_condition" >
                                        </label>                                   
                                        </div>                            
                                </div>  
                            </div>
                            
                            <div class="col-md-12">
                                <div class="col-xs-12">    
                                    <h5 style="margin-left:115px;position: relative;top:30px;" >Note - Please upload only jpg,jpeg,gif,png,pdf documents.</h5>              
                                </div>  
                            </div>
                            
                          
                            
                            
                            
                                 
                        
                                
                                
                                
                           
                                
                                
                                
                                
                                
                        
                                
                                
                           
                              
                            <div class="prenext-btn"> 
                                <input type="button" class="btn btn-previous btn-web"  value="Prev" >   
                                <input type="button" class="btn btn-next btn-web" id="goNext3" onclick="go_to_next3()"  value="Submit" >
                                <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder_submit" />
                            </div>
                        </fieldset>

                        <div class="otp">
                          <!--
                            <div class="cancle" onclick="closePopup()">&times;</div> 
                          -->
                          <h4 style="margin-top:30px;">Client Authentication <i class="fa fa-user detail" aria-hidden="true"></i></h4>  
                          <hr/>
                          <p>OTP is sent to your registered phone no so please enter these details to varify your phone no.</p>
                           
                          <div id="otp_box" style="display:none" >
                                 <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                 <span id="otp_msgbox"></span>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">OTP</label>	
                                <div class="col-sm-8">                                
                                    <input type="text" class="form-control" id="otp_data" style="width:200px;" autocomplete="off" placeholder="One Time Password" />
                                </div>
                            </div>
                            
                            <input  type="button"  onclick="sent_otp()" class="btn btn-default pull-right" value="Resend" />
                            <input  type="button" onclick="save_otp('<?php echo $this->webroot;?>ClientActivations/matchotp')" class="btn btn-primary pull-right" value="Submit" />
                            
                        </div>
                        <div id="cover" ></div>
                        
                        
                        <input type="hidden" id="numberverify" value="<?php echo $this->Session->read('verify_no');?>" />
                        <input type="hidden" id="phone_verify" value="<?php if(isset($data['TmpRegistrationMaster']['phone_no'])){echo $data['TmpRegistrationMaster']['phone_no'];}?>"  />
                        <input type="hidden" id="view_contact2" value="<?php if(isset($data['TmpRegistrationMaster']['contact_person2'])){echo $data['TmpRegistrationMaster']['contact_person2'];}?>"  />
                        <input type="hidden" id="view_contact3" value="<?php if(isset($data['TmpRegistrationMaster']['contact_person3'])){echo $data['TmpRegistrationMaster']['contact_person3'];}?>"  />
                        <input type="hidden" id="company_url" value="<?php echo $this->webroot;?>ClientActivations/check_exist_company"  />
                        <input type="hidden" id="url" value="<?php echo $this->webroot;?>ClientActivations/sendotp"  />
                        <input type="hidden" id="smtpUrl" value="<?php echo $this->webroot;?>ClientActivations/checkSmtpValidation"  /> 
                        <input type="hidden" id="existemail" value="<?php echo $this->webroot;?>ClientActivations/check_exist_email"  />  
                        <input type="hidden" id="save_first_form_url" value="<?php echo $this->webroot;?>ClientActivations/save_firstForm_data"  /> 
                        <input type="hidden" id="save_second_form_url" value="<?php echo $this->webroot;?>ClientActivations/save_secondForm_data"  />
                        <input type="hidden" id="delete_otp" value="<?php echo $this->webroot;?>ClientActivations/delete_otp_session"  />
                        <input type="hidden" id="check_exist_phone" value="<?php echo $this->webroot;?>ClientActivations/check_exist_phone"  />
                        <input type="hidden" id="verify_email1" value="<?php if(isset($data['TmpRegistrationMaster']['email'])){echo $data['TmpRegistrationMaster']['email'];}?>"  />
                    </form>
                </div>	
            </div>
        </div>
    </div>
</div>


