<script>
    function preEscalation(){
        document.getElementById('escalationtab').click();
    }
    function nextTimer(){
        document.getElementById('timertab').click();
    }
    function nextReport(){
        document.getElementById('reporttab').click();
    } 
</script>
<ol class="breadcrumb">
    <li><?php echo $this->Html->link('Home',array('controller'=>'Homes','action'=>'index','full_base'=>true)); ?></li>
    <li><?php echo $this->Html->link('View Escalation',array('controller'=>'Escalations','action'=>'view','full_base'=>true)); ?></li>    
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
<div id="tabs">
  <ul>
    <li><a href="#tabs-1" id="escalationtab" >Escalation Field</a></li>
    <li><a href="#tabs-2" id="timertab" >Timer</a></li>
    <li><a href="#tabs-3" id="reporttab" >Report Fields</a></li>
  </ul>
  <div id="tabs-1">
        <table id="table" class="table table-striped table-bordered datatables" border="0" cellpadding="2" cellspacing="2">
        <tr>
            <td align="center">Select Notification</td>
            <td>
                <div class="form-group">
                    <div class="col-sm-10">
                        <?php echo $this->Form->input('notification',array('label'=>false,'options'=>array('alert'=>'Alert','Escalation'=>'Escalation'),'empty'=>'Select Notification','required'=>true,'class'=>'form-control'));?>
                    </div>
                </div>
            </td>
            
	</tr>
        
        <tr>
            <td  align="center">Select Category</td>
            <td>
                <div class="form-group">
                <div class="col-sm-10">
                <?php echo $this->Form->input('Parent',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'Parent1','required'=>true,'class'=>'form-control'));?>
                </div></div>
            </td>
            <td></td><td>&nbsp;</td>
	</tr>
        <tr>
            <td  align="center">Find Sub - Category</td>
            <td>
            <div class="form-group">
               <div class="col-sm-2">
                  
                </div> 
                <div class="col-sm-4">
                    <button  type="button" id="button" onClick="escalation_getChild()" class="btn-raised btn-primary btn">Find Sub-Category</button>
                </div>
                <div class="col-sm-2">
                  
                </div>
            </div>
            
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td colspan="4">
            <div class="form-group">
               <div class="col-sm-2">
                    <input type="button" value="Back" class="btn-raised btn-primary btn" onclick="window.history.back()" />   
               </div>
               <div class="col-sm-2">
                    <?php echo $this->Html->link('View', array('controller'=>'Escalations','action'=>'view'),array('class'=>'btn-raised btn-primary btn')); ?>
               </div>
                <div class="col-sm-2">
                    <input type="button" value="Next" class="btn-raised btn-primary btn" id="nextview" onclick="nextTimer()" />   
               </div>
                
            </div>
            </td>
        </tr>
        </table>
        </div>
        <div id="tabs-2">
                <div class="form-group">
                <label class="col-sm-2 control-label">Select Alert/Escalation Type</label>
                <div class="col-sm-6">
                    <?php echo $this->Form->input('timer',array('label'=>false,'options'=>array('Year','Month','Week','Days','Hour'),'empty'=>'Select Time','required'=>true,'class'=>'form-control'));?>
                </div></div>
                       
                <div class="form-group">
                <label class="col-sm-2 control-label">Select Alert/Escalation Time</label>
                <div class="col-sm-6">
            <?php echo $this->Form->input('dater0',array('label'=>false,'id'=>'datetimepicker','class'=>'cl1','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
            <?php $month_days=array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','Month End'=>'Month End'); echo $this->Form->input('dater1',array('label'=>false,'options'=>$month_days,'id'=>'month','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control')); unset($month_days);?>                
            <?php $week_days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');echo $this->Form->input('dater2',array('label'=>false,'id'=>'week','options'=>$week_days,'style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control')); unset( $week_days);?>                
            <?php echo $this->Form->input('dater3',array('label'=>false,'id'=>'datetimepicker1','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
            <?php echo $this->Form->input('dater4',array('label'=>false,'id'=>'datetimepicker5','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
                </div></div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Please Select Notification</label>
                <div class="col-sm-6">
            <?php echo $this->Form->input('type',array('label'=>false,'options'=>array('email'=>'Email','sms'=>'SMS','both'=>'Both'),'id'=>'','required'=>true,'class'=>'form-control'));?>
            </div></div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Fill Email Id</label>
                <div class="col-sm-6">
                <?php echo $this->Form->input('email',array('label'=>false,'required'=>true,'type'=>'email','class'=>'form-control'));?>
                </div></div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Fill Contact No</label>
                <div class="col-sm-6">
            <?php echo $this->Form->input('sms',array('label'=>false,'required'=>true,'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control'));?>
                </div></div>
            
            <div class="form-group">
                <div class="col-sm-2">
                    <input type="button" value="Prev" class="btn-raised btn-primary btn" id="nextview" onclick="preEscalation()" />   
               </div>
                <div class="col-sm-2">
                    <input type="button" value="Next" class="btn-raised btn-primary btn" id="nextview" onclick="nextReport()" />   
                </div>
            </div>
            
            

        </div>
        <div id="tabs-3">
        <table>
	<tr>
            <td width="30%">Fill <div id="msg">Email or SMS Format </div></td>
            <td width="35%">
            <div class="form-group">
                <div class="col-sm-10">
            <?php echo $this->Form->textArea('format',array('label'=>false,'ng-model'=>'name','required'=>true,'class'=>'form-control'));?>
                </div></div>
            </td>		
            <td rowspan="2" width="10%">
            <div class="form-group">
                <div class="col-sm-2">
            <button name="add_fields" type="button" ng-click="name = name +'[tag1]' +select +'[/tag1]'" class="btn-raised btn-primary btn">ADD</button></br>
            <button name="clear_fields" type="button" ng-click="name = ''" class="btn-raised btn-primary btn">Clear</button>
            </div></div>
            </td>
            <td rowspan="3" width="25%" align="center">
            <div class="form-group">
                <div class="col-sm-10">
            <?php echo $this->Form->textArea('field',array('label'=>false,'style'=>'height: 290pt','value'=>"{{name}}",'required'=>true,'readOnly'=>true,'class'=>'form-control'));?>
                </div>
            </div>
            </td>
        </tr>
	<tr>
            <td>Please Add Virtual Fields</td>
            <td>
            <div class="form-group">
                <div class="col-sm-10">
                <?php echo $this->Form->input('field_set1',array('label'=>false,'options'=>$field_send1,'multiple'=>'multiple',"ng-model"=>"select",'class'=>'form-control'));?>
                </div>
            </div>
            </td><td>&nbsp;</td>
        </tr>

        <tr>
            <td>Please Add Virtual Fields</td>
            <td>
            <div class="form-group">
                <div class="col-sm-10">
                <?php echo $this->Form->input('field_set2',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 100pt','class'=>'form-control'));?>
                </div>
             </div>
            </td>
            <td>
             <div class="form-group">
                <div class="col-sm-6">
            <button name="add_fields2" type="button" ng-click="name = name +'[tag]' +select2 +'[/tag]'" class="btn-raised btn-primary btn">ADD</button></br>
            <button name="clear_fields" type="button" ng-click="name = ''" class="btn-raised btn-primary btn">Clear</button>
            </div></div>
            </td>
        </tr>
        <tr>
            <td><br/>
                
                <input type="button" value="Prev" class="btn-raised btn-primary btn" id="nextview" onclick="nextTimer()" /> 
                
            <input type="submit"  value="Submit" class="btn-raised btn-primary btn">
            </td>
						
            <td align="right"><br/>
            </td>
        </tr>
        </table>
        </div>
        </div>
        </div>   
            <?php echo $this->Form->end(); ?>
        </div>  
    </div>



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
     <?php echo "<a href=\"?id=".base64_encode($post1['ClientCategory']['id'])."\"><font color=\"#336666\">".$post1['ClientCategory']['ecrName'].' First'."</font></a>";?>
		<ul>
			<?php
				foreach($data as $post2):
					if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
						{?><li><?php
							echo "<a href=\"?id=".base64_encode($post2['ClientCategory']['id'])."\"><font color=\"#336666\">".$post2['ClientCategory']['ecrName'].' Second</font></a>';?>
                              <ul>
								<?php
									foreach($data as $post3):
										if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
											{?><li> <?php
												echo "<a href=\"?id=".base64_encode($post3['ClientCategory']['id'])."\"><font color=\"#336666\">".$post3['ClientCategory']['ecrName'].' Third</font></a>';?>
                                                   <ul>
													  <?php
														foreach($data as $post4):
															if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																{?><li> <?php
																	echo "<a href=\"?id=".base64_encode($post4['ClientCategory']['id'])."\"><font color=\"#336666\">".$post4['ClientCategory']['ecrName'].' Fourth</font></a>';?>
                                                                      <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																					{?><li><?php
																					 	echo "<a href=\"?id=".base64_encode($post5['ClientCategory']['id'])."\"><font color=\"#336666\">".$post5['ClientCategory']['ecrName'].' Fifth</font></a>';
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
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
<thead>
<tr>
    <th>Email</th>
    <th>SMS</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
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

</tbody>
</table>
       
            </div>
        </div>  




</div>
</div>




<?php
echo $this->Html->css("jquery-ui");
echo $this->Html->css("jquery.datetimepicker");
echo $this->Html->css("jquery.datetimepicker.min");
?>
<script>/*
window.onerror = function(errorMsg) {
	$('#console').html($('#console').html()+'<br>'+errorMsg)
}*/

$.datetimepicker.setLocale('en');

$('#datetimepicker_format').datetimepicker({value:'2016/03/03 05:03', format: $("#datetimepicker_format_value").val()});
$("#datetimepicker_format_change").on("click", function(e){
	$("#datetimepicker_format").data('xdsoft_datetimepicker').setOptions({format: $("#datetimepicker_format_value").val()});
});
$("#datetimepicker_format_locale").on("change", function(e){
	$.datetimepicker.setLocale($(e.currentTarget).val());
});

$('#datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
startDate:	'2016/03/03',

});
$('#datetimepicker').datetimepicker({value:'',step:10});

$('.some_class').datetimepicker();

$('#default_datetimepicker').datetimepicker({
	formatTime:'H:i',
	formatDate:'d.m.Y',
	//defaultDate:'8.12.1986', // it's my birthday
	defaultDate:'+03.01.1970', // it's my birthday
	defaultTime:'10:00',
	timepickerScrollbar:false
});

$('#datetimepicker10').datetimepicker({
	step:5,
	inline:true
});
$('#datetimepicker_mask').datetimepicker({
	mask:'9999/19/39 29:59'
});

$('#datetimepicker1').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:1
});
$('#datetimepicker2').datetimepicker({
	datepicker:false,
	allowTimes:['1','2','15:00','17:00','17:05','17:20','19:00','20:00'],
	step:0
});
$('#datetimepicker3').datetimepicker({
	inline:true
});
$('#datetimepicker4').datetimepicker();
$('#open').click(function(){
	$('#datetimepicker4').datetimepicker('show');
});
$('#close').click(function(){
	$('#datetimepicker4').datetimepicker('hide');
});
$('#reset').click(function(){
	$('#datetimepicker4').datetimepicker('reset');
});
$('#datetimepicker5').datetimepicker({
	datepicker:false,
	format:'H:i',	
	step:1
});
$('#datetimepicker6').datetimepicker();
$('#destroy').click(function(){
	if( $('#datetimepicker6').data('xdsoft_datetimepicker') ){
		$('#datetimepicker6').datetimepicker('destroy');
		this.value = 'create';
	}else{
		$('#datetimepicker6').datetimepicker();
		this.value = 'destroy';
	}
});
var logic = function( currentDateTime ){
	if (currentDateTime && currentDateTime.getDay() == 6){
		this.setOptions({
			minTime:'11:00'
		});
	}else
		this.setOptions({
			minTime:'8:00'
		});
};
$('#datetimepicker7').datetimepicker({
	onChangeDateTime:logic,
	onShow:logic
});
$('#datetimepicker8').datetimepicker({
	onGenerate:function( ct ){
		$(this).find('.xdsoft_date')
			.toggleClass('xdsoft_disabled');
	},
	minDate:'-1970/01/2',
	maxDate:'+1970/01/2',
	timepicker:false
});
$('#datetimepicker9').datetimepicker({
	onGenerate:function( ct ){
		$(this).find('.xdsoft_date.xdsoft_weekend')
			.addClass('xdsoft_disabled');
	},
	weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
	timepicker:false
});
var dateToDisable = new Date();
	dateToDisable.setDate(dateToDisable.getDate() + 2);
$('#datetimepicker11').datetimepicker({
	beforeShowDay: function(date) {
		if (date.getMonth() == dateToDisable.getMonth() && date.getDate() == dateToDisable.getDate()) {
			return [false, ""]
		}

		return [true, ""];
	}
});
$('#datetimepicker12').datetimepicker({
	beforeShowDay: function(date) {
		if (date.getMonth() == dateToDisable.getMonth() && date.getDate() == dateToDisable.getDate()) {
			return [true, "custom-date-style"];
		}

		return [true, ""];
	}
});
$('#datetimepicker_dark').datetimepicker({theme:'dark'})


</script>
