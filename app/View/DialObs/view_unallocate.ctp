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
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >View Unallocated Data</a></li>
    <li class="active"><a href="#">View Unallocated Data</a></li>
</ol>
<div class="page-heading">            
    <h1>View Unallocated Data</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>View Unallocated Data</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
            <?php echo $this->Form->create('MobDatas',array('action'=>'update_code')); ?>

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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo $row['Mobdata']['custcode'];?></td>
                            <td><?php echo $row['Mobdata']['custname'];?></td>
                            <td><?php echo $row['Mobdata']['c_phone_1'];?></td>
                            <td><?php echo $row['Mobdata']['c_phone_2'];?></td>
                            <td><?php echo $row['Mobdata']['c_contact_person'];?></td>
                            <td><?php echo $row['Mobdata']['adrress1'];?></td>
                            <td><?php echo $row['Mobdata']['smancode'];?></td>
                            <td> 
                                <input type="checkbox" name="selectcode[]" value="<?php echo $row['Mobdata']['Id'];?>">                  
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
                    <td><input type="Submit" class="btn btn-web" value="Allocate" ></td>
                    </tr>
                </table>
                <?php $this->Form->end(); ?>
            </div>
            <div class="panel-footer"></div>
        </div>
    </div>
</div>


