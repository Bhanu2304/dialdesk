<script>
function validUpdateReportMatrix(){
	var fld = document.getElementById('user_id');
	var values = [];
	for (var i = 0; i < fld.options.length; i++) {
  		if (fld.options[i].selected) {
    	values.push(fld.options[i].value);
  		}
	}

	var report=$("#report").val();
	var type1= $("#type1").val();
	var type2= $("#type2").val();
	var daywise= $("#daywise").val();
	var hourwise= $("#hourwise").val();
	var rt=[];
	var radioButtons = document.getElementsByName("report_type");
    for (var x = 0; x < radioButtons.length; x ++) {
      if (radioButtons[x].checked) {
	   rt.push(radioButtons[x].value);
     }
     }
			
	if(values.length ==0){
		$("#user_id").focus();
		$("#erroMsg").html('Please select user.');
		return false;
	}
	else if($.trim(report) ===""){
		$("#report").focus();
		$("#erroMsg").html('Please select report.');
		return false;
	}
	else  if(rt.length ==0){
		$("#erroMsg").html('Please select report type.');
		return false;
	}
	else if(rt[0] ==='weekly' && daywise ===""){
		$("#daywise").focus();
		$("#erroMsg").html('Please select Day.');
		return false;
	}
	else if(rt[0] ==='daily' && hourwise ===""){
		$("#hourwise").focus();
		$("#erroMsg").html('Please select time.');
		return false;
	}
	else{
		return true;	
	}
}

function showWeekly(){
	$("#daywise").show();
	$("#hourwise").hide();
}
function showDaily(){
	$("#hourwise").show();
	$("#daywise").hide();
}
</script>

<div id="wrapper">
	<div id="register">
  		<?php echo $this->Form->create('MisAndReportMatrixs',array('action'=>'update_report_matrix','onsubmit'=>'return validUpdateReportMatrix()')); ?>
      		<h1> Update Report Matrix </h1>
            <div id="erroMsg"></div> 
          	<table>
            	<tr>
                	<td>
                    	<?php echo $this->form->label('Select User');?><br/>
                   		<select style="height:100px;width:217px;" name='user_id[]' id="user_id" multiple>
                        	<?php foreach($user as $row){?>
                            <?php $exp=explode(",",$get_report_matrix['ReportMatrixMaster']['user_id']);?>
							<option <?php if(in_array($row['LogincreationMaster']['id'],$exp)){echo 'selected="selected"';} ?> 
                            value="<?php echo $row['LogincreationMaster']['id'] ?>"><?php echo $row['LogincreationMaster']['name'] ?></option>
                            <?php }?>
                        </select>  
                    </td>
                </tr>
          		<tr>
            		<td>
            			<?php echo $this->form->label('Report');?><br/>
                        <select name="report" id="report">
                        	<option value="">Select Report Type</option>
							<option <?php if($get_report_matrix['ReportMatrixMaster']['report']=="Date wise calling MIS") echo 'selected="selected"'; ?> 
                            value="Date wise calling MIS">Date wise calling MIS</option>
                            <option  <?php if($get_report_matrix['ReportMatrixMaster']['report']=="Category wise MIS") echo 'selected="selected"'; ?>
                            value="Category wise MIS">Category wise MIS</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report']=="Type / Subtype MIS") echo 'selected="selected"'; ?>
                            value="Type / Subtype MIS">Type / Subtype MIS</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report']=="SR Status wise MIS") echo 'selected="selected"'; ?>
                            value="SR Status wise MIS">SR Status wise MIS</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report']=="Escalation based MIS") echo 'selected="selected"'; ?>
                            value="Escalation based MIS">Escalation based MIS</option>
                        </select> 
            		</td>
            	</tr>
                <tr>
                	<td>
                    	<?php echo $this->form->label('Report Type');?><br/>
                        <input type="radio" name="report_type" id="type1" onclick="showWeekly()" 
						<?php if($get_report_matrix['ReportMatrixMaster']['report_type']=="weekly") echo 'checked="checked"'; ?> value="weekly" >Weekly Report
                        <input type="radio" name="report_type" id="type2"  onclick="showDaily()"
                        <?php if($get_report_matrix['ReportMatrixMaster']['report_type']=="daily") echo 'checked="checked"'; ?> value="daily" >Daily Report
                    </td>
                </tr>
                
                
                <tr>
            		<td>
                    	<select name="daywise" id="daywise" <?php if($get_report_matrix['ReportMatrixMaster']['report_type']==='weekly'){}else{?> style="display:none"<?php }?> >
                        	<option value="">Select Day</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="monday") echo 'selected="selected"'; ?> value="monday">Monday</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="tuesday") echo 'selected="selected"'; ?> value="tuesday">Tuesday</option>
                          <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="wednesday") echo 'selected="selected"'; ?> value="wednesday">Wednesday</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="thursday") echo 'selected="selected"'; ?> value="thursday">Thursday</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="friday") echo 'selected="selected"'; ?> value="friday">Friday</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="saturday") echo 'selected="selected"'; ?> value="saturday">Saturday</option>
                            <option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']=="sunday") echo 'selected="selected"'; ?> value="sunday">Sunday</option>
                        </select>
            		
            		</td>
            	</tr>
				<tr>
            		<td>
                    	<select name="hourwise" id="hourwise" <?php if($get_report_matrix['ReportMatrixMaster']['report_type']==='daily'){}else{?> style="display:none"<?php }?> >
                        	<option value="">Select Time</option>
                            <?php for($i=1;$i<=24;$i++){?>
                            	<option <?php if($get_report_matrix['ReportMatrixMaster']['report_value']==$i) echo 'selected="selected"'; ?>
                                value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php }?>
                        </select>
            		</td>
            	</tr>
            </table>
            <input type="hidden" id="hid_rep_type"  value="<?php echo $get_report_matrix['ReportMatrixMaster']['report_type'];?>"  />
            <input type="hidden" name="id"  value="<?php echo $get_report_matrix['ReportMatrixMaster']['id'];?>" />
            <p class="signin button"> 
				<input type="submit" value="Submit" /> 
			</p>	        
		<?php $this->Form->end();?>
	</div>
</div>


