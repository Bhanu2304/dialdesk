<?php ?>
<script>
    function getPdf(){
        var url = "FromDate=" +$("#FromDate").val();
            url +="&ToDate=" +$("#ToDate").val();
            url +="&ClientId=" +$("#ClientId").val();
            
            $.post('<?php echo $this->webroot.'BillingReports/get_tagging_status';?>',{ClientId:$("#ClientId").val()},function(data){
                
                if(data =="Yes"){
                    window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_statement_plan_wise_new.php?'; ?>"+url,'_blank');
                }
                else{
                    window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_statement_plan_wise_new.php?'; ?>"+url,'_blank');
                }
            });
           
            
            return false;
    }
    function getPrepaidInvoice(){
        var ClientId=$("#ClientId").val();
        if(ClientId !=""){
            var url ="&ClientId="+ClientId;
            window.open ("<?php echo $this->webroot.'BillingReports/view_prepaid_invoice/example.pdf?'; ?>"+url,'_blank');
            return false;
        }
        else{
            alert('Please select client.');
            $("#ClientId").focus();
            return false;
        }
    }
    
     function getOldBill(){
        var data = $('#OldBillDate').val();
        var arr = data.split('__');
 
        var url = "FromDate=" +arr[0];
            url +="&ToDate=" +arr[1];
            url +="&ClientId=" +$("#ClientIdOld").val();
            //alert(url);
        //window.open ("<?php echo $this->webroot.'app/webroot/html2pdf/examples/exemple00.php?'; ?>"+url,'_blank');
        //window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/examples/example05_tables.php?'; ?>"+url,'_blank');
        window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_tables.php?'; ?>"+url,'_blank');
        return false;
    }
    
    function getBillDate(ClientId){
        $.post('<?php echo $this->webroot.'BillingReports/get_oldbill_date';?>',{ClientId:ClientId},function(data){
            $('#OldBillDate').html(data);   
           }
        );
    }    
    
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Billing Statement</a></li>
    <li class="active"><a href="#">Statement</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Current Bill Statement</h2>
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
                        <input type="text" autocomplete="off" id="FromDate" name="FromDate" placeholder="From Date" required="" class="form-control date-picker">
                    </div>
                    <label class="col-sm-1 control-label">To</label>
                    <div class="col-sm-3">
                        <input type="text" autocomplete="off" id="ToDate" name="ToDate" placeholder="To Date" required="" class="form-control date-picker">
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
            
            
            
       <!-- <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                     <h2>Old Bill Statement</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <form method="Get"  class="form-horizontal row-border" onsubmit="return getOldBill()" target="posthereonly">
                <div class="form-group">
                    <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <select id="ClientIdOld" name="ClientIdOld"  onchange="getBillDate(this.value)" class="form-control" required="" >
                            <option value="">Select Client</option>
                            <?php
                            foreach($data as $k=>$v)
                            {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                     <label class="col-sm-1 control-label">Date</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="OldBillDate" id="OldBillDate" required=""  >
                                <option value="">Select Date</option>
                                <?php foreach ($oldbill as $value){?>
                                <option value="<?php echo $value['BalanceMasterHistory']['start_date'].'__'.$value['BalanceMasterHistory']['end_date'];?>"><?php echo $value['BalanceMasterHistory']['start_date'].' To '.$value['BalanceMasterHistory']['end_date'];?></option>
                                <?php }?>
                            </select>
                        </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-6">
                        <input type="submit" value="submit" class="btn-web btn"> 
               
                        <input type="button" value="View Invoice" onclick="getPrepaidInvoice()" class="btn-web btn"> 
                       
                    </div>
                </div>    
                </form>
            </div>
        </div>-->
            
            
    </div>
</div>
