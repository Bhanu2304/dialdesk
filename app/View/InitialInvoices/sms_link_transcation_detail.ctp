<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View SMS Link Trans. Details</a></li>
    <li class="active"><a href="#">View SMS Link Trans. Details</a></li>
</ol>
<div class="page-heading">            
    <h1>View SMS Link Trans. Details</h1>
</div>

<a class="btn btn-primary btn-lg" id="show-ecr-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                        <!--
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        -->
                    </div>
                    <div class="modal-body">
                        <p id="ecr-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>


<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View SMS Link Trans. Details</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Order No.</th>
                            <th>Amount</th>
                            <th>trans_pg</th>
                            <th>trans_ag</th>
                            <th>Status</th>
                            <th>Trans. Date Time</th>
                            <th>Protocol</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ik=1;
                        //print_r($data); die;
                        foreach($transcation_data as $trans_row){?>
                        <tr>
                            <td><?php echo $ik++;?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['ord_number'];?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['amount'];?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['trans_pg'];?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['trans_ag'];?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['trans_status'];?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['trans_date_time'];?></td>
                            <td><?php echo $trans_row['Sms_link_transaction']['protocol'];?></td>
                            <!-- <td>
                                 <a href="<?php echo $this->webroot;?>AgentCreations/delete_agents?id=<?php echo $row['AgentMaster']['id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                     </a> 
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $row['AgentMaster']['id'];?>','<?php echo $row['AgentMaster']['displayname'];?>','<?php echo $row['AgentMaster']['password2'];?>')" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                            
                                
                            </td>   -->
                            
                        </tr>

                    <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>


<div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Agent</h2>      
            </div>
            <?php echo $this->Form->create('AgentCreations',array('action'=>'updateagent',"class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Display Name</label>
                                    <div class="col-sm-6">
                                        <input type="hidden"  name="id" id="Id" >
                                        <?php echo $this->Form->input('displayname',array('label'=>false,'placeholder'=>'Display Name','id'=>'displayname','class'=>'form-control','required'=>true ));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>'password','id'=>'password','class'=>'form-control','required'=>true ));?>
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>AgentCreations/updateagent','close-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>