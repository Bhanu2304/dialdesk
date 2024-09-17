<?php if($this->Session->read('role') =="admin"){
	$companyid=$this->Session->read('companyid');     
?>
<script>
    function viewClient(){
        $("#view_client_form").submit();	
    } 
</script>
<div style="margin-left:20px;">
    <div class="form-group">
<?php echo $this->Form->create('Homes',array('action'=>'index','id'=>'view_client_form')); ?>
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
</style> 
<style>
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
            <?php echo $this->Form->create('Homes');?>
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
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
 
              <div class="row ">
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
       
        <div class="col-md-6 col-sm-6">
            
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
