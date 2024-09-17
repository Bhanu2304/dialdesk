<?php

$rentalPeriod = array('Day'=>'Day','Month'=>'Month','Year'=>'Year');

//$Fields2 = array('Free.FreeValue'=>array('label'=>'Free Value','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.PeriodType'=>array('label'=>'Period Type','type'=>'select','value'=>$rentalPeriod,'required'=>'0','readonly'=>'0','function'=>array('onChange'=>"getFreePeriod(this.value)")),
//                'Free.RentalPeriod'=>array('label'=>'Free Rental Period','type'=>'select','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.InboundCall'=>array('label'=>'Inbound Call Minute','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.OutboundCall'=>array('label'=>'Outbound Call Minute','type'=>'nmber','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.MissCall'=>array('label'=>'Miss Call minute','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.VFOCall'=>array('label'=>'Vfo Call Minute','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.SMS'=> array('label'=>'SMS Minute','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>''),
//                'Free.Email'=>array('label'=>'Free Email','type'=>'number','value'=>'','required'=>'0','readonly'=>'0','function'=>'') 
//                );                
                
                
                
?>
<script>    
function createClientAccount(){
	$('#clientaccount_form').attr('action', '<?php echo $this->webroot;?>ClientAccounts');
	$("#clientaccount_form").submit();	
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
    function getFreePeriod(value)
    {
        var str='<label class="col-sm-2 control-label">Free Rental Period</label><div class="col-sm-2"><div class="input select">';
        str += '<select name="data[Free][RentalPeriod]" class="form-control" id="FreeRentalPeriod">';
        if(value=='')
        {    str += '<option value="">Free Rental Period</option>'; }
        
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
            document.getElementById("rentalFreePeriod").innerHTML=str;
    }
    
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Account Master</a></li>
    <li class="active"><a href="#">Account Master</a></li>
</ol>
<?php echo $this->Form->create('ClientAccounts',array('action'=>'client_wise_weber_creation','id'=>'clientaccount_form',"class"=>"form-horizontal")); ?>
<div class="page-heading margin-top-head">            
    <h2>Account Master</h2>
    <div >
        
       <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'createClientAccount();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','class'=>'form-control','required'=>true)); ?>
       
    </div>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <?php if(!empty($account)){?>
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Client Account</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <div class="panel-body">
            <?php 
//                    $count = count($Fields2);
//                    $keys = array_keys($Fields2); 
//                    $i=0; $flag = false;
//                    echo '<div class="form-group">';
//                        for(; $i<$count; $i++)
//                        {
//                            if($i%3==0 && $flag)
//                            {
//                                echo '</div><div class="form-group">';
//                            }
//                            $flag = true;
//                            $label = $Fields2[$keys[$i]]['label'];
//                            $type = $Fields2[$keys[$i]]['type'];
//                            $required = ($Fields2[$keys[$i]]['required']==1?array('required'=>true):'');
//                            $readonly = ($Fields2[$keys[$i]]['readonly']==1?array('readonly'=>true):'');
//                            $function = $Fields2[$keys[$i]]['function'];
//                            $validate = array();
//                            if(!empty($required))
//                            {
//                                $validate = $required;
//                            }
//                            if(!empty($readonly))
//                            {
//                                $validate = array_merge($validate,$readonly);
//                            }
//                            if(!empty($function))
//                            {
//                                $validate = array_merge($validate,$function);
//                            }
//                            $option = $Fields2[$keys[$i]]['value'];
//                            $fieldName = $keys[$i];
//                            
//                            //if($i==10)
//                            //{print_r($validate); exit;}
//                            if($fieldName=='Free.RentalPeriod') echo '<div id="rentalFreePeriod">';
//                            //else if($fieldName=='ChequeNo') echo '<div id="ChequeNoReplace">';
//                            echo '<label class="col-sm-2 control-label">'.$label.'</label>';
//                            echo '<div class="col-sm-2">';
//                            
//                            if($type=='select')
//                            {
//                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'options'=>$option,'empty'=>$label,'class'=>'form-control'),$validate));
//                            }
//                            
//                            else if($type=='time')
//                            {
//                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,"onClick"=>"displayDatePicker(\''.$fieldName.'\');",'placeholder'=>$label,'class'=>'form-control'),$validate));
//                            }
//                            
//                            else if($type=='number')
//                            {
//                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>$label,'class'=>'form-control'),$validate));
//                            }
//                            else if($type=='area')
//                            {
//                                echo $this->Form->textArea($fieldName, array_merge(array('label'=>false,'placeholder'=>$label,'class'=>'form-control'),$validate));
//                            }
//                            else if($type=='file')
//                            {
//                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'type'=>'file','placeholder'=>$label,'class'=>'form-control'),$validate));
//                            }
//                            
//                            else
//                            {
//                                echo $this->Form->input($fieldName, array_merge(array('label'=>false,'placeholder'=>$label,'class'=>'form-control'),$validate));
//                            }
//                        echo '</div>';
//                        if($fieldName=='Free.RentalPeriod') echo '</div>';
//                        //else if($fieldName=='ChequeNo') echo '</div>';
//                        }
//                        if($i%3==0)
//                        {
//                            echo '</div><div class="form-group">'; 
//                           echo '<label class="col-sm-2 control-label">&nbsp;</label>';                            
//                           echo '<div class="col-sm-2">';
//                            echo "<input type='submit' value='submit' name='submit' class='btn-web btn'>";
//                            echo '</div>';
//                        }
//                        else
//                        {
//                           
//                            echo '<label class="col-sm-2 control-label">&nbsp;</label>';
//                            echo '<div class="col-sm-2">';
//                            echo "<input type='submit' name='submit' value='submit' name='submit' class='btn-web btn'>";
//                            echo '</div>';
//                        }
//                    echo '</div>';
                 ?>
        </div>
        </div>
        <div class="panel-footer"></div>
    </div>
    <?php }else{echo "Please Create Plan First";} ?>
        
    </div>
    
</div>
 <?php echo $this->Form->end(); ?>
