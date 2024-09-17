<script>
function obecrDetails(){
    $("#view_obecr").submit();
}

function get_campaign(path,client){
    var id=client.value;
    $.ajax({
            type:'post',
            url:path,
            data:{id:id},
            success:function(data){
                    $("#AdminObecrsCampaign").html(data);
            }
    });	
}
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>ECR TREE STRUCTURE</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <?php echo $this->Form->create('AdminObecrs',array('action'=>'view','id'=>'view_obecr')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'get_campaign("get_campaign",this);','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
                    <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>isset($campaign)?$campaign:"",'onchange'=>'obecrDetails();','value'=>isset($campaign_id)?$campaign_id:"",'empty'=>'Select Campaign Name','class'=>'form-control','required'=>true ));?>
                <?php echo $this->Form->end(); ?>
                
                <?php if(isset($data) && !empty($data)){?>
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
                
                
                    
                <?php echo $this->Html->link('Add New', array('controller'=>'AdminObecrs','action'=>'index','?'=> array('id' =>isset($clientid)?$clientid:"")),array('class'=>'btn-raised btn-primary btn')); ?>
                
                <?php }?>
            </div>
        </div>
    </div>
</div>










