<?php //print_r($fieldName); ?>
<?php //print_r($fieldValue); ?>
<ol class="breadcrumb">
    <li><a href="index.html">Home</a></li>
    <li><a href="#">Advanced Forms</a></li>
    <li class="active"><a href="ui-forms.html">Form Components</a></li>
</ol> 
<div class="page-heading">                                           
<h1>View Capture Field </h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
    <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Capture Fields</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
<table>
<tr>
	<th>Name</th>
	<th>Type</th>
	<th>Edit</th>
</tr>
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
		echo $this->Form->input($post['FieldCreation']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
	}
	if($post['FieldCreation']['FieldType']=='TextArea')
	{
		echo $this->Form->textArea($post['FieldCreation']['FieldName'],array('label'=>false,'required'=>$req,'class'=>'form-control'));
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
		echo $this->Form->input($post['FieldCreation']['FieldName'],array('label'=>false,'options'=>$option,'required'=>$req,'class'=>'form-control'));
	}
	echo "</td><td>";
        $id = base64_encode($post['FieldCreation']['id']);
	echo $this->Html->link('edit',array('controller'=>'ClientFields','action'=>'edit','?'=>array('id'=>$id)),array('class'=>'btn-raised btn-primary btn'));
	echo "</td></tr>";

endforeach;

?>

</table>                    
            </div>
        </div>          
    </div>
</div>
