<script> 		
function validateExport(url){
    $(".w_msg").remove();
    var report_type=$("#report_type").val();
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    

    if(fdate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonCallReports/export_abandon_call_reports');
        }
       
        $('#validate-form').submit();
        return true;
    }


}
</script>
<script>
  $(function () {
        $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
  });
</script>
<style>
/* #button{
  display:block;
  margin:20px auto;
  padding:10px 30px;
  background-color:#eee;
  border:solid #ccc 1px;
  cursor: pointer;
} */
#overlay{	
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;  
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}

</style>
<script>  
jQuery(function($){
  $(document).ajaxSend(function() {
    $("#overlay").fadeIn(300); 
  });
		
  $('#button').click(function(){
    $.ajax({
      type: 'GET',
      success: function(data){
        console.log(data);
      }
    }).done(function() {
      setTimeout(function(){
        $("#overlay").fadeOut(300);
      },500);
    });
  });	
});
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Abandon Call Reports</a></li>
    <li class="active"><a href="#">Abandon Call Detail Analysis Reports</a></li>
</ol>
<div class="page-heading">            
    <h1>Abandon Call Detail Analysis Reports (Internal)</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Abandon Call Detail Analysis Reports (Internal)</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AbandonCallReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                <div class="form-group">
                 <div class="col-sm-2">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
               
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','autocomplete'=>'off','class'=>'form-control date-picker1'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','autocomplete'=>'off','class'=>'form-control date-picker1'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" id="button" class="btn btn-web" value="Export" >
                        </div>
<div  id="overlay" class="col-sm-2" style="margin-top:-8px;">
                        <div class="cv-spinner">
                     <span class="spinner"></span>
                        </div>
                        </div>
                      

                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>

        <?php if(isset($html) && $html !=""){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Abandon Call Reports</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <?php echo $html;?>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>

    </div>
</div>




