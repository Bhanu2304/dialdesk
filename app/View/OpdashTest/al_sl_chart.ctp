<?php 
 $Ans = $data[0][0]['Answered'];
 $Abn = $data[0][0]['Abandon'];
 $Sl = $data[0][0]['WithinSLA'];
 $SlTen = $data[0][0]['WithinSLATen'];
 $Offer = $Ans+$Abn;

$alValue = round($Ans*100/$Offer); // Example value for AL
$slValue = round($SlTen*100/$Ans); // Example value for SL 10 
$sl20Value = round($Sl*100/$Ans);


$utilization = $agent_data['Utilization'];
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        
        google.charts.load('current', {'packages':['gauge']});
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawChart2);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['AL', <?php echo $alValue; ?>],
                ['SL 10', <?php echo $slValue; ?>],
                ['SL 20', <?php echo $sl20Value; ?>]
            ]);

            var options = {
                width: 1000, height: 200,
                greenFrom: 90, greenTo: 100,
                yellowFrom: 75, yellowTo: 90,
                redFrom: 0, redTo: 75,
                minorTicks: 5
            };

            var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
            chart.draw(data, options);
        }


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

            setInterval(function() {
                data2.setValue(0, 1, <?php echo $utilization; ?>);
                chart2.draw(data2, options2);
            }, 22000);
        }

        function ajax_agent(loader)
        {
            if(loader== "loader")
            {
                $('#preloader').css('opacity', '1');
            }

            var type = document.querySelector('.viewCheckbox2:checked').value;
            $.post("get_agent_chart",{
            client2 : $('#client2').val(),        
            type : document.querySelector('.viewCheckbox2:checked').value
            },
		    function(util,status){

            var data2 = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Utilization', parseFloat(util)] 
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
            //alert("draw callled");

            if(loader== "loader")
            {
                $('#preloader').css('opacity', '0');
            }

			});

            var client_name = $("#client2 :selected").text();
            $('#utilization_heading').text(client_name+' : '+type);

        }

        function ajax_sl(loader)
        {
            if(loader== "loader")
            {
                $('#preloader').css('opacity', '1');
            }

            var type = document.querySelector('.viewCheckbox:checked').value;

            $.post("get_sl_chart",{
                client2 : $('#client').val(),        
                type : type
            },
		    function(util,status){

            util = JSON.parse(util);
            const answered = util[0][0].Answered;
            const abandon = Number(util[0][0].Abandon);
            const withinSLA = Number(util[0][0].WithinSLA);
            const withinSLATen = Number(util[0][0].WithinSLATen);

            const offer = Number(answered) + Number(abandon);

            const alValue = offer ? Math.round(answered * 100 / offer) : 0; 
            const slValue = withinSLATen ? Math.round(withinSLATen * 100 / answered) : 0; 
            const sl20Value = withinSLA ? Math.round(withinSLA * 100 / answered) : 0;
            
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['AL', alValue],
                ['SL 10', slValue],
                ['SL 20', sl20Value]
            ]);


            var options = {
                width: 1000, height: 200,
                greenFrom: 90, greenTo: 100,
                yellowFrom: 75, yellowTo: 90,
                redFrom: 0, redTo: 75,
                minorTicks: 5
            };

            var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
            chart.draw(data, options);
            //alert("draw callled 1");
                if(loader== "loader")
                {
                    $('#preloader').css('opacity', '0');
                }

			});

            var client_name = $("#client :selected").text();

            $('#al_sl_heading').text(client_name+' : '+type);


        }


        setInterval(ajax_agent, 60000);
        setInterval(ajax_sl, 60000);

    </script>
    <style>
        body {
            background-color: #222;
        }
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0; 
            transition: opacity 0.3s ease; 
            pointer-events: none; 
        }
            #loader {
                display: block;
                position: relative;
                left: 50%;
                top: 50%;
                width: 150px;
                height: 150px;
                margin: -75px 0 0 -75px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #9370DB;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }
            #loader:before {
                content: "";
                position: absolute;
                top: 5px;
                left: 5px;
                right: 5px;
                bottom: 5px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #BA55D3;
                -webkit-animation: spin 3s linear infinite;
                animation: spin 3s linear infinite;
            }
            #loader:after {
                content: "";
                position: absolute;
                top: 15px;
                left: 15px;
                right: 15px;
                bottom: 15px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #FF00FF;
                -webkit-animation: spin 1.5s linear infinite;
                animation: spin 1.5s linear infinite;
            }
            @-webkit-keyframes spin {
                0%   {
                    -webkit-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
            @keyframes spin {
                0%   {
                    -webkit-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    -ms-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
    </style>
</head>
<body>
    <ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
        <li><a>AL And SL</a></li>
    </ol>
    <div class="page-heading">            
        <h1>AL And SL</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>AL And SL</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding">
                    <h5 style="text-align:center" id="al_sl_heading">All : Today</h5>
                    <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'required'=>true,'multiple' => true, 'default' => 'All')); ?>
                    </div>
                    <div class="col-sm-2" style="margin-top:45px;">
                        <input type="radio" class="viewCheckbox" id='view_type' <?php if(isset($view_type) && $view_type==="Today"){echo "checked='checked'";}?> onclick="ajax_sl('loader');" name="view_type" value="Today" checked='checked'/> Today &nbsp;&nbsp;&nbsp;
                        <br>
                        <input type="radio" class="viewCheckbox" id='view_type' <?php if(isset($view_type) && $view_type==="MTD"){echo "checked='checked'";}?> onclick="ajax_sl('loader');" name="view_type" value="MTD" /> MTD
                    </div>
                    <div class="col-sm-5"><div id="chart_div" style="height: 200px;"></div></div>
                    
                    
                </div>
            </div>

            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>Agent Utilization</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding">
                    <h5 style="text-align:center" id="utilization_heading">All : Today</h5>
                    <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('agent_id',array('label'=>false,'id'=>'client2','required'=>'true','class'=>'form-control','options'=>$agent_arr,'required'=>true,'multiple' => true,'default' => 'All')); ?>
                    </div>
                    <div class="col-sm-2" style="margin-top:45px;">
                        <?php //echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'required'=>true,'multiple' => true)); ?>
                        <input type="radio" class="viewCheckbox2" id='view_type2' <?php if(isset($view_type2) && $view_type2==="Today"){echo "checked='checked'";}?> onclick="ajax_agent('loader');" name="view_type2" value="Today" checked='checked'/> Today &nbsp;&nbsp;&nbsp;
                        <br>
                        <input type="radio" class="viewCheckbox2" id='view_type2' <?php if(isset($view_type2) && $view_type2==="MTD"){echo "checked='checked'";}?> onclick="ajax_agent('loader');" name="view_type2" value="MTD" /> MTD
                    </div>
                    <div class="col-sm-5"><div id="chart_div2" style="height: 200px;"></div></div>
                  
                </div>
            </div>

            <div id="preloader">
                <div id="loader"></div>
            </div>

        </div>
    </div>






<script>
    
        // google.charts.load('current', {'packages':['gauge']});
        // google.charts.setOnLoadCallback(drawChart);
        // google.charts.setOnLoadCallback(drawChart2);
    </script>    