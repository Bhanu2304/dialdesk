
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>OutboundReports/export_reports');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>OutboundReports/index');
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
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Outbound Reports</a></li>
    <li class="active"><a href="#">Outbound Reports</a></li>
</ol>
<div class="page-heading">            
    <h1>Outbound Reports</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Outbound Reports</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('OutboundReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker1'));?>
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
        
        <?php if(isset($data) && !empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Outbound Reports</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                <tr>
                <th colspan="2">Portfolio Name</th>    
                    <th>MTD</th>  
                <?php foreach($datetime_arr as $key=>$value){  ?>
                    
                  <th><?php echo $key; ?></th>
                <?php } ?>
                </tr>
                <tr>
                <th colspan="2">Allocation</th>    
                <th><?php echo $data['Allocation'];?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo $date_arr[$time]['Allocation'];?></th>
                <?php } ?>    
                </tr>
                <tr>
                <th colspan="2">Overall Attempt</th>
                <th><?php echo $data['OAttempt'];?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo $date_arr[$time]['OAttempt'];?></th>
                <?php } ?>    
                </tr>
                <tr>
                <th colspan="2">Unique Attempt</th>  
                <th><?php echo count($data['UAttempt']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo count($date_arr[$time]['UAttempt']);?></th>
                <?php } ?>    
                </tr>
                <tr>
                <th colspan="2">Multiple </th>  
                <th><?php echo array_sum($data['MAttempt']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo array_sum($date_arr[$time]['MAttempt']);?></th>
                <?php } ?>    
                </tr>
                               
                <tr>
                <th colspan="2">Contactablity ( Unique) </th>  
                <th><?php echo count($data['UContact']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo count($date_arr[$time]['UContact']);?></th>
                <?php } ?>    
                </tr>  
                
                <tr>
                <th colspan="2">Contactablity ( overall) </th>  
                <th><?php echo array_sum($data['MContact']);?></th>    
                <?php foreach($datetime_arr as $time) { ?>    
                    <th><?php echo array_sum($date_arr[$time]['MContact']);?></th>
                <?php } ?>    
                </tr>  
                 
                
                <?php  $total_nc =array();
                $cat_arr = array();
                        foreach($category_arr as $cat=>$subcat_arr)
                        {
                            #$subcat_arr = array_unique($subcat_arr);
                            $cat_arr[$cat] = $cat;
                            if(!empty($subcat_arr))
                            {
                                $count = count($subcat_arr);
                                $rowspan = 'rowspan="'.$count.'"';
                                $cat_raw =  "<th $rowspan>".$cat.'</th>';
                                foreach($subcat_arr as $sub)
                                {
                                    echo '<tr>';
                                    
                                    
                                    echo $cat_raw;
                                    echo '<th>'.$sub.'</th>';
                                    echo '<th>'.$data[$cat][$sub].'</th>';
                                    $total_nc[$cat] +=  $data[$time][$cat][$sub];
                                    foreach($datetime_arr as $time) { 
                                      echo  '<th>'.$date_arr[$time][$cat][$sub].'</th>';
                                      $total_nc[$time][$cat] +=  $date_arr[$time][$cat][$sub];
                                     } 


                                     $cat_raw = "";
                                    echo '</tr>';                                    
                                }
                            }
                            else
                            {
                                echo '<tr>';
                                    
                                    
                                    echo "<th $rowspan>".$cat.'</th>';
                                    echo '<th>'.$sub.'</th>';
                                    echo '<th>'.$data[$cat][''].'</th>';
                                    $total_nc[$cat] +=  $data[$time][$cat][''];
                                    foreach($datetime_arr as $time) { 
                                      echo  '<th>'.$date_arr[$time][$cat][''].'</th>';
                                      $total_nc[$time][$cat] +=  $date_arr[$time][$cat][''];
                                     }                                     
                                    echo '</tr>';
                            }
                        }


                        
                            echo '<tr>';
                            echo '<th>Total NC</th>';
                            echo '<th></th>';
                            echo '<th>'.$total_nc['Non- workable NC'].'</th>';
                            foreach($datetime_arr as $time) { 
                                echo  '<th>'.$total_nc[$time]['Non- workable NC'].'</th>';
                               }   
                            echo '</tr>';
                        

                        
                            echo '<tr>';
                            echo '<th>Sucess</th>';
                            echo '<th>Sales / Interested / Confimation /paid</th>';
                            echo '<th>'.$total_nc['Sucess'].'</th>';
                            foreach($datetime_arr as $time) { 
                                echo  '<th>'.$total_nc[$time]['Sucess'].'</th>';
                               }   
                            echo '</tr>';
                        
                        
                            echo '<tr>';
                            echo '<th colspan="2">Success (%) on Base Allocation</th>';                            
                            echo '<th>'.(($total_nc['Sucess']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['Sucess']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';
                        
                        
                            echo '<tr>';
                            echo '<th colspan="2">Success (%) on contactable Allocation</th>';                            
                            echo '<th>'.(($total_nc['Sucess']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['Sucess']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';

                            echo '<tr>';
                            echo '<th colspan="2">WIP (%) on Base</th>';                            
                            echo '<th>'.(($total_nc['WIP']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['WIP']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';

                            echo '<tr>';
                            echo '<th colspan="2">Portfolio Failure (%)</th>';                            
                            echo '<th>'.(($total_nc['Portfolio Failure']*100)/$data['Allocation']).'</th>';
                            foreach($datetime_arr as $time) { 
                                echo '<th>'.(($total_nc[$time]['Portfolio Failure']*100)/$data[$time]['Allocation']).'</th>';
                               }   
                            echo '</tr>';

                            
                        

                ?>
                        
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




