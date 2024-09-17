<?php
$weber = array('FreeValue'=>'Free Value','PeriodType'=>'Rental Period','InboundCall'=>'Inbound Call Minute','OutboundCall'=>'Outbound Call Minute',
'MissCall'=>'Miss Call Minute','VFOCall'=>'VFO Call','SMS'=>'Free SMS','Email'=>'Free Email');
$Fields = array('WeberType'=>array('label'=>'Select Weber','type'=>'select','value'=>$weber,'required'=>'0','readonly'=>'0','function'=>array('onChange'=>"getPeriodType(this.value)")),
    'freeweber'=>array('label'=>'No. of Free Weber','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>array()));

                $count = count($Fields);
                $keys = array_keys($Fields); 
                
?>
<script>    
function createClientWeber(){
	$('#clientweber_form').attr('action', '<?php echo $this->webroot;?>ClientWebers');
	$("#clientweber_form").submit();	
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
    
    function getPeriodType(value)
    {
        var str='';
        if(value=='PeriodType')
        {
            str = '<label class="col-sm-2 control-label">Period Type</label>';
            str += '<div class="col-sm-2"><div class="input select"><select name="data[ClientWebers][PeriodType]" class="form-control" onchange="getPeriod(this.value)" id="ClientWebersPeriodType">';
            str +='<option value="">Select</option>';
            str +='<option value="Day">Day</option>';
            str +='</select></div></div>';
            str +='</select></div></div>';
            str +='<div id="rentalPeriod2"></div>';
        }
        else
        {
            str = '<label class="col-sm-2 control-label">No. of Free Weber</label>';
            str += '<div class="col-sm-2"><div class="input text">';
            str += '<input name="data[ClientWebers][freeweber]" onkeypress="return checkCharacter(event,this)" placeholder="No. of Free Weber" class="form-control" id="ClientWebersFreeweber" type="text"></div></div>';
        }
        document.getElementById("rentalPeriod").innerHTML=str;
    }
    
    function getPeriod(value)
    {
        var str='<label class="col-sm-2 control-label">Rental Period</label><div class="col-sm-2"><div class="input select">';
        str += '<select id="ClientWebersRentalPeriod" required="" class="form-control" name="data[ClientWebers][RentalPeriod]">';
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
            document.getElementById("rentalPeriod2").innerHTML=str;
    }
    
    
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>ClientDetails">Home</a></li>
    <li><a >Weber Master</a></li>
    <li class="active"><a href="#">Weber Master</a></li>
</ol>
<?php echo $this->Form->create('ClientWebers',array("class"=>"form-horizontal")); ?>

<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Weber Master</h2>
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
                            if($fieldName=='freeweber') echo '<div id="rentalPeriod">';
                            //else if($fieldName=='ChequeNo') echo '<div id="ChequeNoReplace">';
                            echo '<label class="col-sm-2 control-label">'.$label.'</label>';
                            echo '<div class="col-sm-2">';
                            
                            if($type=='select')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'options'=>$option,'empty'=>$label,'class'=>'form-control'),$validate));
                            }
                            
                            else if($type=='time')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,"onClick"=>"displayDatePicker(\''.$fieldName.'\');",'placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                            
                            else if($type=='number')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                            else if($type=='area')
                            {
                                echo $this->Form->textArea($fieldName, array_merge(array('label'=>false,'placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                            else if($type=='file')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'type'=>'file','placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                            
                            else
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                        echo '</div>';
                        if($fieldName=='freeweber') echo '</div>';
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
        </div>
        <div class="panel-footer"></div>
    </div>
    </div>
</div>
 <?php 
echo $this->Form->end(); 
 ?>
