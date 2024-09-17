<h1> Welcome <?php echo $this->Session->read("username"); ?></h1>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Call Lead</a></li>
    <li><a href="#tabs-2">Tagging</a></li>
    <li><a href="#tabs-3">History1</a></li>
    <li><a href="#tabs-4">History2</a></li>
    <li><a href="#tabs-5">Live Panel</a></li>
    <li><a href="#tabs-6">Search</a></li>
  </ul>
<div id="tabs-1">
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Call Lead</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                
<?php
	
	$keys = array_keys($data);
	$count = count($keys);
	$flag = false;
        
	echo '<div class="form-group">';
	for($i=0; $i<$count; $i++)
	{
		if($i%3==0 && $flag) echo '</div><div class="form-group">';
		echo '<label class="col-sm-2 control-label">'.$keys[$i].'</label>';
		echo '<label class="col-sm-2 control-label">'.$data[$keys[$i]].'</label>';
		$flag = true;
	}
	echo "</div>";
	echo $this->Form->input('MSISDN',array('label'=>false,'type'=>'hidden','value'=>$data['phone_number']));
?>
                </div>
            </div>
        </div>
    </div>
</div>
  

  <div id="tabs-2">
<?php echo $this->Form->create("Home",array("url"=>"save_tagging",'class'=>'form-horizontal row-border'));
      echo $this->Form->input('LeadId',array('label'=>false,'type'=>'hidden','value'=>$data[$keys[0]]));
?>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Tagging</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
<table id="cat1">
<?php
	$keys = array_keys($ecr);
	for($i =0; $i <1; $i++)
	{
            $key = $keys[$i];
            $value = explode(",",$ecr[$key]);
            $options = array();
            for($j =0; $j<count($value); $j++)
            {
                $options[$value[$j]] = $value[$j];
            }
            
            if($i%3==0) echo "<tr>";
            echo "<th> Category".$key."</th>";
            echo "<td>".$this->Form->input('Category'.$key, array('label'=>false,'options'=>$options,'onChange'=>'inbound_getChild(this.id)'))."</td>";
            if($i%3==2) echo "</tr>";
	}
	unset($options); unset($key);unset($value);unset($i);unset($j);
	echo "</tr>";
?>
</table>
                </div>
            </div>
<div class="panel panel-default" data-widget='{"draggable": "false"}'>
    <div class="panel-heading">
        <h2>Tagging</h2>
            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
    </div>
    <div data-widget-controls="" class="panel-editbox"></div>
    <div class="panel-body">
        <div class="form-group">

<?php $j = 1;
foreach($fieldName as $post):

	if($j%4==0) echo '</div><div class="form-group">';
	echo '<label class="col-sm-2 control-label">';
	if($post['FieldMaster']['FieldType']=='DropDown'){echo "Select ";}
	echo $post['FieldMaster']['FieldName'].'</label><div class="col-sm-2">';

	$req = false;
	$type = 'text';
	$fun = "";
	
	if($post['FieldMaster']['RequiredCheck']==1)
	{
		$req = true;
	}
	if($post['FieldMaster']['FieldValidation']=='Numeric')
	{
		$type = 'Number';
		$fun = "return isNumberKey(event)";
	}
	
	if($post['FieldMaster']['FieldType']=='TextBox')
	{
		echo $this->Form->input('Field'.$j++,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
	}
	if($post['FieldMaster']['FieldType']=='TextArea')
	{
		echo $this->Form->textArea('Field'.$j++,array('label'=>false,'required'=>$req,'class'=>'form-control'));
	}
	if($post['FieldMaster']['FieldType']=='DropDown')
	{
		$option = array();
		$options = explode(',',$fieldValue[$post['FieldMaster']['id']]);
		$count = count($options);
		
		for( $i=0; $i<$count; $i++)
		{
			$option[$options[$i]] = $options[$i];
		}
		
		echo $this->Form->input('Field'.$j++,array('label'=>false,'options'=>$option,'required'=>$req,'class'=>'form-control'));
	}
	
	echo "</div>";
endforeach;

?>
            <div class="panel-footer">
                <div class="clearfix">
                    <input type="submit" name="submit" value="submit" class="btn btn-primary btn-raised pull-right">
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>      
  <?php echo $this->Form->end(); ?>

   
  </div>
<div id="tabs-3">
<div id="tabs2">
	<ul>
    	<?php $j = 1;
		foreach($history as $his):
			if(!empty($his['CallMaster']['CallDate'])){$date = date_create($his['CallMaster']['CallDate']);$his['CallMaster']['CallDate'] = date_format($date,'d M Y'); }
			else
			{$date = $his['CallMaster']['CallDate'];}
    		echo "<li><a href=\"#tabs2-".$j++."\">".$his['CallMaster']['CallDate']."</a></li>";
		endforeach;
		?>
	</ul>    

<?php $j = 1;
		foreach($history as $his):
    		echo "<div id=\"tabs2-".$j++."\">";
?>
			<table border="1">
				<tr>
					<th>MSISDN</th>
<?php
			foreach($keys as $k)
			{ echo "<td>"."Category".$k."</td>";}
		
			
			foreach($fieldName as $post): 
				echo "<td>".$post['FieldMaster']['FieldName']."</td>";
			endforeach;
?>
				</tr>
                <tr>
<?php       
			echo "<th>".$his['CallMaster']['MSISDN']."</th>";         
			foreach($keys as $k)
			{ echo "<td>".$his['CallMaster']["Category".$k]."</td>";}
		
			$k = 1;
			
			foreach($fieldName as $post):
				echo "<td>".$his['CallMaster']['Field'.$k++]."</td>";
			endforeach;
                
?>                
                </tr>
            </table>
<?php			
		echo "</div>";
		endforeach;	
?>        
  </div>
 </div>
  <div id="tabs-4">
  <table>
  		<tr><?php
			echo "<th>MSISDN</th>";
            foreach($keys as $k)
			{ echo "<th>"."Category".$k."</th>";}
			
			foreach($fieldName as $post): 
				echo "<th>".$post['FieldMaster']['FieldName']."</th>";
			endforeach;	
			echo "<th>Calling Date</th>";		
			?>
        </tr>
 	<?php
		foreach($history as $his):
			echo "<tr>";
			echo "<th>".$his['CallMaster']['MSISDN']."</th>";
			foreach($keys as $k)
			{ echo "<td>".$his['CallMaster']["Category".$k]."</td>";}
			
			foreach($fieldName as $post):
				echo "<td>".$his['CallMaster']['Field'.$k++]."</td>";
			endforeach;
			
			echo "<td>".$his['CallMaster']['CallDate']."</td>";
			echo "</tr>";
		endforeach;
	?>
  </table>
  </div>
    <div id="tabs-5">
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>
    <div id="tabs-6">
 <div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Tagging</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">       
            <?php echo $this->Form->create(array('class'=>'form-horizontal row-border')); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">MSISDN</label>
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('MSISDN',array('label'=>false, 'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control')); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">CallDate</label>
                    <div class="col-sm-6">
                        <?php echo $this->Form->input('CallDate',array('label'=>false,'onClick'=>"displayDatePicker('data[FieldMaster][CallDate]');",'class'=>'form-control')); ?>
                    </div>
                </div>
<?php
	$keys = array_keys($ecr);
	for($i =0; $i<count($keys); $i++)
	{
		$key = $keys[$i];
		$value = explode(",",$ecr[$key]);
		$options = array();
			for($j =0; $j<count($value); $j++)
			{
				$options[$value[$j]] = $value[$j];
			}			
		echo '<div class="form-group"><label class="col-sm-2 control-label">'."Category".$key."</label>";
		echo '<div class="col-sm-6">'.$this->Form->input('Category'.$key, array('label'=>false,'options'=>$options,'class'=>'form-control'))."</div></div>";
	}
	
	unset($options); unset($key);unset($value);unset($ecr);unset($i);unset($j);
?>
<?php $j = 1;
foreach($fieldName as $post):

	echo '<div class="form-group"><label class="col-sm-2 control-label">';
	if($post['FieldMaster']['FieldType']=='DropDown'){echo "Select ";}
	echo $post['FieldMaster']['FieldName'].'</label><div class="col-sm-6">';

	$req = false;
	$type = 'text';
	$fun = "";
	
	if($post['FieldMaster']['FieldValidation']=='Numeric')
	{		
		$fun = "return isNumberKey(event)";
	}
	
	if($post['FieldMaster']['FieldType']=='DropDown')
	{
		$option = array();
		$options = explode(',',$fieldValue[$post['FieldMaster']['id']]);
		$count = count($options);
		
		for( $i=0; $i<$count; $i++)
		{
			$option[$options[$i]] = $options[$i];
		}
		
		echo $this->Form->input('Field'.$j++,array('label'=>false,'options'=>$option,'class'=>'form-control'));
	}
	else
	{
		echo $this->Form->input('Field'.$j++,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'class'=>'form-control'));
	}
	echo "</div></div>";

endforeach;
?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <input type="submit" value="search" name="submit" class="btn btn-primary btn-raised pull-right">
                    </div>
                </div>
    <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
 </div> 
<table>
<?php

if(isset($search) && is_array($search) && !empty($search))
{
echo "<tr>";
	echo "<th>MSISDN</th>";
		echo "<th>Calling Date</th>";
	foreach($keys as $k)
	{ echo "<th>"."Category".$k."</th>";}
			
	foreach($fieldName as $post): 
		echo "<th>".$post['FieldMaster']['FieldName']."</th>";
	endforeach;	
echo "</tr>";

	foreach($search as $serc):
	echo "<tr>";	
		foreach($serc['CallMaster'] as $ser=>$v) 
		{	
			echo "<td>".$v."</td>";
		}
	endforeach;
	echo "</tr>";
}
?>
</table>    
    
    </div>
  
</div>
