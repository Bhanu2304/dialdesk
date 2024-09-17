<?php ?>
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    
    if(fdate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>ClientRevenueReports/export_client_revewal_report');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>ClientRevenueReports/client_revewal_report');
        }
        $('#validate-form').submit();
        return true;
    }
}

$(function () {
    $( ".date-picker1" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: '-100:+0' });
});

</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Client Billing</a></li>
    <li class="active"><a href="#">Renewal Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Client Renewal Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Client Renewal Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('ClientRevenueReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Invoice Due Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'Invoice Due Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker1'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                           <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php  if(isset($client_arr) && !empty($client_arr)){  ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW CLIENT RENEWAL REPORTS</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <!-- <div class="panel-heading">
                   <h1><?php //echo $sub_month_str;?></h1>
                </div> -->
                    <table class="table">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Client Name</th>
                            <th>Subs Amount</th>
                            <th>Credit Value</th>
                            <th>Payment Mode</th>
                            <th>Renewal Date</th> 
                            <th>Invoice Due Date</th>  
                            <th>Client Status</th>  
                            <th>Collection Status</th>
                        </tr>
                        <?php $i=1;
                        $total_subscription = 0;
                        $total_credit = 0;
                        $total_collection = 0;
                        foreach($client_arr as $client=>$det)
                        {
                         ?>
                         <tr>
                         <td><?php echo $i++; ?></td>
                            <td><?php echo $client; ?></td>
                            <td><?php echo number_format($det['subs_amount'],2); ?></td>
                            <td><?php echo number_format($det['credit_value'],2); ?></td>
                            <td><?php echo $det['PeriodType']=='Quater'?'Quarter':$det['PeriodType']; ?></td>
                            <td><?php echo $det['renew_date']; ?></td>
                            <td><?php echo $det['invoice_due_date']; ?></td>
                            <td><?php if($det['client_status'] == 'A'){echo 'Active';}else if($det['client_status'] == 'H'){echo 'Hold';} ?></td>
                            <td><?php echo number_format($det['collection_status'],2); ?></td>
                        </tr>
                        <?php $total_subscription+=$det['subs_amount'];
                            $total_credit+=$det['credit_value'];
                            $total_collection+=$det['collection_status'];
                    } ?> 
                        <tr>
                        <th></th>
                        <th>Total</th>
                        <th><?php echo number_format($total_subscription,2); ?></th>
                        <th><?php echo number_format($total_credit,2); ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><?php echo number_format($total_collection,2); ?></th>
                        </tr>
                    </table>             
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>
