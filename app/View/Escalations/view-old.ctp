<?php //print_r($fieldName); ?>
<?php //print_r($fieldValue); ?>
<html>
<body>
<table>
<?php

foreach($fieldName as $post):
	//echo "<br>".$post['FieldCreation']['FieldName']." ".$post['FieldCreation']['FieldType']." ".$post['FieldCreation']['FieldValidation']." ".$post['FieldCreation']['RequiredCheck'];
	echo "<tr><th>";
	if($post['FieldCreation']['FieldType']=='DropDown'){echo "Select ";}
	echo $post['FieldCreation']['FieldName']."</th><td>";

	$req = false;
	$type = 'text';
	$fun = "";
	
	if($post['FieldCreation']['RequiredCheck']==1)
	{
		$req = true;
	}
	if($post['FieldCreation']['FieldValidation']=='Numeric')
	{
		$type = 'Number';
		$fun = "return isNumberKey(event)";
	}
	
	if($post['FieldCreation']['FieldType']=='TextBox')
	{
		echo $this->Form->input($post['FieldCreation']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req));
	}
	if($post['FieldCreation']['FieldType']=='TextArea')
	{
		echo $this->Form->textArea($post['FieldCreation']['FieldName'],array('label'=>false,'required'=>$req));
	}
	if($post['FieldCreation']['FieldType']=='DropDown')
	{
		$option = array();
		$options = explode(',',$fieldValue[$post['FieldCreation']['id']]);
		$count = count($options);
		
		for( $i=0; $i<$count; $i++)
		{
			$option[$options[$i]] = $options[$i];
		}
		//print_r($count);

		//$option[$fieldValue[$post['FieldCreation']['id']]] = $fieldValue[$post['FieldCreation']['id']];
		echo $this->Form->input($post['FieldCreation']['FieldName'],array('label'=>false,'options'=>$option,'required'=>$req));
	}
	
	echo "</td></tr>";

endforeach;

?>

</table>
</body>
</html>