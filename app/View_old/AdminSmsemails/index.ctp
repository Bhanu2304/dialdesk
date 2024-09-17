<script>
function clientWiseData(){
	$('#sms_email_form').attr('action', '<?php echo $this->webroot;?>AdminSmsemails/index');
	$("#sms_email_form").submit();
}

function addSmsEmail(){
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var phoneNum = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
	
	if($.trim($("#AdminSmsemailsNotification").val()) ===""){
		$("#AdminSmsemailsNotification").focus();
		return false;
	}
	else if($.trim($("#Parent1").val()) ===""){
		$("#Parent1").focus();
		return false;
	}
	else if($.trim($("#AdminSmsemailsTimer").val()) ===""){
		$("#AdminSmsemailsTimer").focus();
		return false;
	}
	else if($.trim($("#AdminSmsemailsTimer").val()) ==="0" && $.trim($("#datetimepicker").val()) ===""){
		$("#datetimepicker").focus();
		return false;
	}
	else if($.trim($("#AdminSmsemailsTimer").val()) ==="1" && $.trim($("#month").val()) ===""){
		$("#month").focus();
		alert('Select Month.');
		return false;
	}
	else if($.trim($("#AdminSmsemailsTimer").val()) ==="2" && $.trim($("#week").val()) ===""){
		$("#week").focus();
		alert('Select Week.');
		return false;
	}
	else if($.trim($("#AdminSmsemailsTimer").val()) ==="3" && $.trim($("#datetimepicker1").val()) ===""){
		$("#datetimepicker1").focus();
		return false;
	}
	else if($.trim($("#AdminSmsemailsTimer").val()) ==="4" && $.trim($("#datetimepicker5").val()) ===""){
		$("#datetimepicker5").focus();
		return false;
	}
	else if($.trim($("#notifi_type").val()) ==="email" && $.trim($("#AdminSmsemailsEmail").val()) ===""){
		$("#AdminSmsemailsEmail").focus();
		return false;
	}
	else if($.trim($("#notifi_type").val()) ==="sms" && $.trim($("#AdminSmsemailsSms").val()) ===""){
		$("#AdminSmsemailsSms").focus();
		return false;
	}
	else if($.trim($("#notifi_type").val()) ==="both" && $.trim($("#AdminSmsemailsEmail").val()) ===""){
		$("#AdminSmsemailsEmail").focus();
		return false;
	}
	else if($.trim($("#notifi_type").val()) ==="both" && $.trim($("#AdminSmsemailsSms").val()) ===""){
		$("#AdminSmsemailsSms").focus();
		return false;
	}
	else if($.trim($("#notifi_type").val()) ==="both" && $.trim($("#AdminSmsemailsSms").val()) ===""){
		$("#AdminSmsemailsSms").focus();
		return false;
	}
	
	else if (!filter.test($.trim($("#AdminSmsemailsEmail").val()))) {
		$("#AdminSmsemailsEmail").focus();
		return false;
	}
	else if(!$.trim($("#AdminSmsemailsSms").val()).match(phoneNum)) {
		$("#AdminSmsemailsSms").focus();
		return false;
	}
	else if($.trim($("#AdminSmsemailsField").val()) ===""){
		$("#AdminSmsemailsFormat").focus();
		return false;
	}
	else{
		$("#sms_email_form").submit();
	}	
}

$(document).ready(function(){
	$("#Parent1").on('change',function(){$.post("<?php echo $this->webroot;?>AdminSmsemails/getParent",
        {
          parent_id: $("#Parent1").val(),
		  Client: $("#AdminSmsemailsClient").val(),
          Label: 2
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

$(document).on('change','#Parent2',function(){$.post("<?php echo $this->webroot;?>AdminSmsemails/getParent",
        {
			
          parent_id: this.value,
		   Client: $("#AdminSmsemailsClient").val(),
          Label: 3
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

$(document).on('change','#Parent3',function(){$.post("<?php echo $this->webroot;?>AdminSmsemails/getParent",
        {
			
          parent_id: this.value,
		  Client: $("#AdminSmsemailsClient").val(),
          Label: 4
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

$(document).on('change','#Parent4',function(){$.post("<?php echo $this->webroot;?>AdminSmsemails/getParent",
        {
			
          parent_id: this.value,
		  Client: $("#AdminSmsemailsClient").val(),
          Label: 5
        },
        function(data,status){
            
			$('#Parent5').replaceWith(data);

        });});
		
function timerType(data){
	value = data.value;
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
}

function getChild(){
	
	var client=$("#AdminSmsemailsClient").val();
	
	parent= $("#Parent1").val();
    label = 2;	
	if(parent==''){ return;}
	if(typeof $("#Parent2").val()!=='undefined'){
		parent = $("#Parent2").val();
		 label =3;
	}		 
	if(typeof $("#Parent3").val()!=='undefined'){
		parent = $("#Parent3").val();
		 label =4;
	}
	if(typeof $("#Parent4").val()!=='undefined'){
		parent = $("#Parent4").val();
		 label =5;
	}
	if(typeof $("#Parent5").val()!=='undefined'){
		return;
	} 
	$.post("<?php echo $this->webroot;?>AdminSmsemails/getParent",{parent_id: parent,Label: label,Client:client},function(data,status){
		if(data!='')$('#table tr:eq('+(label-1)+')').after("<tr><td>Select Child</td><td>"+data+"</td><td></td><td></td></tr>");
  	});
}

</script>
<div class="row-fluid">
	<div class="span12">
		<div class="box dark">
  			<header>
 				<div class="icons"><i class="icon-edit"></i></div>
          		<h5>SMS & Email Text Master</h5>
			</header>
    		<div id="div-1" class="accordion-body collapse in body">
            	<h3>Add Client Wise Email/Sms</h3>
      			<?php echo $this->Form->create('AdminSmsemails',array('action'=>'add','id'=>'sms_email_form')); ?>
                <div  style="margin-left:0%;">
             		<?php echo $this->Form->input('client',array('label'=>false,'onchange'=>'clientWiseData();','options'=>$client,'empty'=>'Select Client','required'=>true));?>
                </div>
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
             	<hr/>
             	<?php if(isset($clid) && $clid !=""){?>
             	<font style="color:red;"><?php echo $this->Session->flash(); ?></font>
           		<div ng-app="">
              		<table id="table" class="display table table-bordered table-condensed table-hovered sortableTable" >
                  		<tr>
                            <td>Select Notifiacation<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('notification',array('label'=>false,'options'=>array('alert'=>'Alert','Escalation'=>'Escalation'),'empty'=>'Select Notification','required'=>true));?></td>
                            <td></td><td></td>
                        </tr>
                    
                        <tr>
                            <td>Select Category<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('Parent',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'Parent1','required'=>true));?></td>
                        	<td></td><td></td>
                        </tr>
                       
                       <tr>
                       		<td></td>
                       		<td><button  type="button" id="button" onClick="getChild()">Find Child</button></td>
                            <td></td><td></td>
                       </tr>
                    
                        <tr>
                            <td>Select Alert/Escalation Type<font style="color:red;">*</font></td>
                            <td><?php echo $this->Form->input('timer',array('label'=>false,'onchange'=>'timerType(this);','options'=>array('Year','Month','Week','Days','Hour'),'empty'=>'Select Time','required'=>true));?></td>
                            <td></td><td></td>
                        </tr>

                        <tr>
                     	<td>Select Alert/Escalation Time<font style="color:red;">*</font></td>
                            <td>
								<?php echo $this->Form->input('dater0',array('label'=>false,'id'=>'datetimepicker','class'=>'cl1','style'=>'display:none;'));?>
                                <?php 
								$month_days=array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','Month End'); 
								echo $this->Form->input('dater1',array('label'=>false,'options'=>$month_days,'id'=>'month','style'=>'display:none;','empty'=>'Select Month')); unset($month_days);?>
                                
                                <?php 
								$week_days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
								echo $this->Form->input('dater2',array('label'=>false,'id'=>'week','options'=>$week_days,'style'=>'display:none;','empty'=>'Select Week')); unset( $week_days);?>
                                
                                <?php echo $this->Form->input('dater3',array('label'=>false,'id'=>'datetimepicker1','style'=>'display:none;'));?>
                                <?php echo $this->Form->input('dater4',array('label'=>false,'id'=>'datetimepicker5','style'=>'display:none;'));?>
                            </td>
                            <td></td><td></td>
                        </tr>
                    
                        <tr>
                            <td>Please Select Notification</td>
                            <td><?php echo $this->Form->input('type',array('label'=>false,'options'=>array('email'=>'Email','sms'=>'SMS','both'=>'Both'),'id'=>'notifi_type'));?></td>
                            <td></td><td></td>
                        </tr>
                        
						<tr>
                			<td>Fill Email Id</td>
                			<td><?php echo $this->Form->input('email',array('label'=>false,'required'=>true,'type'=>'email'));?></td>
                    		<td></td><td></td>
                		</tr>
                        
						<tr>
                        	<td>Fill Contact No</td>
                			<td><?php echo $this->Form->input('sms',array('label'=>false,'required'=>true,'onKeyPress'=>'return isNumberKey(event)'));?></td>
                            <td></td><td></td>
                		</tr>
                        
                        <tr>
                            <td>Fill <div id="msg">Email or SMS Format </div></td>
                            <td><?php echo $this->Form->textArea('format',array('label'=>false,'ng-model'=>'name','required'=>true,'style'=>'width:295px'));?></td>
                            
                            <td rowspan="2">
                            	<button name="add_fields" type="button" ng-click="name = name +'[tag1]' +select +'[/tag1]'">ADD</button>
                            	<button name="clear_fields" type="button" ng-click="name = ''">Clear</button></td>
                            <td rowspan="3"><?php echo $this->Form->textArea('field',array('label'=>false,'style'=>'height: 250pt','value'=>"{{name}}",'required'=>true,'readOnly'=>true));?></td>
                        </tr>
                
                        <tr>
                            <td>Please Add Virtual Fields</td>
                            <td><?php echo $this->Form->input('field_set1',array('label'=>false,'options'=>$field_send1,'multiple'=>'multiple',"ng-model"=>"select",'style'=>'height: 100pt'));?></td>
                        </tr>

                        <tr>
                            <td>Please Add Virtual Fields</td>
                            <td><?php echo $this->Form->input('field_set2',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 100pt'));?></td>
                            <td>
                            	<button name="add_fields2" type="button" ng-click="name = name +'[tag]' +select2 +'[/tag]'">ADD</button>
                                <button name="clear_fields" type="button" ng-click="name = ''">Clear</button>
                            </td>
                        </tr>
					</table>
             	</div>
            	<p class="signin button"><input type="button" onclick="addSmsEmail();" style="width:75px;"  value="ADD" ></p>
                <?php }?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>

<script>

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
