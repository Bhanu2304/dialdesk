<?php ?>

<?php echo $this->Form->create('CloseDetails',array('action'=>'update_srclose_field','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
<div class="modal-body">
    <div class="panel-body detail">
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form" style="overflow-y: scroll;height: 450px;"  >
                <input type="hidden" name="Id" value="<?php echo $callId;?>" >
                    <?php 
                    //echo $this->Form->input('Id',array('label'=>false,'value'=>$callId,'type'=>'hidden','class'=>"form-control"));
                   
                    $j = 1;
                    $f=1;

                    foreach($fieldName1 as $post):
                    echo '<div class="form-group">';

                    $fld="CField".$f;
                    $fName = $post['CloseFieldData']['fieldNumber'];
                            if($j%4==0) echo '';
                             echo '<label class="col-sm-3 control-label">';
                            if($post['CloseFieldData']['FieldType']=='DropDown'){echo "Select ";}

                            echo $post['CloseFieldData']['FieldName'].'';
                            echo '</label>';
                            $req = false;
                            $type = 'text';
                            $fun = "";
                             echo '<div class="col-sm-6">';
                            if($post['CloseFieldData']['RequiredCheck']==1)
                            {
                                    $req = true;
                            }
                            if($post['CloseFieldData']['FieldValidation']=='Numeric')
                            {
                                    $type = 'Number';
                                    $fun = "return isNumberKey(event)";
                            }
                            if($post['CloseFieldData']['FieldValidation']=='Datepicker')
                            {

                                    $Datepicker="date-picker";
                            }
                            else{
                              $Datepicker="";  
                            }

                            if($post['CloseFieldData']['FieldType']=='TextBox')
                            {

                                    echo $this->Form->input('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>"form-control $Datepicker"));
                            }
                            if($post['CloseFieldData']['FieldType']=='TextArea')
                            {
                             //'value'=>isset($AgRecord)?$AgRecord['Field'.$fName]:"",     
                                    echo $this->Form->textArea('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'required'=>$req,'class'=>'form-control'));
                            }
                            if($post['CloseFieldData']['FieldType']=='DropDown')
                            {
                                    $option = array();
                                    $options = explode(',',$fieldValue1[$post['CloseFieldData']['id']]);
                                    $count = count($options);

                                    for( $i=0; $i<$count; $i++)
                                    {
                                            $option[$options[$i]] = $options[$i];
                                    }



                                    echo $this->Form->input('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'options'=>$option,'empty'=>'Select '.$post['CloseFieldData']['FieldName'],'required'=>$req,'class'=>'form-control'));
                            }
                            $j++;

                            $f++;
                            echo "</div>";
                            echo "</div>";
                    endforeach;
                    ?>  
            </div>
        </div>
    </div>   
</div>
<div class="modal-footer">
   
    <button type="button" id="close-sr-popup" onclick="closing();" class="btn btn-default" data-dismiss="modal">Close</button>
    <?php if(!empty($fieldName1)){?>
    <input type="submit" value="Submit" class="btn-web btn" >
    <?php }?>
    
</div>
<?php echo $this->Form->end(); ?>
 
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
  <script>
  $( function() {
    $( ".date-picker" ).datepicker({dateFormat: "yy-mm-dd"});
  });
  </script>
  