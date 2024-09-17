<div class="page-content">
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Bound Campaign</a></li>
        <li class="active"><a href="#">View Capture Fields</a></li>
    </ol>
    <div class="page-heading">            
        <h1>View Capture Fields</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>View Capture Fields</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
						<?php
                        foreach($fieldName as $post):
                            echo "<tr><td>";
                            if($post['ObField']['FieldType']=='DropDown'){echo "Select ";}
                            echo $post['ObField']['FieldName']."</td><td>";
                        
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
                                echo $this->Form->input($post['ObField']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req));
                            }
                            if($post['ObField']['FieldType']=='TextArea')
                            {
                                echo $this->Form->textArea($post['ObField']['FieldName'],array('label'=>false,'required'=>$req));
                            }
                            if($post['ObField']['FieldType']=='DropDown')
                            {
                                $option = array();
                                $options = explode(',',$fieldValue[$post['ObField']['Id']]);
                                $count = count($options);
                                
                                for( $i=0; $i<$count; $i++)
                                {
                                    $option[$options[$i]] = $options[$i];
                                }
                               
                                echo $this->Form->input($post['ObField']['FieldName'],array('label'=>false,'options'=>$option,'required'=>$req));
                            }
                            
                            echo "</td></tr>";
                        
                        endforeach;
                        ?>
               		</table>
          		</div> 
            </div>
        </div>
    </div> 
</div>


