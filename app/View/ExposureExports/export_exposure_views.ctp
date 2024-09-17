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


<?php header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exposure_views_".date("Ymdhis").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
    
    $color_array = array('Active'=>"#4CAF50","Hold"=>"#ff9800",'De-Active'=>'#f44336','Close'=>'#fff');
            $color_array2 = array('Active'=>"A","Hold"=>"H",'De-Active'=>'D');
            date_default_timezone_set('Asia/Kolkata');

                    if (date('m') <= 3) {
                        $financial_year = (date('Y')-1) . '-' . date('Y');
                    } else {
                        $financial_year = date('Y') . '-' . (date('Y') + 1);
                    }
            ?>
            <p>
                
                    
                    
                    
                    
                    
            
            
                

                <table class="table" style="width:100%;font-size: 11px;">
                    <tr>
                        <th rowspan="2" style="padding:1px;">S.No.</th>
                        <th class="left_bord"></th>
                        
                        <th colspan="4" class="left_bord" style="text-align: center;background: #EEE9E9;">Ledger</th>
                        <th colspan="5" class="left_bord" style="text-align: center;background: #FAEBEB;">Credit Point Consumption</th>
                        <th colspan="2" class="left_bord" style="text-align: center;">Proposed Action</th>
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
                                <td align="right" style="padding:2px; <?php if($open_bal<0) { echo "background-color:#f44336;color:#fff";} else {echo "background-color:#FAEBEB;";} ?>"><?php $open_bal = round($record['op2_credit'],2); echo number_format($open_bal,2);   $AB=$AB+$open_bal; ?></td>
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
                                <td align="right" style="padding:2px;background: #EEE9E9;"><?php  echo number_format($tobebilled2+$tobebilled+$tobebilledfirst,2); ?></td>
                                
                                

                                
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
                        
                        
                        
                        <!-- <th align="center" style="background: #CDB7B5;" class="left_bord">Payment</th> -->
                    </tr>
                    

                </table>

                <?php echo exit; ?>
                




