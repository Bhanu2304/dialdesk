
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Bound Campaign</a></li>
        <li class="active"><a href="#">Export Campaign Formate</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Export Campaign Formate</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Export Campaign Formate</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
                <div class="panel-body">
               		
                    <?php echo $this->Form->create('ObImports',array('action'=>'download_campaign','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
              		
                        <div class="form-group">
                      		<label class="col-sm-3 control-label"></label>	
                          	<div class="col-sm-6">      
                               <div style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                              
               				
                         	</div>
                		</div>
                        
                        
                        <div class="form-group">
                      		<label class="col-sm-3 control-label">Campaign Name</label>	
                          	<div class="col-sm-6">      
                                <?php echo $this->Form->input('CampaignName',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign','required'=>true,'class'=>'form-control' ));?>
                                <div id="nn"></div>
               					<div id="note"></div> 	
                         	</div>
                		</div>
                	<?php echo $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div>
