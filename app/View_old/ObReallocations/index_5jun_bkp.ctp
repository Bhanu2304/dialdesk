<script>
    function showListId(type){ 
        $("#clientListId").hide();
        $("#ObImportsListid").hide();
        document.getElementById("ObImportsListid").required = false;
        if(type ==="pd"){
            $("#clientListId").show();
            $("#ObImportsListid").show();
            document.getElementById("ObImportsListid").required = true;
        }
    }
</script>
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Call Management</a></li>
        <li class="active"><a href="#">Manage Allocations</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Manage Allocations</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            

            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Manage Allocations</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
               		
                    <?php	echo $this->Form->create('ObImports',array('action'=>'index','enctype'=>'multipart/form-data','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
              			
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label"></label>	
                          	<div class="col-sm-6"> 
                            	<div style="color:red;font-size: 15px;"><?php	echo $this->Session->flash();?></div>
                         	</div>
                		</div>
                        
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Campaign Name</label>	
                          	<div class="col-sm-6"> 
                            	<?php echo $this->Form->input('CampaignName',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign','class'=>'form-control','required'=>true ));?>     
                         	</div>
                		</div>
                    
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Select Type</label>	
                          	<div class="col-sm-6"> 
                            	<?php echo $this->Form->input('uploadType',array('label'=>false,'options'=>array('manual'=>'Manual','pd'=>'PD'),'empty'=>'Select Type','onchange'=>'showListId(this.value)','class'=>'form-control','required'=>true ));?>     
                         	</div>
                		</div>
                    
                    <div class="form-group" id="clientListId" style="display: none;" >
                      		<label class="col-sm-3 control-label">Select List Id</label>	
                          	<div class="col-sm-6"> 
                            	<?php echo $this->Form->input('listid',array('label'=>false,'options'=>$viewListId,'empty'=>'Select List Id','class'=>'form-control','required'=>true ));?>     
                         	</div>
                		</div>
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Allocation Name</label>	
                          	<div class="col-sm-6"> 
                            	<?php echo $this->Form->input('AllocationName',array('label'=>false,'class'=>'form-control','placeholder'=>'allocation name','autocomplete'=>'off','required'=>true ));?>
                         	</div>
                		</div>
   
                        <div class="uploadfile">
                        	<label class="col-sm-3 control-label">Upload Data</label>
                        	<div class="col-sm-6">   
                            	<?php echo $this->Form->File('File',array('label'=>false,'type'=>'File','required'=>true));?> 
                                <span>(Upload Only CSV File)</span>
                         	</div>
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
                	<?php echo $this->Form->end(); ?>
          		</div> 
            </div>
            
            
            
                <?php if(!empty($viewAlloc)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Allocation</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling1">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Campaign Name</th>
                            <th>Allocation Name</th>
                            <th>Allocation Type</th>
                            <th>Create Date</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;foreach($viewAlloc as $row){?>
                            <tr >
                                <td><?php echo $i++;?></td>
                                <td><?php echo $row['cmapname'];?></td>
                                <td><?php echo $row['allocname'];?></td>
                                <td><?php echo $row['alloctype'];?></td>
                                <td><?php echo $row['createdate'];?></td>
                                <td class="center">
                                    <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObImports/delete_allocation?id=<?php echo $row['id'];?>')" >
                                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                    </a>
                                </td>  
                            </tr>
                        <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
            
            
        </div>
    </div>



