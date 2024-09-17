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
    
    <table>
<?php
		
		$keys = array_keys($data);
		$count = count($keys);
		$flag = false;
		echo "<tr>";
		for($i=0; $i<$count; $i++)
		{
			if($i%3==0 && $flag) echo "</tr><tr>";
			echo "<th>".$keys[$i]."</th>";
			echo "<td>".$data[$keys[$i]]."</td>";
			$flag = true;
		}
		echo "</tr>";
		
?>
    </table>
    
  </div>

  <div id="tabs-2">
    
 

<?php echo $this->Form->create("Home",array("url"=>"save_tagging_outbound")); ?>
<table>
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
			if($i%3==0) echo "<tr>";
		echo "<th> Category".$key."</th>";						
		echo "<td>".$this->Form->input('Category'.$key, array('label'=>false,'options'=>$options))."</td>";
				if($i%3==2) echo "</tr>";
	}
	unset($options); unset($key);unset($value);unset($i);unset($j);
	echo "</tr>";
?>
<?php $j = 1;
//print_r($ObFieldValue);
foreach($fieldName as $post):

	if($j%3==1) echo "<tr>";
	echo "<th>";
	if($post['ObField']['FieldType']=='DropDown'){echo "Select ";}
	echo $post['ObField']['FieldName']."</th><td>";

	$req = false;
	$type = 'text';
	$fun = "";
	
	if($post['ObField']['RequiredCheck']==1)
	{
		$req = true;
	}
	if($post['ObField']['FieldValidation']=='Numeric')
	{
		$type = 'Number';
		$fun = "return isNumberKey(event)";
	}
	
	if($post['ObField']['FieldType']=='TextBox')
	{
		echo $this->Form->input('Field'.$j++,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req));
	}
	if($post['ObField']['FieldType']=='TextArea')
	{
		echo $this->Form->textArea('Field'.$j++,array('label'=>false,'required'=>$req));
	}
	if($post['ObField']['FieldType']=='DropDown')
	{
		$option = array();
		$options = explode(',',$ObFieldValue[$post['ObField']['Id']]);
		$count = count($options);
		
		for( $i=0; $i<$count; $i++)
		{
			$option[$options[$i]] = $options[$i];
		}
		
		echo $this->Form->input('Field'.$j++,array('label'=>false,'options'=>$option,'required'=>$req));
	}
	
	echo "</td>";
if($j%3==0) echo "</tr>";
endforeach;
echo "</tr>";
?>
</table>
<?php
 echo $this->Form->input('DataId',array('value'=>$DataId,'type'=>'hidden'));
 echo $this->Form->end("Submit"); 
  ?>   
    
  </div>
<div id="tabs-3">
<div id="tabs2">
	<ul>
    	<?php $j = 1;
/*		foreach($history as $his):
			if(!empty($his['ObCampaignDataMaster']['CallDate'])){$date = date_create($his['ObCampaignDataMaster']['CallDate']);$his['ObCampaignDataMaster']['CallDate'] = date_format($date,'d M Y'); }
			else
			{$date = $his['ObCampaignDataMaster']['CallDate'];}
    		echo "<li><a href=\"#tabs2-".$j++."\">".$his['ObCampaignDataMaster']['CallDate']."</a></li>";
		endforeach;*/
		?>
	</ul>    

<?php $j = 1;
/*		foreach($history as $his):
    		echo "<div id=\"tabs2-".$j++."\">";
?>
			<table>
				<tr>
					<th>MSISDN</th>
<?php
			foreach($keys as $k)
			{ echo "<td>"."Category".$k."</td>";}
		
			
			foreach($fieldName as $post): 
				echo "<td>".$post['ObField']['FieldName']."</td>";
			endforeach;
?>
				</tr>
                <tr>
<?php       
			echo "<th>".$his['ObCampaignDataMaster']['MSISDN']."</th>";         
			foreach($keys as $k)
			{ echo "<td>".$his['ObCampaignDataMaster']["Category".$k]."</td>";}
		
			$k = 1;
			
			foreach($fieldName as $post):
				echo "<td>".$his['ObCampaignDataMaster']['Field'.$k++]."</td>";
			endforeach;
                */
?>                
                </tr>
            </table>
<?php			
/*		echo "</div>";
		endforeach;	*/
?>        
  </div>
 </div>
  <div id="tabs-4">
  <table>
  		<tr><?php
			
            foreach($keys as $k)
			{ echo "<th>"."Category".$k."</th>";}
			
			foreach($fieldName as $post): 
				echo "<th>".$post['ObField']['FieldName']."</th>";
			endforeach;	
			echo "<th>Calling Date</th>";		
			?>
        </tr>
 	<?php
		foreach($history as $his):
			echo "<tr>";
			
			foreach($keys as $k)
			{ echo "<td>".$his['ObCampaignDataMaster']["Category".$k]."</td>";}
			
			foreach($fieldName as $post):
				echo "<td>".$his['ObCampaignDataMaster']['Field'.$k++]."</td>";
			endforeach;
			
			echo "<td>".$his['ObCampaignDataMaster']['CallDate']."</td>";
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
    <?php echo $this->Form->create(); ?>
<table>
<tr><th>CallDate</th><td><?php echo $this->Form->input('CallDate',array('label'=>false,'onClick'=>"displayDatePicker('data[ObField][CallDate]');")); ?></td></tr>
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
		echo "<tr><th> Category".$key."</th>";						
		echo "<td>".$this->Form->input('Category'.$key, array('label'=>false,'options'=>$options))."</td></tr>";
	}
	
	unset($options); unset($key);unset($value);unset($ecr);unset($i);unset($j);
?>
<?php $j = 1;
foreach($fieldName as $post):

	echo "<tr><th>";
	if($post['ObField']['FieldType']=='DropDown'){echo "Select ";}
	echo $post['ObField']['FieldName']."</th><td>";

	$req = false;
	$type = 'text';
	$fun = "";
	
	if($post['ObField']['FieldValidation']=='Numeric')
	{		
		$fun = "return isNumberKey(event)";
	}
	
	if($post['ObField']['FieldType']=='DropDown')
	{
		$option = array();
		$options = explode(',',$ObFieldValue[$post['ObField']['Id']]);
		$count = count($options);
		
		for( $i=0; $i<$count; $i++)
		{
			$option[$options[$i]] = $options[$i];
		}
		
		echo $this->Form->input('Field'.$j++,array('label'=>false,'options'=>$option));
	}
	else
	{
		echo $this->Form->input('Field'.$j++,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun));
	}
	echo "</td></tr>";

endforeach;
?>
</table>
    <?php echo $this->Form->end("search"); ?>
    
<table>
<?php

if(isset($search) && is_array($search) && !empty($search))
{
echo "<tr>";
	
		echo "<th>Calling Date</th>";
	foreach($keys as $k)
	{ echo "<th>"."Category".$k."</th>";}
			
	foreach($fieldName as $post): 
		echo "<th>".$post['ObField']['FieldName']."</th>";
	endforeach;	
echo "</tr>";

	foreach($search as $serc):
	echo "<tr>";	
		foreach($serc['OutboundMaster'] as $ser=>$v) 
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
