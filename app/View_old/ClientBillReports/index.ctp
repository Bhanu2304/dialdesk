<?php ?>

<script>
    function getPdf(){
        
        var d11 = new Date($("#sd").val());
        var d22 = new Date($("#ed").val());
        
        var d1 = new Date($("#FromDate").val());
        var d2 = new Date($("#ToDate").val());
        
        if(d1.getTime() < d11.getTime()){
            alert('Please select current start date.');
            return false;
        }
        else if(d2.getTime() > d22.getTime()){
            alert('Please select current end date.');
            return false;
        }
        else{

        
        var url = "FromDate=" +$("#FromDate").val();
            url +="&ToDate=" +$("#ToDate").val();
            url +="&ClientId=<?php echo $this->Session->read('companyid');?>";
        window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_tables.php?'; ?>"+url,'_blank');
        return false;
        }
    }
    
    function getPdf1(){
        
        var d11 = new Date($("#sd").val());
        var d22 = new Date($("#ed").val());
        
        var d1 = new Date($("#FromDate").val());
        var d2 = new Date($("#ToDate").val());
        
        var url = "FromDate=" +$("#FromDate").val();
            url +="&ToDate=" +$("#ToDate").val();
            url +="&ClientId=<?php echo $this->Session->read('companyid');?>";
             
        window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_tables_dialer.php?';?>"+url,'_blank');
    }
    
    function getInvoice(){
        var url ="&ClientId=<?php echo $this->Session->read('companyid');?>";
        window.open ("<?php echo $this->webroot.'ClientBillReports/view_invoice/example.pdf?'; ?>"+url,'_blank');
        return false;   
    }
    
    function getPrepaidInvoice(){
        var url ="&ClientId=<?php echo $this->Session->read('companyid');?>";
        window.open ("<?php echo $this->webroot.'ClientBillReports/view_prepaid_invoice/example.pdf?'; ?>"+url,'_blank');
        return false;   
    }
    
    function getOldBill(){
        var data = $('#OldBillDate').val();
        var arr = data.split('__');
       
        var url = "FromDate=" +arr[0];
            url +="&ToDate=" +arr[1];
            url +="&ClientId=<?php echo $this->Session->read('companyid');?>";
        window.open ("<?php echo $this->webroot.'app/webroot/billing_statement/billing_tables.php?'; ?>"+url);
        return false;
    }
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Billing Statement</a></li>
    <li class="active"><a href="#">Statement</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            
            <?php if($result['BalanceMaster']['CrmTagStatus'] =="Yes"){ ?>
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Current Bill Statement</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
           
                <?php if(!empty($result) && $result['BalanceMaster']['PlanType'] =="Prepaid"){?>
                    
                <form method="Get" onsubmit="return getPdf()"  class="form-horizontal row-border" target="posthereonly" >
                    <input type="hidden" id="sd" value="<?php echo $result['BalanceMaster']['start_date'];?>" >
                     <input type="hidden" id="ed" value="<?php echo $result['BalanceMaster']['end_date'];?>" >
                <div class="form-group">
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
                    <div class="col-sm-6">
                        <input type="submit" value="submit"  class="btn-web btn">
                        <!--
                        <input type="button" value="View Invoice" onclick="getPrepaidInvoice()" class="btn-web btn"> 
                        -->
                    </div>
                </div>    
                </form>
                
                <?php }else{?>
                    <input type="button" value="View Invoice" onclick="getInvoice()" class="btn-web btn"> 
                <?php }?>
                    
            </div>
        </div>
         <?php }else{?> 
            
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Current Bill Statement</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
           
                <?php if(!empty($result) && $result['BalanceMaster']['PlanType'] =="Prepaid"){?>
                    
                <form method="Get" onsubmit="return getPdf1()"  class="form-horizontal row-border" target="posthereonly" >
                    <input type="hidden" id="sd" value="<?php echo $result['BalanceMaster']['start_date'];?>" >
                     <input type="hidden" id="ed" value="<?php echo $result['BalanceMaster']['end_date'];?>" >
                <div class="form-group">
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
                    <div class="col-sm-6">
                        <input type="submit" value="submit"  class="btn-web btn">
                    </div>
                </div>    
                </form>
                <?php }?>   
            </div>
        </div>
        <?php }?>  
            
            
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Old Bill Statement</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
           
                <?php if(!empty($result) && $result['BalanceMaster']['PlanType'] =="Prepaid"){?>  
                    <form method="Get" onsubmit="return getOldBill()"  class="form-horizontal row-border" target="posthereonly">
                    <div class="form-group">
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
                            <input type="submit" value="submit"  class="btn-web btn">
                        </div>
                    </div>    
                    </form>
    
                <?php }else{?>
                    <input type="button" value="View Invoice" onclick="getInvoice()" class="btn-web btn"> 
                <?php }?>
                    
            </div>
        </div>
            
            
    </div>
</div>
