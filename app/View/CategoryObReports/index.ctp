<?php ?>
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
    var scenario=$("#scenario").val();

    if(scenario ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please Select Scenario.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>CategoryObReports/export_ob_report');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>CategoryObReports');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Category Ob Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Category Ob Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Category Ob Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('CategoryObReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('Category',array('label'=>false,'placeholder'=>'Select Scenario','options'=>array('Category1'=>'Scenario','Category2'=>'Sub-Scenario'),'class'=>'form-control','id'=>'scenario','empty'=>'Select Scenario'));?>
                        </div>

                        <div class="col-sm-4" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>

                        <!-- <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div> -->
    
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div> 
        
        <?php if(isset($calldate_Arr) && !empty($calldate_Arr) && $category=='Category1'){?>

        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Category Ob Scenario Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>Scenario</th>			
                            <th>MTD</th>
                        <?php foreach($calldate_Arr as $calldate) {

                            echo "<th>DAY ".date('d',strtotime($calldate))."</th>"; 
                          
                        }?>

                        </tr>
                        <?php   
                                foreach($parent_Arr as $parentName=>$pcount)
                                {
                                    echo '<tr>';
                                        echo '<td>';
                                        echo $parentName;
                                        echo '</td>';
                                        echo '<td>';
                                        echo $pcount;
                                        echo '</td>';

                                        foreach($calldate_Arr as $calldate) {
                                            echo '<td>';
                                                echo $parent_data_Arr[$calldate][$parentName];
                                            echo '</td>';
                                        }

                                    echo '</tr>';
                                }
                                echo '<tr>';
                                    echo '<td>Grand Total</td>';
                                    echo '<td>'.array_sum($parent_Arr).'</td>';
                                    foreach($calldate_Arr as $calldate) {
                                        echo '<td>';
                                            echo array_sum($parent_data_Arr[$calldate]);
                                        echo '</td>';
                                    }
                                echo '</tr>';
                        ?>  
                        
                    </thead>
                        
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>

        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Category Ob Scenario Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>Scenario</th>			
                            <th>MTD</th>
                        <?php foreach($calldate_Arr as $calldate) {

                            echo "<th>DAY ".date('d',strtotime($calldate))."</th>"; 
                          
                        }
                        echo "<th>Contribution%</th>"; 
                        ?>

                        </tr>
                        <?php   $mtdtotal = array_sum($parent_Arr);
                                foreach($parent_Arr as $parentName=>$pcount)
                                {
                                    echo '<tr>';
                                        echo '<td>';
                                        echo $parentName;
                                        echo '</td>';
                                        echo '<td>';
                                        echo $pcount;
                                        echo '</td>';

                                        foreach($calldate_Arr as $calldate) {
                                            echo '<td>';
                                                echo $parent_data_Arr[$calldate][$parentName];
                                            echo '</td>';
                                           
                                        }
                                        echo '<td>';
                                        //echo $pcount;
                                        echo round($pcount*100/$mtdtotal).'%';
                                        echo '</td>';
                                        
                                         
                                    echo '</tr>';
                                }
                                echo '<tr>';
                                    echo '<td>Grand Total</td>';
                                    //$mtdtotal = array_sum($parent_Arr);
                                    echo '<td>'.array_sum($parent_Arr).'</td>';
                                        foreach($calldate_Arr as $calldate) {
                                            echo '<td>';
                                            //$gtotal = array_sum($parent_data_Arr[$calldate]);

                                                echo array_sum($parent_data_Arr[$calldate]);
                                            echo '</td>';
                                        }
                                echo '</tr>';
                        ?>  
                        
                    </thead>
                        
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>

        <?php }?>


        <?php if(isset($calldate_Arr) && !empty($calldate_Arr) && $category=='Category2'){?>

        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Category Ob Sub-Scenario Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>Sub-Scenario</th>			
                            <th>MTD</th>
                            <?php foreach($calldate_Arr as $calldate) {
                                echo "<th>DAY ".date('d',strtotime($calldate))."</th>"; 
                            }?>

                        </tr>
                        
                    </thead>
                        <tbody>
                        <?php   
                                foreach($parent_Arr as $parentName=>$pcount)
                                {
                                    
                                    echo '<tr style="background-color:yellow;">';
                                        echo '<td>';
                                        echo $parentName;
                                        echo '</td>';
                                        echo '<td>';
                                        echo $pcount;
                                        echo '</td>';

                                        foreach($calldate_Arr as $calldate) {
                                            echo '<td>';
                                                echo $parent_data_Arr[$calldate][$parentName];
                                            echo '</td>';
                                        }

                                    echo '</tr>';

                                    foreach($child_Arr[$parentName] as $childName=>$ccount)
                                    {
                                        
                                        echo '<tr >';
                                            echo '<td>';
                                            echo $childName;
                                            echo '</td>';
                                            echo '<td>';
                                            echo $ccount;
                                            echo '</td>';

                                            foreach($calldate_Arr as $calldate) {
                                                echo '<td>';
                                                    echo $child_data_Arr[$calldate][$parentName][$childName];
                                                echo '</td>';
                                            }

                                        echo '</tr>';

                                            

                                    }
                                        

                                }
                                echo '<tr>';
                                    echo '<td>Grand Total</td>';
                                    echo '<td>'.array_sum($parent_Arr).'</td>';
                                    foreach($calldate_Arr as $calldate) {
                                        echo '<td>';
                                            echo array_sum($parent_data_Arr[$calldate]);
                                        echo '</td>';
                                    }
                                echo '</tr>';
                        ?> 
                        </tbody>
                </table>              
            </div>

           
            <div class="panel-footer"></div>
        </div>

        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Category Ob Sub-Scenario Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>Sub-Scenario</th>			
                            <th>MTD</th>
                            <?php foreach($calldate_Arr as $calldate) {
                                echo "<th>DAY ".date('d',strtotime($calldate))."</th>"; 
                            }
                            echo "<th>Contribution%</th>"; 
                            ?>

                        </tr>
                        
                    </thead>
                        <tbody>
                        <?php  $mtdtotal = array_sum($parent_Arr); 
                                foreach($parent_Arr as $parentName=>$pcount)
                                {
                                    
                                    echo '<tr style="background-color:yellow;">';
                                        echo '<td>';
                                        echo $parentName;
                                        echo '</td>';
                                        echo '<td>';
                                        echo $pcount;
                                        echo '</td>';

                                        foreach($calldate_Arr as $calldate) {
                                            echo '<td>';
                                                echo $parent_data_Arr[$calldate][$parentName];
                                            echo '</td>';
                                        }
                                        echo '<td>';
                                        echo round($pcount*100/$mtdtotal).'%';
                                        echo '</td>';

                                    echo '</tr>';

                                    foreach($child_Arr[$parentName] as $childName=>$ccount)
                                    {
                                        
                                        echo '<tr>';
                                            echo '<td>';
                                            echo $childName;
                                            echo '</td>';
                                            echo '<td>';
                                            echo $ccount;
                                            echo '</td>';

                                            foreach($calldate_Arr as $calldate) {
                                                echo '<td>';
                                                    echo $child_data_Arr[$calldate][$parentName][$childName];
                                                echo '</td>';
                                            }
                                            echo '<td>';
                                               echo round($ccount*100/$mtdtotal).'%';
                                            echo '</td>';

                                        echo '</tr>';

                                            

                                    }
                                        

                                }
                                echo '<tr>';
                                    echo '<td>Grand Total</td>';
                                    echo '<td>'.array_sum($parent_Arr).'</td>';
                                    foreach($calldate_Arr as $calldate) {
                                        echo '<td>';
                                            echo array_sum($parent_data_Arr[$calldate]);
                                        echo '</td>';
                                    }
                                echo '</tr>';
                        ?> 
                        </tbody>
                </table>              
            </div>

           
            <div class="panel-footer"></div>
        </div>

        <?php }?>
      

    </div>
</div>




