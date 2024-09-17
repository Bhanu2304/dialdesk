<script>
function traningDetails(){
	$("#traning_form").submit();
	}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Training Details</a></li>
    <li class="active"><a href="#">Training Details</a></li>
</ol>
<div class="page-heading margin-top-head">            
    <h2>Training Details</h2>
    <div >
        <?php echo $this->Form->create('AdminTrainings',array('action'=>'view','id'=>'traning_form')); ?>
             <?php echo $this->Form->input('client',array('label'=>false,'options'=>$client,'onchange'=>'traningDetails();','empty'=>'Select Client','class'=>'form-control client-box','required'=>true));?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <?php if(isset($client) && !empty($client)){ ?>
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>VIEW CLOSE LOOPING</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body no-padding scrolling">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                <thead>
                    <tr>
                        <th>S. No.</th>
                        <th>Date</th>		
                        <th>File 1</th>
                        <th>File 2</th>
                        <th>File 3</th>
                        <th>File 4</th>
                        <th>File 5</th>
                        <th>File 6</th>
                        <th>File 7</th>
                        <th>File 8</th>
                        <th>File 9</th>
                        <th>File 10</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;foreach($data as $post){?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            <td><?php echo date_format(date_create($post['Training']['createdate']),'d M Y');?></td>
                            <?php  
                                for($n=1; $n<=10; $n++){
                                $extension = explode('.',$post['Training']['Field'.$n]);
                                $extension = $extension[count($extension)-1]; 
                                if(in_array($extension,array('jpg','jpeg','gif','png'))){
                                    $url=$this->webroot.'upload/training_file/client_'.$post['Training']['ClientId'].'/'.$post['Training']['Field'.$n];
                                    $tag="<a href='$url' target='_blank' >".$post['Training']['Field'.$n]."</a>";
                                }
                                else{
                                    $url=$this->webroot.'TrainingMasters/download_training_file?file='.$post['Training']['Field'.$n];
                                    $tag="<a href='$url'>".$post['Training']['Field'.$n]."</a>";
                                }
                                ?>
                            <td><?php echo $tag;?></td>
                            <?php }?>
                            <td>              
                                <a href="#" onclick="deleteData('<?php echo $this->webroot;?>TrainingMasters/delete_training_file?id=<?php echo $post['Training']['id'];?>')" >
                                    <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
                                </a>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <div class="panel-footer"></div>
    </div>
    <?php }?>
        
    </div>
</div>