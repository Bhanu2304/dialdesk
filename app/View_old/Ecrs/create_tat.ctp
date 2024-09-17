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
    <li class="active"><a href="<?php echo $this->webroot;?>Ecrs">Manage TAT</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Manage TAT</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
             <div class="col-xs-12">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Manage TAT</h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <?php echo $this->Form->create('Ecrs',array('action'=>'savetat')); ?>
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
function getwcat(id,eid){
    if(id ==="Custom Hours"){
        $("#wstime"+eid).show();
        $("#wetime"+eid).show();
        $("#wsday"+eid).show();
        $("#weday"+eid).show();
        $.post("<?php echo $this->webroot;?>Ecrs/start_day",{sd:''},function(data){$("#wsday"+eid).html(data);});
        $.post("<?php echo $this->webroot;?>Ecrs/end_day",{ed:''},function(data){$("#weday"+eid).html(data);}); 
    }
    else{
        $("#wstime"+eid).hide();
        $("#wetime"+eid).hide();
        $("#wstime"+eid).html('');
        $("#wetime"+eid).html('');
        $("#wsday"+eid).hide();
        $("#weday"+eid).hide();
        $("#wsday"+eid).html('');
        $("#weday"+eid).html('');
    }
}

function editgetwcat(id,eid,sd,ed){
    if(id ==="Custom Hours"){
        
        $("#wstime"+eid).show();
        $("#wetime"+eid).show();
        $("#wsday"+eid).show();
        $("#weday"+eid).show();
        $.post("<?php echo $this->webroot;?>Ecrs/start_day",{sd:sd},function(data){$("#wsday"+eid).html(data);});
        $.post("<?php echo $this->webroot;?>Ecrs/end_day",{ed:ed},function(data){$("#weday"+eid).html(data);}); 
    }
    else{
        $("#wstime"+eid).hide();
        $("#wetime"+eid).hide();
        $("#wstime"+eid).html('');
        $("#wetime"+eid).html('');
        $("#wsday"+eid).hide();
        $("#weday"+eid).hide();
        $("#wsday"+eid).html('');
        $("#weday"+eid).html('');
    }
}



/*
function getwhour(id,eid){
    
    if(id >= $("#wstime"+eid).val()){
        if(id !=""){
            $("#wsday"+eid).show();
            $("#weday"+eid).show();
            $.post("<?php echo $this->webroot;?>Ecrs/start_day",{sd:''},function(data){$("#wsday"+eid).html(data);});
            $.post("<?php echo $this->webroot;?>Ecrs/end_day",{ed:''},function(data){$("#weday"+eid).html(data);}); 
        }
        else{
            $("#wsday"+eid).hide();
            $("#weday"+eid).hide();
            $("#wsday"+eid).html('');
            $("#weday"+eid).html('');
        }
    }
    else{
        $("#wsday"+eid).hide();
        $("#weday"+eid).hide();
        $("#wsday"+eid).html('');
        $("#weday"+eid).html('');
        //alert('Select Correct Time');
        return false;
       
    }
}

function editgetwhour(id,eid,sd,ed){
    
    if(id >= $("#wstime"+eid).val()){
        if(id !=""){
            $("#wsday"+eid).show();
            $("#weday"+eid).show();
            $.post("<?php echo $this->webroot;?>Ecrs/start_day",{sd:sd},function(data){$("#wsday"+eid).html(data);});
            $.post("<?php echo $this->webroot;?>Ecrs/end_day",{ed:ed},function(data){$("#weday"+eid).html(data);}); 
        }
        else{
            $("#wsday"+eid).hide();
            $("#weday"+eid).hide();
            $("#wsday"+eid).html('');
            $("#weday"+eid).html('');
        }
    }
    else{
        $("#wsday"+eid).hide();
        $("#weday"+eid).hide();
        $("#wsday"+eid).html('');
        $("#weday"+eid).html('');
        //alert('Select Correct Time');
        return false;
       
    }
}

*/



    
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
