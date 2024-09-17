<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View SMS Details</a></li>
</ol>
<div class="page-heading">            
    <h1>View SMS Details</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View SMS Details</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Order No.</th>
                            <th>Category</th>
                            <th>Client Name</th>
                            <th>Client Email</th>
                            <th>Client Phone</th>
                            <th>SMS Send Date Time</th>
                            <th>Amount</th>
                            <th>Recharge Type</th>
                            <th>Status</th>
                            <th>Payment Date Time</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        //print_r($data); die;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['SendInformation']['order_no'];?></td>
                            <td><?php echo $row['SendInformation']['category'];?></td>
                            <td><?php echo $row['SendInformation']['clientName'];?></td>
                            <td><?php echo $row['SendInformation']['client_Email'];?></td>
                            <td><?php echo $row['SendInformation']['client_Phone'];?></td>
                            <td><?php echo $row['SendInformation']['sms_send_time'];?></td>
                            <td><?php echo $row['SendInformation']['amount'];?></td>
                            <td><?php echo $row['SendInformation']['recharge_type'];?></td>
                            <td><?php echo $row['SendInformation']['status'];?></td>
                            <td><?php echo $row['SendInformation']['update_date'];?></td>
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
