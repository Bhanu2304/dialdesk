<?php ?>
<script>
    function getPdf(){
        var url ="ClientId=" +$("#ClientId").val();
            window.open ("<?php echo $this->webroot.'billing_reports/get_billing_reports?'; ?>"+url,'_blank');
            return false;
    }
     
    
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Billing Reports</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Bill Statement As on Current Month</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <form method="Get"  class="form-horizontal row-border" onsubmit="return getPdf()" target="posthereonly">
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Type</label>
                        <div class="col-sm-3">
                            <select id="ClientId" name="ClientId"  class="form-control" required="" >
                                <option value="All">All</option>
                                <option value="Only Negative">Only Negative</option> 
                            </select>
                        </div>
                        
                        <div class="col-sm-2">
                            <input type="submit" value="Export" class="btn-web btn"> 
                            <!--
                            <input type="button" value="View Invoice" onclick="getPrepaidInvoice()" class="btn-web btn"> 
                            -->
                        </div>
                        
                    </div>
                    <div class="form-group">
                        
                    </div>    
                </form>
            </div>
        </div>
            
            
            
        
            
            
    </div>
</div>
