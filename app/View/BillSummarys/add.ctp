<?php ?>
<script> 
function viewReport(type){
    $("#loadingimg").show();
    $("#showCallReport").hide();
    
    var fdate=$("#FromDate").val();
    var ldate=$("#ToDate").val();
    
    $.post('<?php echo $this->webroot?>BillSummarys/view_report',{fdate:fdate,ldate:ldate},function(data){
       $("#showCallReport").show();
       $("#ReportDetails").html(data);
       $("#loadingimg").hide();
    });  
    
    /*

    if(fdate ==""){
       $("#FromDate").focus();
       alert('Please select first date.');
       return false;
    }
    else if(ldate ==""){
       $("#ToDate").focus();
       alert('Please select last date.');
       return false;
    }
    else{
        $.post('<?php echo $this->webroot?>BillSummarys/view_report',{fdate:fdate,ldate:ldate},function(data){
           $("#showCallReport").show();
           $("#ReportDetails").html(data);
           $("#loadingimg").hide();
        });   
    }*/  
}
</script>

<style>
.table-scroll table {
    display: flex;
    flex-flow: column;
    height:470px;;
}

.table-scroll table thead {
    flex: 0 0 auto;
    width: calc(100% - 0.9em);
}

.table-scroll table tbody {
    flex: 1 1 auto;
    display: block;
    max-height: 300%;
    overflow-y: scroll;

}

.table-scroll table tbody tr {
    width: 100%;
}

.table-scroll table thead,.table-scroll table tbody tr {
    display: table;
    table-layout: fixed;
}
</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Report</a></li>
    <li class="active"><a href="#">Add Bill Summary Auto Mail</a></li>
</ol>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Add Bill Summary Auto Mail</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body" >
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
                <?php echo $this->Form->create('BillSummarys',array('action'=>'add','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('Name',array('label'=>false,'placeholder'=>'Name','class'=>'form-control','required'=>true ));?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('email',array('label'=>false,'placeholder'=>'Email','class'=>'form-control','required'=>true ));?>
                        </div>
                    </div>
                
                    <div class="form-group">     
                        <div class="col-sm-4" style="margin-top:-10px;">
                           <input type="submit" class="btn btn-web"  value="Submit">
                        </div>
                    </div>
                
                <?php echo $this->Form->end(); ?>  
            </div>
        </div>
        <?php if(!empty($data)){?>
        <div id="showCallReport" >
            <div class="panel panel-default" data-widget='{"draggable": "false"}'  >
                <div class="panel-heading">
                    <h2>View Bill Summary Auto Mail</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                
            <?php foreach($data as $row){?>	
                <tr>
                    <td><?php echo $row['BillSummaryAutoMailMaster']['Name'];?></td>
                    <td><?php echo $row['BillSummaryAutoMailMaster']['email'];?></td>
                    <td>
                        <a href="<?php echo $this->webroot;?>BillSummarys/delete?id=<?php echo $row['BillSummaryAutoMailMaster']['Id']?>" onclick="return confirm('Are you sure you want to delete this item?')" >
                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                        </a> 
                    </td>
                </tr>
            <?php } ?>
            </tbody>                    
        </table>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</div>
