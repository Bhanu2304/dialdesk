<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


<?php if($this->Session->read('role') =="admin"){
	$companyid=$this->Session->read('companyid');     
?>
<script>
    function viewClient(){
        $("#view_client_form").submit();	
    } 
</script>

<!-- <script type="text/javascript" src="https://www.google.com/jsapi"></script> -->

<style>
    thead > tr > td {background: cornflowerblue;color: white;font-weight: 500;}
    .odd,.even {    border: 1px solid cornflowerblue;background:blue!important;color: white;}
</style>

<div style="margin-left:20px;">
    <div class="form-group">
<?php echo $this->Form->create('Home',array('url'=>'/homes2','id'=>'view_client_form')); ?>

        <div class="col-sm-3">
    <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control', 'onchange'=>'viewClient();','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
            </div>
        <div class="col-sm-4" style="margin-top:10px;">
    <?php echo '<span style="color:red;" class="">Registration Date:-'.$activation_date.'</span>';?>
        </div>
<?php echo $this->Form->end(); ?>
    </div>
</div>
<br/>
<br/>
<?php }?>

<?php if($this->Session->read('companyid') !=""){?>
<?php if($this->Session->read('clientstatus') ==="A"){?>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/jsapi"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/loader.js"></script>
<script type="text/javascript">
google.load('visualization', '1', {'packages':['columnchart','piechart','table']});
$(document).ready(function () { 
    document.getElementById("fdate").disabled = true;
    document.getElementById("ldate").disabled = true;
    <?php if(isset($viewType) && $viewType==="Custom"){?>
	document.getElementById("fdate").disabled = false;
	document.getElementById("ldate").disabled = false;
    <?php }?>
});


function getType(){
    $("#fdate").val('');
    $("#ldate").val('');
    document.getElementById("fdate").disabled = true;
    document.getElementById("ldate").disabled = true;
    
    var rates = document.getElementsByName('view_type');

    var rate_value;
    for(var i = 0; i < rates.length; i++){
        if(rates[i].checked){
            rate_value = rates[i].value;
            if(rate_value =="Custom"){
                document.getElementById("fdate").disabled = false;
                document.getElementById("ldate").disabled = false;
            }
            else
            {
                document.getElementById("HomesIndexForm").submit();
            }
            
        }
    }	
}






function getTypeCount(category,type){  
    var qry="<?php echo $qry;?>";
   // alert(category+''+type);
    //var Head = category+'Head';
    var Body = category+'Body';
    //document.getElementById(Head).innerHTML=category+'-'+type;
    //document.getElementById(Body).innerHTML=category+type;
    Body = '#'+category+'Body';
    var view ='#'+category+'Body123';
    
     $.post("Homes/getTypeCount",{Category:category,Type:type,qry:qry},function(data){
        $(view).show();
        $(Body).html(data);
    });

}
/*
function selectCampaign(type){
    $("#campaignid").hide();
    if(type ==='outbounds'){
        $("#campaignid").show();
    }  
}*/
<?php //if(isset($callType) && $callType ==="outbounds"){?>
/*
$(document).ready(function(){
    selectCampaign('<?php //echo $callType;?>');
});*/
<?php //}?>
</script>
<style>
        .scenario{                
            border: 1px solid gray;
            text-align: center;
            width: 180px;
            margin-left: 130px;

        }

        .wrap {
            width: 100%;
        }

        .wrap table {
            width: 100%;
            table-layout: fixed;
        }

        table tr td {
            padding: 5px;
            border: 1px solid #eee;
            /*width: 200px;*/
            word-wrap: break-word;
        }

        table.head tr td {
            background: #eee;
        }

        .inner_table {
            height: 160px;
            overflow-y: auto;
        }
</style>
<?php 
$cType="inbounds";
if(isset($callType)){$cType=$callType;}
?>
    <ol class="breadcrumb">                            
        <li class=""><a href="<?php echo $this->webroot?>homes">Home</a></li>
        <li class="active"><a href="<?php echo $this->webroot?>homes">Dashboard</a></li>
        
    </ol>
    <div class="page-heading">            
        <h1>Dashboard<small></small></h1>
        <?php if($viewDate !=""){?>
            <span style="margin-left:29%;font-size:20px;"><?php echo $viewDate;?></span>
        <?php }?>
            <?php echo $this->Form->create('Home',array('url'=>'/homes2'));?>
            <div class="search-dashbord" > 
                <p>
                    <input type="radio" <?php if(isset($viewType) && $viewType==="Today"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="Today" />Today
                    <input type="radio" <?php if(isset($viewType) && $viewType==="Yesterday"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="Yesterday" />Yesterday
                    <input type="radio" <?php if(isset($viewType) && $viewType==="Weekly"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="Weekly" />Weekly
                    <input type="radio" <?php if(isset($viewType) && $viewType==="Monthly"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="Monthly" />Monthly
                    <input type="radio" <?php if(isset($viewType) && $viewType==="Custom"){echo "checked='checked'";}?> onclick="getType();" name="view_type" value="Custom" />Custom  
                </p> 
                <p>
                    <select onchange="selectCampaign(this.value)" name="callType"  style="width:90px;" >
                        <option <?php if(isset($cType) && $cType==="inbounds"){echo "selected='selected'";}?> value="inbounds">Inbounds</option>
                        <!--
                        <option <?php if(isset($cType) && $cType==="outbounds"){echo "selected='selected'";}?> value="outbounds">Outbounds</option>
                        -->
                        </select>
                    <!--
                    <select name="campaignid" style="width:90px;display:none;" id="campaignid">
                        <option value="">Select</option>
                        <?php //foreach ($Campaign as $key => $value){?>
                            <option <?php //if(isset($campaignid) && $campaignid==$key){echo "selected='selected'";}?> value="<?php //echo $key;?>"><?php //echo $value;?></option>
                        <?php //} ?>
                    </select>
                    -->
                    <input type="text" name="fdate" style="width:90px;" value="<?php echo isset($fd)?$fd:"";?>" id="fdate" class="date-picker" placeholder="From" />
                    <input type="text" name="ldate" style="width:90px;" value="<?php echo isset($ld)?$ld:"";?>" id="ldate" class="date-picker" placeholder="To" />
                    <input type="submit" value="Search" /> 
                </p>
               
                
            
        </div>
        <?php echo $this->form->end();?>
            

        <div class="options"></div>
    </div>
    <?php if($cType ==="inbounds"){?>

    <div class="container-fluid">                   
        <div data-widget-group="group1">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                    <div class="info-tile info-tile-alt tile-green">
                        <div class="info">
                            <div class="tile-heading"><span>Opening Balance</span><br>
                                as on 1<sup>st</sup> of Current Month</div>
                            <br>
                            <div class="tile-body"><span><?php echo $opening_balance; ?></span></div>
                        </div>
                        <div class="stats">
                            <div class="tile-content"><div id="dashboard-sparkline-indigo"></div></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                    <div class="info-tile info-tile-alt tile-danger">
                        <div class="info">
                            <div class="tile-heading"><span>Consumed Value</span><br>MTD</div><br>
                            <div class="tile-body"><span><?php echo empty($consumed)?0:$consumed; ?></span></div>
                        </div>
                        <div class="stats" style="padding-top:0px;padding-bottom:0px;padding-right: 0px;">
                                    IB  :<?php echo round($cs_pulse['ib']+(int)$today_cs_pulse['ib']); ?> <br>
                                    OB  :<?php echo round($cs_pulse['ob']+(int)$today_cs_pulse['ob']); ?> <br>
                                    SMS :<?php echo round($cs_pulse['sms']+(int)$today_cs_pulse['sms']); ?> <br>
                                    Email: <?php echo round($cs_pulse['email']+(int)$today_cs_pulse['email']); ?> <br>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                        <div class="info-tile info-tile-alt tile-success">
                            <div class="info">
                                <div class="tile-heading"><span>Collection</span><br>MTD</div>
                                <br>
                                <div class="tile-body"><span><?php echo $coll_bal; ?></span></div>
                            </div>
                            <div class="stats">
                                <div class="tile-content"><div id="dashboard-sparkline-indigo"></div></div>
                            </div>
                        </div>
                    </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                    <div class="info-tile info-tile-alt tile-danger">
                        <div class="info">
                            <div class="tile-heading"><span>Closing Balance</span><br>as on Date</div>
                            <br>
                            <div class="tile-body"><span><?php echo round($opening_balance-$consumed); ?></span></div>
                        </div>
                        <div class="stats">
                            <div class="tile-content"><div id="dashboard-sparkline-indigo"></div></div>
                        </div>
                    </div>
                </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-tile info-tile-alt tile-indigo">
                        <div class="info">
                            <div class="tile-heading"><span>Total Ans. Calls</span></div>
                            <div class="tile-body"><span><?php echo empty($Answered)?0:$Answered; ?></span></div>
                        </div>
                        <div class="stats">
                            <div class="tile-content"><div id="dashboard-sparkline-indigo"></div></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-tile info-tile-alt tile-danger">
                        <div class="info">
                            <div class="tile-heading"><span>Total Aband Calls</span></div>
                            <div class="tile-body "><span><?php echo empty($Abandon)?0:$Abandon; ?></span></div>
                        </div>
                        <div class="stats">
                            <div class="tile-content"><div id="dashboard-sparkline-gray"></div></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-tile info-tile-alt tile-primary">
                        <div class="info">
                            <div class="tile-heading"><span>Total Tagged Calls</span></div> 
                            <div class="tile-body "><span><?php echo empty($totalTag)?0:$totalTag; ?></span></div>
                        </div>
                        <div class="stats">
                            <div class="tile-content"><div id="dashboard-sparkline-primary"></div></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="info-tile info-tile-alt tile-green">
                        <div class="info">
                            <div class="tile-heading"><span>Total Aband Call Back</span></div> 
                            <div class="tile-body "><span><?php echo empty($AbandCallBack)?0:$AbandCallBack; ?></span></div>
                        </div>
                        <div class="stats">
                            <div class="tile-content"><div id="dashboard-sparkline-primary"></div></div>
                        </div>
                    </div>
                </div>
                
            </div>
 
              <div class="row" style="display:none;">
                 <div class="col-md-6 col-sm-6" >
                    <div class="panel panel-white ov-h" data-widget='{"draggable": "false"}'>
                        <div class="panel-body ov-h">
                            <div class="scenario" >CALL PERFORMANCE</div>
                            <script type="text/javascript">
                                //google.charts.load("current", {packages:["corechart"]});

                                google.setOnLoadCallback (callChart);
                                function callChart() {
                                    var data = google.visualization.arrayToDataTable([
                                      ['Type', 'MTD'],
                                      ['Answer Calls',<?php echo empty($Answered)?0:$Answered; ?>],
                                      ['Aband Calls',<?php echo empty($Abandon)?0:$Abandon; ?>],
                                      ['Tagged Calls',<?php echo empty($totalTag)?0:$totalTag; ?>],
                                      ['Aband Call Back',<?php echo empty($AbandCallBack)?0:$AbandCallBack; ?>]
                                    ]);

                                    var options = {
                                      title: '',
                                      is3D: true,
                                      width:550,
                                      height:250,
                                    };
                                    $("text:contains(" + options.title + ")").attr({'x':'230', 'y':'20'});
                                    var CallChart = new google.visualization.PieChart(document.getElementById('call_chart'));
                                    CallChart.draw(data, options);

                                }
                            </script>
                            <div id="call_chart" style="margin-left:-80px;"></div>
                            <script type="text/javascript">
                                google.setOnLoadCallback (callChartTbl);
                                function callChartTbl() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Summary');
                                    data.addColumn('string', 'MTD');
                                    data.addRows([
                                        
                                        <?php if($Answered !=""){?>['Answer Calls','<?php echo empty($Answered)?0:$Answered; ?>'],<?php }?>
                                        <?php if($Abandon !=""){?>['Aband Calls','<?php echo empty($Abandon)?0:$Abandon; ?>'],<?php }?>
                                        <?php if($totalTag !=""){?>['Tagged Calls','<?php echo empty($totalTag)?0:$totalTag; ?>']<?php }?>
                                        <?php if($AbandCallBack !=""){?>['Aband Call Back','<?php echo empty($AbandCallBack)?0:$AbandCallBack; ?>']<?php }?>
                                       
                                       
                                        ]);
                                        var CallChartTable = new google.visualization.Table(document.getElementById('call_table'));
                                        CallChartTable.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
                                }

                            </script> 
                            <!--
                            <div style="height:150px;overflow: auto;">
                                <div id="call_table"></div>
                            </div>
                            -->
                            <div class="wrap">
                                <table class="head">
                                    <tr>
                                        <td>SNo</td>
                                        <td>Summary</td>
                                        <td>MTD</td>
                                    </tr>
                                </table>
                                <div class="inner_table">
                                    <table>
                                        <?php if($Answered !=""){?>
                                        <tr>
                                            <td>1</td>
                                            <td>Answer Calls</td>
                                            <td><?php echo empty($Answered)?0:$Answered; ?></td>
                                        </tr>
                                        <?php }?>
                                        <?php if($Abandon !=""){?>
                                        <tr>
                                            <td>2</td>
                                            <td>Aband Calls</td>
                                            <td><?php echo empty($Abandon)?0:$Abandon; ?></td>
                                        </tr>
                                        <?php }?>
                                        <?php if($totalTag !=""){?>
                                        <tr>
                                            <td>3</td>
                                            <td>Tagged Calls</td>
                                            <td><?php echo empty($totalTag)?0:$totalTag; ?></td>
                                        </tr>
                                        <?php }?>
                                        <?php if($AbandCallBack !=""){?>
                                        <tr>
                                            <td>4</td>
                                            <td>Aband Call Back </td>
                                            <td><?php echo empty($AbandCallBack)?0:$AbandCallBack; ?></td>
                                        </tr>
                                        <?php }?>
                                    </table>
                                </div>
                            </div>
                                
                        
                        </div>
                    </div>
                </div>
                <?php
                $keys = array_keys($newcategory);
                //$header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                $header = array('MTD'=>'','intat'=>'Close in TAT','outtat'=>'Close out TAT','openintat'=>'Open in TAT','openouttat'=>'Open Out TAT');
                ?>
                  
                  
                 <div class="col-md-6 col-sm-6" >
                    <div class="panel panel-white ov-h" data-widget='{"draggable": "false"}'>
                        <div class="panel-body ov-h">
                            <div class="scenario" >TAT PERFORMANCE</div>
                            <script type="text/javascript">
                                google.setOnLoadCallback (TatChart);
                                function TatChart() {
                                    var data = google.visualization.arrayToDataTable([
                                      ['Task', 'MTD'],
                                    
                                       <?php foreach($keys as $k){foreach($header as $k1=>$v1){
                                        $string=$k.$v1;
                                        $string1 =(strlen($string) > 15) ? substr($string,0,15).'...' : $string; 
                                        ?>
                                        ['<?php echo $string1;?>',<?php echo $newcategory[$k][$k1];?>],              
                                        <?php }}?>
                                    
                                    ]);

                                    var options = {
                                      title: '',
                                      is3D: true,
                                      width:550,
                                      height:250,
                                    };
                                    options.pieSliceTextStyle = {fontSize:25};
                                    var Tat = new google.visualization.PieChart(document.getElementById('Tat_pie_chart'));
                                    Tat.draw(data, options);
                                }


                            </script>
                            <div id="Tat_pie_chart" style="margin-left:-80px;"></div>
                            
                            <script type="text/javascript">
                                google.setOnLoadCallback (closeLoop);
                                function closeLoop() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Summary');
                                    data.addColumn('string', 'MTD');
                                    data.addColumn('string', '%');
                                    
                                    data.addRows([
                                        <?php foreach($keys as $k){foreach($header as $k1=>$v1){?>
                                        ['<?php echo $k;?> <?php echo $v1;?>','<?php echo $newcategory[$k][$k1];?>','<?php echo round($newcategory[$k][$k1]*100/$tatTotal,2);?>%' ],
                                        <?php }}?>
                                        ]);
                                        var closeLoopData = new google.visualization.Table(document.getElementById('close_loop_div'));
                                        closeLoopData.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
                                }
                            </script> 
                            <!--
                            <div style="height:150px;overflow: auto;">
                                <div id="close_loop_div" ></div>
                            </div>
                            -->
                            
                            
                            <div class="wrap">
                                <table class="head">
                                    <tr>
                                        <td>SNo</td>
                                        <td>Summary</td>
                                        <td>MTD</td>
                                        <td>%</td>
                                    </tr>
                                </table>
                                <div class="inner_table">
                                    <table>
                                    <?php $sr=1; foreach($keys as $k){foreach($header as $k1=>$v1){?>
                                    <tr>
                                        <td><?php echo $sr++; ?></td>
                                        <td><?php echo $k;?> <?php echo $v1;?></td>
                                        <td><?php echo $newcategory[$k][$k1];?></td>
                                        <td><?php echo round($newcategory[$k][$k1]*100/$tatTotal,2);?>%</td>
                                    </tr>
                                     <?php }}?>
                                </table>
                                </div>
                            </div>
                            
                            

                        </div>
                           
                        </div>
                    </div>
                </div>
            
            
    
            
            

            
        <?php $count=0; $Total=0;
        foreach($Category1 as $c):
            $data[$count]['ecrName'] = $c['tab']['Category1'];
            $data[$count]['count'] = $c['0']['count'];
            $Total += $c['0']['Total'];
            $count++;
            endforeach;
            
        ?> 
            
            
    <?php for($i=1; $i<=ceil($count/3);$i++){?>               
        <div class="row ">
        <?php 
        $flag = false;   
        
        
        
        for($j=(3*$i-3); $j<$count; $j++){
            if($j%3==0 && $flag){
                break;
            }
            $flag = true;
        ?>
       
        <div class="col-md-6 col-sm-6" style="display:none;">
            
            <div class="panel panel-white ov-h" data-widget='{"draggable": "false"}'>
                
                <div class="panel-body ov-h">
                    <div class="scenario" >Scenario - <?php echo $data[$j]['ecrName']; ?></div>
                    <div class="panel-body ov-h">
                    <script type="text/javascript">
                        google.setOnLoadCallback (<?php echo $data[$j]['ecrName'];?>Chart);
                        function <?php echo $data[$j]['ecrName'];?>Chart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Type', 'MTD'],
                              <?php
                              $type = explode(',',$data[$j]['count']);
                              foreach($type as $k){
                              $split = explode('@@',$k);
                              $string=$split[0];
                              ?>
                              ['<?php echo $string = (strlen($string) > 15) ? substr($string,0,15).'...' : $string;?>',<?php echo $split[1];?>],
                               <?php }?>
                            ]);

                            var options = {
                              title: '',
                              is3D: true,
                              width:500,
                              height:250

                            };

                            var chartId<?php echo $data[$j]['ecrName'];?> = new google.visualization.PieChart(document.getElementById('chartId<?php echo $data[$j]['ecrName'];?>'));
                            chartId<?php echo $data[$j]['ecrName'];?>.draw(data, options);

                        }
                    </script>
                     <div id='chartId<?php echo $data[$j]['ecrName'];?>' style="margin-left:-80px;"></div>


                </div>
                    
                    
                <div class="wrap">
                    <table class="head">
                        <tr>
                            <td>SNo</td>
                            <td>Sub Scenario 1</td>
                            <td>Count</td>
                            <td>%</td>
                        </tr>
                    </table>
                    <div class="inner_table">
                        <table>
                        <?php
                        $type = explode(',',$data[$j]['count']);
                        $sr=1;
                        foreach($type as $k){
                        $split = explode('@@',$k);
                        ?>
                        <tr>
                            <td><?php echo $sr++; ?></td>
                            <td><span style="cursor:pointer;  text-decoration: underline;" onclick="scrollWin(0, 1000),getTypeCount('<?php echo $data[$j]['ecrName'];?>','<?php echo $split[0];?>')"><?php echo $split[0];?></span></td>
                            <td><?php echo $split[1]; ?></td>
                            <td><?php echo round(($split[1]*100)/$Total).'%'; ?></td>
                        </tr>
                        <?php }?>
                    </table>
                    </div>
                </div> 
                    
                    
                    
                   <!--
                    <div style=" height:150px;overflow: auto;">
             
                    <table  class="table table-striped table-bordered dataTable" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th align="center">SNo</th>
                            <th align="center">Sub Scenario 1</th>
                            <th align="center">Count</th>
                            <th align="center">&nbsp;&nbsp;&nbsp;&nbsp;%&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        </tr>
                        
                        <?php
                        $type = explode(',',$data[$j]['count']);
                        $sr=1;
                        foreach($type as $k){
                        $split = explode('@@',$k);
                        
                       
                       
                        ?>
                        
                        <tr>
                            <td align="left"><?php echo $sr++; ?></td>

                            <td align="left">
                                <span style="cursor:pointer;  text-decoration: underline;" onclick="scrollWin(0, 200),getTypeCount('<?php echo $data[$j]['ecrName'];?>','<?php echo $split[0];?>')"><?php echo $split[0];?></span>

                            </td>
                           
                            <td align="left"><?php echo $split[1]; ?></td>
                            <td align="left"><?php echo round(($split[1]*100)/$Total).'%'; ?></td>
                    </tr>
                    
                    <?php    } ?>
                    </table>
                    
                </div>
                    -->
                    
                    
                    
                   
                    
                </div>
            </div>
        </div>
        
    <?php } ?>
   
    
    <?php $flag = false;   
        for($j=(3*$i-3); $j<$count; $j++){
                if($j%3==0 && $flag){
                    break;
                }
                $flag = true;
        ?>
        <div class="col-md-6 col-sm-6" id="<?php echo $data[$j]['ecrName'];?>Body123" style="display:none;"  >
            <div class="panel panel-white ov-h" data-widget='{"draggable": "false"}'>  
                <div class="panel-body ov-h">
                    <div id="<?php echo $data[$j]['ecrName'];?>Body"></div>
                </div>
            </div>
        </div>        
        <?php } ?>
        
        
    </div>
   

    <script>
    function scrollWin(x, y) {
        window.scrollBy(x, y);
    }
    </script>
            
    <!--          
    <div class="row "> 
        <?php $flag = false;   
        for($j=(3*$i-3); $j<$count; $j++){
                if($j%3==0 && $flag){
                    break;
                }
                $flag = true;
        ?>
        <div class="col-md-6 col-sm-6">
            <div class="panel panel-white ov-h" data-widget='{"draggable": "false"}'>  
                <div class="panel-heading">
                    <h2><div id="<?php echo $data[$j]['ecrName'];?>Head"></div></h2>
                </div>

                <div class="panel-body ov-h">
                    <div id="<?php echo $data[$j]['ecrName'];?>Body"></div>
                </div>
            </div>
        </div>        
        <?php } ?>
        </div>
    -->




    <?php } ?>

            </div>  
        </div>
    <?php }?>


    <?php if($cType ==="outbounds"){?>
        <div class="container-fluid">                   
            <div data-widget-group="group1">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-indigo">
                            <div class="info">
                                <div class="tile-heading"><span>Total Ans. Calls</span></div>
                                <div class="tile-body"><span><?php echo empty($obAnswered)?0:$obAnswered; ?></span></div>
                            </div>
                            <div class="stats">
                                <div class="tile-content"><div id="dashboard-sparkline-indigo"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-danger">
                            <div class="info">
                                <div class="tile-heading"><span>Total Aband Calls</span></div>
                                <div class="tile-body "><span><?php echo empty($obAbandon)?0:$obAbandon; ?></span></div>
                            </div>
                            <div class="stats">
                                <div class="tile-content"><div id="dashboard-sparkline-gray"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-primary">
                            <div class="info">
                                <div class="tile-heading"><span>Total Tagged Calls</span></div> 
                                <div class="tile-body "><span><?php echo empty($obTotalTag)?0:$obTotalTag; ?></span></div>
                            </div>
                            <div class="stats">
                                <div class="tile-content"><div id="dashboard-sparkline-primary"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="row ">
                    <div class="col-md-6 col-sm-6" >
                        <div class="panel panel-white ov-h" data-widget='{"draggable": "false"}'>
                            <div class="panel-controls dropdown">
                                <button class="btn btn-icon-rounded dropdown-toggle" data-toggle="dropdown"><span class="material-icons inverted">more_vert</span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="panel-heading">
                                <h2>Calling Performance</h2>
                            </div>
                            <div class="panel-body ov-h">
                                <script type="text/javascript">
                                    google.setOnLoadCallback (obCallChart);
                                    function obCallChart() {
                                        var dataTable = new google.visualization.DataTable();   //create data table object
                                        dataTable.addColumn('string','Quarters 2009');  //define columns
                                        dataTable.addColumn('number', 'Outbound Call Details');
                                        dataTable.addRows([['Total Ans. Calls',<?php echo empty($obAnswered)?0:$obAnswered; ?>], ['Total Aband Calls',<?php echo empty($obAbandon)?0:$obAbandon; ?>],['Total Tagged Calls',<?php echo empty($obTotalTag)?0:$obTotalTag; ?>]]);//define rows of data
                                        var CallChart = new google.visualization.ColumnChart (document.getElementById('obcall_chart'));//instantiate our chart objects                          
                                        var options = {width:450, height: 280, is3D: true, title: '',
                                        colors: ['#3f51b5', '#e84e40', '#03a9f4', '#f3b49f', '#f6c7b6']};//define options for visualization                          
                                        CallChart.draw(dataTable, options);//draw our chart
                                    }
                                </script> 
                                <div style="height:300px;overflow: auto;">
                                    <div id="obcall_chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                
                
                
            </div>       
        </div>
    <?php }?>    

    <?php if($cType ==="outbounds" || $cType ==="inbounds"){?> 
        
        
        <div class="container-fluid">                   
            <div class="row">
                <div class="col-xs-12">
                  <?php 
                           date_default_timezone_set('Asia/Kolkata');

                            if (date('m') <= 3) {
                                $financial_year = (date('Y')-1) . '-' . date('Y');
                            } else {
                                $financial_year = date('Y') . '-' . (date('Y') + 1);
                            }
                    ?>
                        <h2>Ledger Balance(FY <?php echo $financial_year;?>)</h2>
                </div>
            </div>



                <?php 
                //print_r($index_ledger_data);exit;

                foreach($index_ledger_data as $client=>$record) 
                { //print_r($record);exit;
                    
                    ?>

                        <tr>
                        
                        
                            <td align="right" style="background: #EEE9E9; display:none;"><?php  $op =($record['ledger_access_usage']+$record['ledger_topup']+$record['ledger_sub']+$record['ledger_setup']);   number_format($op,2); $Opening_ledger=$Opening_ledger+$op; ?></td>
                            <td align="right" style="background: #EEE9E9; display:none;"><?php  $led_op = $record['billed']+$record['subbilled']+$record['firstbilled']+$record['setupbilled'];   number_format($led_op,2); $Billed=$Billed+$led_op; ?></td>
                            <td align="right" style="background: #EEE9E9; display:none;"><?php  $total_collected = $record['coll']+$record['subs_coll']+$record['first_plan_coll']+$record['setupcoll'];  number_format($total_collected,2);  $Collected=$Collected+$total_collected; ?></td>
                            <td align="right" style="background: #EEE9E9; display:none;" class="left_bord"><?php   $cl=round($op+$led_op-$record['coll']-$record['subs_coll']-$record['first_plan_coll']-$record['setupcoll'],2);  $clsum= $clsum+$cl;
                            number_format($cl,2); 
                            $led_access = round(($record['ledger_access_usage']/118)*100,2);
                            $led_topup = round(($record['ledger_topup']/118)*100,2);
                            //$led_sub = round(($record['ledger_sub']/118)*100,2);
                            
                            $cln = round(($record['ledger_sub']/118)*100,2);
                            $onePer = round($record['RentalAmount']/100,2); 
                            $plan_pers = round($record['Balance']/$onePer,3);
                            $led_bal_sub = round(($cln*$plan_pers)/100,2); 
                            //$open_bal = $record['talk_time']-($led_bal_sub+$led_access+$led_topup);
                            $open_bal = $record['talk_time'];
                            $coll = $record['coll']+$record['subs_coll']+$record['first_plan_subscoll'];
                            //echo '<br/>'.($record['subs_coll']/118;exit;
                            
                            
                            $op_val_subs = round((($record['first_plan_subscoll'])/118)*$plan_pers,2);
                            $open_bal +=$op_val_subs;
                            $fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
                            $fr_val_talk= round(($record['billed']*100/118),2);
                            $fr_val = $fr_val_subs+$fr_val_talk;
                            
                            $first_bill = $record['first_plan_value'];
                            $first_bill_with_gst = round($record['first_plan_value_with_gst'],2);
                            $plan_sub_cost = round($record['new_plan_sub_cost'],2);
                            $plan_setup_cost = round($record['new_plan_setup_cost'],2);
                            $plan_dev_cost = round($record['new_plan_dev_cost'],2);
                            
                            $url = "bill_type=first_bill&Subscription={$plan_sub_cost}&SetupCost={$plan_setup_cost}&DevelopmentCost={$plan_dev_cost}&cost_center={$record['cost_center']}&amt=".abs($first_bill_with_gst)."&sub_start_date={$record['sub_start_date']}&sub_end_date={$record['sub_end_date']}&due_date={$record['due_date']}";
                            ?></td>
                            <td align="right" style=" <?php if($open_bal<0) { echo "background-color:red;display:none;";} else {echo "background-color:#FAEBEB;display:none;";} ?>"><?php   $open_bal -=round($record['open_till_bal'],2); $AB=$AB+$open_bal; ?></td>
                            <td align="right" style="background: #FAEBEB;display:none;"><?php    number_format($fr_val,2); $ABD=$ABD+$fr_val; ?></td>
                            <td align="right" style="background: #FAEBEB;display:none;"><?php $csbal =$record['cs_bal']; $XYZ=$XYZ+$csbal;   number_format($csbal,2); ?></td>
                            <td align="right" style="background: #FAEBEB;display:none;" class="left_bord"><?php $bal = round($open_bal+$fr_val-$record['cs_bal'],2); 
                            $CDX=$CDX+$bal;  number_format($bal,2); ?></td>
                            <td align="right" style="background: #EEE9E9;display:none;"><?php //echo $record['subbilled'];exit;
                            $tobebilledfirst = round($first_bill_with_gst-$record['firstbilled'],2);
                            $tobebilled = round($record['subs_val']-$record['subbilled'],2);
                            $total_without_tax = 0;
                            if($bal<0)
                            {    $total_without_tax=round($bal,2);     }
                            $total = round($total_without_tax*1.18,2);
                            //$exp = $total+$cl;
                            $exp = $total;
                            //$exp = $cl-$led_exp; 
                            if($exp<0)
                            {
                                $exp = -1*$exp;
                            }


                            if($tobebilled<0)
                            {
                                $tobebilled = 0;
                            }
                            $tobebilled_without_tax = round(($tobebilled/118)*100,2);

                            $exposure = $exp+$cl+$tobebilled+$tobebilledfirst;

                            $TOTAL_EXP =$TOTAL_EXP+$exposure;

                            number_format($exp+$cl+$tobebilled+$tobebilledfirst,2);
                            //printing exposure value ends here
                            //echo '<br/>';
                            //echo $record['adv_val'];exit;
                            if($exp==0)
                            {
                                $tobebilled2=0;
                            }
                            else
                            {
                                $tobebilled2=round($exp-$record['billed'],2);
                                $tobebilled2_without_tax = round(($tobebilled2/118)*100,2);
                            }
                            
                            $TO_BE_BILLED = $tobebilled2+$tobebilled+$tobebilledfirst;

                            $TOTAL_TO_BE_BILLED = $TOTAL_TO_BE_BILLED +$TO_BE_BILLED;
                            
                            ?>
                            </td> 
                            <td align="right" style="background: #EEE9E9;display:none;"><?php  number_format($tobebilled2+$tobebilled+$tobebilledfirst,2); ?></td>
                            
                            

                            
                        </tr> 
                    

                <?php } ?>


                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-green">
                            <div class="info">
                                <div class="tile-heading"><span>Opening Balance</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo $Opening_ledger; ?></span></div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-indigo">
                            <div class="info">
                                <div class="tile-heading"><span>Billed</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo $Billed; ?></span></div>
                            </div>
                        
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-success">
                            <div class="info">
                                <div class="tile-heading"><span>Paid</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo $Collected; ?></span></div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-danger">
                            <div class="info">
                                <div class="tile-heading"><span>Outstanding</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo $clsum; ?></span></div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h2>Usage Value Balance</h2>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-green">
                            <div class="info">
                                <div class="tile-heading"><span>Opening Balance</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo $AB; ?></span></div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-indigo">
                            <div class="info">
                                <div class="tile-heading"><span>Value added</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo number_format($ABD,2); ?></span></div>
                            </div>
                        
                        </div>
                    </div>
                    <?php 
                                $mothfirstdate = date("Y-m-01"); 
                                //$currntdate = date("Y-m-d"); 
                                $currntdate = date('Y-m-d',strtotime("-1 days"));
                                // $currntdate = "2022-07-06";
                                ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <a target="_blank" href="<?php echo $this->webroot.'app/webroot/billing_statement/billing_tables.php?FromDate='.$mothfirstdate.'&ToDate='.$currntdate.'&ClientId='.$ClientId; ?>">
                            <div class="info-tile info-tile-alt tile-success">
                                <div class="info">
                                    <div class="tile-heading"><span>Consumed Value</span>
                                        <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                    </div>

                                    <br>
                                    <div class="tile-body"><span><?php echo number_format($XYZ,2); ?></span></div>
                                </div>

                                <div class="stats" style="padding-top:0px;padding-bottom:0px;padding-right: 0px;">
                                
                                    
                                                IB  :<?php echo round($cs_pulse['ib']+(int)$today_cs_pulse['ib']); ?> <br>
                                                OB  :<?php echo round($cs_pulse['ob']+(int)$today_cs_pulse['ob']); ?> <br>
                                                SMS :<?php echo round($cs_pulse['sms']+(int)$today_cs_pulse['sms']); ?> <br>
                                                Email: <?php echo round($cs_pulse['email']+(int)$today_cs_pulse['email']); ?> <br>
                                    
                                </div>
                                
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-tile info-tile-alt tile-danger">
                            <div class="info">
                                <div class="tile-heading"><span>Closing Balance</span>
                                    <!-- <br> as on 1<sup>st</sup> of Current Month -->
                                </div>
                                <br>
                                <div class="tile-body"><span><?php echo number_format($CDX,2); ?></span></div>
                            </div>
                            
                        </div>
                    </div>
                </div>

            
            <div class="row">
                <div class="col-xs-12">
                
                        <h2>ACTIVE SERVICES</h2>
                </div>
                
            </div>

            <div class="row">
                 <?php if(isset($planmaster_arr) && !empty($planmaster_arr)) 
                 { 
                    foreach($planmaster_arr as $pma) 
                    {
                    ?>
                                       
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="info-tile info-tile-alt tile-indigo">
                                                <div class="info">
                                                    
                                                    <div class="tile-heading"><span> MY PLAN </span></div>
                                                    <div class="tile-body"><span><?php echo $pma['plan_master']['PlanName'];?></span></div>

                                                    <div class="tile-heading"><span> SUBSCRIPTION</span></div>
                                                    <div class="tile-body"><span><?php echo $pma['plan_master']['PeriodType'];?></span></div>

                                                    <div class="tile-heading"><span> SUBSCRIPTION Value</span></div>
                                                    <div class="tile-body"><span><?php echo $pma['plan_master']['CreditValue'];?></span></div>

                                                    <div class="tile-heading"><span> INBOUND CALL - DAY CHARGES</span></div>
                                                    <div class="tile-body"><span><?php echo $pma['plan_master']['InboundCallCharge'];?> Rs./Minutes</span></div>

                                                    <div class="tile-heading"><span> PAYMENT MODE</span></div>
                                                    <div class="tile-body"><span><?php echo " "; ?></span></div>

                                                </div>
                                                
                                            </div>
                                        </div>


                    <?php 
                    } 
                 } else { echo "<p style='text-align: center;color: red;font-weight: 500; font-size: large; '>Active Plan Not Found!!</p>"; } ?>
                
            </div>

            <?php $i =1;
               
                    foreach($data as $client=>$record) 
                    { //print_r($record);exit;
                        //echo "";
                        ?>

                            <tr>
                               
                                <td align="right" style="background: #EEE9E9;"><?php  $op =($record['ledger_access_usage']+$record['ledger_topup']+$record['ledger_sub']+$record['ledger_setup']);   number_format($op,2); $Opening_ledger=$Opening_ledger+$op; ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php  $led_op = $record['billed']+$record['subbilled']+$record['firstbilled']+$record['setupbilled'];   number_format($led_op,2); $Billed=$Billed+$led_op; ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php  $total_collected = $record['coll']+$record['subs_coll']+$record['first_plan_coll']+$record['setupcoll'];  number_format($total_collected,2);  $Collected=$Collected+$total_collected; ?></td>
                                <td align="right" style="background: #EEE9E9;" class="left_bord"><?php   $cl=round($op+$led_op-$record['coll']-$record['subs_coll']-$record['first_plan_coll']-$record['setupcoll'],2);  $clsum= $clsum+$cl;
                                 number_format($cl,2); 
                                $led_access = round(($record['ledger_access_usage']/118)*100,2);
                                $led_topup = round(($record['ledger_topup']/118)*100,2);
                                //$led_sub = round(($record['ledger_sub']/118)*100,2);
                                
                                $cln = round(($record['ledger_sub']/118)*100,2);
                                $onePer = round($record['RentalAmount']/100,2); 
                                $plan_pers = round($record['Balance']/$onePer,3);
                                $led_bal_sub = round(($cln*$plan_pers)/100,2); 
                                //$open_bal = $record['talk_time']-($led_bal_sub+$led_access+$led_topup);
                                $open_bal = $record['talk_time'];
                                $coll = $record['coll']+$record['subs_coll']+$record['first_plan_subscoll'];
                                //echo '<br/>'.($record['subs_coll']/118;exit;
                                
                                
                                $op_val_subs = round((($record['first_plan_subscoll'])/118)*$plan_pers,2);
                                $open_bal +=$op_val_subs;
                                $fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
                                $fr_val_talk= round(($record['billed']*100/118),2);
                                $fr_val = $fr_val_subs+$fr_val_talk;
                                
                                $first_bill = $record['first_plan_value'];
                                $first_bill_with_gst = round($record['first_plan_value_with_gst'],2);
                                $plan_sub_cost = round($record['new_plan_sub_cost'],2);
                                $plan_setup_cost = round($record['new_plan_setup_cost'],2);
                                $plan_dev_cost = round($record['new_plan_dev_cost'],2);
                                
                                $url = "bill_type=first_bill&Subscription={$plan_sub_cost}&SetupCost={$plan_setup_cost}&DevelopmentCost={$plan_dev_cost}&cost_center={$record['cost_center']}&amt=".abs($first_bill_with_gst)."&sub_start_date={$record['sub_start_date']}&sub_end_date={$record['sub_end_date']}&due_date={$record['due_date']}";
                                ?></td>
                                <td align="right" style=" <?php if($open_bal<0) { echo "background-color:red; display:none;";} else {echo "background-color:#FAEBEB; display:none;";} ?>"><?php   $open_bal; $AB=$AB+$open_bal; ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php    number_format($fr_val,2); $ABD=$ABD+$fr_val; ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php $csbal =$record['cs_bal']; $XYZ=$XYZ+$csbal;   number_format($csbal,2); ?></td>
                                <td align="right" style="background: #FAEBEB;" class="left_bord"><?php $bal = round($open_bal+$fr_val-$record['cs_bal'],2); 
                                $CDX=$CDX+$bal;  number_format($bal,2); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php //echo $record['subbilled'];exit;
                                $tobebilledfirst = round($first_bill_with_gst-$record['firstbilled'],2);
                                $tobebilled = round($record['subs_val']-$record['subbilled'],2);
                                $total_without_tax = 0;
                                if($bal<0)
                                {    $total_without_tax=round($bal,2);     }
                                $total = round($total_without_tax*1.18,2);
                                //$exp = $total+$cl;
                                $exp = $total;
                                //$exp = $cl-$led_exp; 
                                if($exp<0)
                                {
                                    $exp = -1*$exp;
                                }


                                if($tobebilled<0)
                                {
                                    $tobebilled = 0;
                                }
                                $tobebilled_without_tax = round(($tobebilled/118)*100,2);

                                $exposure = $exp+$cl+$tobebilled+$tobebilledfirst;

                                $TOTAL_EXP =$TOTAL_EXP+$exposure;

                                 number_format($exp+$cl+$tobebilled+$tobebilledfirst,2);
                                //printing exposure value ends here
                                //echo '<br/>';
                                //echo $record['adv_val'];exit;
                                if($exp==0)
                                {
                                    $tobebilled2=0;
                                }
                                else
                                {
                                    $tobebilled2=round($exp-$record['billed'],2);
                                    $tobebilled2_without_tax = round(($tobebilled2/118)*100,2);
                                }
                                
                                $TO_BE_BILLED = $tobebilled2+$tobebilled+$tobebilledfirst;

                                $TOTAL_TO_BE_BILLED = $TOTAL_TO_BE_BILLED +$TO_BE_BILLED;
                                
                                ?>
                                </td> 
                                <td align="right" style="background: #EEE9E9;"><?php   number_format($tobebilled2+$tobebilled+$tobebilledfirst,2); ?></td>
                                
                                

                                
                            </tr> 
                        
                    
                    <?php } 
   

                ?>

            <div class="row">
                <div class="col-xs-6">                
                        <h2>Ticket By Source</h2>
                
                    <table class="table">
                        <thead>
                                <tr>
                                    <td>Ticket By Source</td>
                                    <td>Open</td>
                                    <td>Close</td>
                                    <td>Overdue (as on date)</td>
                                </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>Call</td>
                                    <td><?php if(!empty($ticket_by_source_call_array[0]['OPEN'])) {echo $ticket_by_source_call_array[0]['OPEN'];} else {echo 0;}?></td>
                                    <td><?php if(!empty($ticket_by_source_call_array[0]['CLOSE'])) {echo $ticket_by_source_call_array[0]['CLOSE'];} else {echo 0;}?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td colspan="3"><?php echo $ticket_by_source_email_array[0][0]['no_of_calls'];?></td>
                                    <!-- <td>10</td> -->
                                    <!-- <td>3</td> -->
                                </tr>
                                <tr>
                                    <td>Whatsapp</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                        </tbody>
                       
                    </table>               
                        
                </div> 
                
                <div class="col-md-6"  style="margin-top: 10px;">

                        <canvas id="myChart" style="width:100%;"></canvas>

                        <script>
                            var xValues = ["Abandan", "Total"];
                            var yValues = [<?php echo empty($Abandon)?0:$Abandon; ?>, <?php echo empty($Answered)?0:$Answered; ?>];
                            var barColors = [
                            "#2E8B57",
                            "#90EE90"
                            ];

                            new Chart("myChart", {
                            type: "pie",
                            data: {
                                labels: xValues,
                                fontColor: 'red',
                                datasets: [{
                                backgroundColor: barColors,
                                data: yValues,
                                
                                }],
                                
                            },
                            
                            options: {
                                title: {
                                display: true,
                                text: "Monthly Call Analysis",
                                fontColor: ['rgba(0,0,0)'],
                                fontSize:  16,
                                
                                }
                            }
                            });
                        </script>

                </div>

            </div>




        </div>
        
        <div class="container">
                <div class="row">
                    
                    <div class="col-md-12"  style="margin-top: 10px;">                       
                    
                        <canvas id="myChart4" style=""></canvas>
                    
                        <script>
                            var ctx = document.getElementById("myChart4").getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                
                                
                                labels: [<?php foreach($graph_date_wise as $dy) {print_r("'".date_format(date_create($dy[0]['gdate']),"d-M-Y")."',");} ?>],
                                datasets: [ {
                                            label: 'Abandon',
                                            backgroundColor: "#e84e40",
                                            data: [<?php foreach($graph_date_wise as $dy) {print_r($dy[0]['Abandon'].",");} ?>],
                                        }, {
                                            label: 'Answered',
                                            backgroundColor: "#8bc34a",
                                            data: [<?php foreach($graph_date_wise as $dy) {print_r($dy[0]['Answered'].",");} ?>],
                                        }
                                        ],
                                },
                            options: {
                                tooltips: {
                                    displayColors: true,
                                    callbacks:{
                                    mode: 'x',
                                    },
                                },
                                scales: {
                                    xAxes: [{
                                    stacked: true,
                                    display:true,
                                    gridLines: {
                                        display: false,
                                    },
                                    barThickness: 16,  // number (pixels) or 'flex'
                                    maxBarThickness: 18, // number (pixels)
                                    }],
                                    // yAxes: [{
                                    //   stacked: true,
                                    
                                    //   ticks: {
                                    //             min: 0,
                                    //             stepSize: 10,
                                    //             max:1000
                                    //         }
                                    
                                    //   ,
                                    //   type: 'linear',
                                    // }]


                                    yAxes: [{
                                    stacked: true,
                                    beginAtZero: true,
                                    ticks: {
                                                
                                                // count : 10,
                                                
                                                // callback:((value, index, ticks) => {
                                                // return value * 100 + '%'; // convert it to percentage
                                                // }),

                                                min: 0,
                                                max: this.max,// Your absolute max value
                                                callback: function (value) {
                                                return (value / this.max * 100).toFixed(0) + '%'; // convert it to percentage
                                                },


                                            },
                                            scaleLabel: {
                                                            display: true,
                                                            labelString: "Percentage"
                                                        }              
                                    ,
                                    type: 'linear',
                                    }]



                                },
                                responsive: true,
                                maintainAspectRatio: true,
                                legend: { position: 'bottom' },
                                }
                            });

                        </script>



                    </div>

                    <div class="col-md-6"  style="margin-top: 10px;">                        
                    
                        <canvas id="bar-chart-grouped1" ></canvas>

                            <?php $new_array = array("wk1","wk2","wk3","wk4","mtd");?>


                                    <?php $week_labels = "'".implode("','",array_keys($week_divider))."'";    ?> 
                                    
                                    
                        <script>
                            new Chart(document.getElementById("bar-chart-grouped1"), 
                            {
                                type: 'bar',
                                data: {
                                labels: [<?php echo $week_labels; ?>],
                                datasets: [
                                    <?php 
                                        // $color_master =array(1=>'#008000','2'=>'#006400',3=>'#90EE90',4=>'#90EE90',5=>'#90EE90');

                                        $color_master =array(1=>"#1E90FF",2=>"#4169E1",3=>"#90EE90",4=>"#006400",5=>"#008000", 6=>"#0074D9", 7=>"#FF4136", 8=>"#2ECC40", 9=>"#FF851B", 10=>"#7FDBFF", 11=>"#B10DC9", 12=>"#FFDC00", 13=>"#001f3f", 14=>"#39CCCC", 15=>"#01FF70", 16=>"#85144b", 17=>"#F012BE", 18=>"#3D9970", 19=>"#111111", 20=>"#AAAAAA");
                                        $week_count = 5; 
                                        $b = 1;
                                        foreach($category_arr as $category) 
                                        {
                                            $values_list = array();
                                            foreach($week_divider as $a) { $values_list[] = empty($week_arr[$category][$a])?0:$week_arr[$category][$a];}  ?>

                                            {
                                                label: "<?php echo $category;?>",
                                                backgroundColor: "<?php echo $color_master[$b++]; ?>",
                                                data: [ <?php echo implode(",",$values_list); ?> ]
                                            },

                                    <?php } ?>
                                    
                                ]
                                },
                                options: {
                                title: {
                                    display: true,
                                    text: 'Ticket Case Analysis',
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                },
                                    legend: { position: 'right' },
                                }
                            });
                        </script>

                    </div>
                

                    <div class="col-md-6"  style="margin-top: 10px;">

                        <canvas id="T_MTD" style="width:100%;"></canvas>                
                            <script>
                                var ctx = document.getElementById("T_MTD").getContext('2d');
                                var myChart_T_MTD = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Open","Close"],
                                    datasets: [{
                                        label: 'Today',
                                        backgroundColor: "#008000",
                                         data: [<?php foreach($tctm_today_row as $ttr) {print_r($ttr[0][0]['TOpen'].",".$ttr[0][0]['TClose']);};?>],
                                        //data: [345,789],
                                    }, {
                                        label: 'MTD',
                                        backgroundColor: "#90EE90",
                                        data: [<?php foreach($tctm_mtd_row as $tmr) {print_r($tmr[0][0]['TOpen'].",".$tmr[0][0]['TClose']);};?>],
                                        //data: [456,678],
                                    }
                                    ],
                                },
                                options: {
                                    tooltips: {
                                    displayColors: true,
                                    callbacks:{
                                    mode: 'x',
                                },
                                },
                                scales: {
                                xAxes: [{
                                    stacked: true,
                                    gridLines: {
                                    display: false,
                                    }
                                }],
                                yAxes: [{
                                    stacked: true,
                                    ticks: {
                                    beginAtZero: true,
                                    },
                                    type: 'linear',
                                }]
                                },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    legend: { position: 'bottom' },
                               
                                title: {
                                    display: true,
                                    text: 'Ticket Case Analysis',
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                },
                                    legend: { position: 'top' },
                                }
                            });

                            </script>


                    </div>            
                    
                    <div class="col-md-6"  style="margin-top: 10px;">
                            <?php //print_r($open_RecArr);?>
                            <canvas id="myChartopenTicket" ></canvas>

                            <script>
                                var xValues = ["In TAT", "Out of TAT"];
                                var yValues = [<?php  echo empty($open_RecArr[0][0]['openintat'])?'0':$open_RecArr[0][0]['openintat'];
                                    echo ",";
                                    echo empty($open_RecArr[0][0]['openouttat'])?'0':$open_RecArr[0][0]['openouttat'];?>];


                                
                                var barColors = [
                                "#1E90FF",
                                "#4169E1"
                                ];

                                new Chart("myChartopenTicket", {
                                type: "pie",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                    backgroundColor: barColors,
                                    
                                    data: yValues
                                    }]
                                },
                                options: {
                                    title: {
                                    display: true,
                                    text: "Open Ticket Analysis",
                                    fontColor: ['rgba(0,0,0)'],
                                    fontSize:  16,
                                    }
                                }
                                });
                            </script>
                    </div>
                   
                    <div class="col-md-6" style="margin-top: 10px;">                                    

                        <canvas id="myChartcloseTicket" ></canvas>

                        <script>
                            var xValues = ["In TAT", "Out of TAT"];
                            // var yValues = [<?php foreach($close_RecArr as $close_rec) { echo $close_rec[0][0]['intat'].",".$close_rec[0][0]['outtat'] ;};?>];
                            var yValues = [<?php  echo empty($open_RecArr[0][0]['intat'])?'0':$open_RecArr[0][0]['intat'];
                                    echo ",";
                                    echo empty($open_RecArr[0][0]['outtat'])?'0':$open_RecArr[0][0]['outtat'];?>];
                                    
                            var barColors = [
                            "#1E90FF",
                            "#4169E1"
                            ];

                            new Chart("myChartcloseTicket", {
                            type: "pie",
                            data: {
                                labels: xValues,
                                datasets: [{
                                backgroundColor: barColors,
                                data: yValues
                                }]
                            },
                            options: {
                                title: {
                                display: true,
                                text: "Close Ticket Analysis",
                                fontColor: ['rgba(0,0,0)'],
                                fontSize:  16,
                                }
                            }
                            });
                        </script>

                     </div>
                
                    
                </div>
        </div>
    
    <?php }?>

    <?php }else{?>

    <style>
        .clientmsg {
            padding: 20px;
            background-color: #2196f3; /* Red */
            color: white;
            margin-bottom: 15px;
        }
        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }
        .closebtn:hover {
            color: black;
        }
    </style>
   

         <div class="clientmsg">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            Your profile is under review by our team for further proceedings , We will notify you on same within 24 Hours. Please contact our sales representative or call us @ 001 - 61105555 to know more.
         </div>
                
        <?php }?>

<?php }?>



    
      
  


