<!-- <script>
          $( function() {
            $( ".date-picker1" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: '-100:+0' });
            
          });
          </script> -->
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
            $(function () {
                $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
                $(".date-picker1").on('change',function () {
                    //var date = Date.parse($(this).val());
                    var sel_date = document.getElementById('fdate').value;
                    var sel_arr = sel_date.split('-');
                    var new_sel_arr = sel_arr[2]+'/'+sel_arr[1]+'/'+sel_arr[0]; 

                    var js_selecteddate = new Date(new_sel_arr);   

                    //alert(js_selecteddate);
                    var date = new Date();

                    if (js_selecteddate > date) {
                        alert('Selected date must be Smaller than today date');
                        $(this).val('');
                    }
                });
            });
          </script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AgentCreations">Agent Creation</a></li>
</ol>
<div class="page-heading">            
    <h1>Agent Creation</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Create Agent</h2>
            </div>
            <div class="panel-body">
                <font style="color:red;"><?php echo $this->Session->flash(); ?></font>
               
        <?php echo $this->Form->create('AgentCreations',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>

                <div class="col-md-16">
                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            
                            <?php echo $this->Form->input('displayname',array('label'=>false,'placeholder'=>'Name of Agent','class'=>'form-control','required'=>true,'autocomplete'=>false, 'addslashes'=>true));?>
                        </div>
                    </div>
 
                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('Agentid',array('label'=>false,'placeholder'=>'Login Id','class'=>'form-control','required'=>true ,'autocomplete'=>'off', 'addslashes'=>true ));?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('Agentpassword',array('label'=>false,'placeholder'=>'Password','autocomplete'=>false,'class'=>'form-control','required'=>true ));?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php //$options = ['Dialdesk' => 'Dialdesk', 'Wiom' => 'Wiom', 'Temporary' => 'Temporary'];?>
                            <?php $options = ['Dialdesk' => 'Dialdesk', 'Dialdesk Support' => 'Dialdesk Support','Others' => 'Others'];?>
<?php echo $this->Form->input('processname',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Process Name','id'=>'processname','required'=>true ));?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                            </span> 
                           
 <?php echo $this->Form->input('dateofjoining',array('label'=>false,'autocomplete'=>false,'placeholder'=>'Date of Joining','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1'));?>
                     

        
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                            </span> 
                            <?php $options = ['Work From Home' => 'Work From Home', 'Work From Office' => 'Work From Office'];?>
<?php echo $this->Form->input('workmode',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Work Mode','id'=>'workmode','required'=>true ));?>
         
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('address',array('label'=>false,'placeholder'=>'Enter Address ','class'=>'form-control' ,'id'=>'address', 'addslashes'=>true ));?>
                        </div>
                    </div>
              

                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                            </span> 
                            <?php $options = ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'];?>
                            <?php echo $this->Form->input('category',array('label'=>false,'type'=>'select','options'=>$options,'class'=>'form-control','empty'=>'Category','id'=>'category','required'=>true ));?>
        
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">							
                            <span class="input-group-addon">
                            <i class="ti ti-user"></i>
                            </span> 
                            <?php echo $this->Form->input('email_id',array('label'=>false,'type'=>'email','class'=>'form-control','placeholder'=>'Email','id'=>'email_id','required'=>true ));?>
        
                        </div>
                    </div>
                </div>
            </div>
                    
        </div>
                </div>
</div>

                <div class="col-md-12" style="margin-top:10px;">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input type="submit" class="btn btn-web pull-left" value="Submit" >
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
    </div>
</div>
