<ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Client Activation</a></li>
        <li class="active"><a href="#">View Report Matrix</a></li>
    </ol>
    <div class="page-heading">            
        <h1>View Report Matrix</h1>
    </div>
    <div class="container-fluid">                     
	<div data-widget-group="group1">
		<div class="row">
        	<div class="col-md-12">      
                <div class="panel panel-default" id="panel-inline">
               		<div class="panel-heading">
                    	<h2>View Report Matrix</h2>
                        <a href="<?php echo $this->webroot;?>MisAndReportMatrixs"><button class="btn btn-midnightblue btn-sm" style="margin-left:5px;">+NEW</button></a>
                       	<div class="panel-ctrls"></div>
                        
                 	</div>
                    <div class="panel-body no-padding">
                 		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable12">
                        <thead>
                        	<tr>
                                <th>S.N</th>
                                <th>User Name</th>
                                <th>Designation</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Report</th>
                                <th>Report Type</th>
                                <th>Report Time</th>
                                <th>Send Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php  $i = 1;foreach($data as $row){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $row['ReportMatrixMaster']['user_name'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['user_designation'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['user_mobile'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['user_email'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['report'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['report_type'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['report_value'];?></td>
                                <td><?php echo $row['ReportMatrixMaster']['send_type'];?></td>
                                <td>
                                    <?php //echo $this->Html->link('Edit',array('controller'=>'MisAndReportMatrixs','action'=>'index','?'=>array(
                                    //'id'=>$row['ReportMatrixMaster']['id']),'full_base' => true)); ?> <!--|| -->
                                    <?php echo $this->Html->link('Delete',array('controller'=>'MisAndReportMatrixs','action'=>'delete_report_matrix','?'=>array(
                                    'id'=>$row['ReportMatrixMaster']['id']),'full_base' => true),array('onclick'=>"return confirm('Are you sure you want to delete this item?')"));?>
                                </td>    
                             </tr>
                            <?php }?>
                        </tbody>
                    </table><!--end table-->
                   	</div>
              		<div class="panel-footer"></div>
              	</div>
           	</div>
   		</div>
            
            
            
            
            
    </div>
</div> <!-- .container-fluid -->

