<?php ?>
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    var client_id=$("#client").val();
    
    var getdate = "FromDate=" +$("#fdate").val();
        getdate +="&ToDate=" +$("#ldate").val();
        getdate +="&client=" +$("#client").val();
    
    if(client_id === ""){
    $("#error").html('<span class="w_msg err" style="color:red;">Please select Client.</span>');
    return false;
    }    
    else if(fdate === ""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate === ""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url === "download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>CorrectiveReport/export_corrective_report');
        }
        if(url === "view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>CorrectiveReport/index');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Corrective Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Corrective Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Corrective Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('CorrectiveReport',array('action'=>'view','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                    <div class="col-sm-2">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                       
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                     
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
      
        <?php if(isset($dataArr) && !empty($dataArr)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW REPORT</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                

                        <tr style="background-color:DarkGray;">
                            <th rowspan="2">Site</th>			
                            <th rowspan="2">Category</th>
                            <th rowspan="2">Total Corrections</th>
                            <th colspan="2" style="text-align: center;">Status</th>
                            <th rowspan="2">Remarks</th>   
                        </tr>
	
                    
                    <tr style="background-color:DarkGray;">
                            
                            <th>Open</th>
                            <th>close</th>
                              
                    </tr>
                           <?php 
                           $grand_total_corr = 0;
                           foreach($dataArr as $key=>$value){  ?>
                            
                                
                                <?php $a=1;$total_corr=0;$total_open= 0;$total_close= 0; $col2_keys = array_keys($value);?>
                                <?php foreach($col2_keys as $key2){  ?>
                                    <tr>
                                    <?php if($a==1) { ?>
                                    <th rowspan="<?php echo count($value); ?>"><?php echo $key; ?></th>
                                    <?php $a=0; } ?>
                                    <th><?php echo $key2; ?></th>
                                    <td><?php $complaint = $value[$key2]['open']+$value[$key2]['close']; echo $complaint;?></td>
                                    <td><?php echo $value[$key2]['open']; ?></td>
                                    <td><?php echo $value[$key2]['close']; ?></td>
                                    <td><?php //echo wordwrap($value[$key2]['data']['Field21'],25,"<br>\n"); ?></td>
                                    
                                    </tr>
                                    <?php $total_open+=$value[$key2]['open'];
                                          $total_close+=$value[$key2]['close'];
                                          $total_corr += $complaint;
                                      }?>  
                                    
                                    <tr>
                                            <?php $phase_total = $total_close/$total_corr; ?>
                                            <th colspan="2">Total</th>
                                            <th><?php echo $total_corr; ?></th>
                                            <th><?php echo $total_open;?></th>
                                            <th><?php echo $total_close; ?></th>
                                            <th><?php echo number_format($phase_total,2)?></th>
                                            
                                    </tr>
                            
                                <?php $grand_total_corr += $total_corr;
                                      $grand_total_open += $total_open;
                                      $grand_total_close += $total_close;
                                      }?>    
                                    <tr>
                                            <th style="background-color:yellow;" colspan="2">Grand Total</th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_corr; ?></th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_open; ?></th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_close; ?></th>
                                            <th style="background-color:yellow;"><?php $totalarr = $grand_total_close/$grand_total_corr; echo number_format($totalarr,2) ; ?></th>
                                            
                                    </tr>
                        								
                 
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>

    </div>
</div>




