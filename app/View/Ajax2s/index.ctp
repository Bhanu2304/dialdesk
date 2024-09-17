<?php echo $this->Html->script('WorkFlow/src/wickedpicker'); ?>
<link rel="stylesheet" href="<?php echo $this->webroot; ?>js/WorkFlow/stylesheets/wickedpicker.css">
<script type="text/javascript">
            $('.timepicker').wickedpicker({now: '00:00', twentyFour: true, title:
                    'My Timepicker', showSeconds: false});
        //    $('.timepicker-24').wickedpicker({twentyFour: true});
</script>
<script type="text/javascript">
            $('.timepicker1').wickedpicker({now: '23:59', twentyFour: true, title:
                    'My Timepicker', showSeconds: false});
        //    $('.timepicker-24').wickedpicker({twentyFour: true});
</script>

<?php
    $workflowid=isset($data1)?$data1['id']:"";
    $start_time=isset($data1)?$data1['start_time']:"";
    $end_time=isset($data1)?$data1['end_time']:"";
    $transferType=isset($data1)?$data1['transferType']:"";
    $Numbers=isset($data1)?$data1['Numbers']:"";
    
    $internal = $transferType =="Internal"? "checked" : "";
    $dialdesk = $transferType =="DialDesk"? "checked" : ""; 
    
    $editurl=$this->webroot.'Ajax2s/index';
    
    //$start_time=isset($data1)?$data1['end_time']:"";
?>
<script>
$(document).ready(function(){
    <?php if($transferType =="Internal" ){?>
        document.getElementById('field1').innerHTML = '<input type="text" name="Numbers" placeholder="Phone No" value="<?php echo $Numbers;?>" id="Numbers" class="first_name">';
    <?php }?>
});
    
    function getNumberField(value)
    {
        if(value=='Internal')
        {
            document.getElementById('field1').innerHTML = '<input type="text" name="Numbers" placeholder="Phone No" value="<?php echo $Numbers;?>"  id="Numbers" class="first_name">';
            document.getElementById('Numbers').focus();
        }
        else
        {
            document.getElementById('field1').innerHTML = '';
        }
    }
</script>
<?php
if(isset($editform))
{
	foreach($editform as $post):
        $id = $post['Ivr']['id'];
        $first_name = $post['Ivr']['Msg'];
        $parent_id = $post['Ivr']['parent_id'];
        $hide = $post['Ivr']['hide'];
        $checked = $hide == 1 ? "checked" : ""; 		
        endforeach;
        
       
    
	
    echo  <<<EOL
       
    <form class="edit_data" method="post" action="$editurl">
        <img class="close" src="{$image}app/webroot/js/WorkFlow/images/close.png" />
        <input type="hidden" name="action" value="edit" >
        <input type="hidden" name="id" value="$workflowid" >
        <input type="text" name="first_name" value="$start_time" placeholder="Start Time" class="timepicker"> 
        <input type="text" name="end_time" value="$end_time" class="timepicker1" placeholder="End Time">
        <input type="radio" $dialdesk value="DialDesk" id="transferType" name="transferType" onClick="getNumberField(this.value)"><span style="font-size:12px;">DialDesk</span>      
        <input type="radio" $internal value="Internal" id="transferType" name="transferType"  onClick="getNumberField(this.value)"><span style="font-size:12px;">Internal</span>     
        <div id="field1"></div>
        <input style="margin-top:10px;" type="submit" class="submit" name="submit" value="Submit">
    </form>
EOL;
}
if(isset($addform))
{
    $select = '';
    for($i=1; $i<=24; $i++)
    {
        $select .= "<option value='$i'>$i</option>";
    }
    
echo <<<EOL
    <form class="add_data" method="post" action="">
        <img class="close" src="{$image}app/webroot/js/WorkFlow/images/close.png" /><br/>
        <input type="text" name="first_name" placeholder="Start Time" class="timepicker"> 
        <input type="text" name="end_time" class="timepicker1" placeholder="End Time">
        <input type="radio" value="DialDesk" id="transferType" name="transferType" onClick="getNumberField(this.value)"><span style="font-size:12px;">DialDesk</span>
        <input type="radio" value="Internal" id="transferType" name="transferType"  onClick="getNumberField(this.value)"><span style="font-size:12px;">Internal</span>
        <div id="field1"></div>
        <input style="margin-top:10px;" type="submit" class="submit" name="submit" value="Submit">
    </form>
EOL;
	
}

if(isset($add))
{
	echo $add;
}
?>