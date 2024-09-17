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


function get_sendType(apiType)
{
    if(apiType=='SMS')
    {
        $("#edit_parent_mobile").show();
        $("#edit_parent_email").hide();
    }
    else if(apiType=='Email')
    {
        $("#edit_parent_email").show();
        $("#edit_parent_mobile").hide();
    }
    else if(apitType='Both')
    {
        $("#edit_parent_email").show();
        $("#edit_parent_mobile").show();
    }
    else
    {
        $("#edit_parent_email").hide();
        $("#edit_parent_mobile").hide();
    }
        
}

function send_payment_url()
{
    var CustomerName,SendType,EmailId,Mobile,Amount;
    CustomerName = $('#CustomerName').val();
    SendType = $('#SendType').val();
    EmailId = $('#EEmailId').val();
    Mobile = $('#MMobile').val();
    Amount = $('#OrderAmount').val();
    var flag = true;
    if(CustomerName=='')
    {
        alert("Please Fill Customer Name");
        flag = false;
    }
    else if(SendType=='')
    {
        alert("Please Fill Type Of Service");
        flag = false;
    }
    else if((SendType=='Email' || SendType=='Both') && EmailId=='')
    {
        alert("Please Fill Email Id");
        flag = false;
    }
    else if((SendType=='SMS' || SendType=='Both') && Mobile=='')
    {
        alert("Please Fill Mobile Number");
        flag = false;
    }
    
    else if(Amount=='')
    {
        alert("Please Fill Order Amount");
        flag = false;
    }
    
    
    
    return flag;
}
function get_enable_resen()
{
    if(document.getElementById('resen').checked)
    {
        $('#payment_status').show();
        $('#resen_pay').show();
        
    }
    else
    {
        $('#payment_status').hide();
        $('#resen_pay').hide();
    }
}
</script>
<?php echo $this->Form->create('CloseLoopings',array('action'=>'update_payment_det','class'=>"form-horizontal row-border")); ?>
    <input type="hidden" name="id" value="<?php echo isset($closeId)?$closeId:"";?>"
    
           <div class="modal-body" >
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <div id="erroMsg1" style="color:red;font-size: 15px;margin-left:160px;"><?php echo $this->Session->flash();?></div>
                        
                        
                        
                        <div class="form-group"> 
                            <label class="col-sm-2 control-label">Scenario</label>
                            <div class="col-sm-3">
                                <select class="form-control" id="editCategory1" name="Category1" onclick="editCategory(this,'1')" >
                                    <option value="">Select Scenario</option>
                                    <option <?php if(isset($cat1) && $cat1=="All"){echo "selected='selected'";} ?> value="All">All</option>
                                    <?php foreach($category as $key=>$value){?>
                                        <option <?php if(isset($cat1) && $cat1==$key){echo "selected='selected'";} ?> value="<?php echo $key;?>"><?php echo $value;?></option>
                                    <?php }?>
                                </select>                              
                            </div>
                            <label class="col-sm-2 control-label">Sub Scenario 1</label>
                            <div class="col-sm-3">
                                <select class="form-control" id="editCategory2" name="Category2"  onchange="editCategory(this,'2')" ></select>
                            </div>
                        </div>
                       
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Sub Scenario 2</label>
                            <div class="col-sm-3">
                                <select class="form-control" id="editCategory3" name="Category3"  onchange="editCategory(this,'3')" ></select>
                            </div>
                             <label class="col-sm-2 control-label">Sub Scenario 3</label>
                            <div class="col-sm-3">
                                <select class="form-control" id="editCategory4" name="Category4"  onchange="editCategory(this,'4')" ></select>
                            </div>
                        </div>
                        
                       
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Sub Scenario 4</label>
                            <div class="col-sm-3">
                                <select class="form-control" id="editCategory5" name="Category5"></select>
                            </div>
                             <label class="col-sm-2 control-label">Select Action Type</label>
                            <div class="col-sm-3">     
                                 <select class="form-control"  name="close_loop" id="edit_close_loop"  onchange="showdate(this.value),showEditParentBycloseLoop()">
                                    <option value="">Select Action Type</option>
                                    <option <?php if(isset($closeType) && $closeType ==="system"){echo "selected='selected'";}?> value="system">System</option>
                                    <option <?php if(isset($closeType) && $closeType ==="manual"){echo "selected='selected'";}?> value="manual">Manual</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        
                        <div class="form-group" id="edit_category_label_div">
                            <label class="col-sm-2 control-label">Select Action Label</label>
                            <div class="col-sm-3">     
                                <select class="form-control" name="label" onchange="selectLabelEdit(this.value)" id="edit_category_label">
                                    <option value="">Select Action Label</option>
                                    <option <?php if(isset($closeLabel) && $closeLabel ==="1"){echo "selected='selected'";}?>  value="1">Category</option>
                                    <option <?php if(isset($closeLabel) && $closeLabel ==="2"){echo "selected='selected'";}?> value="2">Sub Category</option>
                                </select>
                            </div>
                            
                            
                        </div>
                        
                        <?php if(!empty($PaymentDetails)) {?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Product</label>
                            <label class="col-sm-3 control-label" style="color:black">
                               <?php echo $PaymentDetails['PaymentOrderNo']['product'];?> 
                            </label>
                            <label class="col-sm-3 control-label">Customer Name</label>
                            <label class="col-sm-3 control-label" style="color:black">
                                <?php echo $PaymentDetails['PaymentOrderNo']['CustomerName'];?> 
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Media Type</label>
                            <label class="col-sm-3 control-label" style="color:black">
                                <?php echo $PaymentDetails['PaymentOrderNo']['SendType'];?> 
                            </label>
                            <?php if($PaymentDetails['PaymentOrderNo']['SendType']=='Email') { ?>
                            <label class="col-sm-3 control-label">Email Id</label>
                            <label class="col-sm-3 control-label" style="color:black">
                                <?php echo $PaymentDetails['PaymentOrderNo']['EEmailId'];?>
                            </label>
                            <?php } ?>
                            <?php if($PaymentDetails['PaymentOrderNo']['SendType']=='SMS') { ?>
                            <label class="col-sm-3 control-label">Mobile</label>
                            <label class="col-sm-3 control-label" style="color:black">
                                <?php echo $PaymentDetails['PaymentOrderNo']['MMobile'];?>
                            </label>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Amount</label>
                            <label class="col-sm-3 control-label" style="color:black">
                                <?php echo $PaymentDetails['PaymentOrderNo']['OrderAmount'];?> INR.
                            </label>
                            <label class="col-sm-3 control-label">Payment Status</label>
                            <label class="col-sm-3 control-label" style="color:black">
                                <?php echo $PaymentDetails['PaymentOrderNo']['PaymentStatus'];?> 
                            </label>
                        </div>
                        <div class="form-group">
                            <?php if($PaymentDetails['PaymentOrderNo']['PaymentStatus']!='Successful') { ?>
                            <label class="col-sm-6 control-label" style="color:black"><input type="checkbox" name="resen" id="resen" value="1" onclick="get_enable_resen()" />Do You Want To Send Payment Request Again</label>
                            <?php } ?>
                        </div>
                        
                        <?php } ?>
                        <div id="payment_status" <?php if(!empty($PaymentDetails)) { ?> style="display: none" <?php } ?> >   
                        
                        <div class="form-group" id="edit_parent_product">
                            <label class="col-sm-2 control-label">Product</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="Product" id="Product" required="" >
                                    <option value="Electrician" <?php if($PaymentDetails['PaymentOrderNo']['product']=='Electrician') echo "selected"; ?>>Electrician</option>
                                    <option value="Plumber" <?php if($PaymentDetails['PaymentOrderNo']['product']=='Plumber') echo "selected"; ?>>Plumber</option>
                                </select>
                            </div>
                            
                            <div id="edit_parent_customer">
                                <label class="col-sm-2 control-label">Customer Name</label>
                                <div class="col-sm-3">
                                    <input class="form-control" id="CustomerName" name="CustomerName" type="text" value="<?php echo $PaymentDetails['PaymentOrderNo']['CustomerName'];?>" required="true" /> 
                                </div>
                            </div>
                        </div>
                       
                        
                        <div class="form-group" id="edit_parent_send">
                            <label class="col-sm-2 control-label">Media Type</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="SendType" id="SendType" onchange="get_sendType(this.value)" required="" >
                                    <option value="SMS" <?php if($PaymentDetails['PaymentOrderNo']['SendType']=='SMS') echo "selected"; ?>>SMS</option>
                                    <option value="Email" <?php if($PaymentDetails['PaymentOrderNo']['SendType']=='Email') echo "selected"; ?>>Email</option>
<!--                                    <option value="Both">Both (SMS,Email)</option>-->
                                </select>
                            </div>
                            
                            <div  id="edit_parent_email"  <?php if(empty($PaymentDetails)) { ?> style="display:none" <?php } else if($PaymentDetails['PaymentOrderNo']['SendType']=='SMS') { ?>style="display:none" <?php }  ?>>
                            <label class="col-sm-2 control-label">Email Id</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="EEmailId" name="EEmailId" type="text" value="<?php echo $PaymentDetails['PaymentOrderNo']['EEmailId'];?>"  /> 
                            </div>
                        </div>
                            
                        </div>
                        
                        
                        
                        <div class="form-group" id="edit_parent_mobile1" >
                            <div id="edit_parent_mobile" <?php if(empty($PaymentDetails)) { ?> style="display:block" <?php } else if($PaymentDetails['PaymentOrderNo']['SendType']=='Email') { ?>style="display:none" <?php }  ?>>
                                <label class="col-sm-2 control-label">Mobile</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="MMobile" name="MMobile" type="text" value="<?php echo $PaymentDetails['PaymentOrderNo']['MMobile'];?>"  /> 
                            </div>
                            </div>
                            
                            
                            <div  id="edit_parent_amount">
                            <label class="col-sm-2 control-label">Amount INR</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="OrderAmount" name="OrderAmount" type="text" required="true" value="<?php echo $PaymentDetails['PaymentOrderNo']['OrderAmount'];?>" /> 
                            </div>
                        </div>
                        </div>
                        
                        </div>
                        
                        <br/>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="hidden" id="ClientId" name="ClientId" value="<?php echo $ClientId; ?>"  />
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"  />
            <button type="button" onclick="hideMsgpopup()" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            <?php if(empty($PaymentDetails)) { ?>
            <button type="submit" onclick="return send_payment_url()" class="btn-web btn">Send Payment Url</button>
            <?php }  ?>
            <div id="resen_pay" style="display: none">
            <button type="submit" onclick="return send_payment_url()" class="btn-web btn">ReSend Payment Url</button>
            </div>
        </div>
                        
<?php echo $this->Form->end(); ?> 
<script src="<?php echo $this->webroot;?>js/assets/js/application.js"></script>
<script src="<?php echo $this->webroot;?>js/assets/main/formcomponents.js"></script>

    

