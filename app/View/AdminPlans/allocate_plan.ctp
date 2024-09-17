
<script>    
function createClientPlan(){
	$('#clientplan_form').attr('action', '<?php echo $this->webroot;?>AdminPlans');
	$("#clientplan_form").submit();	
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
<script>
    function getPeriod(value)
    {
        
        var str='<div class="input select">';
        str += '<select name="RentalPeriod" required="" class="form-control">';
        str +='<option>Select</option>';
        if(value=='')
        {    str += '<option value="">Rental Period</option>'; }
        
        else if(value=='DAY')
        {
            for(var i=1; i<=29; i++){    str +='<option value="'+i+'">'+i+'</option>'; }
        }
        else if(value=='MONTH')
        {
            for(var i=1; i<=11; i++){    str +='<option value="'+i+'">'+i+'</option>'; }
        }
        else if(value=='YEAR')
        {
            for(var i=1; i<=2; i++)    {    str +='<option value="'+i+'">'+i+'</option>';    }
        }
        str +='</select></div>';
            document.getElementById("rentalPeriod").innerHTML=str;
    }
   
   
    function getPlanType(planid){
        $.post("<?php echo $this->webroot;?>AdminPlans/get_plan_type",{planid:planid},function(data){
            $( "#PlanType" ).val(data);
        });
   }
    
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Plan Master</a></li>
    <li class="active"><a href="#">Allocate Plan</a></li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Allocate Plan</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('AdminPlan',array('class'=>'form-horizontal')); ?>    
            <div class="form-group">
                <label class="col-sm-1 control-label">Plan</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('PlanId',array('label'=>false,'options'=>$PlanName,'empty'=>'Select PlanName','onchange'=>'getPlanType(this.value)','class'=>'form-control','required'=>true)); ?>
                </div>
                <label class="col-sm-1 control-label">Client</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$ClientName,'empty'=>'Select Client','class'=>'form-control','required'=>true)); ?> 
                    <?php echo $this->Form->input('PlanType',array('label'=>false,'type'=>'hidden','id'=>'PlanType','class'=>'form-control')); ?> 
                </div>
                <label class="col-sm-1 control-label">Start Date</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('start_date',array('label'=>false,'class'=>'form-control date-picker','placeholder'=>'Plan Start Date','required'=>true,'autocomplete'=>'off')); ?> 
                </div>
                
                <!--
                <label class="col-sm-1 control-label">Plan Type</label>
                <div class="col-sm-3">
                    <?php //echo $this->Form->input('PlanType',array('label'=>false,'type'=>'hidden','id'=>'PlanType','class'=>'form-control')); ?> 
                </div>
                -->
                
            </div>
            <div class="form-group">
            <div class="col-sm-2">
                    <input type="submit" name="submit" value="Allocate" class="btn-web btn">
                </div>
            </div>    
            <?php echo $this->Form->end(); ?>
            
        </div>
            <div class="panel-body no-padding scrolling ">
                <div>
             <table class="table table-striped table-bordered datatables"> 
                <thead>
                <tr>
                    <th style="text-align:left;">Sr. No.</th>
                    <th style="text-align:left;">Client</th>
                    <th style="text-align:left;">Campaign</th>
                    <th style="text-align:left;">Plan</th>
                    <th style="text-align:left;">Start Date</th>
                    <th style="text-align:left;">End Date</th>
                    <th style="text-align:left;">SetupCost</th>
                    <th style="text-align:left;">RentalCost</th>
                    <th style="text-align:left;">Balance</th>
                    <th style="text-align:left;">Payment Terms</th>
                    <th style="text-align:left;">IBCall</th>
                    <th style="text-align:left;">IBCallNight</th>
                    <th style="text-align:left;">OBCall</th>
                    <th style="text-align:left;">SMS</th>
                    <th style="text-align:left;">Email</th>
                    <th style="text-align:left;">MissCall</th>
                    <th style="text-align:left;">VFO</th>
                    
                    
                    
<!--                    <th>Action</th>-->
                </tr>
                </thead>
                <tbody>
                    <?php  $i=1;  foreach($plan_det as $plan) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        
                        <td><?php echo $plan['rm']['company_name']; ?></td>
                        <td><?php echo $plan['rm']['campaignid']; ?></td>
                <td><?php echo $plan['pm']['PlanName']; ?></td>
                <td><?php echo $plan['0']['start_date']; ?></td>
                <td><?php echo $plan['0']['end_date']; ?></td>
                <td style="text-align:right;"><?php echo $plan['pm']['SetupCost']; ?></td>
                <td style="text-align:right;"><?php echo $plan['pm']['RentalAmount']; ?></td>
                <td style="text-align:right;"><?php echo $plan['pm']['Balance']; ?></td>
                <td style="text-align:right;"><?php echo $plan['pm']['PeriodType']; ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['pm']['InboundCallCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['InboundCallChargeNight'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['pm']['OutboundCallCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['pm']['SMSCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['pm']['EmailCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['pm']['MissCallCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['pm']['VFOCallCharge'],2); ?></td>
                
                
                
<!--                <td><a href="edit_plan?id=<?php //echo $plan['PlanMaster']['Id']; ?>">Edit</a></td>-->
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
                    </div>
            
            </div>
        <div class="panel-footer"></div>
        </div>
    </div>
    
</div>
 
