<script>
function getClient(){
    $("#view_esclation_form").submit();	
}
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Escalation View</h5>
            </header>
            <div id="div-1" class="accordion-body collapse in body">
                <?php echo $this->Form->create('AdminEscalations',array('action'=>'view','id'=>'view_esclation_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?> 
            </div>
        </div>
    </div>
</div>

<?php if(isset($clientid) && !empty($clientid)){?>  
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Basic Form Elements</h5>
            </header>
            <div id="div-1" class="accordion-body collapse in body">
                <?php
 foreach($data as $post1): 
	if($post1['ClientCategory']['Label']==1){  ?><li>
     <?php echo "<a href=\"view?id=".base64_encode($post1['ClientCategory']['id'])."&cid=".$clientid."\"><font color=\"#336666\">".$post1['ClientCategory']['ecrName'].' First'."</font></a>";?>
		<ul>
			<?php
				foreach($data as $post2):
					if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
						{?><li><?php
							echo "<a href=\"view?id=".base64_encode($post2['ClientCategory']['id'])."&cid=".$clientid."\"><font color=\"#336666\">".$post2['ClientCategory']['ecrName'].' Second</font></a>';?>
                              <ul>
								<?php
									foreach($data as $post3):
										if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
											{?><li> <?php
												echo "<a href=\"view?id=".base64_encode($post3['ClientCategory']['id'])."&cid=".$clientid."\"><font color=\"#336666\">".$post3['ClientCategory']['ecrName'].' Third</font></a>';?>
                                                   <ul>
													  <?php
														foreach($data as $post4):
															if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																{?><li> <?php
																	echo "<a href=\"view?id=".base64_encode($post4['ClientCategory']['id'])."&cid=".$clientid."\"><font color=\"#336666\">".$post4['ClientCategory']['ecrName'].' Fourth</font></a>';?>
                                                                      <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																					{?><li><?php
																					 	echo "<a href=\"view?id=".base64_encode($post5['ClientCategory']['id'])."&cid=".$clientid."\"><font color=\"#336666\">".$post5['ClientCategory']['ecrName'].' Fifth</font></a>';
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
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Email & Sms</h5>
            </header>
            <div id="div-1" class="accordion-body collapse in body">
                <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                    <tr>
                        <th>Email</th>
                        <th>SMS</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        if(isset($escalation)){
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
                                echo '<td><a href="delete?id='.base64_encode($esc['Escalation']['id']).'&cid='.$clientid.'">Activate/Deactivate</a></td>';
                                echo "</tr>";
                            endforeach;
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php }?>




























