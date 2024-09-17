



<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <b><h2 style="color:green;"><?php echo $this->Session->Flash(); ?></h2></b>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body no-padding  scrolling ">
             <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Subject</th>
                    <th>Service Requirment</th>
                    <th>Monthly Call Inflow</th>
                    <th>Monthly Call Outflow</th>
                    <th>Remark</th>
                    <th>Screen Name</th>
                    <th>Date</th>
<!--                    <th>Action</th>-->
                </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($data as $row) {  ?>
                    <tr>
                <td><?php echo $row['ContactUs']['name']; ?></td>
                <td><?php echo $row['ContactUs']['company']; ?></td>
                <td><?php echo $row['ContactUs']['email']; ?></td>
                <td><?php echo $row['ContactUs']['address']; ?></td>
                <td><?php echo $row['ContactUs']['subject'] ?></td>
                <td><?php echo $row['ContactUs']['service'] ; ?></td>
                <td><?php echo $row['ContactUs']['monthlyCallInflow']; ?></td>
                <td><?php echo $row['ContactUs']['monthlyCallOutflow']; ?></td>
     
                <td><?php echo $row['ContactUs']['remark'] ?></td>
                <td><?php echo $row['ContactUs']['screen']; ?></td>
                 <td><?php echo $row['ContactUs']['createdate']; ?></td>
<!--                <td><a href="edit_plan?id=<?php //echo $plan['PlanMaster']['Id']; ?>">Edit</a></td>-->
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
           
        </div>
        <div class="panel-footer"></div>
    </div>
    
     
    </div>
</div>

 
