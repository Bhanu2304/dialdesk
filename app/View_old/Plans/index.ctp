<script>
    function isDecimalKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	
	if ((charCode> 31 && (charCode < 48 || charCode > 57)) && charCode!=46)
		return false;
	
	return true;
}
function view_edit_plans(id)
{                    
        $.post("<?php echo $this->webroot;?>Plans/edit",{id:id},function(data){
            $("#fields-data").html(data);
    }); 
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Client Activation</a></li>
    <li class="active"><a href="#">Plan</a></li>
</ol>
<div class="page-heading">                                           
<h1>Plan</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Create New Plan</h2>
                <h2> <?php echo $this->Session->flash(); ?></h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('Plans',array("class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Plan Name</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('PlanName',array('label'=>false,'placeholder'=>'Plan Name','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Payment Type</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('PaymentType',array('label'=>false,
                                'options'=>array('Monthly'=>'Monthly','Quarterly'=>'Quarterly','Half Yearly'=>'Half Yearly','Yearly'=>'Yearly'),'empty'=>'Select Plan','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">One Time Setup Cost</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('OTSetupCost',array('label'=>false,'placeholder'=>'One Time Setup Cost','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Refundable Security Deposit</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('RefSecurityDeposit',array('label'=>false,'placeholder'=>'Refundable Security Deposit','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Advance Yearly Rental</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('AdvYearlyRental',array('label'=>false,'placeholder'=>'Advance Yearly Rental','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Free Value - Mobile & Landline</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('FreeValMobile',array('label'=>false,'placeholder'=>'Free Value - Mobile & Landline','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Free Value - Toll Free</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('FreeValueToll',array('label'=>false,'placeholder'=>'Free Value - Mobile & Landline','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Transactional SMS (150 Characters)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('SMSCharge',array('label'=>false,'placeholder'=>'Transactional SMS','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('Email',array('label'=>false,'placeholder'=>'Email','autocomplete'=>'off','onKeypress'=>'return isDecimalKey(event)','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Inbound Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('InboundCallingCharge',array('label'=>false,'placeholder'=>'Inbound Calling Minutes (Pulse 1 MIN)','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Outbound Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('OutboundCallingCharge',array('label'=>false,'placeholder'=>'Outbound Calling Minutes (Pulse 1 MIN)','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">VFO Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('VFOCallingMinute',array('label'=>false,'placeholder'=>'VFO Calling Minutes (Pulse 1 MIN)','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">MISSED CALL Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('MissedCallingMinute',array('label'=>false,'placeholder'=>'MISSED CALL Calling Minutes (Pulse 1 MIN)','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">CRM ID</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('CRMID',array('label'=>false,'placeholder'=>'CRM ID','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="col-sm-2"></div><div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="ADD" >
                          <div id="hider" style="display:none"><textarea class="form-control fullscreen" name="dd" style="display: none"></textarea></div>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <?php if(!empty($data)){?>
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>View Plan Details</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div style="color:green;margin-left:20px;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <div class="scrolling">
                <table class="table table-striped table-bordered datatables dataTable no-footer">
                    <tr>
                        <th>SrNo.</th>
                        <th>Plan Name</th>
                        <th>Payment Type</th>
                        <th>One Time Setup Cost</th>
                        <th>Refundable Security Deposit</th>
                        <th>Yearly Advance Rental</th>
                        <th>Free Value - Mobile & Landline</th>
                        <th>Free Value - Toll Free</th>
                        <th>Transactional Charge</th>
                        <th>EMAIL</th>
                        <th>Inbound Calling Minutes (Pulse 1 MIN)- Landline & Mobile</th>
                        <th>Outbound Calling Minutes (Pulse 1 MIN)</th>
                        <th>VFO Calling Minutes (Pulse 1 MIN)</th>
                        <th>MISSED CALL Calling Minutes (Pulse 1 MIN)</th>
                        <th>CRM ID</th>
                        <th>Action</th>
                    </tr>
                    <?php $i=1;
                            foreach($data as $d):
                                echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$d['Plan']['PlanName']."</td>";
                                    echo "<td>".$d['Plan']['PaymentType']."</td>";
                                    echo "<td>".$d['Plan']['OTSetupCost']."</td>";
                                    echo "<td>".$d['Plan']['RefSecurityDeposit']."</td>";
                                    echo "<td>".$d['Plan']['AdvYearlyRental']."</td>";
                                    echo "<td>".$d['Plan']['FreeValMobile']."</td>";
                                    echo "<td>".$d['Plan']['FreeValueToll']."</td>";
                                    echo "<td>".$d['Plan']['SMSCharge']."</td>";
                                    echo "<td>".$d['Plan']['Email']."</td>";
                                    echo "<td>".$d['Plan']['InboundCallingCharge']."</td>";
                                    echo "<td>".$d['Plan']['OutboundCallingCharge']."</td>";
                                    echo "<td>".$d['Plan']['VFOCallingMinute']."</td>";
                                    echo "<td>".$d['Plan']['MissedCallingMinute']."</td>";
                                    echo "<td>".$d['Plan']['CRMID']."</td>";
                                    ?>
                    <td><a  href="#" class="btn-raised" data-toggle="modal" data-target="#fieldsUpdate" onclick="view_edit_plans('<?php echo $d['Plan']['id'];?>')" >
                            <label class="btn btn-xs btn-midnightblue btn-raised">
                                <i class="fa fa-edit"></i><div class="ripple-container"></div>
                            </label>
                        </a> 
                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Plans/delete_plan?id=<?php echo $d['Plan']['id'];?>')" >
                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                        </a>
                    </td>
                                    <?php
                                echo "</tr>";
                            endforeach;
                    ?>
                </table>
                <div>
        </div> 
        
        <?php }?>
    </div>
</div>

<!-- Edit Capture Fields -->
<div class="modal fade" id="fieldsUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Fields</h2>
            </div>
            <div id="fields-data"></div>
        </div>
    </div>
</div>

<?php 
echo $this->Html->script('assets/main/formcomponents');
echo $this->Html->script('assets/main/dialdesk');
?>