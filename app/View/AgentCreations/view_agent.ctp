<?php ?>
<script> 		
function validateExport(url){

 
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AgentCreations/export_agent');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AgentCreations/view_agent');
        }
        $('#validate-form').submit();
        return true;
  
}
</script>
<script>
function showPopup(id,name,pass,process_name,fdate,category,workmode,address,ldate,email){
    $("#Id").val(id);
    $("#displayname").val(name);
    //$("#password").val(pass);
    $("#process_name").val(process_name); 
    $("#fdate").val(fdate);   
    $("#category").val(category);
    $("#workmode").val(workmode); 
    $("#address").val(address); 
    $("#ldate").val(ldate); 
    $("#email").val(email);
    

   
}

    
function submitForm(form,path,id){
    var formData = $(form).serialize();

    var date_joining = $("#fdate").val();   
    var date_leaving = $("#ldate").val();
    // alert(date_joining); 
    // alert(date_leaving);

    if(date_leaving !=""){

        var sel_arr = date_joining.split('-');
        var new_sel_arr = sel_arr[2]+'/'+sel_arr[1]+'/'+sel_arr[0]  ;          
        
        var js_joiningeddate = new Date(new_sel_arr); 

        var sel_arr1 = date_leaving.split('-');
        var new_sel_arr1 = sel_arr1[2]+'/'+sel_arr1[1]+'/'+sel_arr1[0];          
        
        var js_leavingdate = new Date(new_sel_arr1);
    
        if(js_joiningeddate >= js_leavingdate) {
            
          $("#error1").html('<span class="w_msg err" style="color:red;">Please select valid date of leaving.</span>');
        //   alert('false');
         return false;
        }

    }
    
    $.post(path,formData).done(function(data){
        $("#"+id).trigger('click');
        $("#show-ecr-message").trigger('click');
        $("#ecr-text-message").text('Agent details update successfully.');
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}
</script>
<script>
$(function () {
                $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">View Agent</a></li>
    <li class="active"><a href="#">View Agent</a></li>
</ol>
<div class="page-heading">            
    <h1>View Agent</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>View Agent</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('Agent',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('processname',array('label'=>false,'class'=>'form-control','options'=>$client,'value'=>isset($processname)?$processname:"",'empty'=>'Select Process Name','required'=>true)); ?>
                        </div> 
                     
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');"  class="btn btn-web" value="View" >
                        </div>
                        
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web"  value="Export" >
                        </div>
                       
                  
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php  if(isset($data) && !empty($data)){  ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2> View Agent </h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Name of Agent</th>
                            <th>Login Id</th>
                            <th>Password</th>
                            <th>Process Name</th>
                            <th>Date of Joining</th>
                            <th>Category</th>
                            <th>Work Mode</th>
                            <th>Address</th>
                            <th>Date of Leaving</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            
                            <td><?php echo $row['AgentMaster']['displayname'];?></td>
                            <td><?php echo $row['AgentMaster']['username'];?></td>
                            <td><?php echo $row['AgentMaster']['password2'];?></td>
                            <td><?php echo $row['AgentMaster']['processname'];?></td>
                            <td><?php $dateofjoining_arr = explode("-",$row['AgentMaster']['dateofjoining']);
                                        $dateofjoining_rev = array_reverse($dateofjoining_arr);
                                        $dateofjoining = implode("-",$dateofjoining_rev); 
                                         echo $dateofjoining;?></td>
                            <td><?php echo $row['AgentMaster']['category'];?></td>
                            <td><?php echo $row['AgentMaster']['workmode'];?></td>
                            <td><?php echo $row['AgentMaster']['address'];?></td>
                            <td><?php $dateofleaving_arr = explode("-",$row['AgentMaster']['dateofleaving']);
                                        $dateofleaving_rev = array_reverse($dateofleaving_arr);
                                        $dateofleaving = implode("-",$dateofleaving_rev); 
                                         echo $dateofleaving;
                                         ?></td>
                            <td><?php echo $row['AgentMaster']['email'];?></td>
                            <td> <a href="<?php echo $this->webroot;?>AgentCreations/delete_agents?id=<?php echo $row['AgentMaster']['id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                </a> 
                                
     <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $row['AgentMaster']['id'];?>','<?php echo $row['AgentMaster']['displayname'];?>','<?php echo $row['AgentMaster']['password2'];?>','<?php echo $row['AgentMaster']['processname'];?>','<?php echo $dateofjoining;?>','<?php echo $row['AgentMaster']['category'];?>','<?php echo $row['AgentMaster']['workmode'];?>','<?php echo $row['AgentMaster']['address'];?>','<?php echo  $dateofleaving;?>','<?php echo $row['AgentMaster']['email'];?>')" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                            
                                
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

<div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Agent Details</h2>      
            </div>
            <?php echo $this->Form->create('AgentCreations',array('action'=>'updateagent',"class"=>"form-vertical row-border")); ?> 
                
                <div>
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                <div class="form-group">
                                    <label class="col-sm-1 control-label"> Name of Agent</label>
                                    <div class="col-sm-4">
                                        <input type="hidden"  name="id" id="Id" >
                                        <?php echo $this->Form->input('displayname',array('label'=>false,'placeholder'=>'Name of Agent','id'=>'displayname','class'=>'form-control','required'=>true , 'addslashes'=>true ));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-4">
                         <?php echo $this->Form->input('password',array('label'=>false,'placeholder'=>'password','id'=>'password','class'=>'form-control','required'=>false));?>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Process Name</label>
                                    <div class="col-sm-4">
                                    <?php //$options = ['Dialdesk' => 'Dialdesk', 'Wiom' => 'Wiom', 'Temporary' => 'Temporary'];?>
                                    <?php $options = ['Dialdesk' => 'Dialdesk','Dialdesk Support'=>'Dialdesk Support','Others'=>'Others'];?>
<?php echo $this->Form->input('processname',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Process Name','id'=>'process_name','required'=>true ));?>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Date of Joining</label>
                                    <div class="col-sm-4">
                                   
 <?php echo $this->Form->input('dateofjoining',array('label'=>false,'placeholder'=>'Date of Joining','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1'));?>
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Select Category</label>
                                    <div class="col-sm-4">
                     <?php $options = ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'];?>
     <?php echo $this->Form->input('category',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Select Category','id'=>'category','required'=>true ));?>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Work Mode</label>
                                    <div class="col-sm-4">
                                    <?php $options = ['Work From Home' => 'Work From Home', 'Work From Office' => 'Work From Office'];?>
<?php echo $this->Form->input('workmode',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Work Mode','id'=>'workmode','required'=>true ));?>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Address</label>
                                    <div class="col-sm-4">
                                   
                                        <?php echo $this->Form->input('address',array('label'=>false,'placeholder'=>'Address','id'=>'address','required'=>'false','class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Date of Leaving</label>
                                    <div class="col-sm-4">
                                   
                                    <?php echo $this->Form->input('dateofleaving',array('label'=>false,'placeholder'=>'Date of Leaving','id'=>'ldate','required'=>'true','class'=>'form-control date-picker1'));?>
                                    <div id="error1" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-1 control-label">Email</label>
                                    <div class="col-sm-4">
                                   
                                        <?php echo $this->Form->input('email',array('label'=>false,'placeholder'=>'Email','id'=>'email','required'=>'false','class'=>'form-control'));?>
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





