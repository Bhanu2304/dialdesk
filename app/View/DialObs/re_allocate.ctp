<script>
function showPopup(id,aban,orde){ alert(aban);
    $("#Id").val(id);
    $("#AbandonText").val(aban);
    $("#OrderText").val(orde);  
}
    
function submitForm(form,path,id){
    var formData = $(form).serialize(); 
    $.post(path,formData).done(function(data){
        $("#"+id).trigger('click');
        $("#show-ecr-message").trigger('click');
        $("#ecr-text-message").text('Watsapp text updated successfully.');
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}
function check_all()
{$("#checkAll").on('change',function(){
    $('input:checkbox').prop('checked', this.checked);
});
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View Allocated Data</a></li>
    <li class="active"><a href="#">View Allocated Data</a></li>
</ol>
<div class="page-heading">            
    <h1>View Re-Allocate Data</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View UnAllocated Data</h2>
                <div class="panel-ctrls"></div>
            </div>
<div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('MobDatas',array('action'=>'re_allocate','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                        <select name="excode_assigned" id="excode_assigned" class="form-control" >
                            <option value="">-Select-</option>
                        <?php
                       // $i=1;
                        foreach($executive as $row1){?>
                         <option value="<?php echo $row1['Mob']['Code'];?>" <?php if($excode_assigned==$row1['Mob']['Code']) echo "selected";?> ><?php echo $row1['Mob']['Code'];?></option>   

                        <?php } ?>
                        </select>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="submit" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        
                        <!--
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        -->
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="panel-body no-padding">
            <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
            <?php echo $this->Form->create('MobDatas',array('action'=>'reallocate_code')); ?>

                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Customer Code</th>
                            <th>Firm Name</th>
                            <th>Phone 1</th>
                            <th>Phone 2</th>
                            <th>Owner Name</th>
                            <th>Address</th>
                            <th>Salesman Code</th>
                            <th>Action <input type="checkbox" id="checkAll" onclick="check_all()" ></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['mob_data']['custcode'];?></td>
                            <td><?php echo $row['mob_data']['custname'];?></td>
                            <td><?php echo $row['mob_data']['c_phone_1'];?></td>
                            <td><?php echo $row['mob_data']['c_phone_2'];?></td>
                            <td><?php echo $row['mob_data']['c_contact_person'];?></td>
                            <td><?php echo $row['mob_data']['adrress1'];?></td>
                            <td><?php echo $row['mob_data']['smancode'];?></td>
                            <td> 
                                <input type="checkbox" name="selectcode[]" value="<?php echo $row['mob_data']['Id'];?>">                  
                            </td>  
                        </tr>

                    <?php }?>
                   

                    </tbody>
                     <tr>
                    <td colspan="3">
                    <select name="excode" id="excode" class="form-control client-box" style="width:200px;">
                    <option value="">-Select-</option>
                    <?php
                       // $i=1;
                        foreach($executive as $row1){?>
                         <option value="<?php echo $row1['Mob']['Code'];?>"><?php echo $row1['Mob']['Code'];?></option>   

                        <?php } ?>
                    </select>
                    </td>
                    <td><input type="submit" class="btn btn-web" value="Allocate" ></td>
                    </tr>
                </table>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>


