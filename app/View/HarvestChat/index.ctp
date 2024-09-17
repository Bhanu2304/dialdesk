<?php echo $this->Html->script('assets/main/dialdesk');?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>	
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/jsapi"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>js/google_chart/loader.js"></script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <!-- <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Training Docs</a></li> -->
</ol>
<div class="page-heading">                                           
<h1>Dashboard</h1>
<div class="col-md-3">  </div>
<div class="col-md-3">  
                 <canvas id="closure_status" ></canvas>
                           <script>
                              var xValues = ["Called", "Not Called"];
                              var yValues = [<?php  echo empty($Closure_status[0][0]['SATISFIED'])?'3':$Closure_status[0][0]['SATISFIED'];
                                 echo ",";
                                 echo empty($Closure_status[0][0]['NOTSATISFIED'])?'4':$Closure_status[0][0]['NOTSATISFIED'];?>];
                              
                              
                              
                              var barColors = [
                              "#3498DB",
                              "#F4D03F"
                              ];
                              
                              new Chart("closure_status", {
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
                                 text: "Ticket Chart",
                                 fontColor: ['rgba(0,0,0)'],
                                 fontSize:  16,
                                 }
                              }
                              });
                           </script>
                 </div>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        
        
             
            <div class="panel panel-default" id="panel-inline">
                <div class="panel-heading">
                    <h2>Dashboard</h2>
                    <div class="panel-ctrls"></div>
                </div>
                
                <div class="panel-body no-padding scrolling">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="exap">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Date</th>
                                <th>Issue</th>
                                <th>Kitty</th>
                                <th>HG</th>
                                <th>Modern</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;foreach($data as $post){
                                   if(!empty($post['dw']['scenario']))
                                   {?>
                            <tr>
                               
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date_format(date_create($post['dw']['created_at']),'d M Y H:i:s'); ?></td>
                                <td><?php echo $post['dw']['scenario']; ?></td>
                                <td><?php echo $post['0']['Kitty']; ?></td>
                                <td><?php echo $post['0']['HG'];?></td>
                                <td><?php echo $post['0']['Modern']; ?></td>
                                <?php $total = $post['0']['Kitty'] + $post['0']['HG'] + $post['0']['Modern']; ?>
                                <td><?php echo $total; ?></td>
                                
                            </tr>
                            <?php }
                              }?>
                        </tbody>
                    </table>
                </div>

                
                <div class="panel-footer">
                    
                </div>
            </div>
        
    </div>
</div>

<!-- <script>

        $(document).ready(function () {
            $('#DataTables_Table_0').DataTable({
                "ordering": false
            });
        });
</script> -->

<script>
    $(document).ready(function () {
    $('#exap').DataTable({
        destroy: true,
        searching: false,
        paging: false,
        ordering: false
    });
});
</script>