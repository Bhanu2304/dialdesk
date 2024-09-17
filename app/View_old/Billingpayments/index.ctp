<script>
function billingDetails(){
	$('#billing_form').attr('action', '<?php echo $this->webroot;?>Billingpayments/index');
	$("#billing_form").submit();
}
function exportBilling(){
	$('#billing_form').attr('action', '<?php echo $this->webroot;?>Billingpayments/download');
	$("#billing_form").submit();
}
</script>
<div class="row-fluid">
	<div class="span12">
		<div class="box dark">
  			<header>
 				<div class="icons"><i class="icon-edit"></i></div>
          		<h5>Billing&Payment Details</h5>
			</header>
    		<div id="div-1" class="accordion-body collapse in body">
            <?php echo $this->Form->create('Billingpayments',array('id'=>'billing_form')); ?>
        		<h3>Export Billing And Payment</h3>
<table class="display table table-bordered table-condensed table-hovered sortableTable"> 
	<tr>
		<td>Select Client</td>
		<td><?php echo $this->Form->input('client',array('label'=>false,'options'=>$client,'onchange'=>'billingDetails();','empty'=>'Select Client','required'=>true));?></td>
	</tr>   
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
<?php $i=1; foreach($data as $post): ?>
<tr>
	<td><?=$i++?></td>
    <td><?php $date =  date_create($post['Training']['createdate']);
		echo date_format($date,'d M Y');
	?></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field1']); ?>"><?php echo $post['Training']['Field1']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field2']); ?>"><?php echo $post['Training']['Field2']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field3']); ?>"><?php echo $post['Training']['Field3']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field4']); ?>"><?php echo $post['Training']['Field4']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field5']); ?>"><?php echo $post['Training']['Field5']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field6']); ?>"><?php echo $post['Training']['Field6']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field7']); ?>"><?php echo $post['Training']['Field7']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field8']); ?>"><?php echo $post['Training']['Field8']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field9']); ?>"><?php echo $post['Training']['Field9']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field10']); ?>"><?php echo $post['Training']['Field10']; ?> </a></td>

</tr>
<?php endforeach; ?>

</table>
	<?php if(isset($data) && !empty($data)){?>
		<input type="button" value="Export" onclick="exportBilling();"  />
    <?php }?>
	<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
