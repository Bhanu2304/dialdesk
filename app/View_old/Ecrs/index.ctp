<?php  
echo $this->Html->script('ecr');
echo $this->Html->script('assets/main/dialdesk');
?>
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
 .ecr ul.ecrtree li{
        padding:5px;
        color:#616161;
    }
</style>
<script>
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

$(function () {
    $(".panel-color-list>li>span").click(function(e) {
        $(".panel").attr('class','panel').addClass($(this).attr('data-style'));
    }); 	
});
</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">In Call Management</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Ecrs">Manage In Call Scenarios</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Manage In Call Scenarios</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
             <div class=" <?php if(!empty($data)){?>col-md-7<?php }else{?>col-md-12<?php }?>"> 
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                            <h2>Manage In Call Scenarios</h2>
                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                            <h2>Create Scenarios</h2>
                                            <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype0" >                     
                                    <?php echo $this->Form->create('Ecr',array('action'=>'create_category',"class"=>"form-horizontal row-border",'data-parsley-validate' )); ?>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Scenarios</label>
                                            <div class="col-sm-6">
                                                <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'autocomplete'=>'off','required'=>true));?>
                                                <div class="textmessage"><?php if(isset($cms) && $cms =="0"){echo $this->Session->flash();}?></div>
                                            </div>
                                        </div>    
                                        <div class="form-group">
                                            <div class="col-sm-4"></div>
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
                                            <h2>CREATE Sub Scenarios 1</h2>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype1" style="display:none;"   >
                <?php echo $this->Form->create('Ecr',array('action'=>'create_type',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Select Scenarios</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Scenarios','id'=>'category1','required'=>true,"class"=>"form-control"));?>
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
                                            <h2>CREATE Sub Scenarios 2</h2>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                   <div class="panel-body" id="addtype2" style="display:none;" name="addtype2">
                <?php echo $this->Form->create('Ecr',array('action'=>'create_sub_type1',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Select Scenarios</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'options'=>$Category,'empty'=>'Select Scenarios','id'=>'category2','required'=>true));?>
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
                                            <h2>CREATE Sub Scenarios 3</h2>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype3" style="display:none;" name="addtype3">
                <?php echo $this->Form->create('Ecr',array('action'=>'create_sub_type2',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Select Scenarios</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Scenarios','id'=>'category3','required'=>true,"class"=>"form-control"));?>
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
                                            <h2>CREATE Sub Scenarios 4</h2>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                   <div class="panel-body" id="addtype4" style="display:none;">
                <?php echo $this->Form->create('Ecr',array('action'=>'create_sub_type3',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Select Scenarios</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select Scenarios','id'=>'category4','required'=>true,"class"=>"form-control"));?>
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
                        
                    </div>
                </div>
            </div>
            
        <?php if(!empty($data)){?>
            <div class="col-xs-5">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Call Scenario TREE</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="ecr">
                        <ul class="ecrtree" >					                     
                        <?php 
                        foreach($data as $post1): 
                            if($post1['ClientCategory']['Label']==1){?>
                            <li><?php echo $post1['ClientCategory']['ecrName'];?>
                                <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#catdiv1" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post1['ClientCategory']['id'];?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                    <ul class="ecrtree" >
                                        <?php
                                        foreach($data as $post2):
                                            if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id']){?>
                                                <li><?php echo $post2['ClientCategory']['ecrName']."";?>
                                                    <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#catdiv2" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><div class="ripple-container"></div></label></a>
                                                    <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post2['ClientCategory']['id'];?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                                    <ul class="ecrtree" >
                                                        <?php
                                                        foreach($data as $post3):
                                                            if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id']){?>
                                                                <li> 
                                                                    <?php echo $post3['ClientCategory']['ecrName']."";?>         	
                                                                    <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#catdiv3" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                    <a href="#" class="delete-ecr-icon" onclick="deleteData('<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post3['ClientCategory']['id'];?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>                               
                                                                    <ul class="ecrtree" >
                                                                        <?php
                                                                        foreach($data as $post4):
                                                                            if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id']){?>                                              
                                                                                <li>
                                                                                    <?php  echo $post4['ClientCategory']['ecrName'];?> 
                                                                                    <a href="#" data-toggle="modal" data-target="#catdiv4" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                                    <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post4['ClientCategory']['id'];?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>                                                                  
                                                                                    <ul class="ecrtree" >  
                                                                                        <?php
                                                                                        foreach($data as $post5): 
                                                                                            if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id']){?>                                                                                        
                                                                                                <li><?php echo $post5['ClientCategory']['ecrName'];?>                                                                                        
                                                                                                    <a href="#" data-toggle="modal" data-target="#catdiv5" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                                                    <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post5['ClientCategory']['id'];?>')"><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a>
                                                                                                </li>                                       
                                                                                        <?php }endforeach;?>      
                                                                                    </ul>
                                                                                </li>
                                                                        <?php }endforeach;?>
                                                                    </ul>
                                                                </li>
                                                        <?php }endforeach;?>									
                                                    </ul>
                                                </li>
                                            <?php }endforeach;?>
                                        </ul>
                                    </li>
                            <?php }endforeach;?>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

            

    
            
        </div>
        

    </div>
</div>

<script>
function submitForm(form,path,msg,id){
    var formData = $(form).serialize(); 
    $.post(path,formData).done(function(data){
        $("#"+id).trigger('click');
        $("#show-ecr-message").trigger('click');
        $("#ecr-text-message").text(msg+' update successfully.');
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}
</script>

<a class="btn btn-primary btn-lg" id="show-ecr-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                        <!--
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        -->
                    </div>
                    <div class="modal-body">
                        <p id="ecr-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<div class="modal fade" id="catdiv1"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Scenarios</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_category',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">
                                   <?php 	
                                    $editcategory = isset($ecrcat1[1])?$ecrcat1[1]:'';
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
                    <button type="button" id="close-cat1" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_category','Scenarios','close-cat1')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat2").on('change',function(){
        $.post("Ecrs/edit_label2",{parent_id : $('#cat2').val()},function(data,status){
            $('#abc').html(data);
        });
    });
});
</script>

<!-- Category Div 2 -->
<div class="modal fade" id="catdiv2"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 1</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_type',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                 <?php 	
                                    $editcategory = isset($ecrcat2[1])?$ecrcat2[1]:'';
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
                    <button type="button" id="close-cat2" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_type','Sub Scenarios 1','close-cat2')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat3").on('change',function(){
	$.post("Ecrs/edit_label2_sub1",{parent_id : $('#cat3').val(),type : 'edittype1'},function(data,status){ 
            $('#edittype1').replaceWith(data);
            $('#abd').html("");	
	});
    });
    
    $('body').on('change',"#edittype1",function(){
	$.post("Ecrs/edit_label3",{parent_id : $('#edittype1').val()},function(data,status){
            $('#abd').html(data);
        });
    });

});


</script>

<!-- Category Div 3 -->
<div class="modal fade" id="catdiv3"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 2</h2>       
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_sub_type1',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                <?php 	
                                $editcategory = isset($ecrcat3[1])?$ecrcat3[1]:'';
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
                    <button type="button" id="close-cat3" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_sub_type1','Sub Scenarios 2','close-cat3')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("body").on('change',"#cat4",function(){
	$.post("Ecrs/edit_label2_sub1",{parent_id : $('#cat4').val(),type : 'typ2'},function(data,status){
            $('#typ2').replaceWith(data);
            $('#sub_typ1').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_typ1" required="required" class="form-control"></select>');
            $('#abe').html('');
        });
    });
    
    $('body').on('change',"#typ2",function(){
	$.post("Ecrs/edit_label2_sub2",{parent_id : $('#typ2').val(),type : 'sub_typ1'},function(data,status){
            $('#sub_typ1').replaceWith(data);
            $('#abe').html("");
        });
    });
    
    $('body').on('change',"#sub_typ1",function(){
        $.post("Ecrs/edit_label3_sub1",{parent_id : $('#sub_typ1').val()},function(data,status){
            $('#abe').html(data);			 
        });
    });
    
});




</script>

<!-- Category Div 4 -->
<div class="modal fade" id="catdiv4"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 3</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_sub_type2',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                               
                              <?php 	
                                $editcategory = isset($ecrcat4[1])?$ecrcat4[1]:'';
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
                                        echo $this->Form->input('category',array('label'=>false,'id'=>'cat4','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
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
                    <button type="button" id="close-cat4" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_sub_type2','Sub Scenarios 3','close-cat4')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat5").on('change',function(){
	$.post("Ecrs/edit_label2_sub1",{parent_id : $('#cat5').val(),type : 'typ3'},function(data,status){
             $('#typ3').replaceWith(data);
             $('#sub_type2').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_type2" required="required" class="form-control"></select>');
             $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]" class="form-control"></select>');
             $('#abf').html("");
        });
    });
    
    $('body').on('change',"#typ3",function(){
	$.post("Ecrs/edit_label2_sub2",{parent_id : $('#typ3').val(),type : 'editsub_type2'},function(data,status){
            $('#editsub_type2').replaceWith(data);
            $('#abf').html("");
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]" class="form-control"></select>');
        });
    });
    
    $('body').on('change',"#editsub_type2",function(){
	$.post("Ecrs/edit_label3_sub2",{parent_id : $('#editsub_type2').val(),type : 'editsub_type2_2'},function(data,status){
            $('#editsub_type2_2').replaceWith(data);
            $('#abf').html("");
        });
    });
    
    $('body').on('change',"#editsub_type2_2",function(){
	$.post("Ecrs/edit_label4_sub1",{parent_id : $('#editsub_type2_2').val()},function(data,status){
            $('#abf').html(data);			 
        });
    });

    
});




</script>

<!-- Category Div 4 -->
<div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 4</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_sub_type3',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                               
                              <?php 	
                                $editcategory = isset($ecrcat5[1])?$ecrcat5[1]:'';
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
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_sub_type3','Sub Scenarios 4','close-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>