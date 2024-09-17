<style>
    .addremove{
        position: relative; 
        /*left: 300px;*/
        margin-left:105%;
        top: -48px;  
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
    var maxField = 20;
    var addButton = $('.add_button');
    var wrapper = $('.field_wrapper');
    var fieldHTML = '<div style="margin-top:-27px;"><input type="text" name="field_name[]" placeholder="Field Name" class="form-control field_name" required  /><a href="javascript:void(0);" class="remove_button addremove" title="Remove field"> <label  class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-minus"></i></label> </a></div>'; //New input field html 
    var x = 1;
    $(addButton).click(function(){
        if(x < maxField){
                x++;
                $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
});

function validate_campaign(){
    var elem = document.getElementsByClassName("field_name");
    var names = [];
    for (var i = 0; i < elem.length; ++i) {
        if (typeof elem[i].value !== "undefined") {
           if(elem[i].value ==elem[i+1].value){
              $("#erm").text('Please remove duplicate field name');
              return false;
           }
        }
    }
}
  


</script>    

<ol class="breadcrumb">                                
        <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
        <li><a href="#">Out Call Management</a></li>
        <li class="active"><a href="#">Manage Campaigns</a></li>
    </ol>
    <div class="page-heading">            
        <h1>Manage Campaigns</h1>
    </div>
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Manage Campaigns</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
               		
                    <?php echo $this->Form->create('Outbounds',array('action'=>'add_campaign','enctype'=>'multipart/form-data','onsubmit'=>'return validate_campaign()','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>	
                        <div class="form-group">
                            <div class="col-sm-6">      
                                <div id="erm" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-5">      
                                <?php	echo $this->Form->input('CampaignName',array('label'=>false,'placeholder'=>'Campaign Name','autocomplete'=>'off','class'=>'form-control','required'=>true ));?>
                                <span>( First field name create for Phone No )</span>
                            </div>
                        </div>
                    <div class="form-group">
                        <div class="col-sm-5">   
                            <div class="field_wrapper">
                                <div>                                       
                                    <input type="text" name="field_name[]" placeholder="Field Name" class="form-control field_name" required  />
                                    <a href="javascript:void(0);" class="add_button addremove" title="Add field"><label  class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-plus"></i></label></a> 
                                </div>
                            </div>
                        </div>
                     </div>
                    
                                
                    
                        
                            
                        <!--
                        <div class="uploadfile">
                            
                        	<label class="col-sm-3 control-label">Upload Campaign Header</label>
                        	<div class="col-sm-6">  
                                    
                      			<?php //echo $this->Form->file('File.',array('label'=>false,'type'=>'file','id'=>'addon3','multiple'=>false,'required'=>true));?>
                                        <span>(Upload Only CSV File)</span>
                         	</div>
                     	</div>
                        -->
                    
                       

                     	<div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="btn-toolbar">
                                        <input type="submit" class="btn btn-web" value="Submit" >
                                    </div>
                                </div>
                            </div>
                    	</div>
                	<?php echo $this->Form->end(); ?>
          		</div> 
            </div>
        </div>
    </div>


       <?php if(!empty($campaign_header)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Campaign</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>CAMPAIGN</th>
                            <th>FIELD</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;foreach($campaign_header as $row){?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $row['campaign'];?></td>
                                <td><?php echo implode(",",$row['field']);?></td>   
                                <td class="center">
                                    <a title="Download"  href="<?php echo $this->webroot;?>ObImports/download?id=<?php echo $row['campaignid'];?>">
                                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-download"></i></label>
                                    </a> 
                                    <a href="#" title="Delete" onclick="deleteData('<?php echo $this->webroot;?>Outbounds/delete_campaign?id=<?php echo $row['campaignid'];?>')" >
                                        <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                    </a>
                                </td>  
                            </tr>
                        <?php }?>

                    </tbody>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>


