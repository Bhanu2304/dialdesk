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

	.nav-tabs .tab-link.active {
        background-color: #337ab7 !important;
        color: #fff !important;
    }


</style>

<script>
	function viewClient(){
        $("#view_client_form").submit();	
    } 
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
				<?php echo $this->Form->create('Home',array('url'=>'benchmarking_new1','id'=>'view_client_form')); ?>
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

			<form action="<?php echo $this->webroot;?>AdminDetails/update_benchmarking_new1" method="post"  accept-charset="utf-8" id="client_form" enctype="multipart/form-data">
			<?php 
			$Benchmark=array("No of calls","Utilization","Agent Count","SLA","AL","ACHT","Talk Time","RL","Login Time","OB Call") ; 
			?>
			<span class="title">Benchmark Details-</span>
			<input type="hidden" name="client_id" id="client_id" value="<?php echo $companyid; ?>">
			<div class="container-fluid">
				<ul class="nav nav-tabs">
					<li><a href="#" class="tab-link active" data-index="0">No of Calls</a></li>
					<li><a href="#" class="tab-link" data-index="1">Others</a></li>
				</ul>
				<table class="table table-striped table-bordered" id="benchmarkTable">
					<tbody class="form-section visible">
						<tr>
							<td rowspan="25">
								<span style="color: #616161;"><h4>No of Calls</h4></span>
								<?php echo $this->Form->input("Benchmark.No of calls.particular", array('label'=>false, 'type'=>'hidden', 'value'=>'No of calls'));?>
							</td>
							<td><span style="color: #616161;font-weight: bold;">Time</span></td>
							<td><span style="color: #616161;font-weight: bold;">Figures</span></td>
							<td><span style="color: #616161;font-weight: bold;">Variance</span></td>
						</tr>
						<?php for ($i = 1; $i <= 24; $i++) { 
							$hour = sprintf("%02d", $i);
							$timeValue = "${hour}:00:00";
							#print_r($benchmark_arr[0]['BenchmarkClient']);die;
							#$existingData = isset($benchmark_arr['BenchmarkClient'][$particular][$i]) ? $benchmark_arr['BenchmarkClient'][$particular][$i] : null;
							
						?>
						<tr>
							<td><?php echo $this->Form->input("Benchmark.No of calls.${i}.time", array('label'=>false, 'maxlength'=>'255', 'value'=>$timeValue));?></td>
							<td><?php echo $this->Form->input("Benchmark.No of calls.${i}.figure", array('label'=>false, 'maxlength'=>'255', 'placeholder'=>'Figures', 'value'=>$benchmark_data['No of calls'][$timeValue]['figure'] ));?></td>
							<td><?php echo $this->Form->input("Benchmark.No of calls.${i}.variance", array('label'=>false, 'maxlength'=>'255', 'placeholder'=>'Variance', 'value'=>$benchmark_data['No of calls'][$timeValue]['variance']));?></td>
						</tr>
						<?php } ?>
					</tbody>
					<tbody class="form-section">
						<?php $head = true; foreach ($benchmark_cat as $part) {
							$particular = $part['Benchmark']['benchmark'];
							if ($particular !== "No of calls") {
						?>
						<tr>
							<td rowspan="2">
								<span style="color: #616161;"><h4><?php echo $particular;?></h4></span>
								<?php echo $this->Form->input("Benchmark.${particular}.particular", array('label'=>false, 'type'=>'hidden', 'value'=>$particular));?>
							</td>
							<!-- <td><span style="color: #616161;">Time</span></td> -->
							<?php if($head){?>
								<td><span style="color: #616161; font-weight: bold;">Figures</span></td>
								<td><span style="color: #616161; font-weight: bold;">Variance</span></td>
							<?php } $head = false; ?>
							
						</tr>
						<?php #for ($i = 1; $i <= 1; $i++) { ?>
						<tr>
							<td><?php echo $this->Form->input("Benchmark.${particular}.figure", array('label'=>false, 'maxlength'=>'255', 'placeholder'=>'Figures', 'value'=>$benchmark_data[$particular]['figure']));?></td>
							<td><?php echo $this->Form->input("Benchmark.${particular}.variance", array('label'=>false, 'maxlength'=>'255', 'placeholder'=>'Variance', 'value'=>$benchmark_data[$particular]['variance']));?></td>
						</tr>
						<?php #} ?>
						<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-sm-4"><button type="button" id="prevBtn" class="btn-web btn">Previous</button></div>
				<div class="col-sm-4"><input type="submit" value="Update" class="btn-web btn" /></div>
				<div class="col-sm-4"><button type="button" id="nextBtn" class="btn-web btn">Next</button></div>
			</div>
		</form>

                
            </div>
        </div>
    </div>
</div>


<?php } ?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    var currentIndex = 0;

    $(".tab-link").click(function() {
        currentIndex = parseInt($(this).data('index'));
        showRows();
		$(".tab-link").removeClass("active");
        $(this).addClass("active");
    });


    $("#prevBtn").click(function() {
        if (currentIndex > 0) {
            currentIndex--;
            showRows();

			$(".tab-link").removeClass("active");
            $(".tab-link[data-index='" + currentIndex + "']").addClass("active");
        }
    });

    $("#nextBtn").click(function() {
        if (currentIndex < $(".tab-link").length - 1) {
            currentIndex++;
            showRows();

			$(".tab-link").removeClass("active");
            $(".tab-link[data-index='" + currentIndex + "']").addClass("active");
        }
    });

    function showRows() {
        $(".form-section").hide();
        $(".form-section").eq(currentIndex).show();
    }

    // Show the initial rows
    showRows();
</script>
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







