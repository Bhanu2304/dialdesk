<script>
    $(function () {




        $('.decimal').keypress(function (e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });

        function hasDecimalPlace(value, x) {
            var pointIndex = value.indexOf('.');
            return pointIndex >= 0 && pointIndex < value.length - x;
        }
    });
</script>

<script>
    function createClientPlan() {
        $('#clientplan_form').attr('action', '<?php echo $this->webroot;?>AdminPlans');
        $("#clientplan_form").submit();
    }

    function checkCharacter(e, t) {
        try {

            if (window.event) {
                var charCode = window.event.keyCode;
            } else if (e) {
                var charCode = e.which;
            } else {
                return true;
            }
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        } catch (err) {
            alert(err.Description);
        }


    }

    function decimal_check(t, val) {
        val = val.toFixed(2);
        t.value = val;
    }
</script>
<script>
    function getPeriod(value) {
        var strx = ''
        var cvalue = document.getElementById('Balance').value;
        if (value == 'YEAR') {
            document.getElementById('CreditValue').value = document.getElementById('Balance').value;
        } else if (value == 'MONTH') {
            document.getElementById('CreditValue').value = Math.round(document.getElementById('Balance').value / 12);
        } else if (value == 'Quater') {
            document.getElementById('CreditValue').value = Math.round(document.getElementById('Balance').value / 4);
        } else if (value == 'Half') {
            document.getElementById('CreditValue').value = Math.round(document.getElementById('Balance').value / 2);
        }

    }

    function getPaymentDetDisp(value) {
        var html = '';
        if (value == 'Percent') {
            html = '<label class="col-sm-3 control-label">Percentage(%) / Transaction</label>';
        } else if (value == 'INR') {
            html = '<label class="col-sm-3 control-label">Rs. / Transaction</label>';
        } else {
            var html = '<label class="col-sm-3 control-label">(Rs./Percent (%))/Transaction</label>';
        }
        document.getElementById('PaymentDetDisp').innerHTML = html;
    }


    // Basant code start from here

    // Day Shift

    function get_rate_per_pulse_day_shift(value) {

        var InboundCallCharge = document.getElementById('InboundCallCharge').value;

        if (value == '1') {
            document.getElementById('rate_per_pulse_day_shift').value = (InboundCallCharge / 60) * 1;
        } else if (value == '15') {
            document.getElementById('rate_per_pulse_day_shift').value = (InboundCallCharge / 60) * 15;
        } else if (value == '30') {
            document.getElementById('rate_per_pulse_day_shift').value = (InboundCallCharge / 60) * 30;
        } else if (value == '45') {
            document.getElementById('rate_per_pulse_day_shift').value = (InboundCallCharge / 60) * 45;
        } else if (value == '60') {
            document.getElementById('rate_per_pulse_day_shift').value = (InboundCallCharge / 60) * 60;
        }



    }


    // Night Shift

    function get_rate_per_pulse_night_shift(value) {

        var InboundCallChargeNight = document.getElementById('InboundCallChargeNight').value;

        if (value == '1') {
            document.getElementById('rate_per_pulse_night_shift').value = (InboundCallChargeNight / 60) * 1;
        } else if (value == '15') {
            document.getElementById('rate_per_pulse_night_shift').value = (InboundCallChargeNight / 60) * 15;
        } else if (value == '30') {
            document.getElementById('rate_per_pulse_night_shift').value = (InboundCallChargeNight / 60) * 30;
        } else if (value == '45') {
            document.getElementById('rate_per_pulse_night_shift').value = (InboundCallChargeNight / 60) * 45;
        } else if (value == '60') {
            document.getElementById('rate_per_pulse_night_shift').value = (InboundCallChargeNight / 60) * 60;
        }



    }


    // Outbound Call Charge

    function get_rate_per_pulse_outbound_call_shift(value) {

        var OutboundCallCharge = document.getElementById('OutboundCallCharge').value;

        if (value == '1') {
            document.getElementById('rate_per_pulse_outbound_call_shift').value = (OutboundCallCharge / 60) * 1;
        } else if (value == '15') {
            document.getElementById('rate_per_pulse_outbound_call_shift').value = (OutboundCallCharge / 60) * 15;
        } else if (value == '30') {
            document.getElementById('rate_per_pulse_outbound_call_shift').value = (OutboundCallCharge / 60) * 30;
        } else if (value == '45') {
            document.getElementById('rate_per_pulse_outbound_call_shift').value = (OutboundCallCharge / 60) * 45;
        } else if (value == '60') {
            document.getElementById('rate_per_pulse_outbound_call_shift').value = (OutboundCallCharge / 60) * 60;
        }



    }

    function show_flat(value) {
        if (value == 'Yes') {
            document.getElementById('flatDisp').style.display = "block";
        } else {
            document.getElementById('flatDisp').style.display = "none";
        }
    }

    // Basant code end here
</script>



<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a>Plan Master</a></li>
    <li class="active"><a href="#">Plan Master</a></li>
</ol>

<div class="container-fluid">
    <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
        <div class="panel-heading">
            <h2>Plan Creation</h2>
        </div>
        <div class="panel-body" style="margin-top:-10px;">
            <font style="color:green;"><?php echo $this->Session->flash(); ?></font>

            <?php echo $this->Form->create('AdminPlans',array('action'=>'client_wise_plan_creation')); ?>
            <div class="col-md-12">


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Plan Name </span>
                        <input type="text" name="PlanName" placeholder="Plan Name" required=""
                            class="form-control extclass">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Setup Fee</span>
                        <input type="text" name="SetupCost" placeholder="Setup Cost" required=""
                            onkeypress="return checkCharacter(event,this)"
                            onkeyup="return decimal_check(this,this.value)" class="form-control extclass decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Subscription Amount </span>
                        <input type="text" id="RentalAmount" name="RentalAmount" placeholder="Subscription Amount"
                            onkeypress="return checkCharacter(event,this)" required=""
                            class="form-control extclass decimal">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Credit value</span>
                        <input type="text" id="Balance" name="Balance" placeholder="Credit value"
                            onkeypress="return checkCharacter(event,this)" required=""
                            class="form-control extclass decimal">
                    </div>
                </div>
            </div>


            <div class="col-md-12">


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Plan Mode</span>
                        <select id="PeriodType" name="PeriodType" onchange="getPeriod(this.value)" required=""
                            class="form-control">
                            <option value="">Period Type</option>
                            <option value="YEAR">YEAR</option>
                            <option value="MONTH">MONTH</option>
                            <option value="Quater">Quarter</option>
                            <option value="Half">Half</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Credit Value as per Plan Mode</span>

                        <input type="text" id="CreditValue" name="CreditValue"
                            placeholder="Credit Value as per Plan Mode" onkeypress="return checkCharacter(event,this)"
                            required="" class="form-control extclass decimal">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Inbound Call Charge(Day Shift)</span>
                        <input type="text" id="InboundCallCharge" name="InboundCallCharge"
                            placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required=""
                            class="form-control decimal">
                    </div>
                </div>

                <!-- Basant Code Start From here ---->

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Pulse(Day Shift)</span>
                        <select id="pulse_day_shift" name="pulse_day_shift"
                            onchange="get_rate_per_pulse_day_shift(this.value)" required="" class="form-control">
                            <option value="">Select</option>
                            <option value="1">1</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Rate Per Pulse(Day Shift)</span>
                        <input type="text" id="rate_per_pulse_day_shift" readonly name="rate_per_pulse_day_shift"
                            placeholder="Rate Per Pulse" onkeypress="return checkCharacter(event,this)" required=""
                            class="form-control decimal">
                    </div>
                </div>



                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Inbound Call Charge(Night Shift)</span>
                        <input type="text" id="InboundCallChargeNight" name="InboundCallChargeNight"
                            placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required=""
                            class="form-control decimal">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Pulse(Night Shift)</span>
                        <select id="pulse_night_shift" name="pulse_night_shift"
                            onchange="get_rate_per_pulse_night_shift(this.value)" required="" class="form-control">
                            <option value="">Select</option>
                            <option value="1">1</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Rate Per Pulse(Night Shift)</span>
                        <input type="text" id="rate_per_pulse_night_shift" readonly name="rate_per_pulse_night_shift"
                            placeholder="Rate Per Pulse" onkeypress="return checkCharacter(event,this)" required=""
                            class="form-control decimal">
                    </div>
                </div>







            </div>


            <div class="col-md-12">



                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Outbound Call Charge</span>
                        <input type="text" id="OutboundCallCharge" name="OutboundCallCharge"
                            placeholder="Outbound Call Charge" onkeypress="return checkCharacter(event,this)"
                            required="" class="form-control decimal">
                    </div>
                </div>



                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Pulse</span>
                        <select id="pulse_outbound_call_shift" name="pulse_outbound_call_shift"
                            onchange="get_rate_per_pulse_outbound_call_shift(this.value)" required=""
                            class="form-control">
                            <option value="">Select</option>
                            <option value="1">1</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Rate Per Pulse</span>
                        <input type="text" id="rate_per_pulse_outbound_call_shift" readonly
                            name="rate_per_pulse_outbound_call_shift" placeholder="Rate Per Pulse"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>






                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>SMS Char 160</span>
                        <input type="text" id="SMSCharge" name="SMSCharge" placeholder="SMS Charge"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Email Charge</span>
                        <input type="text" id="EmailCharge" name="EmailCharge" placeholder="Per Email Charge"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>VFO Call Charge</span>
                        <input type="text" id="VFOCallCharge" name="VFOCallCharge" placeholder="VFO Rs./Min"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Miss Call Charge</span>
                        <input type="text" id="MissCallCharge" name="MissCallCharge" placeholder="Miss Call Rs./Min"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span style="font-style:italic;">IVR Call Charge</span>
                        <div class="form-group is-empty"><input type="text" id="IVR_Charge" name="IVR_Charge"
                                placeholder="IVR Call Rs./CALL" onkeypress="return checkCharacter(event,this)"
                                required="" class="form-control decimal"><span class="material-input"></span></div>
                    </div>
                </div>



            </div>



            <div class="col-md-12">



                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span style="font-style:italic;">First Minute</span>
                        <div class="form-group is-empty">

                            <span>Enable</span> <input type="radio" id="first_minute" value="Enable"
                                name="first_minute"><span class="material-input"></span>

                            <span>Disable</span> <input type="radio" id="first_minute" value="Disable"
                                name="first_minute"><span class="material-input"></span>


                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>No of Users</span>
                        <input type="text" id="NoOfFreeUser" name="NoOfFreeUser" placeholder="No. Of Free User"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Charge Per Extra User</span>
                        <input type="text" id="ChargePerExtraUser" name="ChargePerExtraUser"
                            placeholder="Charge For Extra User Rs./User" onkeypress="return checkCharacter(event,this)"
                            required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Balance Carry Forward</span>
                        <select id="TransferAfterRental" onChange="show_flat(this.value);" name="TransferAfterRental"
                            required="" class="form-control">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="flatDisp" style="display:none">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Flat %</span>

                        <input type="text" id="Flat" name="Flat" placeholder="% Used to Balance Carry Forward"
                            onkeypress="return checkCharacter(event,this)" class="form-control decimal">
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Multi Language (Inbound Charge)</span>
                        <input type="text" id="MultiIBCharges" name="MultiIBCharges" placeholder="Multi Language (Inbound Charge)"
                            onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Multi Language (Outbound Charge)</span>
                        <input type="text" id="MultiOBCharges" name="MultiOBCharges"
                            placeholder="Multi Language (Outbound Charge)" onkeypress="return checkCharacter(event,this)"
                            required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Multi Language (Live Chat)</span>
                        <input type="text" id="MultiLiveChat" name="MultiLiveChat"
                            placeholder="Multi Language (Live Chat)" onkeypress="return checkCharacter(event,this)"
                            required="" class="form-control decimal">
                    </div>
                </div>

                <div class="col-md-3">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <input type="submit" value="Submit" class="btn-web btn" style="margin-top: 26px;">
                    </div>
                </div>
            </div>

               
        

            </div>
            <?php  echo $this->Form->end(); ?>

        </div>
    </div>


</div>

</div>
