<script>
    function isDecimalKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
    
        if ((charCode> 31 && (charCode < 48 || charCode > 57)))
            return false;
        
        return true;
    }
function view_edit_sims(id)
{         
    $.post("<?php echo $this->webroot;?>Sims/edit",{id:id},function(data){
        $("#fields-data").html(data);
    }); 
}
</script>
<script>
$(function () {
    $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});

</script>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Sim Management</a></li>
    <li class="active"><a href="#">Sim</a></li>
</ol>
<div class="page-heading">                                           
<h1>Sim Management</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Create New Sim</h2>
        <br/>
                <h2> <?php echo $this->Session->flash(); ?></h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('Sims',array("class"=>"form-horizontal")); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contact No.</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('contactnumber',array('label'=>false,'placeholder'=>'Contact No.','autocomplete'=>'off','required'=>true,'class'=>'form-control','minlength'=>"10",'maxlength'=>"10"));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Process Name</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('processname',array('label'=>false,
                                'placeholder'=>'Process Name','required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Purpose</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('purpose',array('label'=>false,'placeholder'=>'Purpose','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Service Provider</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('serviceprovider',array('label'=>false,'options'=>array('Jio'=>'Jio','Airtel'=>'Airtel','Vodafone Idea'=>'Vodafone Idea'),'empty'=>'Service Provider','required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Recharge Date</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('rechargedate',array('label'=>false,'placeholder'=>'Recharge Date','autocomplete'=>'off','required'=>true,'class'=>'form-control date-picker1','id'=>'recharge_date'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Validity</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('validity',array('label'=>false,'placeholder'=>'Validity','maxlength'=>"3",'autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Amount of recharge</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('amountofrecharge',array('label'=>false,'placeholder'=>'Amount of recharge','autocomplete'=>'off','required'=>true,'onKeypress'=>'return isDecimalKey(event)','class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Date of Alert</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('dateofalert',array('label'=>false,'placeholder'=>'Date of Alert','autocomplete'=>'off','required'=>true,'class'=>'form-control date-picker1'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Status of SIM</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('statusofsim',array('label'=>false,'empty'=>'Status','options'=>array('ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'),'required'=>true,'class'=>'form-control'));?>
                        </div>
                    </div>
                
                    
                
                    
                    
                
                    <div class="form-group">
                        <div class="col-sm-2"></div><div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="ADD" >
                        
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <?php if(!empty($data)){?>
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>View Sim Details</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div style="color:green;margin-left:20px;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <div class="scrolling">
                <table class="table">
                    <tr>
                        <th>SrNo.</th>
                        <th>Contact No.</th>
                        <th>Process Name</th>
                        <th>Purpose</th>
                        <th>Service Provider</th>
                        <th>Recharge Date</th>
                        <th>Validity</th>
                        <th>Amount of recharge</th>
                        <th>Date of Alert</th>
                        <th>Status of SIM</th>
                        <th>Action</th>
                    </tr>
                    <?php $i=1;
                            foreach($data as $d):
                                echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$d['Sim']['contactnumber']."</td>";
                                    echo "<td>".$d['Sim']['processname']."</td>";
                                    echo "<td>".$d['Sim']['purpose']."</td>";
                                    echo "<td>".$d['Sim']['serviceprovider']."</td>";
                                    echo "<td>".date_format(date_create($d['Sim']['rechargedate']),'d-m-Y')."</td>";
                                    echo "<td>".$d['Sim']['validity']."</td>";
                                    echo "<td>".$d['Sim']['amountofrecharge']."</td>";
                                    echo "<td>".date_format(date_create($d['Sim']['dateofalert']),'d-m-Y')."</td>";
                                    echo "<td>".$d['Sim']['statusofsim']."</td>";
                                    ?>
                    <td><a  href="#" class="btn-raised" data-toggle="modal" data-target="#fieldsUpdate" onclick="view_edit_sims('<?php echo $d['Sim']['id'];?>')" >
                            <label class="btn btn-xs btn-midnightblue btn-raised">
                                <i class="fa fa-edit"></i><div class="ripple-container"></div>
                            </label>
                        </a> 
                        <!-- <a href="#" onclick="deleteData('<?php //echo $this->webroot;?>Sims/delete_sim?id=<?php //echo $d['Sim']['id'];?>')" >
                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                        </a> -->
                    </td>
                                    <?php
                                echo "</tr>";
                            endforeach;
                    ?>
                </table>
                <div>
        </div> 
        
        <?php }?>
    </div>
</div>

<!-- Edit Capture Fields -->
<div class="modal fade" id="fieldsUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Edit Fields</h2>
            </div>
            <div id="fields-data"></div>
        </div>
    </div>
</div>
