<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AdminDetails/view_client">View Client</a></li>
</ol>
<div class="page-heading">            
    <h1>View Client</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Client</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>SRN.</th>
                            <th>COMPANY NAME</th>
                            <th>CLIENT NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $val='no'; $i=1;?>
                    <?php foreach($clint_record as $row){?>
                  	<tr>
                    	<td ><?php echo $i;?></td>
                      	<td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                      	<td><?php echo $row['RegistrationMaster']['auth_person'];?></td>
                      	<td><?php echo $row['RegistrationMaster']['email'];?></td>
                     	<td><?php echo $row['RegistrationMaster']['phone_no'];?></td>
                        <td><?php if($row['RegistrationMaster']['status'] =="D"){echo "<font color='#FF0000'>Pending</font>";} else if($row['RegistrationMaster']['status'] =="H"){echo "<font color='#FF0000'>Hold</font>";}else if($row['RegistrationMaster']['status'] =="CL"){echo "<font color='#FF0000'>Close</font>";} else{echo "<font color='#009900'>Active</font>";}?></td>
                        <td>
                            <!--
                            <a href="<?php echo $this->webroot;?>AdminDetails/client_details?id=<?php echo $row['RegistrationMaster']['company_id'];?>&type=view">
                                <label class="btn btn-xs btn-midnightblue btn-raised"><i title="View" class="fa fa-file-text-o"></i><div class="ripple-container"></div></label>
                            </a>
                            -->
                            <a href="<?php echo $this->webroot;?>AdminDetails/client_details?id=<?php echo $row['RegistrationMaster']['company_id'];?>&type=edit">
                                <label class="btn btn-xs btn-midnightblue btn-raised"><i title="Edit" class="fa fa-edit"></i><div class="ripple-container"></div></label>
                            </a>

                            <a href="<?php echo $this->webroot;?>AdminDetails/benchmarking?id=<?php echo $row['RegistrationMaster']['company_id'];?>&type=edit">
                                <label class="btn btn-xs btn-midnightblue btn-raised"><i title="Edit" class="fa fa-list-ul"></i><div class="ripple-container"></div></label>
                            </a>
			</td>  
                    </tr>
                    <?php $i++;}?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>




