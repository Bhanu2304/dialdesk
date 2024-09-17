<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Upload Existing Customers</a></li>
</ol>
<div class="page-heading">            
    <h1>Upload Existing Customers</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Upload Existing Customers</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>

            <div class="panel-body">
                <?php echo $this->Form->create('UploadExistingBases',array('action'=>'add','enctype'=>'multipart/form-data','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                <hr/>    
                <div class="form-group">
                        <label class="col-sm-3 control-label"></label>	
                        <div class="col-sm-6">      
                            <div style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>	
                        <div class="col-sm-6">      
                            <p>Click On Import Format<font style="color:red;">*</font> <a href="UploadExistingBases/download">Download</a></p>
                           <!-- <p colspan="2"  style="color:#000099">After download format plz convert in csv and upload base.</p> -->
                        </div>
                    </div>

                    <label class="col-sm-3 control-label">Upload Base<font style="color:red;">*</font></label>	
                    <div class="col-sm-6">      
                       <?php echo $this->Form->File('File',array('label'=>false,'type'=>'File','required'=>true));?>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="btn-toolbar">
                                    <input type="submit" class="btn btn-web" value="Upload" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                <?php $this->Form->end(); ?>
            </div> 
        </div>
    </div>
</div> 















