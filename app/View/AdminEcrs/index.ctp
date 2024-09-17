<script>    
function getClient(){
    $("#ecr_form").submit();	
}

$(document).ready(function(){
    $("#category2").on('change',function(){
	$.post("AdminEcrs/get_label2",{parent_id : $('#category2').val(),type : 'type1'},function(data,status){
            $('#type1').replaceWith(data);
	});
    });
    
    $("#category3").on('change',function(){
	$.post("AdminEcrs/get_label2",{parent_id : $('#category3').val(),type : 'type2'},function(data,status){
            $('#type2').replaceWith(data);
            $('#sub_type1').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_type1" required="required" class="form-control"></select>');
        });
    });
    
    $("#category4").on('change',function(){
	$.post("AdminEcrs/get_label2",{parent_id : $('#category4').val(),type : 'type3'},function(data,status){
            $('#type3').replaceWith(data);
            $('#sub_type2').replaceWith('<select name="data[Ecr][sub_type1]" class="form-control" id="sub_type2" required="required"></select>');
            $('#EcrSubType2').replaceWith('<select name="data[Ecr][sub_type2]" class="form-control" required="required" id="EcrSubType2"></select>');
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
        });
    });
    
    $("body").on('change',"#type2",function(){
        $.post("AdminEcrs/get_label3",{parent_id : $('#type2').val(),type : 'sub_type1'},function(data,status){
            $('#sub_type1').replaceWith(data);
        });
    });
    
    $("body").on('change',"#type3",function(){
        $.post("AdminEcrs/get_label3",{parent_id : $('#type3').val(),type : 'sub_type2'},function(data,status){
            $('#sub_type2').replaceWith(data);
            $('#sub_type2_2').replaceWith('<select required="required" class="form-control" id="sub_type2_2" name="data[Ecr][sub_type2]"></select>');
        });
    });
    
    $("body").on('change',"#sub_type2",function(){
        $.post("AdminEcrs/get_label4",{parent_id : $('#sub_type2').val(),type : 'sub_type2_2'},function(data,status){
            $('#sub_type2_2').replaceWith(data);
	});
    });
     
});
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Create ECR</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <?php echo $this->Form->create('AdminEcrs',array('action'=>'index','id'=>'ecr_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?> 
 
                <?php if(isset($clientid) && !empty($clientid)){ ?>
                <div style="width: 100%;overflow: scroll;" >
                <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                    <tr>
                        <td>
                            <?php echo $this->Form->create('AdminEcrs',array('action'=>'create_category')); ?>
                                <h6>Create Category</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'placeholder'=>'Create Category','autocomplete'=>'off','required'=>true));?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                            
                            
                        </td>

                        <td>
                            <?php echo $this->Form->create('AdminEcrs',array('action'=>'create_type')); ?>
                                <h6>Create Type</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr >
                                        <td>
                                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category1','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('type',array('label'=>false,'placeholder'=>'Add Type',"class"=>"form-control",'autofill'=>'false','required'=>true));?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
         
                        <td>
                            <?php echo $this->Form->create('AdminEcrs',array('action'=>'create_sub_type1')); ?>
                                <h6>Create Sub Type1</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'options'=>$Category,'empty'=>'Select Category','id'=>'category2','required'=>true));?>
                                            <?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'options'=>'','empty'=>'Select Type','id'=>'type1','required'=>true));?>
                                            <?php echo $this->Form->input('sub_type1',array('label'=>false,'placeholder'=>'Add Sub Type 1',"class"=>"form-control",'autofill'=>'false','required'=>true));?> 
                                            <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->create('AdminEcrs',array('action'=>'create_sub_type2')); ?>
                                <h6>Create Sub Type2</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category3','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','empty'=>'Select Type','id'=>'type2','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','empty'=>'Select Sub Type 1','id'=>'sub_type1','required'=>true,"class"=>"form-control"));?>
                                             <?php echo $this->Form->input('sub_type2',array('label'=>false,'placeholder'=>'Add Sub Type 2','id'=>'rds','autofill'=>'false','required'=>true,"class"=>"form-control"));?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->create('AdminEcrs',array('action'=>'create_sub_type3')); ?>
                                <h6>Create Sub Type3</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'category4','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','empty'=>'Select Type','id'=>'type3','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','empty'=>'Select Sub Type 1','id'=>'sub_type2','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','empty'=>'Select Sub Type 2','id'=>'sub_type2_2','required'=>true,"class"=>"form-control"));?>
                                            <?php echo $this->Form->input('sub_type3',array('label'=>false,'placeholder'=>'Add Sub Type 3','autofill'=>'false','required'=>true,"class"=>"form-control"));?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                    </tr>
                </table>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<?php if(isset($data) && !empty($data)){ ?>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>ECR TREE STRUCTURE</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
               
                <ul id="someID">
  								
				<?php
                      foreach($data as $post1): 
						if($post1['ClientCategory']['Label']==1){?><li>
                                	<?php echo "<font color=\"#336666\">".$post1['ClientCategory']['ecrName'].' First'."</font>";?>
                                	<?php 
											?>
												<ul>
											<?php
											foreach($data as $post2):
												if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
													{?><li><?php
														 echo $post2['ClientCategory']['ecrName'].' Second<br>';
														?>
                                                            	<ul>
																<?php
														foreach($data as $post3):
															if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
															{?><li> <?php
																 echo $post3['ClientCategory']['ecrName'].' Third<br>';
																?>
                                                                    	<ul>
																		<?php
																foreach($data as $post4):
																	if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																	{?><li> <?php
																		 echo "".$post4['ClientCategory']['ecrName'].' Fourth<br>';
																		?>
                                                                        <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																				{?><li><?php
																					 echo "".$post5['ClientCategory']['ecrName'].' Fifth';
																				
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
        </div>
    </div>
</div>
 <?php }?>



    

          