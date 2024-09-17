<ol class="breadcrumb">
    <li><?php echo $this->Html->link('Home',array('controller'=>'Homes','action'=>'index','full_base'=>true)); ?></li>
    <li><?php echo $this->Html->link('Escalation',array('controller'=>'Escalations','action'=>'index','full_base'=>true)); ?></li>    
</ol> 
<div class="page-heading">
    <h1>Escalation View</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
	<div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Basic Form Elements</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
  								
<?php
 foreach($data as $post1): 
	if($post1['ClientCategory']['Label']==1){  ?><li>
     <?php echo "<a href=\"view?id=".base64_encode($post1['ClientCategory']['id'])."\"><font color=\"#336666\">".$post1['ClientCategory']['ecrName'].' First'."</font></a>";?>
		<ul>
			<?php
				foreach($data as $post2):
					if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
						{?><li><?php
							echo "<a href=\"view?id=".base64_encode($post2['ClientCategory']['id'])."\"><font color=\"#336666\">".$post2['ClientCategory']['ecrName'].' Second</font></a>';?>
                              <ul>
								<?php
									foreach($data as $post3):
										if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
											{?><li> <?php
												echo "<a href=\"view?id=".base64_encode($post3['ClientCategory']['id'])."\"><font color=\"#336666\">".$post3['ClientCategory']['ecrName'].' Third</font></a>';?>
                                                   <ul>
													  <?php
														foreach($data as $post4):
															if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																{?><li> <?php
																	echo "<a href=\"view?id=".base64_encode($post4['ClientCategory']['id'])."\"><font color=\"#336666\">".$post4['ClientCategory']['ecrName'].' Fourth</font></a>';?>
                                                                      <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																					{?><li><?php
																					 	echo "<a href=\"view?id=".base64_encode($post5['ClientCategory']['id'])."\"><font color=\"#336666\">".$post5['ClientCategory']['ecrName'].' Fifth</font></a>';
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
                        
						
                </div>              
        </div>
<div class="panel panel-default" data-widget='{"draggable": "false"}'>
<div class="panel-heading">
<h2>Email & Sms</h2>
<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
</div>
<div data-widget-controls="" class="panel-editbox"></div>
<div class="panel-body">
<table border = "1">
<tr>
    <th>Email</th>
    <th>SMS</th>
    <th>Action</th>
</tr>

<?php //print_r($escalation);
    if(isset($escalation))
    {
        foreach($escalation as $esc):
            echo "<tr>";
            if($esc['Escalation']['type']=='email')
            {
		echo "<td>".$esc['Escalation']['email']."</td>";
		echo "<td></td>";
            }
            else if($esc['Escalation']['type']=='sms')
            {
                echo "<td></td>";
		echo "<td>".$esc['Escalation']['smsNo']."</td>";
            }
            else if($esc['Escalation']['type']=='both')
            {
                echo "<td>".$esc['Escalation']['email']."</td>";
                echo "<td>".$esc['Escalation']['smsNo']."</td>";
            }
            echo '<td><a href="delete?id='.base64_encode($esc['Escalation']['id']).'">Activate/Deactivate</a></td>';
            echo "</tr>";
	endforeach;
    }
				?>
</table>
            <div class="form-group">
                <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                        <input type="button" value="Back" class="btn-raised btn-primary btn" onclick="window.history.back()" />   
                    </div>
                    
                    <div class="col-sm-3">
                            <?php echo $this->Html->link('Create Escalation', array('controller'=>'Escalations','action'=>'index'),array('class'=>'btn-raised btn-primary btn')); ?>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>    
