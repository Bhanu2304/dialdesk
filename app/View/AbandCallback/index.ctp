<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<style>

.form-control::placeholder
{
    /* font-weight: bold !important; */
    font-weight: normal !important;
    display: block;
    color:black !important;
    white-space: nowrap;
    min-height: 1.2em;
    padding: 0px 2px 1px;

}

</style>
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
    $(".date-picker1").timepicker({  dynamic: true,
         timeFormat: 'HH:mm',
          dropdown: true,
          scrollbar: true,
         interval:1
    });
});

</script>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Aband Call Setting</a></li>
</ol>
<div class="page-heading">                                           
<h1>Aband Call Setting</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
            <h2 style="color: green;"><?php echo $this->Session->flash(); ?></h2>
                
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                <?php echo $this->Form->create('AbandCallback',array("class"=>"form-horizontal")); ?>
                    <div class="form-group">
                    
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('client_id',array('label'=>false,'class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('start_time',array('label'=>false,'class'=>'form-control date-picker1','PlaceHolder'=>'Select Start Time','autocomplete'=>'off','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('end_time',array('label'=>false,'class'=>'form-control date-picker1','PlaceHolder'=>'Select End Time','autocomplete'=>'off','required'=>true)); ?>
                        </div>
                        <!-- <label class="col-sm-2">Call with in 10 minutes</label> -->
                        <!-- <div class="col-sm-4">
                            <input type="radio" name="aband_status" value="Yes" required> Yes &nbsp
							<input type="radio" name="aband_status" value="No"> No	

                        </div> -->
                        <div class="col-sm-3">
                        <?php $time_slot = ['1' => '1', '2' => '2','5' => '5','10' => '10','15' => '15','20' => '20','25' => '25','30' => '30','35' => '35','40' => '40','45' => '45','50' => '50','55' => '55','60' => '60','65' => '65','70' => '70','75' => '75','80' => '80','85' => '85','90' => '90','95' => '95','100' => '100'];?>
                        <?php echo $this->Form->input('aband_status',array('label'=>false,'class'=>'form-control','options'=>$time_slot,'empty'=>'Select call with in minutes','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                           <input type="submit" class="btn-web btn" value="Submit">
                        </div>					
                     
                    </div>
                   
                    
                
                <?php echo $this->Form->end(); ?>
                
                
            </div>
        </div>
        <?php  if(!empty($data_aband)){?>
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
                                                            <th>Client Name</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Aband Call in Minutes</th>
                                                            <th>Create Date</th>
                                                            <th>Action</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($data_aband as $d): $id = $d['aband_call_time']['id']; ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $d['0']['clientname']; ?></td>
                                                                    <td><?php echo $d['aband_call_time']['start_time']; ?></td>
                                                                    <td><?php echo $d['aband_call_time']['end_time']; ?></td>
                                                                    <td><?php echo $d['aband_call_time']['aband_status']; ?></td>
                                                                    <td><?php echo date_format(date_create($d['aband_call_time']['created_at']),'d M Y'); ?></td>
                                                                    <td>
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>AbandCallback/delete_aband?id=<?php echo $id;?>')">
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                                                        </a>
                                                                    </td>  
                                                                </tr>
                                                            <?php endforeach; ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                          

       
</div>
<?php }?>


