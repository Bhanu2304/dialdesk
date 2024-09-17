
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Bound Campaign</a></li>
        <li class="active"><a href="#">Outbound Capture Field Creation</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Outbound Capture Field Creation</h1>
    </div>
    <div class="container-fluid">
    	<div data-widget-group="group1">
      		<div class="panel panel-default" data-widget='{"draggable": "false"}'>
            	<div class="panel-heading">
                	<h2>Capture Field</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                	<?php echo $this->Form->create('ObCreations',array('action'=>'add')); ?>
                    	<table id="table" class="table table-striped table-bordered datatables">                    	
                            <tr>
                                <td>Enter Field Name<font style="color:red;">*</font></td>
                                <td><?php echo $this->Form->input('FieldName',array('label'=>false,'placeholder'=>'Please Enter Field Name','autocomplete'=>'off','required'=>true));?></td>
                            </tr>
                       
			   				<tr>
                           		<td>Choose Field Type<font style="color:red;">*</font></td>
                           		<td>         
									<input type="radio" name="data[ObCreations][FieldType]" id="ObCreationsFieldTypeTextBox" value="TextBox" required="required" onclick="ob_closeDropDown()">Text Box
									<input type="radio" name="data[ObCreations][FieldType]" id="ObCreationsFieldTypeTextBox" value="TextArea" required="required" onclick="ob_closeDropDown()">Text Area
									<input type="radio" name="data[ObCreations][FieldType]" id="ObCreationsFieldTypeTextBox" value="DropDown" required="required" onclick="ob_getDropDown()">Drop Down
                            	</td>
                        	</tr>
                            
                       		<tr><div id="select"></div></tr>
                            
                            <tr>
                                <td>Choose Field Validation</td>
                                <td>
                                        <?php echo $this->Form->Radio('FieldValidation',array('Numeric'=>'Numeric','Char'=>'Char'),array('legend'=>false));?>
                                </td>
                            </tr>
                      
                            <tr>
                                <td>Check for Field Required</td>
                                <td>
                                        <?php echo $this->Form->Checkbox('RequiredCheck',array('label'=>false));?>
                                </td>
                            </tr>

                           
                      	</table>
                     	<button class="btn btn-raised btn-default btn-primary">Add</button>	
      					<input type="hidden" value="" name="down" id="down">
					<?php echo $this->Form->end(); ?>
                    
          			<?php echo $this->Form->create('ObCreations',array('action'=>'setPriority')); ?>
                        <h1>Created Fields</h1>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                            <tr>
                                <th>Field Name</th>
                                <th>Field Type</th>
                                <th>Field Validation</th>
                                <th>Required</th>
                                <th>Priority</th>
                            </tr>
                            <?php foreach($data as $post): ?>
                            <tr>
                                <td><?=$post['ObField']['FieldName']?></td>
                                <td><?=$post['ObField']['FieldType']?></td>
                                <td><?=$post['ObField']['FieldValidation']?></th>
                                <td><?php if($post['ObField']['RequiredCheck']==1){echo "Yes";} else{ echo "No";}?></td>
                                <td><?php echo $this->Form->input($post['ObField']['Id'],array('label'=>false,'onKeyPress'=>'','autocomplete'=>'off','placeholder'=>'Priority','value'=>$post['ObField']['Priority'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                        <button class="btn btn-raised btn-default btn-primary">Update</button>
            		<?php echo $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
  	</div> 








