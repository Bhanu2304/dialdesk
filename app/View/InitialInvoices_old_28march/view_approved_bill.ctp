<?php //print_r($branch_master); ?>
<?php $this->Form->create('Add',array('controller'=>'AddInvParticular','action'=>'view')); ?>
<?php foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
	endforeach;
?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Billing Management</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Billing">View Invoice</a></li>
</ol>
<div class="page-heading">            
    <h1>View Invoice</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            
            <div class="panel-body" style="margin-top:-10px;">
	        <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
		<div class="col-md-12">
                <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Branch</span>
                                <?php $data=array(); foreach ($branch_master as $post): 
                         $data[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name']; 
                         endforeach; ?><?php unset($Addbranch); 
                         echo $this->Form->input('branch_name', array('options' => $data,'empty' => 'Select','label' => false, 'div' => false,'onChange'=>'getInvoices(this)','class'=>'form-control')); ?>
                                
                           </div>
                        </div>
                
                <?php
							$this->Js->event('change',    $this->Js->request(array('controller' => 'InitialInvoice','action'=>'get_view'),
							        array('async' => true, 'update' => '#mm')));
							 ?>

                        
                        
                    </div>
                    
                <div id="mm"></div>
                
            </div>
	</div>
    </div>
</div>



<?php $this->Form->end();?>

<script>
    function getInvoices(val)
{
	var branch_name=document.getElementById('AddBranchName').value;
	var xmlHttpReq = false;	
				if (window.XMLHttpRequest)
				{
				xmlHttpReq = new XMLHttpRequest();
				}
				else if (window.ActiveXObject)
				{
				xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlHttpReq.onreadystatechange = function()
				{
				if (xmlHttpReq.readyState == 4)
				{     //alert(xmlHttpReq.responseText);
					 document.getElementById("mm").readOnly= true;
					 	
				     document.getElementById('mm').innerHTML = xmlHttpReq.responseText;
				}
				} 
				xmlHttpReq.open('POST','get_view_approved/?branch_name='+''+branch_name,true);
                                
				xmlHttpReq.send(null);
}

</script>    