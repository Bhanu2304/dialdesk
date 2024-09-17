<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AdminDetails/view_client_request">View Client Request</a></li>
</ol>
<div class="page-heading">            
    <h1>View Client Request</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Client Request</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>COMPANY NAME</th>
                            <th>CLIENT NAME</th>
                            <th>REQUEST TYPE</th>
                            <th>REQUEST STATUS</th>
                            <th>REQUEST DATA</th>
                            <th>REQUEST DATE</th>
                            <th>RESPONSE DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $val='no';?>
                    <?php foreach($client_request as $row){?>
                  	<tr>
                    	<td ><?php echo $row['ClientRequestMaster']['client_id'];?></td>
                      	<td><?php echo $row['registration_master']['company_name'];?></td>
                      	<td><?php echo $row['registration_master']['auth_person'];?></td>
                      	<td>
                            <?php 
                                if($row['ClientRequestMaster']['request_type'] ==="email"){echo "Change Email Id";}
                                else if($row['ClientRequestMaster']['request_type'] ==="phone"){echo "Change Phone No";}
                            ?>
                        </td>
                     	<td style="text-align: center;"><?php if($row['ClientRequestMaster']['request_status'] =="P"){echo "<font color='#FF0000'>PENDING</font>";}else if($row['ClientRequestMaster']['request_status'] =="N"){echo "<font color='#009900'>NOT PENDING</font>";}?></td>
                        <td><?php echo $row['ClientRequestMaster']['request_data'];?></td>
                        <td><?php echo $row['ClientRequestMaster']['request_date'];?></td>
                        <td><?php echo $row['ClientRequestMaster']['response_date'];?></td>
                        <td><?php echo $this->Html->link('Update',array('controller'=>'AdminDetails','action'=>'update_request','?'=>array('id'=>$row['ClientRequestMaster']['id']),'full_base' => true));?></td>
                    </tr>
                    <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>


