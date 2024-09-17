<?php  ?>
<script>
    $(document).ready(function()
    {
        $("#InitialInvoiceBranchName").on('change',function()
        {
            
            var branch = $('#InitialInvoiceBranchName').val();
            $.post("InitialInvoices/get_costcenter",
            {
                branch : branch
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                
                $("#InitialInvoiceCostCenter").empty();
                $("#InitialInvoiceCostCenter").html(text);
            });  
     });
     $("#InitialInvoiceInvoiceDate").blur(function()
        {
            var str = $("#InitialInvoiceInvoiceDate").val().split("-").reverse().join("-");
            var date1 = new Date(str);
            var date2 = new Date("2017-07-01");
            if(date1>=date2)
            {
                $("#InitialInvoiceApplyKrishiTax").prop("checked",false);
                $("#InitialInvoiceApplyKrishiTax").attr("disabled","disabled");
                $("#InitialInvoiceApplyGst").attr("disabled",false);
            }
            else
            {
                $("#InitialInvoiceApplyKrishiTax").prop("checked",false);
                $("#InitialInvoiceApplyKrishiTax").attr("disabled",false);
                $("#InitialInvoiceApplyGst").prop("checked",false);
                $("#InitialInvoiceApplyGst").attr("disabled",true);
                $("#GSTTYPEID").hide();
            }
        });
    });
    
    function get_gst_pop_up()
    {
        var cost = $('#InitialInvoiceCostCenter').val();
        $.post("InitialInvoices/get_service_no",
            {
                cost_center : cost
            },
            function(data,status){
                 var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                
                $("#InitialInvoiceServNo").empty();
                $("#InitialInvoiceServNo").html(text);
            });
        
            $.post("InitialInvoices/get_gst_type",
            {
                cost_center : cost
            },
            function(data,status){
                if(data=='1')
                {
                    return true;
                }
                else
                {
                    $("#GSTTYPEID").show();
                    //$("#InitialInvoiceServNo").attr("disabled",false);
                    //window.open(""+'<?php //echo $this->Html->url("get_pop_up"."', '_blank', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,resizable=1,width=500,height=500'");?>');
                    
                }
            });
          
        return false;
    }
    
    function serve_enable()
    {
        $("#InitialInvoiceServNo").attr("disabled",false);
    }
    function serve_disable()
    {
        $("#InitialInvoiceServNo").attr("disabled",true);
        $("#InitialInvoiceServNo").val("");
    }
</script>

<?php echo $this->Form->create('InitialInvoice',array('action'=>'add','onsubmit'=>"return get_revenue_validate()")); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Billing Management</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Billing">Create Invoice</a></li>
</ol>
<div class="page-heading">            
    <h1>Create Invoice</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            
            <div class="panel-body" style="margin-top:-10px;">
	        <h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
		<div class="col-md-12">
                <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Branch</span>
                                <?php $data=array(); foreach ($branch_master as $post): 
                         $data[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name']; 
                         endforeach; ?><?php unset($Addbranch); 
                         echo $this->Form->input('branch_name', array('options' => $data,'empty' => 'Select','label' => false, 'div' => false,'class'=>'form-control')); ?>
                                
                           </div>
                        </div>
                
                <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Cost Center</span>
                                <?php	echo $this->Form->input('cost_center', array('label'=>false,'class'=>'form-control','options' 
                                => '','empty' => 'Cost Center','required'=>true)); ?>
                                
                           </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Finance Year</span>
                                <?php	echo $this->Form->input('finance_year', array('label'=>false,'class'=>'form-control','options' 
=> $finance_yearNew,'empty' => 'Select','required'=>true)); ?>
                                
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Month</span>
                                <?php 
                        $data=array(
			'Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
                        'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
                    ?>
                    <?php   echo $this->Form->input('month', array('label'=>false,'class'=>'form-control','options' => 
$data,'empty' => 'Month','required'=>true,'onChange'=>'getDescription(this);get_provision(this.value)')); ?>
                                
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Invoice Date</span>
                                <?php	echo $this->Form->input('invoiceDate', array('label'=>false,'class'=>'form-control date-picker','placeholder'=>'Date',
                        'onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');",'onBlur'=>'','required'=>true,'readonly'=>true)); ?>
                                
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Category</span>
                                <?php	echo $this->Form->input('category', array('label'=>false,'class'=>'form-control','value'=>'Talk Time',
                        'options'=>array("Talk Time"=>"Talk Time",'Subscription'=>'Subscription',"Setup Cost"=>"Setup Cost",'Topup'=>'Top up'),'required'=>true)); ?>
                                
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Apply Tax Calculation</span>
                                <br>
                                <div class="checkbox-inline" ><label>
                    <?php	echo $this->Form->checkbox('app_tax_cal', array('label'=>false,'checked'=>true,readonly=>true)); ?></label></div>(check for Yes)
                    </div>
                                
                           </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">GST TYPE</span><br>
                                <input type="radio" name="GSTType" onclick="serve_enable()" value="Integrated"> Integrate
                       <input type="radio" name="GSTType" onclick="serve_disable()" value="Intrastate"> IntraState
                                
                           </div>
                        </div>
                        
                    </div>
                <div class="col-md-12">
                        
                    
                    
                    <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>            
                                <button type="submit"  class="btn btn-success btn-label-left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Next</b></button>
                                
                           </div>
                        </div>
                        </div>
            </div>
	</div>
    </div>
</div>
	<?php	echo $this->Form->input('invoiceType', array('label'=>false,'type'=>'hidden','class'=>'form-control',
                        'value'=>"Revenue",'required'=>true)); ?>
<?php echo $this->Form->end(); ?>
<script>
function get_provision(month)
{
    var year = $('#InitialInvoiceFinanceYear').val();
    var branch = $('#InitialInvoiceBranchName').val();
    var cost_center = $('#InitialInvoiceCostCenter').val();
    $.post("InitialInvoices/get_provision_months",
    {
     year: year,
     month:month,
     branch:branch,
     cost_center:cost_center
    },
    function(data,status){
        $("#month_selection").html(data);

    });  

}
    
    function get_display(dispId)
    {
        
        
        if($('#' + dispId).is(":checked"))
        {
            $('#'+dispId+'Disp').show();
        }
        else
        {
            $('#'+dispId+'Disp').hide();
        }
        get_revenue_total();
    }
    
    function get_revenue_total()
    {
        
       var total = 0;
       var idvalue =0;
       var month_select = $('#month_check').val();
       
       var str_month_arr = month_select.split(",");
       
       for(var i=0; i<str_month_arr.length; i++)
       {
          
            var mnt = str_month_arr[i];
            
              if($('#' + mnt).is(":checked"))
              {
                  idvalue =  $('#input'+mnt).val();  
                  total += parseInt(idvalue);
              }
       }
       
       
       
       $('#Total').html(total);
    }
    
    
    
    
    
</script>