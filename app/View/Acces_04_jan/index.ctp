<?php 
echo $this->Html->css('css/mystyle');
?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">User Rights</a></li>
    <li class="active"><a href="#">Manage User Access</a></li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage User Access</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
				
                
                <div class="col-md-5">
                        <div class="col-xs-12">
                            <div class="input-group">							
                                <span class="input-group-addon"><i class="ti ti-user"></i></span>
                                User Type
                                <select id="selected_user" name="selected_user" class="form-control">
                                    <option value='none'>Select</option>
                                <?php 
                                    foreach($users as $key => $user){
                                        echo "<option value='".$user['user_type']['username']."'>".$user['user_type']['username']."</option>";
                                    }
                                ?>

                                </select>
                            </div>
                        </div>
                </div>
                <div class="col-md-3">
                <div class="col-xs-6">
                    <div class="input-group">
                        <br/>
                        <br/>
                    <input type="checkbox" id="all_check" name="all_check" onclick="auto_fill_all()" />&nbsp;&nbsp; Auto Fill  
                    </div>
                </div>
                <div class="col-xs-4">
                        <br/>
                <a href="homes" class="btn-web btn">Back</a>
                </div>    
                </div>
        <!-- new content -->
        <div id="main_container">
                    <div class="col-md-12" style="color:#828282;margin-top:20px;visibility:hidden;" id="menu_tree">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">User Right</label>
                                <div class="col-sm-1">
                                    <div class="assign-right" style="height:280px;width: 595px;margin-left:-100px;">
                                        <ol class="user-tree">
                                            <?php echo $UserRight;?>                                    
                                        </ol>
                                    </div>                              
                                </div>
                            </div>
                        </div>
                    </div>
        </div>    
<!-- end new content --> 

        


    




<style>
    .btn-new{
        width:70px;height:40px;font-size:16px;
        margin-top:20px;
    }
label{font-weight:normal;}
</style>
     
        <div class="col-sm-8" id="update_btn" style="visibility:hidden;margin-top:15px;">
            <input type="button" id="update_btn_main" value="Save" onclick="check()" class="btn-web btn">
        </div>
    </div>
            <div id="status" style="visibility:hidden;"><span id="status_span" style="color:green; display:block;padding-top:5px;padding-bottom:5px;margin-left:200px;">Updated Successfully</span></div>
</div>
</div>
</div>    
<?php //echo "zzzzzzzz ".Router::url( $this->here, true ); ?> 























<script>

function auto_fill_all()
{
    //$("#main_container input:checkbox").prop("checked", true);
    if(document.getElementById('all_check').checked == true)
    {
        $("#main_container input:checkbox").prop("checked", true);
    }
    else
    {
        $("#main_container input:checkbox").prop("checked", false);
        var sel = $('#selected_user').val();
            $("#status").css({"visibility": "hidden"});
        if(sel=="none"){
            
            $("#panel_box").css({"height": "150",});
            $("#menu_tree").css({"visibility": "hidden",});
            $("#update_btn").css({"visibility": "hidden"});console.log("hidden");
        }else{
        $("#main_container input:checkbox").prop("checked", false); 
        
        $.getJSON("<?php echo 'http://'.$_SERVER['SERVER_NAME'].Router::url('/Acces/index'); ?>", {user: $('#selected_user').val()}, function (data) {
            if(Object.keys(data).length === 0)
            {
               // $("#main_container input:checkbox").prop("checked", true);
                
            }
            else
            {
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
            
        }
        });
                    $("#status_span").html("Updated Successfully");
                    $("#menu_tree").css({"visibility": "visible"});
                    $("#update_btn").css({"visibility": "visible"});
                    $("#panel_box").css({"height": "500px",});                    
    }
        
        
        
    }
}


function show_child(id)
{
    if(document.getElementById(id).checked == true)
    {
        $("#a"+id+" input:checkbox").prop("checked", true);
    }
    else
    {
        $("#a"+id+" input:checkbox").prop("checked", false);
    }
}


    $('#selected_user').change(function () {
            var sel = $('#selected_user').val();
            $("#status").css({"visibility": "hidden"});
        if(sel=="none"){
            
            $("#panel_box").css({"height": "150",});
            $("#menu_tree").css({"visibility": "hidden",});
            $("#update_btn").css({"visibility": "hidden"});console.log("hidden");
        }else{
        $("#main_container input:checkbox").prop("checked", false); 
        
        $.getJSON("<?php echo 'http://'.$_SERVER['SERVER_NAME'].Router::url('/Acces/get_access_id'); ?>", {user: $('#selected_user').val()}, function (data) {
            
            if(Object.keys(data).length === 0)
            {
               // $("#main_container input:checkbox").prop("checked", true);
               
            }
            else
            {
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
            
        }
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
            $("#status_span").html("Please Select User Type");
        }else{
        
        var ride = "";
        $('input:checked').each(function () {
            ride = ride + $(this).val() + ",";

        });
        ride = ride.slice(0, ride.length - 1); 
        
        
        $.get("<?php echo 'http://'.$_SERVER['SERVER_NAME'].Router::url('/Acces/save'); ?>", {rides: ride,user: $('#selected_user').val()}, function (data) {
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
