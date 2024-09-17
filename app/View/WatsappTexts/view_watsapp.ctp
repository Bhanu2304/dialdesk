<script>
function showPopup(id,aban,orde){ alert(aban);
    $("#Id").val(id);
    $("#AbandonText").val(aban);
    $("#OrderText").val(orde);  
}
    
function submitForm(form,path,id){
    var formData = $(form).serialize(); 
    $.post(path,formData).done(function(data){
        $("#"+id).trigger('click');
        $("#show-ecr-message").trigger('click');
        $("#ecr-text-message").text('Watsapp text updated successfully.');
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View Watsapp</a></li>
    <li class="active"><a href="#">View Watsapp</a></li>
</ol>
<div class="page-heading">            
    <h1>View Watsapp</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Watsapp</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Client Name</th>
                            <th>Abandon Text</th>
                            <th>Order Text</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['Watsapp']['ClientId'];?></td>
                            <td><?php echo $row['Watsapp']['AbandonText'];?></td>
                            <td><?php echo $row['Watsapp']['OrderText'];?></td>
                            <td> 
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo $row['Watsapp']['Id'];?>','<?php echo $row['Watsapp']['AbandonText'];?>','<?php echo $row['Watsapp']['OrderText'];?>')" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                            
                                
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
                <h2  class="modal-title">Update TeXt</h2>      
            </div>
            <?php echo $this->Form->create('WatsappTexts',array('action'=>'updateagent',"class"=>"form-horizontal row-border")); ?> 
                
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                <div class="form-group">
                                    
                                    <div class="col-sm-6">
                                        <input type="hidden"  name="id" id="Id" >
                                         <?php echo $this->Form->input('AbandonText',array('type' => 'textarea','id'=>'AbandonText','label'=>false,'placeholder'=>'Abandon Text','class'=>'form-control','required'=>true ));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-6">
                                         <?php echo $this->Form->input('OrderText',array('type' => 'textarea','id'=>'OrderText','label'=>false,'placeholder'=>'Order Text','class'=>'form-control','required'=>true, 'cols' => '30' ));?>
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>WatsappTexts/updateagent','close-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>