<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >In Call Operation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AgentCreations">Update Ticket Status</a></li>
</ol>
<div class="page-heading">            
    <h1>Update Ticket Status</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Update Ticket Status</h2>
            </div>
            <div class="panel-body">
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('Closeloops',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate','enctype'=>'multipart/form-data')); ?>

                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                           <?php echo $this->Form->input('uploadfile',array('type' => 'file','label'=>false)); ?> Only CSV file allowed
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-12" style="margin-top:10px;">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input type="submit" class="btn btn-web pull-left" value="Upload" >
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
    </div>
</div>
