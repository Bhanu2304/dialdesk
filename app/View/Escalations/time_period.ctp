<div class="page-heading">            
    <h1>Escalation Time Period</h1>
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
                <label class="col-sm-2 control-label"><b>Select Client</b></label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('clientId',array('label'=>false,'class'=>'form-control','options'=>$client,'empty'=>'Select Client','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label"><b>Type</b></label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('time_type',array('label'=>false,'class'=>'form-control','options'=>array('0'=>'Working Time','1'=>'Day Time'),'empty'=>'Select','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">Hours</label>
                <div class="col-sm-2">
                    <?php $hours = array();
                          for($i=1; $i<=24;$i++)
                            $hours[$i] = $i.' hrs.';
                            echo $this->Form->input('time_Hours',array('label'=>false,'class'=>'form-control','options'=>$hours,'empty'=>'Select','onchange'=>"getType(this.value)",'required'=>true)); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Start Time</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('start_time',array('label'=>false,'class'=>'form-control date-picker','placeholder'=>'Start Date','required'=>true)); ?>
                </div>
                <label class="col-sm-2 control-label">End Time</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->input('end_time',array('label'=>false,'class'=>'form-control date-picker','placeholder'=>'End Date','required'=>true)); ?>
                </div>
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
                    <th>Type</th>
                    <th>Hours</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Action</th>
                </tr>
            <?php  $i =1;
            foreach($data as $d): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $d['TimePeriod']['clientId']; ?></td>
                    <td><?php echo $d['TimePeriod']['time_type']; ?></td>
                    <td><?php echo $d['TimePeriod']['time_Hours']; ?></td>
                    <td><?php echo $d['TimePeriod']['start_time']; ?></td>
                    <td><?php echo $d['TimePeriod']['end_time']; ?></td>
                    <td><?php echo $d['TimePeriod']['id']; ?></td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>
      </div>  
    </div>
</div>