<?php echo $this->Html->script('assets/main/dialdesk');?>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Bot Master</a></li>
    <li class="active"><a href="#">Bot Data</a></li>
</ol>
<div class="page-heading">                                           
<h1>Bot Data</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        
        
             
            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>VIEW</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding scrolling">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="exap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Number</th>
                                <th>Request</th>
                                <th>Response</th>
                                <th>Option</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data as $post){?>
                            <tr>
                              
                                <td><?php echo $post['WhatsappNonRegNumber']['id']; ?></td>
                                <td><?php echo $post['WhatsappNonRegNumber']['number']; ?></td>
                                <td><?php echo $post['WhatsappNonRegNumber']['company_name']; ?></td>
                                <td><?php echo date_format(date_create($post['WhatsappNonRegNumber']['created_at']),'d M Y');?></td>
                                <td><a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $post['WhatsappNonRegNumber']['id'];?>','<?php echo $post['WhatsappNonRegNumber']['number'];?>')" > <label class="btn btn-xs btn-midnightblue btn-raised">Update<div class="ripple-container"></div></label></a></td>
                            
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
                <h2  class="modal-title">Update Number</h2>      
            </div>
            <?php echo $this->Form->create('TicketMaster',array('action'=>'update_non_regnumber',"class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                             <div class="row"> 
                                <div class="col-md-12">        
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-6">
                                       
                                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>

                                            <input type="hidden"  name="id" id="id" >
                                            <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12"> 
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Number</label>
                                        <div class="col-sm-6">
                                            
                                            <?php echo $this->Form->input('number',array('label'=>false,'placeholder'=>'Number','id'=>'number','class'=>'form-control','readonly'=>true ));?>
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
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>TicketMaster/update_non_regnumber','close-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>


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