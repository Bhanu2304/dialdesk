<?php

$rentalPeriod = array('Day'=>'Day','Month'=>'Month','Year'=>'Year');
$rental = array($plan['PlanMaster']['RentalPeriod']=>$plan['PlanMaster']['RentalPeriod']);
$Fields = array('PlanName'=>array('label'=>'Plan Name','type'=>'text','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'SetupCost'=>array('label'=>'Setup Cost','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'RentalAmount'=>array('label'=>'Rental Amount','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'Balance'=>array('label'=>'Free Value','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'PeriodType'=>array('label'=>'Period Type','type'=>'select','value'=>$rentalPeriod,'required'=>'1','readonly'=>'0','function'=>array('onChange'=>"getPeriod(this.value)")),
                'RentalPeriod'=>array('label'=>'Rental Period','type'=>'select','value'=>$rental,'required'=>'1','readonly'=>'0','function'=>''),
                'InboundCallCharge'=>array('label'=>'Inbound Call Charge','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'OutboundCallCharge'=>array('label'=>'Outbound Call Charge','type'=>'nmber','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'MissCallCharge'=>array('label'=>'MissCallCharge','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'VFOCallCharge'=>array('label'=>'VfoCharge','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'SMSCharge'=> array('label'=>'SMS Charge','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>''),
                'EmailCharge'=>array('label'=>'Email Charge','type'=>'number','value'=>'','required'=>'1','readonly'=>'0','function'=>'') 
                );

                $count = count($Fields);
                $keys = array_keys($Fields); 
                
?>
<script>    
function createClientPlan(){
	$('#clientplan_form').attr('action', '<?php echo $this->webroot;?>AdminPlans');
	$("#clientplan_form").submit();	
}
function checkCharacter(e,t) {
	try{
    	if (window.event) {
        	var charCode = window.event.keyCode;
     	}
        else if(e){
     		var charCode = e.which;
        }
        else{return true;}
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {         
        	return false;
        }
        return true;     
	}
    catch (err) {
    	alert(err.Description);
    }
}
</script>
<script>
    function getPeriod(value)
    {
        var str='<label class="col-sm-2 control-label">Rental Period</label><div class="col-sm-2"><div class="input select">';
        str += '<select id="AdminPlansRentalPeriod" required="" class="form-control" name="data[AdminPlans][RentalPeriod]">';
        if(value=='')
        {    str += '<option value="">Rental Period</option>'; }
        
        else if(value=='Day')
        {
            for(var i=1; i<=29; i++){    str +='<option value="'+i+'">'+i+'</option>'; }
        }
        else if(value=='Month')
        {
            for(var i=1; i<=11; i++){    str +='<option value="'+i+'">'+i+'</option>'; }
        }
        else if(value=='Year')
        {
            for(var i=1; i<=2; i++)    {    str +='<option value="'+i+'">'+i+'</option>';    }
        }
        str +='</select></div></div>';
            document.getElementById("rentalPeriod").innerHTML=str;
    }
    
    
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Plan Master</a></li>
    <li class="active"><a href="#">Plan Master</a></li>
</ol>
<?php echo $this->Form->create('AdminPlans',array("class"=>"form-horizontal")); ?>

<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Plan Master</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <?php 
                    $i=0; $flag = false;
                    echo '<div class="form-group">';
                        for(; $i<$count; $i++)
                        {
                            if($i%3==0 && $flag)
                            {
                                echo '</div><div class="form-group">';
                            }
                            $flag = true;
                            $label = $Fields[$keys[$i]]['label'];
                            $type = $Fields[$keys[$i]]['type'];
                            $required = ($Fields[$keys[$i]]['required']==1?array('required'=>true):'');
                            $readonly = ($Fields[$keys[$i]]['readonly']==1?array('readonly'=>true):'');
                            $function = $Fields[$keys[$i]]['function'];
                            $validate = array();
                            if(!empty($required))
                            {
                                $validate = $required;
                            }
                            if(!empty($readonly))
                            {
                                $validate = array_merge($validate,$readonly);
                            }
                            if(!empty($function))
                            {
                                $validate = array_merge($validate,$function);
                            }
                            $option = $Fields[$keys[$i]]['value'];
                            $fieldName = $keys[$i];
                            
                            //if($i==10)
                            //{print_r($validate); exit;}
                            if($fieldName=='RentalPeriod') echo '<div id="rentalPeriod">';
                            //else if($fieldName=='ChequeNo') echo '<div id="ChequeNoReplace">';
                            echo '<label class="col-sm-2 control-label">'.$label.'</label>';
                            echo '<div class="col-sm-2">';
                            
                            if($type=='select')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'options'=>$option,'value'=>$plan['PlanMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            
                            else if($type=='time')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,"onClick"=>"displayDatePicker(\''.$fieldName.'\');",'value'=>$plan['PlanMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            
                            else if($type=='number')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'onkeypress'=>'return checkCharacter(event,this)','value'=>$plan['PlanMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            else if($type=='area')
                            {
                                echo $this->Form->textArea($fieldName, array_merge(array('label'=>false,'value'=>$plan['PlanMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            else if($type=='file')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'type'=>'file','placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                            
                            else
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'value'=>$plan['PlanMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                        echo '</div>';
                        if($fieldName=='RentalPeriod') echo '</div>';
                        //else if($fieldName=='ChequeNo') echo '</div>';
                        }
                        if($i%3==0)
                        {
                            //echo '</div>';
                           echo '</div><div class="form-group">';
                           echo '<label class="col-sm-2 control-label">&nbsp;</label>';                            
                           echo '<div class="col-sm-2">';
                           echo "<input type='submit' value='Update' name='Update' class='btn-web btn'>";
                           echo '</div>';
                        }
                        else
                        {
                            echo '<label class="col-sm-2 control-label">&nbsp;</label>';
                            echo '<div class="col-sm-2">';
                            echo "<input type='submit' name='Update' value='Update' name='Update' class='btn-web btn'>";
                            echo '</div>';
                        }  
                    echo '</div>';
                 ?>
            <input type="hidden" name="id" value="<?php echo $plan['PlanMaster']['Id']; ?>" />
        </div>
        <div class="panel-footer"></div>
    </div>
    </div>
</div>
 <?php 
echo $this->Form->end(); 
 ?>
