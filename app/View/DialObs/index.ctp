<script>
function get_allocation(path,clientid){ 
       // var clientid=camp.value;
       // var clientid=$("#DialObsAllocationName").val();    
        $.ajax({
            type:'post',
            url:path,
            data:{clientid:clientid},
            success:function(data){
                $("#DialObsAllocationName").html(data);
            }
        }); 
    }
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >OB Calling Data</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AgentCreations">Upload Calling Data</a></li>
</ol>
<div class="page-heading">            
    <h1>Upload Calling Data</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Upload Calling Data</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('DialObs',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate','enctype'=>'multipart/form-data')); ?>

                <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <select id="ClientId" name="ClientId"  class="form-control" required="" onchange="get_allocation('/dialdesk/DialObs/get_allocation',this.value)">
                            <option value="">Select Client</option>
                            <?php
                            foreach($data as $k=>$v)
                            {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <label class="col-sm-1 control-label">Allocation</label>
                <div class="col-md-4">

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                           <?php echo $this->Form->input('AllocationName',array('label'=>false,'options'=>'','empty'=>'Select Allocation','class'=>'form-control','required'=>false ));?>
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
