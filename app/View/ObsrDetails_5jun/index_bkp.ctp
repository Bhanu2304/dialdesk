<script>
function validateExport(){
    $(".w_msg").remove();
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    var campname=$("#CampaignId").val();
    var alocname=$("#AllocationId").val();
    var category1=$("#category1").val();
    var category2=$("#category2").val();
    var firstsr=$("#firstsr").val();
    var lastsr=$("#lastsr").val();
    var msisdn=$("#msisdn").val();
   
    if(campname ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select campaign name.</span>');
        return false;
    }
    else if(alocname ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select allocation name.</span>');
        return false;
    }
    else if(fdate || ldate !=""){
        if ((new Date(fdate).getTime()) <= (new Date(ldate).getTime())) {return true;} 
            else {
                $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
                return false;
        }
    } 
    else if(firstsr || lastsr !=""){
        if(firstsr ==="" || lastsr ===""){
            $("#error").html('<span class="w_msg err" style="color:red;">Please select valid srno.</span>');
            return false;
        }
        else if(parseInt(firstsr) > parseInt(lastsr)){
            $("#error").html('<span class="w_msg err" style="color:red;">Please select valid srno.</span>');
            return false;
        }
    }


}
	
function get_allocation_data(path,camp){
    var campid=camp.value;
    $.ajax({
            type:'post',
            url:path,
            data:{campid:campid},
            success:function(data){
                $("#AllocationId").html(data);
            }
    });
   
    $.post('<?php echo $this->webroot;?>ObsrDetails/get_type',{campid:campid},function(data){
        $("#category1").html(data);
    });
    $.post('<?php echo $this->webroot;?>ObsrDetails/get_subtype',{campid:campid},function(data){
        $("#category2").html(data);
    });	
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

function viewOutboundSR(path,srno){                    
    $.post(path,{srno:srno},function(data){
            $("#outbound-data").html(data);
    }); 
}

</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Client Operations</a></li>
    <li class="active"><a href="#">Outbound SR Details</a></li>
</ol>
<div class="page-heading">            
    <h1>Outbound SR Details</h1>
</div>
<div class="container-fluid">                     
    <div data-widget-group="group1">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>VIEW OUTBOUND SR DETAILS</h2>
                        <div class="panel-ctrls"></div> 
                    </div>
                    <div class="panel-body no-padding scrolling">
                        <div id="error" style="margin-left:15px;"><?php echo $this->Session->flash();?></div><br/>
                        <div style="margin-top:-23px;margin-bottom:30px;margin-left:15px;">
                            <?php echo $this->Form->create('ObExportReports',array('action'=>'download','onsubmit'=>'return validateExport()','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>                              
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('CampaignName',array('label'=>false,'id'=>'CampaignId','onchange'=>'get_allocation_data("'.$this->webroot.'ObExportReports/get_allocation",this)','options'=>$Campaign,'empty'=>'Select Campaign','class'=>'form-control'));?>
                                        <?php echo $this->Form->input('AllocationName',array('label'=>false,'id'=>'AllocationId','onchange'=>'get_count("'.$this->webroot.'DataAllocations/get_count",this)','options'=>'','empty'=>'Select Allocation','class'=>'form-control'));?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('Category1',array('label'=>false,'options'=>'','id'=>'category1','class'=>'form-control'));?>
                                        <?php echo $this->Form->input('Category2',array('label'=>false,'options'=>'','id'=>'category2','class'=>'form-control'));?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'start date','id'=>'fdate','class'=>'form-control date-picker'));?>
                                        <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'end date','id'=>'ldate','class'=>'form-control date-picker'));?>
                                    </div>
                                    <div class="col-sm-2"> 
                                        <?php 
                                            echo $this->Form->input('firstsr',array('label'=>false,'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>'First SRNO','id'=>'firstsr','class'=>'form-control'));
                                            echo $this->Form->input('lastsr',array('label'=>false,'onkeypress'=>'return checkCharacter(event,this)','placeholder'=>'Last SRNO','id'=>'lastsr','class'=>'form-control'));
                                        ?>
                                    </div>
                                     <div class="col-sm-2">   
                                        <?php
                                            echo $this->Form->input('MSISDN',array('label'=>false,'maxlength'=>'10','pattern'=>'.{10,10}','onkeypress'=>'return checkCharacter(event,this)','id'=>'msisdn','class'=>'form-control','placeholder'=>'MSISDN'));                                        
                                        ?>
                                        
                                        <input type="submit" style="margin-top:2px;margin-left:10px;" class="btn btn-web" value="Export" >
                                    </div>
                                </div>
                            <?php $this->Form->end(); ?>
                        </div>

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
                            <thead>
                                <tr>
                                    <?php
                                    //print_r($fieldName);
                                    
                                    $keys = array_keys($ecr);
                                    echo "<th>View</th>";
                                    echo "<th>Download</th>";
                                    echo "<th>SrNo</th>";
                                    echo "<th>MSISDN</th>";
                                    foreach($keys as $k){ 
                                        echo "<th>"."Category".$k."</th>";
                                    }
                                    foreach($fieldName as $post): 
                                        echo "<th>".$post['ObField']['FieldName']."</th>";
                                    endforeach;
                                    echo "<th>Calling Date</th>";		
                                    ?>
                                </tr>
                            </thead>
                        <tbody>
                            <?php            
                            foreach($history as $his){
                                echo "<tr>";

                                   ?> 
                                    <td><a href="#" class="btn-raised" data-toggle="modal" data-target="#obsr" onclick="viewOutboundSR('<?php echo $this->webroot?>ObsrDetails/view_details','<?php echo $his['ObcallMasterout']['SrNo'];?>');">View</a></td> 
                                    <td><a href="#">Download</a></td>
                           
                                    <?php

                                echo "<td>".$his['ObcallMasterout']['SrNo']."</td>";
                                echo "<td>".$his['ObcallMasterout']['MSISDN']."</td>";
                                foreach($keys as $k){ 
                                    echo "<td>".$his['ObcallMasterout']["Category".$k]."</td>";  
                                } 
                                 $j=1;
                                foreach($fieldName as $post){  
                                    echo "<td>".$his['ObcallMasterout']['Field'.$j]."</td>";
                                    $j++;
                                }
                                echo "<td>".$his['ObcallMasterout']['CallDate']."</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

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
<div class="modal fade "  id="obsr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;" >
        <div class="modal-content " >
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Outbound SR Details</h4>
            </div>
            <div id="outbound-data"></div>
        </div>
    </div>
</div>





