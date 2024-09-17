<?php //print_r($branch_master); ?>
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
            <?php 
            date_default_timezone_set('Asia/Kolkata');

                    if (date('m') <= 3) {
                        $financial_year = (date('Y')-1) . '-' . date('Y');
                    } else {
                        $financial_year = date('Y') . '-' . (date('Y') + 1);
                    }
            ?>
            <h4 class="page-header text-center"><span> <?= date('F');?> </span> ( <span> <?= $financial_year;?></span> ) 
            <br><?php echo $this->Session->flash(); ?></h4>
<!--                <form id="form_month" method="post" action="index_ledger">
                <select name="month" id="month" onchange="onMonth()">
                    <option value="">Select</option>
                    <option value="Jan" <?php if($month=='Jan'){echo 'selected';} ?>>Jan</option>
                    <option value="Feb" <?php if($month=='Feb'){echo 'selected';} ?>>Feb</option>
                    <option value="Mar" <?php if($month=='Mar'){echo 'selected';} ?>>Mar</option>
                </select> 
                </form>-->
                <table class="table" style="width:100%;font-size: 10px;">
                    <tr>
                        <th rowspan="2">S.No.</th>
                        <th class="left_bord"></th>
                        
                        <th colspan="4" class="left_bord" style="text-align: center;background: #EEE9E9;">Ledger</th>
                        <th colspan="4" class="left_bord" style="text-align: center;background: #FAEBEB;">Credit Point Consumption</th>
                        <th colspan="3" class="left_bord" style="text-align: center;">Proposed Action</th>
                    </tr>
                    <tr>
                        <th class="left_bord">Client</th>
                        <th align="center" style="background: #EEE9E9;">Opening</th>
                        <th align="right" style="background: #EEE9E9;">Billed</th>
                        <th align="right" style="background: #EEE9E9;">Collected</th>
                        <th align="right" style="background: #EEE9E9;" class="left_bord">Closing</th>
                        <th align="right" style="background: #FAEBEB;">Opening</th>
                        <th align="right" style="background: #FAEBEB;">Fresh released</th>
                        <th align="right" style="background: #FAEBEB;">Consumed</th>
                        <th align="right" style="background: #FAEBEB;" class="left_bord">Balance</th>
                        <th align="right" style="background: #CDB7B5;">Exposure</th>
                        <th align="right" style="background: #CDB7B5;">To be billed</th>
                        <th align="center" style="background: #CDB7B5;" class="left_bord">Action</th>
                        <th align="center" style="background: #CDB7B5;display: none;" class="left_bord">Paymnet</th> 
                    </tr>
                    <?php $i =1;
               
                    foreach($data as $client=>$record) 
                    { //print_r($record);exit;
                        //echo "";
                        ?>

                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td class="left_bord"><?php echo substr($client,0,30); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php $Opening_ledger=$Opening_ledger+$op; $op =($record['ledger_access_usage']+$record['ledger_topup']+$record['ledger_sub']);  echo number_format($op,2); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php $Billed=$Billed+$led_op; $led_op = $record['billed']+$record['subbilled']+$record['firstbilled']+$record['setupbilled'];  echo number_format($led_op,2); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php $Collected=$Collected+$total_collected; $total_collected = $record['coll']+$record['subs_coll']+$record['first_plan_coll']+$record['setupcoll']; echo number_format($total_collected,2); ?></td>
                                <td align="right" style="background: #EEE9E9;" class="left_bord"><?php $clsum= $clsum+$cl;  $cl=round($op+$led_op-$record['coll']-$record['subs_coll']-$record['first_plan_coll']-$record['setupcoll'],2); 
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
                                $fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
                                $fr_val_talk= round(($record['billed']*100/118),2);
                                $fr_val = $fr_val_subs+$fr_val_talk;
                                
                                $first_bill = $record['first_plan_value'];
                                $first_bill_with_gst = round($record['first_plan_value_with_gst'],2);
                                $plan_sub_cost = round($record['new_plan_sub_cost'],2);
                                $plan_setup_cost = round($record['new_plan_setup_cost'],2);
                                $plan_dev_cost = round($record['new_plan_dev_cost'],2);
                                
                                $url = "bill_type=first_bill&Subscription={$plan_sub_cost}&SetupCost={$plan_setup_cost}&DevelopmentCost={$plan_dev_cost}&cost_center={$record['cost_center']}&amt=".abs($first_bill_with_gst)."&sub_start_date={$record['sub_start_date']}&sub_end_date={$record['sub_end_date']}&due_date={$record['due_date']}";
                                ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php $AB=$AB+$open_bal; echo $open_bal; ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php $ABD=$ABD+$fr_val; echo  number_format($fr_val,2); ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php $csbal =$record['cs_bal']; $XYZ=$XYZ+$csbal;  echo number_format($csbal,2); ?></td>
                                <td align="right" style="background: #FAEBEB;" class="left_bord"><?php $bal = round($open_bal+$fr_val-$record['cs_bal'],2); 
                                $CDX=$CDX+$bal; echo number_format($bal,2); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php //echo $record['subbilled'];exit;
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
                                    $tobebilled2=round($exp-$record['billed'],2);
                                    $tobebilled2_without_tax = round(($tobebilled2/118)*100,2);
                                }
                                
                                $TO_BE_BILLED = $tobebilled2+$tobebilled+$tobebilledfirst;

                                $TOTAL_TO_BE_BILLED = $TOTAL_TO_BE_BILLED +$TO_BE_BILLED;
                                
                                ?>
                                </td> 
                                <td align="right" style="background: #EEE9E9;"><?php  echo number_format($tobebilled2+$tobebilled+$tobebilledfirst,2); ?></td>
                                
                                <td style="background: #EEE9E9;" class="left_bord">
                                    <?php if($tobebilledfirst>1) { ?><?php echo $br;$br = "<br/>"; ?>
                                        <a href="add_bill?<?php echo $url?>">First Bill</a>
                                    <?php }  ?>  
                                    <?php if($tobebilled2>1) { ?><?php echo $br;$br = "<br/>"; ?><a href="add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=talk&amt=<?php echo round($tobebilled2_without_tax,2);?>">Credit</a><?php } ?>
                                    
                                    <?php if($tobebilled>1) {  ?> <?php echo $br;$br = "<br/>"; ?>
                                    <a href="add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=subs&amt=<?php echo abs($tobebilled_without_tax);?>&sub_start_date=<?php echo $record['sub_start_date'];?>&sub_end_date=<?php echo $record['sub_end_date'];?>&due_date=<?php echo $record['due_date'];?>">Subscription</a>
                                    <?php }  ?>
                                    
                                    
                                </td>

                                <td style="background: #EEE9E9;display: none;">
                                    <!-- <div style="width: max-content;margin-bottom: 10px;">  
                                
                                       <a class="btn-primary" href="add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=talk&amt=<?php echo round($tobebilled2_without_tax,2);?>" style="padding: 5px;border-radius: 5px;font-size: 12px;">Advance Recharge</a>
                                     </div> -->
                                <!-- </td>
                                <td> -->
                                <?php if(($tobebilledfirst>0) || ($tobebilled2>0) || ($tobebilled>0)) { ?>

                                    <button type="button" id="btnclick<?php echo $i; ?>" onclick="modalsms(<?php echo $i; ?>)">Send SMS</button>

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
                                                        <form class="form-horizontal" action="send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;">
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
                                                    <form class="form-horizontal" action="send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;">
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
                                                    <form class="form-horizontal" action="send_payment_data" method="post" style="padding-bottom: 20px;padding-right: 20px;">
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

                        

                ?>

                   
                    <tr>
                        <th colspan="2" align="center"style="text-align: center;">Total</th>
                        <th align="center" style="background: #EEE9E9;text-align: center;"><?= $Opening_ledger;?></th>
                        <th align="right" style="background: #EEE9E9;text-align: center;"><?= $Billed;?></th>
                        <th align="right" style="background: #EEE9E9;text-align: center;"><?= $Collected;?></th>
                        <th align="right" style="background: #EEE9E9;text-align: center;" class="left_bord"><?= $clsum;?></th>
                      
                      
                        <th align="right" style="background: #FAEBEB;text-align: center;"><?= $AB;?></th>
                        <th align="right" style="background: #FAEBEB;text-align: center;"><?= number_format($ABD,2);?></th>

                        <th align="right" style="background: #FAEBEB;text-align: center;"><?= number_format($XYZ,2);?></th>

                        <th align="right" style="background: #FAEBEB;text-align: center;" class="left_bord"><?php echo number_format($CDX,2);?></th>
                      
                        <th align="center" style="background: #CDB7B5;text-align: center;"><?= number_format($TOTAL_EXP,2) ;?></th>
                        <th align="center" style="background: #CDB7B5;text-align: center;"><?= number_format($TOTAL_TO_BE_BILLED,2);?></th>
                        <th align="center" style="background: #CDB7B5;" class="left_bord"></th>
                        <!-- <th align="center" style="background: #CDB7B5;" class="left_bord">Paymnet</th> -->
                    </tr>


                </table>
                
            </div>
	</div>
    </div>
</div>



<?php $this->Form->end();?>

