<script>
function getClient(){
    $("#campaign_base_ecr_form").submit();	
} 
   
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
  return true;
}

$(document).ready(function(){
    $("#campaign1").on('change',function(){
        $.post("AdminObecrs/get_label1",{parent_id : $('#campaign1').val(),type : 'parent1'},function(data,status){
            $('#parent1').replaceWith(data);
        });
    });
    
    $("#campaign2").on('change',function(){
        $.post("AdminObecrs/get_label1",{parent_id : $('#campaign2').val(),type : 'parent2'},function(data,status){
            $('#parent2').replaceWith(data);
            $('#type1').replaceWith('<select name="data[AdminObecrs][parent]" id="type1" class="form-control" required="required"></select>');
            $('#sub_parent1').replaceWith('<select name="data[AdminObecrs][sub_parent1]" id="sub_parent1" class="form-control" required="required"></select>');
        });
    });
    
    $("#campaign3").on('change',function(){
        $.post("AdminObecrs/get_label1",{parent_id : $('#campaign3').val(),type : 'parent3'},function(data,status){
            $('#parent3').replaceWith(data);
            $('#type2').replaceWith('<select name="data[AdminObecrs][parent]" id="type2" class="form-control" required="required"></select>');
            $('#sub_type1').replaceWith('<select name="data[AdminObecrs][parent3]" class="form-control" id="sub_type1"></select>');
	});
    });
    
    $("#campaign4").on('change',function(){
	$.post("AdminObecrs/get_label1",{parent_id : $('#campaign4').val(),type : 'parent4'},function(data,status){
            $('#parent4').replaceWith(data);
            $('#sub_type2').replaceWith('<select name="data[AdminObecrs][sub_type1]" id="sub_type2" class="form-control" required="required"></select>');
            $('#type3').replaceWith('<select name="data[AdminObecrs][parent]" id="type3" class="form-control" required="required"></select>');
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
        });
    });
     
    $(document).on("change","#parent2", function() {
	$.post("AdminObecrs/get_label2",{parent_id : $('#parent2').val(),type : 'type1'},function(data,status){
            $('#type1').replaceWith(data);
	});
    });
    
    $(document).on("change","#parent3", function() {
        $.post("AdminObecrs/get_label2",{parent_id : $('#parent3').val(),type : 'type2'},function(data,status){
            $('#type2').replaceWith(data);
            $('#sub_type1').replaceWith('<select name="data[AdminObecrs][parent3]" class="form-control" id="sub_type1">');
        });
    });
    
    $(document).on("change","#parent4", function() {
        $.post("AdminObecrs/get_label2",{parent_id : $('#parent4').val(),type : 'type3'},function(data,status){
            $('#type3').replaceWith(data);
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
        });
    });
    
    $(document).on("change","#type2", function() {
        $.post("AdminObecrs/get_label3",{parent_id : $('#type2').val(),type : 'sub_type1'},function(data,status){
            $('#sub_type1').replaceWith(data);
        });
    });
    
    $(document).on("change","#type3", function() {
        $.post("AdminObecrs/get_label3",{parent_id : $('#type3').val(),type : 'sub_type2'},function(data,status){
            $('#sub_type2').replaceWith(data);
        });
    });
    
    $(document).on("change","#sub_type2", function() {
        $.post("AdminObecrs/get_label4",{parent_id : $('#sub_type2').val(),type : 'sub_type2_2'},function(data,status){
            $('#sub_type2_2').replaceWith(data);
        });
    });
    
});
</script>

<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Create ECR</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <?php echo $this->Form->create('AdminObecrs',array('action'=>'index','id'=>'campaign_base_ecr_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?>
                
                <?php if(isset($clientid) && !empty($clientid)){ ?>
                <div style="width: 100%;overflow: scroll;" >
                <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                    <tr>
                        <td>
                            <?php echo $this->Form->create('AdminObecrs',array('action'=>'create_category')); ?>
                                <h6>Create Category</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td><?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','class'=>'form-control','required'=>true ));?></td>                                                                                    
                                    </tr>
                                    <tr>                                         
                                        <td><?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'autocomplete'=>'off','required'=>true));?></td>
                                    </tr>
                                </table>
                                <input type="submit" class="btn-raised btn-primary btn"  value="Add" >
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                            
                            
                        </td>
                        
                        <td>
                            <?php echo $this->Form->create('AdminObecrs',array('action'=>'create_type')); ?>
                                <h6>Create Type</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td><?php echo $this->Form->input('campaign1',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign1','class'=>'form-control','required'=>true ));?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('parent1',array('label'=>false,'options'=>'','empty'=>'Select Category','id'=>'parent1','required'=>true,"class"=>"form-control"));?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'autofill'=>'false','required'=>true));?></td>
                                    </tr>
                                </table>
                                <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>

                        <td>
                            <?php echo $this->Form->create('AdminObecrs',array('action'=>'create_sub_type1')); ?>
                                <h6>Create Sub Type1</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td><?php echo $this->Form->input('campaign2',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign2','class'=>'form-control','required'=>true ));?></td>                  
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('parent2',array('label'=>false,"class"=>"form-control",'options'=>'','empty'=>'Select Category','id'=>'parent2','required'=>true));?></td>                  
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'options'=>'','id'=>'type1','required'=>true));?></td>                  
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('sub_type1',array('label'=>false,"class"=>"form-control",'autofill'=>'false','required'=>true));?>  </td>                  
                                    </tr>
                                </table>
                                <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                                 
                        <td>
                            <?php echo $this->Form->create('AdminObecrs',array('action'=>'create_sub_type2')); ?>
                                <h6>Create Sub Type2</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td><?php echo $this->Form->input('campaign3',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign3','class'=>'form-control','required'=>true ));?></td>                
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('parent3',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'parent3','required'=>true,"class"=>"form-control"));?></td>                
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type2','required'=>true,"class"=>"form-control"));?></td>                
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type1','required'=>true,"class"=>"form-control"));?></td>                
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('sub_type2',array('label'=>false,'id'=>'rds','autofill'=>'false','required'=>true,"class"=>"form-control"));?></td>                
                                    </tr>
                                </table>
                                <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->create('AdminObecrs',array('action'=>'create_sub_type3')); ?>
                                <h6>Create Sub Type3</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td><?php echo $this->Form->input('campaign4',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign4','class'=>'form-control','required'=>true ));?></td>                    
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('parent4',array('label'=>false,'options'=>$Category,'empty'=>'Select Category','id'=>'parent4','required'=>true,"class"=>"form-control"));?></td>                    
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type3','required'=>true,"class"=>"form-control"));?></td>                    
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type2','required'=>true,"class"=>"form-control"));?></td>                    
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'sub_type2_2','required'=>true,"class"=>"form-control"));?></td>                    
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->Form->input('sub_type3',array('label'=>false,'autofill'=>'false','required'=>true,"class"=>"form-control"));?></td>                    
                                    </tr>
                                </table>
                                <input type="submit" class="btn-raised btn-primary btn"  value="ADD" >
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                    </tr>
                </table>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>


