<?php ?>
<script>
    $( function() {
            $( ".date-picker1" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: '-100:+0' });
          });
    function getPdf(){
        var url = "FromDate=" +$("#FromDate").val();
            url +="&ToDate=" +$("#ToDate").val();
            url +="&ClientId=" +$("#ClientId").val();
            window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_revenue2.php?'; ?>"+url,'_blank');
            return false;
    }
    
    
        
    
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Client Billing</a></li>
    <li class="active"><a href="#">Renewal Report</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Client Renewal Reports</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <h1><?php echo $sub_month_str;?></h1>
                    <table class="table">
                        <tr>
                        <th>Sr. No.</th>
                            <th>Client</th>
                            <th>Payment Mode</th>
                            <th>Subs Amount</th>
                            <th>Credit Value</th>
                            <th>Renewal Date</th>    
                        </tr>
                        <?php $i=1;
                        foreach($client_arr as $client=>$det)
                        {
                         ?>
                         <tr>
                         <td><?php echo $i++; ?></td>
                            <td><?php echo $client; ?></td>
                            <td><?php echo $det['PeriodType']=='Quater'?'Quarter':$det['PeriodType']; ?></td>
                            <td><?php echo $det['subs_amount']; ?></td>
                            <td><?php echo $det['credit_value']; ?></td>
                            <td><?php echo $det['renew_date']; ?></td>
                        </tr>
                        <?php } ?> 
                    </table>
            </div>
        </div>
            
            
            
       
            
            
    </div>
</div>
