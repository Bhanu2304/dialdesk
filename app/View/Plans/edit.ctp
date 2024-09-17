
<?php echo $this->Form->create('Plans',array('action'=>'update','class'=>"form-horizontal row-border")); ?>

    <div class="modal-body">
        <div class="panel-body detail">
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Plan Name</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('PlanName',array('label'=>false,'value'=>$plan['Plan']['PlanName'],'autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Payment Type</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('PaymentType',array('label'=>false,
                                'options'=>array('Monthly'=>'Monthly','Quarterly'=>'Quarterly','Half Yearly'=>'Half Yearly','Yearly'=>'Yearly'),'value'=>$plan['Plan']['PaymentType'],'required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">One Time Setup Cost</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('OTSetupCost',array('label'=>false,'value'=>$plan['Plan']['OTSetupCost'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Refundable Security Deposit</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('RefSecurityDeposit',array('label'=>false,'value'=>$plan['Plan']['RefSecurityDeposit'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Advance Yearly Rental</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('AdvYearlyRental',array('label'=>false,'value'=>$plan['Plan']['AdvYearlyRental'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Free Value - Mobile & Landline</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('FreeValMobile',array('label'=>false,'value'=>$plan['Plan']['FreeValMobile'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Free Value - Toll Free</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('FreeValueToll',array('label'=>false,'value'=>$plan['Plan']['FreeValueToll'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Transactional SMS (150 Characters)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('SMSCharge',array('label'=>false,'value'=>$plan['Plan']['SMSCharge'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('Email',array('label'=>false,'value'=>$plan['Plan']['Email'],'autocomplete'=>'off','onKeypress'=>'return isDecimalKey(event)','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Inbound Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('InboundCallingCharge',array('label'=>false,'value'=>$plan['Plan']['InboundCallingCharge'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Outbound Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('OutboundCallingCharge',array('label'=>false,'value'=>$plan['Plan']['OutboundCallingCharge'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">VFO Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('VFOCallingMinute',array('label'=>false,'value'=>$plan['Plan']['VFOCallingMinute'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">MISSED CALL Calling Minutes (Pulse 1 MIN)</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('MissedCallingMinute',array('label'=>false,'value'=>$plan['Plan']['MissedCallingMinute'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">CRM ID</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('CRMID',array('label'=>false,'value'=>$plan['Plan']['CRMID'],'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
    <div class="modal-footer">
        <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="btn-web btn">Submit</button>
        <div id="hider" style="display:none"><textarea class="form-control fullscreen" name="dd" style="display: none"></textarea></div>
        <input type="hidden" value="<?php echo $plan['Plan']['id']; ?>" name="id">
    </div>
     
<?php echo $this->Form->end(); ?> 
<script src="<?php echo $this->webroot;?>js/assets/js/application.js"></script>
<script src="<?php echo $this->webroot;?>js/assets/main/formcomponents.js"></script>


