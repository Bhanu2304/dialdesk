<script> 		
function validateExport(url){
    $(".w_msg").remove();
    var report_type=$("#report_type").val();

    
    if(url ==="download"){
        $('#validate-form').attr('action','<?php echo $this->webroot; ?>ScenarioReports/export_inbound_scenario');
    }
    if(url ==="view"){
        $('#validate-form').attr('action','<?php echo $this->webroot; ?>ScenarioReports');
    }
    $('#validate-form').submit();
    return true;
    


}
</script>
<script>
$(function () {
                $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});
</script>


<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot; ?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Inbound Scenario</a></li>
</ol>
<div class="page-heading">            
    <h1>Inbound Scenario</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Inbound Scenario Reports</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <?php echo $this->Form->create("ScenarioReports", [
                    "action" => "index",
                    "id" => "validate-form",
                    "class" => "form-horizontal row-border",
                    "data-parsley-validate",
                ]); ?>
                <div class="form-group">
                        <div class="col-sm-4">
                            <?php echo $this->Form->input("clientID", [
                                "label" => false,
                                "class" => "form-control",
                                "options" => $client,
                                "value" => isset($companyid) ? $companyid : "",
                                "empty" => "Select Client",
                                "required" => true,
                            ]); ?>
                        </div>
               
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" id="button" class="btn btn-web" value="Export" >
                        </div>

                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" id="button" class="btn btn-web" value="View" >
                        </div>

                <?php $this->Form->end(); ?>
            </div>
        </div>


        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Inbound Scenario</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body no-padding scrolling">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                <tr>
                    <th>Scenario</th>
                    <th>Sub Scenario</th>
                    <th>Sub Scenario 2</th>
                    <th>Sub Scenario 3</th>
                    <th>Sub Scenario 4</th>
                </tr>
                
                <?php foreach($data_ecr as $key=>$data){
                        $html = "<tr>";
                        $html .=  "<td>".$key."</td>";
                        if(empty($data))
                        {
                            echo $html.'</tr>';
                            continue;
                        }

                        foreach($data as $d=>$key1)
                        {   
                            
                            if(empty($key1))
                            {
                                echo $html."<td>".$d."</td>".'</tr>';
                                continue;
                            }
                            $html2 =$html.  "<td>".$d."</td>";
                            foreach($key1 as $d1=>$key2)
                            {
                                
                                if(empty($key2))
                                {
                                    echo $html2."<td>".$d1."</td>".'</tr>';
                                    continue;
                                }
                                $html3 =$html2.  "<td>".$d1."</td>";
                                foreach($key2 as $d2=>$key3)
                                {
                                    
                                    
                                    if(empty($key3))
                                    {
                                        echo $html3."<td>".$d2."</td>".'</tr>';
                                        continue;
                                    }
                                    $html4 =$html3.  "<td>".$d2."</td>";
                                    foreach($key3 as $d3=>$key4)
                                    {
                                        echo $html4. "<td>".$d3."</td></tr>";
                                    }
                                }
                            }
                        }
                        
                        echo "</tr>";

                    }?>

                
            </table>
        </div>
        <div class="panel-footer"></div>
    </div>





    </div>
</div>




