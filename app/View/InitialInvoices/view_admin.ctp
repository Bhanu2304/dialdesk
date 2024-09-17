<?php //print_r($res); ?>

<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'edit')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar"><i class="fa fa-bars"></i></a>
        <ol class="breadcrumb pull-left"></ol>
            <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
            </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i><span>View Invoice To Add PO No.</span>
		</div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                        <?php $case=array('active',''); $i=0; ?>
                        <thead>
                            <tr class="active" align="center">
                                <td>Sr. No.</td>
                                <td>Finance Year</td>
                                <td>Branch Name</td>
                                <td>Invoice No.</td>
                                <td>Amount</td>
                                <td>Invoice Description</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tbl_invoice as $post): ?>
                            <tr class="<?php  echo $case[$i%2]; $i++;?>" align="center">
                                <?php $id= $post['InitialInvoice']['id']; ?>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $post['InitialInvoice']['finance_year']; ?></td>
                                <td><?php echo $post['InitialInvoice']['branch_name']; ?></td>
                                <td><?php echo $post['InitialInvoice']['bill_no']; ?></td>
                                <td><?php echo $post['InitialInvoice']['total']; ?></td>
                                <td><?php echo $post['InitialInvoice']['invoiceDescription']; ?></td>
                                <td><?php echo $this->Html->link('view',array('controller'=>'InitialInvoices','action'=>'view_forpo','?'=>array('id'=>$id),'full_base' => true)); ?></td>
                            </tr>
                            <?php endforeach; unset($InitialInvoice); ?>
                        </tbody>
                    </table>						
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>