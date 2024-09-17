<script>
function getClient(){
    $("#obsrdetails_form").submit();	
}
function validateExport(){
		$(".w_msg").remove();
		var fdate=$("#fdate").val();
		var ldate=$("#ldate").val();
		var campname=$("#AdminobsrDetailsCampaignName").val();
		var alocname=$("#AdminobsrDetailsAllocationName").val();
		
		if(campname && alocname !=""){
			if ((new Date(fdate).getTime()) <= (new Date(ldate).getTime())) {return true;} 
			else {
				$("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
				return false;
			}
		}
	}
	
function get_allocation_data(path,camp){
    var campid=camp.value;
    $.ajax({
            type:'post',
            url:path,
            data:{campid:campid,cid:$("#cid").val()},
            success:function(data){
                $("#AdminobsrDetailsAllocationName").html(data);
            }
    });	
}
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="box dark">
            <header>
                <div class="icons"><i class="icon-edit"></i></div>
                <h5>Outbound SR Report</h5>
            </header>   
            <div id="div-1" class="accordion-body collapse in body"  >
                <div style="width: 100%;overflow: scroll;">
                <?php echo $this->Form->create('AdminobsrDetails',array('action'=>'index','id'=>'obsrdetails_form')); ?>
                    <?php echo $this->Form->input('clientID',array('label'=>false,'onchange'=>'getClient();','options'=>$client,'value'=>isset($clientid)?$clientid:"",'id'=>'slclient','empty'=>'Select Client','required'=>true)); ?>
                <?php echo $this->Form->end(); ?>  
                
                <?php if(isset($clientid) && !empty($clientid)){ ?>
                <div>
                    <?php echo $this->Form->create('AdminobsrDetails',array('action'=>'download','onsubmit'=>'return validateExport()','id'=>'validate-form')); ?>
                        <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                        <table>
                            <tr>
                                <td><?php echo $this->Form->input('CampaignName',array('label'=>false,'onchange'=>'get_allocation_data("'.$this->webroot.'AdminobsrDetails/get_allocation",this)','options'=>$Campaign,'empty'=>'Select Campaign','required'=>true ));?></td>
                                <td><?php echo $this->Form->input('AllocationName',array('label'=>false,'options'=>'','empty'=>'Select Allocation','required'=>true ));?></td>
                            </tr>
                            <tr>
                                <td>
                                    <input  placeholder="First Date" type="text" name="data[AdminobsrDetails][startdate]" id="fdate" class="date-picker" required  >                                 
                                </td>
                                <td>
                                    <input  placeholder="Last Date" type="text" name="data[AdminobsrDetails][enddate]" id="ldate" class="date-picker" required >                            
                                    <input type="submit" class="btn btn-raised btn-default btn-primary" value="Export" style="position:relative;top:-5px;" >
                                </td>
                            </tr>
                        </table>
                        <?php echo $this->Form->hidden('cid',array('label'=>false,'id'=>'cid','value'=>isset($clientid)?$clientid:""));?>             
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
                                        foreach($r1['call_master_out'] as $val){?>
                                                <?php if($c=='1'){?>
                                                        <!--
                                                        <td><a href="#" onclick="openPopup('<?php echo $this->webroot?>SrDetails/view_details','<?php echo $val;?>');"><?php echo $val;?></a></td>
                                                        -->
                                                        <td><?php echo $val;?></td>
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
