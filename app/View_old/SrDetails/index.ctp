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
    $.post("SrDetails/get_sub_closeloop",{parent_id : clpid.value},function(data){
        $('#sub_close_loop_category').replaceWith(data);   
    });
    $.post("SrDetails/get_date_picker",{parent_id : clpid.value},function(data){
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
    //location.reload();
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
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Operations</a></li>
    <li class="active"><a href="#">In Call Details</a></li>
</ol>
<div class="page-heading">            
    <h1>In Call Details</h1>
</div>
<div class="container-fluid">                     
    <div data-widget-group="group1">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>In Call Details</h2>
                        <div class="panel-ctrls"></div> 
                    </div>
                    <div class="panel-body no-padding">
                        <div id="error" style="color:red;font-size: 15px;margin-left:20px;"><?php echo $this->Session->flash();?></div>
                        <div style="margin-top:-23px;margin-left:15px;">
                            
                            <?php echo $this->Form->create('IbExportReports',array('id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <?php
                                            echo $this->Form->input('startdate',array('label'=>false,'value'=>isset($showVal['startdate'])?$showVal['startdate']:"",'placeholder'=>'Start Date','id'=>'fdate','class'=>'form-control date-picker'));
                                            if(!empty($Category1)){
                                                echo $this->Form->input('Category1',array('label'=>false,'value'=>isset($showVal['Category1'])?$showVal['Category1']:"",'options'=>empty($Category1)?$Category1:array_merge(array('All'=>'All'),$Category1),'class'=>'form-control','id'=>'type','empty'=>'Select Scenario'));
                                            }
                                        ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php
                                            echo $this->Form->input('enddate',array('label'=>false,'value'=>isset($showVal['enddate'])?$showVal['enddate']:"",'placeholder'=>'End Date','id'=>'ldate','class'=>'form-control date-picker'));
                                            if(!empty($Category2)){
                                                echo $this->Form->input('Category2',array('label'=>false,'value'=>isset($showVal['Category2'])?$showVal['Category2']:"",'options'=>empty($Category2)?$Category2:array_merge(array('All'=>'All'),$Category2),'class'=>'form-control','id'=>'subtype','empty'=>'Sub Scenario 1')); 
                                            }
                                        ?>
                                    </div>
                                   
                                    <div class="col-sm-2">
                                        <?php
                                            echo $this->Form->input('CloseLoopCate1',array('label'=>false,'value'=>isset($showVal['CloseLoopCate1'])?$showVal['CloseLoopCate1']:"",'options'=>$CloseStatus,'id'=>'closestatus','class'=>'form-control','empty'=>'In Call Action'));
                                            if(!empty($Category3)){
                                                echo $this->Form->input('Category3',array('label'=>false,'value'=>isset($showVal['Category3'])?$showVal['Category3']:"",'options'=>array_merge(array('All'=>'All'),$Category3),'class'=>'form-control','id'=>'sub_scenario2','empty'=>'Sub Scenario 2'));
                                            }
                                        ?>
                                    </div>
                                    <!--
                                    <div class="col-sm-2">
                                        <?php
                                            //echo $this->Form->input('CloseLoopCate1',array('label'=>false,'options'=>$CloseStatus,'id'=>'closestatus','class'=>'form-control','empty'=>'In Call Action'));
                                            //echo $this->Form->input('MSISDN',array('label'=>false,'maxlength'=>'10','pattern'=>'.{10,10}','onkeypress'=>'return checkCharacter(event,this)','id'=>'msisdn','class'=>'form-control','placeholder'=>'Call From'));
                                        ?>
                                    </div>
                                    -->
                                    <div class="col-sm-2">
                                        <?php 
                                          echo $this->Form->input('firstsr',array('label'=>false,'value'=>isset($showVal['firstsr'])?$showVal['firstsr']:"",'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>'First In Call Id','id'=>'firstsr','class'=>'form-control')); 
                                          if(!empty($Category4)){
                                            echo $this->Form->input('Category4',array('label'=>false,'value'=>isset($showVal['Category4'])?$showVal['Category4']:"",'options'=>empty($Category4)?$Category4:array_merge(array('All'=>'All'),$Category4),'class'=>'form-control','id'=>'sub_scenario3','empty'=>'Sub Scenario 3'));
                                          }
                                        ?>
                                    </div>
                                    
                                    <div class="col-sm-2"> 
                                        <?php 
                                            echo $this->Form->input('lastsr',array('label'=>false,'value'=>isset($showVal['lastsr'])?$showVal['lastsr']:"",'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>'Last In Call Id','id'=>'lastsr','class'=>'form-control'));
                                            if(!empty($Category5)){
                                                echo $this->Form->input('Category5',array('label'=>false,'value'=>isset($showVal['Category5'])?$showVal['Category5']:"",'options'=>empty($Category5)?$Category5:array_merge(array('All'=>'All'),$Category5),'class'=>'form-control','id'=>'sub_scenario4','empty'=>'Sub Scenario 4'));
                                            }
                                        ?>
                                    </div>

                                    <div class="col-sm-2" style="margin-top:-12px;">                      
                                        <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                                        <input type="button" style="width:108px;" onclick="validateExport('view');" class="btn btn-web" value="View" >
                                    </div>
                                   
                                </div>

                            <?php $this->Form->end(); ?>
                        </div>

                        <?php if(isset($history) && !empty($history)){?>
                        <div class="scrolling">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables ">
                            <thead>
                                <tr>
                                    <?php
                                    $keys = array_keys($ecr);
                                    echo "<th>VIEW</th>";
                                    echo "<th>RECORDING</th>";
                                    //echo "<th>Payment</th>";
                                    echo "<th>IN CALL ID</th>";
                                    echo "<th>CALL FROM</th>";
                                    foreach($keys as $k){
                                        $no=$k-1;
                                    
                                        if($k =='1'){
                                        echo "<th>SCENARIO</th>";
                                        }
                                        else {
                                            echo "<th>"."SUB SCENARIO".$no."</th>";
                                        }

                                    }
                                    foreach($fieldName as $post): 
                                        echo "<th>".$post['FieldMaster']['FieldName']."</th>";
                                    endforeach;
                                    echo "<th>Call Action</th>";
                                    echo "<th>Call Sub Action</th>";
                                    echo "<th>Call Action Remarks</th>";
                                    echo "<th>Closer Date</th>";
                                    echo "<th>Follow Up Date</th>";
                                    echo "<th>Call Date</th>";
                                    echo "<th>Tat</th>";
                                    echo "<th>Due Date</th>";
                                    echo "<th>Call Created</th>";
                                    echo "<th>Closer Time</th>";
                                    
                                    foreach($fieldName1 as $post): 
                                        echo "<th>".$post['CloseFieldData']['FieldName']."</th>";
                                    endforeach;
                                    
                                    echo "<th>Call Status</th>";
                                    echo "<th>Return AWB</th>";
                                    echo "<th>Return Token</th>";
                                    echo "<th>Pickup Date</th>";
                                    echo "<th>Forword AWB</th>";
                                    echo "<th>Forword Token</th>";
                                    echo "<th>Pickup Date</th>";
                                    ?>
                                </tr>
                            </thead>
                        <tbody>
                            <?php            
                            foreach($history as $his){
                                $mode='';
                                if($his['CallMaster']['CallType']=='Inbound'){$mode='DD';}
                                if($his['CallMaster']['CallType']=='Manual'){$mode='DD';}
                                
                                else if($his['CallMaster']['CallType']=='VFO-Inbound'){$mode='VFO';}
                                echo "<tr>";
                                    ?> 
                                    <td>
                                        <!--
                                        <a href="#" class="btn-raised" data-toggle="modal" data-target="#srdetails" onclick="view_edit_sr('<?php echo $this->webroot?>SrDetails/view_details','<?php echo $his['CallMaster']['Id'];?>');">
                                         <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-search-plus"></i><div class="ripple-container"></div></label>
                                        </a>
                                        <a href="#" class="btn-raised" data-toggle="modal" data-target="#srclosefields" onclick="view_srclose_fields('<?php echo $this->webroot?>CloseDetails/view_close_fields','<?php echo $his['CallMaster']['Id'];?>');">
                                         <label class="btn btn-xs btn-midnightblue btn-raised"><i class="material-icons">add_circle</i><div class="ripple-container"></div></label>
                                        </a>
                                        -->
                                        
                                        <a target="_blank" href="<?php echo $this->webroot?>CloseDetails/view_close_fields?id=<?php echo $his['CallMaster']['Id'];?>" >
                                         <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-search-plus"></i><div class="ripple-container"></div></label>
                                        </a>
                                    </td>
                                    
                                    <td>
                                        <a href="http://182.71.80.196/download-recording/download.php?mode=<?php echo $mode; ?>&filename=<?php echo $his['CallMaster']['LeadId'];?>">
                                             <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label>         
                                        </a>
                                    </td>
<!--                                    <td>
                                         <a  href="#" class="btn-raised" data-toggle="modal" data-target="#srdetails" onclick="payment_gateway('<?php //echo $his['CallMaster']['Id'];?>')" >
                                        
                                        <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-cc-visa"></i><div class="ripple-container"></div></label>
                                    </a> 
                                    </td>-->
                                                                 
                                    <?php
                                    echo "<td>".$his['CallMaster']['SrNo']."</td>";
                                    echo "<td>".$his['CallMaster']['MSISDN']."</td>";
                                    foreach($keys as $k){ 
                                        echo "<td>".$his['CallMaster']["Category".$k]."</td>";  
                                    } 
                                     
                                    foreach($headervalue as $header){
                                        echo "<td>".$his['CallMaster'][$header]."</td>";
                                    }
                                    if($his['CallMaster']['CloseLoopingDate'] !=""){
                                        $cld=$his['CallMaster']['CloseLoopingDate'];
                                    }
                                    else{
                                        $cld="";
                                    }
                                    
                                    if($cld !=""){
                                    $t1 = StrToTime ($cld);
                                    $t2 = StrToTime ($his['CallMaster']['CallDate']);
                                    $diff = $t1 - $t2;
                                    $hours = $diff / ( 60 * 60 );
                                    }
                                    else{
                                      $hours="";  
                                    }
                                            
                                    echo "<td>".$his['CallMaster']['CloseLoopCate1']."</td>";
                                    echo "<td>".$his['CallMaster']['CloseLoopCate2']."</td>";
                                    echo "<td>".$his['CallMaster']['closelooping_remarks']."</td>";
                                    echo "<td>".$cld."</td>";
                                    echo "<td>".$his['CallMaster']['FollowupDate']."</td>";
                                    echo "<td>".$his['CallMaster']['CallDate']."</td>";
                                    echo "<td>".$his['CallMaster']['tat']."</td>";
                                    echo "<td>".$his['CallMaster']['duedate']."</td>";
                                    echo "<td>".$his['CallMaster']['callcreated']."</td>";
                                    echo "<td>".round($hours)."</td>";
                                    
                                    foreach($headervalue1 as $header1){
                                        echo "<td>".$his['CallMaster'][$header1]."</td>";
                                    }
                                    
                                    echo "<td>".$his['CallMaster']['AbandStatus']."</td>";
                                    
                                    if($his['CallMaster']['AWBNo'] !=""){
                                        echo "<td>".$his['CallMaster']['AWBNo']."</td>";
                                    }
                                    else{
                                        if($his['CallMaster']['Category1']=="Return Request" && $his['CallMaster']['AreaPincode'] !=""){
                                        ?>
                                        <td>
                                            <a target="_blank" href="<?php echo $this->webroot?>SrDetails/bluedartapi?id=<?php echo $his['CallMaster']['Id'];?>" >
                                             Create AWB
                                            </a>
                                        </td>
                                        <?php
                                        }
                                        else{
                                            echo "<td></td>";
                                        }
                                        
                                    }
                                    
                                    
                                    echo "<td>".$his['CallMaster']['TokenNumber']."</td>";
                                    if($his['CallMaster']['AWBNo'] !=""){
                                        echo "<td>".$his['CallMaster']['CallDate']."</td>";
                                    }
                                    else{
                                        echo "<td></td>"; 
                                    }
                                    echo "<td>".$his['CallMaster']['Ret_AWBNo']."</td>";
                                    echo "<td>".$his['CallMaster']['Ret_TokenNumber']."</td>";
                                    echo "<td>".$his['CallMaster']['Ret_PikupDate']."</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                    <?php }?>
                
                    </div>
                <div class="panel-footer"></div>
            </div>   
            <div id="srpopup_details"></div>
            <div class="modal-backdrop fade in" style="height:100%;display:none;"></div>
            <div class="panel-footer"></div>
   	</div>
    </div>
</div> 

<!-- Edit Capture Fields -->
<div class="modal fade "  id="srdetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="top:35px;width: 800px;" >
        <div class="modal-content " >
             <div class="modal-header">
                <button type="button" onclick="closing()" class="close" id="close_srdetails" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">SR Details</h4>
            </div>
            <div id="fields-data"></div>
        </div>
    </div>
</div>

<script>
function view_srclose_fields(path,id){  
    $.post(path,{id:id},function(data){
        $("#srclosedata").html(data);
    }); 
}
</script>

<div class="modal fade "  id="srclosefields" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="top:35px;width: 700px;" >
        <div class="modal-content " >
             <div class="modal-header">
                <button type="button" onclick="closing()" class="close" id="close_srdetails" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">SR Close Fields</h4>
            </div>
            <div id="srclosedata"  ></div>
        </div>
    </div>
</div>

<script>
function payment_gateway(id){
   
    
        $.post("<?php echo $this->webroot;?>SrDetails/payment_gateway",{id:id},function(data){
            $("#fields-data").html(data);
          
    }); 
}
</script>







