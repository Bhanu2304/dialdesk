<?php //print_r($escalation);
//print_r($data);

 ?>
<div id="wrapper">
	<div id="register">
			<?php echo $this->Form->create('Escalation',array('action'=>'add')); ?>
            <fieldset id="fieldset1" >
                <h1>Escalation</h1>
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                <div class="form-bottom">
                    <table id="table">                    	

                <tr>
					<td>Select Category<font style="color:red;">*</font></td>
					<td><?php echo $this->Form->input('Parent',array('label'=>false,'options'=>$Category,'empty'=>'Select 
Category','id'=>'Parent1','required'=>true));?>
					</td>                    
			   </tr>

				<tr>
                	<td>
                    	Select User
                    </td>
                	<td>
                    	<?php echo $this->Form->input('user',array('label'=>false,'options'=>$agent,'multiple'=>'multiple','required'=>true));?>
                    </td>                    
                </tr>

				<tr>
                	<td>
                    	Select Notification
                    </td>
                	<td>
                    	<?php echo $this->Form->input('type',array('label'=>false,'options'=>array('email'=>'Email','sms'=>'SMS'),'multiple'=>'multiple','required'=>true));?>
			
                    </td>
                    
                </tr>


			   <tr>
                    <td><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="ADD" >
					</p>
					</td>
						
                    <td align="right"><br/>
						<p class="signin button">
							<button  type="button" id="button" onClick="escalation_getChild()">Find Child</button>
					</p>
					</td>
                 </tr>
                      </table>
                </div>              
            </fieldset> 

            <fieldset id="fieldset2" >
                <h1>Your ECR</h1>
                
                <div class="form-bottom">
                    <table border="1" bordercolor="#00FFFF" id='table'  cellspacing="0">
				<?php
                      foreach($data as $post1): 
						if($post1['ClientCategory']['Label']==1){?><tr><td valign="top">                        
                                	<?php echo "<font color=\"#336666\">".$post1['ClientCategory']['Plan'].' First'."</font>";
											$mobile =""; $email = "";
											foreach($escalation as $esc):
													if($esc['Escalation']['ecrId'] == $post1['ClientCategory']['id'])
														{ $mobile .= $esc['Escalation']['contactNo'].",";
														  $email .= $esc['Escalation']['email'].",";
														}
												endforeach;
												if($mobile!="") echo "(".$mobile.")";
												if($email!="") echo "(".$email.")";
									?>
                                    
                                    <br>
                                	<?php 
											?>
												<table border="1" bordercolor="#00FF33" id='table'><tr>
											<?php
											foreach($data as $post2):
												if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
													{?><td valign="top"><?php
														 echo $post2['ClientCategory']['Plan'].' Second<br>';
														 											$mobile =""; $email = "";
											foreach($escalation as $esc):
													if($esc['Escalation']['ecrId'] == $post2['ClientCategory']['id'])
														{ $mobile .= $esc['Escalation']['contactNo'].",";
														  $email .= $esc['Escalation']['email'].",";
														}
												endforeach;
												if($mobile!="") echo "(".$mobile.")";
												if($email!="") echo "(".$email.")";

														?>
                                                            	<table border="1" bordercolor="#0000FF" id='table' cellspacing="0"><tr>
																<?php
														foreach($data as $post3):
															if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
															{?><td valign="top"> <?php
																 echo $post3['ClientCategory']['Plan'].' Third<br>';
																 	$mobile =""; $email = "";
																foreach($escalation as $esc):
																	if($esc['Escalation']['ecrId'] == $post3['ClientCategory']['id'])
																		{ $mobile .= $esc['Escalation']['contactNo'].",";
														  					$email .= $esc['Escalation']['email'].",";
																		}
																endforeach;
																if($mobile!="") echo "(".$mobile.")";
																if($email!="") echo "(".$email.")";

																?>
                                                                    	<table border="1" bordercolor="#FF9966"><tr>
																		<?php
																foreach($data as $post4):
																	if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																	{?><td valign="top"> <?php
																		 echo "".$post4['ClientCategory']['Plan'].' Fourth<br>';
																		 	$mobile =""; $email = "";
																		foreach($escalation as $esc):
																			if($esc['Escalation']['ecrId'] == $post4['ClientCategory']['id'])
																				{ $mobile .= $esc['Escalation']['contactNo'].",";
														  						$email .= $esc['Escalation']['email'].",";
																				}
																		endforeach;
																		if($mobile!="") echo "(".$mobile.")";
																		if($email!="") echo "(".$email.")";

																		?>
                                                                        <table border="1" bordercolor="#FF0000" cellspacing="0"><tr>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																				{?><td valign="top"><?php
																					 echo "".$post5['ClientCategory']['Plan'].' Fifth';
																					$mobile =""; $email = "";
																					foreach($escalation as $esc):
																						if($esc['Escalation']['ecrId'] == $post5['ClientCategory']['id'])
																						{ $mobile .= $esc['Escalation']['contactNo'].",";
														  								$email .= $esc['Escalation']['email'].",";
																						}
																					endforeach;
																					if($mobile!="") echo "(".$mobile.")";
																					if($email!="") echo "(".$email.")";

																				?></td> <?php }
																			endforeach;
																		?>
                                                                        </tr></table>
                                                                      </td>
																		<?php
																		
																	}
																endforeach;?>
                                                                </tr></table>
                                                                </td>
																<?php
																
															}
														endforeach;?>
                                                        </tr></table>
                                                     </td>
														<?php														
													}
											endforeach;	?>
                                            </tr></table>
                                        </td></tr>
								<?php }	endforeach; ?>
                        
                      </table>
                </div>              
            </fieldset> 
                 	                   			
			                    

            <div class="otp"><br/>
                           
                 <p class="signin button">
                    <input type="button" style="width:75px;"  value="Submit" >
                </p>
            </div>
			<div id="cover" ></div>

           
		<?php $this->Form->end(); ?>
	</div>	
</div>