
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a>Social Media</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AgentCreations">Call Flow</a></li>
</ol>
<div class="page-heading">            
    <h1>Call Flow</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Call Flow</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('AkaiFlow',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate','enctype'=>'multipart/form-data')); ?>

                 <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client_id','class'=>'form-control', 'onchange'=>'viewcalltype(this);','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                    </div>
                    
                </div>

                <div class="col-md-4" style="margin-top:10px;">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                           <?php echo $this->Form->input('uploadfile',array('type' => 'file','id' => 'selectfile','label'=>false,'required'=>true)); ?> Only CSV file allowed
                           <!-- <a href="{{url('course_participant_approval.csv')}}">Sample File</a> -->
                           <?php //echo $this->Html->link('Sample File', '/templates.csv');?>
                        </div>
                    </div>
                    
                </div>

                <div class="col-md-4">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input type="submit" class="btn btn-web pull-left" value="Upload">
                             <!-- <input type="button" onclick="return upload_data('<?php// echo $this->webroot;?>AkaiFlow/view')"  value="Upload" class="btn-web btn"> -->
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
    </div>
</div>
