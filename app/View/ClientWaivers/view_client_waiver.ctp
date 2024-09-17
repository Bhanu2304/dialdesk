<script>
function viewClientWaiver()
{
	$('#view_clientplan_form').attr('action', '<?php echo $this->webroot;?>ClientWaivers/view_client_waiver');
	$("#view_clientplan_form").submit();	
}
function removeReadonly(){
	$('input').prop('readonly', false);
	$("#update_plan").show();
	$("#cancel").show();
}
function addReadonly(){
	$('input').prop('readonly', true);
	$("#update_plan").hide();
	$("#cancel").hide();
}
function checkCharacter(e,t) {
	try{
    	if (window.event) {
        	var charCode = window.event.keyCode;
     	}
        else if(e){
     		var charCode = e.which;
        }
        else{return true;}
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {         
        	return false;
        }
        return true;     
	}
    catch (err) {
    	alert(err.Description);
    }
}
</script>
<style>
.imgclass{
	width:30px;
	cursor:pointer;
}
#addnew{
	position:absolute;
	left:87%;
	top:40px;	
}
</style>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>ClientDetails">Home</a></li>
    <li><a >Balance Master</a></li>
    <li class="active"><a href="#">View Balance</a></li>
</ol>
<?php echo $this->Form->create('ClientWaivers',array('action'=>'client_wise_plan_creation','id'=>'view_clientplan_form')); ?>
<div class="page-heading margin-top-head">            
    <h2>Client</h2>
    <div>  
     <?php  echo $this->Form->input('clientID',array('label'=>false,'options'=>$client,'value'=>$clientId,'onchange'=>'viewClientWaiver();','empty'=>'Select Client','class'=>'form-control client-box')); ?>
    </div>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <h2 style="color:green;"><?php echo $this->Session->Flash(); ?></h2>
            <div class="panel-ctrls"></div>
        </div>
            <?php if(!empty($clientId)) { ?>
            <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                <thead>
                    <tr>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td><a href="add_waiver?id=<?php echo $clientId; ?>">Add Balance</a><br></td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>
           <?php if(!empty($waiver_all)){?> 
        <div class="panel-body no-padding ">
             <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                <thead>
                <tr>
                    <th>Balance Added</th>
                    <th>Balance Period</th>
                    <th>Remarks</th>
                    
                </tr>
                </thead>
                <tbody>
                    <?php  foreach($waiver_all as $waiver) { ?>
                    <tr>
                <td><?php echo $waiver['Waiver']['Balance']; ?></td>
                <td><?php $start_date =  $waiver['Waiver']['start_date'];
                          $end_date   =  $waiver['Waiver']['end_date'];
                          if(!empty($start_date) && $start_date!='0000-00-00')
                          {
                              $start_date = date('d-M-y',strtotime($start_date));
                          }
                          if(!empty($end_date) && $end_date!='0000-00-00')
                          {
                              $end_date = date('d-M-y',strtotime($end_date));
                          }
                          
                          echo "$start_date To $end_date";
                ?></td>
                <td><?php echo $waiver['Waiver']['remarks']; ?></td>
                
                </tr>
                    <?php }  ?>
                
                   
                </tbody>
            </table>
        </div>
        <div class="panel-footer"></div>
            <?php }?>
    </div>
    
    
     <?php if(isset($waiver) && empty($waiver)){echo "Create Plan First";}?>   
    </div>
</div>

 <?php echo $this->Form->end(); ?>
