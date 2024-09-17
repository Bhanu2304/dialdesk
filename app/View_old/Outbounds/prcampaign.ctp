
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Bound Campaign</a></li>
        <li class="active"><a href="#">Category Creation</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Campaign Base Category Creation</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Category Creation</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
					<?php echo $this->Form->create('Outbounds',array('action'=>'create_category')); ?>
                		<h4>Create Category</h4>
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                        	<tr>
                                <td>Create Campaign Name<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'required'=>true));?></td>
                            </tr>
                            <tr>
                                <td>Create Category<font style="color:red;">*</font></td>                                             
                                <td><?php echo $this->Form->input('category',array('label'=>false,'autocomplete'=>'off','required'=>true));?></td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                <input type="submit" class="btn btn-raised btn-default btn-primary" value="Create" >
                                </td>
                            </tr>
						</table>             
					<?php echo $this->Form->end(); ?>

					<?php echo $this->Form->create('Outbounds',array('action'=>'create_type')); ?>
                    	<h4>Create Type</h4>
                      	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                            <tr>
                                <td>Select Campaign Name<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('campaign1',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign1','required'=>true));?></td>
                            </tr>      	
                            <tr>
                                <td>Select Category<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('parent1',array('label'=>false,'options'=>'','empty'=>'Select Category','id'=>'parent1','required'=>true));?></td>
                            </tr>                                
                           	<tr>
                           		<td>Add Type<font style="color:red;">*</font></td>				
                              	<td><?php echo $this->Form->input('type',array('label'=>false,'autofill'=>'false','required'=>true));?></td>
                      		</tr> 
                          	<tr>
                      			<td colspan=2>
                            		<input type="submit" class="btn btn-raised btn-default btn-primary" value="Add" >
                              	</td>
                         	</tr>
                      	</table>  
                    <?php  echo $this->Form->end(); ?>

					<?php echo $this->Form->create('Outbounds',array('action'=>'create_sub_type1')); ?>
                    	<h4>Create Sub Type1</h4>   
                      	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                       		<tr>
                            	<td>Select Campaign Name<font style="color:red;">*</font></td>				
                             	<td><?php echo $this->Form->input('campaign2',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign2','required'=>true));?></td>
                         	</tr>     	
                        	<tr>
                     			<td>Select Category<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('parent2',array('label'=>false,'options'=>'','empty'=>'Select Category','id'=>'parent2','required'=>true));?></td>
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
                            	<td colspan=2>
                                	<input type="submit" class="btn btn-raised btn-default btn-primary" value="Add" >
                              	</td>
                          	</tr>
                       	</table>  
                    <?php echo $this->Form->end(); ?>

					<?php echo $this->Form->create('Outbounds',array('action'=>'create_sub_type2')); ?>
                    	<h4>Create Sub Type2</h4>   
                     	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                        	<tr>
                            	<td>Select Campaign Name<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign3','required'=>true));?></td>
                         	</tr>      	
                          	<tr>
                            	<td>Select Category<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('parent3',array('label'=>false,'options'=>'','empty'=>'Select Category','id'=>'parent3','required'=>true));?></td>
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
                                	<input type="submit" class="btn btn-raised btn-default btn-primary" value="Add" >
                             	</td>
                         	</tr>
                       	</table>
                    <?php echo $this->Form->end(); ?>

					<?php echo $this->Form->create('Outbounds',array('action'=>'create_sub_type3')); ?>
                    	<h4>Create Sub Type3</h4>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                         	<tr>
                                <td>Select Campaign Name<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign4','required'=>true));?></td>
                            </tr>     	
                            <tr>
                                <td>Select Category<font style="color:red;">*</font></td>				
                                <td><?php echo $this->Form->input('parent4',array('label'=>false,'options'=>'','empty'=>'Select Category','id'=>'parent4','required'=>true));?></td>
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
                                 <input type="submit" class="btn btn-raised btn-default btn-primary" value="Add" >
                                </td>
                            </tr>
                        </table>
              		<?php echo $this->Form->end(); ?>
 
          		</div> 
            </div>
        </div>
    </div> 





  	
