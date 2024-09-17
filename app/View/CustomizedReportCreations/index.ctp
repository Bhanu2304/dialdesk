
<script>

</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Customized MIS</a></li>
</ol>

<div class="page-heading">            
    <h1>Manage Customized MIS</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage Customized MIS</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('CustomizedReportCreations',array('action'=>'index','id'=>'showsheet')); ?>
                <div class="form-group" style="margin-top:-10px;" >
                    <div class="col-sm-12"><?php echo $this->Session->flash();?></div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-4">
                        <select  name="SheetName" class="form-control" onchange="this.form.submit();" required="" >
                            <option value="">Select Sheet Name</option>
                            <?php foreach($SheetName as $key=>$value){?>
                            <option <?php if($SelectSheetName =="Category$value##$key"){echo "selected='selected'";} ?> value="Category<?php echo $value;?>##<?php echo $key;?>"><?php echo $key;?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="col-sm-1">
                        <input type="text" name="SheetOrder" value="<?php echo isset($selectedOrder)?$selectedOrder:0?>" style="text-align: center;" class="form-control" autocomplete="off" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-12 control-label" style="font-size:16px;color;color:black;" >Select Header</label>
                    <div class="col-sm-12"><hr/></div>
                </div>
            
                <div class="form-group">
                    <div class="col-sm-12" style="overflow-y: scroll;height:350px;" >
                        <input type="checkbox" name="HeaderName[]" <?php echo isset($selectedData['SrNo']['rename'])?'checked':''?> value="SrNo">
                        <input type="text" name="Order_SrNo"  value="<?php echo isset($selectedData['SrNo']['order'])?$selectedData['SrNo']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_SrNo" value="<?php echo isset($selectedData['SrNo']['rename'])?$selectedData['SrNo']['rename']:'SrNo'?>"  autocomplete="off">
                        <label style="font-size: 12px;" >SrNo</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['MSISDN']['rename'])?'checked':''?> value="MSISDN">
                        <input type="text" name="Order_MSISDN"  value="<?php echo isset($selectedData['MSISDN']['order'])?$selectedData['MSISDN']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_MSISDN" value="<?php echo isset($selectedData['MSISDN']['rename'])?$selectedData['MSISDN']['rename']:'MSISDN'?>"  autocomplete="off">
                        <label style="font-size: 12px;" >MSISDN</label><br/>

                        <input type="checkbox" name="HeaderName[]"   <?php echo isset($selectedData['CallDate']['rename'])?'checked':''?> value="CallDate">
                        <input type="text" name="Order_CallDate"  value="<?php echo isset($selectedData['CallDate']['order'])?$selectedData['CallDate']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_CallDate" value="<?php echo isset($selectedData['CallDate']['rename'])?$selectedData['CallDate']['rename']:'CallDate'?>"  autocomplete="off">
                        <label style="font-size: 12px;" >CallDate</label><br/>

                        <?php foreach($ScenarioName as $key=>$value){?>
                            <input type="checkbox" name="HeaderName[]" <?php echo isset($selectedData['Category'.$value]['rename'])?'checked':''?> value="Category<?php echo $value;?>">
                            <input type="text" name="Order_Category<?php echo $value;?>"  value="<?php echo isset($selectedData['Category'.$value]['order'])?$selectedData['Category'.$value]['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                            <input type="text" name="Rename_Category<?php echo $value;?>" value="<?php echo isset($selectedData['Category'.$value]['rename'])?$selectedData['Category'.$value]['rename']:'Scenario'.$value?>"  autocomplete="off">
                            <label style="font-size: 12px;" ><?php echo "Scenario$value";?></label><br/>
                        <?php }?>

                        <?php foreach($FieldName as $key=>$value){?>
                            <input type="checkbox" name="HeaderName[]" <?php echo isset($selectedData['Field'.$value]['rename'])?'checked':''?>  value="Field<?php echo $value;?>">
                            <input type="text" name="Order_Field<?php echo $value;?>"  value="<?php echo isset($selectedData['Field'.$value]['order'])?$selectedData['Field'.$value]['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                            <input type="text" name="Rename_Field<?php echo $value;?>" value="<?php echo isset($selectedData['Field'.$value]['rename'])?$selectedData['Field'.$value]['rename']:$key?>"  autocomplete="off">
                            <label style="font-size: 12px;" ><?php echo $key;?></label><br/>
                        <?php }?>

                        <?php foreach($CloseField as $key=>$value){?>
                            <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['CField'.$value]['rename'])?'checked':''?> value="CField<?php echo $value;?>">
                            <input type="text" name="Order_CField<?php echo $value;?>"  value="<?php echo isset($selectedData['CField'.$value]['order'])?$selectedData['CField'.$value]['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                            <input type="text" name="Rename_CField<?php echo $value;?>" value="<?php echo isset($selectedData['CField'.$value]['rename'])?$selectedData['CField'.$value]['rename']:$key?>"  autocomplete="off">
                            <label style="font-size: 12px;" ><?php echo $key;?></label><br/>
                        <?php }?>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['close_loop']['rename'])?'checked':''?> value="close_loop">
                        <input type="text" name="Order_close_loop"  value="<?php echo isset($selectedData['close_loop']['order'])?$selectedData['close_loop']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_close_loop" value="<?php echo isset($selectedData['close_loop']['rename'])?$selectedData['close_loop']['rename']:'close_loop'?>"  autocomplete="off">
                        <label style="font-size: 12px;">Close Action Type</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['CloseLoopCate1']['rename'])?'checked':''?> value="CloseLoopCate1">
                        <input type="text" name="Order_CloseLoopCate1"  value="<?php echo isset($selectedData['CloseLoopCate1']['order'])?$selectedData['CloseLoopCate1']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_CloseLoopCate1" value="<?php echo isset($selectedData['CloseLoopCate1']['rename'])?$selectedData['CloseLoopCate1']['rename']:'CloseLoopCate1'?>"  autocomplete="off">
                        <label style="font-size: 12px;">CloseLoopCate1</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['CloseLoopCate2']['rename'])?'checked':''?> value="CloseLoopCate2">
                        <input type="text" name="Order_CloseLoopCate2"  value="<?php echo isset($selectedData['CloseLoopCate2']['order'])?$selectedData['CloseLoopCate2']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_CloseLoopCate2" value="<?php echo isset($selectedData['CloseLoopCate2']['rename'])?$selectedData['CloseLoopCate2']['rename']:'CloseLoopCate2'?>"  autocomplete="off">
                         <label style="font-size: 12px;">CloseLoopCate2</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['CloseLoopingDate']['rename'])?'checked':''?> value="CloseLoopingDate">
                        <input type="text" name="Order_CloseLoopingDate"  value="<?php echo isset($selectedData['CloseLoopingDate']['order'])?$selectedData['CloseLoopingDate']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_CloseLoopingDate" value="<?php echo isset($selectedData['CloseLoopingDate']['rename'])?$selectedData['CloseLoopingDate']['rename']:'CloseLoopingDate'?>"  autocomplete="off">
                        <label style="font-size: 12px;">CloseLoopingDate</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['FollowupDate']['rename'])?'checked':''?> value="FollowupDate">
                        <input type="text" name="Order_FollowupDate"  value="<?php echo isset($selectedData['FollowupDate']['order'])?$selectedData['FollowupDate']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_FollowupDate" value="<?php echo isset($selectedData['FollowupDate']['rename'])?$selectedData['FollowupDate']['rename']:'FollowupDate'?>"  autocomplete="off">
                        <label style="font-size: 12px;">FollowupDate</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['closelooping_remarks']['rename'])?'checked':''?> value="closelooping_remarks">
                        <input type="text" name="Order_closelooping_remarks"  value="<?php echo isset($selectedData['closelooping_remarks']['order'])?$selectedData['closelooping_remarks']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_closelooping_remarks" value="<?php echo isset($selectedData['closelooping_remarks']['rename'])?$selectedData['closelooping_remarks']['rename']:'closelooping_remarks'?>"  autocomplete="off">
                        <label style="font-size: 12px;">closelooping_remarks</label><br/>

                        <input type="checkbox" name="HeaderName[]"  <?php echo isset($selectedData['CloseLoopStatus']['rename'])?'checked':''?> value="CloseLoopStatus">
                        <input type="text" name="Order_CloseLoopStatus"  value="<?php echo isset($selectedData['CloseLoopStatus']['order'])?$selectedData['CloseLoopStatus']['order']:0?>" style="width:30px;text-align: center;" autocomplete="off">
                        <input type="text" name="Rename_CloseLoopStatus" value="<?php echo isset($selectedData['CloseLoopStatus']['rename'])?$selectedData['CloseLoopStatus']['rename']:'CloseLoopStatus'?>"  autocomplete="off">
                        <label style="font-size: 12px;">CloseLoopStatus</label><br/>
                    </div>
                    <div class="col-sm-12"><hr/></div>
                    <div class="col-sm-12">
                        <input type="submit" name="Save" class="btn btn-web pull-right" value="Submit" >
                    </div>
                </div>                  
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12"> 
                <?php if(!empty($data)){?>
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>VIEW CUSTOMIZED SHEET DETAILS</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>SHEET NAME</th>
                                    <th>ORDER</th>
                                    <th>CREATE DATE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;foreach($data as $row){?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $row['ReportTabMaster']['tab_name'];?></td>
                                        <td><?php echo $row['ReportTabMaster']['tab_order'];?></td>
                                        <td><?php echo $row['ReportTabMaster']['create_date'];?></td>
                                        <td>
                                            <a href="#" onclick="deleteData('<?php echo $this->webroot;?>CustomizedReportCreations/delete_user?id=<?php echo $row['ReportTabMaster']['id'];?>')" >
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
        </div>

        
    </div>  
</div>

