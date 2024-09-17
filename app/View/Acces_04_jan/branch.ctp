<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
					<span><input type='radio' name='Accesstype'  onclick="return window.location.href='<?php echo $this->webroot;?>Acces'" > User Rights</span>
					<span style='margin-left:20px;'><input type='radio' name='Accesstype' checked > Branch Rights</span>
                </div>
				<div class="box-icons">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a class="expand-link"><i class="fa fa-expand"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
				<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
              
                <?php echo $this->Form->create('Acces',array('action'=>'branch','class'=>'form-horizontal'));?>
					
				<?php echo $this->Session->flash();?>
						
				<div class="form-group">
					<div class="col-sm-1 pull-left" >Users</div>
					<div class="col-sm-3 pull-left">
						<select name="userid" class="form-control" onchange="this.form.submit()" required>
							<option value=''>Select</option>
							<?php 
							foreach($users as $key => $user){
								$selected	=	$id==$user['tbl_user']['id']?'selected="selected"':"";
								echo "<option $selected value='".$user['tbl_user']['id']."'>".$user['tbl_user']['username']."</option>";
							}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-1 pull-left" >Branch</div>
					<div class="col-sm-3 pull-left">
						<?php foreach($branchName as $branch){?>
							<input type="checkbox" name="branch[]" value="<?php echo $branch;?>" <?php echo in_array($branch,$branchArrList)?"checked":"";?> > <span style="margin-left:10px;"><?php echo $branch;?></span><br/>
						<?php }?>
					</div>
					
					
					
					
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<input type="submit" name="Submit"  value="Submit" class="btn btn-primary btn-new pull-right"  >
					</div>
				</div>
				<?php echo $this->Form->end(); ?>
            </div>
				
            </div>
        </div>
    </div>	
</div>




























<script>

    $('#selected_user').change(function () {
            var sel = $('#selected_user').val();
            $("#status").css({"visibility": "hidden"});
        if(sel=="none"){
            
            $("#panel_box").css({"height": "150",});
            $("#menu_tree").css({"visibility": "hidden",});
            $("#update_btn").css({"visibility": "hidden"});console.log("hidden");
        }else{
        $("#main_container input:checkbox").prop("checked", false);
        
        $.getJSON("<?php echo 'http://'.$_SERVER['SERVER_NAME'].Router::url('/RideChecks/index'); ?>", {user: $('#selected_user').val()}, function (data) {

            $.each(data, function (key, val) {
                $.each(val, function (key1, val1) { 
                    var acces = val1.access;
                    var p_acces = val1.parent_access;
                    var acces_arr = acces.split(',');
                    $.each(acces_arr, function (index, value) {
                        
                        $('#' + value).prop('checked', true);
                        
                    });
                    
                    var p_acces_arr = p_acces.split(',');
                    $.each(p_acces_arr, function (index, value) { 
                        $('#' + value).prop('checked', true);
                        
                    });
                    
                }); 

            });
        });
                    $("#status_span").html("Updated Successfully");
                    $("#menu_tree").css({"visibility": "visible"});
                    $("#update_btn").css({"visibility": "visible"});
                    $("#panel_box").css({"height": "500px",});                    
    }});   
        
    
    function check() {
        var sel = $('#selected_user').val();
        if(sel=="none"){
            $("#status").css({"visibility": "visible"});
            $("#status_span").html("Please Select User");
        }else{
        
        var ride = "";
        $('input:checked').each(function () {
            ride = ride + $(this).val() + ",";

        });
        ride = ride.slice(0, ride.length - 1);        
        $.get("<?php echo 'http://'.$_SERVER['SERVER_NAME'].Router::url('/RideChecks/save'); ?>", {rides: ride,user: $('#selected_user').val()}, function (data) {
            console.log(data);
            if (data = "save") {
                $("#main_container input:checkbox").prop("checked", false);
                $("#status").css({"visibility": "visible"});
                $('#selected_user').val("none");
            }
        });
        }
    }
    

</script>
