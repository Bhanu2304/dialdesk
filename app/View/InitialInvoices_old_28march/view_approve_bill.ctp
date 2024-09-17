<?php foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
	endforeach;
?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Invoice</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Billing">Approve Invoice</a></li>
</ol>
<div class="page-heading">            
    <h1>Approve Invoice</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            
            <div class="panel-body" style="margin-top:-10px;">
	        <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
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
                    <td>Action</td>
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
                    <td><?php echo $this->Html->link(__('PDF'), array('controller'=>'InitialInvoices','action' => 'view_proforma_pdf','?'=>array('id'=>base64_encode($id)), 'ext' => 'pdf', 'ProformaInvoice'),array('target'=>'_blank')); ?>
                    <?php echo $this->Html->link('View',
                    array('controller'=>'InitialInvoices','action'=>'view_bill','?'=>array('id'=>$id,'back_url'=>'view_approve_bill'),'full_base' => true)); ?>
                        </td>
                    <td>
                            
                            <?php echo $this->Html->link('Approve',
                    array('controller'=>'InitialInvoices','action'=>'edit_bill','?'=>array('id'=>$id),'full_base' => true)); ?>


                    </td>
            </tr>
            <?php endforeach; ?>
            
    </tbody>
</table>						

                
            </div>
	</div>
    </div>
</div>

  