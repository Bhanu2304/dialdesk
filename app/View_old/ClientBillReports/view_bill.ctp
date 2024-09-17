<?php ?>
<script>
    function getPdf(){
        
         //var url ="&ClientId=<?php echo $this->Session->read('companyid');?>"+"&BillMonth="+$("#BillMonth").val();
         var url ="&ClientId=<?php echo $this->Session->read('companyid');?>";
         
            //alert(url);
        window.open ("<?php echo $this->webroot.'ClientBillReports/view_invoice/example.pdf?'; ?>"+url,'_blank');
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
                    <form method="Get"  class="form-horizontal row-border" onsubmit="return getPdf()" target="posthereonly">
                        <div class="form-group" style="margin-top: -20px;" >
                    <!--
                    <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <select id="ClientId" name="ClientId" class="form-control" onchange="getBillMonth(this.value)" required="" >
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
                    <!--
                    <label class="col-sm-1 control-label">Bill Month</label>
                    <div class="col-sm-3">
                        <select id="BillMonth" name="BillMonth" class="form-control" required="" >
                            <option value="">Select Bill Month</option>
                            <?php
                            foreach($result as $val){
                                $BillMonth = $val['BillMasterPost']['BillStartDate']." To ".$val['BillMasterPost']['BillEndDate'];
                                $fd    = $val['BillMasterPost']['BillStartDate'];
                                $ld    = $val['BillMasterPost']['BillEndDate'];
                                echo "<option value='{$fd}_{$ld}'>$BillMonth</option>";
                            }
                            ?>
                        </select>
                    </div>
                    -->
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-2">
                        <input type="submit" value="View Invoice"  class="btn-web btn"> 
                    </div>
                </div>    
                </form>
            </div>
        </div>
    </div>
</div>
