


<?php 
    $Year = date('y');
    $NextYear = $Year+1;
    $months = array("Jan-$NextYear"=>"Jan","Feb-$NextYear"=>"Feb","Mar-$NextYear"=>"Mar",
        "Apr-$Year"=>"Apr","May-$Year"=>"May","Jun-$Year"=>"Jun","Jul-$Year"=>"Jul",
        "Aug-$Year"=>"Aug","Sep-$Year"=>"Sep","Oct-$Year"=>"Oct","Nov-$Year"=>"Nov","Dec-$Year"=>"Dec");
    ?>
<div class="row">
<div id="breadcrumb" class="col-xs-12">
<a href="#" class="show-sidebar">
        <i class="fa fa-bars"></i>
</a>
<ol class="breadcrumb pull-left">
</ol>
</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
                    <h4 class="page-header">Invoice Approval Stage</h4>
                    <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                    <div class="box">
                        <?php echo $this->Form->create('InitialInvoice');  ?>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Branch</label>
                                <div class="col-sm-3">
                                <?php echo $this->Form->input('branch_name', array('label'=>false,'options'=>$branch_master,'empty'=>'Select','class'=>'form-control')); ?>
                                </div>
                                <label class="col-sm-1 control-label">Year</label>
                                <div class="col-sm-2">
                                <?php echo $this->Form->input('year', array('label'=>false,'options'=>$finance_year,'empty'=>'Select','class'=>'form-control')); ?>
                                </div>
                                <label class="col-sm-1 control-label">Month</label>
                                <div class="col-sm-2">
                                <?php echo $this->Form->input('month', array('label'=>false,'options'=>$months,'empty'=>'Select','class'=>'form-control')); ?>
                                </div>
                                <div class="col-sm-1">
                                    <input type="submit" name="View" id="View" value="View" class="btn btn-primary"/>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>    
                    </div>
            <div class="box-content">
                <table class="table" border="1">
                    <tr>
                        <th>Branch</th>
<!--                        <th>Client</th>
                        <th>Cost Center Name</th>-->
                        <th>Cost Center</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Status</th>
                        
                        <th>Action</th>
                    </tr>
                    <?php
                            
                            foreach($data as $dt)
                            {
                                echo $this->Form->create('InitialInvoice');
                               echo '<tr>';
                               
                               echo '<td>'.$dt['InitialInvoice']['branch_name'].'</td>';
                               echo '<td>'.$dt['InitialInvoice']['cost_center'].'</td>';
                               echo '<td>'.$dt['InitialInvoice']['grnd'].'</td>';
                               echo '<td>'.$dt['InitialInvoice']['InvoiceDeleteRemarks'].'</td>';
                               echo '<td>'.$dt['InitialInvoice']['RequestInvoiceType'].'</td>';
                               echo '<td>';
                               
                                echo '<a href="'.$this->webroot.'/InitialInvoices/edit_invoice?id='.$dt['InitialInvoice']['id'].'&status=ch" class="btn btn-primary">View</a> <br/>';
                                echo $this->Form->input('id', array('label'=>false,'type'=>'hidden','value'=>$dt['InitialInvoice']['id']));
                                echo '<button type="submit" name="submit" value="Approve" class="btn btn-primary" >Approve</button>';
                               
                               echo '</td>';
                               
                               echo '</tr>';
                               echo $this->Form->end();
                            }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
			
