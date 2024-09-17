<?php ?>

<?php   
$CloseStatus[0] = 'Pending';
?>
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    var type=$("#type").val();
    var subtype=$("#subtype").val();
    
    var sub_scenario2=$("#sub_scenario2").val();
    var sub_scenario3=$("#sub_scenario3").val();
    var sub_scenario4=$("#sub_scenario4").val();
    
    var closestatus=$("#closestatus").val();
    //var msisdn=$("#msisdn").val();
    var firstsr=$("#firstsr").val();
    var lastsr=$("#lastsr").val();
    
    if(fdate !="" || ldate !="" || closestatus !="" || firstsr !="" || lastsr !=""<?php if(!empty($Category1)){?> || type !=""<?php }?><?php if(!empty($Category2)){?> || subtype !=""<?php }?><?php if(!empty($Category3)){?> || sub_scenario2 !=""<?php }?><?php if(!empty($Category4)){?> || sub_scenario3 !=""<?php }?><?php if(!empty($Category5)){?> || sub_scenario4 !=""<?php }?>){
     
    //if(fdate !="" || ldate !="" || type !="" || subtype !="" || sub_scenario2 !="" || sub_scenario3 !="" || sub_scenario4 !="" || closestatus !="" || firstsr !="" || lastsr !=""){
    
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>IbExportReports/download');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>SrDetails');
        }
        $('#validate-form').submit();
        return true;

    }
    else{
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid search item.</span>');
        return false;
    }
  
}

function updateCloseLoop(path,id){
    var cl1    = $("#close_loop_category").val();
    var cl2    = $("#sub_close_loop_category").val();
    var cldate = $("#closelooingdate").val();
    var close_remarks = $("#closelooping_remarks").val();
    var datestatus = $('#datestatus').val();

   
    if(cl1 ===""){
        alert('Please Selcet Close Loop.');
        return false;
    }
    else if(datestatus ==="A" && cldate ===""){
        alert('Please Selcet Date.');
        return false;
    }
    
    $.ajax({
            type:'post',
            url:path,
            data:{close_cat1:cl1,close_cat2:cl2,id:id,close_date:cldate,closelooping_remarks:close_remarks,CampaignId:'<?php echo isset($CampaignId)?$CampaignId:"";?>'},
            success:function(data){
                    if(data !=''){
                        $("#close_srdetails").trigger('click');
                        $("#srmsgpopup").trigger('click');
                        $("#showsrmsg").html('<span>Update SuccessFully.</span>');
                    }
            }
    });
  
}

function view_edit_sr(path,id){                    
    $.post(path,{id:id},function(data){
            $("#fields-data").html(data);
    }); 
}

function getSubCloseloop(clpid){
    $.post("<?php echo $this->webroot;?>OutboundCloseDetails/get_sub_closeloop",{parent_id:clpid.value,CampaignId:'<?php echo isset($CampaignId)?$CampaignId:"";?>'},function(data){
        $('#sub_close_loop_category').replaceWith(data);   
    });
    $.post("<?php echo $this->webroot;?>OutboundCloseDetails/get_date_picker",{parent_id : clpid.value,CampaignId:'<?php echo isset($CampaignId)?$CampaignId:"";?>'},function(data){
        if(data =="A"){
            $('#datestatus').val(data);
            $('#showdate').show();
        }
        else{
            $('#datestatus').val('');
            $('#showdate').hide();
        }
    });
}


function closing(){
    window.location.reload(); 
}


function checkCharacter(e,t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;
               
            }
            catch (err) {
                alert(err.Description);
            }
   }

</script>
<p id="srmsgpopup" data-toggle="modal" data-target="#srmsg"></p>
<div class="modal fade" id="srmsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <div class="modal-header">
                <button type="button" onclick="closing()" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                
                <h4> </h4>      
            </div>
          
            <div class="modal-body">
                <div id="showsrmsg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closing()" id="close-phone-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<?php
$scl=array(isset ($history['CallMasterOut']['CloseLoopCate2']) ? $history['CallMasterOut']['CloseLoopCate2'] : ""=>isset ($history['CallMasterOut']['CloseLoopCate2']) ? $history['CallMasterOut']['CloseLoopCate2'] : "");
?>

<div class="container-fluid" style="margin-top:10px" >
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>OUT CALL CLOSE LOOPING</h2>
                <div class="panel-ctrls"></div>
            </div>
            
            <div class="panel-body no-padding">
                <div class="col-md-6">
                    <div class="panel panel-primary" >
                        <div class="panel-heading">SR DETAILS</div>
                        <div class="panel-body">
                            <div style="height:660px;overflow:scroll;width:448px;">
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                    <tr><th>SCENARIO</th><th>VALUE</th></tr>                                 
                                    <?php 
                                    $keys = array_keys($ecr);
                                    foreach($keys as $k){ $no=$k-1;?>
                                    <tr>
                                        <?php if($k =='1'){?>
                                        <td>SCENARIO</td>
                                        <?php }else{?>
                                            <td>SUB SCENARIO <?php echo $no;?></td>
                                         <?php }?>

                                        <td><?php echo $history['CallMasterOut']["Category".$k]?></td>
                                    </tr>
                                    <?php }?>
                                    <tr><th>REQUIRED FIELDS</th><th>VALUE</th></tr>
                                    <?php 
                                    $j=0;
                                    foreach($fieldName as $post){ 
                                    echo "<tr>";
                                    echo "<td>".$post['ObField']['FieldName']."</td>";
                                    echo "<td>".$history['CallMasterOut'][$headervalue[$j]]."</td>";
                                    echo "</tr>";
                                    $j++;}
                                    ?>
                                    
                                    <tr><th>CALL DETAILS</th><th>VALUE</th></tr>
                                    <tr>
                                        <td>IN CALL ID</td>
                                        <td><?php echo $history['CallMasterOut']['SrNo']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>CALL FROM</td>
                                        <td><?php echo $history['CallMasterOut']['MSISDN']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>CALL DATE</td>
                                        <td><?php echo $history['CallMasterOut']["CallDate"]?></td>
                                    </tr>
                                    <tr>
                                        <td>CLOSER TYPE</td>
                                        <td><?php echo $history['CallMasterOut']["close_loop"]?></td>
                                    </tr>
                                    <tr>
                                        <td>CLOSER DATE</td>
                                        <td><?php echo $history['CallMasterOut']["CloseLoopingDate"]?></td>
                                    </tr>
                                    <!--
                                    <tr>
                                        <td>TAT</td>
                                        <td><?php echo $history['CallMasterOut']["tat"]?></td>
                                    </tr>
                                    <tr>
                                        <td>DUE DATE</td>
                                        <td><?php echo $history['CallMasterOut']["duedate"]?></td>
                                    </tr>
                                    -->
                                    <tr>
                                        <td>CALL CREATED</td>
                                        <td><?php echo $history['CallMasterOut']["callcreated"]?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="panel panel-primary" >
                        <div class="panel-heading">CLOSE FIELDS</div>
                        <div class="panel-body">
                            <div id="error" style="color:green;font-size: 15px;margin-left:20px;"><?php echo $this->Session->flash();?></div>
                            <div style="height:660px;overflow:scroll;width:448px;">
                                
                                <?php if($history['CallMasterOut']['close_loop'] !="system"){?>   
                                <div style="border: 1px solid silver;">
                                <?php echo $this->Form->create('OutboundCloseDetails',array('action'=>'update_srclose_field','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                                <input type="hidden" name="Id" value="<?php echo $callId;?>" >
                                <input type="hidden" name="CampaignId" value="<?php echo $CampaignId;?>" >
                                
                                
                                <?php 
                                //echo $this->Form->input('Id',array('label'=>false,'value'=>$callId,'type'=>'hidden','class'=>"form-control"));

                                $j = 1;
                                $f=1;

                                foreach($fieldName1 as $post):
                                echo '<div class="form-group">';

                                $fld="CField".$f;
                                $fName = $post['ObCloseFieldData']['fieldNumber'];
                                        if($j%4==0) echo '';
                                         echo '<label class="col-sm-3 control-label">';
                                        if($post['ObCloseFieldData']['FieldType']=='DropDown'){echo "Select ";}

                                        echo $post['ObCloseFieldData']['FieldName'].'';
                                        echo '</label>';
                                        $req = false;
                                        $type = 'text';
                                        $fun = "";
                                         echo '<div class="col-sm-6">';
                                        if($post['ObCloseFieldData']['RequiredCheck']==1)
                                        {
                                                $req = true;
                                        }
                                        if($post['ObCloseFieldData']['FieldValidation']=='Numeric')
                                        {
                                                $type = 'Number';
                                                $fun = "return isNumberKey(event)";
                                        }
                                        if($post['ObCloseFieldData']['FieldValidation']=='Datepicker')
                                        {

                                                $Datepicker="date-picker";
                                        }
                                        else{
                                          $Datepicker="";  
                                        }

                                        if($post['ObCloseFieldData']['FieldType']=='TextBox')
                                        {

                                                echo $this->Form->input('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>"form-control $Datepicker"));
                                        }
                                        if($post['ObCloseFieldData']['FieldType']=='TextArea')
                                        {
                                         //'value'=>isset($AgRecord)?$AgRecord['Field'.$fName]:"",     
                                                echo $this->Form->textArea('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'required'=>$req,'class'=>'form-control'));
                                        }
                                        if($post['ObCloseFieldData']['FieldType']=='DropDown')
                                        {
                                                $option = array();
                                                $options = explode(',',$fieldValue1[$post['ObCloseFieldData']['id']]);
                                                $count = count($options);

                                                for( $i=0; $i<$count; $i++)
                                                {
                                                        $option[$options[$i]] = $options[$i];
                                                }



                                                echo $this->Form->input('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'options'=>$option,'empty'=>'Select '.$post['ObCloseFieldData']['FieldName'],'required'=>$req,'class'=>'form-control'));
                                        }
                                        $j++;

                                        $f++;
                                        echo "</div>";
                                        echo "</div>";
                                endforeach;
                                ?> 
               
                                <?php if(!empty($fieldName1)){?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="submit" value="Update" class="btn-web btn pull-right" >
                                    </div>
                                </div><br/><br/>
                                <?php }?>
                                <?php echo $this->Form->end(); ?>
                                </div><br/>
                                <?php }?>
                                
                                
                                <?php if($history['CallMasterOut']['close_loop'] ==="system"){?>                           
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
                                    <tr>
                                        <td>Close Status</td>
                                       <td><?php echo $history['CallMasterOut']["CloseLoopCate1"]?></td>
                                    </tr>
                                    <?php if($history['CallMasterOut']["CloseLoopCate2"] !=""){ ?>
                                    <tr>
                                        <td>Close Sub Status</td>
                                       <td><?php echo $history['CallMasterOut']["CloseLoopCate2"]?></td>
                                    </tr>
                                    <?php }?>
                                </table>                             
                                <?php }?>
                                
                                <?php if($history['CallMasterOut']['close_loop'] !="system"){?>                          
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"> 
                                    <tr>
                                        <td>CALL ACTION</td>
                                        <td>
                                        <!--
                                        <?php if($clpid ==$key){?>selected="selected"<?php }?>
                                        -->
                                            <select style="width:200px;height:35px;" name="data[close_loop_category]" required id="close_loop_category" class="form-control1" onchange="getSubCloseloop(this)"  >
                                                <option value="">Select Call Action</option>
                                                <?php foreach($mpcat as $val){?>
                                                <option  value="<?php echo $val['clm']['id'];?>" ><?php echo $val['clm']['close_loop_category'];?></option>
                                                <?php }?>
                                            </select>

                                            <?php //echo $this->Form->input('close_loop_category',array('label'=>false,'options'=>$mpcat,'value'=>isset ($clpid) ? $clpid : "",'onchange'=>'getSubCloseloop(this)','class'=>'form-control','id'=>'close_loop_category','empty'=>'Select Close Status','required'=>true));?>

                                        </td>
                                    </tr>
                    
           
                                    <tr>
                                        <td>CALL SUB ACTION</td>
                                        <td>

                                        <?php 
                                            echo $this->Form->input('sub_close_loop_category',array('label'=>false,'options'=>'','style'=>'width:200px;height:35px;','id'=>'sub_close_loop_category'));
                                            //echo $this->Form->input('sub_close_loop_category',array('label'=>false,'options'=>array($history['CallMaster']["CloseLoopCate2"]=>$history['CallMaster']["CloseLoopCate2"]),'style'=>'width:200px;height:35px;','id'=>'sub_close_loop_category'));
                                        ?>
                                        </td>
                                    </tr>
                                        <input type="hidden" id="datestatus" >
                                        <tr id="showdate" style="display:none;">
                                            <td>FOLOW UP DATE</td>
                                            <td> <input type="text" name="data[CloseLoopingDate]" value="" id="closelooingdate" class="date-picker" placeholder="Select Date" style="width:200px;height:35px;" required ></td>
                                        </tr>



                                    <tr>
                                        <td>REMARKS</td>

                                        <td>
                                            <?php echo $this->Form->textarea('closelooping_remarks',array('label'=>false,'value'=>isset($history['CallMasterOut']['MSISDN'])?$history['CallMasterOut']['closelooping_remarks'] : "",'id'=>'closelooping_remarks','style'=>'width:200px;height:70px;'));?>
                                        </td>
                                    </tr>
                                    </table>
                                    
                                    <div class="form-group">
                                    <div class="col-sm-10">
                                        <input type="button" value="Submit" onclick="updateCloseLoop('<?php echo $this->webroot;?>OutboundCloseDetails/update_closeloop','<?php echo $history['CallMasterOut']['Id'];?>');" class="btn-web btn pull-right" >                                                          
                                    </div>
                                </div>    
                                    <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
                

            <div class="panel-footer"></div>
            </div>
        </div>
    </div>     
</div>

 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
  <script>
  $( function() {
    $( ".date-picker" ).datepicker({dateFormat: "yy-mm-dd"});
  });
</script>
  