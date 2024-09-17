<?php  
echo $this->Html->script('ecr');
echo $this->Html->script('assets/main/dialdesk');
?>
<style>
    #tab1{display: none;}
    #tab2{display: none;}
    #tab3{display: none;}
    #tab4{display: none;}
</style>
<script>
     $(document).ready(function(){ 
    <?php if(isset($tab) && $tab !=""){?>
         document.getElementById("<?php echo $tab;?>").style.display="block";
    <?php }?>
});

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

function IsAlphaNumeric(e)
{
        var key;
        if(window.event)
                key = window.event.keyCode;     //IE
    else
                key = e.which;                  //Firefox
     if ((key>47 && key<58) || (key>64 && key<91) || (key>96 && key<123) || key == 8 || key ==0)
     {
                return true;
     }
     else
     {
                return false;
     }
}

 function addCaptureField() {
    var cp  =$('#captureField').val();
    var smt =$('#smsTextArea').val();
    if(smt !=""){
        smt=smt+'';
    }

    var result=[];
    for(var i=0;i<cp.length;i++){
       result.push(":"+cp[i]+":");
    }                                  
     document.getElementById('smsTextArea').value =smt+result; 
}
function addEcrField() {
    var cp =$('#ecrField').val();
    var smt =$('#smsTextArea').val();
    if(smt !=""){
        smt=smt+'';
    }
    
    var result=[];
    for(var i=0;i<cp.length;i++){
       result.push(":"+cp[i]+":");  
    }
    document.getElementById('smsTextArea').value =smt+result; 
}
function removeSmsText(){
    document.getElementById('smsTextArea').value = "";
}
function get_smsHeader(val)
{
    if(val=='' || val=='email')
    {
       document.getElementById("smsHeader").innerHTML=''; 
    }
    else
    {
        var ht = '<label class="col-sm-2 control-label">Sms Header</label>'+'<div class="col-sm-2">';
        ht +='<input name="data[Escalations][smsHeader]" class="form-control" placeholder="SMS Header" required="required" id="EscalationsSmsHeader" type="text">'
        ht +='</div>';
        document.getElementById("smsHeader").innerHTML=ht;
                                                    
                                                        
                                                     
    }
}
</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">In Call Management</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Ecrs">Manage Alerts & Escalations</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Manage Alerts & Escalations</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
            <div class=" col-md-12"> 
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2></h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Alerts</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab1"   >                                      
                                        <div class="panel panel-default"   data-widget='{"draggable": "false"}'>   
                                            <?php if(isset($tab) && $tab ==="tab1"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('Escalations',array('action'=>'save_alert_esclation','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
                                                <?php echo $this->Form->hidden('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert')); ?>
                                                <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab1')); ?>
                                                <div class="form-group"> 
                                                     <label class="col-sm-2 control-label">Scenario</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/getEcr','a')",'required'=>true)); ?>
                                                    </div>
                                                    <div id="type"></div>
                                                    <div id="subtype"></div>
                                                    <div id="subtype1"></div>
                                                    <div id="subtype2"></div>
                                                   
                                                </div> 
                                                 <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <hr>
                                                    </div>
                                                 </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Name</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('personName',array('label'=>false,'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Designation</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Email</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email','required'=>true)); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">

                                                    <label class="col-sm-2 control-label">Mobile No.</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('mobileNo',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','placeholder'=>'Mobile No.','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Alert On</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('alertOn',array('label'=>false,'class'=>'form-control','options'=>array('sms'=>'SMS','email'=>'Email','both'=>'Both'),'empty'=>'Select','onChange'=>'get_smsHeader(this.value)','required'=>true)); ?>
                                                    </div>
                                                    <!--
                                                    <div id='smsHeader'></div>
                                                    -->
                                                </div>
                                                <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                                    <div class="col-sm-2">
                                                        <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                    </div>
                                                </div>
                                            <?php echo $this->Form->end();?>                  
                                        </div>

                                        <?php if(!empty($data1)){?>
                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline">
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Name</th>
                                                            <th>Alert Type</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                            <th>Alert On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($data1 as $d): $id = $d['Matrix']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $d['Matrix']['personName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['alertType']; ?></td>
                                                                    <td><?php echo $d['Matrix']['categoryName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['typeName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['Matrix']['mobileNo']; ?></td>
                                                                    <td><?php echo $d['Matrix']['email']; ?></td>
                                                                    <td><?php echo $d['Matrix']['alertOn']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab1')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Escalations/delete_matrix?id=<?php echo $id;?>&tab=tab1')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

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
                        
                        <script>
                            function view_edit_alert_esclation(id,type){
                                $.post("<?php echo $this->webroot;?>Escalations/view_edit_data",{id:id,type:type},function(data){
                                    $("#ae-data").html(data);
                                }); 
                            }
                        </script>
                        
                        <!-- Edit Login Popup -->
                        <!--
                        <div class="modal fade" id="esclationUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" style="top:250px;" >
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Edit Alert & Esclation</h4>
                                    </div>
                                     <div id="ae-data" ></div> 
                                </div>
                            </div>
                        </div>
                        -->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define SMS To Caller</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab3"  >                 
                                         <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                        <?php if(isset($tab) && $tab ==="tab3"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('Escalations',array('action'=>'save_customer_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                                                                       
                                            <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab3')); ?>
                                           
                                        <div class="form-group">
                                            
                                            
                                                <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert','type'=>'hidden')); ?>
                                            
                                            <label class="col-sm-2 control-label"><b>Scenario</b></label>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/getEcr','s')",'required'=>true)); ?>
                                            </div>
                                            <div id="stype"></div>
                                            <div id="ssubtype"></div>
                                             <div id="ssubtype1"></div>
                                            <div id="ssubtype2"></div>
                                        </div>
        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Sender ID</label>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('senderID',array('label'=>false,'pattern'=>'.{6,6}','maxlength'=>'6','onkeypress'=>'return  IsAlphaNumeric(event)','class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                                            </div>
                                            <label class="col-sm-2 control-label">SMS Text</label>
                                            <div class="col-sm-6">
                                                <?php echo $this->Form->textArea('smsText',array('label'=>false,'class'=>'form-control','placeholder'=>'Validated SMS Text Otherwise message will be failed','required'=>true)); ?>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                            <div class="col-sm-2">
                                                <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                            </div>
                                        </div>
                                        <?php echo $this->Form->end(); ?>
                                        </div>  
                                                
                                        <?php if(!empty($data3)){?>
                                            
                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline1">
                                            
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                  
                                                    
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Alert Type</th>
                                                                <th>Scenario</th>
                                                                <th>Sub Scenario 1</th>
                                                                <th>Sub Scenario 2</th>
                                                                <th>Sub Scenario 3</th>
                                                                <th>Sub Scenario 4</th>
                                                                <th>senderID</th>
                                                                <th>smsText</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $i =1; foreach($data3 as $d): ?>
                                                                <tr >
                                                                    <td><?php echo $i++; $id = $d['SMSText']['id']; ?></td>                                                               
                                                                    <td><?php echo $d['SMSText']['alertType']; ?></td>
                                                                     <td><?php echo $d['SMSText']['categoryName']; ?></td>
                                                                    <td><?php echo $d['SMSText']['typeName']; ?></td>
                                                                    <td><?php echo $d['SMSText']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['SMSText']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['SMSText']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['SMSText']['senderID']; ?></td>
                                                                    <td><?php echo $d['SMSText']['smsText']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab3')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Escalations/delete_sms?id=<?php echo $id;?>&tab=tab3')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

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
                        
   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Internal Communications</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab4" >                 
                                             <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                            <?php if(isset($tab) && $tab ==="tab4"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('Escalations',array('action'=>'save_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                                                                       
                                            <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab4')); ?>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><b>Alert Type</b></label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','options'=>array('Alert'=>'Alert','Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label"><b>Scenario</b></label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/getEcr','c')",'required'=>true)); ?>
                                                </div>
                                                <div id="ctype"></div>
                                                <div id="csubtype"></div>
                                                <div id="csubtype1"></div>
                                                <div id="csubtype2"></div>
                                            </div>
                                 
                                              <!--  
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Sender ID</label>
                                                <div class="col-sm-2">
                                                    <?php //echo $this->Form->input('senderID',array('label'=>false,'pattern'=>'.{6,6}','maxlength'=>'6','onkeypress'=>'return  IsAlphaNumeric(event)','class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                                                </div>
                                            </div>
                                             -->
                                            <div class="form-group">                                             
                                                <label class="col-sm-2 control-label">Add Fields</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('capturefield',array('label'=>false,'options'=>$field_send1,'value'=>'','multiple'=>'multiple',"ng-model"=>"select",'style'=>'height: 125px','id'=>'captureField','class'=>'form-control'));?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button onclick="addCaptureField();"  type="button"  class="btn-web btn">Add+</button>
                                                    <button onclick="removeSmsText();" type="button"  class="btn-web btn">Clear </button>                    
                                                </div>
                                               
                                            </div>
                                                
                                            <div class="form-group">                                             
                                                <label class="col-sm-2 control-label">Add Fields</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('ecrfields',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 125px','id'=>'ecrField','class'=>'form-control'));?>
        
                                                </div>
                                                <div class="col-sm-2">
                                                    <button onclick="addEcrField();" type="button"  class="btn-web btn">Add+</button>
                                                    <button onclick="removeSmsText();" type="button"  class="btn-web btn">Clear </button>
                                                </div>
                                            </div>
                                         
                                            <div class="form-group">                                             
                                                <label class="col-sm-2 control-label">SMS Text</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->textArea('smsText',array('label'=>false,'class'=>'form-control','id'=>'smsTextArea','placeholder'=>'Validated SMS Text Otherwise message will be failed','readonly'=>false,'required'=>true)); ?>
                                             
                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                                <div class="col-sm-2">
                                                    <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                </div>
                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>
                                            
                                            
                                            <?php if(!empty($data4)){?>
                                            
                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline1">
                                            
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                  
                                                    
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Alert Type</th>
                                                                <th>Scenario</th>
                                                                <th>Sub Scenario 1</th>
                                                                <th>Sub Scenario 2</th>
                                                                <th>Sub Scenario 3</th>
                                                                <th>Sub Scenario 4</th>
                                                               <!-- <th>senderID</th>-->
                                                                <th>smsText</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $i =1; foreach($data4 as $d): ?>
                                                                <tr >
                                                                    <td><?php echo $i++; $id = $d['SMSText']['id']; ?></td>                                                               
                                                                    <td><?php echo $d['SMSText']['alertType']; ?></td>
                                                                     <td><?php echo $d['SMSText']['categoryName']; ?></td>
                                                                    <td><?php echo $d['SMSText']['typeName']; ?></td>
                                                                    <td><?php echo $d['SMSText']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['SMSText']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['SMSText']['subtype2Name']; ?></td>
                                                                   <!-- <td><?php //echo $d['SMSText']['senderID']; ?></td>-->
                                                                    <td><?php echo $d['SMSText']['smsText']; ?></td>
                                                                    <td >
                                                                        <!--
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab4')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        -->
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Escalations/delete_sms?id=<?php echo $id;?>&tab=tab4')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

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
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Esclation Matrix</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab2"  >                            
                                        <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                            <?php if(isset($tab) && $tab ==="tab2"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('Escalations',array('action'=>'save_alert_esclation','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
                                                <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab2')); ?>
                                                <div class="form-group"> 
                                                    <label class="col-sm-2 control-label">Alert Type</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','options'=>array('Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Scenario</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/getEcr','e')",'required'=>true)); ?>
                                                    </div>
                                                    <div id="etype"></div>
                                                    <div id="esubtype"></div>
                                                    <div id="esubtype1"></div>
                                                    <div id="esubtype2"></div>                                             
                                                </div>
                     
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <hr>
                                                    </div>
                                                 </div>
                                                
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">TAT</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('tat',array('label'=>false,'class'=>'form-control','placeholder'=>'TAT','required'=>true)); ?>
                                                </div>
                                                
                                                <label class="col-sm-2 control-label">Name</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('personName',array('label'=>false,'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Designation</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Mobile No.</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('mobileNo',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','placeholder'=>'Mobile No.','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Alert On</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('alertOn',array('label'=>false,'class'=>'form-control','options'=>array('sms'=>'SMS','email'=>'Email','both'=>'Both'),'empty'=>'Select','required'=>true)); ?>
                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                                <div class="col-sm-2">
                                                    <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                </div>
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                        </div>  
                                                
                                      
                                               <?php if(!empty($data2)){?>
                                            
                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline1">
                                            
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                  
                                                    
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Name</th>
                                                            <th>Alert Type</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                            <th>Alert On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($data2 as $d): $id = $d['Matrix']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $d['Matrix']['personName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['alertType']; ?></td>
                                                                    <td><?php echo $d['Matrix']['categoryName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['typeName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['Matrix']['mobileNo']; ?></td>
                                                                    <td><?php echo $d['Matrix']['email']; ?></td>
                                                                    <td><?php echo $d['Matrix']['alertOn']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab2')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Escalations/delete_matrix?id=<?php echo $id;?>&tab=tab2')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

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


                    </div>
                </div>
            </div>
        </div>  
        


<!-- Edit Aleart -->
<!--
<div id="ae-data" ></div> 
-->


        
        
    </div>
</div>


<!-- Edit Login Popup -->
<div class="modal fade" id="esclationUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;width:750px;" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Alert & Esclation</h4>
            </div>
             <div id="ae-data" ></div> 
        </div>
    </div>
</div>