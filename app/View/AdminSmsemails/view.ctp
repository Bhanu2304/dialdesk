<script>
function clientWiseData(){
	$('#view_smsemail_form').attr('action', '<?php echo $this->webroot;?>AdminSmsemails/view');
	$("#view_smsemail_form").submit();
}
</script>
<div class="row-fluid">
	<div class="span12">
		<div class="box dark">
  			<header>
 				<div class="icons"><i class="icon-edit"></i></div>
          		<h5>SMS & Email Text Master</h5>
			</header>
    		<div id="div-1" class="accordion-body collapse in body">
            	<h3>View Client Wise Email/Sms</h3>
            	<?php echo $this->Form->create('AdminSmsemails',array('id'=>'view_smsemail_form')); ?>
				<div  style="margin-left:0%;">
             		<?php echo $this->Form->input('client',array('label'=>false,'value'=>isset ($clid) ? $clid : "",'onchange'=>'clientWiseData();','options'=>$client,'empty'=>'Select Client','required'=>true));?>
                </div>
             	<hr/>
				<?php echo $this->Form->end(); ?>
  
                             
<?php if(isset($clid) && $clid !=""){?>

<?php if(isset($data) && !empty($data)){?>	
<table class="display table table-bordered table-condensed table-hovered sortableTable" >
	<tr>
    	<td>   
    		<h5 style="color:#903;">Escalation</h5><hr/>    
        	<ul id="someID">					
<?php
 foreach($data as $post1): 
	if($post1['ClientCategory']['Label']==1){  ?><li>
     <?php echo "<a href=\"view?id=".base64_encode($post1['ClientCategory']['id'])."&cid=".base64_encode($clid)."\"><font color=\"#336666\">".$post1['ClientCategory']['Plan'].' First'."</font></a>";?>
		<ul>
			<?php
				foreach($data as $post2):
					if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
						{?><li><?php
							echo "<a href=\"view?id=".base64_encode($post2['ClientCategory']['id'])."&cid=".base64_encode($clid)."\"><font color=\"#336666\">".$post2['ClientCategory']['Plan'].' Second</font></a>';?>
                              <ul>
								<?php
									foreach($data as $post3):
										if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
											{?><li> <?php
												echo "<a href=\"view?id=".base64_encode($post3['ClientCategory']['id'])."&cid=".base64_encode($clid)."\"><font color=\"#336666\">".$post3['ClientCategory']['Plan'].' Third</font></a>';?>
                                                   <ul>
													  <?php
														foreach($data as $post4):
															if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																{?><li> <?php
																	echo "<a href=\"view?id=".base64_encode($post4['ClientCategory']['id'])."&cid=".base64_encode($clid)."\"><font color=\"#336666\">".$post4['ClientCategory']['Plan'].' Fourth</font></a>';?>
                                                                      <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																					{?><li><?php
																					 	echo "<a href=\"view?id=".base64_encode($post5['ClientCategory']['id'])."&cid=".base64_encode($clid)."\"><font color=\"#336666\">".$post5['ClientCategory']['Plan'].' Fifth</font></a>';
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
                    

      </td>
    </tr>
</table>
<?php } ?> 
<?php if(isset($data) && empty($data)){echo "No Record";}?>


<?php if(isset($escalation) && !empty($escalation)){?>
<table class="display table table-bordered table-condensed table-hovered sortableTable" >
	<tr>
    	<td>
   			<h5 style="color:#903;">Mobile & Sms</h5> 
   			<hr/>     
       		<table style="width:400px;" class="display table table-bordered table-condensed table-hovered sortableTable" >
            	<tr>
                	<td>Email</td>
                    <td>SMS</td>
                </tr>
                <?php
				if(isset($escalation)){
					foreach($escalation as $esc):
						echo "<tr>";
						if($esc['Escalation']['type']=='email'){
							echo "<td>".$esc['Escalation']['email']."</td>";
							echo "<td></td>";
						}
						else if($esc['Escalation']['type']=='sms'){
							echo "<td>".$esc['Escalation']['smsNo']."</td>";
							echo "<td></td>";
						}
						else if($esc['Escalation']['type']=='both'){
							echo "<td>".$esc['Escalation']['email']."</td>";
							echo "<td>".$esc['Escalation']['smsNo']."</td>";
						}
						echo "</tr>";
					endforeach;
				}
				?>
            </table>
    	</td>
	</tr>
</table> 
<?php } ?> 
<?php if(isset($escalation) && empty($escalation)){echo "No Record";}?>  
                
				<?php }?>            
			</div>
		</div>
	</div>
</div>

