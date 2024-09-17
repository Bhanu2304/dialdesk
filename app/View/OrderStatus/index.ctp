<script>
    function validateExport(url){

        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>OrderStatus/export_data');
        }
        
        $('#validate-form').submit();
        return true;
    }
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">OBD Management</a></li>
    <li class="active"><a href="#">Order Status</a></li>
</ol>
<div class="page-heading">                                           
<h1>Order Status</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		
        <div class="form-group">
            <div class="col-sm-4"><h2>View Order Status</h2></div>
            <!-- <div class="col-sm-4"></div> -->
            <?php echo $this->Form->create('OrderStatus',array('id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
            <div class="col-sm-8">
                <input type="button" onclick="validateExport('download');" class="btn btn-web pull-right" value="Export" >
            </div>
            <?php $this->Form->end(); ?>
            
        </div>
        
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
            <table class="table">
                    <tr>
                        <th>SrNo.</th>
                        <th>Order Id</th>
                        <th>Phone No.</th>
                        <th>Call Date</th>
                        <th>Status</th>
                        <th>Call Count</th>
                        <th>Tag Status</th>
                        <th>Response</th>
                    </tr>
                    <?php $i=1;
                            foreach($tag_data as $data):
                                #print_r($data);
                                if($data['vicidial_list']['called_count'] == '4' && $data['vicidial_list']['last_name']== '' && $data['vicidial_list']['comments'] =='1' || $data['vicidial_list']['last_name']== '1' && $data['vicidial_list']['comments'] =='1' || $data['vicidial_list']['last_name']== '2' && $data['vicidial_list']['comments'] =='1'){
                                }else{
                                echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$data['vicidial_list']['address3']."</td>";
                                    echo "<td>".$data['vicidial_list']['phone_number']."</td>";
                                    #echo "<td>".$data['vicidial_list']['modify_date']."</td>";
                                    echo "<td>".date_format(date_create($data['vicidial_list']['modify_date']),'d M Y')."</td>";
                                    #echo "<td>" . ($data['vicidial_list']['last_name'] == '1' ? 'Yes' : 'No') . "</td>";
                                    echo "<td>" . ($data['vicidial_list']['last_name'] == '1' ? 'Yes' : ($data['vicidial_list']['last_name'] == '2' ? 'No' : 'N/A')) . "</td>";
                                    echo "<td>".$data['vicidial_list']['called_count']."</td>";
                                    echo "<td>".$data['tag_status']."</td>";
                                    echo "<td>".$data['vicidial_list']['comments']."</td>";

                                echo "</tr>";
                                }

                            endforeach;
                    ?>
                </table>
            </div>
        </div>
    
            <!-- <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>View Sim Details</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div style="color:green;margin-left:20px;font-size: 15px;"><?php echo $this->Session->flash(); ?></div>
                <div class="scrolling">
                
                <div>
        </div>  -->
        
    </div>
</div>


