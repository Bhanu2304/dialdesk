<?php echo $this->Html->script('assets/main/dialdesk');?>
<style>
    .training-des{
        margin-left: 16px;
        margin-top: -21px;
        width: 260px;
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
    var maxField = 10;
    var addButton = $('.add_button');
    var wrapper = $('.field_wrapper');
    var fieldHTML = '<div><input accept="image/*,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf,application/msword" type="file" name="training[]" required="required" ><a href="javascript:void(0);" class="remove_button" title="Remove field"> <label  class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-minus"></i></label> </a><input type="text" name="description[]" class="form-control " style="width: 260px;margin-top:8px;" placeholder="Description" required ></div>'; //New input field html 
    var x = 1;
    $(addButton).click(function(){
        if(x < maxField){
                x++;
                $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
});
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Training Docs</a></li>
</ol>
<div class="page-heading">                                           
<h1>Manage Training Docs</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage Training Docs</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body"> 
                <div class="col-md-6">
                    <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                        <div class="panel-heading">
                            <h2>Manage Training Docs</h2>
                            <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>

                            </div>
                        </div>
                        <div class="panel-body">
                            <?php echo $this->Form->create('TrainingMaster',array('action'=>'update','enctype'=>'multipart/form-data','class'=>"form-horizontal row-border")); ?>
                            <div class="field_wrapper">
                                <div>
                                    <input  accept="image/*,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf,application/msword"   type="file" name="training[]" required  >
                                   
                                    <a href="javascript:void(0);" class="add_button" title="Add field">
                                        <label  class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-plus"></i></label>
                                    </a>
                                    <input type="text" name="description[]" placeholder="Description" class="form-control training-des" required ><br/>
                                </div>
                            </div>
                            
                            <div style="margin-top:15px;">Note - (Upload only image,excel,msword,pdf)</div>
                            <div class="green"><?php echo $this->Session->flash(); ?></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-web" >UPLOAD</button>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
              <?php if(!empty($data)){?>
            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>VIEW TRAINING FILE</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding scrolling">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>DATE</th>
                                <?php for($m=1; $m<=10; $m++){?>
                                        <th>FILE <?php echo  $m;?></th>
                                        <th>DESCRIPTION</th>
                                <?php }?>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;foreach($data as $post){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo date_format(date_create($post['TrainingMaster']['createdate']),'d M Y');?></td>
                                <?php  
                                    for($n=1; $n<=10; $n++){
                                        $extension = explode('.',$post['TrainingMaster']['Field'.$n]);
                                        $extension = $extension[count($extension)-1]; 
                                        if(in_array($extension,array('jpg','jpeg','gif','png'))){
                                            $url=$this->webroot.'upload/training_file/client_'.$post['TrainingMaster']['ClientId'].'/'.$post['TrainingMaster']['Field'.$n];
                                            $tag="<a href='$url' target='_blank' >".$post['TrainingMaster']['Field'.$n]."</a>";
                                        }
                                        else{
                                            $url=$this->webroot.'TrainingMasters/download_training_file?file='.$post['TrainingMaster']['Field'.$n];
                                            $tag="<a href='$url'>".$post['TrainingMaster']['Field'.$n]."</a>";
                                        }
                                        ?>
                                        <td><?php echo $tag;?></td>
                                        <td><?php echo $post['TrainingMaster']['Des'.$n]?></td>                                      
                                    <?php }?>
                                <td>              
                                    <a href="#" onclick="deleteData('<?php echo $this->webroot;?>TrainingMasters/delete_training_file?id=<?php echo $post['TrainingMaster']['id'];?>')" >
                                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                    </a>
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