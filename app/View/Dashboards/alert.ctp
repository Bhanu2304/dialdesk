<script>
    function isDecimalKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
    
        if ((charCode> 31 && (charCode < 48 || charCode > 57)))
            return false;
        
        return true;
    }

</script>
<script>
$(function () {
    $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});

</script>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Alert Dashboard</a></li>
    <li class="active"><a href="#">Alert Mechanism</a></li>
</ol>
<div class="page-heading">                                           
<h1>Alert Mechanism</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Create Alert</h2>
        <br/>
                <h2> <?php echo $this->Session->flash(); ?></h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('Dashboards',array("class"=>"form-horizontal")); ?>
                    <div class="form-group">
                    <?php $options = ['alert1' => 'Al Alert','alert2' => 'Negative In Exposure','alert3' => 'CALLS STARTED BUT BILLING NOT STARTED','alert4' => 'AGENTS  PERFORMANCE ALERTS','alert5'=>'Clients who offered ZERO Calls'];?>
                    <label class="col-sm-2 control-label">Label</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('type',array('label'=>false,'value'=>$sim_det['sim_bcc'],'options'=>$options,'empty'=>'Select Label','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                        <label class="col-sm-2 control-label">To</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('to',array('label'=>false,'value'=>$sim_det['to'],'placeholder'=>'To','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">CC</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('cc',array('label'=>false,
                                'placeholder'=>'CC','value'=>$sim_det['cc'],'required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Remarks</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('remarks',array('type'=>'textarea','value'=>$sim_det['remarks'],'label'=>false,'placeholder'=>'Remarks','required'=>true,'class'=>'form-control','rows'=>3));?>
                        </div>
                    
                        <div class="col-sm-2"></div><div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="Update">
                        
                        </div>
                    
                        
                    </div>
                   
                
                    
                
                    
                    
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline">
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Label</th>
                                                            <th>To</th>
                                                            <th>Cc</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($sim_det as $d): $id = $d['AlertMechanism']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php if($d['AlertMechanism']['type'] == 'alert1'){echo 'Al Alert';} else if($d['AlertMechanism']['type'] == 'alert2'){echo 'Negative In Exposure';}else if($d['AlertMechanism']['type'] == 'alert3'){echo 'CALLS STARTED BUT BILLING NOT STARTED';} else if($d['AlertMechanism']['type'] == 'alert4'){echo 'AGENTS  PERFORMANCE ALERTS';} else if($d['AlertMechanism']['type'] == 'alert5'){echo 'Clients who offered ZERO Calls';}?></td>
                                                                    <td><?php echo $d['AlertMechanism']['to']; ?></td>
                                                                    <td><?php echo $d['AlertMechanism']['cc']; ?></td>
                                                                    <td><?php echo $d['AlertMechanism']['remarks']; ?></td>
                                                                    <td>
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Dashboards/delete_alert?id=<?php echo $id;?>')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

                                                    </tbody>
                                                </table>
                                            </div>
        
    </div>
</div>


