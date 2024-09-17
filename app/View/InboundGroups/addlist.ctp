<?php echo $this->Html->script('admin_creation'); ?>
<script>
    function getClient(){
        $("#client_form").submit();	
    } 
    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Obd Management</a></li>
    <li class="active"><a href="#">Add List</a></li>
</ol>
<div class="page-heading">
    <h1>Add List</h1>
</div>
<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
    <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Add List</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('ObdManagement',array('action'=>'addlist','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate','enctype'=>'multipart/form-data')); ?>

                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('listid',array('label'=>false,'class'=>'form-control','onkeypress'=>'return isNumberKey(event)' ,'placeholder'=>'List Id','required'=>true)); ?>
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('Description',array('label'=>false,'type'=>'textarea','class'=>'form-control' ,'placeholder'=>'Description','required'=>true)); ?>
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <input type="submit" class="btn btn-web pull-left" value="Submit">
                        </div>
                    </div>

                    
                </div>
    
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
        
        <?php if(isset($result) && !empty($result)){ ?>
           
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW List Id</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>List Id</th>
                                    <th>Description</th>
                                    <th>Create Date</th>
                                     <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $ik='1';
                            foreach($result as $ctr) { ?>
                                <tr>
                                    <td><?= $ik++;?></td>
                                    <td><?php echo $ctr['ObdList']['list_id'];?></td>
                                    <td><?php echo $ctr['ObdList']['description'];?></td>
                                    <?php if($ctr['ObdList']['createdate'] != '') {?>
                                    <td><?php echo date_format(date_create($ctr['ObdList']['createdate']),'d M Y');?></td>
                                    <?php }else{
                                        echo '<td></td>';
                                    }?>
                                    <td> 
                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObdManagements/delete_list?id=<?php echo $ctr['ObdList']['id'];?>')" >
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            <?php }?>

    </div>
</div>