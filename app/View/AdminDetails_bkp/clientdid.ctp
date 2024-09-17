<script>
    function getClient(){
        $("#client_form").submit();	
    } 
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AdminDetails/clientdid">DID Creation</a></li>
</ol>
<div class="page-heading margin-top-head">            
    <h2>DID Creation</h2>
    <div >
        <?php echo $this->Form->create('AdminDetails',array('action'=>'clientdid','id'=>'client_form')); ?>
            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control client-box', 'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php if(isset($clientid) && !empty($clientid)){ ?>
<div class="container-fluid margin-top-head">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                 <h2>DID Creation</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <div class="col-md-4">
                        <?php echo $this->Form->create('AdminDetails',array('action'=>isset($didnumber['DidMaster']['id'])?'update_did':"add_did",'id'=>'client_form','data-parsley-validate'));?>
                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php echo $this->Form->input('did_number',array('label'=>false,'class'=>'form-control', 'placeholder'=>'DID Number','maxlength'=>'8','value'=>isset ($didnumber['DidMaster']['did_number']) ? $didnumber['DidMaster']['did_number'] : "","onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php echo $this->Form->input('customer_care_number',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Customer Care Number','maxlength'=>'10','value'=>isset ($didnumber['DidMaster']['customer_care_number']) ? $didnumber['DidMaster']['customer_care_number'] : "","onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="input-group">							
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span> 
                                    <?php  echo $this->Form->hidden('client_id',array('label'=>false,'value'=>isset($clientid)?$clientid:"",'required'=>true)); ?> 
                                    
                                    <?php 
                                     if(isset($didnumber) && !empty($didnumber)){
                                        echo $this->Form->hidden('id',array('label'=>false,'value'=>isset ($didnumber['DidMaster']['id']) ? $didnumber['DidMaster']['id'] : "",'required'=>true));
                                        echo $this->Form->submit('Update',array('class'=>'btn-web btn'));
                                    }
                                    else{
                                         echo $this->Form->submit('Submit',array('class'=>'btn-web btn'));
                                    }
                                    ?>    
                                </div>
                            </div>
                        <?php  echo $this->Form->end(); ?>
                    <?php }?>

                </div> 
            </div>
        </div>
        
        <?php if(isset($clientid) && !empty($clientid)){ ?>
             <?php if(isset($didnumber) && !empty($didnumber)){?>
                <div class="panel panel-default" id="panel-inline1">
                    <div class="panel-heading">
                        <h2>VIEW CLIENT DID</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th>DID NUMBER</th>
                                    <th>Customer Care No</th>
                                    <th>Create Date</th>
                                    <th>Update Date</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $didnumber['DidMaster']['did_number'];?></td>
                                    <td><?php echo $didnumber['DidMaster']['customer_care_number'];?></td>
                                    <td><?php echo $didnumber['DidMaster']['create_date'];?></td>
                                    <td><?php echo $didnumber['DidMaster']['update_date'];?></td>
                                    <td>
                                        <a href="<?php echo $this->webroot;?>AdminDetails/delete_did?id=<?php echo $didnumber['DidMaster']['id']?>&cid=<?php echo $didnumber['DidMaster']['client_id'];?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                        </a> 
                                    </td>  
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            <?php }?>

             <?php if(isset($hisdidnumber) && !empty($hisdidnumber)){?>
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>CLIENT DID HISTORY</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                   <div class="panel-body no-padding">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                            <thead>
                                <tr>
                                    <th>DID NUMBER</th>
                                    <th>Customer Care No</th>
                                    <th>Create Date</th>
                                    <th>Update Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php foreach($hisdidnumber as $row){?>
                                    <tr>
                                        <td><?php echo $row['DidHistoryMaster']['did_number'];?></td>
                                        <td><?php echo $row['DidHistoryMaster']['customer_care_number'];?></td>
                                        <td><?php echo $row['DidHistoryMaster']['create_date'];?></td>
                                        <td><?php echo $row['DidHistoryMaster']['update_date'];?></td>
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