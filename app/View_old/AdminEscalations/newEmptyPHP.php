<div id="wrapper">
	<div id="register">
			<?php echo $this->Form->create('Escalation',array('action'=>'add')); ?>
            <fieldset id="fieldset1" >
                <h1>Alert & Escalation</h1>
                
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                <div class="form-bottom"><div ng-app="">
                    <table id="table">

                	<tr>
						<td>Select Notification<font style="color:red;">*</font></td>
						<td><?php echo $this->Form->input('notification',array('label'=>false,'options'=>array('alert'=>'Alert','Escalation'=>'Escalation'),'empty'=>'Select Notification','required'=>true));?></td>
						<td></td><td></td>
			   		</tr>
                    
                	<tr>
						<td>Select Category<font style="color:red;">*</font></td>
						<td><?php echo $this->Form->input('Parent',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'Parent1','required'=>true));?></td>
						<td></td><td></td>
			   		</tr>
                    <tr>
                    	<td>Find Sub - Category</td>
                        <td>
                        	<p class="signin button">
								<button  type="button" id="button" onClick="escalation_getChild()">Find Sub-Category</button>
							</p>
						</td>
                   		<td></td><td></td>
                    </tr>
                	<tr>
						<td>Select Alert/Escalation Type<font style="color:red;">*</font></td>
						<td><?php echo $this->Form->input('timer',array('label'=>false,'options'=>array('Year','Month','Week','Days','Hour'),'empty'=>'Select Time','required'=>true));?></td>
						<td></td><td>
</td>
			   		</tr>

                	<tr>
						<td>Select Alert/Escalation Time<font style="color:red;">*</font></td>
						<td><?php echo $this->Form->input('dater0',array('label'=>false,'id'=>'datetimepicker','class'=>'cl1','style'=>'display:none;','readOnly'=>true,'required'=>true));?>
                        	<?php $month_days=array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','Month End'=>'Month End'); echo $this->Form->input('dater1',array('label'=>false,'options'=>$month_days,'id'=>'month','style'=>'display:none;','readOnly'=>true,'required'=>true)); unset($month_days);?>
                            
                            <?php $week_days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');echo $this->Form->input('dater2',array('label'=>false,'id'=>'week','options'=>$week_days,'style'=>'display:none;','readOnly'=>true,'required'=>true)); unset( $week_days);?>
                            
                            <?php echo $this->Form->input('dater3',array('label'=>false,'id'=>'datetimepicker1','style'=>'display:none;','readOnly'=>true,'required'=>true));?>
                            <?php echo $this->Form->input('dater4',array('label'=>false,'id'=>'datetimepicker5','style'=>'display:none;','readOnly'=>true,'required'=>true));?>
                        </td>
						<td></td><td>
</td>
			   		</tr>
                    
					<tr>
                		<td>Please Select Notification</td>
                		<td>
                    	<?php echo $this->Form->input('type',array('label'=>false,'options'=>array('email'=>'Email','sms'=>'SMS','both'=>'Both'),'id'=>'','required'=>true));?>
                    	</td><td></td><td></td>
                	</tr>
					<tr>
                		<td>Fill Email Id</td>
                	<td><?php echo $this->Form->input('email',array('label'=>false,'required'=>true,'type'=>'email'));?></td>
                    <td></td><td></td>
                	</tr>
					<tr>
                		<td>
                    		Fill Contact No
                    	</td>
                	<td>
                    	<?php echo $this->Form->input('sms',array('label'=>false,'required'=>true,'onKeyPress'=>'return isNumberKey(event)'));?>
                    </td><td></td><td></td>
                	</tr>
				<tr>
                	<td>Fill <div id="msg">Email or SMS Format </div></td>
                	<td><?php echo $this->Form->textArea('format',array('label'=>false,'ng-model'=>'name','required'=>true,'style'=>'width:295px'));?></td>
					
					<td rowspan="2"><button name="add_fields" type="button" ng-click="name = name +'[tag1]' +select +'[/tag1]'">ADD</button>
                    <button name="clear_fields" type="button" ng-click="name = ''">Clear</button></td>
					<td rowspan="3"><?php echo $this->Form->textArea('field',array('label'=>false,'style'=>'height: 250pt','value'=>"{{name}}",'required'=>true,'readOnly'=>true));?></td>
                </tr>
				<tr>
                	<td>Please Add Virtual Fields</td>
                	<td>
                    	<?php echo $this->Form->input('field_set1',array('label'=>false,'options'=>$field_send1,'multiple'=>'multiple',"ng-model"=>"select",'style'=>'height: 100pt'));?>
                    </td>
					<td>
					</td>
                </tr>

				<tr>
                	<td>Please Add Virtual Fields</td>
                	<td>
                    	<?php echo $this->Form->input('field_set2',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 100pt'));?>
                    </td>
					<td><button name="add_fields2" type="button" ng-click="name = name +'[tag]' +select2 +'[/tag]'">ADD</button>
                    	<button name="clear_fields" type="button" ng-click="name = ''">Clear</button>
					</td>
                </tr>


			   <tr>
                    <td><br/>
						<p class="signin button">
							<input type="submit" style="width:75px;"  value="ADD" >
						</p>
					</td>
						
                    <td align="right"><br/>
<!--						<p class="signin button">
							<button  type="button" id="button" onClick="escalation_getChild()">Find Child</button>
						</p>
-->					</td>
                 </tr>
                      </table>
                      </div>
                </div>              
            </fieldset> 

<fieldset id="fieldset2" >
            <fieldset id="fieldset3" >            
                <h1>Escalation</h1>
                
                <div class="form-bottom">
                    <ul id="someID">
  								
<?php
 foreach($data as $post1): 
	if($post1['ClientCategory']['Label']==1){  ?><li>
     <?php echo "<a href=\"Escalations?id=".base64_encode($post1['ClientCategory']['id'])."\"><font color=\"#336666\">".$post1['ClientCategory']['ecrName'].' First'."</font></a>";?>
		<ul>
			<?php
				foreach($data as $post2):
					if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id'])
						{?><li><?php
							echo "<a href=\"Escalations?id=".base64_encode($post2['ClientCategory']['id'])."\"><font color=\"#336666\">".$post2['ClientCategory']['ecrName'].' Second</font></a>';?>
                              <ul>
								<?php
									foreach($data as $post3):
										if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id'])
											{?><li> <?php
												echo "<a href=\"Escalations?id=".base64_encode($post3['ClientCategory']['id'])."\"><font color=\"#336666\">".$post3['ClientCategory']['ecrName'].' Third</font></a>';?>
                                                   <ul>
													  <?php
														foreach($data as $post4):
															if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id'])
																{?><li> <?php
																	echo "<a href=\"Escalations?id=".base64_encode($post4['ClientCategory']['id'])."\"><font color=\"#336666\">".$post4['ClientCategory']['ecrName'].' Fourth</font></a>';?>
                                                                      <ul>
																		<?php
																			foreach($data as $post5): 
																				if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id'])
																					{?><li><?php
																					 	echo "<a href=\"Escalations?id=".base64_encode($post5['ClientCategory']['id'])."\"><font color=\"#336666\">".$post5['ClientCategory']['ecrName'].' Fifth</font></a>';
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
   </fieldset> 
<fieldset id="fieldset4" >
   <h1>Mobile & Sms</h1>      
   <div class="form-bottom">
       		<table border = "1">
            	<tr>
                    <td>email</td>
                    <td>SMS</td>
                </tr>
                <?php
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
								else if($esc['Escalation']['type']=='Both')
								{
								  echo "<td>".$esc['Escalation']['email']."</td>";
								  echo "<td>".$esc['Escalation']['smsNo']."</td>";
								}
								echo "</tr>";
							endforeach;
						}
				?>
            </table>

   </div>
   
</fieldset>
</fieldset>
                 	                   			
			                    

            
                           
                 <p class="signin button">
                    <input type="button" style="width:75px;"   value="Submit" >
                </p>
            
			<div id="cover" ></div>
		
</div>
</div>
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
