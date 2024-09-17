<script>    
function getClient(){
    $("#cptfields").submit();	
}
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>View Capture Field</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <?php  
                    echo $this->Form->create('AdminobclientFields',array('action'=>'view','id'=>'cptfields'));
                    echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true));
                    echo $this->Form->end();
                ?>
                <?php if(isset($clientid) && !empty($clientid)){?>
                
                <table class="display table table-bordered table-condensed table-hovered sortableTable">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Edit</th>
                    </tr>
                    <?php
                    foreach($fieldName as $post):

                            echo '<tr><td>';
                            if($post['ObfieldCreation']['FieldType']=='DropDown'){echo "Select ";}
                            echo $post['ObfieldCreation']['FieldName'].'</td><td>';

                            $req = false;
                            $type = 'text';
                            $fun = "";

                            if($post['ObfieldCreation']['RequiredCheck']==1)
                            {
                                    $req = true;
                            }
                            if($post['ObfieldCreation']['FieldValidation']=='Numeric')
                            {
                                    $type = 'Number';
                                    $fun = "return isNumberKey(event)";
                            }

                            if($post['ObfieldCreation']['FieldType']=='TextBox')
                            {
                                    echo $this->Form->input($post['ObfieldCreation']['FieldName'],array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
                            }
                            if($post['ObfieldCreation']['FieldType']=='TextArea')
                            {
                                    echo $this->Form->textArea($post['ObfieldCreation']['FieldName'],array('label'=>false,'required'=>$req,'class'=>'form-control'));
                            }
                            if($post['ObfieldCreation']['FieldType']=='DropDown')
                            {
                                    $option = array();
                                    $options = explode(',',$fieldValue[$post['ObfieldCreation']['id']]);
                                    $count = count($options);

                                    for( $i=0; $i<$count; $i++)
                                    {
                                            $option[$options[$i]] = $options[$i];
                                    }



                                    echo $this->Form->input($post['ObfieldCreation']['FieldName'],array('label'=>false,'options'=>$option,'required'=>$req,'class'=>'form-control'));
                            }
                            echo '</td><td>';
                            $id = base64_encode($post['ObfieldCreation']['id']);
                            echo $this->Html->link('edit',array('controller'=>'AdminobclientFields','action'=>'edit','?'=>array('id'=>$id,'cid'=>$clientid)),array('class'=>'btn-raised btn-primary btn'));
                            echo "</td></tr>";

                    endforeach;

                    ?>                  
                </table>             
                <input type="button" value="Back" class="btn-raised btn-primary btn" onclick="window.history.back()" />  
                <?php echo $this->Html->link('Create Capture', array('controller'=>'AdminobclientFields','action'=>'index','?'=>array('id'=>isset($clientid)?$clientid:"")),array('class'=>'btn-raised btn-primary btn')); ?>   
                <?php }?>
            </div>
        </div>
    </div>
</div>
