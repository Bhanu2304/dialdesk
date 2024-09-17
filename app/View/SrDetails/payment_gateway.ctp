    <script> 
    function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       function onlyAlphabets(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode==32 || (charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode > 7 && charCode < 9))
                    return true;
                else
                    return false;
            }
            catch (err) {
                alert(err.Description);
            }
        }
        
function checkMobileNumber(val,evt)
{
    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57))
        {            
		return false;
        }
        else if(val.length>9 && charCode>48 && charCode<57)
        {
            alert("Mobile No. Should be 10 digits Only");
            return false;
        }
	return true;
}        
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

<?php echo $this->Form->create('SrDetails',array('action'=>'update_payment_det','class'=>"form-horizontal row-border")); ?>
    <input type="hidden" name="id" value="<?php echo isset($closeId)?$closeId:"";?>"
    
           <div class="modal-body" >
            <div class="panel-body detail">
                <div class="tab-content">
                    <div class="tab-pane active" id="horizontal-form">
                        <div id="erroMsg1" style="color:red;font-size: 15px;margin-left:160px;"><?php echo $this->Session->flash();?></div>
                        
                        
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

                                        <td><?php echo $history['CallMaster']["Category".$k]?></td>
                                    </tr>
                                    <?php }?>
                                    <tr><th>REQUIRED FIELDS</th><th>VALUE</th></tr>
                                    <?php 
                                    $j=0;
                                    foreach($fieldName as $post){ 
                                    echo "<tr>";
                                    echo "<td>".$post['FieldMaster']['FieldName']."</td>";
                                    echo "<td>".$history['CallMaster'][$headervalue[$j]]."</td>";
                                    echo "</tr>";
                                    $j++;}
                                    ?>
                                    
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
                                        <td>DOWNLOAD LABEL</td>
                                        <td><a target="_blank"  href="http://dialdesk.co.in/printingpdf/examples/forwordlabel.php?rmano=<?php echo base64_encode($history['CallMaster']['SrNo']);?>"title="Download Label" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label></a></td>
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
                                
                                <div style="text-align: center;font-weight: bold;font-size: 15px;" >Return Shipping Status</div>
                                    <?php echo $ReturnShippingStatus;?>
                                <?php }?>
                                
                                <?php if($this->Session->read('companyid') =="277" && $history['CallMaster']["Category1"] =="Return Request" && $history['CallMaster']["Ret_AWBNo"] !=""){?>
                                <hr/>    
                                <div style="text-align: center;font-weight: bold;font-size: 15px;" >Foword Shipping Status</div>
                                    <?php echo $ForwordShippingStatus;?>
                                <?php }?>
                                
                            
                        
                        
                        
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

    

