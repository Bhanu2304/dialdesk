<?php ?>
<script> 		


</script>
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Master Field</a></li>
    <li class="active"><a href="#">Dashboard</a></li>
</ol>
<div class="page-heading">            
    <h1>Dashboard</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Dashboard</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- <div id="chart_div"></div> -->
                    <div class="col-lg-2 col-md-8 col-sm-8 col-xs-12"></div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <canvas id="chart_div"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>

    var labels = ['Name', 'Address', 'City', 'Phone', 'Email', 'State', 'Pincode'];
    var dataValues = [<?php echo $total_name; ?>, <?php echo $total_address; ?>, <?php echo $total_city; ?>, <?php echo $total_phone; ?>, <?php echo $total_email; ?>, <?php echo $total_state; ?>, <?php echo $total_pincode; ?>];

    var data = {
        labels: labels,
        datasets: [{
            label: 'Name',
            data: dataValues,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(60, 179, 113, 0.5)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(60, 179, 113, 1)'
            ],
            borderWidth: 1
        }],
    };

    var ctx = document.getElementById('chart_div').getContext('2d');

    var myPieChart = new Chart(ctx, {
        labels: labels,
        type: 'bar',
        data: data,
        options: {
            plugins: {
                datalabels: {
                        display: true,
                        color: '#000000', 
                        font: {
                            size: 14, 
                            weight: 'bold' 
                        },
                        formatter: function (value, context) {
                            // var label = labels[context.dataIndex]; 
                            // return value > 0 ? label + ': ' + value : '';
                        },
                        filter: {
                            enabled: true, 
                            function: function(value, index, values) {
                                return value > 0; 
                            }
                        }
                    }
            },
            legend: {
                display: true,
                labels: {
                    generateLabels: function(chart) {
                        return chart.data.labels.map(function(label, index) {
                            return {
                                text: label,
                                fillStyle: chart.data.datasets[0].backgroundColor[index]
                            };
                        });
                    }
                }
            },
            datalabels: {
                color: '#ffffff', 
                font: {
                    size: 14, 
                    weight: 'bold' 
                },
                formatter: function(value, context) {
                    return value + ' Tickets'; 
                }
            }
          
        },
        plugins:[ChartDataLabels]
    });
</script>






          