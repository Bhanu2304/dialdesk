<?php echo $this->Html->script('assets/main/dialdesk');?>

<script>
function showPopup(id,name,number,issue_date,status,remark,concern1,exclose_date){
    $("#id").val(id);
    $("#name").val(name);
    $("#number").val(number);
    $("#issue_raised_date").val(issue_date);
    $("#status").val(status);
    $("#remarks").val(remark); 
    $("#concern1").text(concern1); 
    $("#FromDate").val(exclose_date);
  
}

function submitForm(form,path,removeid){

    var status = $("#status").val();
    
    var formData = $(form).serialize(); 
    var fdate=$("#issue_raised_date").val();
    var ldate=$("#FromDate").val();

    if((new Date(fdate).getTime()) > (new Date(ldate).getTime()))
    {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;

    }
   
    $.post(path,formData).done(function(data){

        $("#"+removeid).trigger('click');
        $("#show-ecr-message").trigger('click');
        $("#ecr-text-message").text('Ticket update successfully.');

        
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}

</script>


<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Social Media</a></li>
    <li class="active"><a href="#">Manage Bot Data</a></li>
</ol>
<div class="page-heading">                                           
<h1>Manage Bot Data</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        
        
             
            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>VIEW Bot Data</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding scrolling">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="exap">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Issue Raised Date</th>
                                <th>Status</th>
                                <th>Expected Date</th>
                                <th>Actual Date</th>
                                <th>TAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;foreach($data as $post){?>
                            <tr>
                               <?php  
                                
                                      $concern1 = str_replace("'", "",$post['HarvestChat']['issue']);
                                      $rep_concern1 = str_replace('"', "", $concern1);

                               ?>
                                <td><a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $post['HarvestChat']['id'];?>','<?php echo $post['HarvestChat']['name'];?>','<?php echo $post['HarvestChat']['wa_id'];?>','<?php echo date_format(date_create($post['HarvestChat']['created_at']),'d M Y');?>','<?php echo $post['HarvestChat']['status']; ?>','<?php echo $post['HarvestChat']['remarks']; ?>','<?php echo $rep_concern1;?>','<?php echo date_format(date_create($post['HarvestChat']['ex_close_date']),'Y-m-d'); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised"><?php echo $post['HarvestChat']['id']; ?> <div class="ripple-container"></div></label></a></td>
                                <td><?php echo $post['HarvestChat']['name']; ?></td>
                                <td><?php echo $post['HarvestChat']['wa_id']; ?></td>
                                <td><?php echo date_format(date_create($post['HarvestChat']['created_at']),'d M Y');?></td>
                                <td><?php echo $post['HarvestChat']['status']; ?></td>
                                <?php if(!empty($post['HarvestChat']['ex_close_date']))
                                {
                                    echo '<td>'.date_format(date_create($post['HarvestChat']['ex_close_date']),'d M Y').'</td>';
                                }
                                else{
                                    echo '<td></td>';
                                }?>
                                <?php if(!empty($post['HarvestChat']['ac_close_date']))
                                {
                                    echo '<td>'.date_format(date_create($post['HarvestChat']['ac_close_date']),'d M Y').'</td>';
                                }
                                else{
                                    echo '<td></td>';
                                }?>
                                <?php $actdate = date('Y-m-d',strtotime($post['HarvestChat']['ac_close_date']));
                                      $exdate = date('Y-m-d',strtotime($post['HarvestChat']['ex_close_date']));

                                      if($exdate < $actdate)
                                      {
                                        echo '<td>OUT</td>';
                                      }
                                      else if($exdate == $actdate || $exdate > $actdate)
                                      {
                                         echo '<td>IN</td>';
                                      }
                                ?>
                           
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
                        
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                       
                    </div>
                    <div class="modal-body">
                        <p id="ecr-text-message"></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Ticket</h2>      
            </div>
            <?php echo $this->Form->create('HarvestChat',array('action'=>'updateticket',"class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                             <div class="row"> 
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-6">
                                            <?php echo $this->Form->input('name',array('label'=>false,'placeholder'=>'Display Name','id'=>'name','class'=>'form-control','readonly'=>true ));?>
                                            <input type="hidden"  name="id" id="id" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Number</label>
                                        <div class="col-sm-6">
                                            
                                            <?php echo $this->Form->input('number',array('label'=>false,'placeholder'=>'Display Name','id'=>'number','class'=>'form-control','readonly'=>true ));?>
                                        </div>
                                    </div> 
                                </div>   
                             </div>
                             
                             <div class="row"> 
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Issue Raised Date</label>
                                        <div class="col-sm-6">
                                            
                                            <?php echo $this->Form->input('IssueDate',array('label'=>false,'placeholder'=>'Display Name','id'=>'issue_raised_date','class'=>'form-control','readonly'=>true));?>
                                        </div>
                                    </div>
                                </div>
                                 
                             </div> 

                             <div class="row"> 
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Status</label>
                                        <div class="col-sm-6">
                                            
                                        <?php $options = ['WIP' => 'WIP', 'Closed' => 'Closed'];?>
                                            <?php echo $this->Form->input('status',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Select','id'=>'status' ));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Remarks</label>
                                        <div class="col-sm-6">
                                        <?php echo $this->Form->input('remarks',array('label'=>false,'type'=>'textarea','rows'=>'2','class'=>'form-control','id'=>'remarks' ));?>
                                        </div>
                                    </div> 
                                </div>   
                             </div> 

                             <div class="row"> 
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Expected Closure Date</label>
                                        <div class="col-sm-6">
                                            
                                        <?php echo $this->Form->input('FromDate',array('label'=>false,'id'=>'FromDate','class'=>'form-control date-picker'));?>
                                        <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                        </div>
                                    </div>
                                </div>
                                   
                             </div>
                             <div class="row"> 
                                <div class="col-md-12">        
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                          <h4>Issue Raised</h4>
                                            <ul>
                                                <li id="concern1"></li> 
                                            </ul>  
                                        </div>
                                    </div>
                                </div>
                                   
                             </div>


                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>HarvestChat/updateticket','close-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<!-- <script>

        $(document).ready(function () {
            $('#DataTables_Table_0').DataTable({
                "ordering": false
            });
        });
</script> -->

<script>
    $(document).ready(function () {
    $('#exap').DataTable({
        destroy: true,
        searching: false,
        paging: false,
        ordering: false
    });
});
</script>