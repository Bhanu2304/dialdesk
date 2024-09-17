<?php
$account['BalanceMaster']['start_date'] = date_format(date_create($account['BalanceMaster']['start_date']),'m/d/Y');
$rentalPeriod = array(''=>'Select','Day'=>'Day','Month'=>'Month','Year'=>'Year');
//$rental = array($waiver['Waiver']['RentalPeriod']=>$weber['Waiver']['RentalPeriod']);
$Fields = array(
                'start_date'=>array('label'=>'Start Date','type'=>'time','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'Balance'=>array('label'=>'Balance','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'FreeInboundMinute'=>array('label'=>'Inbound Balance','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'FreeOutboundMinute'=>array('label'=>'Outbound Call Minute','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'FreeMissCallMinute'=>array('label'=>'Miss Call Minute','type'=>'nmber','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'FreeVFOMinute'=>array('label'=>'VFO Minute','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'FreeSMS'=>array('label'=>'SMS','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                'FreeEmail'=> array('label'=>'Email','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
                );

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

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>ClientDetails">Home</a></li>
    <li><a >Client Account</a></li>
    <li class="active"><a href="#">Client Account</a></li>
</ol>
<?php echo $this->Form->create('ClientAccounts',array("class"=>"form-horizontal")); ?>

<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Client Account</h2>
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
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'options'=>$option,'value'=>$account['BalanceMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            
                            else if($type=='time')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'value'=>$account['BalanceMaster'][$fieldName],'class'=>'form-control date-picker'),$validate));
                            }
                            
                            else if($type=='number')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'onkeypress'=>'return checkCharacter(event,this)','value'=>$account['BalanceMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            else if($type=='area')
                            {
                                echo $this->Form->textArea($fieldName, array_merge(array('label'=>false,'value'=>$account['BalanceMaster'][$fieldName],'class'=>'form-control'),$validate));
                            }
                            else if($type=='file')
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'type'=>'file','placeholder'=>$label,'class'=>'form-control'),$validate));
                            }
                            
                            else
                            {
                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'value'=>$account['BalanceMaster'][$fieldName],'class'=>'form-control'),$validate));
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
            <input type="hidden" name="id" value="<?php echo $account['BalanceMaster']['Id']; ?>" />
        </div>
        <div class="panel-footer"></div>
    </div>
    </div>
</div>
 <?php 
echo $this->Form->end(); 
 ?>
