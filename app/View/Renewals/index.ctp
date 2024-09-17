<?php ?>
<script> 
function getPlan(type){
    $("#newplan").hide();
    if(type ==='Different Plan'){
        $("#newplan").show();
    }
}

function validateform(){
    var type= $("#type").val();
    var newplan= $("#newplan").val();
    var paymentType= $("#paymentType").val();
    var paymentDate= $("#paymentDate").val();
    var clientstatus= $("#clientstatus").val();
    var NotFollow= $("#NotFollow").val();
  
    var date1 = new Date();
    var date2 = new Date(paymentDate);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
    
    if(type ==='Different Plan' && newplan  ===''){
        alert('Please select new plan.');
        return false;
    }
    else if(paymentType ==='PTP' && diffDays > '30' ){
        alert('PTP date allow only for 30 days.');
        return false;
    }
    else if(document.getElementById('NotFollow').checked == true && clientstatus !='D'){
        alert('Not follow use only for deactive account.');
        return false;
    }
    else{
        return true;
    }
}

function validateUpdateStatus(){
    var paymentReceive= $("#paymentReceive").val();
    var payDate= $("#payDate").val();
    var ptpcount= $("#ptpcount").val();
    
    var d1 = new Date();
    var d2 = new Date(payDate);
    
    var timeDiff = Math.abs(d2.getTime() - d1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
    
    if(paymentReceive ==='No' && diffDays > '30'){
        alert('PTP date allow only for 30 days.');
        return false;
    }
    else if(paymentReceive ==='No' && ptpcount > '2' && d2.getTime() > d1.getTime()){
        alert('Sory PTP can use only 3 times.');
        return false;
    }
    else{
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Renewal</a></li>
    <li class="active"><a href="#">Renewal Plan</a></li>
</ol>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div style="color:red;font-size: 16px;margin-buttom:10px;"><?php echo $this->Session->flash();?></div>
        <div class="row">
            <?php if($_REQUEST['view'] =='renewal'){?>
            <div class=" col-md-4">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Renewal Plan</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body" >
                       
                        <?php echo $this->Form->create('Renewals',array('action'=>'index','id'=>'validate-form','onsubmit'=>'return validateform()','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                            <input type="hidden" name="clientid" value="<?php echo isset($_REQUEST['clientid'])?$_REQUEST['clientid']:''?>" >
                            <input type="hidden" name="oldplan" value="<?php echo isset($_REQUEST['planid'])?$_REQUEST['planid']:''?>" >
                            <input type="hidden" name="view" value="<?php echo isset($_REQUEST['view'])?$_REQUEST['view']:''?>" >
                            <input type="hidden" name="clientstatus" id="clientstatus" value="<?php echo isset($ClientArray['status'])?$ClientArray['status']:''?>" >
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="form-control" id="paymentType" name="paymentTypes" required="">
                                        <option value="">Payment Type</option>
                                        <option value="PTP">PTP</option>
                                        <option value="Payment">Payment</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"  >
                                <div class="col-sm-12">
                                    <input type="text" class="form-control date-picker" required=""  name="paymentDate" id="paymentDate" placeholder="Select Date" >
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-sm-12">
                                    <textarea  class="form-control" placeholder="Remarks" name="paymentRemark" id="paymentRemark" required="" ></textarea>
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-sm-12">
                                    Not Follow Bill Cycle <input type="checkbox" id="NotFollow" value="NF" name='BillCycle' >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="form-control" required="" name="planMode" onchange="getPlan(this.value)" name="type" id="type" >
                                        <option value="">Select Plan Mode</option>
                                        <option value="Same Plan">Same Plan</option>
                                        <option value="Different Plan">Different Plan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="form-control" name="newplan" id="newplan" style="display:none;" >
                                        <option value="">Select Plan</option>
                                        <?php foreach ($PlanName as $key=>$value){?>
                                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                          <div class="form-group">     
                                <div class="col-sm-12" style="margin-top:-10px;">
                                   <input type="submit" class="btn btn-web pull-right"  value="Submit">
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>  
                    </div>
                </div>
            </div>
            <?php }?>
            
            <?php if($_REQUEST['view'] =='balance'){?>
            <div class=" col-md-4">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Add Balance</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body" >
                        <?php echo $this->Form->create('Renewals',array('action'=>'addbalance','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                            <input type="hidden" name="clientid" value="<?php echo isset($_REQUEST['clientid'])?$_REQUEST['clientid']:''?>" >
                            <input type="hidden" name="oldplan" value="<?php echo isset($_REQUEST['planid'])?$_REQUEST['planid']:''?>" >
                            <input type="hidden" name="view" value="<?php echo isset($_REQUEST['view'])?$_REQUEST['view']:''?>" >
                            
                       
                            <div class="form-group"  >
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" required="" name="BalanceAmount" placeholder="Balance Amount" >
                                </div>
                            </div>
                            
                            <div class="form-group" >
                                <div class="col-sm-12">
                                    <textarea  class="form-control" placeholder="Remarks" name="Remark" ></textarea>
                                </div>
                            </div>

                            <div class="form-group">     
                                <div class="col-sm-12" style="margin-top:-10px;">
                                   <input type="submit" class="btn btn-web pull-right"  value="Submit">
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>  
                    </div>
                </div>
            </div>
            <?php }?>
            <?php if($_REQUEST['view'] =='update'){?>
            <div class=" col-md-4">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Update Payment Status</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body" >
                        <?php echo $this->Form->create('Renewals',array('action'=>'update_status','id'=>'validate-form','onsubmit'=>'return validateUpdateStatus()','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                            <input type="hidden" name="clientid" value="<?php echo isset($_REQUEST['clientid'])?$_REQUEST['clientid']:''?>" >
                            <input type="hidden" name="oldplan" value="<?php echo isset($_REQUEST['planid'])?$_REQUEST['planid']:''?>" >
                            <input type="hidden" id="ptpcount" name="ptpcount" value="<?php echo isset($_REQUEST['ptpcnt'])?$_REQUEST['ptpcnt']:''?>" >
                            <input type="hidden" name="view" value="<?php echo isset($_REQUEST['view'])?$_REQUEST['view']:''?>" >
                            <input type="hidden" name="Id" value="<?php echo isset($_REQUEST['id'])?$_REQUEST['id']:''?>" >
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <select class="form-control" id="paymentReceive" name="paymentReceive" required="">
                                        <option value="">Payment Receive</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"  >
                                <div class="col-sm-12">
                                    <input type="text" class="form-control date-picker" required=""  name="payDate" id="payDate" placeholder="Select Date" >
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-sm-12">
                                    <textarea  class="form-control" placeholder="Remarks" name="payRemark" id="payRemark" required="" ></textarea>
                                </div>
                            </div>

                            <div class="form-group">     
                                <div class="col-sm-12" style="margin-top:-10px;">
                                    <input type="submit" class="btn btn-web pull-right"  value="Submit">
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>  
                    </div>
                </div>
            </div>
            <?php }?>
         </div>
    </div>
</div>
