<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php   ?>
<style>
    .h2_wrap {
        font-size: 18px;
        overflow-wrap: break-word;

    }
    .fr-wrapper.show-placeholder {
        /* display: none !important; */
    }
    /* .fr-wrapper.show-placeholder > div,
    .fr-wrapper.show-placeholder + a {
        text-align: 10px;
    } */
    a[href="https://www.froala.com/wysiwyg-editor?k=u"] {
        display: none !important;
    }
</style>
<script>
    function selectcalltype(pid) {
        var id = pid.value;
        $.post("<?php echo $this->webroot?>AkaiFlow/selectCalltype", {
            id: id
        }, function (data) {


            $("#callsubtype").html(data);


        });
    }

    function selectResolution(pid) {
        var id = pid.value;
        $.post("<?php echo $this->webroot?>AkaiFlow/selectResolution", {
            id: id
        }, function (data) {


            $(".h2_wrap").html(data);


        });
    }
</script>
<?php echo $this->Html->script('tinymce/js/tinymce/tinymce.min.js'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#" Call Flow</a> </li> <li class="active"><a href="#">Add Call Flow</a></li>
</ol>
<div class="page-heading">
    <h1>Add Call Flow</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Call Flow</h2>
                        <div class="panel-ctrls"></div>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Resolution</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container=""
                                            data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab3">
                                        <div class="panel panel-default" data-widget='{"draggable": "false"}'>

                                            <div style="color:green;margin-left:75px;margin-top: 5px;">
                                                <?php echo $this->Session->flash(); ?></div>
                                            <?php echo $this->Form->create('AkaiFlows',array('action'=>'index','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-sm-2 control-label">Language</label>
                                                        <div class="col-sm-2">
                                                            <?php $options = ['En' => 'English', 'Hi' => 'Hindi'];?>
                                                            <?php echo $this->Form->input('language',array('label'=>false,'class'=>'form-control','options'=>$options,'empty'=>'Select','required'=>true)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <label class="col-sm-2 control-label">Scenario</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('category',array('label'=>false,'class'=>'form-control','options'=>$category,'empty'=>'Select','onchange'=>"getType(this.value,'".$this->webroot."escalations/getEcr','s')",'required'=>true)); ?>
                                                        </div>

                                                        <div id="stype"></div>
                                                        <div id="ssubtype"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div id="ssubtype1"></div>
                                                        <div id="ssubtype2"></div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <label class="col-sm-2 control-label">Resolution</label>
                                                        <div class="col-sm-10">
                                                            <?php //echo $this->Form->textArea('smsText',array('label'=>false,'class'=>'form-control','rows'=>'8','placeholder'=>'Resolution','required'=>true)); ?>
                                                            <textarea id="txtarea" name="body"
                                                                class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-left:365px;padding-bottom: 50px;">
                                                <div class="col-sm-2">
                                                    <input type="submit" name="Add" value="Add" class="btn-web btn">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="reset" name="Reset" value="Reset" class="btn-web btn">
                                                </div>
                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>

                                        <?php if(!empty($data)){?>

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
                                                            <th>Language</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>Resolution</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php  $i =1; foreach($data as $d){ ?>
                                                        <tr>
                                                            <td><?php echo $i++; $id = $d['CallFlow']['id']; ?></td>
                                                            <td><?php if($d['CallFlow']['language'] == "En"){ echo "<td>English</td>";}else{echo "<td>Hindi</td>";} ?></td>
                                                            <td><?php echo $d['CallFlow']['category']; ?></td>
                                                            <td><?php echo $d['CallFlow']['type']; ?></td>
                                                            <td><?php echo $d['CallFlow']['subtype']; ?></td>
                                                            <td><?php echo $d['CallFlow']['subtype1']; ?></td>
                                                            <td><?php echo $d['CallFlow']['subtype2']; ?></td>
                                                            <td><?php echo $d['CallFlow']['resolution']; ?>
                                                            </td>
                                                            <td>
                                                                <a href="#"
                                                                    onclick="deleteData('<?php echo $this->webroot;?>AkaiFlows/delete_resolution?id=<?php echo $id;?>')">
                                                                    <label
                                                                        class="btn btn-xs tn-midnightblue btn-raised"><i
                                                                            class="fa fa-trash"></i></label>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>

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
    </div>

<script type = "text/javascript">  
    tinymce.init({  
    selector: 'textarea',  
    width: 900,
    height: 300
    }); 
</script> 
 
