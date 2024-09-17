<?php
if(isset($result)){
    $cat1           =   $result['CloseLoopMaster']['Category1'];
    $cat2           =   $result['CloseLoopMaster']['Category2'];
    $cat3           =   $result['CloseLoopMaster']['Category3'];
    $cat4           =   $result['CloseLoopMaster']['Category4'];
    $cat5           =   $result['CloseLoopMaster']['Category5'];
    $closeType      =   $result['CloseLoopMaster']['close_loop'];
    $closeLabel      =   $result['CloseLoopMaster']['label'];
    $parent_id      =   $result['CloseLoopMaster']['parent_id'];
    $closeCategory  =   $result['CloseLoopMaster']['close_loop_category'];
    $closeSubCategory  =  $result['CloseLoopMaster']['close_loop_sub_category'];
    $clDate         =   $result['CloseLoopMaster']['close_looping_date'];
    $OrderBy         =   $result['CloseLoopMaster']['orderby'];
    $OrderNo         =   $result['CloseLoopMaster']['orderno'];
    $InCallStatus         =   $result['CloseLoopMaster']['InCallStatus'];
    $closeParentId  =   "";
    $closeId  =   $result['CloseLoopMaster']['id'];
    
    if($clDate !=""){
        $closeDate=date("m/d/Y",strtotime($clDate));
    }
    
    $display=$closeType !="manual"?"display:none":"";
    $display1=$clDate ==""?"display:none":"";
    
}
?>     
<script>
var catid1 = ["editCategory1","editCategory2","editCategory3","editCategory4","editCategory5",];
var maxid1 = ["edit_close_loop","edit-parent-child","edit_parent_id",];
   
$(document).ready(function(){
     <?php if(isset($cat2) && $cat2 !=""){?>
        matchEditData(<?php echo $cat1;?>,'1','<?php echo $cat2;?>');
     <?php }?>
     <?php if(isset($cat3) && $cat3 !=""){?>
        matchEditData(<?php echo $cat2;?>,'2','<?php echo $cat3;?>');
     <?php }?>
      <?php if(isset($cat4) && $cat4 !=""){?>
        matchEditData(<?php echo $cat3;?>,'3','<?php echo $cat4;?>');
     <?php }?>
    <?php if(isset($cat5) && $cat5 !=""){?>
        matchEditData(<?php echo $cat4;?>,'4','<?php echo $cat5;?>');
     <?php }?>
});

<?php if(isset($clDate) && $clDate !=""){?>
    $(document).ready(function(){
        $('#edit_date_field').show();
    });
<?php }?>
    
function matchEditData(pid,id,cid){
    var n = Number(id) + Number(1);
    $.post("<?php echo $this->webroot?>CloseLoopings/editSelectCategory",{parent_id:pid,cid:cid,divid:n},function(data){ 
         $("#editCategory"+n).html(data);
    });
}

function editCategory(pid,id){
    if(pid.value ===""){
        var pcid='0'
    }
    else{
        var pcid=pid.value;
    }

    var k = Number(id) + Number(1);
  
    $.post("<?php echo $this->webroot?>CloseLoopings/selectCategory",{parent_id:pcid,divid:k},function(data){
        if(pid.value ==="" && id ==="1"){
           $("#editCategory3").html('');
           $("#editCategory4").html('');
           $("#editCategory5").html('');
        }
        if(pid.value ==="" && id ==="2"){
           $("#editCategory4").html('');
           $("#editCategory5").html('');
        }
        if(pid.value ==="" && id ==="3"){
           $("#editCategory5").html('');
        }
         $("#editCategory"+k).html(data);
    });
    
}

function showdate(data){
    $("#editdateoption").hide();
    if(data ==="manual"){
        $("#editdateoption").show();
    }
}


function showEditParentBycloseLoop(){
    $.post("<?php echo $this->webroot?>CloseLoopings/get_parent_action",{close_loop:$("#edit_close_loop").val()},function(data){
        $("#edit_parent_category").html(data);
    });
}

function selectLabelEdit(label){
    $("#edit_close_loop_category_div").hide();
    $("#edit_parent_div").hide();
    if(label =="1"){
        $("#edit_close_loop_category_div").show();
    }
    if(label =="2"){
        $("#edit_close_loop_category_div").show();
        $("#edit_parent_div").show();
    }
}

$(document).ready(function(){
    <?php if(isset($closeLabel) && $closeLabel =='1'){?>
        $("#edit_close_loop_category_div").show();
    <?php }?>
    <?php if(isset($closeLabel) && $closeLabel =='2'){?>
        $.post("<?php echo $this->webroot?>CloseLoopings/edit_parent_action",{close_loop:$("#edit_close_loop").val(),sd:<?php echo $parent_id;?>},function(data){
            $("#edit_close_loop_category_div").show();
            $("#edit_parent_category").html(data);
        });
        $("#edit_parent_div").show();
    <?php }?>
});

$(document).ready(function(){
    <?php if(isset($OrderBy) && $OrderBy =='Yes'){?>
        $("#orderno_edit").show();
        $("#orderno_edit").val(<?php echo $OrderNo;?>);
    <?php }?>
});

function checkorder_edit(){
    $("#orderno_edit").hide();
    if(document.getElementById('orderby_edit').checked == true){
        $("#orderno_edit").show();
    }
}

$(document).ready(function(){
    <?php if(isset($closeLabel) && $closeLabel =='1'){?>
        $("#CloseStatusDivEdit").show();
        
    <?php }?>
});



</script>
<?php echo $this->Form->create('CloseLoopings',array('action'=>'update_close_loop','class'=>"form-horizontal row-border")); ?>
    <input type="hidden" name="id" value="<?php echo isset($closeId)?$closeId:"";?>"
    
        <div class="modal-body">
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <div id="erroMsg1" style="color:red;font-size: 15px;margin-left:160px;"><?php echo $this->Session->flash();?></div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-6">
                               <div class="checkbox checkbox-inline checkbox-black checkbox-left">
                                    <label>
                                        Order By <input type="checkbox" <?php if($OrderBy =="Yes"){?> checked<?php }?> id="orderby_edit" onclick="checkorder_edit();" value="Yes" name="orderby" > 
                                    </label>
                                    <input type="text"  name="orderno" onkeypress="return checkCharacter(event,this)" maxlength="2" id="orderno_edit"  style="width:65px;height:35px;display: none;" placeholder="Order No">
                              </div>
                            </div>
                        </div>
                        
                        <div class="form-group"> 
                            <label class="col-sm-3 control-label">Scenario</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="editCategory1" name="Category1" onclick="editCategory(this,'1')" >
                                    <option value="">Select Scenario</option>
                                    <option <?php if(isset($cat1) && $cat1=="All"){echo "selected='selected'";} ?> value="All">All</option>
                                    <?php foreach($category as $key=>$value){?>
                                        <option <?php if(isset($cat1) && $cat1==$key){echo "selected='selected'";} ?> value="<?php echo $key;?>"><?php echo $value;?></option>
                                    <?php }?>
                                </select>                              
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sub Scenario 1</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="editCategory2" name="Category2"  onchange="editCategory(this,'2')" ></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sub Scenario 2</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="editCategory3" name="Category3"  onchange="editCategory(this,'3')" ></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sub Scenario 3</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="editCategory4" name="Category4"  onchange="editCategory(this,'4')" ></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sub Scenario 4</label>
                            <div class="col-sm-6">
                                <select class="form-control" id="editCategory5" name="Category5"></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Select Action Type</label>
                            <div class="col-sm-6">     
                                 <select class="form-control"  name="close_loop" id="edit_close_loop"  onchange="showdate(this.value),showEditParentBycloseLoop()">
                                    <option value="">Select Action Type</option>
                                    <option <?php if(isset($closeType) && $closeType ==="system"){echo "selected='selected'";}?> value="system">System</option>
                                    <option <?php if(isset($closeType) && $closeType ==="manual"){echo "selected='selected'";}?> value="manual">Manual</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" id="edit_category_label_div">
                            <label class="col-sm-3 control-label">Select Action Label</label>
                            <div class="col-sm-6">     
                                <select class="form-control" name="label" onchange="selectLabelEdit(this.value)" id="edit_category_label">
                                    <option value="">Select Action Label</option>
                                    <option <?php if(isset($closeLabel) && $closeLabel ==="1"){echo "selected='selected'";}?>  value="1">Category</option>
                                    <option <?php if(isset($closeLabel) && $closeLabel ==="2"){echo "selected='selected'";}?> value="2">Sub Category</option>
                            </select>
                            </div>
                        </div>
                        
                        <div class="form-group" style="display:none;" id="edit_parent_div">
                            <label class="col-sm-3 control-label">Select Parent Action</label>
                            <div class="col-sm-6">     
                                <select class="form-control" name="parent_id" id="edit_parent_category"  >
                                    <!--
                                    <option value="">Select Parent Action</option>
                                    <?php foreach($parent_category as $key=>$catval){?>
                                        <option <?php if(isset($parent_id) && $parent_id ==$key){echo "selected='selected'";}?> value="<?php echo $key;?>"><?php echo $catval;?></option>
                                    <?php }?>
                                    -->
                                </select>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        <div class="form-group" style="display:none;" id="edit_close_loop_category_div">
                            <label class="col-sm-3 control-label">Create Action Category</label>
                            <div class="col-sm-6">                              
                                <input type="text" name="close_loop_category" value="<?php echo isset($closeCategory)?$closeCategory:"";?>" class="form-control" id="edit_close_loop_category" placeholder="Create Action Category" required >
                            </div>
                        </div>
                        
                        
                        
                        <!--
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Create Action Sub Category</label>
                            <div class="col-sm-6">
                                <input type="text" name="close_loop_category[]" value="<?php echo isset($closeSubCategory)?$closeSubCategory:"";?>" class="form-control" id="create_sub_category" placeholder="Create Action Sub Category" >   
                            </div>
                        </div>
                        -->
                        <div class="form-group" style="<?php echo $display;?>" id="editdateoption" >
                            <label class="col-sm-3 control-label">Select Date Option</label>
                            <div class="col-sm-6">
                               
                                <div class="checkbox checkbox-black" >
                                    <label>
                                        <input type="checkbox" name="close_looping_date" value="A"  <?php if(isset($clDate) && $clDate !=""){echo "checked";}?>   onclick="checkOption('edit_date_field','checkEditdate','edit_close_looping_date')" id="checkEditdate" >
                                   
                                    </label>              
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group" style="display: none;" id="CloseStatusDivEdit" >
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-6">
                                <div class="radio radio-inline radio-primary redio-left">
                                    <label>
                                        <input type="radio" <?php if(isset($InCallStatus) && $InCallStatus =="OPEN"){?> checked<?php }?> name="InCallStatus"  value="OPEN"> OPEN
                                    </label>
                                </div><br/>
                                <div class="radio radio-inline radio-primary redio-left" >
                                    <label>
                                        <input type="radio" <?php if(isset($InCallStatus) && $InCallStatus =="CLOSE"){?> checked<?php }?> name="InCallStatus" value="CLOSE"> CLOSE
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        <!--
                        <div class="form-group" style="<?php //echo $display1;?>" id="edit_date_field" >
                            <label class="col-sm-3 control-label">Create Sub Category</label>
                            <div class="col-sm-6">
                                <input type="text" name="close_looping_date" class="form-control date-picker" value="<?php //echo isset($closeDate)?$closeDate:"";?>" id="edit_close_looping_date" placeholder="Select Date" >                             
                            </div>
                        </div>
                        -->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" onclick="hideMsgpopup()" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" onclick="return updateCloseLoop(this.form,'<?php echo $this->webroot;?>CloseLoopings/update_close_loop')" class="btn-web btn">Submit</button>
        </div>
<?php echo $this->Form->end(); ?> 
<script src="<?php echo $this->webroot;?>js/assets/js/application.js"></script>
<script src="<?php echo $this->webroot;?>js/assets/main/formcomponents.js"></script>

    

