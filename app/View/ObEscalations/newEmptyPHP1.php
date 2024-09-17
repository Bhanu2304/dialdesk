<ol class="breadcrumb">
<li><a href="index.html">Home</a></li>
<li><a href="#">Advanced Forms</a></li>
<li class="active"><a href="ui-forms.html">Form Components</a></li>
</ol>

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
        <?php echo $this->Form->create('Escalation',array('action'=>'add','class'=>'form-horizontal row-border')); ?>
        <div ng-app="">
        <table id="table">
        <tr>
            <td>Select Notification</td>
            <td><?php echo $this->Form->input('notification',array('label'=>false,'options'=>array('alert'=>'Alert','Escalation'=>'Escalation'),'empty'=>'Select Notification','required'=>true,'class'=>'form-control'));?></td>
            <td></td><td></td>
	</tr>
        
        <tr>
            <td>Select Category</td>
            <td><?php echo $this->Form->input('Parent',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'Parent1','required'=>true,'class'=>'form-control'));?></td>
            <td></td><td>&nbsp;</td>
	</tr>
        <tr>
            <td>Find Sub - Category</td>
            <td>
            <button  type="button" id="button" onClick="escalation_getChild()" class="btn-raised btn-primary btn">Find Sub-Category</button>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Select Alert/Escalation Type</td>
            <td><?php echo $this->Form->input('timer',array('label'=>false,'options'=>array('Year','Month','Week','Days','Hour'),'empty'=>'Select Time','required'=>true,'class'=>'form-control'));?></td>
            <td></td><td></td>
        </tr>

        <tr>
            <td>Select Alert/Escalation Time</td>
            <td><?php echo $this->Form->input('dater0',array('label'=>false,'id'=>'datetimepicker','class'=>'cl1','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
            <?php $month_days=array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','Month End'=>'Month End'); echo $this->Form->input('dater1',array('label'=>false,'options'=>$month_days,'id'=>'month','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control')); unset($month_days);?>                
            <?php $week_days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');echo $this->Form->input('dater2',array('label'=>false,'id'=>'week','options'=>$week_days,'style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control')); unset( $week_days);?>                
            <?php echo $this->Form->input('dater3',array('label'=>false,'id'=>'datetimepicker1','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
            <?php echo $this->Form->input('dater4',array('label'=>false,'id'=>'datetimepicker5','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
            </td>
            <td></td><td></td>
	</tr>
                    
	<tr>
            <td>Please Select Notification</td>
            <td>
            <?php echo $this->Form->input('type',array('label'=>false,'options'=>array('email'=>'Email','sms'=>'SMS','both'=>'Both'),'id'=>'','required'=>true,'class'=>'form-control'));?>
            </td><td></td><td></td>
        </tr>
	<tr>
            <td>Fill Email Id</td>
            <td><?php echo $this->Form->input('email',array('label'=>false,'required'=>true,'type'=>'email','class'=>'form-control'));?></td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Fill Contact No</td>
            <td>
            <?php echo $this->Form->input('sms',array('label'=>false,'required'=>true,'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control'));?>
            </td><td></td><td>&nbsp;</td>
        </tr>
	<tr>
            <td width="30%">Fill <div id="msg">Email or SMS Format </div></td>
            <td width="35%"><?php echo $this->Form->textArea('format',array('label'=>false,'ng-model'=>'name','required'=>true,'class'=>'form-control'));?></td>
					
            <td rowspan="2" width="5%"><button name="add_fields" type="button" ng-click="name = name +'[tag1]' +select +'[/tag1]'" class="btn-raised btn-primary btn">ADD</button></br>
            <button name="clear_fields" type="button" ng-click="name = ''" class="btn-raised btn-primary btn">Clear</button></td>
            <td rowspan="3" width="30%"><?php echo $this->Form->textArea('field',array('label'=>false,'style'=>'height: 250pt','value'=>"{{name}}",'required'=>true,'readOnly'=>true,'class'=>'form-control'));?></td>
        </tr>
	<tr>
            <td>Please Add Virtual Fields</td>
            <td>
                <?php echo $this->Form->input('field_set1',array('label'=>false,'options'=>$field_send1,'multiple'=>'multiple',"ng-model"=>"select",'class'=>'form-control'));?>
            </td><td>&nbsp;</td>
        </tr>

        <tr>
            <td>Please Add Virtual Fields</td>
            <td>
                <?php echo $this->Form->input('field_set2',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 100pt','class'=>'form-control'));?>
            </td>
            <td><button name="add_fields2" type="button" ng-click="name = name +'[tag]' +select2 +'[/tag]'" class="btn-raised btn-primary btn">ADD</button></br>
            <button name="clear_fields" type="button" ng-click="name = ''" class="btn-raised btn-primary btn">Clear</button>
            </td>
        </tr>
        <tr>
            <td><br/>
            <input type="submit"  value="ADD" class="btn-raised btn-primary btn">
            </td>
						
            <td align="right"><br/>
            </td>
        </tr>
        </table>
        </div>   
            <?php echo $this->Form->end(); ?>
        </div>  
    </div>
</div>
</div>