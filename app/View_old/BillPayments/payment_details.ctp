<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>BillPayments/payment_approval">Payment Details</a></li>
</ol>
<!--
<div class="page-heading">            
    <h1>View Client</h1>
</div>
-->
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Payment Details</h2>             
                <div class="panel-ctrls"></div>
            </div>
           
            <div class="panel-body no-padding">
                <div style="margin-left:20px;font-size: 18px;">Bill Approval - <?php echo $data['BillMonth'];?></div>
                <div class="form-group" style="margin-top:-10px;" >
                     <div class="col-sm-12"><hr/></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2"><b>Company Name:</b></div>
                    <div class="col-sm-4"><?php echo $data['CompanyName'];?></div>
                    <div class="col-sm-2"><b>Plan Name:</b></div>
                    <div class="col-sm-4"><?php echo $data['PlanName'];?></div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-2"><b>Plan Type:</b></div>
                    <div class="col-sm-4"><?php echo $data['PlanType'];?></div>
                    <div class="col-sm-2"><b>Payment Type:</b></div>
                    <div class="col-sm-4"><?php echo $data['PaymentType'];?></div>
                </div>
                
                <?php if($data['PaymentType'] =="Cheque"){?>              
                <div class="form-group">
                    <div class="col-sm-2"><b>Bank Name:</b></div>
                    <div class="col-sm-4"><?php echo $data['BankName'];?></div>
                    <div class="col-sm-2"><b>Account No:</b></div>
                    <div class="col-sm-4"><?php echo $data['AccountNo'];?></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2"><b>Pay Name:</b></div>
                    <div class="col-sm-4"><?php echo $data['PayName'];?></div>
                    <div class="col-sm-2"><b>Cheque No:</b></div>
                    <div class="col-sm-4"><?php echo $data['ChequeNo'];?></div>
                </div>
                <?php }?>
                
                <div class="form-group">
                    <?php if($data['PaymentType'] =="Online"){?> 
                    <div class="col-sm-2"><b>Transaction No:</b></div>
                    <div class="col-sm-4"><?php echo $data['TransactionNo'];?></div>
                    <?php }?>
                    <div class="col-sm-2"><b>Pay Amount:</b></div>
                    <div class="col-sm-4"><?php echo $data['PayAmount'];?></div>
                    <?php if($data['PaymentType'] =="Cheque"){?>     
                    <div class="col-sm-2"><b>Pay Date:</b></div>
                    <div class="col-sm-4"><?php echo $data['PayDate'];?></div>
                     <?php }?>
                </div>
                
                <?php if($data['PaymentType'] =="Online"){?>
                <div class="form-group">
                    <div class="col-sm-2"><b>Pay Date:</b></div>
                    <div class="col-sm-4"><?php echo $data['PayDate'];?></div> 
                </div>
                <?php }?>
                
                <?php echo $this->Form->create('BillPayments',array('action'=>'payment_approved')); ?> 
                <div class="form-group">
                     <div class="col-sm-12"><hr/></div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="Id" value="<?php echo $data['Id'];?>" >
                    <input type="hidden" name="Month" value="<?php echo $data['BillMonth'];?>" >
                    <input type="hidden" name="CompanyName" value="<?php echo $data['CompanyName'];?>" >
                    <input type="hidden" name="Email" value="<?php echo $data['CreatedEmail'];?>" >
                    <div class="col-sm-2"><input type="radio" value="1" required="" name="PaymentStatus"> Approve</div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2"><input type="radio" value="0" required="" name="PaymentStatus"> Not Approve</div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12"><textarea name="remarks" rows="5" cols="50" ></textarea></div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-1 control-label">&nbsp;</label>
                    <div class="col-sm-12">
                        <input type="submit" value="submit" class="btn-web btn pull-left"> 
                    </div>
                </div> 
                <?php echo $this->Form->end(); ?>
                
           
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>




