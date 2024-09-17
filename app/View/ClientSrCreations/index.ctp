<script>
function getEcrChild(id)
{
    
var i;
if(id=='ClientSrCreationsCategory1') i = 1;
if(id=='ClientSrCreationsCategory2') i = 2;
if(id=='ClientSrCreationsCategory3') i = 3;
if(id=='ClientSrCreationsCategory4') i = 4;
if(id=='ClientSrCreationsCategory5') i = 5;
var labeld = i;
for(; i<5; i++)
{
	try{
		document.getElementById("ClientSrCreationsCategory"+(i+1)).value;					
		document.getElementById("cat1").deleteRow(labeld);
		}
		catch(err)
		{}
}

	var parent= document.getElementById("ClientSrCreationsCategory1").value;
	var label = 1;

	if(parent==''){ return;}
	
	try{
		
		 parent = document.getElementById("ClientSrCreationsCategory2").value;
		 label =2;
		 
		 
		 parent = document.getElementById("ClientSrCreationsCategory3").value;
		 label =3;
		
		
		 parent = document.getElementById("ClientSrCreationsCategory4").value;
		 label =4;
		 
		 
		 parent = document.getElementById("ClientSrCreationsCategory5").value;
		 label =5;	
		 
		 }
	catch(err)
	{
		
	}
	
	$.post("/dialdesk/ClientSrCreations/getChild",
        {
          Parent: parent,
          Label: label
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
			if(data!='')
			{
				var table = document.getElementById("cat1");
				var row   = table.insertRow(label);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				cell1.innerHTML = "Category"+(label+1);
				cell2.innerHTML = data;
			}			
        });
	
}

</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">SR Details</a></li>
    <li class="active"><a href="#">Add SR Details</a></li>
</ol>
<div class="page-heading">            
    <h1>Add SR Details</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
      

        
<div class="panel panel-default" data-widget='{"draggable": "false"}'>
    <div class="panel-heading">
        <h2>Tagging</h2><br/>
        <div><h4 style="text-align: left;"> Welcome <?php echo $this->Session->read("username");?></h4><hr/></div>
        <div style="color:red;margin-left:75px;font-size: 15px;"><?php echo $this->Session->flash();?></div>
        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>          
    </div>
    
    <div data-widget-controls="" class="panel-editbox"></div>
    <div class="panel-body">
        <?php echo $this->Form->create('ClientSrCreations',array('action'=>'save_srcreation','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
        <!--
        <div class="form-group">
            <label class="col-sm-3 control-label">MSISDN</label>	
            <div class="col-sm-6">      
                <?php echo $this->Form->input('MSISDN',array('label'=>false,"onkeyup"=>"this.value=this.value.replace(/[^0-9]/,'')",'placeholder'=>'phone no','data-parsley-type'=>'digits','data-parsley-minlength'=>'10','maxlength'=>'10','class'=>'form-control','required'=>true ));?>
            </div>
        </div>
        -->
        <table id="cat1">
        <?php
	$keys = array_keys($ecr);
	for($i =0; $i <1; $i++){
            $key = $keys[$i];
            $value = explode(",",$ecr[$key]);
            $options = array();
            for($j =0; $j<count($value); $j++)
            {
                $options[$value[$j]] = $value[$j];
            }
            
            if($i%3==0) echo "<tr>";
            echo "<td> Category".$key."</td>";
            echo "<td>".$this->Form->input('Category'.$key, array('label'=>false,'empty'=>'Select Category','options'=>$options,'onChange'=>'getEcrChild(this.id)'))."</td>";
            if($i%3==2) echo "</tr>";
	}
	unset($options); unset($key);unset($value);unset($i);unset($j);
	echo "</tr>";
        ?>
</table><hr/>
        
        
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
            <?php echo $this->Form->hidden('ClientId',array('value'=>$this->Session->read('companyid')));?>
            <div class="panel-footer">
                <div class="clearfix">
                    <input type="submit" name="submit" value="submit" class="btn btn-primary btn-raised pull-right">
                </div>
            </div>
            
        </div>
        <?php $this->Form->end(); ?>
    </div>
</div>
    </div>
</div> 

