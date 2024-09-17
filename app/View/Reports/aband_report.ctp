<?php ?>
<script> 
function viewReport(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();

    if(fdate ==""){
       $("#FromDate").focus();
       alert('Please select first date.');
       return false;
    }
    else if(ldate ==""){
       $("#ToDate").focus();
       alert('Please select last date.');
       return false;
    }
    else{
        $.post('<?php echo $this->webroot?>Reports/export_aband_report',{fdate:fdate,ldate:ldate},function(data){
           $("#showCallReport").show();
           $("#ReportDetails").html(data);
           $("#loadingimg").hide();
        });   
    }  
}
</script>

<style>
.table-scroll table {
    display: flex;
    flex-flow: column;
    height: 400px;;
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
    <li class="active"><a href="#">Aband Call Report</a></li>
</ol>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Aband Call Report</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <form method="post" action="<?php echo $this->webroot;?>Reports/aband_report"  class="form-horizontal row-border" >
                    <div class="form-group">
                        <div class="col-sm-3">
                            <input type="text" id="FromDate" name="FromDate" placeholder="First Date" value="<?php echo $_REQUEST['FromDate'];?>" required="" class="form-control date-picker">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ToDate" name="ToDate" placeholder="Last Date" value="<?php echo $_REQUEST['ToDate'];?>" required="" class="form-control date-picker">
                        </div>
                        <div class="col-sm-6" style="margin-top:-12px;">
                            <input type="button"  class="btn btn-web" value="View" onclick="viewReport();" >
                            <input type="submit" class="btn btn-web" value="Export" >
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
