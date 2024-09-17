<script>
function showPopup(id,name,pass,process_name,fdate,category,workmode){
    $("#Id").val(id);
    $("#displayname").val(name);
//    $("#password").val(pass);
    $("#process_name").val(process_name); 
    $("#fdate").val(fdate);   
    $("#category").val(category);
    $("#workmode").val(workmode);    

   
}
    
function submitForm(form,path,id){
    var formData = $(form).serialize(); 
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
                $(".date-picker1").on('change',function () {
                    //var date = Date.parse($(this).val());
                    var sel_date = document.getElementById('fdate').value;
                    var sel_arr = sel_date.split('-');
                    var new_sel_arr = sel_arr[2]+'/'+sel_arr[1]+'/'+sel_arr[0]; 

                    var js_selecteddate = new Date(new_sel_arr);   

                    //alert(js_selecteddate);
                    var date = new Date();

                    if (js_selecteddate > date) {
                        alert('Selected date must be lower than today date');
                        $(this).val('');
                    }
                });
            });
          </script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View Agent</a></li>
    <li class="active"><a href="#">View Agent</a></li>
</ol>
<div class="page-heading">            
    <h1>View Agent</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Agent</h2>
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
                            <td> <a href="<?php echo $this->webroot;?>AgentCreations/delete_agents?id=<?php echo $row['AgentMaster']['id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                </a> 
     <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $row['AgentMaster']['id'];?>','<?php echo $row['AgentMaster']['displayname'];?>','<?php echo $row['AgentMaster']['password2'];?>','<?php echo $row['AgentMaster']['processname'];?>','<?php echo $dateofjoining;?>','<?php echo $row['AgentMaster']['category'];?>','<?php echo $row['AgentMaster']['workmode'];?>')" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                            
                                
                            </td>  
                        </tr>

                    <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
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
                                    <?php $options = ['Dialdesk' => 'Dialdesk', 'Wiom' => 'Wiom','Temporary' => 'Temporary'];?>
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
