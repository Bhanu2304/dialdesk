<script>
    function isDecimalKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
    
        if ((charCode> 31 && (charCode < 48 || charCode > 57)))
            return false;
        
        return true;
    }

</script>
<script>
$(function () {
    $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});

</script>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Mgt</a></li>
    <li class="active"><a href="#">Call Summary Report Automation</a></li>
</ol>
<div class="page-heading">                                           
<h1>Call Summary Report Automation</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
		<h2>Add</h2>
        <br/>
                <h2 style="color: green;"> <?php echo $this->Session->flash(); ?></h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('ScenarioAutomates',array("class"=>"form-horizontal","action"=>"call_summary_in")); ?>
                    <div class="form-group">
                    
                    <label class="col-sm-2 control-label">Select Client</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('client',array('label'=>false,'class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        <label class="col-sm-2 control-label">To</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('to',array('label'=>false,'placeholder'=>'To','autocomplete'=>'off','required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                        <label class="col-sm-2 control-label">CC</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('cc',array('label'=>false,
                                'placeholder'=>'CC','required'=>true,'class'=>'form-control'));?>
                        </div>
                    
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Remarks</label> 
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('remarks',array('type'=>'textarea','label'=>false,'placeholder'=>'Remarks','required'=>true,'class'=>'form-control','rows'=>3));?>

                        </div>
                    
                        <div class="col-sm-2"></div><div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="Submit">
                        
                        </div>
                    
                        
                    </div>
                   
           
                
                    
                    
                
                <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
         <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline">
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                            <th>S.No</th>
                                                            <th>Client</th>
                                                            <th>To</th>
                                                            <th>Cc</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($scen_data as $d): $id = $d['ScenarioAutomate']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $d['ScenarioAutomate']['client_name']; ?></td>
                                                                    <td><?php echo $d['ScenarioAutomate']['to']; ?></td>
                                                                    <td><?php echo $d['ScenarioAutomate']['cc']; ?></td>
                                                                    <td><?php echo $d['ScenarioAutomate']['remarks']; ?></td>
                                                                    <td>
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ScenarioAutomates/delete_alert3?id=<?php echo $id;?>')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

                                                    </tbody>
                                                </table>
                                            </div>

       
</div>


