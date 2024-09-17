<style>
	/* Style the tab */
/* Style the vertical tab */
.tab {
    overflow: hidden;
    border-right: 1px solid #ccc;
    background-color: #f1f1f1;
    height: 100%;
    float: left;
}

/* Style the buttons inside the vertical tab */
.tab button {
    background-color: inherit;
    float: none;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 10px 15px;
    display: flex;
    transition: 0.3s;
    width: 100%;
    text-align: left;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #37474f;
	color: white;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-left: none;
    height: 100%;
}

/* Show the tab content */
.tabcontent.active {
    display: flex;
}


</style>

<?php //echo $this->Html->script('jquery-steps/build/jquery.steps.min.js'); ?>

<script>
	function viewClient(){
        $("#view_client_form").submit();	
    } 
</script>
<script>


	function openTab(evt, tabName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
            tabcontent[i].style.display = tabcontent[i].className.replace(" active", "");
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(tabName).style.display = "flex";

		evt.currentTarget.className += " active";
        document.getElementById(tabName).classList.add("active");

	}

	function changeTab(n) {
		var currentTab = document.getElementsByClassName("tabcontent active")[0].id;
        console.log("current tab=>"+currentTab);
		var tabs = document.getElementsByClassName("tablinks");
		var currentIndex;
		for (var i = 0; i < tabs.length; i++) {
			if (tabs[i].textContent.trim() === currentTab) {
				currentIndex = i;
				break;
			}
		}
        console.log("current index=>"+currentIndex);
        console.log("next index =>"+nextIndex);
		var nextIndex = currentIndex + n;
		if (nextIndex >= 0 && nextIndex < tabs.length) {
			tabs[currentIndex].className = tabs[currentIndex].className.replace(" active", "");
			document.getElementById(currentTab).style.display = "none";
			tabs[nextIndex].className += " active";
			document.getElementById(tabs[nextIndex].textContent.trim()).style.display = "flex";
		}
	}



</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Activation</a></li>
    <li class="active"><a href="#">Client Details</a></li>
</ol>
<div class="page-heading">            
    <h1>Benchmarking</h1>
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
<?php //if(!empty($companyid)){ ?>

	<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-body">
                <form action="<?php echo $this->webroot;?>AdminDetails/update_benchmarking_new" method="post" accept-charset="utf-8" id="client_form" enctype="multipart/form-data">
                    <?php $Benchmark = array("Call Count", "Utilization", "SLA", "AL", "ACHT", "Talk Time", "RL", "Login Time", "OB Call"); ?>
                    <div class="tab vertical">
                        <?php foreach ($Benchmark as $particular) { ?>
                            <button type="button" class="tablinks <?php if ($particular === $Benchmark[0]) echo "active"; ?>" onclick="openTab(event, '<?php echo $particular; ?>')"><?php echo $particular; ?></button>
                        <?php } ?>
                    </div>
                    <?php foreach ($Benchmark as $particular) { ?>
                        <div id="<?php echo $particular; ?>" class="tabcontent <?php if ($particular === $Benchmark[0]) echo "active"; ?>">
                            <table class="table table-striped table-bordered" id="benchmarkTable">
                                <tbody class="form-section">
                                    <tr>
                                        <td rowspan="25">
                                            <span style="color: #616161;"><h3><?php echo $particular; ?></h3></span>
                                            <?php echo $this->Form->input("Benchmark.${particular}.particular", array('label' => false, 'type' => 'hidden', 'value' => $particular)); ?>
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
										<td><?php echo $this->Form->input("Benchmark.${particular}.${i}.time", array('label' => false, 'maxlength' => '255', 'value' => $timeValue)); ?></td>
										<td><?php echo $this->Form->input("Benchmark.${particular}.${i}.figure", array('label' => false, 'maxlength' => '255', 'placeholder' => 'Figures', 'value' => '')); ?></td>
										<td><?php echo $this->Form->input("Benchmark.${particular}.${i}.variance", array('label' => false, 'maxlength' => '255', 'placeholder' => 'Variance', 'value' => '')); ?></td>
									</tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-4"><button type="button" id="prev" class="btn-web btn" onclick="changeTab(-1)">Previous</button></div>
                        <div class="col-sm-4"><input type="submit" value="Update" class="btn-web btn" /></div>
                        <div class="col-sm-4"><button type="button" id="next" class="btn-web btn" onclick="changeTab(1)">Next</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<?php //} ?>

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







