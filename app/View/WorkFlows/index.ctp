<!--
<?php 
echo $this->Html->css('parentChild/style');
echo $this->Html->script('WorkFlow/jquery-migrate-1.2.1.min');
echo $this->Html->script('WorkFlow/jquery-ui');
echo $this->Html->script('WorkFlow/jquery.tree');
?>

<script>
$(document).ready(function() {
    $('.tree').tree_structure({
        'add_option': true,
        'edit_option': true,
        'delete_option': true,
        'confirm_before_delete': true,
        'animate_option': false,
        'fullwidth_option': false,
        'align_option': 'center',
        'draggable_option': true
    });
    
    //$(".messageBox").hide().delay(5000).fadeOut();
});
</script>

<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">Client Activation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>WorkFlows">WorkFlow Creation</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Work Flow</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="row">
            <div class="col-md-12">               
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Create Work Flow </h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div id="wf" style="margin-left:18px;color:green;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body" >
                       <?php echo $html;?>
                    </div>
                </div>        
            </div>
        </div> 
    </div>
</div>
-->



<?php  
//echo $this->Html->script('ecr');
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

.tat .textlabel{
   color:#616161;
}

.tat .textbox{
    margin-left: 10px;
    width: 45px;
    
}

.tat ul li{
    padding:20px;
}

</style>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">In Call Management</a></li>
    <li class="active"><a href="#">Manage Work Flow</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Manage Work Flow</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
             <div class="col-xs-12">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Manage Work Flow</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <?php echo $this->Form->create('WorkFlows',array('action'=>'save_workflow')); ?>
                        <div style="color:green;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        <div class="tat">
                            <ul class="ecrtree" >	
                                <?php echo $UserRight;?>                                    
                            </ul>  
                        </div>
                        <button type="submit" class="btn btn-web" >Submit</button>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getNumber(type,id){
    if(type=="Internal"){$("#wfnumber"+id).show();}
    else{$("#wfnumber"+id).hide();}
}
function editNumber(type,id){
    if(type=="Internal"){$("#wfnumber"+id).show();}
    else{$("#wfnumber"+id).hide();}
}
function deleteWorkFlow(id){
    bootbox.confirm("Are you sure you want to delete?", function(result){
        if(result ==true){
            window.location.href ="<?php echo $this->webroot;?>WorkFlows/delete_workflow?id="+id;
        }
    });   
}
</script>

<?php echo $this->Html->script('WorkFlow/src/wickedpicker'); ?>
<link rel="stylesheet" href="<?php echo $this->webroot; ?>js/WorkFlow/stylesheets/wickedpicker.css">
<script type="text/javascript">
    $('.timepicker').wickedpicker({now: '00:00', twentyFour: true, title:'My Timepicker', showSeconds: false
    });
</script>
<script type="text/javascript">
    $('.timepicker1').wickedpicker({now: '23:59', twentyFour: true, title:'My Timepicker', showSeconds: false
    });
</script>
