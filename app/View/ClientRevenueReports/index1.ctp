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
    <li class="active"><a href="#">Revenue Report</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Client Revenue Reports</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <form method="Get"  class="form-horizontal row-border" onsubmit="return getPdf()" target="posthereonly">
                <div class="form-group">
                    <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <select id="ClientId" name="ClientId"  class="form-control" required="" >
                            <option value="">Select Client</option>
                            <option value="All">All</option>
                            <?php
                            foreach($data as $k=>$v)
                            {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-3">
                        <!-- <input type="text" autocomplete="off" id="FromDate" name="FromDate" placeholder="From Date" required="" class="form-control date-picker1"> -->
                        <?php	echo $this->Form->input('FromDate', array('label'=>false,'id'=>'FromDate','class'=>'form-control','options' => $finance_yearNew,'empty' => 'Select','required'=>true)); ?>
                    </div>
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-3">
                        <input type="text" autocomplete="off" id="ToDate" name="ToDate" placeholder="To Date" required="" class="form-control date-picker1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-6">
                        <input type="submit" value="submit" class="btn-web btn"> 
                        <!--
                        <input type="button" value="View Invoice" onclick="getPrepaidInvoice()" class="btn-web btn"> 
                        -->
                    </div>
                </div>    
                </form>
            </div>
        </div>
            
            
            
       
            
            
    </div>
</div>
