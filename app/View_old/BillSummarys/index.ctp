<?php ?>
<script> 
function viewReport(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();
    
    $.post('<?php echo $this->webroot?>BillSummarys/view_report',{fdate:fdate,ldate:ldate},function(data){
       $("#showCallReport").show();
       $("#ReportDetails").html(data);
       $("#loadingimg").hide();
    });  
}

function renewalDetails(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();
    
    $.post('<?php echo $this->webroot?>BillSummarys/renewal_report',{fdate:fdate,ldate:ldate},function(data){
       $("#showCallReport").show();
       $("#ReportDetails").html(data);
       $("#loadingimg").hide();
    });  
}

function OldBillReport(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();
    
    $.post('<?php echo $this->webroot?>BillSummarys/old_report',{fdate:fdate,ldate:ldate},function(data){
       $("#showCallReport").show();
       $("#ReportDetails").html(data);
       $("#loadingimg").hide();
    });  
}

function OldRenewalReport(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();
    
    $.post('<?php echo $this->webroot?>BillSummarys/old_renewal_report',{fdate:fdate,ldate:ldate},function(data){
       $("#showCallReport").show();
       $("#ReportDetails").html(data);
       $("#loadingimg").hide();
    });  
}

function BalanceReport(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();
    
    $.post('<?php echo $this->webroot?>BillSummarys/balance_report',{fdate:fdate,ldate:ldate},function(data){
       $("#showCallReport").show();
       $("#ReportDetails").html(data);
       $("#loadingimg").hide();
    });  
}

</script>


<style>
.table-scroll table {
    display: flex;
    flex-flow: column;
    height:470px;;
}

.table-scroll table thead {
    flex: 0 0 auto;
    width: calc(100% - 0.9em);
}

.table-scroll table tbody {
    flex: 1 1 auto;
    display: block;
    max-height: 300%;
    overflow-y: scroll;

}

.table-scroll table tbody tr {
    width: 100%;
}

.table-scroll table thead,.table-scroll table tbody tr {
    display: table;
    table-layout: fixed;
}
</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Report</a></li>
    <li class="active"><a href="#">Client Wise Bill Summary</a></li>
</ol>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Client Wise Bill Summary</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body" >
                <form method="post" action="<?php echo $this->webroot;?>BillSummarys"  class="form-horizontal row-border" >
                    <div class="form-group"  >
                        <!--
                        <div class="col-sm-3">
                            <input type="text" id="FromDate" name="FromDate" placeholder="First Date" required="" class="form-control date-picker">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ToDate" name="ToDate" placeholder="Last Date" required="" class="form-control date-picker">
                        </div>
                        -->
                        <div class="col-sm-12" style="margin-top:-50px;">
                            <input type="button"  class="btn btn-web" value="Bill Summary" onclick="viewReport();" >
                            <input type="submit" class="btn btn-web" value="Bill Export">
                            <input type="button"  class="btn btn-web" value="Bill History" onclick="OldBillReport();" >
                            <input type="button"  class="btn btn-web" value="Renewal History" onclick="renewalDetails();" >
                            <!--
                            <input type="button"  class="btn btn-web" value="Renewal History" onclick="OldRenewalReport();" >
                            -->
                            <input type="button"  class="btn btn-web" value="Balance History" onclick="BalanceReport();" >
                            
                            <img id="loadingimg" style="display:none;" src="<?php echo $this->webroot;?>images/loading.gif" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="showCallReport" style="display:none;" >
        <div class="panel panel-default" data-widget='{"draggable": "false"}'  >
            <div class="panel-heading">
                <h2>Details</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <div id="ReportDetails" class="table-scroll" ></div>
            </div>
        </div>
        </div>
    </div>
</div>
