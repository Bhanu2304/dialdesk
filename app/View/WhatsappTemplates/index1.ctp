<?php echo $this->Html->script('admin_creation'); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Social Media</a></li>
    <li class="active"><a href="#">Message Broadcasting</a></li>
</ol>
<div class="page-heading">            
    <h1>Message Broadcasting</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Message Broadcasting</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('WhatsappTemplate',array('action'=>'index1','id'=>'validate-form')); ?>
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <div id="erroMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>      
                            </div>
                        </div>
                    </div>
               
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('template_name',array('label'=>false,'id'=>'name','placeholder'=>'Template Name','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div> 
                    </div>
                        
                    <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <?php echo $this->Form->input('emailid',array('label'=>false,'id'=>'emailid','placeholder'=>'Template Id','class'=>'form-control','autocomplete'=>'off'));?>
                           </div>
                        </div>
                    </div>
                       
                    <div class="col-md-12">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Clients</label>
                                <div class="col-sm-1">
                                    <div class="assign-right" style="width: 595px;height:350px;">
                                        <ol class="user-tree" style="width: 500px;">
                                            <?php echo $UserRight;?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               
                    

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-8">
                                <div class="btn-toolbar">
                                <input type="submit" class="btn btn-web pull-left" value="Submit" >
                                </div>
                            </div>
                        </div>
                    </div>
                   
                <?php $this->Form->end(); ?>
            </div>
        </div> 
       

        
    </div>  
</div>



   