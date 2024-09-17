<?php echo $this->Html->script('admin_creation'); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Dispositions</a></li>
    <li class="active"><a href="#">Mobile Users</a></li>
</ol>
<div class="page-heading">            
    <h1>Dispositions</h1>
</div>
<style>
.sorting{text-transform: uppercase;}
.form-group input[type=file] {
    opacity: 1;
    position: relative;
}
.form-group select {
    -webkit-appearance: auto;
    -moz-appearance: auto;
    appearance: auto;
}
label.control-label {text-transform: uppercase;}
.form-group {
    margin-top: 0;
}
</style>
<script>
 function checkNumber(val,evt)
  {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    
    if (charCode> 31 && (charCode < 48 || charCode > 57) )
        {            
            return false;
        }
        if(val.length>10)
        {
            return false;
        }
    return true;
}
</script>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Dispositions</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body"  style="display:none1;">
                <?php echo $this->Form->create('MobileUploads',array('action'=>'dispositions','id'=>'index','method'=>'Post')); ?>
                   
                        <div class="col-md-8  col-sm-offset-2">


                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <div id="erroMsg" style="color:red;font-size: 15px;text-align:center;">
                                            <?php echo $this->Session->flash();?>
					                    </div>      
                                    </div>
                                </div>
                           	
                            <div class="col-xs-4">
                                <div class="input-group">							
                                    
                                    <label>Choose Dispositions</label>
                                    <select id="disposition" name="disposition" placeholder='disposition' class="form-control" autocomplete='off'  required/>

                                        <option value="">Choose Dispositions</option>
                                        <option value="Connected">Connected</option>
                                        <option value="Not Connected">Not Connected</option>


                                    </select>
                           
                                </div>
                            </div>

                            <div class="col-xs-4">
                                <div class="input-group">							
                                    
                                    <label>Choose Sub Dispositions</label>
                                    <select id="sub_disposition" name="sub_disposition"  class="form-control" autocomplete='off'  required/>

                                        <option value="">Choose Sub Dispositions</option>
                                        <option value="Customer Disconnected the call">Customer Disconnected the call</option>
                                        <option value="Busy">Busy</option>
                                        <option value="Call Back">Call Back</option>
                                        <option value="DO NOT CALL">DO NOT CALL</option>
                                        <option value="Not Interested">Not Interested</option>
                                        <option value="Order booked">Order booked</option>
                                        <option value="Wrong number">Wrong number</option>
                                        <option value="Call Transferred">Call Transferred</option>
                                        <option value="Right person not available">Right person not available</option>
                                        <option value="Call disconnected">Call disconnected</option>
                                        <option value="Ringing not answering">Ringing not answering</option>
                                        <option value="Number not reachable">Number not reachable</option>


                                    </select>
                           
                                </div>
                            </div>

                            <div class="col-xs-4">
                                <div class="input-group">							
                                    
                                    <label>Choose thirds</label>
                                    <select id="third" name="third"  class="form-control" autocomplete='off'  required/>

                                        <option value="">Choose thirds</option>
                                        <option value="1Connected">1Connected</option>
                                        <option value="11Not Connected">11Not Connected</option>


                                    </select>
                           
                                </div>
                            </div>
                            
                            
                        
                        </div>
                                       

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-5">
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
               
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>Mobile Upload Data</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">

                    <div class="col-md-12">
                    
                            
                                                        

                            
                                                       
                                    
                    <label class="col-sm-2 control-label">Customer Code</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $Customer_Code;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">Cust name</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $Cust_name;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">mobile no1</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $mobile_no1;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">mobile no2</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $mobile_no2;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">address1</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $address1;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">address2</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $address2;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">address3</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $address3;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">city</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $city;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">pincode</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $pincode;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">email id</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $email_id;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">salesman code</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $salesman_code;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">salesman name</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $salesman_name;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">district</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $district;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">dlno1</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $dlno1;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">dlno2</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $dlno2;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">dl expiry date</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $dl_expiry_date;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">pan no</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $pan_no;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">code created on</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $code_created_on;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">customer type</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $customer_type;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">agent code</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $agent_code;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                            
                                                       
                                    
                                    <label class="col-sm-2 control-label">CreateDate</label>
                                    
                                    <div class="col-sm-2">
                                         <div class="input text">
                                              <input value="<?php echo $CreateDate;?>" class="form-control " type="text" readonly>
                                         </div>
                                    </div>
                    </div>



                    </div>
                    <div class="panel-footer"></div>
                </div>
                
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
