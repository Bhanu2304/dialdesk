<!--
<script src="<?php echo $this->webroot;?>datepicker/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="<?php echo $this->webroot;?>datepicker/jquery-ui.css">
<script src="<?php echo $this->webroot;?>datepicker/jquery-ui.js"></script>
  <script>
            $(function() {
                $( ".date-picker" ).datepicker();
            });
        </script>
-->
<script>

function getClient(){
    $("#srdetails_form").submit();	
}

function validateExport(){
		$(".w_msg").remove();
		var fdate=$("#fdate").val();
		var ldate=$("#ldate").val();
			
		if ((new Date(fdate).getTime()) <= (new Date(ldate).getTime())) {return true;} 
		else {
			$("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
			return false;
		}
	}

function openPopup(path,srno,clid){
	$.ajax({
		type:'post',
		url:path,
		data:{srno:srno,cid:clid},
		success:function(data){
			$(".srpopup").show();
			$("#cover").show();
			$(".modal-backdrop").show();
			$("#srpopup_details").html(data);
		}
	});
}
function updateCloseLoop(path,cl1,cl2,srno,cid){
    
	if(cl1 !=''){
	$.ajax({
		type:'post',
		url:path,
		data:{close_cat1:cl1,close_cat2:cl2,srno:srno,cid:cid},
		success:function(data){
			if(data !=''){
				alert('Update SuccessFully.');
			}
		}
	});
	}
	else{
		alert('Close loop category not created.');
	}
}

function closePopup(){
	$(".srpopup").hide();
	$("#cover").hide();
	$(".modal-backdrop").hide();
	
}
</script>

<style>
#cover{ 
	position: absolute;
	top:0; 
	left:0; 
	background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
	z-index:1000; 
	width:100%; 
	height:100%; 
	display:none;
}
</style>

<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Inbound SR Details</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body"  >
                <div style="width: 100%;overflow: scroll;">
                <?php echo $this->Form->create('AdminsrDetails',array('action'=>'index','id'=>'srdetails_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?>  
                
                <?php if(isset($clientid) && !empty($clientid)){ ?>


                <div>
                    <?php echo $this->Form->create('AdminsrDetails',array('action'=>'download','onsubmit'=>'return validateExport()','id'=>'validate-form')); ?>
                        <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        <input style="width:100px;" placeholder="First Date" type="text" name="data[AdminsrDetails][startdate]" id="fdate" class="date-picker" required  >
                        <input style="width:100px;" placeholder="Last Date" type="text" name="data[AdminsrDetails][enddate]" id="ldate" class="date-picker" required >
                        <input type="submit" class="btn btn-raised btn-default btn-primary" value="Export" style="position:relative;top:-5px;" >
                        <?php echo $this->Form->hidden('cid',array('label'=>false,'value'=>isset($clientid)?$clientid:""));?>             
                    <?php $this->Form->end(); ?>
                </div>



                <?php 
                $NewField = explode(',',$Data[0][0]['CatField']);
                $ArrCnt   = count($NewField);
                ?>
                <table class="pagination display table table-bordered table-condensed table-hovered sortableTable" cellspacing="0" width="100%"  >
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>MSISDN</th>
                            <?php for($i=0;$i<$ArrCnt;$i++){?>
                            <th><?php echo is_numeric($NewField[$i])?'Category'.$NewField[$i]:$NewField[$i]; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(isset($data)){ 
                                foreach($data as $r1){?>
                                <tr>
                                <?php 
                                        $c=1;
                                        foreach($r1['call_master'] as $val){?>
                                                <?php if($c=='1'){?>
                                                        <td><a href="#" onclick="openPopup('<?php echo $this->webroot?>AdminsrDetails/view_details','<?php echo $val;?>','<?php echo $clientid;?>');"><?php echo $val;?></a></td>
                                                <?php }else{?>
                                                        <td><?php echo $val;?></td>
                                                <?php }?>
                                                <?php 
                                                $c++;
                                        }?>
                                        </tr>
                                        <?php 
                                }
                        }
                        ?>
                    </tbody>
                </table>
                <div id="srpopup_details"></div>
                <div id="cover"></div>
                <?php }?>
                <div>
            </div>
        </div>
    </div>
</div>



    