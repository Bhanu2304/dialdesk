<script>

$(document).ready(function () {

	var date_data = "<?php if((isset($data['RegistrationMaster']['deactive_date'])) && strtotime($data['RegistrationMaster']['deactive_date']) != 0){ echo date("d-m-Y", strtotime($data['RegistrationMaster']['deactive_date']));};?>";
var data = '<span style="color: #616161;">Deactivation Date<font>*</font></span><input id="deactive_date" type="text" placeholder="Deactivation Date" name="data[deactive_date]" class="form-control date-picker1" value="'+date_data+'">';
	if($('input[name="data[permission]"]:checked').val() !="A")
	{
	   $("#deactive_date_input").html(data);
	}

});



var req="This field is required";
var pho="This field is required";
var ema="This field is required";
	


function show_error(data_id,msg){
	$("#"+data_id).focus();
	$("#"+data_id).after("<br/ id='space'><span id='err' style='color:red;'>"+msg+"</span>");
}

function getCity(path,state,city){
	var state=$("#"+state).val();
	$.ajax({
		type:'post',
		url:path,
		data:{id:state},
		success:function(data){
			$("#"+city).html(data);
		}
	});
}

function checkCharacter(e,t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;
               
            }
            catch (err) {
                alert(err.Description);
            }
}

function getDeactive(value)
{
	// alert(value);
	// return false;
	if(value == 'A')
	{
		$("#deactive_date_input").hide();
		$('#deactive_date').val('');
	}else{
		$("#deactive_date_input").show();
		var date_data = "<?php if((isset($data['RegistrationMaster']['deactive_date'])) && strtotime($data['RegistrationMaster']['deactive_date']) != 0){ echo date("d-m-Y", strtotime($data['RegistrationMaster']['deactive_date']));};?>";

		var data = '<span style="color: #616161;">Deactivation Date<font>*</font></span><input id="deactive_date" type="text" placeholder="Deactivation Date" name="data[deactive_date]" class="form-control date-picker1"   value="'+date_data+'" >';

		$(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});

		$("#deactive_date_input").html(data);

		$(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});

	}

	
}

$(function () {
    $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});

</script>
<style>
.title{color: #903}
.imgset{width:100px;height:100px;cursor:pointer;margin-left:35px;}

</style>

<link rel="stylesheet" type="text/css" media="screen" href="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" />
<style type="text/css">
    a.fancybox img {
        border: none;
        box-shadow: 0 1px 7px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }

	.form-group select {
    -webkit-appearance: auto;
    -moz-appearance: auto;
    appearance: auto;
}
</style>

<?php 
if(isset($type) && $type=="edit"){$readonly=false;}else{$readonly=true;}

if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status']=="A" || $data['RegistrationMaster']['status']=="D" || $data['RegistrationMaster']['status']=="H" || $data['RegistrationMaster']['status']=="CL"){
    $readonly1=true;
}
else{
  $readonly1=false;  
}

?>
<style>
font{color:#F00; font-size:14px;}
</style>

<script>
	function viewClient(){
        $("#view_client_form").submit();	
    } 
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="#">Client Details</a></li>
</ol>
<div class="page-heading">            
    <h1>Benchmarking</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            
            <div class="panel-body">
				<?php echo $this->Form->create('Home',array('url'=>'benchmarking','id'=>'view_client_form')); ?>
					<div class="col-sm-3">
						<?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control', 'onchange'=>'viewClient();','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
					</div>
					
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<?php if(!empty($companyid)){ ?>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            
            <div class="panel-body">

                <form action="<?php echo $this->webroot;?>AdminDetails/update_benchmarking" method="post"  accept-charset="utf-8" id="client_form" enctype="multipart/form-data">
				<?php $Benchmark=array("SKILL Against Forecast","Benchmark Vs Actual","Inbound Calls","AL - 95%","SL - 10 sec - 90%","SL - 20 sec - 80%","RL - 98%","CB - within 15 mins","CBSL - 90% CBs to abandoned in 15 mins threshold","Primary LOB","AHT","FTR - 75%","Quality Score - INBOUND","Primary LOB performance","Quality Score - OUTBOUND","Ticket Closure TAT","Login Time","Ready to Take Call time","No of  calls","ACHT","TAlk Time","Utilization","Primary LOV") ; ?>
				<span class="title">Benchmark Details-</span>
				<input type="hidden" name="client_id" id="client_id" value="<?php echo $companyid; ?>">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
					<?php foreach ($Benchmark as $particular) {
					for ($i = 1; $i <= 24; $i++) {?>
						<tr>
							<td>
								<span style="color: #616161;"><?php echo $particular;?></span>
								<?php echo $this->Form->input("Benchmark.${particular}.${i}.particular",array('label'=>false,'class'=>'form-control','type'=>'hidden','value'=>$particular));?>
							</td>
							<td>
								<span style="color: #616161;">Time</span> 
								<?php echo $this->Form->input("Benchmark.${particular}.${i}.time",array('label'=>false,'class'=>'form-control','maxlength'=>'255','value'=>$i));?> 
							</td> 
							<td>
								<?php $figures = ['Number' => 'Number', 'Percent' => 'Percent'];?>
								<span style="color: #616161;">Figures in</span>    
								<?php echo $this->Form->input("Benchmark.${particular}.${i}.figure_in",array('label'=>false,'class'=>'form-control','maxlength'=>'255','empty'=>'Select','options'=>$figures,'value'=>''));?> 
							</td>
							<td>
								<span style="color: #616161;">Figures</span>    
								<?php echo $this->Form->input("Benchmark.${particular}.${i}.figure",array('label'=>false,'class'=>'form-control','maxlength'=>'255','placeholder'=>'Figures','value'=>''));?> 
							</td>
							<td>
								<span style="color: #616161;">Variance</span>  
								<?php echo $this->Form->input("Benchmark.${particular}.${i}.varian ce",array('label'=>false,'class'=>'form-control','maxlength'=>'255','placeholder'=>'Variance','value'=>''));?> 
							</td>
						</tr>
						
					<?php } 
					} ?>
				
				</table>

           		
                       
                <input onclick="goBack()" type="button" value="Back" class="btn-web btn"  />
                <input type="submit" value="Update" class="btn-web btn"  />
               
                
				</form>
                
            </div>
        </div>
    </div>
</div>
<?php } ?>







