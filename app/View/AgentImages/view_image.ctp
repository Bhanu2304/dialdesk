<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Agent Images</a></li>
    <li class="active"><a href="#">View Images</a></li>
</ol>
<div class="page-heading">                                           
<h1>Agent Images</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>View Images</h2>
        <br/>
                <h2> <?php echo $this->Session->flash(); ?></h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
            <table class="table">
                    <tr>
                        <th>SrNo.</th>
                        <th>Phone No.</th>
                        <th>Image</th>
                        <th>Image Local</th>
                        <th>Date Receive</th>
                    </tr>
                    <?php $i=1;
                            foreach($data as $d):
                                echo "<tr>";
                                    echo "<td>".$i++."</td>";
                                    echo "<td>".$d['agent_whatsapp_log']['phone_no']."</td>";
                                    echo "<td><a target='_blank' href=".$d['agent_whatsapp_log']['image'].">image</a</td>";
                                    echo "<td><a target='_blank' href=".str_replace("lms.mascallnetnorth.in","192.168.11.252",$d['agent_whatsapp_log']['image']).">image local</a</td>";
                                    
                                    echo "<td>".$d['agent_whatsapp_log']['created_at']."</td>";
                                echo "</tr>";

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


