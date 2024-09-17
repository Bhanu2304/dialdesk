<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AddLists/addlist">List Creation</a></li>
</ol>
<div class="page-heading margin-top-head">            
    <h2>List Creation</h2>
    <div >
        <?php echo $this->Form->create('AddLists',array('action'=>'addlist','id'=>'client_form')); ?>
            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php if(isset($clientid) && !empty($clientid)){ ?>
<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>List Creation</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <div class="col-md-4">
                        <?php echo $this->Form->create('AddLists',array('action'=>"add_list_id",'id'=>'client_form','data-parsley-validate'));?>
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php echo $this->Form->input('listid',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Enter List Id',"onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php  
                                    echo $this->Form->hidden('client_id',array('label'=>false,'value'=>isset($clientid)?$clientid:"",'required'=>true));
                                    echo $this->Form->submit('Submit',array('class'=>'btn-web btn'));
                                    ?>    
                                </div>
                            </div>
                        <?php  echo $this->Form->end(); ?>
                    <?php }?>

                </div> 
            </div>
        </div>
        
        <?php if(isset($clientid) && !empty($clientid)){ ?>
             <?php if(isset($listid) && !empty($listid)){?>
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>CLIENT DID HISTORY</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                   <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>SrNo</th>
                                    <th>List Id</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php $i=1; foreach($listid as $row){?>
                                    <tr>
                                        <td><?php echo $i++;?></td>
                                        <td><?php echo $row['ListMaster']['list_id'];?></td>
                                        <td>
                                        <a href="<?php echo $this->webroot;?>AddLists/delete_list_id?id=<?php echo $row['ListMaster']['Id'];?>&cid=<?php echo $row['ListMaster']['client_id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
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
 <?php }?>
