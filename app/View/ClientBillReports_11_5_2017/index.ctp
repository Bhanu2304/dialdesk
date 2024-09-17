<?php ?>
<script>
    function getPdf()
    {
        var url = "FromDate=" +$("#FromDate").val();
            url +="&ToDate=" +$("#ToDate").val();
            url +="&ClientId=<?php echo $this->Session->read('companyid');?>";
            //alert(url);return false;
        window.open ("<?php echo $this->webroot.'app/webroot/html2pdf/examples/exemple00.php?'; ?>"+url,'_blank');
        return false;
    }
</script>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2></h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <form method="Get" onsubmit="return getPdf()"  class="form-horizontal row-border" target="posthereonly">
                <div class="form-group">
                    <!--
                    <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <select id="ClientId" name="ClientId" class="form-control">
                            <option value="">Select Client</option>
                            <?php
                            foreach($data as $k=>$v)
                            {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    -->
                    <label class="col-sm-1 control-label">From</label>
                    <div class="col-sm-3">
                        <input type="text" id="FromDate" name="FromDate" placeholder="From Date" required="" class="form-control date-picker">
                    </div>
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-3">
                        <input type="text" id="ToDate" name="ToDate" placeholder="To Date" required="" class="form-control date-picker">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-2">
                        <input type="submit" value="submit"  class="btn-web btn"> 
                    </div>
                </div>    
                </form>
            </div>
        </div>
    </div>
</div>
