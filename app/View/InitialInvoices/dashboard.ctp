<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="index.html">Dashboard</a></li>
			<li><a href="#">Tables</a></li>
			<li><a href="#">Simple Tables</a></li>
		</ol>
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
	<tr align="center">
		<td><b style=" color:#000000"> S. No. </b></td>
		<td><b style=" color:#000000"> Branch </b></td>
		<td><b style=" color:#000000"> Bill Awaiting </b></td>
		<td><b style=" color:#000000"> Bill Generated </b></td>
		<td><b style=" color:#000000"> PO Approved </b></td>
		<td><b style=" color:#000000"> PO Awaiting </b></td>
		<td><b style=" color:#000000"> GRN Awaiting </b></td>
		<td><b style=" color:#000000"> Final Invoices </b></td>
	</tr>
	<?php $i=1; ?>
	<?php foreach($dashboard as $post) :
	
			foreach($post as $post1) :
			
	?>
			<tr align="center">
				<td class="warning"><b style=" color:#000000"><?php echo $i++; ?></b></td>
				<td class="success"><b style=" color:#000000"><?php echo $post1['tds']; ?></b></td>
				<td class="danger"><b style=" color:#000000"><?php echo $post1['wait_bill_approve']; ?></td>
				<td class="active"><b style=" color:#000000"><?php echo $post1['bill_gnr']; ?></td>
				<td class="success"><b style=" color:#000000"><?php echo $post1['apr_po']; ?></td>
				<td class="danger"><b style=" color:#000000"><?php echo $post1['wait_po']; ?></td>
				<td class="warning"><b style=" color:#000000"><?php echo $post1['wait_grn']; ?></td>
				<td class="success"><b style=" color:#000000"><?php echo $post1['final_inv']; ?></td>
			</tr>
			<?php endforeach; endforeach;?>
</table>
<script>
// Draw Knob examples on page
function UIPage_Knob(){
	DrawKnob($(".knob"));
}
$(document).ready(function() {
	LoadKnobScripts(UIPage_Knob);
	// Start Knob clock avery 1 sec
	setInterval(RunClock, 1000);
	// Make all sliders on page
	CreateAllSliders();
	// Add Drag-n-Drop feature to boxes
	WinMove();
});
</script>
