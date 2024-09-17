<?php $base_url=$this->webroot.'MyAccounts/';?>
<?php if($data['incorporation_certificate']=="" || $data['pancard']=="" || $data['bill_address_prof']=="" || $data['authorized_id_prof']=="" || $data['auth_person_address_prof']==""){$result=false;}else{$result=true;}?> 
<script src="<?php echo $this->webroot?>myaccount_script/tabcontent.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot?>myaccount_script/myaccount.js" type="text/javascript"></script>
<link href="<?php echo $this->webroot?>myaccount_script/tabcontent.css" rel="stylesheet" type="text/css" />
<ol class="breadcrumb">                            
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">My Account</a></li>
    <li class="active"><a href="#">Update Details</a></li>
    
</ol>
<div class="page-heading">            
   <h1>My Account</h1>
</div>     
<div class="container-fluid foam-details">
    <form class="form-horizontal" action="<?php echo $base_url.'update_contact_details';?>" method="post" accept-charset="utf-8" id="contact_details_form"  >
        <?php echo $this->Form->hidden('company_id',array('label'=>false,'value'=>isset ($data['company_id']) ? $data['company_id'] : ""));?>  
        
         
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'>
                    <div class="panel-body detail">
                        
                        <h4>Company Details <i class="fa fa-user detail" aria-hidden="true"></i></h4>
                         
                        <div class="tab-content">
                            <div class="tab-pane active scrolling" id="horizontal-form"   >
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                    <tr>
                                        <th>Company Name:</th>
                                        <td><?php echo isset ($data['company_name']) ? $data['company_name'] : "";?></td>
                                        <th>State:</th>
                                        <td><?php echo isset ($data['state']) ? $data['state'] : "";?></td>
                                    </tr>              
                                    <tr>
                                        <th>City:</th>
                                        <td><?php echo isset ($data['city']) ? $data['city'] : "";?></td>
                                        <th>Pincode:</th>
                                        <td><?php echo isset ($data['pincode']) ? $data['pincode'] : "";?></td>
                                    </tr>                               
                                    <tr>
                                        <th>Address1:</th>
                                        <td><?php echo isset ($data['reg_office_address1']) ? $data['reg_office_address1'] : "";?></td>
                                        <th>Address2:</th>
                                        <td><?php echo isset ($data['reg_office_address2']) ? $data['reg_office_address2'] : "";?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'>
                    <div class="panel-body detail">
                        <h4>Client Details <i class="fa fa-user detail" aria-hidden="true"></i></h4>
                        <div class="tab-content">
                            <div class="tab-pane active scrolling" id="horizontal-form">
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                    <tr>
                                        <th>Name:</th>
                                        <td><?php echo isset ($data['auth_person']) ? $data['auth_person'] : "";?></td>
                                      
                                        <th>Designation:</th>
                                        <td><?php echo isset ($data['designation']) ? $data['designation'] : "";?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>
                                            <?php echo isset ($data['phone_no']) ? $data['phone_no'] : "";?>
                                            
                                            <a  href="#" data-toggle="modal" data-target="#change-phone" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a> 
                                 
                                            <input type="hidden" id="cphone" value="<?php echo isset ($data['phone_no']) ? $data['phone_no'] : "";?>" >
                                            <input type="hidden" id="newphno" value="" >
                                            <input type="hidden" id="contact1_phoneno" value="<?php echo isset ($data['cp1_phone']) ? $data['cp1_phone'] : "";?>" >
                                        </td>
                         
                                        <th>Email:</th>
                                        <td>
                                            <?php echo isset ($data['email']) ? $data['email'] : "";?>
                                            <a  href="#" data-toggle="modal" data-target="#change-email" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a>  
                                        </td>                                      
                                    </tr> 
                                    
                                    <tr>
                                        <th>Company Logo</th>
                                        <td> 
                                            <img id="company_logo" style="width:30px;" src="<?php if($data['company_logo'] !=""){ ?><?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$data['company_logo'];?> <?php }else{?><?php echo $this->webroot;?>images/image_preview.png<?php }?>" >
                                            <a  href="#" data-toggle="modal" data-target="#change-logo" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a>  
                               
                                            
                                            <input type="hidden" id="logourl" value="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id'];?>/" >
                                           
                                        </td>
                                        <th>Password</th>
                                        <td> 
                                            <span style="font-size:20px;">*****</span>
                                            <a  href="#" data-toggle="modal" data-target="#change-password" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a>  
                               
                                           
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
   
        
        <div class="row contactdetails"  >
            <div class="col-md-12">
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'   >
                    <div class="panel-body detail" >
                        <h4>Contact Details <i class="fa fa-user detail" aria-hidden="true"></i></h4>
                        <div class="tab-content">
                            <div class="tab-pane active scrolling" id="horizontal-form">
                                <div class="red" ><?php if(isset($update) && $update ==="cod"){ echo "Contact details update successfully.";} ?></div>
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                    <tr>
                                        <td>
                                            <span>Contact Details 1</span>
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                     <th>Action</th>
                                                </tr>
                                                
                                                <tr>
                                                    <td><?php echo isset ($data['contact_person1']) ? $data['contact_person1'] : "";?></td>
                                                    <td><?php echo isset ($data['cp1_designation']) ? $data['cp1_designation'] : "";?></td>
                                                    <td><?php echo isset ($data['cp1_phone']) ? $data['cp1_phone'] : "";?></td>
                                                    <td><?php echo isset ($data['cp1_email']) ? $data['cp1_email'] : "";?></td>
                                                    <td>
                                                         <a  href="#" data-toggle="modal" data-target="#contact-details1" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a>  
                               
                                                      
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Contact Details 2 </span>
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                     <th>Action</th>
                                                </tr>
                                                
                                                <tr>
                                                
                                                    <td><?php echo isset ($data['contact_person2']) ? $data['contact_person2'] : "";?></td>
                                                    <td><?php echo isset ($data['cp2_designation']) ? $data['cp2_designation'] : "";?></td>
                                                    <td><?php echo isset ($data['cp2_phone']) ? $data['cp2_phone'] : "";?></td>
                                                    <td><?php echo isset ($data['cp2_email']) ? $data['cp2_email'] : "";?></td>
                                                    <td><a  href="#" data-toggle="modal" data-target="#contact-details2" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a>  
                               </td>
                                                </tr>
                                                
                                               
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Contact Details 3 </span>
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                     <th>Action</th>
                                                </tr>
                                                
                                                <tr>
                                                    <td><?php echo isset ($data['contact_person3']) ? $data['contact_person3'] : "";?></td>
                                                    <td><?php echo isset ($data['cp3_designation']) ? $data['cp3_designation'] : "";?></td>
                                                    <td><?php echo isset ($data['cp3_phone']) ? $data['cp3_phone'] : "";?></td>
                                                    <td><?php echo isset ($data['cp3_email']) ? $data['cp3_email'] : "";?></td>
                                                    <td><a  href="#" data-toggle="modal" data-target="#contact-details3" class="edit_logo" ><label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label></a>  
                               </td>
                                                </tr>
                                                
                                               
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        
            
        
        <div class="modal fade" id="contact-details1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Contact Details 1 <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                                <div id="mge-error"></div>
                                <div class="form-group">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('contact_person1',array('label'=>false,'class'=>'form-control','placeholder'=>'Name','maxlength'=>'255','value'=>isset ($data['contact_person1']) ? $data['contact_person1'] : ""));?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Designation</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('cp1_designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','maxlength'=>'255','value'=>isset ($data['cp1_designation']) ? $data['cp1_designation'] : ""));?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('cp1_phone',array('label'=>false,'class'=>'form-control','placeholder'=>'Phone','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','value'=>isset ($data['cp1_phone']) ? $data['cp1_phone'] : ""));?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('cp1_email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email','maxlength'=>'255','value'=>isset ($data['cp1_email']) ? $data['cp1_email'] : ""));?>
                                        </div>
                                    </div>    
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button"  onclick="saveContact1();" class="btn btn-web">Update</button>
            </div>
        </div>
    </div>
</div>
        
        <div class="modal fade" id="contact-details2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Contact Details 2 <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                                <div id="mge-error"></div>
                                 <div class="form-group">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('contact_person2',array('label'=>false,'class'=>'form-control','placeholder'=>'Name','maxlength'=>'255','value'=>isset ($data['contact_person2']) ? $data['contact_person2'] : ""));?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Designation</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('cp2_designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','maxlength'=>'255','value'=>isset ($data['cp2_designation']) ? $data['cp2_designation'] : ""));?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('cp2_phone',array('label'=>false,'class'=>'form-control','placeholder'=>'Phone','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','value'=>isset ($data['cp2_phone']) ? $data['cp2_phone'] : ""));?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-8">
                                            <?php echo $this->Form->input('cp2_email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email','maxlength'=>'255','value'=>isset ($data['cp2_email']) ? $data['cp2_email'] : ""));?>
                                        </div>
                                    </div>   
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="saveContact2();" class="btn btn-web">Update</button>
            </div>
        </div>
    </div>
</div>
        
        
    <div class="modal fade" id="contact-details3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Contact Details 3 <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                                <div id="mge-error"></div>
                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-8">
                                        <?php echo $this->Form->input('contact_person3',array('label'=>false,'class'=>'form-control','placeholder'=>'Name','maxlength'=>'255','value'=>isset ($data['contact_person3']) ? $data['contact_person3'] : ""));?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Designation</label>
                                    <div class="col-sm-8">
                                       <?php echo $this->Form->input('cp3_designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','maxlength'=>'255','value'=>isset ($data['cp3_designation']) ? $data['cp3_designation'] : ""));?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Phone</label>
                                    <div class="col-sm-8">
                                         <?php echo $this->Form->input('cp3_phone',array('label'=>false,'class'=>'form-control','placeholder'=>'Phone','onkeypress'=>'return checkCharacter(event,this)','maxlength'=>'10','value'=>isset ($data['cp3_phone']) ? $data['cp3_phone'] : ""));?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-8">
                                         <?php echo $this->Form->input('cp3_email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email','maxlength'=>'255','value'=>isset ($data['cp3_email']) ? $data['cp3_email'] : ""));?>
                                    </div>
                                </div>  
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="saveContact3();" class="btn btn-web">Update</button>
            </div>
        </div>
    </div>
</div>
        
     
    </form>
         <form class="form-horizontal" action="<?php echo $base_url.'update_client';?>" method="post" accept-charset="utf-8" id="myaccount_form" enctype="multipart/form-data" >
        <?php echo $this->Form->hidden('company_id',array('label'=>false,'value'=>isset ($data['company_id']) ? $data['company_id'] : ""));?>  
      

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'>
                    <div class="panel-body detail">
                        <h4>Upload Document <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                        <span style="font-size: 15px;">Note - Upload only (JPG,JPEG,GIF,PNG,PDF) documents.</span>
                        <div class="red" ><?php if(isset($update) && $update ==="cld"){ echo "Contact details update successfully.";} ?></div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">
                                
                                <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">Incorporation Certificate</label>
                                    <input type="file" multiple="multiple" name="userfile[]" id="upload_doc"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Incorporation Certificate / Company Deed">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                
                                <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">PAN Card</label>
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
                                
                                <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">Billing Address Proof</label>
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
                                
                                <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">Authorized Person ID</label>
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
                                
                                <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">Authorized Person Address Proof</label>
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
                                
                                <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">Other Documents</label>
                                    <input type="file" multiple="multiple" name="userfile6[]" id="upload_doc6"  >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Other Documents">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
				</div>
                                
                                <input type="button" style="margin-top:20px;" onclick="saveUpload();" class="btn btn-web pull-right" value="Submit" > 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        
        <div class="row" name="cod" > 
            <?php if($result ==false){?>
            <h4 class="doc-msg">Document Details Not Complete.</h4>
            <?php }?>
            
            <div class="col-md-4" >
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'  >
                    <div class="panel-body detail <?php if($data['incorporation_certificate'] ===""){?>border-red<?php }?>">
                        <?php if($data['incorporation_certificate'] !=""){?>
                        
                        <?php if($data['status'] =="D"){?>
                            <a href="<?php echo $base_url.'delete_documents?item=incorporation_certificate';?>" onclick="return confirm('Are you sure you want to delete this item?');">
                               <i title="Delete" class="fa fa-close btn-danger delete-img"></i>
                            </a>
                         <?php }?>
                        <?php }?>
                        <h4>Incorporation Certificate  <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                        <div class="tab-content scrolling">
                            <div class="tab-pane active" id="horizontal-form">
                                <?php 
                                if(isset($data['incorporation_certificate']) && $data['incorporation_certificate'] !=""){
                                foreach(explode(",",$data['incorporation_certificate']) as $row1){
                                $extension = explode('.',$row1);
                                $extension = $extension[count($extension)-1]; 
                                ?>            
                                <a target="_blank" href="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row1;?>">
                                     <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                    <img class="fancybox imgset"  src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row1;?>"  /><br/><br/>
                                            <?php }?>
                                </a>                                            
                                <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4" >
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'  >
                    <div class="panel-body detail <?php if($data['pancard'] ===""){?>border-red<?php }?>" >
                        <?php if($data['pancard'] !=""){?>
                            <?php if($data['status'] =="D"){?>
                            <a href="<?php echo $base_url.'delete_documents?item=pancard';?>" onclick="return confirm('Are you sure you want to delete this item?');">
                               <i title="Delete" class="fa fa-close btn-danger delete-img"></i>
                            </a>
                         <?php }?>
                        <?php }?>
                        <h4>Pancard <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                            
                            
                        
                        <div class="tab-content scrolling">
                            <div class="tab-pane active" id="horizontal-form">
                                <?php 
                                if(isset($data['pancard']) && $data['pancard'] !=""){
                                foreach(explode(",",$data['pancard']) as $row2){
                                $extension = explode('.',$row2);
                                $extension = $extension[count($extension)-1]; 
                                ?>                                   
                                <a target="_blank" href="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row2;?>">
                                     <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                    <img class="fancybox imgset" src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row2;?>"  />
                                            <?php }?>
                                </a>                                           
                                <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="col-md-4" >
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'  >
                    <div class="panel-body detail <?php if($data['bill_address_prof'] ===""){?>border-red<?php }?>">
                        <?php if($data['bill_address_prof'] !=""){?>
                             <?php if($data['status'] =="D"){?>
                            <a href="<?php echo $base_url.'delete_documents?item=bill_address_prof';?>" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i title="Delete" class="fa fa-close btn-danger delete-img"></i>
                            </a>
                            <?php }?>
                        <?php }?>
                        <h4>Billing Address <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                        <div class="tab-content scrolling">
                            <div class="tab-pane active" id="horizontal-form">
                                <?php 
                                if(isset($data['bill_address_prof']) && $data['bill_address_prof'] !=""){
                                foreach(explode(",",$data['bill_address_prof']) as $row3){
                                $extension = explode('.',$row3);
                                $extension = $extension[count($extension)-1]; 
                                ?>                                  
                                <a target="_blank" href="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row3;?>">
                                     <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                    <img class="fancybox imgset" src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row3;?>"  />
                                            <?php }?>
                                </a>                                           
                                <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="col-md-4" >
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'  >
                    <div class="panel-body detail <?php if($data['authorized_id_prof'] ===""){?>border-red<?php }?>">
                        <?php if($data['authorized_id_prof'] !=""){?>
                             <?php if($data['status'] =="D"){?>
                            <a href="<?php echo $base_url.'delete_documents?item=authorized_id_prof';?>" onclick="return confirm('Are you sure you want to delete this item?');">
                               <i title="Delete" class="fa fa-close btn-danger delete-img"></i>
                            </a>
                            <?php }?>
                        <?php }?>
                        <h4>ID Proof <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                        <div class="tab-content scrolling">
                            <div class="tab-pane active" id="horizontal-form">
                            <?php 
                            if(isset($data['authorized_id_prof']) && $data['authorized_id_prof'] !=""){
                            foreach(explode(",",$data['authorized_id_prof']) as $row4){
                            $extension = explode('.',$row4);
                                $extension = $extension[count($extension)-1];     
                            ?>
                            <a target="_blank" href="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row4;?>">
                                 <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row4;?>"  />
                                            <?php }?>
                            </a>
                            <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="col-md-4" >
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'  >
                    <div class="panel-body detail <?php if($data['auth_person_address_prof'] ===""){?>border-red<?php }?>">
                         <?php if($data['auth_person_address_prof'] !=""){?>
                            <?php if($data['status'] =="D"){?>
                                <a href="<?php echo $base_url.'delete_documents?item=auth_person_address_prof';?>" onclick="return confirm('Are you sure you want to delete this item?');">
                                    <i title="Delete" class="fa fa-close btn-danger delete-img"></i>
                                </a>
                                <?php }?>
                            <?php }?>
                        <h4>Address Proof <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                        <div class="tab-content scrolling">
                            <div class="tab-pane active" id="horizontal-form">
                                <?php 
                                if(isset($data['auth_person_address_prof']) && $data['auth_person_address_prof'] !=""){
                                foreach(explode(",",$data['auth_person_address_prof']) as $row5){
                                $extension = explode('.',$row5);
                                $extension = $extension[count($extension)-1]; 
                                ?>                                   
                                <a target="_blank" href="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row5;?>">
                                     <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                    <img class="fancybox imgset" src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row5;?>"  />
                                            <?php }?>
                                </a>                                           
                                <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div class="col-md-4" >
                <div class="panel panel-primary" data-widget='{"draggable": "false"}'  >
                    <div class="panel-body detail">
                         <?php if($data['other_documents'] !=""){?>
                            <?php if($data['status'] =="D"){?>
                                <a href="<?php echo $base_url.'delete_documents?item=other_documents';?>" onclick="return confirm('Are you sure you want to delete this item?');">
                                    <i title="Delete" class="fa fa-close btn-danger delete-img"></i>
                                </a>
                                <?php }?>
                            <?php }?>
                        <h4>Address Proof <i class="fa fa-user detail" aria-hidden="false"></i></h4>
                        <div class="tab-content scrolling">
                            <div class="tab-pane active" id="horizontal-form">
                                <?php 
                                if(isset($data['other_documents']) && $data['other_documents'] !=""){
                                foreach(explode(",",$data['other_documents']) as $row6){
                                $extension = explode('.',$row6);
                                $extension = $extension[count($extension)-1]; 
                                ?>                                   
                                <a target="_blank" href="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row6;?>">
                                    <?php if(in_array($extension,array('pdf','PDF'))){ ?>
                                                <img class="fancybox imgset" src="<?php echo $this->webroot;?>images/pdflogo.png"  />
                                            <?php }else{?>
                                    <img class="fancybox imgset" src="<?php echo $this->webroot;?>/upload/client_file/client_<?php echo $data['company_id']."/".$row6;?>"  />
                                            <?php }?>
                                </a>                                           
                                <?php }}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <?php echo $this->Form->hidden('incorporation_certificate',array('label'=>false,'value'=>isset ($data['incorporation_certificate']) ? $data['incorporation_certificate'] : ""));?> 
        <?php echo $this->Form->hidden('pancard',array('label'=>false,'value'=>isset ($data['pancard']) ? $data['pancard'] : ""));?> 
        <?php echo $this->Form->hidden('bill_address_prof',array('label'=>false,'value'=>isset ($data['bill_address_prof']) ? $data['bill_address_prof'] : ""));?> 
        <?php echo $this->Form->hidden('authorized_id_prof',array('label'=>false,'value'=>isset ($data['authorized_id_prof']) ? $data['authorized_id_prof'] : ""));?> 
        <?php echo $this->Form->hidden('auth_person_address_prof',array('label'=>false,'value'=>isset ($data['auth_person_address_prof']) ? $data['auth_person_address_prof'] : ""));?>
        <?php echo $this->Form->hidden('other_documents',array('label'=>false,'value'=>isset ($data['other_documents']) ? $data['other_documents'] : ""));?> 
    </form>
</div> <!-- .container-fluid -->


<div class="modal fade" id="change-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Change Password <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <form class="form-horizontal" data-parsley-validate > 
                                <div id="mge-error"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Current Password</label>	
                                    <div class="col-sm-8">      
                                        <?php echo $this->Form->input('currentpass',array('label'=>false,'placeholder'=>'Current Password','class'=>'form-control','type'=>'password','required'=>true,'id'=>'currentpass'));?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">New Password</label>	
                                    <div class="col-sm-8">      
                                        <?php echo $this->Form->input('newpass',array('label'=>false,'placeholder'=>'New Password','class'=>'form-control','type'=>'password','required'=>true,'id'=>'newpass'));?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Confirm</label>	
                                    <div class="col-sm-8">      
                                        <?php echo $this->Form->input('confirmpass',array('label'=>false,'placeholder'=>'Confirm Password','class'=>'form-control','type'=>'password','required'=>true,'id'=>'confirmpass'));?>
                                    </div>
                                </div>
                            </form>       
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="changePassword()" class="btn btn-web">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Change Email <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <form class="form-horizontal" data-parsley-validate > 
                                <div id="mge-error"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">New Email</label>	
                                    <div class="col-sm-8">      
                                        <?php echo $this->Form->input('newemail',array('label'=>false,'placeholder'=>'New Email','class'=>'form-control','required'=>true,'id'=>'newemail'));?>                               
                                        <input type="hidden" id="cp1email" value="<?php echo isset ($data['cp1_email']) ? $data['cp1_email'] : ""; ?>" >
                                    </div>
                                </div>
                            </form>       
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="loder1" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="changeEmail()" class="btn btn-web">Submit</button>
               
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-phone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Change Phone <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <form class="form-horizontal" data-parsley-validate > 
                                <div id="mge-error"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Phone No</label>	
                                    <div class="col-sm-8">   
                                        <input type="text" name="phone_no" id="newnumber" class="form-control" autocomplete="off" maxlength="10" onkeypress="return checkCharacter(event,this)" > 
                                       
                                        <input type="hidden" id="cp1email" value="<?php echo isset ($data['cp1_email']) ? $data['cp1_email'] : ""; ?>" >
                                    </div>
                                </div>
                            </form>       
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="loder1" />
                <button type="button" id="close-change-phone" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="sendNumber('<?php echo $base_url;?>sendotp','<?php echo $base_url;?>check_exist_phone','<?php echo $base_url;?>check_admin_verify')" class="btn btn-web">Send</button>
               
            </div>
        </div>
    </div>
</div>

<script>
function changeLogo(){
    $("#ms").remove();
    var companylogo=$("#companylogo").val();
    var logourl=$("#logourl").val();
    
    if($.trim(companylogo) ===""){
        $("#companylogo").after('<span style="color:red;" id="ms">Please upload company logo.</span>');
        $("#companylogo").focus();
        return false;
    }
    else{
        var formData = new FormData($("#imageform")[0]);
        $.ajax({
            url: "/dialdesk/MyAccounts/change_logo",
            type: "POST",             
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,      
            cache: false,            
            processData:false,       
            success: function(data){
                if(data ==="2"){
                    $("#companylogo").after('<span style="color:green;" id="ms">Upload only jpg,jpeg,gif,png,pdf.</span>');
                    $("#ms").delay(5000).fadeOut();
                }
                else{
                    $("#companylogo").after('<span style="color:green;" id="ms">Company logo update successfully.</span>');
                    $("#ms").delay(5000).fadeOut();
                    $("#company_logo").attr("src",logourl+data);
                }
            }
        });
    }  
}

</script>
<div class="modal fade" id="change-logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Change Logo <i class="fa fa-user detail" aria-hidden="true"></i></h4>      
            </div>
            <div class="modal-body">
                <div class="panel-body detail">
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">
                            <form id="imageform" >
                                <div id="mge-error"></div>
                              <div class="form-group">
                                    <label for="addon3" class="col-sm-3 control-label">Upload Logo</label>
                                    <input type="file" name="companylogo" id="companylogo"   >
                                    <div class="col-sm-8 input-group">
                                        <input type="text" readonly id="addon3" class="form-control" placeholder="Upload Logo">
                                        <span class="input-group-btn input-group-sm">
                                            <button class="btn btn-fab btn-fab-mini" type="button">
                                                <i class="material-icons">attach_file</i>
                                            </button>
                                        </span>
                                    </div>
                                    
				</div>
                            </form>       
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="changeLogo()" class="btn btn-web">Submit</button>
            </div>
        </div>
    </div>
</div>

<p id="msgpopup" data-toggle="modal" data-target="#popup-message"></p>
<div class="modal fade" id="popup-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                
                <h4> </h4>      
            </div>
          
            <div class="modal-body">
                <div id="show-message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="otpdiv" >       
   <span  class="otpcancle" onclick="closePopup()">&times;</span>
   <h4>Client Authentication <i class="fa fa-user detail" aria-hidden="true"></i></h4>     
   <p>OTP is sent to your registered phone no so please enter these details to varify your phone no.</p>
 
   <div class="form-group">
        <label class="col-sm-2 control-label">OTP</label>	
        <div class="col-sm-8">                                
            <input type="text" placeholder="Password" class="form-control" name="otpval" id="otpval" required autocomplete="off" />
        </div>
    </div>
    
    <input type="hidden" id="delete_newotp" value="<?php echo $base_url;?>delete_otp_session"  />
    <input  type="button"  onclick="sent_otp('<?php echo $base_url;?>sendotp')" class="btn btn-default pull-right" value="Resend" />
    <input  type="button" onclick="changePhone()" class="btn btn-web pull-right" value="Submit" />
    
</div>
<div id="coverdiv" ></div>








