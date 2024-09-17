 <div id="wrapper">
	<div id="register">
		<?php echo $this->Form->create('Outbounds',array('action'=>'create_category')); ?>
            <fieldset id="fieldset1" >
                <h1>Create Category</h1>
                <font style="color:red;"></font>
                <div class="form-bottom">
				<table>      	
					<tr>
						<td>Create Category<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('category',array('label'=>false,'autocomplete'=>'off','required'=>true));?></td>
			   		</tr>
					<tr>
						<td colspan=2><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="Create" >
						</p>
						</td>
					</tr>
				</table>
                </div>              
            </fieldset> 
		<?php echo $this->Form->end(); ?>

		<?php echo $this->Form->create('Outbounds',array('action'=>'create_type')); ?>
            <fieldset id="fieldset2" >
                <h1>Create Type</h1>
                <font style="color:red;"></font>
                <div class="form-bottom">
				<table>      	
					<tr>
						<td>Select Category<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category1','required'=>true));?></td>
			   		</tr>

					<tr>
						<td>Add Type<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('type',array('label'=>false,'autofill'=>'false','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td colspan=2><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="ADD" >
						</p>
						</td>
					</tr>
				</table>
                </div>              
            </fieldset> 
		<?php  echo $this->Form->end(); ?>

		<?php echo $this->Form->create('Outbounds',array('action'=>'create_sub_type1')); ?>
            <fieldset id="fieldset2" >
                <h1>Create Sub Type1</h1>
                <font style="color:red;"></font>
                <div class="form-bottom">
				<table>      	
					<tr>
						<td>Select Category<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category2','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td>Select Type<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type1','required'=>true));?></td>
			   		</tr>

					<tr>
						<td>Add Sub Type 1<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('sub_type1',array('label'=>false,'autofill'=>'false','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td colspan=2><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="ADD" >
						</p>
						</td>
					</tr>
				</table>
                </div>              
            </fieldset> 
		<?php echo $this->Form->end(); ?>


		<?php echo $this->Form->create('Outbounds',array('action'=>'create_sub_type2')); ?>
            <fieldset id="fieldset2" >
                <h1>Create Sub Type2</h1>
                <font style="color:red;"></font>
                <div class="form-bottom">
				<table>      	
					<tr>
						<td>Select Category<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category3','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td>Select Type<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type2','required'=>true));?></td>
			   		</tr>


					<tr>
						<td>Select Sub Type 1<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type1','required'=>true));?></td>
			   		</tr>

					<tr>
						<td>Add Sub Type 2<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('sub_type2',array('label'=>false,'autofill'=>'false','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td colspan=2><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="ADD" >
						</p>
						</td>
					</tr>
				</table>
                </div>              
            </fieldset> 
		<?php echo $this->Form->end(); ?>

		<?php echo $this->Form->create('Outbounds',array('action'=>'create_sub_type3')); ?>
            <fieldset id="fieldset2" >
                <h1>Create Sub Type3</h1>
                <font style="color:red;"></font>
                <div class="form-bottom">
				<table>      	
					<tr>
						<td>Select Category<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category4','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td>Select Type<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type3','required'=>true));?></td>
			   		</tr>


					<tr>
						<td>Select Sub Type 1<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type2','required'=>true));?></td>
			   		</tr>

					<tr>
						<td>Select Sub Type 2<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'sub_type2_2','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td>Add Sub Type 3<font style="color:red;">*</font></td>				
	           			<td><?php echo $this->Form->input('sub_type3',array('label'=>false,'autofill'=>'false','required'=>true));?></td>
			   		</tr>
                    
					<tr>
						<td colspan=2><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="ADD" >
						</p>
						</td>
					</tr>
				</table>
                </div>              
            </fieldset> 
		<?php echo $this->Form->end(); ?>



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

                 	                   			
			                    

            <div class="otp"><br/>
                           
                 <p class="signin button">
                    <input type="button" style="width:75px;"  value="Submit" >
                </p>
            </div>
			<div id="cover" ></div>

           
		
	</div>	
</div>