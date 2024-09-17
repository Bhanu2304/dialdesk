<?php ?>
<script> 		
function validateExport(url){
    
    $(".w_msg").remove();
    
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    
    if(fdate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MobileUploads/MobileUploads_boundreport');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MobileUploads/boundreport');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Out Calling Data  Management</a></li>
    <li class="active"><a href="#">Out Calling Data Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Out Calling Data Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Out Calling Data Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('MobileUploads',array('action'=>'reports','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        
			<div class="col-sm-2">
                            	<select name="reporttype" id="reporttype" class="form-control client-box" style="width:170px;">
                    		<option value="OB Data">OB Data</option>
	                   	<option value="IB Data">IB Data</option>

                   		</select>
                        </div>
                        
			<div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php  if(isset($Data) && !empty($Data)){  ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Out Calling Data Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
<?php if($ReportType=='OB Data') { ?>
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                <!-- <table cellspacing="0" border="1"> -->
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                     
                                    <th>S.N</th>   
                                    <th>Latest</th>
                                    <th>Customer Code</th> 
                                    <th>Cust name</th> 
                                    <th>mobile no1</th> 
                                    <th>mobile no2</th> 
                                    <th>address1</th> 
                                    <th>address2</th> 
                                    <th>address3</th> 
                                    <th>city</th> 
                                    <th>pincode</th> 
                                    <th>email id</th> 
                                    <th>salesman code</th> 
                                    <th>salesman name</th> 
                                    <th>district</th> 
                                    <th>dlno1</th> 
                                    <th>dlno2</th> 
                                    <th>dl expiry date</th> 
                                    <th>pan no</th> 
                                    <th>code created on</th> 
                                    <th>customer type</th> 
                                    <th>agent code</th> 
                                    <th>CreateDate</th>
                                    <th>Category</th>
                                    <th>Mobile No.</th>
                                    <th>Dispositions</th>
                                    <th>Sub Dispositions</th>
                                    <th>Agent</th>
                                    <th>Dial Id</th>
                                    <th>Remarks</th>

                </tr>
                
            </thead>
            <tbody>

            <?php
                            
            $ik='1'; foreach ($Data as $row) { ?>
              
                    <tr>
                            <td><?php echo $ik++;?></td>
                            <td><?php echo $row['t1']['disposition'];?></td>
                            <td><?php echo $row['t1']['Customer_Code'];?></td> 
                            <td><?php echo $row['t1']['Cust_name'];?></td> 
                            <td><?php echo $row['t1']['mobile_no1'];?></td> 
                            <td><?php echo $row['t1']['mobile_no2'];?></td> 
                            <td><?php echo $row['t1']['address1'];?></td> 
                            <td><?php echo $row['t1']['address2'];?></td> 
                            <td><?php echo $row['t1']['address3'];?></td> 
                            <td><?php echo $row['t1']['city'];?></td> 
                            <td><?php echo $row['t1']['pincode'];?></td> 
                            <td><?php echo $row['t1']['email_id'];?></td> 
                            <td><?php echo $row['t1']['salesman_code'];?></td> 
                            <td><?php echo $row['t1']['salesman_name'];?></td> 
                            <td><?php echo $row['t1']['district'];?></td> 
                            <td><?php echo $row['t1']['dlno1'];?></td> 
                            <td><?php echo $row['t1']['dlno2'];?></td> 
                            <td><?php echo $row['t1']['dl_expiry_date'];?></td> 
                            <td><?php echo $row['t1']['pan_no'];?></td> 
                            <td><?php echo $row['t1']['code_created_on'];?></td> 
                            <td><?php echo $row['t1']['customer_type'];?></td> 
                            <td><?php echo $row['t1']['agent_code'];?></td> 
                            <td><?php echo $row['t1']['CreateDate'];?></td>
                            <td><?php echo $row['t2']['category'];?></td>
                            <td><?php echo $row['t2']['mobile'];?></td>
                            <td><?php echo $row['t2']['dispositions'];?></td>
                            <td><?php echo $row['t2']['sub_dispositions'];?></td>
                            <td><?php echo $row['t2']['agentid'];?></td>
                            <td><?php echo $row['t2']['dialid'];?></td>
                            <td><?php echo $row['t2']['remarks'];?></td>
                    </tr>



                <?php 
                    } 
            						
            echo '</tbody>
            </table>'; ?>
<?php } else if($ReportType=='IB Data') { ?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                <!-- <table cellspacing="0" border="1"> -->
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                     
                                    <th>S.N</th>   
                                    <th>Mobile no</th> 
                                    <th>agent code</th> 
                                    <th>Category</th>
                                    <th>Dispositions</th>
                                    <th>Sub Dispositions</th>
                                    <th>Remarks</th>

                </tr>
                
            </thead>
            <tbody>

            <?php
                            
            $ik='1'; foreach ($Data as $row) { ?>
              
                    <tr>
                            <td><?php echo $ik++;?></td>
                            
                            <td><?php echo $row['t2']['mobile'];?></td>
			    <td><?php echo $row['t2']['agentid'];?></td>
			    <td><?php echo $row['t2']['category'];?></td>
                            <td><?php echo $row['t2']['dispositions'];?></td>
                            <td><?php echo $row['t2']['sub_dispositions'];?></td>
                            <td><?php echo $row['t2']['remarks'];?></td>
                    </tr>



                <?php 
                    } 
            						
            echo '</tbody>
            </table>'; } ?>
             
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




