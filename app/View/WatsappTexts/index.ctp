<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Social Media</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>WatsappTexts">Text Creation</a></li>
</ol>
<div class="page-heading">            
    <h1>Text Creation</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Create Text</h2>
            </div>
            <div class="panel-body">
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('WatsappTexts',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>

                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('ClientId',array('label'=>false,'options'=>$company,'empty'=>'Select Client','class'=>'form-control','required'=>false ));?>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group" style="padding-bottom: 15px;">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            Please Enter Message to be sent for Abandon Cart
                            <?php echo $this->Form->input('AbandonText',array('type' => 'textarea','label'=>false,'placeholder'=>'Abandon Text','class'=>'form-control','required'=>true ));?>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            Please Enter Message to be sent for Order Confirmation
                            <?php echo $this->Form->input('OrderText',array('type' => 'textarea','label'=>false,'placeholder'=>'Order Text','class'=>'form-control','required'=>true, 'cols' => '30' ));?>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user" style="font-size: 18px;">Please Copy and Paste Data Elements from below</i>
                            </span> 
                            
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user" style="font-size: 18px;">:ShipingPhone: <br><br><br> :BillingName: <br><br><br> :Email: <br><br><br> :ProductName:</i>
                            </span> 
                            
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-12" style="margin-top:10px;">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input type="submit" class="btn btn-web pull-left" value="Submit" >
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
    </div>
</div>
