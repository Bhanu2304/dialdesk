<style>
    .nav-tabs-to-dropdown>li.active>a,
    .nav-tabs-to-dropdown>li.active>a:hover,
    .nav-tabs-to-dropdown>li.active>a:focus {
        /* color: #9e9e9e;
    background-color: #000!important;
    border: 1px solid #eeeeee;
    border-bottom-color: transparent; */
        /* cursor: default; */
    }

    .nav-pills>li.active>a,
    .nav-pills>li.active>a:hover,
    .nav-pills>li.active>a:focus {
        /* color: #9e9e9e; */
        background-color: #37474f !important;
    }

    .nav-tabs-to-dropdown {
        width: 12% !important;
    }

    .nav>li>a:focus {
        padding: 14px 18px !important;
        color: #9e9e9e;
        background-color: #37474f !important;
        border: 1px solid #eeeeee;
        border-bottom-color: transparent;
        /* cursor: default; */
    }
</style>
<script>
    function tabs(div_name, tabName) {

        $(div_name).removeClass('tab-pane fade active in');

        // document.getElementById(tabName).style.display = "block";
        $('#pills-' + tabName + '-tab').trigger('click');
        // document.getElementById(tabName).className = "tab-pane fade active in";

    }
</script>
<script>
    $(document).ready(function () {

        $('input[name=first_minute]').attr("disabled", true);

    });

    function enable_dialdesk() {
        if ($('#dialdeskcheck').is(":checked")) {
            $("#InboundCallCharge").removeAttr("disabled");
            $("#pulse_day_shift").removeAttr("disabled");
            $("#InboundCallChargeNight").removeAttr("disabled");
            $("#pulse_night_shift").removeAttr("disabled");
            $("#InboundCallCharge").removeAttr("disabled");
            $("#OutboundCallCharge").removeAttr("disabled");
            $("#pulse_outbound_call_shift").removeAttr("disabled");
            $("#SMSCharge").removeAttr("disabled");
            $("#EmailCharge").removeAttr("disabled");
            $("#VFOCallCharge").removeAttr("disabled");
            $("#MissCallCharge").removeAttr("disabled");
            $("#IVR_Charge").removeAttr("disabled");
            $("#NoOfFreeUser").removeAttr("disabled");
            $("#ChargePerExtraUser").removeAttr("disabled");
            $('input[name=first_minute]').removeAttr("disabled");

        } else {
            $("#InboundCallCharge").val("").attr("disabled", true);
            $("#pulse_day_shift").val("").attr("disabled", true);
            $("#InboundCallChargeNight").val("").attr("disabled", true);
            $("#pulse_night_shift").val("").attr("disabled", true);
            $("#InboundCallCharge").val("").attr("disabled", true);
            $("#OutboundCallCharge").val("").attr("disabled", true);
            $("#pulse_outbound_call_shift").val("").attr("disabled", true);
            $("#SMSCharge").val("").attr("disabled", true);
            $("#EmailCharge").val("").attr("disabled", true);
            $("#VFOCallCharge").val("").attr("disabled", true);
            $("#MissCallCharge").val("").attr("disabled", true);
            $("#IVR_Charge").val("").attr("disabled", true);
            $("#NoOfFreeUser").val("").attr("disabled", true);
            $("#ChargePerExtraUser").val("").attr("disabled", true);
            $("#rate_per_pulse_day_shift").val("");
            $("#rate_per_pulse_night_shift").val("");
            $("#rate_per_pulse_outbound_call_shift").val("");
            $('input[name=first_minute]').val("").attr("disabled", true);

        }

    }

    function enable_dialdee() {
        if ($('#dialdeecheck').is(":checked")) {
            $("#QuarterlyRental").removeAttr("disabled");
            $("#FreeSessions").removeAttr("disabled");
            $("#InboundWhatsappCharge").removeAttr("disabled");
            $("#OutboundWhatsappCharge").removeAttr("disabled");

        } else {
            $("#QuarterlyRental").val("").attr("disabled", true);
            $("#FreeSessions").val("").attr("disabled", true);
            $("#InboundWhatsappCharge").val("").attr("disabled", true);
            $("#OutboundWhatsappCharge").val("").attr("disabled", true);

        }
    }

    function enable_vfo() {
        if ($('#vfocheck').is(":checked")) {
            $("#VfoQuarterlyRental").removeAttr("disabled");
            $("#FreeMinutes").removeAttr("disabled");
            $("#CallForwardingCharges").removeAttr("disabled");
            $("#Pulse").removeAttr("disabled");

        } else {
            $("#VfoQuarterlyRental").val("").attr("disabled", true);
            $("#FreeMinutes").val("").attr("disabled", true);
            $("#CallForwardingCharges").val("").attr("disabled", true);
            $("#Pulse").val("").attr("disabled", true);

        }
    }

    function enable_leadsquare() {
        if ($('#leadsquarecheck').is(":checked")) {
            $("#OrderStartingDate").removeAttr("disabled");
            $("#OrderEndDate").removeAttr("disabled");
            $("#NextDueDate").removeAttr("disabled");

        } else {
            $("#OrderStartingDate").val("").attr("disabled", true);
            $("#OrderEndDate").val("").attr("disabled", true);
            $("#NextDueDate").val("").attr("disabled", true);

        }
    }
</script>
<script>
    var req = "This field is required";
    var reqs = "This Tab All fields Required";

    function dialdesk_validation() {
        $("#space").hide();
        $("#err").hide();
        $('#space').remove();
        $('#err').remove();

        if ($.trim($("#InboundCallCharge").val()) === "") {
            show_error('InboundCallCharge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#pulse_day_shift").val()) === "") {
            show_error('pulse_day_shift', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#InboundCallChargeNight").val()) === "") {
            show_error('InboundCallChargeNight', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#pulse_night_shift").val()) === "") {
            show_error('pulse_night_shift', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#OutboundCallCharge").val()) === "") {
            show_error('OutboundCallCharge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#pulse_outbound_call_shift").val()) === "") {
            show_error('pulse_outbound_call_shift', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#SMSCharge").val()) === "") {
            show_error('SMSCharge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#EmailCharge").val()) === "") {
            show_error('EmailCharge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#VFOCallCharge").val()) === "") {
            show_error('VFOCallCharge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#MissCallCharge").val()) === "") {
            show_error('MissCallCharge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#IVR_Charge").val()) === "") {
            show_error('IVR_Charge', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#NoOfFreeUser").val()) === "") {
            show_error('NoOfFreeUser', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else if ($.trim($("#ChargePerExtraUser").val()) === "") {
            show_error('ChargePerExtraUser', req);
            show_error('dialdeskcheck', reqs);
            return false;
        } else {
            return true;
        }
    }

    function dialdee_validation() {
        $("#space").hide();
        $("#err").hide();
        $('#space').remove();
        $('#err').remove();

        if ($.trim($("#QuarterlyRental").val()) === "") {
            show_error('QuarterlyRental', req);
            show_error('dialdeecheck', reqs);
            return false;
        } else if ($.trim($("#FreeSessions").val()) === "") {
            show_error('FreeSessions', req);
            show_error('dialdeecheck', reqs);
            return false;
        } else if ($.trim($("#InboundWhatsappCharge").val()) === "") {
            show_error('InboundWhatsappCharge', req);
            show_error('dialdeecheck', reqs);
            return false;
        } else if ($.trim($("#OutboundWhatsappCharge").val()) === "") {
            show_error('OutboundWhatsappCharge', req);
            show_error('dialdeecheck', reqs);
            return false;
        } else {
            return true;
        }

    }

    function vfo_validation() {
        $("#space").hide();
        $("#err").hide();
        $('#space').remove();
        $('#err').remove();

        if ($.trim($("#VfoQuarterlyRental").val()) === "") {
            show_error('VfoQuarterlyRental', req);
            show_error('vfocheck', reqs);
            return false;
        } else if ($.trim($("#FreeMinutes").val()) === "") {
            show_error('FreeMinutes', req);
            show_error('vfocheck', reqs);
            return false;
        } else if ($.trim($("#CallForwardingCharges").val()) === "") {
            show_error('CallForwardingCharges', req);
            show_error('vfocheck', reqs);
            return false;
        } else if ($.trim($("#Pulse").val()) === "") {
            show_error('Pulse', req);
            show_error('vfocheck', reqs);
            return false;
        } else {
            return true;
        }
    }

    function leadsquare_validation() {
        $("#space").hide();
        $("#err").hide();
        $('#space').remove();
        $('#err').remove();

        if ($.trim($("#OrderStartingDate").val()) === "") {
            show_error('OrderStartingDate', req);
            show_error('leadsquarecheck', reqs);
            return false;
        } else if ($.trim($("#OrderEndDate").val()) === "") {
            show_error('OrderEndDate', req);
            show_error('leadsquarecheck', reqs);
            return false;
        } else if ($.trim($("#NextDueDate").val()) === "") {
            show_error('NextDueDate', req);
            show_error('leadsquarecheck', reqs);
            return false;
        } else {
            return true;
        }
    }

    function show_error(data_id, msg) {
        $("#" + data_id).focus();
        $("#" + data_id).after("<br/ id='space'><span id='err' style='color:red;'>" + msg + "</span>");

        setTimeout(function () {
            $('#err').fadeOut("slow");
        }, 2000);
    }
</script>
<script>
    function plan_validation() {
        $("#space").hide();
        $("#err").hide();
        $('#space').remove();
        $('#err').remove();
        var req = "This Tab All fields required";
        var selected = new Array();
        $("input[type=checkbox]:checked").each(function () {
            //console.log($(this).val());
            selected.push($(this).attr("name"));

        });
        let dial = '';
        let dee = '';
        let vfo = '';
        let lead = '';
        $.each(selected, function (key, val) {

            if (val == 'dialdeskcheck') {
                dial += dialdesk_validation();

            } else if (val == 'dialdeecheck') {
                dee += dialdee_validation();

            } else if (val == 'vfocheck') {
                vfo += vfo_validation();

            } else if (val == 'leadsquarecheck') {
                lead += leadsquare_validation();
            }

        });

        if (dial == 'false' || dee == 'false' || vfo == 'false' || lead == 'false') {

            return false;
        } else if (dial == '' && dee == '' && vfo == '' && lead == '') {

            show_error('leadsquarecheck', 'Please Select Any Plan.');
            return false;
        }




    }
</script>
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


</script>



<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a>Plan Tool Master</a></li>
    <li class="active"><a href="#">Plan Tool Master</a></li>
</ol>

<div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
    <div class="panel-heading">
        <h2>Tool Plan Edit</h2>
    </div>
    <?php echo $this->Form->create('AdminPlans',array('action'=>'client_wise_plan_update','onsubmit'=>'return plan_validation();')); ?>
    <div class="panel-body" style="margin-top:-10px;">
        <div class="panel-heading">
            <div class="col-md-12">


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Plan Name </span>
                        <input type="text" name="PlanName" placeholder="Plan Name" id="plan_name"
                            value="<?php echo $plan['PlanMasters']['PlanName']; ?>" class="form-control extclass"
                            required>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Setup Fee</span>
                        <input type="text" name="SetupCost" placeholder="Setup Cost" id="setupcost"
                            onkeypress="return checkCharacter(event,this)"
                            value="<?php echo $plan['PlanMasters']['SetupCost']; ?>"
                            onkeyup="return decimal_check(this,this.value)" class="form-control extclass decimal"
                            required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Subscription Amount </span>
                        <input type="text" id="subscriptionAmount" name="RentalAmount" placeholder="Subscription Amount"
                            onkeypress="return checkCharacter(event,this)" class="form-control extclass decimal"
                            value="<?php echo $plan['PlanMasters']['RentalAmount']; ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span>Credit value </span>
                        <input type="text" id="Balance" name="Balance" placeholder="Credit value"
                            value="<?php echo $plan['PlanMasters']['Balance']; ?>"
                            onkeypress="return checkCharacter(event,this)" class="form-control extclass decimal"
                            required>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span style="font-style:italic;">Plan Mode</span>
                        <select id="PeriodType" name="PeriodType" onchange="getPeriod(this.value)" required=""
                            class="form-control">
                            <option value="">Period Type</option>
                            <option value="YEAR"
                                <?php if($plan['PlanMasters']['PeriodType']=='YEAR') echo 'selected'; ?>>
                                YEAR
                            </option>
                            <option value="MONTH"
                                <?php if($plan['PlanMasters']['PeriodType']=='MONTH') echo 'selected'; ?>>
                                MONTH
                            </option>
                            <option value="Quater"
                                <?php if($plan['PlanMasters']['PeriodType']=='Quater') echo 'selected'; ?>>
                                Quater
                            </option>
                            <option value="Half"
                                <?php if($plan['PlanMasters']['PeriodType']=='Half') echo 'selected'; ?>>
                                Half
                            </option>
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
                            value="<?php echo $plan['PlanMasters']['CreditValue']; ?>"
                            class="form-control extclass decimal" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <span style="font-style:italic;">Balance Carry Forward</span>
                        <select id="TransferAfterRental" name="TransferAfterRental" required="" class="form-control">
                            <option>Select</option>
                            <option value="Yes"
                                <?php if($plan['PlanMasters']['TransferAfterRental']=='Yes') echo 'selected'; ?>>
                                Yes
                            </option>
                            <option value="No"
                                <?php if($plan['PlanMasters']['TransferAfterRental']=='No') echo 'selected'; ?>>
                                No
                            </option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br><br>
    <div class="panel-body" style="margin-top:-10px;">
        <div class="tabs-to-dropdown">
            <ul class="nav nav-pills d-none d-md-flex" id="pills-tab" role="tablist">
                <li class="nav-item active" role="presentation">
                    <a class="nav-link" id="pills-dialdesk-tab" data-toggle="tab" href="#dialdesk" role="tab"
                        aria-controls="pills-dialdesk" aria-selected="true">Dialdesk</a>
                </li>
                <li class="nav-item " role="presentation">
                    <a class="nav-link" id="pills-dialdee-tab" data-toggle="tab" href="#dialdee" role="tab"
                        aria-controls="pills-dialdee" aria-selected="false">Dialdee</a>
                </li>
                <li class="nav-item <?php if($plan['PlanMasters']['PlanCreate']=='Vfo') echo 'active'; ?>"
                    role="presentation">
                    <a class="nav-link" id="pills-vfo-tab" data-toggle="tab" href="#vfo" role="tab"
                        aria-controls="pills-vfo" aria-selected="false">Vfo</a>
                </li>
                <li class="nav-item <?php if($plan['PlanMasters']['PlanCreate']=='Leadsquared') echo 'active'; ?>"
                    role="presentation">
                    <a class="nav-link" id="pills-leadsquared-tab" data-toggle="tab" href="#leadsquare" role="tab"
                        aria-controls="pills-leadsquared" aria-selected="false">Leadsquared</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- dialdesk tab -->
                <div id="dialdesk" class="tab-pane fade in active">
                    <div class="container-fluid">
                        <h2>Dialdesk Plan Edit</h2>
                        <input type="checkbox" name="dialdeskcheck" id="dialdeskcheck" style="margin-left: 25px;"
                            onclick="enable_dialdesk();">

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Inbound Call Charge(Day Shift)</span>
                                    <input type="text" id="InboundCallCharge" name="InboundCallCharge"
                                        value="<?php echo $plan['PlanMasters']['InboundCallCharge']; ?>"
                                        placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)"
                                        class="form-control decimal" disabled>
                                </div>
                            </div>



                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Pulse(Day Shift)</span>
                                    <select id="pulse_day_shift" name="pulse_day_shift"
                                        onchange="get_rate_per_pulse_day_shift(this.value)" required=""
                                        class="form-control" disabled>
                                        <option value="">Select</option>
                                        <option value="1"
                                            <?php if($plan['PlanMasters']['pulse_day_shift']=='1') echo 'selected'; ?>>
                                            1</option>
                                        <option value="15"
                                            <?php if($plan['PlanMasters']['pulse_day_shift']=='15') echo 'selected'; ?>>
                                            15
                                        </option>
                                        <option value="30"
                                            <?php if($plan['PlanMasters']['pulse_day_shift']=='30') echo 'selected'; ?>>
                                            30
                                        </option>
                                        <option value="45"
                                            <?php if($plan['PlanMasters']['pulse_day_shift']=='45') echo 'selected'; ?>>
                                            45
                                        </option>
                                        <option value="60"
                                            <?php if($plan['PlanMasters']['pulse_day_shift']=='60') echo 'selected'; ?>>
                                            60
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Rate Per Pulse(Day Shift)</span>
                                    <input type="text" id="rate_per_pulse_day_shift" readonly
                                        name="rate_per_pulse_day_shift"
                                        value="<?php echo $plan['PlanMasters']['rate_per_pulse_day_shift']; ?>"
                                        onkeypress="return checkCharacter(event,this)" required=""
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
                                        value="<?php echo $plan['PlanMasters']['InboundCallChargeNight']; ?>"
                                        onkeypress="return checkCharacter(event,this)" required=""
                                        class="form-control decimal" disabled>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Pulse(Night Shift)</span>
                                    <select id="pulse_night_shift" name="pulse_night_shift"
                                        onchange="get_rate_per_pulse_night_shift(this.value)" required=""
                                        class="form-control" disabled>
                                        <option value="">Select</option>
                                        <option value="1"
                                            <?php if($plan['PlanMasters']['pulse_night_shift']=='1') echo 'selected'; ?>>
                                            1
                                        </option>
                                        <option value="15"
                                            <?php if($plan['PlanMasters']['pulse_night_shift']=='15') echo 'selected'; ?>>
                                            15
                                        </option>
                                        <option value="30"
                                            <?php if($plan['PlanMasters']['pulse_night_shift']=='30') echo 'selected'; ?>>
                                            30
                                        </option>
                                        <option value="45"
                                            <?php if($plan['PlanMasters']['pulse_night_shift']=='45') echo 'selected'; ?>>
                                            45
                                        </option>
                                        <option value="60"
                                            <?php if($plan['PlanMasters']['pulse_night_shift']=='60') echo 'selected'; ?>>
                                            60
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Rate Per Pulse(Night Shift)</span>
                                    <input type="text" id="rate_per_pulse_night_shift" readonly
                                        name="rate_per_pulse_night_shift"
                                        value="<?php echo $plan['PlanMasters']['rate_per_pulse_night_shift']; ?>"
                                        onkeypress="return checkCharacter(event,this)" required=""
                                        class="form-control decimal">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Outbound Call Charge</span>
                                    <input type="text" id="OutboundCallCharge" name="OutboundCallCharge"
                                        placeholder="Outbound Call Charge"
                                        value="<?php echo $plan['PlanMasters']['OutboundCallCharge']; ?>"
                                        onkeypress="return checkCharacter(event,this)" class="form-control decimal"
                                        disabled>
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
                                        class="form-control" disabled>
                                        <option value="">Select</option>
                                        <option value="1"
                                            <?php if($plan['PlanMasters']['pulse_outbound_call_shift']=='1') echo 'selected'; ?>>
                                            1
                                        </option>
                                        <option value="15"
                                            <?php if($plan['PlanMasters']['pulse_outbound_call_shift']=='15') echo 'selected'; ?>>
                                            15
                                        </option>
                                        <option value="30"
                                            <?php if($plan['PlanMasters']['pulse_outbound_call_shift']=='30') echo 'selected'; ?>>
                                            30
                                        </option>
                                        <option value="45"
                                            <?php if($plan['PlanMasters']['pulse_outbound_call_shift']=='45') echo 'selected'; ?>>
                                            45
                                        </option>
                                        <option value="60"
                                            <?php if($plan['PlanMasters']['pulse_outbound_call_shift']=='60') echo 'selected'; ?>>
                                            60
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Rate Per Pulse</span>
                                    <input type="text" id="rate_per_pulse_outbound_call_shift" readonly
                                        name="rate_per_pulse_outbound_call_shift" placeholder="Rate Per Pulse"
                                        value="<?php echo $plan['PlanMasters']['rate_per_pulse_outbound_call_shift']; ?>"
                                        onkeypress="return checkCharacter(event,this)" class="form-control decimal"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>SMS Char 160</span>
                                    <input type="text" id="SMSCharge" name="SMSCharge" placeholder="SMS Charge"
                                        value="<?php echo $plan['PlanMasters']['SMSCharge']; ?>"
                                        onkeypress="return checkCharacter(event,this)" class="form-control decimal"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Email Charge</span>
                                    <input type="text" id="EmailCharge" name="EmailCharge"
                                        value="<?php echo $plan['PlanMasters']['EmailCharge']; ?>"
                                        placeholder="Per Email Charge" onkeypress="return checkCharacter(event,this)"
                                        class="form-control decimal" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>VFO Call Charge</span>
                                    <input type="text" id="VFOCallCharge" name="VFOCallCharge" placeholder="VFO Rs./Min"
                                        value="<?php echo $plan['PlanMasters']['VFOCallCharge']; ?>"
                                        onkeypress="return checkCharacter(event,this)" class="form-control decimal"
                                        disabled>
                                </div>
                            </div>

                        </div>



                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Miss Call Charge</span>
                                    <input type="text" id="MissCallCharge" name="MissCallCharge"
                                        value="<?php echo $plan['PlanMasters']['MissCallCharge']; ?>"
                                        placeholder="Miss Call Rs./Min" onkeypress="return checkCharacter(event,this)"
                                        class="form-control decimal" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span style="font-style:italic;">IVR Call Charge</span>
                                    <div class="form-group is-empty"><input type="text" id="IVR_Charge"
                                            name="IVR_Charge" placeholder="IVR Call Rs./CALL"
                                            value="<?php echo $plan['PlanMasters']['Ivr_Charge']; ?>"
                                            onkeypress="return checkCharacter(event,this)" class="form-control decimal"
                                            disabled><span class="material-input"></span></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span style="font-style:italic;">First Minute</span>
                                    <div class="form-group is-empty">

                                        <span>Enable</span> <input type="radio" id="first_minute" value="Enable"
                                            <?php if($plan['PlanMasters']['first_minute']=='Enable') echo 'checked'; ?>
                                            name="first_minute"><span class="material-input"></span>

                                        <span>Disable</span> <input type="radio" id="first_minute" value="Disable"
                                            <?php if($plan['PlanMasters']['first_minute']=='Disable') echo 'checked'; ?>
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
                                    <input type="text" id="NoOfFreeUser" name="NoOfFreeUser"
                                        value="<?php echo $plan['PlanMasters']['NoOfFreeUser']; ?>"
                                        placeholder="No. Of Free User" onkeypress="return checkCharacter(event,this)"
                                        class="form-control decimal" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Charge Per Extra User</span>
                                    <input type="text" id="ChargePerExtraUser" name="ChargePerExtraUser"
                                        placeholder="Charge For Extra User Rs./User"
                                        value="<?php echo $plan['PlanMasters']['ChargePerExtraUser']; ?>"
                                        onkeypress="return checkCharacter(event,this)" class="form-control decimal"
                                        disabled>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="button" value="Next" class="btn-web btn" style="float:right;"
                                    onclick="tabs('dialdesk', 'dialdee');">
                            </div>
                        </div>

                    </div>
                </div>
                <!-- dialdesk tab end-->

                <!-- dialdee tab -->
                <div id="dialdee" class="tab-pane fade in">
                    <div class="container-fluid">
                        <h2>Dialdee Plan Edit</h2>
                        <input type="checkbox" name="dialdeecheck" id="dialdeecheck" style="margin-left: 25px;"
                            onclick="enable_dialdee();">
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Quarterly Rental</span>
                                    <input type="text" name="QuarterlyRental" placeholder="Quarterly Rental"
                                        id="QuarterlyRental" onkeypress="return checkCharacter(event,this)"
                                        onkeyup="return decimal_check(this,this.value)"
                                        class="form-control extclass decimal" value="<?php echo $plan['PlanMasters']['QuarterlyRental']; ?>" disabled >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Free Sessions</span>
                                    <input type="text" id="FreeSessions" name="FreeSessions" placeholder="Free Sessions"
                                        onkeypress="return checkCharacter(event,this)" value="<?php echo $plan['PlanMasters']['FreeSessions']; ?>"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Inbound Whatsapp Charge</span>
                                    <input type="text" id="InboundWhatsappCharge" name="InboundWhatsappCharge"
                                        placeholder="Inbound Whatsapp Charge" value="<?php echo $plan['PlanMasters']['InboundWhatsappCharge']; ?>"
                                        onkeypress="return checkCharacter(event,this)"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Outbound Whatsapp Charge</span>
                                    <input type="text" id="OutboundWhatsappCharge" name="OutboundWhatsappCharge"
                                        placeholder="Outbound Whatsapp Charge" value="<?php echo $plan['PlanMasters']['OutboundWhatsappCharge']; ?>"
                                        onkeypress="return checkCharacter(event,this)"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="button" value="Previous" class="btn-web btn" style="float:left;"
                                    onclick="tabs('leadsquared', 'dialdesk');">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="button" value="Next" class="btn-web btn" style="float:right;"
                                    onclick="tabs('dialdee', 'vfo');">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- dialdeee tab end-->

                <!-- vfo tab -->
                <div id="vfo" class="tab-pane fade in">
                    <div class="container-fluid">
                        <h2>Vfo Plan Edit</h2>
                        <input type="checkbox" name="vfocheck" id="vfocheck" style="margin-left: 25px;"
                            onclick="enable_vfo();">
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Quarterly Rental</span>
                                    <input type="text" name="VfoQuarterlyRental" placeholder="Quarterly Rental"
                                        id="VfoQuarterlyRental" onkeypress="return checkCharacter(event,this)"
                                        onkeyup="return decimal_check(this,this.value)" value="<?php echo $plan['PlanMasters']['VfoQuarterlyRental']; ?>"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Free Minutes</span>
                                    <input type="text" id="FreeMinutes" name="FreeMinutes" placeholder="Free Minutes"
                                        onkeypress="return checkCharacter(event,this)" value="<?php echo $plan['PlanMasters']['FreeMinutes']; ?>"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Call Forwarding Charges After Free Minutes</span>
                                    <input type="text" id="CallForwardingCharges" name="CallForwardingCharges"
                                        placeholder="Call Forwarding Charges / Minute" value="<?php echo $plan['PlanMasters']['CallForwardingCharges']; ?>"
                                        onkeypress="return checkCharacter(event,this)"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Pulse</span>
                                    <input type="text" id="Pulse" name="Pulse" placeholder="Pulse"
                                        onkeypress="return checkCharacter(event,this)" value="<?php echo $plan['PlanMasters']['Pulse']; ?>"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="button" value="Previous" class="btn-web btn" style="float:left;"
                                    onclick="tabs('dialdesk', 'dialdee');">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="button" value="Next" class="btn-web btn" style="float:right;"
                                    onclick="tabs('vfo', 'leadsquared');">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- vfo tab end-->

                <!-- leadsqaure tab -->
                <div id="leadsquare" class="tab-pane fade in">
                    <div class="container-fluid">
                        <h2>Leadsquared Plan Edit</h2>
                        <input type="checkbox" name="leadsquarecheck" id="leadsquarecheck" style="margin-left: 25px;"
                            onclick="enable_leadsquare();">
                        <div class="col-md-12">


                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Order Form Starting Date</span>
                                    <input type="text" name="OrderStartingDate" placeholder="Order Form Starting Date" value="<?php echo $plan['PlanMasters']['OrderStartingDate']; ?>"
                                        id="OrderStartingDate" class="form-control date-picker" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Order Form End Date</span>
                                    <input type="text" name="OrderEndDate" placeholder="Order Form End Date" value="<?php echo $plan['PlanMasters']['OrderEndDate']; ?>"
                                        id="OrderEndDate" class="form-control date-picker" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Next Bill Due Date</span>
                                    <input type="text" name="NextDueDate" placeholder="Next Bill Due Date" value="<?php echo $plan['PlanMasters']['NextDueDate']; ?>"
                                        id="NextDueDate" class="form-control date-picker" disabled>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="button" value="Previous" class="btn-web btn" style="float:left;"
                                    onclick="tabs('dialdee', 'vfo');">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <input type="hidden" name="id" value="<?php echo $plan['PlanMasters']['Id']; ?>" />
                                <input type="submit" value="Update" class="btn-web btn">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- leadsqaure tab end-->
            </div>
        </div>
    </div>
</div>
