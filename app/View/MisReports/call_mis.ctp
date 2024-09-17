<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/jsapi"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/loader.js"></script>
<script type="text/javascript">
    google.load('visualization', '1', {'packages':['columnchart','piechart','table']});
    google.setOnLoadCallback (callChart);
    google.setOnLoadCallback (callChart1);
    function callChart() {
        var dataTable = new google.visualization.DataTable();   //create data table object
        dataTable.addColumn('string','Quarters 2009');  //define columns
        dataTable.addColumn('number', 'Call Details');
        dataTable.addRows([
            ['TOTAL RECEIVED CALLS',620], 
            ['ANSWERED CALLS',610],
            ['ABANDONED CALLS',10],
            ['QUALITY %',87],
            ['AHT',1.72],
            ['WORK DAYS',5],
            ['AVG CAPACITY',8.2],
             ['AVG CALL \ AGENT',74.93]
        
        ]);//define rows of data
        var CallChart = new google.visualization.ColumnChart (document.getElementById('call_chart'));//instantiate our chart objects                          
        var options = {width: 500, height: 200, is3D: true, title: ''};//define options for visualization                          
        CallChart.draw(dataTable, options);//draw our chart
    }
    
     function callChart1() {
        var dataTable = new google.visualization.DataTable();   //create data table object
        dataTable.addColumn('string','Quarters 2009');  //define columns
        dataTable.addColumn('number', 'Tagging Details');
        dataTable.addRows([
   
       
            <?php foreach($category as $row){?>
            ['<?php echo $row['category'];?>',<?php echo $row['total'];?>], 
            <?php }?> 
        
        ]);//define rows of data
        var CallChart = new google.visualization.ColumnChart (document.getElementById('call_chart1'));//instantiate our chart objects                          
        var options = {width: 500, height: 200, is3D: true, title: ''};//define options for visualization                          
        CallChart.draw(dataTable, options);//draw our chart
    }
</script>
<!--
<table cellspacing="0" border="1">
    <tr style="background-color:#317EAC; color:#FFFFFF;">
        <th>DASHBOARD</th>
    </tr>
</table>

<div style="float: left;">
-->
    <table cellspacing="0" border="1">
    <thead>
        <tr style="background-color:#317EAC; color:#FFFFFF;">
            <th>SUMMARY</th>
            <th>MTD</th>
            <th>%</th>
        </tr>
       
    </thead>
    <tbody>
        <tr>
            <td>Total Received Calls</td>
            <td><?php echo isset($TotalCalls['received'])?$TotalCalls['received']:""; ?></td>
            <td><?php echo isset($TotalPersent['received'])?$TotalPersent['received']:""; ?></td>
        </tr>
        <tr>
            <td>Total Answered Calls</td>
            <td><?php echo isset($TotalCalls['answered'])?$TotalCalls['answered']:""; ?></td>
           <td><?php echo isset($TotalPersent['answered'])?$TotalPersent['answered']:""; ?></td>
        </tr>
        <tr>
            <td>Total Abandoned Calls</td>
            <td><?php echo isset($TotalCalls['abandoned'])?$TotalCalls['abandoned']:""; ?></td>
            <td><?php echo isset($TotalPersent['abandoned'])?$TotalPersent['abandoned']:""; ?></td>
        </tr>
        <tr>
            <td>AHT</td>
            <td><?php echo isset($TotalCalls['AHT'])?$TotalCalls['AHT']:""; ?></td>
            <td><?php echo isset($TotalPersent['AHT'])?$TotalPersent['AHT']:""; ?></td>
        </tr>
		<tr>
            <td>Quality %</td>
            <td><?php echo isset($TotalCalls['quality'])?$TotalCalls['quality']:""; ?></td>
            <td><?php echo isset($TotalPersent['quality'])?$TotalPersent['quality']:""; ?></td>
        </tr>
        <tr>
            <td>Work Days</td>
            <td><?php echo isset($TotalCalls['workdays'])?$TotalCalls['workdays']:""; ?></td>
            <td><?php echo isset($TotalPersent['workdays'])?$TotalPersent['workdays']:""; ?></td>
        </tr>
        <tr>
            <td>AVG Capacity</td>
            <td><?php echo isset($TotalCalls['avgcapacity'])?$TotalCalls['avgcapacity']:""; ?></td>
            <td><?php echo isset($TotalPersent['avgcapacity'])?$TotalPersent['avgcapacity']:""; ?></td>
        </tr>
        <tr>
            <td>AVG Call \ Agent</td>
            <td><?php echo isset($TotalCalls['avgcall'])?$TotalCalls['avgcall']:""; ?></td>
            <td><?php echo isset($TotalPersent['avgcall'])?$TotalPersent['avgcall']:""; ?></td>
        </tr>
        
        <tr>
            <td>Tagging Details</td>
            <td><?php echo isset($totalTag)?$totalTag:""; ?></td>
            <td><?php echo isset($totalTag)?'100%':""; ?></td>
        </tr>
       
        <?php foreach($category as $row){?>
            <tr>
                <td>Total Tagged <?php echo $row['category'];?> Details</td>
                <td><?php echo $row['total'];?></td>
                <td><?php if($row['persent'] !=""){echo $row['persent']."%";}?></td>
            </tr>
        <?php }?>  
            
             
    </tbody>
</table>
<!--
</div>
<div style="float: left;border: 2px solid grey;">
    <div id="call_chart"></div>
    <div id="call_chart1"></div>
</div>
-->


