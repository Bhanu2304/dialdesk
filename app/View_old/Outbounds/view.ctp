            <fieldset id="fieldset2" >
                <h1>Your Outbounds</h1>
                
                <div class="form-bottom">
                    <ul id="someID">
  								
				<?php
                      foreach($data as $post1): 
						if($post1['ClientCategory']['Label']==1){?><li>
                                	<?php echo "<font color=\"#336666\">".$post1['ClientCategory']['Plan'].' First'."</font>";?>
                                	<?php 
											?>
												<ul>
											<?php
											foreach($data as $post2):
												if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
													{?><li><?php
														 echo $post2['ClientCategory']['Plan'].' Second<br>';
														?>
                                                            	<ul>
																<?php
														foreach($data as $post3):
															if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
															{?><li> <?php
																 echo $post3['ClientCategory']['Plan'].' Third<br>';
																?>
                                                                    	<ul>
																		<?php
																foreach($data as $post4):
																	if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																	{?><li> <?php
																		 echo "".$post4['ClientCategory']['Plan'].' Fourth<br>';
																		?>
                                                                        <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																				{?><li><?php
																					 echo "".$post5['ClientCategory']['Plan'].' Fifth';
																				
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
                </div>              
            </fieldset> 
