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
                            <th>Time</th>
                            <th>Green</th>
                            <th>Orange</th>
                            <th>Red</th> 
                            
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                          


                        $ik=1;
                     
                        foreach($ben_list as $k) {  ?>
                        <tr>
                            <td><?php echo $ik++; ?></td>
                            <td><?php echo $k;?></td>
                            <td></td>
                           
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

                    <?php foreach($ben_ag_list as $k) {  ?>
                        <tr>
                            <td><?php echo $ik++; ?></td>
                            <td><?php echo $k;?></td>
                            <td></td>
                           
                            <td>
                              <?php if (count($bnc_agent_list['green'][$k]) > 0): ?>
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo implode(', ', $bnc_agent_list['green'][$k]); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised" style="color: white; background-color: green;"><?php echo count($bnc_agent_list['green'][$k]); ?> <div class="ripple-container"></div></label></a>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (count($bnc_agent_list['orange'][$k]) > 0): ?>
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo implode(', ', $bnc_agent_list['orange'][$k]); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised" style="color: white; background-color: orange;"><?php echo count($bnc_agent_list['orange'][$k]); ?> <div class="ripple-container"></div></label></a>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (count($bnc_agent_list['red'][$k]) > 0): ?>
                                <a href="#" data-toggle="modal" data-target="#catdiv5" onclick="showPopup('<?php echo implode(', ', $bnc_agent_list['red'][$k]); ?>')" > <label class="btn btn-xs btn-midnightblue btn-raised" style="color: white; background-color: red;"><?php echo count($bnc_agent_list['red'][$k]); ?> <div class="ripple-container"></div></label></a>
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
                <h2 class="modal-title">Details</h2>
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




