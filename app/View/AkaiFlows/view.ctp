<?php  
echo $this->Html->script('ecr');
echo $this->Html->script('assets/main/dialdesk');
?>
<script>

function selectcalltype(pid,Label)
{
    $.post("<?php echo $this->webroot?>AkaiFlows/selectCalltype",{pid:pid,Label:Label},function(data){
  
        $("#subtype"+Label).html(data);

    });
}

</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>
    <li class=""><a href="#">Call Flow</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Ecrs">Manage Call Flow</a></li>
</ol>
<div class="page-heading">
    <h1>Manage Call Flow</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Manage Call Flow</h2>
                                    </div>
                                    <div class="panel-body" id="tab3">
                                        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                            <div style="color:green;margin-left:75px;margin-top: 5px;">
                                                <?php echo $this->Session->flash(); ?></div>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_customer_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>
                                            <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab3')); ?>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert','type'=>'hidden')); ?>

                                                        <label class="col-sm-2 control-label">Call Type</label>
                                                        <div class="col-sm-4">
                                                            <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$calltype,'empty'=>'Select','onchange'=>"selectcalltype(this.value,'1')",'required'=>true)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div id="subtype1"></div>
                                                        <div id="subtype2"></div>
                                                        <div id="subtype3"></div>
                                                        <div id="subtype4"></div>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            
                                            <?php echo $this->Form->end(); ?>
                                        </div>

                                        <?php if(!empty($data3)){?>

                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline1">

                                            <div class="panel-heading">
                                                <h2>View</h2>
                                                <div class="panel-ctrls"></div>
                                            </div>

                                            <div class="panel-body1 no-padding scrolling">


                                                <table cellpadding="0" cellspacing="0" border="0"
                                                    class="table table-striped table-bordered datatables">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Campaign</th>
                                                            <th>Alert Type</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>senderID</th>
                                                            <th>smsText</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php  $i =1; foreach($data3 as $d): ?>
                                                        <tr>
                                                            <td><?php echo $i++; $id = $d['ObSMSText']['id']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['campaignidName']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['alertType']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['categoryName']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['typeName']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['subtypeName']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['subtype1Name']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['subtype2Name']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['senderID']; ?></td>
                                                            <td><?php echo $d['ObSMSText']['smsText']; ?></td>
                                                            <td>
                                                                <a href="#" class="btn-raised" data-toggle="modal"
                                                                    data-target="#esclationUpdate"
                                                                    onclick="view_edit_alert_esclation('<?php echo $id;?>','tab3')">
                                                                    <label
                                                                        class="btn btn-xs btn-midnightblue btn-raised"><i
                                                                            class="fa fa-edit"></i>
                                                                        <div class="ripple-container"></div>
                                                                    </label>
                                                                </a>
                                                                <a href="#"
                                                                    onclick="deleteData('<?php echo $this->webroot;?>ObEscalations/delete_sms?id=<?php echo $id;?>&tab=tab3')">
                                                                    <label
                                                                        class="btn btn-xs tn-midnightblue btn-raised"><i
                                                                            class="fa fa-trash"></i></label>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>

                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="panel-footer"></div>
                                        </div>

                                        <?php }?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Edit Aleart -->
        <!--
<div id="ae-data" ></div> 
-->




    </div>
</div>


<!-- Edit Login Popup -->
<div class="modal fade" id="esclationUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="top:100px;width:750px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Alert & Esclation</h4>
            </div>
            <div id="ae-data"></div>
        </div>
    </div>
</div>