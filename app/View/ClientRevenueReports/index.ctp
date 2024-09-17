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
                    <label class="col-sm-1 control-label">Year</label>
                    <div class="col-sm-2">
                        <select id="FromDate" name="FromDate" required="" class="form-control">
                            <?php foreach($finance_year as $year) { ?>
                                <option value="<?php echo $year;?>" <?php if($current_year== $year) echo 'selected'; ?>><?php echo $year;?></option>
                            <?php } ?>    
                        </select>    
                    </div>
                    <label class="col-sm-1 control-label">Month</label>
                    <div class="col-sm-2">
                    <select id="ToDate" name="ToDate" required="" class="form-control">
                            <?php foreach($month_arr as $month) { ?>
                                <option value="<?php echo $month;?>" <?php if($current_month==$month) echo 'selected'; ?>><?php echo $month;?></option>
                            <?php } ?>    
                        </select> 
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
