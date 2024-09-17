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
    .left_bord {
  border-right: thin solid;
  border-color: black;
}
</style>
<script>
    function onMonth()
    {
        $('#form_month').submit();
    }
</script>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            
            <div class="panel-body" style="margin-top:-10px;">
	        <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
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
                    </tr>
                    <?php $i =1;
                    foreach($data as $client=>$record) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td class="left_bord"><?php echo substr($client,0,30); ?></td>
                        <td align="right" style="background: #EEE9E9;"><?php $op =($record['ledger_access_usage']+$record['ledger_topup']+$record['ledger_sub']);  echo number_format($op,2); ?></td>
                        <td align="right" style="background: #EEE9E9;"><?php echo number_format($record['billed']+$record['subbilled'],2); ?></td>
                        <td align="right" style="background: #EEE9E9;"><?php echo number_format($record['coll']+$record['subs_coll'],2); ?></td>
                        <td align="right" style="background: #EEE9E9;" class="left_bord"><?php  $cl=round($op-$record['coll']-$record['subs_coll'],2); 
                        echo number_format($cl,2); 
                        $led_access = round(($record['ledger_access_usage']/118)*100,2);
                        $led_topup = round(($record['ledger_topup']/118)*100,2);
                        //$led_sub = round(($record['ledger_sub']/118)*100,2);
                        
                        $cln = round(($record['ledger_sub']/118)*100,2);
                        $onePer = round($record['RentalAmount']/100,2); 
                        $plan_pers = round($record['Balance']/$onePer,3);
                        $led_bal_sub = round(($cln*$plan_pers)/100,2); 
                        $open_bal = $record['talk_time']-($led_bal_sub+$led_access+$led_topup);
                        $coll = $record['coll']+$record['subs_coll'];
                        //echo '<br/>'.($record['subs_coll']/118;exit;
                        $fr_val_subs= round(($record['subs_coll']/118)*$plan_pers,2);
                        $fr_val_talk= round(($record['coll']*100/118),2);
                        $fr_val = $fr_val_subs+$fr_val_talk;
                        ?></td>
                        <td align="right" style="background: #FAEBEB;"><?php echo "$open_bal"; ?></td>
                        <td align="right" style="background: #FAEBEB;"><?php echo number_format($fr_val,2); ?></td>
                        <td align="right" style="background: #FAEBEB;"><?php echo number_format($record['cs_bal'],2); ?></td>
                        <td align="right" style="background: #FAEBEB;" class="left_bord"><?php  $bal = round($open_bal+$fr_val-$record['cs_bal'],2); 
                        echo number_format($bal,2); ?></td>
                        <td align="right" style="background: #EEE9E9;"><?php //echo $record['subbilled'];exit;
                        $tobebilled = round($record['subs_val']-$record['subbilled'],2);
                        $total_without_tax=round(($bal/$plan_pers)*100,2); 
                        $total = round($total_without_tax*1.18,2);
                        $led_exp = $total+$cl;
                        $exp = $cl-$led_exp;
                        echo number_format($exp+$record['subs_val'],2);
                        $tobebilled2=round(abs($led_exp)-$record['billed']-$record['adv_val'],2);
                        $tobebilled2_without_tax = round(($tobebilled2/118)*100,2);
                        ?>
                        </td> 
                        <td align="right" style="background: #EEE9E9;"><?php  echo number_format(abs($tobebilled2+$tobebilled),2); ?></td>
                        <?php   ?>
                        <td style="background: #EEE9E9;" class="left_bord">
                            <?php if($tobebilled2>0) { ?><a href="add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=talk&amt=<?php echo round($tobebilled2_without_tax,2);?>">Credit</a><?php } ?>
                            <br/>
                            <?php if($tobebilled>0) {  ?><a href="add_bill?cost_center=<?php echo $record['cost_center'];?>&bill_type=subs&amt=<?php echo abs($tobebilled);?>">Subscription</a><?php }  ?>
                        </td>
                    </tr> 
                        
                    
                    <?php } ?>
                </table>
                
            </div>
	</div>
    </div>
</div>



<?php $this->Form->end();?>

