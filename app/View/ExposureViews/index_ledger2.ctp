<?php //print_r($branch_master); ?>
<script>
    $( function() {
            $( ".date-picker1" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: '-100:+0' });
          });
    function get_exposure_export()
    {
        var client = $('#client').val();
        var client_status = $('#client_status').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        window.location.href = '/dialdesk/ExposureExports/export_exposure_views?client='+client+'&client_status='+client_status+'&from_date='+from_date+'&to_date='+to_date;
    }
</script>
<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view')); ?>
<?php foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
	endforeach;
?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Invoice</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Billing">View Exposure</a></li>
</ol>
<div class="page-heading">            
    <h1>View Exposure</h1>
    <h1 style="float:right;" onclick="get_exposure_export();">Export</h1>
</div>
<style>
    .message{color: green;}
    .left_bord 
    {
        border-right: thin solid;
        border-color: black;
    }

        .page-header 
        {
            margin: 0;
            font-weight: 800;
        }

        /*
        modal
        -----
        */

        .modalBox {

            position: fixed;
            z-index: 99;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.6);
        }
            .modalContent {
                background: white;
                padding: 40px 40px 20px;
                width: 30%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                animation-name: modalZoom;
                animation-duration: 0.3s;

            }

            .close {
                color: black;
                position: absolute;
                top: 2px;
                right: 10px;
                font-size: 24px;
                cursor: pointer;
                transition: color 0.3s linear;

            }

            
        @-webkit-keyframes modalZoom {
            from {
                transform: translate(-50%, -50%) scale(0);
            }

            to {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes modalZoom {
            from {
                transform: translate(-50%, -50%) scale(0);
            }

            to {
                transform: translate(-50%, -50%) scale(1);
            }
        }



</style>
<script>
    function onMonth()
    {
        $('#form_month').submit();
    }

    function checkNumber(val,evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        
        if (charCode> 31 && (charCode < 48 || charCode > 57) )
            {            
                return false;
            }
            if(val.length>10)
            {
                return false;
            }
        return true;
    }

    function modalsms(id)
    {

        $(".containe"+id).show();

    $(".close"+id).click(function(){
        $(".containe"+id).hide();
    });

    }
</script>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            
            
            <div class="panel-body" style="margin-top:-10px;">
            <?php $color_array = array('Active'=>"#4CAF50","Hold"=>"#ff9800",'De-Active'=>'#f44336','Close'=>'#fff');
            $color_array2 = array('Active'=>"A","Hold"=>"H",'De-Active'=>'D');
            date_default_timezone_set('Asia/Kolkata');

                    if (date('m') <= 3) {
                        $financial_year = (date('Y')-1) . '-' . date('Y');
                    } else {
                        $financial_year = date('Y') . '-' . (date('Y') + 1);
                    }
            ?>
            <p>
                <h4 class="page-header text-center">
                    <span> <?= date('F',strtotime( date() . ' -1 day' ));?> </span> ( <span> <?= $financial_year;?></span> )
                    <div style="float:right;"><span style="background-color: red;width: 10px;border: 2px solid #f44336;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> De-Active &nbsp;&nbsp;</div>
                    <div style="float:right;"><span style="background-color: yellow;width: 10px;border: 2px solid #ff9800;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Hold &nbsp;&nbsp;</div>                    
                    <div style="float:right;"><span style="background-color: green;width: 10px;border: 2px solid #4CAF50;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Active &nbsp;&nbsp;</div>
                    
            <br><?php echo $this->Session->flash(); ?></h4></p>
            <p class="text-center">Sequence of Records ordered as Active First and Ascending order of Exposure.</p>
                <form id="form_month" method="post" >
                <div class="form-group">
                    <div class="col-sm-3">
                        <select name="client" id="client" class="form-control">
                            <option value="">Client</option> 
                            <?php foreach($client_det as $clientid=>$clientname) { ?>
                                <option value="<?php echo $clientid; ?>" <?php if($company_id==$clientid) { echo 'selected';} ?>><?php echo $clientname; ?></option>
                            <?php } ?>    
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="client_status" id="client_status" class="form-control">
                            <option value="">Status</option> 
                            <option value="">All</option> 
                            <?php foreach($color_array2 as $st2=>$st3) { ?>
                                <option value="<?php echo $st3; ?>" <?php if($st3==$client_status) { echo 'selected';} ?>><?php echo $st2; ?></option>
                            <?php } ?>    
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="from_date" id="from_date" value="<?php echo $from_date; ?>" class="form-control date-picker1" required=""/>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="to_date" id="to_date" value="<?php echo $to_date; ?>" class="form-control date-picker1" required=""/>
                    </div>
                    
                    <div class="col-sm-3">
                        <button type="submit" style="margin-top:-1px;" class="btn btn-web">Search</button>
                        <button type="button" onclick="get_exposure_export();" style="margin-top:-1px;" class="btn btn-web">Export</button>
                    </div>
                    
                </div>
                </form>

                <table class="table" style="width:100%;font-size: 11px;">
                    <tr>
                        <th rowspan="2" style="padding:1px;">S.No.</th>
                        <th class="left_bord"></th>
                        
                        <th colspan="4" class="left_bord" style="text-align: center;background: #EEE9E9;">Ledger</th>
                        <th colspan="5" class="left_bord" style="text-align: center;background: #FAEBEB;">Credit Point Consumption</th>
                        <th colspan="4" class="left_bord" style="text-align: center;">Proposed Action</th>
                    </tr>
                    <tr>
                        <th class="left_bord" style="padding:2px;">Client</th>
                        <th  style="text-align:right;padding:2px;background: #EEE9E9;">Opening</th>
                        <th  style="text-align:right;padding:2px;background: #EEE9E9;">Billed</th>
                        <th  style="text-align:right;padding:2px;background: #EEE9E9;">Collected</th>
                        <th  style="text-align:right;padding:2px;background: #EEE9E9;" class="left_bord">Closing</th>

                        <th  style="text-align:right;padding:2px;background: #FAEBEB;">Opening</th>
                        <th  style="text-align:right;padding:2px;background: #FAEBEB;">Fresh released</th>
                        <th  style="text-align:right;padding:2px;background: #FAEBEB;">Consumed</th>
                        <th  style="text-align:right;padding:2px;background: #FAEBEB;">Balance</th>
                        <th  style="padding:2px;background: #FAEBEB;" class="left_bord">Status</th>        

                        <th  style="text-align:right;padding:2px;background: #CDB7B5;">Exposure</th>
                        <th  style="text-align:right;padding:2px;background: #CDB7B5;">To be billed</th>
                        <th  style="text-align:right;padding:2px;background: #CDB7B5;" class="left_bord">Action</th>
                        <th  style="text-align:right;padding:2px;background: #CDB7B5;display: none1;" class="left_bord">Payment</th> 
                    </tr>
                    <?php 
                    $i =1;
                    

                    foreach($color_array as $st=>$color)
                    {
                        //print_r($data4[$st]);exit;
                        $data3 = $data4[$st];
                        foreach($data3 as $ex=>$data)
                        {
                            //print_r($data);exit;
                            foreach($data as $client=>$record) 
                            { //print_r($record);exit;
                        //echo "";
                        ?>

                            <tr>
                                <td style="padding:1px;"><?php echo $i++; ?></td>
                                <td class="left_bord" style="padding:2px;background:<?php echo $color_array[$record['status']]; ?>"><b style="text-color:#fff;"><?php echo substr($client,0,20); ?></b></td>
                                <td align="right" style="padding:2px;background: #EEE9E9;"><?php  $op =($record['op2_ledger']);  echo number_format($op,2); $Opening_ledger=$Opening_ledger+$op; ?></td>
                                <td align="right" style="padding:2px;background: #EEE9E9;"><?php  $led_op = $record['bill2_ledger'];  echo number_format($led_op,2); $Billed=$Billed+$led_op; ?></td>
                                <td align="right" style="padding:2px;background: #EEE9E9;"><?php  $total_collected = $record['coll2_ledger']; echo number_format($total_collected,2);  $Collected=$Collected+$total_collected; ?></td>
                                <td align="right" style="padding:2px;background: #EEE9E9;" class="left_bord"><?php   $cl=round($op+$led_op-$total_collected,2);  $clsum= $clsum+$cl;
                                echo number_format($cl,2); 
                                $led_access = round(($record['ledger_access_usage']/118)*100,2);
                                $led_topup = round(($record['ledger_topup']/118)*100,2);
                                //$led_sub = round(($record['ledger_sub']/118)*100,2);
                                
                                $cln = round(($record['ledger_sub']/118)*100,2);
                                $onePer = round($record['RentalAmount']/100,2); 
                                $plan_pers = round($record['Balance']/$onePer,3);
                                $led_bal_sub = round(($cln*$plan_pers)/100,2); 
                                //$open_bal = $record['talk_time']-($led_bal_sub+$led_access+$led_topup);
                                $open_bal = $record['talk_time'];
                                $coll = $record['coll']+$record['subs_coll']+$record['first_plan_subscoll'];
                                //echo '<br/>'.($record['subs_coll']/118;exit;
                                
                                
                                $op_val_subs = round((($record['first_plan_subscoll'])/118)*$plan_pers,2);
                                $open_bal +=$op_val_subs;
                                //$fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
                                $fr_val_subs = round((($record['subbilled_as_sub_date'])/118)*$plan_pers,2);
                                $fr_val_talk= round(($record['billed']*100/118),2);
                                $fr_val = $fr_val_subs+$fr_val_talk;
                                
                                $first_bill = $record['first_plan_value']+$record['new_plan_sub_cost'];
                                $first_bill_with_gst = round($first_bill*1.18,2);
                                $plan_sub_cost = round($record['new_plan_sub_cost'],2);
                                $plan_setup_cost = round($record['new_plan_setup_cost'],2);
                                $plan_dev_cost = round($record['new_plan_dev_cost'],2);
                                
                                $url = "bill_type=first_bill&Subscription={$plan_sub_cost}&SetupCost={$plan_setup_cost}&DevelopmentCost={$plan_dev_cost}&cost_center={$record['cost_center']}&amt=".abs($first_bill_with_gst)."&sub_start_date={$record['sub_start_date']}&sub_end_date={$record['sub_end_date']}&due_date={$record['due_date']}";
                                ?></td>
                                <td align="right" style="padding:2px; <?php $open_bal = round($record['op2_credit'],2); if($open_bal<0) { echo "background-color:#f44336;color:#fff";} else {echo "background-color:#FAEBEB;";} ?>"><?php  echo number_format($open_bal,2);   $AB=$AB+$open_bal; ?></td>
                                <td align="right" style="padding:2px;background: #FAEBEB;"><?php   $fr_val = round($record['fr_release_credit'],2);echo number_format($fr_val,2);  $ABD=$ABD+$fr_val; ?></td>
                                <td align="right" style="padding:2px;background: #FAEBEB;"><?php $csbal = round($record['consume_credit'],2); if($record['plan_status']=='Testing') { echo number_format($record['consume_credit_testing'],2); } else { echo number_format($csbal,2);   } $XYZ=$XYZ+$csbal;  ?></td>
                                <td align="right" style="padding:2px;background: #FAEBEB;" class="left_bord"><?php $bal = round($open_bal+$fr_val-$csbal,2); 
                                $CDX=$CDX+$bal; echo number_format($bal,2); ?></td>
                                <td align="right" style="padding:2px;background: #FAEBEB;" class="left_bord"><?php echo $record['plan_status']; ?></td>
                                <td align="right" style="padding:2px;background: #EEE9E9;"><?php //echo $record['subbilled'];
                                $tobebilledfirst = round($first_bill_with_gst-$record['firstbilled'],2);
                                $tobebilled = round($record['subs_val']-$record['subbilled'],2);
                                $total_without_tax = 0;
                                if($bal<0)
                                {    $total_without_tax=round($bal,2);     }
                                $total = round($total_without_tax*1.18,2);
                                //$exp = $total+$cl;
                                $exp = $total;
                                //$exp = $cl-$led_exp; 
                                if($exp<0)
                                {
                                    $exp = -1*$exp;
                                }


                                if($tobebilled<0)
                                {
                                    $tobebilled = 0;
                                }
                                $tobebilled_without_tax = round(($tobebilled/118)*100,2);

                                $exposure = $exp+$cl+$tobebilled+$tobebilledfirst;

                                $TOTAL_EXP =$TOTAL_EXP+$exposure;
                                //echo "$exp+$cl+$tobebilled+$tobebilledfirst/";
                                echo number_format($exp+$cl+$tobebilled+$tobebilledfirst,2);
                                //printing exposure value ends here
                                echo '<br/>';
                                //echo $record['adv_val'];exit;
                                if($exp==0)
                                {
                                    $tobebilled2=0;
                                }
                                else
                                {
                                    #$tobebilled2=round($exp-$record['billed'],2);
                                    $tobebilled2=round($exp,2);
                                    $tobebilled2_without_tax = round(($tobebilled2/118)*100,2);
                                }
                                //echo "round($exp-{$record['billed']},2)";
                                $TO_BE_BILLED = $tobebilled2+$tobebilled+$tobebilledfirst;

                                $TOTAL_TO_BE_BILLED = $TOTAL_TO_BE_BILLED +$TO_BE_BILLED;
                                
                                ?>
                                </td> 
                                
                                <td align="right" style="padding:2px;background: #EEE9E9;"><?php //echo "$tobebilled2+$tobebilled+$tobebilledfirst/";
                                  echo number_format($tobebilled2+$tobebilled+$tobebilledfirst,2); ?></td>
                                
                                <td style="padding:2px;background: #EEE9E9;" class="left_bord">
                                    <?php if($tobebilledfirst>1) { ?><?php echo $br;$br = "<br/>"; ?>
                                        <a href="/dialdesk/InitialInvoices/add_bill?<?php echo $url?>">First Bill</a>
                                    <?php }  ?>  
                                    <?php if($tobebilled2>1) { ?><?php echo $br;$br = "<br/>"; ?><a href="/dialdesk/InitialInvoices/add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=talk&amt=<?php echo round($tobebilled2_without_tax,2);?>">Credit</a><?php } ?>
                                    
                                    <?php if($tobebilled>1) {  ?> <?php echo $br;$br = "<br/>"; ?>
                                    <a href="/dialdesk/InitialInvoices/add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=subs&amt=<?php echo abs($tobebilled_without_tax);?>&sub_start_date=<?php echo $record['sub_start_date'];?>&sub_end_date=<?php echo $record['sub_end_date'];?>&due_date=<?php echo $record['due_date'];?>">Subscription</a>
                                    <?php }  ?>
                                    
                                    
                                </td>

                                <td style="padding:2px;background: #EEE9E9;display: none1;">
                                    <!-- <div style="width: max-content;margin-bottom: 10px;">  
                                
                                       <a class="btn-primary" href="add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=talk&amt=<?php echo round($tobebilled2_without_tax,2);?>" style="padding: 5px;border-radius: 5px;font-size: 12px;">Advance Recharge</a>
                                     </div> -->
                                <!-- </td>
                                <td> -->
                                <?php if(($tobebilledfirst>0) || ($tobebilled2>0) || ($tobebilled>0)) { ?>

                                    <button style="background: green;border-color: green;color: white;border: 0;border-radius: 5px;" type="button" id="btnclick<?php echo $i; ?>" onclick="modalsms(<?php echo $i; ?>)">Send SMS</button>

                                    <div class="containe<?php echo $i; ?>" style="display:none;">
                                    <!-- Modal Box for Login -->
                                        <div class="modalBox">
                                            <!-- Start Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close<?php echo $i; ?>" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">SMS Header</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                    <?php if($tobebilledfirst>0) { ?>
                                                    <div class="col-md-4">
                                                        <h3>First Bill</h3>
                                                        <!-- <a style="background-color: #607d8b;" class="btn btn-success btn-sm" href="smssend?amnt=3456">Send SMS</a> -->
                                                        <form class="form-horizontal" action="/dialdesk/InitialInvoices/send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;">
                                                            <div class="form-group">
                                                                <label for="usr">Name:</label>
                                                                <input type="text" class="form-control" name="client_name" value="<?php echo substr($client,0,30); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="em">Email ID</label>
                                                                <input type="email" class="form-control" name="client_email" value="<?php echo $record['clientEmail'];?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pn">Phone No:</label>
                                                                <input type="text" class="form-control" name="phone_no"  onkeypress="return checkNumber(this.value,event)"  value="<?php echo $record['phone_no'];?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="am">Amount:</label>
                                                                <input type="text" class="form-control" name="amount" value="<?php echo $tobebilledfirst;?>" required>
                                                            </div>
                                                            
                                                            <!-- <div class="form-group">
                                                                <label for="am">Bill No:</label>
                                                                <input type="text" class="form-control" name="bill_no" value="09-172/21-22" required>
                                                            </div> -->
                                                            <input type="hidden" name="recharge_type" value="Normal">
                                                            <input type="hidden" name="category" value="First Bill">
                                                            <div class="form-group">
                                                                <input type="submit" class="btn btn-success" value="Send SMS" name="buy" style="background: #607d8b;">
                                                            </div>
                                                        </form>

                                                        <!-- <form class="form-horizontal" action="send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;"> <input type="submit" value="BUY" name="buy"></form> -->

                                                    </div>
                                                    <?php }  if($tobebilled>0) { ?>
                                                    <div class="col-md-4">
                                                        <h3>Subscription</h3>
                                                        <!-- <a style="background-color: #607d8b;" class="btn btn-success btn-sm" href="smssend?amnt=<?php echo abs($tobebilled_without_tax);?>">Send SMS</a> -->
                                                    <form class="form-horizontal" action="/dialdesk/InitialInvoices/send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;">
                                                            <div class="form-group">
                                                                <label for="usr">Name:</label>
                                                                <input type="text" class="form-control" name="client_name" value="<?php echo substr($client,0,30); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="em">Email ID</label>
                                                                <input type="email" class="form-control" name="client_email" value="<?php echo $record['clientEmail'];?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pn">Phone No:</label>
                                                                <input type="text" class="form-control" name="phone_no"  onkeypress="return checkNumber(this.value,event)"  value="<?php echo $record['phone_no'];?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="am">Amount:</label>
                                                                <input type="text" class="form-control" name="amount" value="<?php echo $tobebilled;?>" required>
                                                            </div>
                                                            
                                                            <!-- <div class="form-group">
                                                                <label for="am">Bill No:</label>
                                                                <input type="text" class="form-control" name="bill_no" value="09-172/21-22" required>
                                                            </div> -->
                                                            <input type="hidden" name="recharge_type" value="Normal">
                                                            <input type="hidden" name="category" value="Subscription">
                                                            <div class="form-group">
                                                                <input type="submit" class="btn btn-success" value="Send SMS" name="buy" style="background: #607d8b;">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <?php }  if($tobebilled2>0) { ?>
                                                    <div class="col-md-4">
                                                        <h3>Credit</h3>
                                                        <!-- <a style="background-color: #607d8b;" class="btn btn-success btn-sm" href="smssend?amnt=<?php echo round($tobebilled2_without_tax,2);?>">Send SMS</a> -->
                                                    <form class="form-horizontal" action="/dialdesk/InitialInvoices/send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;">
                                                            <div class="form-group">
                                                                <label for="usr">Name:</label>
                                                                <input type="text" class="form-control" name="client_name" value="<?php echo substr($client,0,30); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="em">Email ID</label>
                                                                <input type="email" class="form-control" name="client_email" value="<?php echo $record['clientEmail'];?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pn">Phone No:</label>
                                                                <input type="text" class="form-control" name="phone_no"  onkeypress="return checkNumber(this.value,event)"  value="<?php echo $record['phone_no'];?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="am">Amount:</label>
                                                                <input type="text" class="form-control" name="amount" value="<?php echo $tobebilled2;?>" required>
                                                            </div>
                                                            
                                                            <!-- <div class="form-group">
                                                                <label for="am">Bill No:</label>
                                                                <input type="text" class="form-control" name="bill_no" value="09-172/21-22" required>
                                                            </div> -->

                                                            <input type="hidden" name="recharge_type" value="Normal">
                                                            <input type="hidden" name="category" value="Credit">
                                                            <div class="form-group">
                                                                <input type="submit" class="btn btn-success" value="Send SMS" name="buy" style="background: #607d8b;">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <?php } ?>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-default close<?php echo $i; ?>" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                            <!-- End Modal content-->
                                        </div>

                                    </div>
                                    <?php } ?>
                                    
                                </td>
                            </tr> 
                        
                    
                    <?php } 
                        }
                    }    
                        

                ?>

                   
                    <tr>
                        <th colspan="2" style="text-align: right;" class="left_bord">Total</th>
                        <th style="padding:2px;background: #EEE9E9;text-align: right;"><?= number_format($Opening_ledger,2);?></th>
                        <th style="padding:2px;background: #EEE9E9;text-align: right;"><?= number_format($Billed,2);?></th>
                        <th style="padding:2px;background: #EEE9E9;text-align: right;"><?= number_format($Collected,2);?></th>
                        <th style="padding:2px;background: #EEE9E9;text-align: right;" class="left_bord"><?= number_format($clsum,2);?></th>
                      
                      
                        <th style="padding:2px;background: #FAEBEB;text-align: right;"><?= number_format($AB,2);?></th>
                        <th style="padding:2px;background: #FAEBEB;text-align: right;"><?= number_format($ABD,2);?></th>
                        <th style="padding:2px;background: #FAEBEB;text-align: right;"><?= number_format($XYZ,2);?></th>
                        <th style="padding:2px;background: #FAEBEB;text-align: right;" class="left_bord"><?php echo number_format($CDX,2);?></th>
                        <th class="left_bord" style="background: #FAEBEB;"></th>
                      
                        <th style="padding:2px;background: #CDB7B5;text-align: right;"><?= number_format($TOTAL_EXP,2) ;?></th>
                        <th style="padding:2px;background: #CDB7B5;text-align: right;"><?= number_format($TOTAL_TO_BE_BILLED,2);?></th>
                        <th style="padding:2px;background: #CDB7B5;text-align: right;" class="left_bord"></th>
                        <th class="left_bord" style="background: #CDB7B5;"></th>
                        
                        <!-- <th align="center" style="background: #CDB7B5;" class="left_bord">Payment</th> -->
                    </tr>
                    

                </table>
                
            </div>
	</div>
    </div>
</div>



<?php $this->Form->end();?>

