<?php if(isset($type) && $type ==="tab1"){?>
<script>
    $(document).ready(function(){
        <?php if($data1['type'] !=""){?>
            getType("<?php echo $data1['category'].'@@'.$data1['categoryName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ua","<?php if($data1['typeName'] ==="All"){echo 'All@@'.$data1['typeName'];}else{ echo $data1['type'].'@@'.$data1['typeName'];}?>");
        <?php }?>
        <?php if($data1['subtype'] !=""){?>
            getSubType("<?php echo $data1['type'].'@@'.$data1['typeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ua","<?php if($data1['subtypeName'] ==="All"){echo 'All@@'.$data1['subtypeName'];}else{echo $data1['subtype'].'@@'.$data1['subtypeName'];}?>");
        <?php }?>
        <?php if($data1['subtype1'] !=""){?>
            getSubType1("<?php echo $data1['subtype'].'@@'.$data1['subtypeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ua","<?php if($data1['subtype1Name'] ==="All"){echo 'All@@'.$data1['subtype1Name'];}else{echo $data1['subtype1'].'@@'.$data1['subtype1Name'];}?>");
        <?php }?>
        <?php if($data1['subtype2'] !=""){?>
            getSubType2("<?php echo $data1['subtype1'].'@@'.$data1['subtype1Name'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ua","<?php if($data1['subtype2Name'] ==="All"){echo 'All@@'.$data1['subtype2Name'];}else{echo $data1['subtype2'].'@@'.$data1['subtype2Name'];}?>");
        <?php }?>
    });
    
function checkDefineAlertEdit(){
    var alertType=$("#EscalationsAlertOnEdit").val();
    var mobil=$("#EscalationsMobileNoEdit").val();
    var email=$("#EscalationsEmailEdit").val();
    
    if(alertType ==="sms" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#EscalationsMobileNoEdit").focus();
        return false;
    }
    else if(alertType ==="email" &&  email ===""){
        alert('Please enter email id.');
        $("#EscalationsEmailEdit").focus();
        return false;
    }
    else if(alertType ==="both" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#EscalationsMobileNoEdit").focus();
        return false;
    }
    else if(alertType ==="both" &&  email ===""){
        alert('Please enter email id.');
        $("#EscalationsEmailEdit").focus();
        return false;
    }
    else{
        return true;
    }  
}
</script>
<?php
if(isset($data1['categoryName']) && $data1['categoryName'] ==="All"){
    $selectScenario= isset($data1['category'])?'All@@'.$data1['categoryName']:"";
}
else{
  $selectScenario= isset($data1['category'])?$data1['category'].'@@'.$data1['categoryName']:"";  
}
?>
    <?php echo $this->Form->create('Escalations',array('action'=>'update_alert_esclation','onsubmit'=>'return checkDefineAlertEdit();','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
        <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab1')); ?>
        <?php echo $this->Form->hidden('id',array('label'=>false,'class'=>'form-control','value'=>isset($data1['id'])?$data1['id']:"",)); ?>
        <?php echo $this->Form->hidden('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert')); ?>        
        <div class="modal-body  ">
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <div class="form-group"> 
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'value'=>$selectScenario,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/get_edit_ecr','ua','')",'required'=>true)); ?>
                            </div>
                            <div id="utype"></div>
                            <div id="usubtype"></div>
                            <div id="usubtype1"></div>
                            <div id="usubtype2"></div>
                        </div>
            
                        <div class="form-group">
                            <div class="col-sm-12"><hr/></div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('personName',array('label'=>false,'value'=>isset($data1['personName'])?$data1['personName']:"",'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Designation</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('designation',array('label'=>false,'value'=>isset($data1['designation'])?$data1['designation']:"",'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                            </div> 
                            <label class="col-sm-2 control-label">Alert On</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('alertOn',array('label'=>false,'id'=>'EscalationsAlertOnEdit','value'=>isset($data1['alertOn'])?$data1['alertOn']:"",'class'=>'form-control','options'=>array('sms'=>'SMS','email'=>'Email','both'=>'both(sms,email)','Whatsapp'=>'Whatsapp'),'empty'=>'Select','required'=>true)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Mobile No.</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('mobileNo',array('label'=>false,'id'=>'EscalationsMobileNoEdit','value'=>isset($data1['mobileNo'])?$data1['mobileNo']:"",'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','placeholder'=>'Mobile No.','required'=>false)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('email',array('type'=>'textarea','label'=>false,'id'=>'EscalationsEmailEdit','value'=>isset($data1['email'])?$data1['email']:"",'class'=>'form-control','placeholder'=>'Email','required'=>false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" value="Submit" class="btn-web btn">
        </div>
    <?php echo $this->Form->end(); ?>     
<?php } ?>

<?php if(isset($type) && $type ==="tab2"){?>

<script>
    $(document).ready(function(){
        <?php if($data1['type'] !=""){?>
            getType("<?php echo $data1['category'].'@@'.$data1['categoryName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ue","<?php if($data1['typeName'] ==="All"){echo 'All@@'.$data1['typeName'];}else{echo $data1['type'].'@@'.$data1['typeName'];}?>");
        <?php }?>
        <?php if($data1['subtype'] !=""){?>
            getSubType("<?php echo $data1['type'].'@@'.$data1['typeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ue","<?php if($data1['subtypeName'] ==="All"){echo 'All@@'.$data1['subtypeName'];}else{echo $data1['subtype'].'@@'.$data1['subtypeName'];}?>");
        <?php }?>
        <?php if($data1['subtype1'] !=""){?>
            getSubType1("<?php echo $data1['subtype'].'@@'.$data1['subtypeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ue","<?php if($data1['subtype1Name'] ==="All"){echo 'All@@'.$data1['subtype1Name'];}else{echo $data1['subtype1'].'@@'.$data1['subtype1Name'];}?>");
        <?php }?>
        <?php if($data1['subtype2'] !=""){?>
            getSubType2("<?php echo $data1['subtype1'].'@@'.$data1['subtype1Name'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","ue","<?php if($data1['subtype2Name'] ==="All"){echo 'All@@'.$data1['subtype2Name'];}else{echo $data1['subtype2'].'@@'.$data1['subtype2Name'];}?>");
        <?php }?>
    });
    
    function checkDefineEsclationEdit(){
    var alertType=$("#EscalationsAlertOnTab2Edit").val();
    var mobil=$("#EscalationsMobileNoTab2Edit").val();
    var email=$("#EscalationsEmailTab2Edit").val();
    
    if(alertType ==="sms" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#EscalationsMobileNoTab2Edit").focus();
        return false;
    }
    else if(alertType ==="email" &&  email ===""){
        alert('Please enter email id.');
        $("#EscalationsEmailTab2Edit").focus();
        return false;
    }
    else if(alertType ==="both" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#EscalationsMobileNoTab2Edit").focus();
        return false;
    }
    else if(alertType ==="both" &&  email ===""){
        alert('Please enter email id.');
        $("#EscalationsEmailTab2Edit").focus();
        return false;
    }
    else{
        return true;
    }  
}
</script>

<?php
if(isset($data1['categoryName']) && $data1['categoryName'] ==="All"){
    $selectScenario= isset($data1['category'])?'All@@'.$data1['categoryName']:"";
}
else{
  $selectScenario= isset($data1['category'])?$data1['category'].'@@'.$data1['categoryName']:"";  
}
?>

    <?php echo $this->Form->create('Escalations',array('action'=>'update_alert_esclation','onsubmit'=>'return checkDefineEsclationEdit();','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
        <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab2')); ?>
        <?php echo $this->Form->hidden('id',array('label'=>false,'class'=>'form-control','value'=>isset($data1['id'])?$data1['id']:"",)); ?>

        <div class="modal-body">
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                 
                        
                        <div class="form-group"> 
                            <label class="col-sm-2 control-label">Alert Type</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','value'=>isset($data1['alertType'])?$data1['alertType']:"",'options'=>array('Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                            </div>
                             <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'value'=>$selectScenario,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/get_edit_ecr','ue')",'required'=>true)); ?>
                            </div>
                            <div id="uetype"></div>
                            <div id="uesubtype"></div>
                            <div id="uesubtype1"></div>
                            <div id="uesubtype2"></div>
                        </div>
                        <div class="form-group">
                            
                            <hr/>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tat</label>
                            <div class="col-sm-4">
                               <?php echo $this->Form->input('tat',array('label'=>false,'value'=>isset($data1['tat'])?$data1['tat']:"",'class'=>'form-control','placeholder'=>'TAT','required'=>true)); ?>
                            </div>
                             <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('personName',array('label'=>false,'value'=>isset($data1['personName'])?$data1['personName']:"",'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                            </div>
                             <label class="col-sm-2 control-label">Designation</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('designation',array('label'=>false,'value'=>isset($data1['designation'])?$data1['designation']:"",'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Alert On</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('alertOn',array('label'=>false,'id'=>'EscalationsAlertOnTab2Edit','value'=>isset($data1['alertOn'])?$data1['alertOn']:"",'class'=>'form-control','options'=>array('sms'=>'SMS','email'=>'Email','both'=>'both(sms,email)','Whatsapp'=>'Whatsapp'),'empty'=>'Select','required'=>true)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Mobile No.</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('mobileNo',array('label'=>false,'id'=>'EscalationsMobileNoTab2Edit','value'=>isset($data1['mobileNo'])?$data1['mobileNo']:"",'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','placeholder'=>'Mobile No.','required'=>false)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('email',array('label'=>false,'id'=>'EscalationsEmailTab2Edit','value'=>isset($data1['email'])?$data1['email']:"",'class'=>'form-control','placeholder'=>'Email','required'=>false)); ?>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" value="Submit" class="btn-web btn">
        </div>
    <?php echo $this->Form->end(); ?>     
<?php } ?>

<?php if(isset($type) && $type ==="tab3"){?>

<script>
    $(document).ready(function(){
        <?php if($data2['type'] !=""){?>
            getType("<?php echo $data2['category'].'@@'.$data1['categoryName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","us","<?php if($data2['typeName'] ==="All"){echo 'All@@'.$data2['typeName'];}else{echo $data2['type'].'@@'.$data2['typeName'];}?>");
        <?php }?>
        <?php if($data2['subtype'] !=""){?>
            getSubType("<?php echo $data2['type'].'@@'.$data1['typeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","us","<?php if($data2['subtypeName'] ==="All"){echo 'All@@'.$data2['subtypeName'];}else{echo $data2['subtype'].'@@'.$data2['subtypeName'];}?>");
        <?php }?>
        <?php if($data2['subtype1'] !=""){?>
            getSubType1("<?php echo $data2['subtype'].'@@'.$data1['subtypeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","us","<?php if($data2['subtype1Name'] ==="All"){echo 'All@@'.$data2['subtype1Name'];}else{echo $data2['subtype1'].'@@'.$data2['subtype1Name'];}?>");
        <?php }?>
        <?php if($data2['subtype2'] !=""){?>
            getSubType2("<?php echo $data2['subtype1'].'@@'.$data1['subtype1Name'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","us","<?php if($data2['subtype2Name'] ==="All"){echo 'All@@'.$data2['subtype2Name'];}else{echo $data2['subtype2'].'@@'.$data2['subtype2Name'];}?>");
        <?php }?>
    });
</script>

<?php
if(isset($data2['categoryName']) && $data2['categoryName'] ==="All"){
    $selectScenario= isset($data2['category'])?'All@@'.$data2['categoryName']:"";
}
else{
  $selectScenario= isset($data2['category'])?$data2['category'].'@@'.$data2['categoryName']:"";  
}
?>
    <?php echo $this->Form->create('Escalations',array('action'=>'update_customer_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
        <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab3')); ?>
        <?php echo $this->Form->hidden('id',array('label'=>false,'class'=>'form-control','value'=>isset($data2['id'])?$data2['id']:"",)); ?>

        <div class="modal-body">
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><b>Alert Type</b></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('alertType',array('label'=>false,'value'=>isset($data2['alertType'])?$data2['alertType']:"",'class'=>'form-control','options'=>array('Alert'=>'Alert','Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','value'=>$selectScenario,'options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/get_edit_ecr','us')",'required'=>true)); ?>
                            </div>
                            <div id="ustype"></div>
                           <div id="ussubtype"></div>
                           <div id="ussubtype1"></div>
                           <div id="ussubtype2"></div>
                        </div>
                     
                        <div class="form-group">
                            <div class="col-sm-12">
                            <hr/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Sender ID</label>
                            <div class="col-sm-5">
                                <?php echo $this->Form->input('senderID',array('label'=>false,'value'=>isset($data2['senderID'])?$data2['senderID']:"",'maxlength'=>'6','onkeypress'=>'return  IsAlphaNumeric(event)','class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">SMS Text</label>
                            <div class="col-sm-5">
                                <?php echo $this->Form->textArea('smsText',array('label'=>false,'value'=>isset($data2['smsText'])?$data2['smsText']:"",'class'=>'form-control','placeholder'=>'Validated SMS Text Otherwise message will be failed','required'=>true)); ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" value="Submit" class="btn-web btn">
        </div>
    <?php echo $this->Form->end(); ?>     
<?php } ?>

<?php if(isset($type) && $type ==="tab4"){?>

<script>
    $(document).ready(function(){
        <?php if($data3['type'] !=""){?>
            getType("<?php echo $data3['category'].'@@'.$data3['categoryName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","uc","<?php if($data3['typeName'] ==="All"){echo 'All@@'.$data3['typeName'];}else{echo $data3['type'].'@@'.$data3['typeName'];}?>");
        <?php }?>
        <?php if($data3['subtype'] !=""){?>
            getSubType("<?php echo $data3['type'].'@@'.$data3['typeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","uc","<?php if($data3['subtypeName'] ==="All"){echo 'All@@'.$data3['subtypeName'];}else{echo $data3['subtype'].'@@'.$data3['subtypeName'];}?>");
        <?php }?>
        <?php if($data3['subtype1'] !=""){?>
            getSubType1("<?php echo $data3['subtype'].'@@'.$data3['subtypeName'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","uc","<?php if($data3['subtype1Name'] ==="All"){echo 'All@@'.$data3['subtype1Name'];}else{echo $data3['subtype1'].'@@'.$data3['subtype1Name'];}?>");
        <?php }?>
        <?php if($data3['subtype2'] !=""){?>
            getSubType2("<?php echo $data3['subtype1'].'@@'.$data3['subtype1Name'];?>","<?php echo $this->webroot.'escalations/get_edit_ecr';?>","uc","<?php if($data3['subtype2Name'] ==="All"){echo 'All@@'.$data3['subtype2Name'];}else{echo $data3['subtype2'].'@@'.$data3['subtype2Name'];}?>");
        <?php }?>
    });
    
    
 function editCaptureField() {
    var cp  =$('#captureField1').val();
    var smt =$('#smsTextArea1').val();
    if(smt !=""){
        smt=smt+',';
    }
  
    var result=[];
    for(var i=0;i<cp.length;i++){
       result.push(cp[i]);
    }                                  
     document.getElementById('smsTextArea1').value =smt+result; 
}
function editEcrField() {
    var cp =$('#ecrField1').val();
    var smt =$('#smsTextArea1').val();
    if(smt !=""){
        smt=smt+',';
    }
    
    var result=[];
    for(var i=0;i<cp.length;i++){
       result.push(cp[i]);  
    }
    document.getElementById('smsTextArea1').value =smt+result; 
}
function editremoveSmsText(){
    document.getElementById('smsTextArea1').value = "";
}
</script>

    <?php
if(isset($data3['categoryName']) && $data3['categoryName'] ==="All"){
    $selectScenario= isset($data3['category'])?'All@@'.$data3['categoryName']:"";
}
else{
  $selectScenario= isset($data3['category'])?$data3['category'].'@@'.$data3['categoryName']:"";  
}
?>

    <?php echo $this->Form->create('Escalations',array('action'=>'update_customer_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
        <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab4')); ?>
        <?php echo $this->Form->hidden('id',array('label'=>false,'class'=>'form-control','value'=>isset($data3['id'])?$data3['id']:"",)); ?>
        <div class="modal-body">
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><b>Alert Type</b></label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('alertType',array('label'=>false,'value'=>isset($data3['alertType'])?$data3['alertType']:"",'class'=>'form-control','options'=>array('Alert'=>'Alert','Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                            </div>
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','value'=>$selectScenario,'options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/get_edit_ecr','uc')",'required'=>true)); ?>
                            </div>
                        </div>
                        
                        <div class="form-group"> 
                            
                        </div>
                        
                        <div class="form-group">
                           <div id="uctype"></div>
                           <div id="ucsubtype"></div>
                           <div id="ucsubtype1"></div>
                           <div id="ucsubtype2"></div>
                        </div>
                     
                    
                        <!--
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Sender ID</label>
                            <div class="col-sm-6">
                                <?php //echo $this->Form->input('senderID',array('label'=>false,'pattern'=>'.{6,6}','value'=>isset($data3['senderID'])?$data3['senderID']:"",'maxlength'=>'6','onkeypress'=>'return  IsAlphaNumeric(event)','class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                            </div>
                        </div>
                        -->
                        <div class="form-group">                                             
                            <label class="col-sm-2 control-label">Add Fields</label>
                            <div class="col-sm-6">
                                <?php echo $this->Form->input('capturefield',array('label'=>false,'options'=>$field_send1,'multiple'=>'multiple',"ng-model"=>"select",'style'=>'height: 125px','id'=>'captureField1','class'=>'form-control'));?>
                            </div>
                            <div class="col-sm-2">
                                <button onclick="editCaptureField();"  type="button"  class="btn-web btn">Add+</button>
                                <button onclick="editremoveSmsText();" type="button"  class="btn-web btn">Clear </button>                    
                            </div>

                        </div>

                        <div class="form-group">                                             
                            <label class="col-sm-2 control-label">Add Fields</label>
                            <div class="col-sm-6">
                                <?php echo $this->Form->input('ecrfields',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 125px','id'=>'ecrField1','class'=>'form-control'));?>

                            </div>
                            <div class="col-sm-2">
                                <button onclick="editEcrField();" type="button"  class="btn-web btn">Add+</button>
                                <button onclick="editremoveSmsText();" type="button"  class="btn-web btn">Clear </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">SMS Text</label>
                            <div class="col-sm-6">
                                <?php echo $this->Form->textArea('smsText',array('label'=>false,'value'=>isset($data3['smsText'])?$data3['smsText']:"",'id'=>'smsTextArea1','class'=>'form-control','placeholder'=>'Validated SMS Text Otherwise message will be failed','required'=>true)); ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" value="Submit" class="btn-web btn">
        </div>
    <?php echo $this->Form->end(); ?>     
<?php } ?>
