<script>
function obecrDetails(){
    $("#view_obecr").submit();
}
</script>
<ol class="breadcrumb">
    <li><?php echo $this->Html->link('Home',array('controller'=>'homes','action'=>'index','full_base'=>true)); ?></li>
    <li><?php echo $this->Html->link('Create Ecr',array('controller'=>'Obecrs','action'=>'index','full_base'=>true)); ?></li>
    <li class="active"><?php echo $this->Html->link('Edit Ecr',array('controller'=>'ObecrEdits','action'=>'index','full_base'=>true)); ?></li>
</ol> 
<div class="page-heading">                                           
<h1>View Ecr</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
<div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Tree Structure</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
    
            <div data-widget-controls="" class="panel-editbox"></div>
            
            <div class="panel-body">
                <?php echo $this->Form->create('Obecr',array('action'=>'view','id'=>'view_obecr',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
                <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'onchange'=>'obecrDetails();','empty'=>'Select Campaign Name','class'=>'form-control','required'=>true ));?>
                        </div>
                </div> <br/><br/>
                <?php echo $this->Form->end(); ?>
              <ul id="someID">
  								
				<?php
                      foreach($data as $post1): 
						if($post1['ObclientCategory']['Label']==1){?><li>
                                	<?php echo "<font color=\"#336666\">".$post1['ObclientCategory']['ecrName'].' First'."</font>";?>
                                	<?php 
											?>
												<ul>
											<?php
											foreach($data as $post2):
												if($post2['ObclientCategory']['Label']==2 && $post2['ObclientCategory']['parent_id']==$post1['ObclientCategory']['id'])
													{?><li><?php
														 echo $post2['ObclientCategory']['ecrName'].' Second<br>';
														?>
                                                            	<ul>
																<?php
														foreach($data as $post3):
															if($post3['ObclientCategory']['Label']==3 && $post3['ObclientCategory']['parent_id']==$post2['ObclientCategory']['id'])
															{?><li> <?php
																 echo $post3['ObclientCategory']['ecrName'].' Third<br>';
																?>
                                                                    	<ul>
																		<?php
																foreach($data as $post4):
																	if($post4['ObclientCategory']['Label']==4 && $post4['ObclientCategory']['parent_id']==$post3['ObclientCategory']['id'])
																	{?><li> <?php
																		 echo "".$post4['ObclientCategory']['ecrName'].' Fourth<br>';
																		?>
                                                                        <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ObclientCategory']['Label']==5 && $post5['ObclientCategory']['parent_id']==$post4['ObclientCategory']['id'])
																				{?><li><?php
																					 echo "".$post5['ObclientCategory']['ecrName'].' Fifth';
																				
																				?></li> <?php }
																			endforeach;
																		?>
                                                                        </ul>
                                                                      </li>
																		<?php
																		
																	}
																endforeach;?>
                                                                </ul>
                                                                </li>
																<?php
																
															}
														endforeach;?>
                                                                </ul>
                                                                </li>
														<?php														
													}
											endforeach;	?>
                                                                </ul>
                                                                </li>
								<?php }	endforeach; ?>
                        
						</ul>
                    <div class="form-group">
                        
                        <div class="col-sm-2">
                         <input type="button" value="Back" class="btn-raised btn-primary btn" onclick="window.history.back()" />      
                        </div>
                        
                        <div class="col-sm-3">
                            <?php echo $this->Html->link('Add New', array('controller'=>'Obecrs','action'=>'index'),array('class'=>'btn-raised btn-primary btn')); ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>    
