<?php echo $this->Html->script('admin_creation'); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Upload OB Base</a></li>
    <li class="active"><a href="#">Mobile Users</a></li>
</ol>
<div class="page-heading">            
    <h1>Upload OB Base</h1>
</div>
<style>
.sorting{text-transform: uppercase;}
.form-group input[type=file] {
    opacity: 1;
    position: relative;}
</style>
<script>
 function checkNumber(val,evt)
  {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    
    if (charCode> 31 && (charCode < 48 || charCode > 57) )
        {            
            return false;
        }
        if(val.length>10)
        {
            return false;
        }
    return true;
}
</script>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Upload OB Base</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('MobileUploads',array('action'=>'add','id'=>'index','method'=>'Post','enctype'=>'multipart/form-data')); ?>
                   
                        <div class="col-md-4  col-sm-offset-4">


                                <div class="col-xs-12">
                                    <div class="input-group">							
                                        <span class="input-group-addon">
                                            <i class="ti ti-user"></i>
                                        </span>
                                        <div id="erroMsg" style="color:red;font-size: 15px;text-align:center;">
                                            <?php echo $this->Session->flash();?>
					                    </div>      
                                    </div>
                                </div>
                           	
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <label>Upload CSV</label>
                                    <input type="file" id="file" name="file" placeholder='file' class="form-control" autocomplete='off'  required/>
                           
                                </div>
                            </div>
                            
                            
                        
                        </div>
                                       

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-5">
                                <div class="btn-toolbar">
                                    <input type="Submit" class="btn btn-web" value="Submit" >
                                    <img src="<?php echo $this->webroot;?>/images/ajax-loader.gif" style="width:30px;display:none;" id="ckloder" />
                                </div>
                            </div>
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
                        <h2>Upload OB Base Data</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Customer Code</th> 
                                    <th>Customer name</th> 
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
                                    <!-- <th>STATUS</th> -->
                                    <!-- <th>ACTION</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                               
                                $i = 1;foreach($data as $row){?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $row['MobileUpload']['Customer_Code'];?></td> 
                                        <td><?php echo $row['MobileUpload']['Cust_name'];?></td> 
                                        <td><?php echo $row['MobileUpload']['mobile_no1'];?></td> 
                                        <td><?php echo $row['MobileUpload']['mobile_no2'];?></td> 
                                        <td><?php echo $row['MobileUpload']['address1'];?></td> 
                                        <td><?php echo $row['MobileUpload']['address2'];?></td> 
                                        <td><?php echo $row['MobileUpload']['address3'];?></td> 
                                        <td><?php echo $row['MobileUpload']['city'];?></td> 
                                        <td><?php echo $row['MobileUpload']['pincode'];?></td> 
                                        <td><?php echo $row['MobileUpload']['email_id'];?></td> 
                                        <td><?php echo $row['MobileUpload']['salesman_code'];?></td> 
                                        <td><?php echo $row['MobileUpload']['salesman_name'];?></td> 
                                        <td><?php echo $row['MobileUpload']['district'];?></td> 
                                        <td><?php echo $row['MobileUpload']['dlno1'];?></td> 
                                        <td><?php echo $row['MobileUpload']['dlno2'];?></td> 
                                        <td><?php echo $row['MobileUpload']['dl_expiry_date'];?></td> 
                                        <td><?php echo $row['MobileUpload']['pan_no'];?></td> 
                                        <td><?php echo $row['MobileUpload']['code_created_on'];?></td> 
                                        <td><?php echo $row['MobileUpload']['customer_type'];?></td> 
                                        <td><?php echo $row['MobileUpload']['agent_code'];?></td> 
                                        <td><?php echo $row['MobileUpload']['CreateDate'];?></td> 
                                        <!-- <td>
                                            <a href="<?php echo $this->webroot;?>MobileUploads/singledata?dial=<?php echo $row['MobileUpload']['agent_code'];?>"
                                         class="btn btn-primary" style="background-color: rgba(153, 153, 153, 0.2);">Dial</a>
                                        </td> -->
                                       
                                        
                                        <!-- <td>
                                            <a  href="#" data-toggle="modal" data-target="#loginUpdate" onclick="view_edit_field('<?php echo $row['Mob']['Id'];?>')" >
                                                <label class="btn btn-xs btn-midnightblue btn-raised">
                                                    <i class="fa fa-edit"></i><div class="ripple-container"></div>
                                                </label>
                                            </a> 
                                            <a href="#" onclick="deleteData('<?php echo $this->webroot;?>MobUsers/delete_agents?Id=<?php echo $row['Mob']['Id'];?>')" >
                                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                            </a>
                                        </td>   -->
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

