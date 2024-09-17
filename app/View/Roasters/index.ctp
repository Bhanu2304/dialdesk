<?php ?>
<?php echo $this->Html->script('admin_creation'); ?>
<script> 
$(function() { 
$( ".datepicker" ).datepicker({dateFormat: 'dd-mm-yy'});
// $('.timepicker').timepicker({ timeFormat: 'H:mm:ss p' });

 });
 </script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Roaster</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AgentCreations">Roaster</a></li>
</ol>
<div class="page-heading">            
    <h1>Roaster</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Roaster</h2>
            </div>
            <div class="panel-body">
                <div><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('Roaster',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal','data-parsley-validate','enctype'=>'multipart/form-data')); ?>                <div class="col-md-6">
                <div class="form-group">
                
                <div class="col-sm-3">                  
                            <?php echo $this->Form->input('roasterdate',array(
                                'label' => false,
                                'type' => 'text',
                                'disabled' => 'disabled',
                                'placeholder' => 'Roaster Date',
                                'value' => $cap_date,
                                'class' => 'form-control datepicker',
                                'required'=> true,
                                'autocomplete' => false,
                                )); 
                                ?>
                </div>

                
                <div class="col-sm-6"> 
                    
                    <input type="file" name="data[Roaster][uploadfile]" style="opacity:100%;position:relative;" class="form-control" autocomplete=""  required="" />
                    <span style="margin-top:3px;" >Note - Upload only csv format</span> 
                    <?php echo $this->Html->link('Sample File', '/roaster.csv');?>
                </div>
                <div class="col-sm-1">
                    <input type="submit" name="submit" style="margin-top:-1px;" value="Submit" class="btn-web btn">
                </div>
                </div>        
                
                
                
                
                <?php echo $this->Form->end(); ?> 
            </div>
        </div>
    </div>
</div>
