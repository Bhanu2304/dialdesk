<?php ?>
<script>
function getPadUnpad(ClientId){
    if(ClientId !=""){
        window.location.href = "<?php echo $this->webroot;?>BillPayments/view_padunpad_payment?cid="+ClientId;
    }
    else{
       window.location.href = "<?php echo $this->webroot;?>BillPayments/view_padunpad_payment"; 
    }
}
</script>


<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Submit Payment Details</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>BillPayments">Submit Payment Details</a></li>
</ol>
<!--
<div class="page-heading">            
    <h1>Payment Submition</h1>
</div>
-->


<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            
            <div class="panel-heading">
                 <h2>View Submit Payment Details</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
          
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('ClientId',array('label'=>false,'options'=>$ClientName,'empty'=>'Select Client','value'=>isset($BillClient)?$BillClient:"",'onchange'=>'getPadUnpad(this.value)','id'=>'ClientId','class'=>'form-control'));?>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Pad Amount</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Plan Name</th>
                            <th>Plan Type</th> 
                            <th>Pay Amount</th>
                            <th>Pay Type</th>
                            <th>Pay Date</th>
                            <th>Approve Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['PaymentMaster']['PlanName'];?></td>
                            <td><?php echo $row['PaymentMaster']['PlanType'];?></td>
                            <td><?php echo $row['PaymentMaster']['PayAmount'];?></td>                          
                            <td><?php echo $row['PaymentMaster']['PaymentType'];?></td>
                            <td><?php echo $row['PaymentMaster']['PayDate'];?></td>
                            <td><?php echo $row['PaymentMaster']['ApproveDate'];?></td>
                        </tr>

			</td>  
                    </tr>
                    <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>

