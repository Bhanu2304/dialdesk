<?php echo $this->Form->create('Sims',array('action'=>'update','class'=>"form-horizontal row-border")); ?>

<div class="modal-body">
    <div class="panel-body detail">
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Contact No.</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('contactnumber',array('label'=>false,'placeholder'=>'Contact No.','autocomplete'=>'off','required'=>true,'class'=>'form-control','minlength'=>"10",'maxlength'=>"10",'value'=>$sim['Sim']['contactnumber']));?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Process Name</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('processname',array('label'=>false,'placeholder'=>'Process Name','required'=>true,'class'=>'form-control','value'=>$sim['Sim']['processname']));?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Purpose</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('purpose',array('label'=>false,'placeholder'=>'Purpose','autocomplete'=>'off','required'=>true,'class'=>'form-control','value'=>$sim['Sim']['purpose']));?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Service Provider</label>
                            <div class="col-sm-9">
                                
                                <select name="data[Plans][serviceprovider]" required="required" class="form-control"
                                    id="PlansServiceprovider">
                                    <option value="">Service Provider</option>
                                    <option value="Jio"
                                        <?php if($sim['Sim']['serviceprovider']=='Jio') echo 'selected'; ?>>Jio</option>
                                    <option value="Airtel"
                                        <?php if($sim['Sim']['serviceprovider']=='Airtel') echo 'selected'; ?>>Airtel
                                    </option>
                                    <option value="Vodafone Idea"
                                        <?php if($sim['Sim']['serviceprovider']=='Vodafone Idea') echo 'selected'; ?>>
                                        Vodafone Idea</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Recharge Date</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('rechargedate',array('label'=>false,'placeholder'=>'Recharge Date','autocomplete'=>'off','required'=>true,'class'=>'form-control date-picker1','value'=>date_format(date_create($sim['Sim']['rechargedate']),'d-m-Y')));?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Validity</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('validity',array('label'=>false,'placeholder'=>'Validity','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control','value'=>$sim['Sim']['validity']));?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Amount of recharge</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('amountofrecharge',array('label'=>false,'placeholder'=>'Amount of recharge','id'=>'updaterechargedate','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control','value'=>$sim['Sim']['amountofrecharge']));?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date of Alert</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->input('dateofalert',array('label'=>false,'placeholder'=>'Date of Alert','id'=>'updatealert','autocomplete'=>'off','required'=>true,'class'=>'form-control date-picker1','value'=>date_format(date_create($sim['Sim']['dateofalert']),'d-m-Y')));?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status of SIM</label>
                            <div class="col-sm-9">
                                
                                <select name="data[Sims][statusofsim]" required="required" class="form-control"
                                    id="SimsStatusofsim">
                                    <option value="">Status</option>
                                    <option value="ACTIVE"
                                        <?php if($sim['Sim']['statusofsim']=='ACTIVE') echo 'selected'; ?>>ACTIVE
                                    </option>
                                    <option value="INACTIVE"
                                        <?php if($sim['Sim']['statusofsim']=='INACTIVE') echo 'selected'; ?>>INACTIVE
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
    <button class="btn-web btn">Update</button>

    <input type="hidden" value="<?php echo $sim['Sim']['id']; ?>" name="id">
</div>

<?php echo $this->Form->end(); ?>
<script>
    $(function () {
        $(".date-picker1").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true
        });
    });
</script>

