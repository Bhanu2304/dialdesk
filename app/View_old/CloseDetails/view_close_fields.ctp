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
            data:{close_cat1:cl1,close_cat2:cl2,id:id,close_date:cldate,closelooping_remarks:close_remarks},
            success:function(data){
                    if(data !=''){
                        $("#close_srdetails").trigger('click');
                        $("#srmsgpopup").trigger('click');
                        $("#closelooping_remarks").val('');
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
   
    $.post("<?php echo $this->webroot;?>CloseDetails/checkorder",{id : clpid.value,CallMasterId:<?php echo $_REQUEST['id'];?>},function(data){
        if(data !=""){
            
            $.post("<?php echo $this->webroot;?>SrDetails/get_sub_closeloop",{parent_id : clpid.value},function(data){
                $('#sub_close_loop_category').replaceWith(data);   
            });
            $.post("<?php echo $this->webroot;?>SrDetails/get_date_picker",{parent_id : clpid.value},function(data){
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
        else{
            alert('Please select step by step correct call action.');
            document.getElementById('close_loop_category').value = "";
            document.getElementById('sub_close_loop_category').innerHTML = "";
            return false;
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

<script type="text/javascript">
window.onload = function (){
    getcategorylist('<?php echo $history['CallMaster']["Category1"]?>','1','CategoryData1','<?php echo $history['CallMaster']["Category1"]?>');
    getcategorylist('<?php echo $history['CallMaster']["Category1"]?>','2','CategoryData2','<?php echo $history['CallMaster']["Category2"]?>');
    getcategorylist('<?php echo $history['CallMaster']["Category2"]?>','3','CategoryData3','<?php echo $history['CallMaster']["Category3"]?>');
    getcategorylist('<?php echo $history['CallMaster']["Category3"]?>','4','CategoryData4','<?php echo $history['CallMaster']["Category4"]?>');
    getcategorylist('<?php echo $history['CallMaster']["Category4"]?>','5','CategoryData5','<?php echo $history['CallMaster']["Category5"]?>');
}

function getcategorylist(ecrName,Label,divid,value){
    $.get("<?php echo $this->webroot;?>CloseDetails/getcategorylist",{ecrName:ecrName,Label:Label,value:value}, function(data, status){
        $("#"+divid).html(data);
    }); 
}

function editcategorylist(ecrName,Label,divid){
    
    if(Label =="1"){
        $("#CategoryData3").html("<option value=''>Select</option>");
        $("#CategoryData4").html("<option value=''>Select</option>");
        $("#CategoryData5").html("<option value=''>Select</option>");
    }
    else if(Label =="2"){
        $("#CategoryData4").html("<option value=''>Select</option>");
        $("#CategoryData5").html("<option value=''>Select</option>");
    }
    else if(Label =="3"){
        $("#CategoryData5").html("<option value=''>Select</option>");
    }
    
    $.get("<?php echo $this->webroot;?>CloseDetails/editcategorylist",{ecrName:ecrName,Label:Label}, function(data, status){
        $("#"+divid).html(data);  
    }); 
}
</script>

<!-- $this->Session->read('companyid') -->
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
$scl=array(isset ($history['CallMaster']['CloseLoopCate2']) ? $history['CallMaster']['CloseLoopCate2'] : ""=>isset ($history['CallMaster']['CloseLoopCate2']) ? $history['CallMaster']['CloseLoopCate2'] : "");
?>

<div class="container-fluid" style="margin-top:10px" >
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>CLOSE LOOPING</h2>
                <div class="panel-ctrls"></div>
            </div>
            
            <div class="panel-body no-padding">
                <div class="col-md-6">
                    <div class="panel panel-primary" >
                        <div class="panel-heading">SR DETAILS</div>
                        <div class="panel-body">
                            <div style="height:660px;overflow:scroll;width:448px;">
                                <?php echo $this->Form->create('CloseDetails',array('action'=>'update_customer_field','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                                <input type="hidden" name="Id" value="<?php echo $callId;?>" >
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

                                        <td>
                                            <select onchange="editcategorylist(this.value,'<?php echo $k;?>','CategoryData<?php echo $k+1;?>');" id="CategoryData<?php echo $k;?>" name="data[CloseDetails][Category<?php echo $k;?>]" style="width:150px;" >
                                                <option value="">Select</option>
                                            </select>
                                            
                                        </td>
                                    </tr>
                                    <?php }?>
                                    <tr><th>REQUIRED FIELDS</th><th>VALUE</th></tr>
                                    <?php 
                                    $j=0;
                                    foreach($fieldName as $post){    
                                    echo "<tr>";
                                    echo "<td>".$post['FieldMaster']['FieldName']."</td>";
                                    //echo "<td>".$history['CallMaster'][$headervalue[$j]]."</td>";
                                    ?>
                                    <td>
                                    <input type="text" name="data[CloseDetails][<?php echo $headervalue[$j];?>]" value="<?php echo $history['CallMaster'][$headervalue[$j]];?>">
                                    </td>
                                    <?php
                                    echo "</tr>";
                                    $j++;}
                                    ?>
                                    
                                    <tr><th></th><th><input type="submit" value="Update" class="btn-web btn pull-left" ></th></tr>
                                   
                                    
                                    <tr><th>CALL DETAILS</th><th>VALUE</th></tr>
                                    <tr>
                                        <td>IN CALL ID</td>
                                        <td><?php echo $history['CallMaster']['SrNo']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>CALL FROM</td>
                                        <td><?php echo $history['CallMaster']['MSISDN']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>CALL DATE</td>
                                        <td><?php echo $history['CallMaster']["CallDate"]?></td>
                                    </tr>
                                    <!--
                                    <tr>
                                        <td>CLOSER DATE</td>
                                        <td><?php echo $history['CallMaster']["CloseLoopingDate"]?></td>
                                    </tr>
                                    -->
                                    <tr>
                                        <td>TAT</td>
                                        <td><?php echo $history['CallMaster']["tat"]?></td>
                                    </tr>
                                    <tr>
                                        <td>DUE DATE</td>
                                        <td><?php echo $history['CallMaster']["duedate"]?></td>
                                    </tr>
                                    <tr>
                                        <td>CALL CREATED</td>
                                        <td><?php echo $history['CallMaster']["callcreated"]?></td>
                                    </tr>

                                    <?php
                                   
                                    $mode='';
                                    if($history['CallMaster']['CallType']=='Inbound'){$mode='DD';}
                                    else if($history['CallMaster']['CallType']=='VFO-Inbound'){$mode='VFO';}   
                                    ?> 

                                    <tr>
                                        <td>RECORDING</td>
                                        <td><a href="http://182.71.80.196/download-recording/download.php?mode=<?php echo $mode; ?>&filename=<?php echo $history['CallMaster']['LeadId'];?>"title="Download" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label></a></td>
                                    </tr>
                                    
                                    <?php if($this->Session->read('companyid') =="283"){ ?>
                                    <tr>
                                        <td>DOWNLOAD FORM</td>
                                        <td><a target="_blank" href="http://dialdesk.co.in/dialdesk/app/webroot/printpdf/examples/summerking00.php?SKID=<?php echo base64_encode($history['CallMaster']['Id']);?>&SKCD=<?php echo base64_encode($this->Session->read('companyid'));?>" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label></a></td>
                                        
                                    </tr>
                                    <?php }?>
                                    
                                    
                                    <?php if($this->Session->read('companyid') =="277" && $history['CallMaster']["Category1"] =="Return Request" && $history['CallMaster']["Ret_AWBNo"] !=""){?>
                                    <tr>
                                        <td>DOWNLOAD LABEL </td>
                                        <!--
                                        <td><a target="_blank"  href="http://dialdesk.co.in/printingpdf/examples/forwordlabel.php?rmano=<?php //echo base64_encode($history['CallMaster']['SrNo']);?>"title="Download Label" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label></a></td>
                                        -->
                                        
                                        <td><a target="_blank"  href="<?php print_r($history['CallMaster']['Ret_DestinationLocation']);?>" title="Download Label" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label></a></td>
                                        
                                    </tr>
                                    <?php }?>
                                    
                                    
                                    <?php if(!empty($CsUpdate)){ ?>
                                    <tr><th colspan="2">CLOSE LOOPING</th></tr>
                                    <tr>
                                        <td colspan="2">
                                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                                <tr><td>ACTION</td><td>SUB ACTION</td><td>REMARKS</td><td>Update</td><td>Follow Up</td></tr>
                                                <?php  foreach($CsUpdate as $CS){?>
                                                    <tr>
                                                        <td><?php echo $CS['CloseStatusHistory']['CloseLoopCategory']?></td>
                                                        <td><?php echo $CS['CloseStatusHistory']['CloseLoopSubCategory']?></td>
                                                        <td><?php echo $CS['CloseStatusHistory']['Remarks']?></td>
                                                        <td><?php echo $CS['CloseStatusHistory']['CreateDate']?></td>
                                                        <td><?php echo $CS['CloseStatusHistory']['FollowUpDate']?></td>
                                                    </tr>
                                                <?php }?>
                                            </table>   
                                        </td>
                                    </tr>                
                                    <?php }?>
                                    
                                    <?php if($history['CallMaster']["CloseLoopStatus"] !=""){?>
                                        <tr><td colspan="2" >STATUS - ( <?php echo $history['CallMaster']["CloseLoopStatus"]?> )</td></tr>
                                    <?php } ?>
                                </table>
                                
                                <?php if($this->Session->read('companyid') =="277" && $history['CallMaster']["Category1"] =="Return Request" && $history['CallMaster']["AWBNo"] !=""){?>
                                <!--
                                <div style="text-align: center;font-weight: bold;font-size: 15px;" >Return Shipping Status</div>
                                -->
                                <?php echo $ReturnShippingStatus;?>
                                <?php }?>
                                
                                <?php if($this->Session->read('companyid') =="277" && $history['CallMaster']["Category1"] =="Return Request" && $history['CallMaster']["Ret_AWBNo"] !=""){?>
                                <hr/>  
                                <!--
                                <div style="text-align: center;font-weight: bold;font-size: 15px;" >Foword Shipping Status</div>
                                -->   
                                 <?php echo $ForwordShippingStatus;?>
                                <?php }?>
                                
                            <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="panel panel-primary" >
                        <div class="panel-heading">CLOSE FIELDS
                        
                            <?php if($this->Session->read('companyid') =="277" && $history['CallMaster']["Category1"] =="Return Request" && $history['CallMaster']["AWBNo"] !=""){?>
                            <a href="<?php echo $this->webroot;?>CloseDetails/bluedartapi?lastid=<?php echo $callId;?>"  style="float:right;margin-left:20px;" onclick="return confirm('Are you sure you want to generate shipment?');" >Create Shipment</a>    
                            <a href="<?php echo $this->webroot;?>CloseDetails/bluedartapi?lastid=<?php echo $callId;?>"  style="float:right;" onclick="return confirm('Are you sure you want to generate order?');" >Create Order </a>
                   
                            <?php }?>
                        
                        </div>
                        <div class="panel-body">
                            <div id="error" style="color:green;font-size: 15px;margin-left:20px;"><?php echo $this->Session->flash();?></div>
                            <div style="height:660px;overflow:scroll;width:448px;">
                                
                                 <div style="border: 1px solid silver;">
                                <?php echo $this->Form->create('CloseDetails',array('action'=>'update_srclose_field','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
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
                                        else if($post['CloseFieldData']['FieldValidation']=='Timepicker')
                                        {

                                                $Datepicker="timepicker";
                                        }
                                        else{
                                          $Datepicker="";  
                                        }

                                        if($post['CloseFieldData']['FieldType']=='TextBox')
                                        {

                                                echo $this->Form->input('CField'.$fName,array('label'=>false,'value'=>isset($CArr)?$CArr['CField'.$fName]:"",'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'autocomplete'=>'off','class'=>"form-control $Datepicker"));
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
               
                                <?php if(!empty($fieldName1)){?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="submit" value="Update" class="btn-web btn pull-right" >
                                    </div>
                                </div><br/><br/>
                                <?php }?>
                                <?php echo $this->Form->end(); ?>
                                </div><br/>
                               
                                
                                <?php if($history['CallMaster']['close_loop'] ==="system"){?>                           
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
                                    <tr>
                                        <td>Close Status</td>
                                       <td><?php echo $history['CallMaster']["CloseLoopCate1"]?></td>
                                    </tr>
                                    <?php if($history['CallMaster']["CloseLoopCate2"] !=""){ ?>
                                    <tr>
                                        <td>Close Sub Status</td>
                                       <td><?php echo $history['CallMaster']["CloseLoopCate2"]?></td>
                                    </tr>
                                    <?php }?>
                                </table>                             
                                <?php }?>
                                
                             
                                
                                <?php if($history['CallMaster']['close_loop'] !="system"){?>                          
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"> 
                                    <tr>
                                        <td>CALL ACTION</td>
                                        <td>
                                            <select style="width:200px;height:35px;" name="data[close_loop_category]" required id="close_loop_category" class="form-control1" onchange="getSubCloseloop(this)"  >
                                                <option value="">Select Call Action</option>
                                                <?php foreach($mpcat as $val){?>
                                                <option <?php if (in_array($val['clm']['id'], $CloseUpdateList)){?> disabled <?php }?> value="<?php echo $val['clm']['id'];?>" ><?php echo $val['clm']['close_loop_category'];?></option>
                                                <?php }?>
                                            </select>
                                        </td>
                                    </tr>
                    
           
                                    <tr>
                                        <td>CALL SUB ACTION</td>
                                        <td>

                                        <?php 
                                            echo $this->Form->input('sub_close_loop_category',array('label'=>false,'options'=>'','style'=>'width:200px;height:35px;','id'=>'sub_close_loop_category'));
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
                                            <?php echo $this->Form->textarea('closelooping_remarks',array('label'=>false,'id'=>'closelooping_remarks','style'=>'width:200px;height:70px;'));?>
                                        </td>
                                    </tr>
                                    </table>
                                    
                                    <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="button" value="Close Looping" onclick="updateCloseLoop('<?php echo $this->webroot;?>SrDetails/update_closeloop','<?php echo $history['CallMaster']['Id'];?>');" class="btn-web btn pull-right" >                                                          
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


<?php echo $this->Html->script('WorkFlow/src/wickedpicker'); ?>
<link rel="stylesheet" href="<?php echo $this->webroot; ?>js/WorkFlow/stylesheets/wickedpicker.css">
<script type="text/javascript">
    $('.timepicker').wickedpicker({now: '00:00', twentyFour: true, title:'My Timepicker', showSeconds: false
    });
</script>
  