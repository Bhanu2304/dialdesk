<!--
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<script>
    $(document).ready(function() {
        $("#closelooingdate").datepicker();
    });
</script>
-->



<?php
$scl=array(isset ($history['CallMaster']['CloseLoopCate2']) ? $history['CallMaster']['CloseLoopCate2'] : ""=>isset ($history['CallMaster']['CloseLoopCate2']) ? $history['CallMaster']['CloseLoopCate2'] : "");
?>
<div class="modal-body">
    <div class="panel-body detail">
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
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
                            </table>
                        </td>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
                            <?php 
                            $j=0;
                            foreach($fieldName as $post){ 
                            echo "<tr>";
                            echo "<td>".$post['FieldMaster']['FieldName']."</td>";
                            echo "<td>".$history['CallMaster'][$headervalue[$j]]."</td>";
                            echo "</tr>";
                            $j++;}
                            ?>
                             </table>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
                    <tr>
                        <?php if($history['CallMaster']['close_loop'] ==="system"){?>
                            <td> 
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
                             </td>
                        <?php }?>


                        


                        <?php if($history['CallMaster']['close_loop'] !="system"){?>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables"> 
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
                            <?php echo $this->Form->textarea('closelooping_remarks',array('label'=>false,'value'=>isset($history['CallMaster']['MSISDN'])?$history['CallMaster']['closelooping_remarks'] : "",'id'=>'closelooping_remarks','style'=>'width:200px;height:70px;'));?>
                        </td>
                    </tr>
                    </table>
                    </td>
                   <?php }?>


                <td>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
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
                        <tr>
                            <td>CLOSER DATE</td>
                            <td><?php echo $history['CallMaster']["CloseLoopingDate"]?></td>
                        </tr>
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
                            <td><a href="http://182.19.10.3/download.php?mode=<?php echo $mode; ?>&filename=<?php echo $history['CallMaster']['LeadId'];?>"title="Download" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label></a></td>
                        </tr>
                    </table>
                </td>  
                
                </tr>
              
                
                    

                   

                  <?php if($history['CallMaster']['close_loop'] ==="system"){?>
                    <span>Close Looping Already Updated.</span>
                    <?php }?>
                    
                </table>
            </div>
        </div>
    </div>   
</div>
<div class="modal-footer">
   
    <button type="button" id="close-sr-popup" onclick="closing();" class="btn btn-default" data-dismiss="modal">Close</button>
    <?php if($history['CallMaster']['close_loop'] !="system"){?>
        <button type="button" onclick="updateCloseLoop('<?php echo $this->webroot;?>SrDetails/update_closeloop','<?php echo $history['CallMaster']['Id'];?>');" class="btn-web btn">Submit</button>
    <?php }?>
</div>

 
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
  <script>
  $( function() {
    $( ".date-picker" ).datepicker();
  });
  </script>
  