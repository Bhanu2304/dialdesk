<script>
$(function () {
    $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
});

</script>


<style type="text/css">

	.form-section {
		display: none;
	}

	.form-section.visible {
		display: contents;
	}

</style>

<script>
	function viewClient(){
        $("#view_client_form").submit();	
    } 
</script>
<script>
    
    var currentIndex = 0; 

    function showRows() {
        var rows = document.getElementById("benchmarkTable").getElementsByTagName("tr");
        for (var i = 0; i < rows.length; i++) {
            if (i >= currentIndex && i < currentIndex + 25) {
                rows[i].style.display = "table-row";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    // Show the initial rows
    showRows();

    // Event listener for Previous button
    document.getElementById("prevBtn").addEventListener("click", function() {
        if (currentIndex > 0) {
            currentIndex -= 25;
            showRows();
        }
    });

    // Event listener for Next button
    document.getElementById("nextBtn").addEventListener("click", function() {
        var rows = document.getElementById("benchmarkTable").getElementsByTagName("tr");
        if (currentIndex + 25 < rows.length) {
            currentIndex += 25;
            showRows();
        }
    });
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li class="active"><a href="#">Company Approval</a></li>
</ol>
<div class="page-heading">            
    <h1>Client Benchmarking</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            
            <div class="panel-body">
				<?php echo $this->Form->create('Home',array('url'=>'benchmarking_new','id'=>'view_client_form')); ?>
					<div class="col-sm-3">
						<?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control', 'onchange'=>'viewClient();','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
					</div>
					
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<?php if(!empty($companyid)){ ?>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            
            <div class="panel-body">

                <form action="<?php echo $this->webroot;?>AdminDetails/update_benchmarking_new" method="post"  accept-charset="utf-8" id="client_form" enctype="multipart/form-data">
				<?php $Benchmark=array("Call Count","Utilization","Agent Count","SLA","AL","ACHT","Talk Time","RL","Login Time","OB Call") ; ?>
				<span class="title">Benchmark Details-</span>
				<input type="hidden" name="client_id" id="client_id" value="<?php echo $companyid; ?>">
				<div class="container-fluid">
					<table  class="table table-striped table-bordered"  id="benchmarkTable">
						<?php $rowCount = 0; foreach ($benchmark_cat as $part) { $particular = $part['Benchmark']['benchmark'];?>
							<tbody class="form-section <?php if($rowCount == 0){ echo "visible" ;} ?> ">
							<tr>
								<td rowspan="25">
									<span style="color: #616161;"><h3><?php echo $particular;?></h3></span>
									<?php echo $this->Form->input("Benchmark.${particular}.particular", array('label'=>false, 'type'=>'hidden', 'value'=>$particular));?>
								</td>
								<td><span style="color: #616161;">Time</span></td>
								<td><span style="color: #616161;">Figures</span></td>
								<td><span style="color: #616161;">Variance</span></td>
							</tr>
							<?php for ($i = 1; $i <= 24; $i++) { 
								$hour = sprintf("%02d", $i);
								$timeValue = "${hour}:00:00";
								?>
								<tr>
									<td><?php echo $this->Form->input("Benchmark.${particular}.${i}.time", array('label'=>false, 'maxlength'=>'255', 'value'=>$timeValue));?></td>
									<td><?php echo $this->Form->input("Benchmark.${particular}.${i}.figure", array('label'=>false, 'maxlength'=>'255', 'placeholder'=>'Figures', 'value'=>''));?></td>
									<td><?php echo $this->Form->input("Benchmark.${particular}.${i}.variance", array('label'=>false, 'maxlength'=>'255', 'placeholder'=>'Variance', 'value'=>''));?></td>
								</tr>
							<?php } ?>
							</tbody>
						<?php $rowCount++; } ?>
					</table>
				</div>
				<div class="row">
					<div class="col-sm-4"><button type="button" id="prev" class="btn-web btn">Previous</button></div>
					<div class="col-sm-4"><input type="submit" value="Update" class="btn-web btn" /></div>
					<div class="col-sm-4"><button type="button" id="next" class="btn-web btn">Next</button></div>
				</div>
				</form>
                
            </div>
        </div>
    </div>
</div>


<?php } ?>
<script>
   
    const sections = document.querySelectorAll('.form-section');
	const prevButton = document.getElementById('prev');
	const nextButton = document.getElementById('next');

	let currentSection = 0;

	function showSection(section) {
		sections.forEach((s, i) => {
			if (i === section) {
				s.classList.add('visible');
			} else {
				s.classList.remove('visible');
			}
		});
		currentSection = section;
	}

	function showNextSection() {
		if (currentSection < sections.length - 1) {
			showSection(currentSection + 1);
		}
	}

	function showPrevSection() {
		if (currentSection > 0) {
			showSection(currentSection - 1);
		}
	}

	prevButton.addEventListener('click', showPrevSection);
	nextButton.addEventListener('click', showNextSection);

	showSection(0);
</script>







