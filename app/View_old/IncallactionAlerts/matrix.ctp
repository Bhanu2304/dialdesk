<div class="page-heading">            
    <h1>Alert & Escalation</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
      <div class="panel panel-default" data-widget='{"draggable": "false"}'>
	<div class="panel-heading">
            <h2><?php echo $this->Session->flash(); ?></h2>
            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
	</div>
	<div data-widget-controls="" class="panel-editbox"></div>
        <div class="panel-body">
            <?php echo $this->Form->create('Escalation',array('class'=>'form-horizontal row-border')) ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"><b>Client</b></label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('clientId',array('label'=>false,'class'=>'form-control','options'=>$client,'empty'=>'Select Client','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label"><b>Alert Type</b></label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','options'=>array('Alert'=>'Alert','Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label"><b>Category</b></label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value)",'required'=>true)); ?>
                </div>
            </div>
            <div class="form-group">             
                <div id="type"></div>
                <div id="subtype"></div>
                <div id="subtype1"></div>
            </div>
            <div class="form-group">
                <div id="subtype2"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">TAT</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('tat',array('label'=>false,'class'=>'form-control','placeholder'=>'TAT','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Person Name</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('personName',array('label'=>false,'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Designation</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Email Address</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('email',array('label'=>false,'class'=>'form-control','placeholder'=>'Email','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Mobile No.</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('mobileNo',array('label'=>false,'class'=>'form-control','placeholder'=>'Mobile No.','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Alert On</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('alertOn',array('label'=>false,'class'=>'form-control','placeholder'=>'Select','required'=>true)); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <input type="submit" name="Add" value="Add" class="btn-raised btn-primary btn" >
                </div>
                <div class="col-sm-2">
                    <input type="reset" name="Reset" value="Reset" class="btn-raised btn-primary btn" >
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
      </div>  
    </div>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
      <div class="panel panel-default" data-widget='{"draggable": "false"}'>
	<div data-widget-controls="" class="panel-editbox"></div>
        <div class="panel-body">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>SubType</th>
                    <th>SubType1</th>
                    <th>SubType2</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            <?php  $i =1;
            foreach($data as $d): $id = $d['Matrix']['id']; ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $d['Matrix']['personName']; ?></td>
                    <td><?php echo $d['Matrix']['categoryName']; ?></td>
                    <td><?php echo $d['Matrix']['typeName']; ?></td>
                    <td><?php echo $d['Matrix']['subtypeName']; ?></td>
                    <td><?php echo $d['Matrix']['subtype1Name']; ?></td>
                    <td><?php echo $d['Matrix']['subtype2Name']; ?></td>
                    <td><?php echo $d['Matrix']['mobileNo']; ?></td>
                    <td><?php echo $d['Matrix']['email']; ?></td>
                    <td><?php echo $this->Html->link('Edit',array('controller'=>'Escalations','action'=>'edit_matrix','?'=>array('id'=>$id,'edit'=>'edit')),array('class'=>'btn-raised btn-primary btn','fullbase'=>true)); 
                              echo $this->Html->link('Delete',array('controller'=>'Escalations','action'=>'edit_matrix','?'=>array('id'=>$id,'edit'=>'delete')),array('class'=>'btn-raised btn-primary btn','fullbase'=>true));
                    ?></td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>
      </div>  
    </div>
</div>