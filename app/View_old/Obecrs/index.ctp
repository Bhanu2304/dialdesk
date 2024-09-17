<?php 
//echo $this->Html->script('obecr');
echo $this->Html->script('assets/main/dialdesk');
?>
<script>
function obecrDetails(id){
    $("#campaignid").val(id.value);
    $("#view_obecr").submit();
}
</script>
<style>
/*ECR View Tree CSS*/
 ul.ecrtree, ul.ecrtree ul {
    list-style: none;
     margin: 0;
     padding: 0;
   } 
   ul.ecrtree ul {
     margin-left: 10px;
   }
   ul.ecrtree li {
     margin: 0;
     padding: 0 7px;
     line-height: 20px;
     color: #369;
     /*font-weight: bold;*/
     border-left:1px solid rgb(100,100,100);

   }
   ul.ecrtree li:last-child {
       border-left:none;
   }
   ul.ecrtree li:before {
      position:relative;
      top:-0.3em;
      height:1em;
      width:12px;
      color:white;
      border-bottom:1px solid rgb(100,100,100);
      content:"";
      display:inline-block;
      left:-7px;
   }
   ul.ecrtree li:last-child:before {
      border-left:1px solid rgb(100,100,100);   
}
 .ob-ecr ul.ecrtree li{
        padding:5px;
        color:#616161;
    }
</style>
<script>
    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
  return true;
}
$(document).ready(function(){
    $("#campaign1").on('change',function(){
        $.post("Obecrs/get_label1",{parent_id : $('#campaign1').val(),type : 'parent1'},function(data,status){
            $('#parent1').replaceWith(data);
        });
    });
    
    $("#campaign2").on('change',function(){
        $.post("Obecrs/get_label1",{parent_id : $('#campaign2').val(),type : 'parent2'},function(data,status){
            $('#parent2').replaceWith(data);
            $('#type1').replaceWith('<select name="data[Obecrs][parent]" id="type1" class="form-control" required="required"></select>');
            $('#sub_parent1').replaceWith('<select name="data[Obecrs][sub_parent1]" id="sub_parent1" class="form-control" required="required"></select>');
        });
    });
    
    $("#campaign3").on('change',function(){
        $.post("Obecrs/get_label1",{parent_id : $('#campaign3').val(),type : 'parent3'},function(data,status){
            $('#parent3').replaceWith(data);
            $('#type2').replaceWith('<select name="data[Obecrs][parent]" id="type2" class="form-control" required="required"></select>');
            $('#sub_type1').replaceWith('<select name="data[Obecrs][parent3]" class="form-control" id="sub_type1"></select>');
	});
    });
    
    $("#campaign4").on('change',function(){
	$.post("Obecrs/get_label1",{parent_id : $('#campaign4').val(),type : 'parent4'},function(data,status){
            $('#parent4').replaceWith(data);
            $('#sub_type2').replaceWith('<select name="data[Obecrs][sub_type1]" id="sub_type2" class="form-control" required="required"></select>');
            $('#type3').replaceWith('<select name="data[Obecrs][parent]" id="type3" class="form-control" required="required"></select>');
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
        });
    });
     
    $(document).on("change","#parent2", function() {
	$.post("Obecrs/get_label2",{parent_id : $('#parent2').val(),type : 'type1'},function(data,status){
            $('#type1').replaceWith(data);
	});
    });
    
    $(document).on("change","#parent3", function() {
        $.post("Obecrs/get_label2",{parent_id : $('#parent3').val(),type : 'type2'},function(data,status){
            $('#type2').replaceWith(data);
            $('#sub_type1').replaceWith('<select name="data[Obecrs][parent3]" class="form-control" id="sub_type1">');
        });
    });
    
    $(document).on("change","#parent4", function() {
        $.post("Obecrs/get_label2",{parent_id : $('#parent4').val(),type : 'type3'},function(data,status){
            $('#type3').replaceWith(data);
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
        });
    });
    
    $(document).on("change","#type2", function() {
        $.post("Obecrs/get_label3",{parent_id : $('#type2').val(),type : 'sub_type1'},function(data,status){
            $('#sub_type1').replaceWith(data);
        });
    });
    
    $(document).on("change","#type3", function() {
        $.post("Obecrs/get_label3",{parent_id : $('#type3').val(),type : 'sub_type2'},function(data,status){
            $('#sub_type2').replaceWith(data);
        });
    });
    
    $(document).on("change","#sub_type2", function() {
        $.post("Obecrs/get_label4",{parent_id : $('#sub_type2').val(),type : 'sub_type2_2'},function(data,status){
            $('#sub_type2_2').replaceWith(data);
        });
    });
    
});

 $(document).ready(function(){ 
    <?php if(isset($cms) && $cms !=""){?>
        showHide('<?php echo $cms;?>')
    <?php }?>
});

function showHide(id){
    var i;
    for(i=0;i<=4;i++){
        if(parseInt(i) == parseInt(id)){
            document.getElementById("addtype"+i).style.display="block"; 
        }
        else{
            document.getElementById("addtype"+i).style.display="none";
        }
    }
}

</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">Out Call Management</a></li>
    <li class="active"><a href="#">Manage Out Call Scenarios</a></li>                    
</ol> 

<div class="page-heading">                                           
<h1>Manage Out Call Scenarios</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
         <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Manage Out Call Scenarios</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Create Scenarios</h2>
                                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div data-widget-controls="" class="panel-editbox"></div>
                                    <div class="panel-body" id="addtype0">
                                        <?php echo $this->Form->create('Obecr',array('action'=>'create_category',"class"=>"form-horizontal row-border",'data-parsley-validate'  )); ?>
                                             <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Campaign</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','class'=>'form-control','required'=>true ));?>
                                                </div>
                                            </div> 

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Create Scenarios</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'autocomplete'=>'off','required'=>true));?>
                                                    <div class="textmessage"><?php if(isset($cms) && $cms =="0"){echo $this->Session->flash();}?></div>
                                                </div>
                                            </div>    
                                            <div class="form-group">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-2">
                                                    <input type="submit" value="Add" class="btn-web btn" />
                                                </div>
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>                             
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                 <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Create Sub Scenarios 1</h2>
                                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div data-widget-controls="" class="panel-editbox"></div>
                                    <div class="panel-body" id="addtype1" style="display:none;" >
                                        <?php echo $this->Form->create('Obecr',array('action'=>'create_type',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Campaign</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('campaign1',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign1','class'=>'form-control','required'=>true ));?>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Scenarios</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('parent1',array('label'=>false,'options'=>'','empty'=>'Select Scenarios','id'=>'parent1','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Add Sub Scenarios 1</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'autofill'=>'false','required'=>true));?>
                                                <div class="textmessage"><?php if(isset($cms) && $cms =="1"){echo $this->Session->flash();}?></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-4"></div>
                                                <div class="col-sm-2">
                                                    <input type="submit" value="ADD" class="btn-web btn" />
                                                </div>
                                            </div>

                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>                             
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Create Sub Scenarios 2</h2>
                                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div data-widget-controls="" class="panel-editbox"></div>
                                    <div class="panel-body" id="addtype2" style="display:none;" >
                                        <?php echo $this->Form->create('Obecr',array('action'=>'create_sub_type1',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Campaign</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('campaign2',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign2','class'=>'form-control','required'=>true ));?>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Scenarios</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('parent2',array('label'=>false,"class"=>"form-control",'options'=>'','empty'=>'Select Scenarios','id'=>'parent2','required'=>true));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Sub Scenarios 1</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'options'=>'','id'=>'type1','required'=>true));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Add Sub Scenarios 2</label>
                                                <div class="col-sm-6">
                                                  <?php echo $this->Form->input('sub_type1',array('label'=>false,"class"=>"form-control",'autofill'=>'false','required'=>true));?>  
                                                <div class="textmessage"><?php if(isset($cms) && $cms =="2"){echo $this->Session->flash();}?></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-4"></div>
                                                <div class="col-sm-2">
                                                  <input type="submit" class="btn-web btn"  value="ADD" >
                                                </div>
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>                             
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Create Sub Scenarios 3</h2>
                                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div data-widget-controls="" class="panel-editbox"></div>
                                    <div class="panel-body" id="addtype3" style="display:none;" >
                                        <?php echo $this->Form->create('Obecr',array('action'=>'create_sub_type2',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Campaign</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('campaign3',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign3','class'=>'form-control','required'=>true ));?>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Scenarios</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('parent3',array('label'=>false,'options'=>$Category,'empty'=>'Select Scenarios','id'=>'parent3','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Sub Scenarios 1</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type2','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Sub Scenarios 2</label>
                                                <div class="col-sm-6">
                                                  <?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type1','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Add Sub Scenarios 3</label>
                                                <div class="col-sm-6">
                                                  <?php echo $this->Form->input('sub_type2',array('label'=>false,'id'=>'rds','autofill'=>'false','required'=>true,"class"=>"form-control"));?>
                                                    <div class="textmessage"><?php if(isset($cms) && $cms =="3"){echo $this->Session->flash();}?></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-4"></div>
                                                <div class="col-sm-2">
                                                  <input type="submit" class="btn-web btn"  value="ADD" >
                                                </div>
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>                             
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Create Sub Scenarios 4</h2>
                                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div data-widget-controls="" class="panel-editbox"></div>
                                    <div class="panel-body" id="addtype4" style="display:none;" >
                                        <?php echo $this->Form->create('Obecr',array('action'=>'create_sub_type3',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Campaign</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('campaign4',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign Name','id'=>'campaign4','class'=>'form-control','required'=>true ));?>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Scenarios</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('parent4',array('label'=>false,'options'=>$Category,'empty'=>'Select Scenarios','id'=>'parent4','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Sub Scenarios 1</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type3','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Select Sub Scenarios 2</label>
                                                <div class="col-sm-6">
                                                  <?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type2','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Add Sub Scenarios 3</label>
                                                <div class="col-sm-6">
                                                  <?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'sub_type2_2','required'=>true,"class"=>"form-control"));?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Add Sub Scenarios 4</label>
                                                <div class="col-sm-6">
                                                  <?php echo $this->Form->input('sub_type3',array('label'=>false,'autofill'=>'false','required'=>true,"class"=>"form-control"));?>
                                                    <div class="textmessage"><?php if(isset($cms) && $cms =="4"){echo $this->Session->flash();}?></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-2"></div>
                                             <div class="col-sm-2"></div>
                                                <div class="col-sm-2">
                                                  <input type="submit" class="btn-web btn"  value="ADD" >
                                                </div>
                                             
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>                             
                        </div>
           
                        
                    </div>
                </div>
            </div>
             
            
             <div class="col-md-5">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Out Call Scenarios TREE</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <?php echo $this->Form->create('Obecr',array('action'=>'index','id'=>'view_obecr',"class"=>"form-horizontal row-border",'data-parsley-validate')); ?>
                        <div class="form-group" style="margin-top:-23px;">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-12">
                                    <?php echo $this->Form->input('campaign',array('label'=>false,'options'=>$Campaign,'value'=>isset($cmid)? $cmid :"",'onchange'=>'obecrDetails(this);','empty'=>'Select Campaign','class'=>'form-control','required'=>true ));?>
                                    <?php echo $this->Form->hidden('campaignid',array('label'=>false,'id'=>'campaignid','value'=>isset($cmid)? $cmid :""));?>
                                </div>
                               
                            </div><br/><br/>
                        <?php echo $this->Form->end(); ?>
                            <div class="ob-ecr">
                         <?php if(!empty($data)){?>
                               
                        <ul class="ecrtree">
  								
				<?php
                      foreach($data as $post1): 
						if($post1['ObclientCategory']['Label']==1){?><li>
                                	<?php echo $post1['ObclientCategory']['ecrName'];?>
                                	<?php 
											?>
                                                    
				<a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#obcatdiv1" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>obecrs/delete_ecr?id=<?php echo $post1['ObclientCategory']['id'];?>&cmid=<?php if(isset($cmid)){ echo $cmid;}?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                <ul class="ecrtree" >
                                                                                                    
											<?php
											foreach($data as $post2):
												if($post2['ObclientCategory']['Label']==2 && $post2['ObclientCategory']['parent_id']==$post1['ObclientCategory']['id'])
													{?><li><?php
														 echo $post2['ObclientCategory']['ecrName'];
														?>
                                                                                                            
                                                            	<a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#obcatdiv2" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>obecrs/delete_ecr?id=<?php echo $post2['ObclientCategory']['id'];?>&cmid=<?php if(isset($cmid)){ echo $cmid;}?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                <ul class="ecrtree" >
																<?php
														foreach($data as $post3):
															if($post3['ObclientCategory']['Label']==3 && $post3['ObclientCategory']['parent_id']==$post2['ObclientCategory']['id'])
															{?><li> <?php
																 echo $post3['ObclientCategory']['ecrName'];
																?>
                                                                    	<a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#obcatdiv3" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>obecrs/delete_ecr?id=<?php echo $post3['ObclientCategory']['id'];?>&cmid=<?php if(isset($cmid)){ echo $cmid;}?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                <ul class="ecrtree" >
																		<?php
																foreach($data as $post4):
																	if($post4['ObclientCategory']['Label']==4 && $post4['ObclientCategory']['parent_id']==$post3['ObclientCategory']['id'])
																	{?><li> <?php
																		 echo "".$post4['ObclientCategory']['ecrName'];
																		?>
                                                                        <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#obcatdiv4" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>obecrs/delete_ecr?id=<?php echo $post4['ObclientCategory']['id'];?>&cmid=<?php if(isset($cmid)){ echo $cmid;}?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                <ul class="ecrtree" >
																		<?php
																			foreach($data as $post5): 
																				if($post5['ObclientCategory']['Label']==5 && $post5['ObclientCategory']['parent_id']==$post4['ObclientCategory']['id'])
																				{?><li><?php
																					 echo "".$post5['ObclientCategory']['ecrName'];
																				
																				?>
                                                                                                                                                                 <a href="#" data-toggle="modal" data-target="#obcatdiv5" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                                                    <a href="#" onclick="deleteData('<?php echo $this->webroot;?>obecrs/delete_ecr?id=<?php echo $post5['ObclientCategory']['id'];?>&cmid=<?php if(isset($cmid)){ echo $cmid;}?>')"><label class="btn btn-xs tn-midni<?php if(isset($cmid)){ echo $cmid;}?>'ghtblue btn-raised"><i class="fa fa-trash"></i></label></a>
                                                                                                                                                                
                                                                                                                                                                
                                                                                                                                                                </li> <?php }
																			endforeach;
																		?>
                                                                        </ul>
                                                                      </li>
																		<?php
																		
																	}
																endforeach;?>
                                                                </ul>
                                                                </li>
																<?php
																
															}
														endforeach;?>
                                                                </ul>
                                                                </li>
														<?php														
													}
											endforeach;	?>
                                                                </ul>
                                                                </li>
								<?php }	endforeach; ?>
                        
						</ul>
                                 <?php }else{ ?>
                                <span>Data Not Found.</span>
                                 <?php }?>
                            </div>
                    </div>
                </div>
             </div>
             
           
         </div>

       
        
        
        
    </div>
</div>



<script>
function obsubmitForm(form,path,msg,id){
    var formData = $(form).serialize(); 
    
   
    
    $.post(path,formData).done(function(data){
        $("#"+id).trigger('click');
        $("#obshow-ecr-message").trigger('click');
        $("#obecr-text-message").text(msg+' update successfully.');
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}
</script>

<a class="btn btn-primary btn-lg" id="obshow-ecr-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                        <!--
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        -->
                    </div>
                    <div class="modal-body">
                        <p id="obecr-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<div class="modal fade" id="obcatdiv1"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Scenarios</h2>      
            </div>
            <?php echo $this->Form->create('Obecrs',array('action'=>'update_category',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">
                                    <?php 	
                                        $editcategory = isset($Category1['1'])? $Category1['1']:'';
                                        if(!empty($editcategory))
                                        {
                                            $loop = explode('==>',$editcategory);
                                            $count = count($loop);
                                            for($i = 0; $i<$count; $i++)
                                            {
                                                $row = explode('=>',$loop[$i]);
                                                echo '<div class="form-group">';
                                                echo '<label class="col-sm-3 control-label">Scenarios</label>';
                                                echo '<div class="col-sm-6">';
                                                echo $this->Form->input($row[0],array('label'=>false,'value'=>$row[1],'required'=>true,'class'=>'form-control'));
                                                echo "</div>";
                                                echo "</div>";
                                            }
                                    ?>

                                <?php }?>


                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="obclose-cat1" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return obsubmitForm(this.form,'<?php echo $this->webroot;?>Obecrs/update_category','Scenarios','obclose-cat1')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat2").on('change',function(){
        $.post("obecrs/edit_label2",{parent_id : $('#cat2').val()},function(data,status){
            $('#abc').html(data);
        });
    });
});
</script>

<!-- Category Div 2 -->
<div class="modal fade" id="obcatdiv2"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 1</h2>      
            </div>
            <?php echo $this->Form->create('Obecrs',array('action'=>'update_type',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                 <?php 	
                                    $editcategory = isset($Category1['1'])?$Category1['1']:'';
                                    if(!empty($editcategory))
                                    {
                                ?>
                                <?php	$loop = explode('==>',$editcategory);
                                        $options = '';
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                        {
                                            $row = explode('=>',$loop[$i]);
                                            $options[$row[0]] = $row[1]; 
                                        }
                                        echo '<div class="form-group">';
                                        echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                            echo '<div class="col-sm-6">';
                                            echo $this->Form->input('category',array('label'=>false,'id'=>'cat2','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                            echo "</div>";
                                        echo "</div>";
                                ?>
                                <div id="abc"></div>
               
                                <?php }?>                
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="obclose-cat2" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return obsubmitForm(this.form,'<?php echo $this->webroot;?>Obecrs/update_type','Sub Scenarios 1','obclose-cat2')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat3").on('change',function(){
	$.post("Obecrs/edit_label2_sub1",{parent_id : $('#cat3').val(),type : 'edittype1'},function(data,status){ 
            $('#edittype1').replaceWith(data);
            $('#abd').html("");	
	});
    });
    
    $('body').on('change',"#edittype1",function(){
	$.post("Obecrs/edit_label3",{parent_id : $('#edittype1').val()},function(data,status){
            $('#abd').html(data);
        });
    });

});

</script>

<!-- Category Div 3 -->
<div class="modal fade" id="obcatdiv3"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Update Sub Scenarios 2</h2>      
            </div>
            <?php echo $this->Form->create('Obecrs',array('action'=>'update_sub_type1',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                <?php 	
                                $editcategory = isset($Category1[1])?$Category1[1]:'';
                                  if(!empty($editcategory))
                                    { ?>
                                <?php				
                                    $loop = explode('==>',$editcategory);
                                    $options = '';
                                    $count = count($loop);
                                    for($i = 0; $i<$count; $i++)
                                    {
                                        $row = explode('=>',$loop[$i]);
                                        $options[$row[0]] = $row[1]; 
                                    }
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                echo '<div class="col-sm-6">';
                                echo $this->Form->input('category',array('label'=>false,'id'=>'cat3','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                echo "</div>";
                                echo "</div>";
                                  ?>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Select Sub Scenarios 1</label>
                                        <div class="col-sm-6">
                                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'edittype1','required'=>true,'class'=>'form-control'));?>
                                        </div>
                                    </div>
                                    <div id="abd"></div>
                                
                                <?php }?>               
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="obclose-cat3" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return obsubmitForm(this.form,'<?php echo $this->webroot;?>Obecrs/update_sub_type1','Sub Scenarios 2','obclose-cat3')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("body").on('change',"#cat4",function(){
	$.post("Obecrs/edit_label2_sub1",{parent_id : $('#cat4').val(),type : 'typ2'},function(data,status){
            $('#typ2').replaceWith(data);
            $('#sub_typ1').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_typ1" required="required" class="form-control"></select>');
            $('#abe').html('');
        });
    });
    
    $('body').on('change',"#typ2",function(){
	$.post("Obecrs/edit_label2_sub2",{parent_id : $('#typ2').val(),type : 'sub_typ1'},function(data,status){
            $('#sub_typ1').replaceWith(data);
            $('#abe').html("");
        });
    });
    
    $('body').on('change',"#sub_typ1",function(){
        $.post("Obecrs/edit_label3_sub1",{parent_id : $('#sub_typ1').val()},function(data,status){
            $('#abe').html(data);			 
        });
    });
    
});




</script>

<!-- Category Div 4 -->
<div class="modal fade" id="obcatdiv4"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 3</h2>      
            </div>
            <?php echo $this->Form->create('Obecrs',array('action'=>'update_sub_type2',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                               
                              <?php 	
                                $editcategory = isset($Category1[1])?$Category1[1]:'';
                                  if(!empty($editcategory))
                                    {
                                        $loop = explode('==>',$editcategory);
                                        $options = '';
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                        {
                                            $row = explode('=>',$loop[$i]);
                                            $options[$row[0]] = $row[1];
                                        }
                                        echo '<div class="form-group">';
                                        echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                        echo '<div class="col-sm-6">';
                                        echo $this->Form->input('category',array('label'=>false,'id'=>'cat4','options'=>$options,'empty'=>'Select Category','required'=>true,'class'=>'form-control'));
                                        echo "</div>";
                                        echo "</div>";
                                  ?>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Select Sub Scenarios 1</label>
                                            <div class="col-sm-6">
                                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'typ2','required'=>true,'class'=>'form-control'));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Select Sub Scenarios 2</label>
                                            <div class="col-sm-6">
                                            <?php echo $this->Form->input('sub_type',array('label'=>false,'options'=>'','id'=>'sub_typ1','required'=>true,'class'=>'form-control'));?>
                                            </div>
                                        </div>
                                        <div id="abe"></div>
                                <?php }?>              
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="obclose-cat4" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return obsubmitForm(this.form,'<?php echo $this->webroot;?>Obecrs/update_sub_type2','Sub Scenarios 3','obclose-cat4')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat5").on('change',function(){
	$.post("Obecrs/edit_label2_sub1",{parent_id : $('#cat5').val(),type : 'typ3'},function(data,status){
             $('#typ3').replaceWith(data);
             $('#sub_type2').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_type2" required="required" class="form-control"></select>');
             $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]" class="form-control"></select>');
             $('#abf').html("");
        });
    });
    
    $('body').on('change',"#typ3",function(){
	$.post("Obecrs/edit_label2_sub2",{parent_id : $('#typ3').val(),type : 'editsub_type2'},function(data,status){
            $('#editsub_type2').replaceWith(data);
            $('#abf').html("");
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]" class="form-control"></select>');
        });
    });
    
    $('body').on('change',"#editsub_type2",function(){
	$.post("Obecrs/edit_label3_sub2",{parent_id : $('#editsub_type2').val(),type : 'editsub_type2_2'},function(data,status){
            $('#editsub_type2_2').replaceWith(data);
            $('#abf').html("");
        });
    });
    
    $('body').on('change',"#editsub_type2_2",function(){
	$.post("Obecrs/edit_label4_sub1",{parent_id : $('#editsub_type2_2').val()},function(data,status){
            $('#abf').html(data);			 
        });
    });

    
});




</script>

<!-- Category Div 4 -->
<div class="modal fade" id="obcatdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 4</h2>      
            </div>
            <?php echo $this->Form->create('Obecrs',array('action'=>'update_sub_type3',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                               
                              <?php 	
                                $editcategory = isset($Category1[1])?$Category1[1]:'';
                                if(!empty($editcategory))
                                { ?>
                                <?php				
                                        $loop = explode('==>',$editcategory);
                                        $options = '';
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                                {
                                                        $row = explode('=>',$loop[$i]);
                                                        $options[$row[0]] = $row[1]; 
                                                }
                                        echo '<div class="form-group">';
                                        echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                        echo '<div class="col-sm-6">';
                                        echo $this->Form->input('category',array('label'=>false,'id'=>'cat5','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                        echo "</div>";
                                        echo "</div>";
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Sub Scenarios 1</label>
                                    <div class="col-sm-6">
                                    <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'typ3','required'=>true,'class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Sub Scenarios 2</label>
                                    <div class="col-sm-6">
                                    <?php echo $this->Form->input('sub_type',array('label'=>false,'options'=>'','id'=>'editsub_type2','required'=>true,'class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Sub Scenarios 3</label>
                                    <div class="col-sm-6">
                                    <?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'editsub_type2_2','required'=>true,'class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div id="abf"></div>
                            <?php }?>              
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="obclose-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return obsubmitForm(this.form,'<?php echo $this->webroot;?>Obecrs/update_sub_type3','Sub Scenarios 4','obclose-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>