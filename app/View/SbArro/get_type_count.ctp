<div class="scenario">Sub Scenario <?php echo $subscenarioname;?></div>

<script type="text/javascript">    
    <?php $j=0; ?>
        
                                google.setOnLoadCallback (<?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']);?>Chart);
                                function <?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']);?>Chart() {
                                    var dataTable = new google.visualization.DataTable();
                                    dataTable.addColumn('string','Quarters 2009');
                                    dataTable.addColumn('number', 'Calls');
                                    dataTable.addRows([
                                        <?php
                                $type = explode(',',$data[$j]['count']);
                                foreach($type as $k)
                                {
                                    $split = explode('@@',$k); ?>
                                            ['<?php echo $split[0];?>',<?php echo $split[1];?>],
                                        <?php }?>
                                    ]);
                                    var chartId<?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']);?> = new google.visualization.PieChart (document.getElementById('chartId<?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']);?>'));
                                    var options = {width: 400, height: 280, is3D: true, title: '',
                                    colors: ['#3f51b5', '#e84e40', '#03a9f4', '#f3b49f', '#f6c7b6']};
                                    chartId<?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']);?>.draw(dataTable, options);
                                }
                            </script>
                               
                                <div id='chartId<?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']);?>' style="margin-left:15px;"></div>
                                
                
                            <script>
                                <?php echo preg_replace("/\W+/", '', $data[$j]['ecrName']).'Chart();'; ?>
                            </script>
                            
                            
                            <div class="wrap">
                                <table class="head">
                                    <tr>
                                        <td>SNo</td>
                                        <td>Sub Scenario 2</td>
                                        <td>Count</td>
                                        <td>%</td>
                                    </tr>
                                </table>
                                <div class="inner_table">
                                    <table>
                                    <?php
                                $type = explode(',',$data[$j]['count']);
                                $sr=1;
                                foreach($type as $k)
                                {
                                    $split = explode('@@',$k);
                                ?>
                                    <tr>
                                        <td><?php echo $sr++; ?></td>
                                        <td><span style="cursor:pointer;  text-decoration: underline;"><?php echo $split[0];?></span></td>
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
                                    <th align="left">SNo</th>
                                    <th align="left">Sub Scenario 2</th>
                                    <th align="left">Count</th>
                                    <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;%&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            <?php
                                $type = explode(',',$data[$j]['count']);
                                $sr=1;
                                foreach($type as $k)
                                {
                                    $split = explode('@@',$k);
                            ?>
                                <tr>
                                    <td align="left"><?php echo $sr++; ?></td>
                                    <td align="left"> <span style="cursor:pointer;  text-decoration: underline;"><?php echo $split[0];?></span></td>
                                    <td align="left"><?php echo $split[1]; ?></td>
                                    <td align="left"><?php echo round(($split[1]*100)/$Total).'%'; ?></td>
                            </tr>
                            <?php    } ?>
                            </table>
                            </div>
                            -->
                            
                            
                            

                            <?php //print_r($data); ?>