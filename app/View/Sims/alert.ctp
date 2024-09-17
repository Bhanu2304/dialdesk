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
    <li class="active"><a href="#">Sim Alert</a></li>
</ol>
<div class="page-heading">                                           
<h1>Sim Alert</h1>
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
                <?php echo $this->Form->create('Sims',array("class"=>"form-horizontal")); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">To</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('sim_to',array('label'=>false,'value'=>$sim_det['sim_to'],'placeholder'=>'To','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">CC</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('sim_cc',array('label'=>false,
                                'placeholder'=>'CC','value'=>$sim_det['sim_cc'],'required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">Bcc</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('sim_bcc',array('label'=>false,'value'=>$sim_det['sim_bcc'],'placeholder'=>'Bcc','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
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
        
    </div>
</div>


