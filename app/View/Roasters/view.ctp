<?php ?>
<?php echo $this->Html->script('admin_creation'); ?>

 <ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View Roaster</a></li>
    <li class="active"><a href="#">View Roaster</a></li>
</ol>
<div class="page-heading">            
    <h1>View Roaster</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Roaster</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
     

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Name of Agent</th>
                            <th>Roaster Date</th>
                            <th>Shift Start Time</th>
                            <th>Shift End Time</th>
                            <th>Working Hour</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                        
                            <td><?php echo $row['Roaster']['Agent'];?></td>
                            <td><?php echo date_format(date_create($row['Roaster']['roasterdate']),'d-m-Y');?></td>
                            <td><?php echo $row['Roaster']['shiftstarttime'];?></td>
                            <td><?php echo $row['Roaster']['shiftendtime'];?></td>
                            <td><?php echo $row['Roaster']['working_hour'];?></td>
                    
                           
                        </tr>

                    <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
    </div>
