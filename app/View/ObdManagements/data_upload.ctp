<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
<ol class="breadcrumb">                                
    <li><a href="#">Home</a></li>
    <li><a>Obd Management</a></li>
    <li class="active"><a href="#">Data Upload</a></li>
</ol>
<div class="page-heading">
    <h1>Data Upload</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Data Upload</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('ObdManagement',array('action'=>'DataUpload','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate','enctype'=>'multipart/form-data')); ?>

                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php //echo $this->Form->input('listid',array('label'=>false,'class'=>'form-control','onkeypress'=>'return isNumberKey(event)' ,'placeholder'=>'List Id','required'=>true)); ?>
                            <?php echo $this->Form->input('listid',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$list,'empty'=>'Select List','required'=>true)); ?>
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-4">
                    <div class="col-xs-12" style="margin-top: 10px;">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                           <?php echo $this->Form->input('uploadfile',array('type' => 'file','label'=>false)); ?> Only CSV file allowed
                           <!-- <a href="{{url('course_participant_approval.csv')}}">Sample File</a> -->
                           <?php echo $this->Html->link('Sample File', '/obd_data.csv');?>
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-4">
                    <div class="col-xs-12">
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
