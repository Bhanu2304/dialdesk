<script>
function obecrDetails(){
    $("#edit_obecr").submit();
}

function get_campaign(client){
    var id=client.value;
    $.ajax({
            type:'post',
            url:'AdminobecrEdits/get_campaign',
            data:{id:id},
            success:function(data){
                    $("#AdminobecrEditsCampaign").html(data);
            }
    });	
}

// for category 2 in second frameset
$(document).ready(function()
{$("#cat2").on('change',function(){
	$.post("AdminobecrEdits/get_label2",{
		parent_id : $('#cat2').val()		
		},
		function(data,status){
			 $('#abc').html(data);
			})})});
//end

// for category 3 in third frameset
$(document).ready(function()
{$("#cat3").on('change',function(){
	$.post("AdminobecrEdits/get_label2_sub1",{
		parent_id : $('#cat3').val(),
		type : 'type1'
		},
		function(data,status){
			 $('#type1').replaceWith(data);
                         $('#abd').html("");
			 //$('#sub_type1').replaceWith('<select name="data[Obecr][sub_type1]" id="sub_type1" required="required"></select>');
			})})});
//end

// for type 1 in third frameset
$(document).ready(function()
{$('body').on('change',"#type1",function(){
	$.post("AdminobecrEdits/get_label3",{
		parent_id : $('#type1').val()		
		},
		function(data,status){
			 $('#abd').html(data);
})})});
//end

// for category 4 in fourth frameset
$(document).ready(function()
{$("body").on('change',"#cat4",function(){
	$.post("AdminobecrEdits/get_label2_sub1",{
		parent_id : $('#cat4').val(),
		type : 'typ2'
		},
		function(data,status){
			 $('#typ2').replaceWith(data);
			 $('#sub_typ1').replaceWith('<select name="data[Obecr][sub_type1]" id="sub_typ1" required="required" class="form-control"></select>');
			 $('#abe').html('');
			})})});
//end

// for type 2 in fourth frameset
$(document).ready(function()
{$('body').on('change',"#typ2",function(){
	$.post("AdminobecrEdits/get_label2_sub2",{
		parent_id : $('#typ2').val(),
		type : 'sub_typ1'		
		},
		function(data,status){
			 $('#sub_typ1').replaceWith(data);
                         $('#abe').html("");
			})})});
//end

// for category 4 in fourth frameset
$(document).ready(function()
{$('body').on('change',"#sub_typ1",function(){
	$.post("AdminobecrEdits/get_label3_sub1",{
		parent_id : $('#sub_typ1').val()
		
		},
		function(data,status){
			 $('#abe').html(data);			 
			})})});
//end

// for category 5 in fifth frameset
$(document).ready(function()
{$("#cat5").on('change',function(){
	$.post("AdminobecrEdits/get_label2_sub1",{
		parent_id : $('#cat5').val(),
		type : 'typ3'
		},
		function(data,status){
			 $('#typ3').replaceWith(data);
			 $('#sub_type2').replaceWith('<select name="data[Obecr][sub_type1]" id="sub_type2" required="required" class="form-control"></select>');
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Obecr][sub_type2]" class="form-control"></select>');
			 $('#abf').html("");
			})})});
//end

// for sub type 3  in fifth frameset
$(document).ready(function()
{$('body').on('change',"#typ3",function(){
	$.post("AdminobecrEdits/get_label2_sub2",{
		parent_id : $('#typ3').val(),
		type : 'sub_type2'		
		},
		function(data,status){
			 $('#sub_type2').replaceWith(data);
			 $('#abf').html("");
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Obecr][sub_type2]" class="form-control"></select>');
})})});
//end

// for sub type 2  in fifth frameset
$(document).ready(function()
{$('body').on('change',"#sub_type2_2",function(){
	$.post("AdminobecrEdits/get_label4_sub1",{
		parent_id : $('#sub_type2_2').val()
		
		},
		function(data,status){
			 $('#abf').html(data);			 
})})});
//end
</script>



<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Edit ECR</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                <?php echo $this->Form->create('AdminobecrEdits',array('action'=>'index','id'=>'edit_obecr')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'get_campaign(this);','options'=>$client,'value'=>isset($clientid)?$clientid:"",'empty'=>'Select Client','required'=>true)); ?>
                    <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>isset($campaign)?$campaign:"",'onchange'=>'obecrDetails();','value'=>isset($campaign_id)?$campaign_id:"",'empty'=>'Select Campaign Name','class'=>'form-control','required'=>true ));?>
                <?php echo $this->Form->end(); ?>
                
                <?php if(isset($Category) && !empty($Category)){ ?>
                
                <div style="width: 100%;overflow: scroll;" >
                <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                    <tr>
                        <td>
                            <?php echo $this->Form->create('AdminobecrEdits',array('action'=>'update_category')); ?>
                                <h6>Edit Category</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php 	
                                            $category = isset($Category['1'])?$Category['1']:'';
                                           // if(!empty($category)){
                                                $loop = explode('==>',$category);
                                                $count = count($loop);
                                                for($i = 0; $i<$count; $i++){
                                                    $row = explode('=>',$loop[$i]);
                                                    echo $this->Form->input($row[0],array('label'=>false,'value'=>$row[1],'required'=>true,'class'=>'form-control'));
                                                }
                                            ?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="Update" >              
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                                <?php echo $this->Form->hidden('cmp',array('label'=>false,'value'=>isset($campaign_id)?$campaign_id:""));?>
                            <?php echo $this->Form->end(); ?>
                            </td>
                            <td>
                            <?php echo $this->Form->create('AdminobecrEdits',array('action'=>'update_type')); ?>
                                <h6>Edit Type</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                             <?php 	
                    $category = isset($Category['1'])?$Category['1']:'';
                    if(!empty($category))
                    {
		?>
		<?php	$loop = explode('==>',$category);
			$options = '';
			$count = count($loop);
			for($i = 0; $i<$count; $i++)
			{
                            $row = explode('=>',$loop[$i]);
                            $options[$row[0]] = $row[1]; 
			}
                       
                            echo $this->Form->input('category',array('label'=>false,'id'=>'cat2','options'=>$options,'empty'=>'Select Category','required'=>true,'class'=>'form-control'));
                           
		?>
                <div id="abc"></div>
                    <?php }?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="Update" >  
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cmp',array('label'=>false,'value'=>isset($campaign_id)?$campaign_id:""));?>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
         
                        <td>
                            <?php echo $this->Form->create('AdminobecrEdits',array('action'=>'update_sub_type1')); ?>
                                <h6>Edit Sub Type1</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php 	
		$category = isset($Category['1'])?$Category['1']:'';
		  if(!empty($category))
                    { ?>
		<?php				
                    $loop = explode('==>',$category);
                    $options = '';
                    $count = count($loop);
                    for($i = 0; $i<$count; $i++)
                    {
                        $row = explode('=>',$loop[$i]);
			$options[$row[0]] = $row[1]; 
                    }
		
		echo $this->Form->input('category',array('label'=>false,'id'=>'cat3','options'=>$options,'empty'=>'Select Category','required'=>true,'class'=>'form-control'));
		
		  ?>
                
                   
                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type1','required'=>true,'class'=>'form-control'));?>
                       
                    <div id="abd"></div>
                     <?php }?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="Update" >  
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cmp',array('label'=>false,'value'=>isset($campaign_id)?$campaign_id:""));?>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->create('AdminobecrEdits',array('action'=>'update_sub_type2')); ?>
                                <h6>Edit Sub Type2</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php 	
		$category = isset($Category['1'])?$Category['1']:'';
		  if(!empty($category))
                    {
                        $loop = explode('==>',$category);
			$options = '';
			$count = count($loop);
			for($i = 0; $i<$count; $i++)
			{
                            $row = explode('=>',$loop[$i]);
                            $options[$row[0]] = $row[1];
			}
		
			echo $this->Form->input('category',array('label'=>false,'id'=>'cat4','options'=>$options,'empty'=>'Select Category','required'=>true,'class'=>'form-control'));
			
		  ?>
			
                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'typ2','required'=>true,'class'=>'form-control'));?>
                            
                     
                            <?php echo $this->Form->input('sub_type',array('label'=>false,'options'=>'','id'=>'sub_typ1','required'=>true,'class'=>'form-control'));?>
                           
                        <div id="abe"></div>
                    <?php }?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="Update" >  
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cmp',array('label'=>false,'value'=>isset($campaign_id)?$campaign_id:""));?>
                                <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>
                            <?php echo $this->Form->end(); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->create('AdminobecrEdits',array('action'=>'update_sub_type3')); ?>
                                <h6>Edit Sub Type3</h6>
                                <table class="display table  table-condensed table-hovered sortableTable">
                                    <tr>
                                        <td>
                                            <?php 	
		$category = isset($Category['1'])?$Category['1']:'';
		  if(!empty($category))
			{ ?>
			<?php				
				$loop = explode('==>',$category);
				$options = '';
				$count = count($loop);
				for($i = 0; $i<$count; $i++)
					{
						$row = explode('=>',$loop[$i]);
						$options[$row[0]] = $row[1]; 
					}
				
				echo $this->Form->input('category',array('label'=>false,'id'=>'cat5','options'=>$options,'empty'=>'Select Category','required'=>true,'class'=>'form-control'));
				
                        ?>
			
                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'typ3','required'=>true,'class'=>'form-control'));?>
                       
                            <?php echo $this->Form->input('sub_type',array('label'=>false,'options'=>'','id'=>'sub_type2','required'=>true,'class'=>'form-control'));?>
                   
                            <?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'sub_type2_2','required'=>true,'class'=>'form-control'));?>
                            
                        <div id="abf"></div>
                        <?php }?>
                                            <input type="submit" class="btn-raised btn-primary btn"  value="Update" >  
                                        </td>                    
                                    </tr>
                                </table>
                                <?php echo $this->Form->hidden('cmp',array('label'=>false,'value'=>isset($campaign_id)?$campaign_id:""));?>
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
                 	                   			
			                    

            