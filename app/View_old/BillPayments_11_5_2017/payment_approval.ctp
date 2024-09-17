<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>BillPayments/payment_approval">Payment Submition</a></li>
</ol>
<!--
<div class="page-heading">            
    <h1>Payment Approval</h1>
</div>
-->
<div id="error" style="color:green;font-size: 15px;margin-left: 16px;"><?php echo $this->Session->flash();?></div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Payment Approval</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>Bill Month</th>
                            <th>COMPANY NAME</th>
                            <th>PLAN NAME</th>
                            <th>PLAN TYPE</th>
                            <th>PAYMENT TYPE</th>
                            <th>PAY AMOUNT</th>
                            <th>PAY DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $val='no';?>
                    <?php foreach($result as $row){?>
                  	<tr>
                        <td><?php echo $row['PaymentMaster']['BillMonth'];?></td>
                      	<td><?php echo $row['PaymentMaster']['CompanyName'];?></td>
                        <td><?php echo $row['PaymentMaster']['PlanName'];?></td>
                        <td><?php echo $row['PaymentMaster']['PlanType'];?></td>
                        <td><?php echo $row['PaymentMaster']['PaymentType'];?></td>
                        <td><?php echo $row['PaymentMaster']['PayAmount'];?></td>
                        <td><?php echo $row['PaymentMaster']['PayDate'];?></td>
                        <td>
                            <a href="<?php echo $this->webroot;?>BillPayments/payment_details?id=<?php echo $row['PaymentMaster']['Id'];?>">
                                <label class="btn btn-xs btn-midnightblue btn-raised"><i title="Edit" class="fa fa-edit"></i><div class="ripple-container"></div></label>
                            </a>
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




