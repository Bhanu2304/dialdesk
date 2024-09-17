<style>

/* .modal {

  top: -20% !important;

} */

</style>
<script>
  function showPopup(client_name)
  {
    $("#clientList1").empty();
    $("#clientList2").empty();
    var clients = client_name.split(', ').map(function(client) {
      return client.trim();
    });

    var column1 = $("<ul>").addClass("column");
    var column2 = $("<ul>").addClass("column");

    for (var i = 0; i < clients.length; i++) {
      var listItem = $("<li>").text(clients[i]);
      if (i < 16) {
        column1.append(listItem);
      } else {
        column2.append(listItem);
      }
    }
    
    $("#clientList1").append(column1);
    $("#clientList2").append(column2);

    
  
  }


    
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Bench Marking</a></li>
</ol>
<div class="page-heading">            
    <h1>Bench Marking</h1>
</div>


<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Bench Marking</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
            <?php //print_r($bnc_client_list); #$Benchmark=array("SKILL Against Forecast","Inbound Calls","AL - 95%","SL - 10 sec - 90%","SL - 20 sec - 80%","RL - 98%","CB - within 15 mins","Primary LOB","AHT","FTR - 75%","Quality Score - INBOUND","Primary LOB performance","Quality Score - OUTBOUND","Ticket Closure TAT","Login Time","Ready to Take Call time","No of  calls","ACHT","TAlk Time","Utilization","Primary LOV") ; ?>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                          <th>Sr. No.</th>
                            <th>Pointers</th>
                            <th>MTD</th>
                            <th>TDY</th>
                            <th>Green</th>
                            <th>Orange</th>
                            <th>Red</th> 
                            
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                          $data2['Inbound Calls']=$total_lbl_list['Answered']+$total_lbl_list['Abandon'];
                          //echo "{$dt[0][0]['Answered']}+{$dt[0][0]['Abandon']} <br/>";
                          $data2['Handled']=$total_lbl_list['Answered'];
                          $data2['Calls Ans (20 Sec)']=$total_lbl_list['WIthinSLA'];
                          $data2['Calls Ans (10 Sec)']=$total_lbl_list['WIthinSLATen'];
                          $data2['Total Calls Abandoned']=$total_lbl_list['Abandon'];
                          $data2['Abnd Within (20)']=$total_lbl_list['AbndWithinThresold'];
                          $data2['Average Aband Time']='';
                          $data2['Total Talk time']=$total_lbl_list['TalkTime'];
                          $data2['SL - 20 sec - 80%']=round($total_lbl_list['WIthinSLA']*100/$data2['Handled']);
                          $data2['SL - 10 sec - 90%']=round($total_lbl_list['WIthinSLATen']*100/$data2['Handled']);
                          //echo "{$dt[0][0]['WIthinSLATen']}*100/{$data['Handled']}";exit;
                          $data2['AL - 95%']=round($total_lbl_list['Answered']*100/$data2['Inbound Calls']);
                          //echo "{$dt[0][0]['Answered']}*100/{$data['Inbound Calls']}";exit;
                          $data2['AHT']=round($total_lbl_list['TotalAcht']/$total_lbl_list['Answered']);
                          $data2['ACHT'] = $total_lbl_list['TotalAcht'];
                          //$data2['Call Rate'] = $rate;
                          $data2['Amount'] = round($total_lbl_list['Abandon']*$rate_per_sec*round($total_lbl_list['TotalAcht']/$total_lbl_list['Answered']),2);
                          $data2['Utilization'] = round($data2['Productive']/$data2['Login Time']*100,2).'%';


                        $ik=1;
                     
                        foreach($benchmark_key as $k) {  ?>
                        <tr>
                            <td><?php echo $ik++; ?></td>
                            <td><?php echo $k;?></td>
                            <td></td>
                            <td><?php echo $data2[$k]; #echo $today_total[$k]['Today']; ?></td>
                            <td>
                              <?php if (count($bnc_client_list['green'][$k]) > 0): ?>
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo implode(', ', $bnc_client_list['green'][$k]); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised" style="color: white; background-color: green;"><?php echo count($bnc_client_list['green'][$k]); ?> <div class="ripple-container"></div></label></a>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (count($bnc_client_list['orange'][$k]) > 0): ?>
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo implode(', ', $bnc_client_list['orange'][$k]); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised" style="color: white; background-color: orange;"><?php echo count($bnc_client_list['orange'][$k]); ?> <div class="ripple-container"></div></label></a>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (count($bnc_client_list['red'][$k]) > 0): ?>
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo implode(', ', $bnc_client_list['red'][$k]); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised" style="color: white; background-color: red;"><?php echo count($bnc_client_list['red'][$k]); ?> <div class="ripple-container"></div></label></a>
                              <?php endif; ?>
                            </td>
                        </tr>

                    <?php }?>

                    </tbody>
                </table>
            </div>
            
            <div class="panel-footer"></div>
        </div>
    </div>
</div>


<div class="modal fade" id="catdiv5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Clients Name</h2>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <ul id="clientList1"></ul>
                </div>
                <div class="col-md-6">
                  <ul id="clientList2"></ul>
                </div>
              </div>   
            </div>
            <div class="modal-footer">
                <button type="button" id="close-login-popup" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




