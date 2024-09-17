<script>
    $('#start_date').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Accoount Master</a></li>
    <li class="active"><a href="#">Client Activation Date</a></li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Client Activation Date</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('ClientAccounts',array('class'=>'form-horizontal')); ?>    
            <div class="form-group">
                <label class="col-sm-1 control-label">Client</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$ClientName,'empty'=>'Client','class'=>'form-control')); ?> 
                </div>
                <label class="col-sm-2 control-label">Activation Date</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date','placeholder'=>'Start Date','class'=>'form-control date-picker')); ?>
                </div>
                <div class="col-sm-2">
                    <input type="submit" name="submit" value="Activate" class="btn-web btn">
                </div>
            </div>
            <?php echo $this->Form->end(); ?>

            <table class="table table-striped table-bordered datatables"> 
                <thead>
                <tr>
                    <th style="text-align:left;">Sr. No.</th>
                    <th style="text-align:left;">Client</th>
                    <th style="text-align:left;">Activation Date</th>
                    
                    
                    
                    
<!--                    <th>Action</th>-->
                </tr>
                </thead>
                <tbody>
                    <?php  $i=1;  foreach($activation_list as $plan) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        
                        <td><?php echo $plan['rm']['company_name']; ?></td>
                        <td><?php echo $plan['0']['activation_date']; ?></td>
<!--                <td><a href="edit_plan?id=<?php //echo $plan['PlanMaster']['Id']; ?>">Edit</a></td>-->
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="panel-footer"></div>
        </div>
    </div>
    
</div>
 
