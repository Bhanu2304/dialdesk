<?php 
 $Ans = $data[0][0]['Answered'];
 $Abn = $data[0][0]['Abandon'];
 $Sl = $data[0][0]['WithinSLA'];
 $SlTen = $data[0][0]['WithinSLATen'];
 $Offer = $Ans+$Abn;

$alValue = round($Ans*100/$Offer); // Example value for AL
$slValue = round($SlTen*100/$Ans); // Example value for SL 10 
$sl20Value = round($Sl*100/$Ans);

// -------------chart 2 --------------------

$Ans2 = $data2[0][0]['Answered'];
$Abn2 = $data2[0][0]['Abandon'];
$Sl2 = $data2[0][0]['WIthinSLA'];
$SlTen2 = $data2[0][0]['WIthinSLATen'];
$Offer2 = $Ans2+$Abn2;

$alValue2 = round($Ans2*100/$Offer2); // Example value for AL
$slValue2 = round($SlTen2*100/$Ans2); // Example value for SL 10 
$sl20Value2 = round($Sl2*100/$Ans2);



$utilization = $agent_data['Utilization'];
?>

<style>

/* .modal {

  top: -20% !important;

} */

</style>
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

            setInterval(function() {
                data.setValue(0, 1, <?php echo $alValue; ?>);
                data.setValue(1, 1, <?php echo $slValue; ?>);
                data.setValue(2, 1, <?php echo $sl20Value; ?>);
                chart.draw(data, options);
            }, 22000);
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

        // Refresh the page every minute (60,000 milliseconds)
        setTimeout(function() {
            location.reload();
        }, 60000);


        function getType(form)
        {
    
            var view_type=$("#view_type").val();
            var client=$("#client").val();
           
            
            if(client ===""){
                $("#error").html('<span class="w_msg err" style="color:red;">Please select Client.</span>');
                return false;
            }else{

                //form.submit();
                $('#validate-form').submit();
            }

            
        }

        function getType2(form)
        {
    
            var view_type=$("#view_type2").val();
            var client=$("#client2").val();
           
            if(client ===""){
                $("#error").html('<span class="w_msg err" style="color:red;">Please select Client.</span>');
                console.log("test1");
                return false;
            }else{
                
                //form.submit();
                $('#validate-form2').submit();
            }



            
        }
    </script>
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
                    <?php #echo $this->Form->create('Opdashs',array('action'=>'al_sl_chart','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <form action="al_sl_chart" id='validate-form' method="post">
                        <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'required'=>true,'multiple' => true)); ?>
                        </div>
                        <div class="col-sm-2" style="margin-top:45px;">
                            <?php //echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'required'=>true,'multiple' => true)); ?>
                            <input type="radio" id='view_type' <?php if(isset($view_type) && $view_type==="Today"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="Today" checked='checked'/> Today &nbsp;&nbsp;&nbsp;
                            <br>
                            <input type="radio" id='view_type' <?php if(isset($view_type) && $view_type==="MTD"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="MTD" /> MTD
                        </div>
                        <div class="col-sm-5"><div id="chart_div" style="height: 200px;"></div></div>
                    </form>
                </div>
            </div>

            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>Agent Utilization</h2>
                    <div class="panel-ctrls"></div>
                </div>
                <div class="panel-body no-padding">
                    <?php #echo $this->Form->create('Opdashs',array('action'=>'al_sl_chart','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <form action="al_sl_chart" id='validate-form2' method="post">
                        <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('agent_id',array('label'=>false,'id'=>'client2','required'=>'true','class'=>'form-control','options'=>$agent_arr,'required'=>true,'multiple' => true)); ?>
                        </div>
                        <div class="col-sm-2" style="margin-top:45px;">
                            <?php //echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'required'=>true,'multiple' => true)); ?>
                            <input type="radio" id='view_type2' <?php if(isset($view_type2) && $view_type2==="Today"){echo "checked='checked'";}?> onclick="getType2();" name="view_type2" value="Today" checked='checked'/> Today &nbsp;&nbsp;&nbsp;
                            <br>
                            <input type="radio" id='view_type2' <?php if(isset($view_type2) && $view_type2==="MTD"){echo "checked='checked'";}?> onclick="getType2();" name="view_type2" value="MTD" /> MTD
                        </div>
                        <div class="col-sm-5"><div id="chart_div2" style="height: 200px;"></div></div>
                    <?php $this->Form->end(); ?>
                </div>
            </div>

        </div>
    </div>






