<?php	
echo $this->Html->script('jquery-ui');
echo $this->Html->script('angular.min');
echo $this->Html->script('capture');
//echo $this->Html->script('Escalation');
echo $this->Html->script('training');
echo $this->Html->script('listCollapse');
?>
<script>
function getClient(){
    $("#esclation_form").submit();	
}
$(function(){
    $( "#tabs" ).tabs();
});
$(function() {
    $( "#tabs2" ).tabs();
});
$(function() {
    $( "#tabs3" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs3 li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
});



$(document).ready(function(){
    $("#button").click(function(){
	parent= $("#Parent1").val();
        label = 2;	
	if(parent==''){ return;}
	if(typeof $("#Parent2").val()!=='undefined'){
			parent = $("#Parent2").val();
		 	label =3;
		 }		 
	if(typeof $("#Parent3").val()!=='undefined')
		 {
			parent = $("#Parent3").val();
		 	label =4;
		 }

		 if(typeof $("#Parent4").val()!=='undefined')
		 {
			parent = $("#Parent4").val();
		 	label =5;
		 }

		 if(typeof $("#Parent5").val()!=='undefined')
		 {
			return;
		 } 
	
	$.post("AdminEscalations/getParent",
        {
          parent_id: parent,
          Label: label,
          Client:$("#slclient").val()
        },
        function(data,status)
        {    
            if(data!='')$('#table tr:eq('+(label-1)+')').after("<tr><td>Select Child</td><td>"+data+"</td></tr>");
        });
});});





$(document).ready(function(){
	$("#Parent1").on('change',function(){$.post("AdminEscalations/getParent",
        {
          parent_id: $("#Parent1").val(),
          Label: 2,
          Client:$("#slclient").val()
        },
        function(data,status){i=2;
            		if(data == ''){if(typeof $("#Parent2").val()!=='undefined'){$('#Parent2').remove();$("#table").find("tr:eq("+i+")").remove(); }}
			else {$('#Parent2').replaceWith(data); i=3;}

			if(typeof $("#Parent3").val()!=='undefined')
		 	{
			$('#Parent3').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}
			if(typeof $("#Parent4").val()!=='undefined')
		 	{
			$('#Parent4').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}

			if(typeof $("#Parent5").val()!=='undefined')
		 	{
			$('#Parent5').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}
			
        });});});

$(document).on('change','#Parent2',function(){$.post("AdminEscalations/getParent",
        {
			
          parent_id: this.value,
          Label: 3,
          Client:$("#slclient").val()
        },
        function(data,status){
            i=3;
			if(data == ''){if(typeof $("#Parent3").val()!=='undefined'){$('#Parent3').remove();$("#table").find("tr:eq("+i+")").remove();}}
			else {$('#Parent3').replaceWith(data); i=4;}
			if(typeof $("#Parent4").val()!=='undefined')
		 	{
			$('#Parent4').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}
			if(typeof $("#Parent5").val()!=='undefined')
		 	{
			$('#Parent5').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}

        });});

$(document).on('change','#Parent3',function(){$.post("AdminEscalations/getParent",
        {
			
          parent_id: this.value,
          Label: 4,
          Client:$("#slclient").val()
        },
        function(data,status){
			i=4;
			if(data == ''){if(typeof $("#Parent4").val()!=='undefined'){$('#Parent4').remove();$("#table").find("tr:eq("+i+")").remove();}}
			else {$('#Parent4').replaceWith(data); i = 5;}


			if(typeof $("#Parent5").val()!=='undefined')
		 	{
			$('#Parent5').remove();$("#table").find("tr:eq("+i+")").remove();
		 	}

        });});

$(document).on('change','#Parent4',function(){$.post("AdminEscalations/getParent",
        {
			
          parent_id: this.value,
          Label: 5,
          Client:$("#slclient").val()
        },
        function(data,status){
            
			$('#Parent5').replaceWith(data);

        });});

$(document).ready(function(){
    $("#add_fields").click(function(){
		frm=$("#AdminEscalationsFieldSet").val();
		vir = $("#AdminEscalationsVirtual").val();
		$("#AdminEscalationsVirtual").val(vir+frm+",");
		frmvalue = $("#AdminEscalationsFieldSet option:selected").text();
		data =$("#AdminEscalationsField").val();
		$("#AdminEscalationsField").val(data+" <"+frmvalue+"> ");
		data =$("#AdminEscalationsFormat").val();
		//$("#AdminEscalationsFormat").val(data+" <"+frmvalue+"> ");
		});});

$(document).ready(function(){
    $("#clear_fields").click(function(){
		$("#AdminEscalationsField").val("");
		$("#AdminEscalationsFormat").val("");
		$("#AdminEscalationsVirtual").val("");
		});});

$(document).ready(function(){
    $("#AdminEscalationsTimer").on('change',function(){
		value = $("#AdminEscalationsTimer").val();
			$("#datetimepicker").hide();
			$("#datetimepicker1").hide();
			$("#month").hide();
			$("#datetimepicker3").hide();
			$("#week").hide();
			$("#datetimepicker5").hide();	
			$("#datetimepicker6").hide();
			$("#datetimepicker7").hide();
			$("#datetimepicker8").hide();
			$("#datetimepicker9").hide();
		
		if(value == '0'){
			$("#datetimepicker").show();
		}
		else if(value == '1'){
			$("#month").show();
		}
		else if(value == '2'){
			$("#week").show();
		}
		else if(value == '3'){
			$("#datetimepicker1").show();
		}
		else if(value == '4'){
			$("#datetimepicker5").show();
		}
		else if(value == '5'){
			$("#datetimepicker9").show();
		}
		else if(value == '6'){
			$("#datetimepicker6").show();
		}

		});});

</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Alert & Escalation</h5>
            </header>
            <div id="div-1" class="accordion-body collapse in body">
                <?php echo $this->Form->create('AdminEscalations',array('action'=>'index','id'=>'esclation_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?> 
                
                <?php if(isset($clientid) && !empty($clientid)){?>  
                    <?php echo $this->Form->create('AdminEscalations',array('action'=>'add')); ?>
                        <font style="color: red;" ><?php echo $this->Session->flash(); ?></font>
                        <div ng-app="">
                            <div id="tabs">
                                <ul>
                                    <li><a href="#tabs-1">Escalation Field</a></li>
                                    <li><a href="#tabs-2">Timer</a></li>
                                    <li><a href="#tabs-3">Report Fields</a></li>
                                  </ul>
                                  <div id="tabs-1">
                                        <!--
                                        <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                                        -->
                                        <table id="table" class="table table-striped table-bordered datatables" border="0" cellpadding="2" cellspacing="2">
                                            <tr>
                                                <td align="center">Select Notification</td>
                                                <td><?php echo $this->Form->input('notification',array('label'=>false,'options'=>array('alert'=>'Alert','Escalation'=>'Escalation'),'empty'=>'Select Notification','required'=>true,));?></td>
                                            </tr>

                                            <tr>
                                                <td align="center">Select Category</td>
                                                <td><?php echo $this->Form->input('Parent',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'Parent1','required'=>true));?></td>                                                                                     
                                            </tr>
                                            <tr>
                                                <td  align="center">Find Sub - Category</td>
                                                <td><button  type="button" id="button" class="btn-raised btn-primary btn">Find Sub-Category</button></td>          
                                            </tr>
                                            <tr>
                                                <td colspan="4"><input type="button" value="Back" class="btn-raised btn-primary btn" onclick="window.history.back()" /></td> 
                                            </tr>
                                    </table>
                                </div>
                                
                                <div id="tabs-2">
                                    <table id="table" class="table table-striped table-bordered datatables" border="0" cellpadding="2" cellspacing="2">
                                        <tr>
                                            <td>Select Alert/Escalation Type</td>
                                            <td><?php echo $this->Form->input('timer',array('label'=>false,'options'=>array('Year','Month','Week','Days','Hour'),'empty'=>'Select Time','required'=>true));?></td>
                                        </tr>
                                        <tr>
                                            <td>Select Alert/Escalation Time</td>
                                            <td>
                                                 <?php echo $this->Form->input('dater0',array('label'=>false,'id'=>'datetimepicker','class'=>'cl1','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
                                                <?php $month_days=array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','Month End'=>'Month End'); echo $this->Form->input('dater1',array('label'=>false,'options'=>$month_days,'id'=>'month','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control')); unset($month_days);?>                
                                                <?php $week_days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');echo $this->Form->input('dater2',array('label'=>false,'id'=>'week','options'=>$week_days,'style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control')); unset( $week_days);?>                
                                                <?php echo $this->Form->input('dater3',array('label'=>false,'id'=>'datetimepicker1','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
                                                <?php echo $this->Form->input('dater4',array('label'=>false,'id'=>'datetimepicker5','style'=>'display:none;','readOnly'=>true,'required'=>true,'class'=>'form-control'));?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Please Select Notification</td>
                                            <td><?php echo $this->Form->input('type',array('label'=>false,'options'=>array('email'=>'Email','sms'=>'SMS','both'=>'Both'),'id'=>'','required'=>true));?></td>
                                        </tr>
                                        <tr>
                                            <td>Fill Email Id</td>
                                            <td><?php echo $this->Form->input('email',array('label'=>false,'required'=>true,'type'=>'email'));?></td>
                                        </tr>
                                        <tr>
                                            <td>Fill Contact No</td>
                                            <td><?php echo $this->Form->input('sms',array('label'=>false,'onKeyPress'=>'return isNumberKey(event)'));?></td>
                                        </tr> 
                                    </table>
                                </div>
                                
                                <div id="tabs-3">
                                    <table id="table" class="table table-striped table-bordered datatables" border="0" cellpadding="2" cellspacing="2">
                                        <tr>
                                            <td width="30%">Fill <div id="msg">Email or SMS Format </div></td>
                                            <td width="35%">                                           
                                                <?php echo $this->Form->textArea('format',array('label'=>false,'ng-model'=>'name','required'=>true,'class'=>'form-control'));?>                                         
                                            </td>		
                                            <td rowspan="2" width="10%">
                                            
                                            <button name="add_fields" type="button" ng-click="name = name +'[tag1]' +select +'[/tag1]'" class="btn-raised btn-primary btn">ADD</button><br/><br/>
                                            <button name="clear_fields" type="button" ng-click="name = ''" class="btn-raised btn-primary btn">Clear</button>
                                            
                                            </td>
                                            <td rowspan="3" width="25%" align="center">
                                           
                                            <?php echo $this->Form->textArea('field',array('label'=>false,'style'=>'height: 290pt','value'=>"{{name}}",'required'=>true,'readOnly'=>true,'class'=>'form-control'));?>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Please Add Virtual Fields</td>
                                            <td>
                                                <?php echo $this->Form->input('field_set1',array('label'=>false,'options'=>$field_send1,'multiple'=>'multiple',"ng-model"=>"select",'class'=>'form-control'));?>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td>Please Add Virtual Fields</td>
                                            <td>
                                                <?php echo $this->Form->input('field_set2',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 100pt','class'=>'form-control'));?>
                                            </td>
                                            <td>
                                                <button name="add_fields2" type="button" ng-click="name = name +'[tag]' +select2 +'[/tag]'" class="btn-raised btn-primary btn">ADD</button><br/><br/>
                                                <button name="clear_fields" type="button" ng-click="name = ''" class="btn-raised btn-primary btn">Clear</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="submit"  value="Submit" class="btn-raised btn-primary btn"></td>
                                            <td></td><td></td> <td></td> 
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>   
                        <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                        <?php echo $this->Form->end(); ?>
                    <?php }?>
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
