<?php ?>
<script> 		
function validateExport(url){
//    alert("hi");
    
 
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AgentCreations/export_summary');
        }
        else{
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AgentCreations/view_summary');
        }
        $('#validate-form').submit();
        return true;
}

</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Agent Summary Report</a></li>
    <li class="active"><a href="#">Agent Summary Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Agent Summary Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Agent Summary Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AgentCreations',array('action'=>'view_summary','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                      
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                        
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php if(!isset($data) && empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Agent Summary Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <tr>
                    <th>Category</th>
                        <?php  foreach($process as $pro)
                        {
                        echo "<th>".$pro."</th>";
                        }
                          ?>
                    <th>Total</th>
                </tr> 

                <?php 
                
                $h_total = array();
                foreach($category as $cat)
                {
    
    
                    echo "<tr>";
                    echo "<th>".$cat."</th>";
                    $grand_total = 0; foreach($process as $pro)
                    {
                    
                    echo "<td>".$dataArr[$cat][$pro]."</td>";
                    $grand_total += $dataArr[$cat][$pro];
                    $h_total[$pro] += $dataArr[$cat][$pro];
                    }
                    echo "<td>".$grand_total."</td>";
                    echo "</tr>";

                }?>
                <tr>
                    <th>Total</th>
                    <?php 
                    $grand_total = 0;
                    foreach($process as $pro)
                    {
                        echo '<th>'.$h_total[$pro].'</th>';
                        $grand_total +=$h_total[$pro];
                    }
                    echo "<th>".$grand_total."</th>";
                    ?>
                </tr>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>






          