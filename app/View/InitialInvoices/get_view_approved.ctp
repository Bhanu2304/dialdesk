<?php //print_r($tbl_invoice); ?>

<table class="table">
        <?php $case=array('primary','success','info','danger'); $i=0; ?>
    <tbody>
            <tr class="active" align="center">
                    <td>Sr. No.</td>
                    <td>Branch Name</td>
                    <td>Proforma No.</td>
                    <td>Client</td>
                    <td>Month</td>
                    <td>Category</td>
                    <td>Amount</td>
                    <td>Incl. GST</td>
                    <td>PDF</td>
                    
            </tr>
            <?php $i=1; foreach ($tbl_invoice as $post): ?>
            <tr  align="center">
                    <?php $id= $post['InitialInvoice']['id']; ?>
                    <td><?php echo $i++; ?></code></td>
                    <td><?php echo $post['InitialInvoice']['branch_name']; ?></td>
                    <td><?php echo $post['InitialInvoice']['proforma_bill_no']; ?></td>                                                       
                    <td><?php echo $post['cm']['client']; ?></td>
                    <td><?php echo $post['InitialInvoice']['month']; ?></td>
                    <td><?php echo $post['InitialInvoice']['category']; ?></td>
                    <td><?php echo $post['InitialInvoice']['total']; ?></td>
                    <td><?php echo $post['InitialInvoice']['grnd']; ?></td>
                    <td><?php echo $this->Html->link(__('PDF'), array('controller'=>'InitialInvoices','action' => 'view_pdf','?'=>array('id'=>base64_encode($id)), 'ext' => 'pdf', 'ProformaInvoice'),array('target'=>'_blank')); ?></td>

                    
            </tr>
            <?php endforeach; ?>
            <?php unset($InitialInvoice); ?>
    </tbody>
</table>						
