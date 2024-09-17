<script>
function viewClientAccount(){
	$('#clientplan_form').attr('action', '<?php echo $this->webroot;?>ClientAccounts/view_client_account');
	$("#clientplan_form").submit();	
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
    <li><a >Account Master</a></li>
    <li class="active"><a href="#">Account Master</a></li>
</ol>
<?php echo $this->Form->create('ClientAccounts',array('action'=>'client_wise_plan_creation','id'=>'clientplan_form')); ?>
<div class="page-heading margin-top-head">            
    <h2>Account Master</h2>
    <div >
        
     <?php  echo $this->Form->input('clientID',array('label'=>false,'options'=>$client,'value'=>$clientId,'onchange'=>'viewClientAccount();','empty'=>'Select Client','class'=>'form-control client-box')); ?>
       
    </div>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <?php if(isset($account) && !empty($account)){?>
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2 style="color:green;"><?php echo $this->Session->Flash(); ?></h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body no-padding ">
            <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                <thead>
                <tr>
                    <th>Balance</th>
                    <th>Start Date</th>
                    <th>Renewal Date</th>
                </tr>
                </thead>
                <tbody>
                <td><?php echo $account['BalanceMaster']['Balance']; ?></td>
                <td>
                    <?php if(!empty($account['BalanceMaster']['start_date']))
                    echo date_format(date_create($account['BalanceMaster']['start_date']),'d-M-Y'); ?>
                </td>
                <td><?php if(!empty($account['BalanceMaster']['end_date'])) echo date_format(date_create($account['BalanceMaster']['end_date']),'d-M-Y'); ?></td>
                </tbody>
            </table>
        </div>
        <div class="panel-footer"></div>
    </div>
    <?php }?>
     <?php if(isset($account) && empty($account)){echo "Create Plan First";}?>   
    </div>
</div>

 <?php echo $this->Form->end(); ?>
