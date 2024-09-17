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


    function enable_dialdee() {
        if ($('#dialdeecheck').is(":checked")) {

            $('#vfocheck').not(this).prop('checked', false);
            $('#leadsquarecheck').not(this).prop('checked', false);
            enable_vfo();
            enable_leadsquare();

            $("#PlanName").removeAttr("disabled");
            $("#PeriodType").removeAttr("disabled");
            $("#QuarterlyRental").removeAttr("disabled");
            $("#FreeSessions").removeAttr("disabled");
            $("#InboundWhatsappCharge").removeAttr("disabled");
            $("#OutboundWhatsappCharge").removeAttr("disabled");

            //var new_input="<input type='hidden' id='PlanCreate' name='PlanCreate' value='Dialdee'>";

        } else {
            $("#PlanName").val("").attr("disabled", true);
            $("#PeriodType").val("").attr("disabled", true);
            $("#QuarterlyRental").val("").attr("disabled", true);
            $("#FreeSessions").val("").attr("disabled", true);
            $("#InboundWhatsappCharge").val("").attr("disabled", true);
            $("#OutboundWhatsappCharge").val("").attr("disabled", true);

        }
    }

    function enable_vfo() {
        if ($('#vfocheck').is(":checked")) {

            $('#dialdeecheck').not(this).prop('checked', false);
            $('#leadsquarecheck').not(this).prop('checked', false);
            enable_dialdee();
            enable_leadsquare();

            $("#PlanNamevfo").removeAttr("disabled");
            $("#PeriodType1").removeAttr("disabled");
            $("#VfoQuarterlyRental").removeAttr("disabled");
            $("#FreeMinutes").removeAttr("disabled");
            $("#CallForwardingCharges").removeAttr("disabled");
            $("#Pulse").removeAttr("disabled");

        } else {
            $("#PlanNamevfo").val("").attr("disabled", true);
            $("#PeriodType1").val("").attr("disabled", true);
            $("#VfoQuarterlyRental").val("").attr("disabled", true);
            $("#FreeMinutes").val("").attr("disabled", true);
            $("#CallForwardingCharges").val("").attr("disabled", true);
            $("#Pulse").val("").attr("disabled", true);

        }
    }

    function enable_leadsquare() {
        if ($('#leadsquarecheck').is(":checked")) {

            $('#dialdeecheck').not(this).prop('checked', false);
            $('#vfocheck').not(this).prop('checked', false);
            enable_vfo();
            enable_dialdee();

            $("#PlanNameleadsquare").removeAttr("disabled");
            $("#OrderStartingDate").removeAttr("disabled");
            $("#OrderEndDate").removeAttr("disabled");
            $("#NextDueDate").removeAttr("disabled");

        } else {
            $("#PlanNameleadsquare").val("").attr("disabled", true);
            $("#OrderStartingDate").val("").attr("disabled", true);
            $("#OrderEndDate").val("").attr("disabled", true);
            $("#NextDueDate").val("").attr("disabled", true);

        }
    }
</script>
<script>
    var req = "This field is required";
    var reqs = "This Tab All fields Required";


    function dialdee_validation() {
        $("#space").hide();
        $("#err").hide();
        $('#space').remove();
        $('#err').remove();

        if ($.trim($("#PlanName").val()) === "") {
            show_error('PlanName', req);
            show_error('dialdeecheck', reqs);
            return false;
        } else if ($.trim($("#QuarterlyRental").val()) === "") {
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

        if ($.trim($("#PlanNamevfo").val()) === "") {
            show_error('PlanNamevfo', req);
            show_error('vfocheck', reqs);
            return false;
        } else if ($.trim($("#VfoQuarterlyRental").val()) === "") {
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

        if ($.trim($("#PlanNameleadsquare").val()) === "") {
            show_error('PlanNameleadsquare', req);
            show_error('leadsquarecheck', reqs);
            return false;
        } else if ($.trim($("#OrderStartingDate").val()) === "") {
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

            if (val == 'dialdeecheck') {
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

<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a>Plan Master</a></li>
    <li class="active"><a href="#">Plan Tool Creation</a></li>
</ol>
<div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
    <div class="panel-heading">
        <h2>Plan Tool Creation</h2>
    </div>


    <?php echo $this->Form->create('AdminToolPlans',array('action'=>'client_wise_plan_creation','onsubmit'=>'return plan_validation();')); ?>

    <div class="panel-body" style="margin-top:-10px;">
        <div class="tabs-to-dropdown">
            <ul class="nav nav-pills d-none d-md-flex" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-dialdee-tab" data-toggle="tab" href="#dialdee" role="tab"
                        aria-controls="pills-dialdee" aria-selected="false">Dialdee</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-vfo-tab" data-toggle="tab" href="#vfo" role="tab"
                        aria-controls="pills-vfo" aria-selected="false">Vfo</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-leadsquared-tab" data-toggle="tab" href="#leadsquare" role="tab"
                        aria-controls="pills-leadsquared" aria-selected="false">Leadsquared</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">

                <!-- dialdee tab -->
                <div id="dialdee" class="tab-pane fade in">
                    <div class="container-fluid">
                        <h2>Dialdee Plan Creation</h2>
                        <input type="checkbox" name="dialdeecheck" id="dialdeecheck" style="margin-left: 25px;"
                            onclick="enable_dialdee();">
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Plan Name </span>
                                    <input type="text" name="PlanName" placeholder="Plan Name" id='PlanName'
                                        class="form-control extclass" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Plan Mode</span>
                                    <select id="PeriodType" name="PeriodType" onchange="getPeriod(this.value)"
                                        required="" class="form-control" disabled>
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
                                    <span>Rental Amount</span>
                                    <input type="text" name="RentalAmount" placeholder="Rental Amount"
                                        id="QuarterlyRental" onkeypress="return checkCharacter(event,this)"
                                        onkeyup="return decimal_check(this,this.value)"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Free Sessions</span>
                                    <input type="text" id="FreeSessions" name="FreeSessions" placeholder="Free Sessions"
                                        onkeypress="return checkCharacter(event,this)"
                                        class="form-control extclass decimal" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Inbound Whatsapp Charge</span>
                                    <input type="text" id="InboundWhatsappCharge" name="InboundWhatsappCharge"
                                        placeholder="Inbound Whatsapp Charge"
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
                                        placeholder="Outbound Whatsapp Charge"
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
                        <h2>Vfo Plan Creation</h2>
                        <input type="checkbox" name="vfocheck" id="vfocheck" style="margin-left: 25px;"
                            onclick="enable_vfo();">
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Plan Name </span>
                                    <input type="text" name="PlanName" placeholder="Plan Name" id='PlanNamevfo'
                                        class="form-control extclass" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Plan Mode</span>
                                    <select id="PeriodType1" name="PeriodType" onchange="getPeriod(this.value)"
                                        required="" class="form-control" disabled>
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
                                    <span>Rental Amount</span>
                                    <input type="text" name="RentalAmount" placeholder="Rental Amount"
                                        id="VfoQuarterlyRental" onkeypress="return checkCharacter(event,this)"
                                        onkeyup="return decimal_check(this,this.value)"
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
                                        onkeypress="return checkCharacter(event,this)"
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
                                        placeholder="Call Forwarding Charges / Minute"
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
                        <h2>Leadsquared Plan Creation</h2>
                        <input type="checkbox" name="leadsquarecheck" id="leadsquarecheck" style="margin-left: 25px;"
                            onclick="enable_leadsquare();">
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Plan Name </span>
                                    <input type="text" name="PlanName" placeholder="Plan Name" id='PlanNameleadsquare'
                                        class="form-control extclass" disabled>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Order Form Starting Date</span>
                                    <input type="text" name="OrderStartingDate" placeholder="Order Form Starting Date"
                                        id="OrderStartingDate" class="form-control date-picker" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Order Form End Date</span>
                                    <input type="text" name="OrderEndDate" placeholder="Order Form End Date"
                                        id="OrderEndDate" class="form-control date-picker" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <span>Next Bill Due Date</span>
                                    <input type="text" name="NextDueDate" placeholder="Next Bill Due Date"
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
                                <input type="submit" value="Submit" class="btn-web btn">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- leadsqaure tab end-->

            </div>
        </div>
        <?php  echo $this->Form->end(); ?>
    </div>
</div>
</div>
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



    // Basant code end here
</script>
