<?php ?>
<ol class="breadcrumb">                                
    <li><a href="#">Home</a></li>
    <li><a >Bill Statement</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>ClientBillReports/view_padunpad_payment">Pad Unpad Amount</a></li>
</ol>
<!--
<div class="page-heading">            
    <h1>Payment Submition</h1>
</div>
-->

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Pad/Unpad Amount</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Bill No</th>
                            <th>Bill Month</th> 
                            <th>Total Due</th>
                            <th>Pay Amount</th>
                            <th>Balance Amount</th>
                            <th>Pay Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($padunpad_payment as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['BillMasterPost']['Id'];?></td>
                            <td><?php echo $row['BillMasterPost']['BillStartDate']." To ".$row['BillMasterPost']['BillEndDate'];?></td>
                            <td><?php echo ($row['BillMasterPost']['krishiTax']+$row['BillMasterPost']['sbcTax']+$row['BillMasterPost']['serviceTax']+$row['BillMasterPost']['CurrentCharge']+$row['BillMasterPost']['LastCarriedAmount']-$row['BillMasterPost']['paymentPaid']);?></td>
                            <td><?php echo $row['BillMasterPost']['paymentPaid'];?></td>
                            <td><?php echo round($row['BillMasterPost']['LastCarriedAmount']-$row['BillMasterPost']['paymentPaid'],2); ?></td>
                            <td><?php echo $row['BillMasterPost']['paymentDate'];?></td>
                            <td><?php if($row['BillMasterPost']['paymentStatus'] =='1'){echo "Pad";}else{echo "Unpad";}?></td>
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

