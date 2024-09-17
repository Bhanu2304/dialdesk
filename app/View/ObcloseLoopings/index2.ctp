<script src="<?php echo $this->webroot;?>js/closelooping.js"></script>
<script>
var catid = ["Category1","Category2","Category3","Category4","Category5",];
var maxid = ["close_loop","parent-child","parent_id",];

function selectCategory(pid,id){
    if(pid.value ===""){
        var pcid='0'
    }
    else{
        var pcid=pid.value;
    }
    var k = Number(id) + Number(1);
  
    $.post("<?php echo $this->webroot?>ObcloseLoopings/selectCategory",{parent_id:pcid,divid:k,CampaignId:'<?php echo isset($cmid)?$cmid:"";?>'},function(data){
       if(pid.value ==="" && id ==="1"){
           $("#Category3").html('');
           $("#Category4").html('');
           $("#Category5").html('');
       }
       if(pid.value ==="" && id ==="2"){
           $("#Category4").html('');
           $("#Category5").html('');
       }
       if(pid.value ==="" && id ==="3"){
           $("#Category5").html('');
       }
       $("#Category"+k).html(data);
    });
    
}



function showParentBycloseLoop(){
    $.post("<?php echo $this->webroot?>ObcloseLoopings/get_parent_action",{close_loop:$("#close_loop").val(),CampaignId:'<?php echo isset($cmid)?$cmid:"";?>'},function(data){
        $("#parent_category").html(data);
    });
}


function selectLabel(label){
    $("#create_category").hide();
    $("#parent_category").hide();
    
    if(label =="1"){
        $("#create_category").show();
    }
    if(label =="2"){
        $.post("<?php echo $this->webroot?>ObcloseLoopings/get_parent_action",{close_loop:$("#close_loop").val(),CampaignId:'<?php echo isset($cmid)?$cmid:"";?>'},function(data){
            $("#parent_category").show();
            $("#parent_category").html(data);
        });
        $("#create_category").show();
        
    }
}

function getCampaignId(id){
    $("#campaign_form").submit();
}

</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >In Call Management</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>ObcloseLoopings">Manage Out Call Actions</a></li>
</ol>
<div class="page-heading">            
    <h1>Manage Out Call Actions</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        
        <?php echo $this->Form->create('ObcloseLoopings',array('action'=>'index2','id'=>'campaign_form',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
            <div class="form-group" style="margin-top:-23px;">
                <div class="col-sm-4">
                    <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'value'=>isset($cmid)? $cmid :"",'onchange'=>'getCampaignId(this);','empty'=>'Select Campaign','class'=>'form-control'));?>
                </div>

            </div><br/>
        <?php echo $this->Form->end(); ?>
        
        <?php if(isset($cmid) && $cmid !=""){?>
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage Out Call Actions</h2>
            </div>
            <div class="panel-body">
                <div id="erroMsg" class="red" style="font-size: 15px;margin-left:38px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('ObcloseLoopings',array('action'=>'save_close_loop','onsubmit'=>'return closeLoopValidate()','class'=>'form-horizontal','data-parsley-validate')); ?>
                <div class="col-md-5">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                            <select class="form-control" id="Category1" name="Category1" onchange="selectCategory(this,'1')" required >
                                <option value="">Select Scenario</option>
                                <option value="All">All</option>
                                <?php foreach($category as $key=>$value){?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php }?>
                            </select>
                            <select class="form-control" id="Category2" name="Category2"  onchange="selectCategory(this,'2')" ></select>
                            <select class="form-control" id="Category3" name="Category3"  onchange="selectCategory(this,'3')" ></select>
                            <select class="form-control" id="Category4" name="Category4"  onchange="selectCategory(this,'4')" ></select>
                            <select class="form-control" id="Category5" name="Category5"></select>   
                       </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                            <select class="form-control"  name="close_loop" id="close_loop"  onchange="getCloseLoopCategory('<?php echo $this->webroot?>ObcloseLoopings/get_parent_closeloop',catid,maxid),showParentBycloseLoop()" required >
                                <option value="">Select Action Type</option>
                                <option <?php if(isset($closeloop['close_loop']) && $closeloop['close_loop'] ==="system"){echo "selected='selected'";}?> value="system">System</option>
                                <option <?php if(isset($closeloop['close_loop']) && $closeloop['close_loop'] ==="manual"){echo "selected='selected'";}?> value="manual">Manual</option>
                            </select>
                            
                            <select class="form-control" name="label" onchange="selectLabel(this.value)" id="category_label">
                                <option value="">Select Action Label</option>
                                <option  value="1">Category</option>
                                <option  value="2">Sub Category</option>
                            </select>
       
                            <select class="form-control" name="parent_id" id="parent_category"  style="display:none;">
                                <option value="">Select Parent Action</option>
                                <?php foreach($parent_category as $key=>$catval){?>
                                    <option value="<?php echo $key;?>"><?php echo $catval;?></option>
                                <?php }?>
                            </select>
                            
                            
                            <input type="text" name="close_loop_category" class="form-control" style="display:none;" id="create_category" placeholder="Create Action Category">
                            <!--
                            <input type="text" name="close_loop_category[]" class="form-control" id="create_sub_category" placeholder="Create Action Sub Category" > 
                            -->
                       </div>
                    </div>
          
                    <div class="col-xs-12" style="display: none;" id="dateoption" >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span><br/>                               
                            <div class="checkbox checkbox-black" >
                                <label>
                                    <span style="font-size:13px;margin-left:-15px;">Select Date Option</span>
                                    <input type="checkbox" name="close_looping_date" value="A" onclick="checkOption('date_field','checkdate','close_looping_date')" id="checkdate" >
                                </label>             
                            </div>                               
                        </div>
                    </div>
  

                <div class="col-md-12">
                      <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input  type="submit" class="btn btn-web pull-right" value="Submit" >
                       </div>
                    </div>
                </div>
                    <input type="hidden" name="CampaignId" value="<?php echo isset($cmid)?$cmid:"";?>" >

                <?php $this->Form->end(); ?>
            </div> 
        </div>
            
        <?php if(!empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW CLOSE LOOPING</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Scenario</th>
                            <th>Sub Scenario 1</th>
                            <th>Sub Scenario 2</th>
                            <th>Sub Scenario 3</th>
                            <th>Sub Scenario 4</th>
                            <th>Action Type</th>
                            <th>Action Category</th>
                            <th>Action Sub Category</th>
                            <th>CALENDER OPTION</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;foreach($data as $row){?>
                            <tr >
                                <td><?php echo $i++;?></td>
                                <td><?php echo $row['CategoryName1'];?></td>
                                <td><?php echo $row['CategoryName2'];?></td>
                                <td><?php echo $row['CategoryName3'];?></td>
                                <td><?php echo $row['CategoryName4'];?></td>
                                <td><?php echo $row['CategoryName5'];?></td>
                                <td><?php echo $row['close_loop'];?></td>
                                <td><?php echo $row['close_loop_category'];?></td>
                                <td><?php echo $row['close_loop_sub_category'];?></td>
                                <td><?php if($row['close_looping_date'] !="A"){echo "No";}else{echo "Yes";}?> </td>
                                <td>
                                    <a  href="#" class="btn-raised" data-toggle="modal" data-target="#fieldsUpdate" onclick="view_edit_close_loop('<?php echo $row['id'];?>')" >
                                        <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                    </a> 
                                    <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObcloseLoopings/delete_close_loop?id=<?php echo $row['id'];?>&CampaignId=<?php echo isset($cmid)?$cmid:"";?>')" >
                                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                    </a>
                                </td>  
                            </tr>
                        <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>

    </div>
            
    <?php }?> 
        
            
</div>

<script>
function view_edit_close_loop(id){                    
        $.post("<?php echo $this->webroot;?>ObcloseLoopings/edit",{id:id,CampaignId:'<?php echo isset($cmid)?$cmid:"";?>'},function(data){
            $("#fields-data").html(data);
    }); 
}
</script>

<!-- Edit Capture Fields -->
<div class="modal fade" id="fieldsUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:70px;">
        <div class="modal-content">
             <div class="modal-header">
                 <button type="button" onclick="hideMsgpopup()" id="close-loop-popup"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Out Call Action</h4>
            </div>
            <div id="fields-data"></div>
        </div>
    </div>
</div> 

<a class="btn btn-primary btn-lg" id="show-loop-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body">
                        <p id="loop-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hideMsgpopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

