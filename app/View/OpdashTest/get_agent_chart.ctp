<?php 

$utilization = $agent_data['Utilization'];
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['gauge']});
    google.charts.setOnLoadCallback(drawChart2);


    function drawChart2() {
        var data2 = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            ['Utilization', <?php echo $utilization; ?>],
        ]);

        var options2 = {
            width: 1000, height: 200,
            greenFrom: 90, greenTo: 100,
            yellowFrom: 75, yellowTo: 90,
            redFrom: 0, redTo: 75,
            minorTicks: 5
        };

        var chart2 = new google.visualization.Gauge(document.getElementById('chart_div2'));
        chart2.draw(data2, options2);




        
    }

    
</script>

    






