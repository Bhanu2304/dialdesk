<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Training</a></li>
    <li class="active"><a href="#">Admin Training</a></li>
</ol>
<div class="page-heading">            
    <h1>Training</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Training</h2>
            </div>
            <div class="panel-body">
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('AdminTrainings',array('action'=>'update','enctype'=>'multipart/form-data')); ?>

                <div class="col-md-5">
                    <div class="col-xs-9">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('client',array('label'=>false,'options'=>$client,'empty'=>'Select Client','class'=>'form-control','required'=>true ));?>
                        </div>
                    </div>

                    

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->file('files.',array('label'=>false,'type'=>'file','multiple'=>true,'required'=>true));?>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top:10px;">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                            <button  onClick="return training_file_validate()"class="btn btn-web pull-left">Update</button>
                             
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
    </div>
</div>