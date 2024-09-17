<div id="wrapper">
	<div id="register">
		<form action="<?php echo $this->webroot;?>ClientActivations/save_company" method="post" accept-charset="utf-8" id="company_register_form" class="registration-form" 
        enctype="multipart/form-data">
            <fieldset id="fieldset1" >
                <h1>Company Registration 1 / 3</h1>
                <h2>Company Details:</h2>
                <div class="form-bottom">
                    <table>
                        <tr>
                            <td>Name Of Company<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('company_name',array('label'=>false,'id'=>'company_name','autocomplete'=>'off'));?></td>
                        </tr>
                        
                        <tr>
                            <td>Registered Office Address 1<font style="color:red;">*</font></td>
                            
                            <td><?php echo $this->Form->textarea('reg_office_address1',array('label'=>false,'id'=>'office_address1','autocomplete'=>'off')); ?></td>
                        </tr>
                        
                        <tr>
                            <td>Address 2</td>
                            <td> <?php echo $this->Form->textarea('reg_office_address2',array('label'=>false,'id'=>'office_address2','autocomplete'=>'off')); ?></td>
                           
                        </tr>
                         <td>City<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('city',array('label'=>false,'id'=>'city','autocomplete'=>'off'));?></td>
                        <tr>
                        
                        </tr>
                        
                        <tr>
                            <td>State<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('state',array('label'=>false,'id'=>'state','autocomplete'=>'off'));?></td>
                            
                        </tr>
                        
                        <tr>
                        <td>PIN Code<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('pincode',array('label'=>false,'id'=>'pincode','autocomplete'=>'off'));?></td>
                        </tr>
                        
                        <tr><td><hr/></td><td><hr/></td></tr></tr>
                        <tr><td> <h2>Authorised Person Details-</h2></td></tr>
                       
                        <tr>
                            <td>Authorised Person Name(Director/Owner/Partner)<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('auth_person',array('label'=>false,'id'=>'authorised_person','autocomplete'=>'off'));?></td>
                            
                        </tr>
                        
                        <tr>
                        <td>Designation</td>
                            <td><?php echo $this->Form->input('designation',array('label'=>false,'id'=>'designation','autocomplete'=>'off'));?></td>
                        </tr>
                            
                        <tr>
                            <td>Number(Number Validation Required)<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('phone_no',array('label'=>false,'id'=>'phone','autocomplete'=>'off'));?></td>
                        </tr>
                        
                        <tr>
                            <td>Email (Mail ID Validation Required)<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('email',array('label'=>false,'id'=>'email','autocomplete'=>'off'));?></td>
                        </tr>
                    
                         <tr><td><hr/></td><td><hr/></td></tr></tr>
                        <tr><td> <h2>Communication Details-</h2></td></tr>
                    
                          <tr>
                        <td>Same As Abobe</td>
                            <td><input type="checkbox" onClick="sameAs()" id="sameas" ></td>	
                        <tr>
                        <tr>
                            <td>Communication Address 1</td>
                            <td><?php echo $this->Form->textarea('comm_address1',array('label'=>false,'id'=>'comm_address1','autocomplete'=>'off')); ?><td>
                            
                        </tr>
                  
                            
                        <tr>
                            <td>Address 2</td>
                            <td><?php echo $this->Form->textarea('comm_address2',array('label'=>false,'id'=>'comm_address2','autocomplete'=>'off')); ?></td>
                            
                        </tr>
                        
                        <tr>
                        <td>City</td>
                            <td><?php echo $this->Form->input('comm_city',array('label'=>false,'id'=>'comm_city','autocomplete'=>'off'));?></td>
                        </tr>
                        
                        <tr>
                            <td>State</td>
                            <td><?php echo $this->Form->input('comm_state',array('label'=>false,'id'=>'comm_state','autocomplete'=>'off'));?></td>
                          
                        </tr>
                        
                        <tr>
                          <td>PIN	Code</td>
                            <td><?php echo $this->Form->input('comm_pincode',array('label'=>false,'id'=>'comm_pincode','autocomplete'=>'off'));?></td>
                        </tr>
                    </table>
                      <p class="signin button">
                    <input type="button" class="btn btn-next" id="goNext1"  value="Next" >
                    </p>
                    <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:50px;margin-left:160px;display:none;" id="ckloder" />
                   
                    <br/>
                </div>              
            </fieldset> 
                 	                   			
            <fieldset id="fieldset2">
                <div class="form-top">
                    <div class="form-top-left">
                        <h1>Company Registration 2 / 3</h1>
                    </div>
                </div>
                <div class="form-bottom">
                    <div class="form-group">
                        <h2>Contact Person Details 1-</h2>
                        
                        <table>
                            <tr>
                                <td>Contact Person 1<font style="color:red;">*</font></td>
                                <td><?php echo $this->Form->input('contact_person1',array('label'=>false,'id'=>'contact_person1','autocomplete'=>'off'));?></td>
                            </tr>
                            
                            <tr>
                                <td>Designation</td>
                                <td><?php echo $this->Form->input('cp1_designation',array('label'=>false,'id'=>'cp1_designation','autocomplete'=>'off'));?></td>
                            </tr>
                                
                            <tr>
                                <td>Number (Number Validation Required)<font style="color:red;">*</font></td>
                                <td><?php echo $this->Form->input('cp1_phone',array('label'=>false,'id'=>'cp1_phone','autocomplete'=>'off'));?></td>
                            </tr>
                            
                             <tr>
                             <td>Email (Mail ID Validation Required)<font style="color:red;">*</font></td>
                                <td><?php echo $this->Form->input('cp1_email',array('label'=>false,'id'=>'cp1_email','autocomplete'=>'off'));?></td>
                            </tr>
                        
                         <tr><td><hr/></td><td><hr/></td></tr></tr>
                        <tr><td> <h2>Contact Person Details 2-</h2></td></tr>
                        
                            <tr>
                                <td>Contact Person 2</td>
                                <td><?php echo $this->Form->input('contact_person2',array('label'=>false,'id'=>'contact_person2','autocomplete'=>'off'));?></td>
                            </tr>
                            
                            <tr>
                                <td>Designation</td>
                                <td><?php echo $this->Form->input('cp2_designation',array('label'=>false,'id'=>'cp2_designation','autocomplete'=>'off'));?></td>
                            </tr>
                                
                            <tr>
                                <td>Number</td>
                                <td><?php echo $this->Form->input('cp2_phone',array('label'=>false,'id'=>'cp2_phone','autocomplete'=>'off'));?></td>
                            </tr>
                            
                             <tr>
                               <td>Email</td>
                                <td><?php echo $this->Form->input('cp2_email',array('label'=>false,'id'=>'cp2_email','autocomplete'=>'off'));?></td>
                            </tr>
                        
                          <tr><td><hr/></td><td><hr/></td></tr></tr>
                        <tr><td> <h2>Contact Person Details 3-</h2></td></tr>
                        
                        
                        
                            <tr>
                                <td>Contact Person 3</td>
                                <td><?php echo $this->Form->input('contact_person3',array('label'=>false,'id'=>'contact_person3','autocomplete'=>'off'));?></td>
                               
                            </tr>
                            
                            <tr>
                                <td>Designation</td>
                                <td><?php echo $this->Form->input('cp3_designation',array('label'=>false,'id'=>'cp3_designation','autocomplete'=>'off'));?></td>
                            </tr>
                                
                            <tr>
                                <td>Number</td>
                                <td><?php echo $this->Form->input('cp3_phone',array('label'=>false,'id'=>'cp3_phone','autocomplete'=>'off'));?></td>               
                            </tr>
                            
                             <tr>
                                 <td>Email</td>
                                <td><?php echo $this->Form->input('cp3_email',array('label'=>false,'id'=>'cp3_email','autocomplete'=>'off'));?></td>
                            </tr>
                        </table>
                        <div style="font-size:15px;">Note - Details for 1 contact person is mandatory& 2 optional</div><br/><br/>
                    </div>
                    <p class="signin button">
                    <input type="button" class="btn btn-previous"  style="width:100px;" value="Previous" >   
                    <input type="button" class="btn btn-next" id="goNext2" style="width:100px;" value="Next" > 
                    </p>
                     <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:50px;margin-left:160px;display:none;" id="ckloder1" />
                  
                </div>
            </fieldset>
			                    
            <fieldset id="fieldset3">
                <div class="form-top">
                    <div class="form-top-left">
                        <h1>Company Registration 3 / 3</h1>
                    </div>
                </div>
                <div class="form-bottom">
                    <div class="form-group">
                        <h2>Documents Details-</h2>
                        <table>
                            <tr>
                                <td>Upload Documentation (Mandate Image Upload Options)</td>
                                <td><input type="file" multiple="multiple" name="userfile[]" id="upload_doc" size="43"/></td>
                            </tr>
                            
                            <tr>
                                <td>Incorporation Certificate/Company Deed</td>
                                <td><?php echo $this->Form->input('incorporation_certificate',array('label'=>false,'id'=>'inco_certificate','autocomplete'=>'off'));?></td>
                            </tr>
                            
                            <tr>
                                <td>PAN Card</td>
                                <td><?php echo $this->Form->input('pancard',array('label'=>false,'id'=>'pancard','autocomplete'=>'off'));?></td>
                            </tr>
                            
                            <tr>
                                <td>Billing </td>
                                <td><?php echo $this->Form->input('bill_address_prof',array('label'=>false,'id'=>'belling_address_pfof','autocomplete'=>'off'));?></td>
                            </tr>
                            
                            <tr>
                                <td>Authorized Person Id</td>
                                 <td><?php echo $this->Form->input('authorized_id_prof',array('label'=>false,'autocomplete'=>'off'));?></td>
                            </tr>
                            
                            
                           
                            <tr>
                                <td>Authorized Person Address Prof</td>
                                <td><?php echo $this->Form->input('auth_person_address_prof',array('label'=>false,'id'=>'auth_person_address_prof','autocomplete'=>'off'));?></td>
                            </tr>
                               
                            <tr>
                                <td><a href="" style="font-size:15px;font-style:italic;">Terms&Conditions</a></td>
                                <td><input type="checkbox" id="terms_condition" ></td>
                            </tr>
                        </table>
                        <div style="font-size:15px;">Note -Terms in hyperlink to read and mandate to.</div><br /><br/>
                    </div>
                    <p class="signin button">
                        <input type="button" class="btn btn-previous" style="width:100px;" value="Previous" >
                        <input type="button"   id="goNext3" style="width:100px;cursor:pointer;" value="Submit" > 
                     </p>
                    <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:50px;margin-left:160px;display:none;" id="ckloder_submit" />
                </div>
            </fieldset>

            <div class="otp"><br/>
                <label>Enter OTP</label>
                <input type="text" id="otp_data" />
                 <p class="signin button">
                    <input type="button" style="width:75px;" onclick="save_otp('<?php echo $this->webroot;?>ClientActivations/matchotp')" value="Submit" >
                </p>
            </div>
			<div id="cover" ></div>

			<input type="hidden" id="numberverify" value=""  />
            <input type="hidden" id="url" value="<?php echo $this->webroot;?>ClientActivations/sendotp"  />
            <input type="hidden" id="smtpUrl" value="<?php echo $this->webroot;?>ClientActivations/checkSmtpValidation"  /> 
            <input type="hidden" id="existemail" value="<?php echo $this->webroot;?>ClientActivations/check_exist_email"  />   
		</form>
	</div>	
</div>