<?php ?>
<script>   
    function validReportMatrix(){
		var all_location_id = document.querySelectorAll('input[name="send_type[]"]:checked');
		var aIds = [];
		for(var x = 0, l = all_location_id.length; x < l;  x++){
			aIds.push(all_location_id[x].value);
		}
                var campaign_id=$("#campaign_id").val();
		var user_name=$("#user_name").val();
		var user_designation=$("#user_designation").val();
		var user_mobile=$("#user_mobile").val();
		var user_email=$("#user_email").val();
		var report=$("#report").val();
		var report=$("#report").val();
		var report_type= $("#report_type").val();
		var daywise= $("#daywise").val();
		var hourwise= $("#hourwise").val();
		var monthwise= $("#monthwise").val();
	
		if($.trim(campaign_id) ===""){
			$("#campaign_id").focus();
			$("#erroMsg").html('Please Choose Campaign First.');
			return false;
		}
            else if($.trim(user_name) ===""){
			$("#user_name").focus();
			$("#erroMsg").html('Please enter user name.');
			return false;
		}
                else if(!allLetter(user_name)) {
                        $("#user_name").focus();
			$("#erroMsg").html(letter_err);
                        return false;
                        }
		else if($.trim(user_designation) ===""){
			$("#user_designation").focus();
			$("#erroMsg").html('Please enter user designation.');
			return false;
		}
		else if($.trim(user_mobile) ==="" && $.trim(user_email) ===""){
			$("#erroMsg").html('Please enter mobile no / email id.');
			return false;
		}
		else if($.trim(user_mobile) !="" && !$.trim(user_mobile).match(phoneNum)) {
			$("#user_mobile").focus();
			$("#erroMsg").html(phone_err);
			return false;
		}
                else if($.trim(user_mobile) !="" && $.trim(user_mobile).charAt(0)==="0") {
			$("#user_mobile").focus();
			$("#erroMsg").html(phone_err);
			return false;
		}
		else if ($.trim(user_email) !="" && !filter.test($.trim(user_email))) {
			$("#user_email").focus();
			$("#erroMsg").html(email_err);
			return false;
		}
		else if($.trim(report) ===""){
			$("#report").focus();
			$("#erroMsg").html('Please select report.');
			return false;
		}
		else if($.trim(report_type) ===""){
			$("#report_type").focus();
			$("#erroMsg").html('Please select report type.');
			return false;
		}
		else if(report_type ==='monthly' && monthwise ===""){
			$("#monthwise").focus();
			$("#erroMsg").html('Please select month.');
			return false;
		}
		else if(report_type ==='weekly' && daywise ===""){
			$("#daywise").focus();
			$("#erroMsg").html('Please select Day.');
			return false;
		}
		else if(report_type ==='daily' && hourwise ===""){
			$("#hourwise").focus();
			$("#erroMsg").html('Please select time.');
			return false;
		}
		else if(aIds.length ==0){
			$("#erroMsg").html('Please select send type.');
			return false;
		}
                else if(aIds.length ==1 && aIds[0] ==="sms" && $.trim(user_mobile) ===""){
			$("#erroMsg").html('Please enter mobile no.');
			return false;
		}
		else if(aIds.length ==1 && aIds[0] ==="email" && $.trim(user_email) ===""){
			$("#erroMsg").html('Please enter user email id.');
			return false;
		}
                else if(aIds.length ==2 && $.trim(user_email) ==="" || $.trim(user_mobile) ===""){
			$("#erroMsg").html('Please enter mobile no & email id.');
			return false;
		}
		else{
			return true;
		}
	}
	
	function showReport(type){
		$("#month").hide();
		$("#day").hide();
		$("#hour").hide();
		$("#send_medium1").hide();
		
		if(type.value ==="monthly"){
			$("#month").show();
			$("#send_medium1").show();
		}
		if(type.value ==="weekly"){
			$("#day").show();
			$("#send_medium1").show();
		}
		if(type.value ==="daily"){
			$("#hour").show();
			$("#send_medium1").show();
		}
	}

function checkCharacter(e,t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;
               
            }
            catch (err) {
                alert(err.Description);
            }
   }
</script>


	<ol class="breadcrumb">                            
		<li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
		<li><a href="#">In Call Management</a></li>
		<li class="active"><a href="#">Manage MIS & Reports</a></li>
	</ol>
    <div class="page-heading">
    	<h1>Manage MIS & Reports</h1>
	</div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Manage MIS & Reports</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                <?php echo $this->Form->create('MisAndReportMatrixs',array('action'=>'save_obreport_matrix','class'=>'form-horizontal row-border','onsubmit'=>'return validReportMatrix()')); ?>
    							
                              
                            <div id="erroMsg" style="color:red;font-size:15px;"><?php echo $this->Session->flash();?></div>
                               
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Campaign</label>
                                <div class="col-sm-10">
                                    <?php echo $this->form->input('campaign_id',array('label'=>false,'value'=>isset ($matrixArr['ObReportMatrixMaster']['campaign_id']) ? $matrixArr['ObReportMatrixMaster']['campaign_id'] : "",'id'=>'campaign_id','empty'=>'select','options'=>$campaign_list,'class'=>'form-control'));?>
                                </div>

                                
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">User Name</label>
                                <div class="col-sm-4">
                                    <?php echo $this->form->input('user_name',array('label'=>false,'value'=>isset ($matrixArr['ObReportMatrixMaster']['user_name']) ? $matrixArr['ObReportMatrixMaster']['user_name'] : "",'id'=>'user_name','class'=>'form-control','size'=>'39'));?>
                                </div>

                                <label class="col-sm-2 control-label">Designation</label>
                                <div class="col-sm-4">
                                        <?php echo $this->form->input('user_designation',array('label'=>false,
                                            'value'=>isset ($matrixArr['ObReportMatrixMaster']['user_designation']) ? $matrixArr['ObReportMatrixMaster']['user_designation'] : "",'id'=>'user_designation','class'=>'form-control','size'=>'39'));?>
                                </div>
                            </div>
                                
                                
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Mobile</label>
                                <div class="col-sm-4">
                                    <?php echo $this->form->input('user_mobile',array('label'=>false,'maxlength'=>'10',
                                        'onkeypress'=>'return checkCharacter(event,this)',
                                        'value'=>isset ($matrixArr['ObReportMatrixMaster']['user_mobile']) ? $matrixArr['ObReportMatrixMaster']['user_mobile'] : "",'id'=>'user_mobile','class'=>'form-control','size'=>'39'));?>
                                </div>

                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-4">
                                    <?php echo $this->form->input('user_email',array('label'=>false,
                                        'value'=>isset ($matrixArr['ObReportMatrixMaster']['user_email']) ? $matrixArr['ObReportMatrixMaster']['user_email'] : "",'id'=>'user_email','class'=>'form-control','size'=>'39'));?>
                                </div>

                            </div>
                                
                                 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Report</label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('report', array('label'=>false,
                                        'value'=>isset ($matrixArr['ObReportMatrixMaster']['report']) ? $matrixArr['ObReportMatrixMaster']['report'] : "",'options'=>$report,'empty'=>'Select Report','class'=>'form-control','id'=>'report')); ?> 
                                </div>

                                <label class="col-sm-2 control-label">Report Type</label>
                                <div class="col-sm-4">
                                    <select name="data[report_type]" class="form-control" id="report_type" onchange="return showReport(this);" >
                                        <option	value="">Report Type</option>
                                        <option <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_type']=='monthly'){echo 'selected="selected"';}?> 
                                        value="monthly">Monthly Report</option>
                                        <option <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_type']=='weekly'){echo 'selected="selected"';}?>
                                            value="weekly">Weekly Report</option>
                                        <option <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_type']=='daily'){echo 'selected="selected"';}?> 
                                        value="daily">Daily Report</option>
                                    </select>
                                </div>

                            </div>

                                
                            <div class="form-group" id="month" style="display:none;">
                                <label class="col-sm-2 control-label">Month Wise</label>
                                <div class="col-sm-4">
                                        <?php echo $this->form->input('monthwise',array('label'=>false,
                                            'value'=>isset ($matrixArr) ? $matrixArr['ObReportMatrixMaster']['report_value'] : "",'id'=>'monthwise','class'=>'date-picker form-control','placeholder'=>'Select Month'));?> 
                                </div>
                            </div>
                                
                            <div class="form-group" id="day" style="display:none;">
                                <label class="col-sm-2 control-label">Day Wise</label>
                                <div class="col-sm-4">
                                    <select name="data[daywise]" class="form-control" id="daywise" >
                                        <option value="">Select Day</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='monday'){echo 'selected="selected"';}?> value="monday">Monday</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='tuesday'){echo 'selected="selected"';}?> value="tuesday">Tuesday</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='wednesday'){echo 'selected="selected"';}?> value="wednesday">Wednesday</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='thursday'){echo 'selected="selected"';}?> value="thursday">Thursday</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='friday'){echo 'selected="selected"';}?> value="friday">Friday</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='saturday'){echo 'selected="selected"';}?> value="saturday">Saturday</option>
                                        <option  <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']=='sunday'){echo 'selected="selected"';}?> value="sunday">Sunday</option>
                                    </select>
                                </div>
                            </div>
                              
                               
                                
                            <div class="form-group" id="hour" style="display:none;">
                                <label class="col-sm-2 control-label">Hour Wise</label>
                                <div class="col-sm-4">
                                
                            
                                    <input type="text" name="data[hourwise]" class="form-control timepicker" id="hourwise" placeholder="Select Time" >

                                    <!--
                                    <select name="data[hourwise]" class="form-control" id="hourwise" >
                                        <option value="">Select Time</option>
                                        <?php for($i=1;$i<=24;$i++){?>
                                        <option <?php if(isset($matrixArr) && $matrixArr['ObReportMatrixMaster']['report_value']==$i){echo 'selected="selected"';}?> value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php }?>
                                    </select>
                                                -->
                                </div>
                            </div>
               
                                <br>
                                <div class="form-group" id="send_medium1" style="display:none;">
                                    <label class="col-sm-2 control-label">Send Type</label>
                                    <?php if(isset($matrixArr['ObReportMatrixMaster']['report_value'])){$exp=explode(",",$matrixArr['ObReportMatrixMaster']['send_type']);}?>
                                    <div class="col-sm-8">
                                       
                                        <div class="checkbox checkbox-inline checkbox-black">
                                            <label>
                                                <input type="checkbox" <?php if(isset($matrixArr['ObReportMatrixMaster']['report_value']) && in_array('sms',$exp)){echo "checked='checked'";}?> name="send_type[]"  value="sms" />
                                                SMS
                                            </label>
                                        </div>
                                       
                                        <div class="checkbox checkbox-inline checkbox-black">
                                            <label>
                                                <input type="checkbox" <?php if(isset($matrixArr['ObReportMatrixMaster']['report_value']) && in_array('email',$exp)){echo "checked='checked'";}?> name="send_type[]" value="email" />
                                                Email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        
                        		<?php echo $this->form->hidden('updateid',array('label'=>false,'value'=>isset ($matrixArr['ObReportMatrixMaster']['id']) ? $matrixArr['ObReportMatrixMaster']['id'] : ""));?>
                                
                       			<div class="panel-footer">
									<div class="row">
										<div class="col-sm-8 col-sm-offset-2">
											<input type="submit" class="btn-web btn" name="submit"  <?php if(isset($matrixArr)){?>  value="Update"<?php }else{?>value="Submit" <?php }?> />
										</div>
									</div>
								</div>
							<?php $this->Form->end();?>  
          		</div> 
            </div>

	<div class="row">
        	<div class="col-md-12">      
                <div class="panel panel-default" id="panel-inline">
               		<div class="panel-heading">
                    	<h2>View Report Matrix</h2>
                        <a href="<?php echo $this->webroot;?>MisAndReportMatrixs"><button class="btn btn-midnightblue btn-sm" style="margin-left:5px;">+NEW</button></a>
                       	<div class="panel-ctrls"></div>
                        
                 	</div>
                    <div class="panel-body no-padding">
                 		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable12">
                        <thead>
                        	<tr>
                                <th>S.N</th>
                                <th>Campaign</th>
                                <th>User Name</th>
                                <th>Designation</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Report</th>
                                <th>Report Type</th>
                                <th>Report Time</th>
                                <th>Send Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php  $i = 1;foreach($data as $row){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $campaign_list[$row['ObReportMatrixMaster']['campaign_id']];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['user_name'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['user_designation'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['user_mobile'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['user_email'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['report'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['report_type'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['report_value'];?></td>
                                <td><?php echo $row['ObReportMatrixMaster']['send_type'];?></td>
                                <td>
                                    <?php //echo $this->Html->link('Edit',array('controller'=>'MisAndReportMatrixs','action'=>'index','?'=>array(
                                    //'id'=>$row['ReportMatrixMaster']['id']),'full_base' => true)); ?> <!--|| -->
                                    <?php echo $this->Html->link('Delete',array('controller'=>'MisAndReportMatrixs','action'=>'delete_obreport_matrix','?'=>array(
                                    'id'=>$row['ObReportMatrixMaster']['id']),'full_base' => true),array('onclick'=>"return confirm('Are you sure you want to delete this item?')"));?>
                                </td>    
                             </tr>
                            <?php }?>
                        </tbody>
                    </table><!--end table-->
                   	</div>
              		<div class="panel-footer"></div>
              	</div>
           	</div>
   		</div>


        </div>
    </div> <!-- .container-fluid -->


<?php echo $this->Html->script('WorkFlow/src/wickedpicker'); ?>
<link rel="stylesheet" href="<?php echo $this->webroot; ?>js/WorkFlow/stylesheets/wickedpicker.css">
<script type="text/javascript">
    $('.timepicker').wickedpicker({now: '00:00', twentyFour: true, title:'My Timepicker', showSeconds: false
    });
</script>
<script type="text/javascript">
    $('.timepicker1').wickedpicker({now: '23:59', twentyFour: true, title:'My Timepicker', showSeconds: false
    });
</script>


