<div class="page-heading">            
    <h1>SMS Text Setting Client</h1>
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
                <label class="col-sm-2 control-label">Sender ID</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('senderId',array('label'=>false,'class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">SMS Text</label>
                <div class="col-sm-6">
                    <?php echo $this->Form->textArea('smsText',array('label'=>false,'class'=>'form-control','placeholder'=>'Validated SMS Text Otherwise message will be failed','required'=>true)); ?>
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
                    <th>Client</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>SubType</th>
                    <th>SubType1</th>
                    <th>SubType2</th>
                    <th>senderID</th>
                    <th>smsText</th>
                    <th>Action</th>
                </tr>
            <?php  $i =1;
            foreach($data as $d): ?>
                <tr>
                    <td><?php echo $i++; $id = $d['SMSText']['id'];  ?></td>
                    <td><?php echo $d['SMSText']['clientId']; ?></td>
                    <td><?php echo $d['SMSText']['categoryName']; ?></td>
                    <td><?php echo $d['SMSText']['typeName']; ?></td>
                    <td><?php echo $d['SMSText']['subtypeName']; ?></td>
                    <td><?php echo $d['SMSText']['subtype1Name']; ?></td>
                    <td><?php echo $d['SMSText']['subtype2Name']; ?></td>
                    <td><?php echo $d['SMSText']['senderID']; ?></td>
                    <td><?php echo $d['SMSText']['smsText']; ?></td>
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