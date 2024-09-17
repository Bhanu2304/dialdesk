<?php ?>
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    
    if(fdate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MobDatas/export_log');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MobDatas/show_location');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-2CIJtAVNKXA13ruVI1cZmn1PdUPzP30"></script>
<script type="text/javascript">




    var markers = [
        <?php 
            foreach($data as $row){
            
                // $Dx[] =   $Data['Descr'] ;  
        ?>      
                {
                    "title": '<?php echo $row['mob_data']['UserID'];?>',
                    "lat": '<?php echo $row['mob_data']['Latitude'];?>',
                    "lng": '<?php echo $row['mob_data']['Longitude'];?>',
                    "description": '<?php echo $row['mob_data']['Descr']."#".$row['mob_data']['CreateDate']."#".$row['mob_data']['MSISDN']?>'
                }
            ,
                

        <?php } ?>
        
    ];
    window.onload = function () {
        var mapOptions = {
            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
            zoom: 40,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var Descrx  = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            lat_lng.push(myLatlng);
            var image = '<?php echo $this->webroot;?>images/walking-tour.png';
            var image1 = '<?php echo $this->webroot;?>images/tickmark1.png';
            var ChkDat = data.description.split("#"); 
            if(ChkDat[0]=='Route Trace') {
                var NewImage = image;
                }
                else {
                var NewImage = image1;
                }
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title,
                icon: NewImage
            });
            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.description);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
 
        //***********ROUTING****************//
 
        //Initialize the Path Array
        var path = new google.maps.MVCArray();
 
        //Initialize the Direction Service
        var service = new google.maps.DirectionsService();
 
        //Set the Path Stroke Color
        var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });
 
        //Loop and Draw Path Route between the Points on MAP
        for (var i = 0; i < lat_lng.length; i++) {
        
       // alert(lat_lng);
            if ((i + 1) < lat_lng.length) {
                var src = lat_lng[i]; //alert('a'+src);
                var des = lat_lng[i + 1]; //alert('b'+des);
                path.push(src);
                poly.setPath(path);
                service.route({
                    origin: src,
                    destination: des,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                }, function (result, status) { //alert(result+'uu'+status);
                    if (status == google.maps.DirectionsStatus.OK) {
                        for (var i = 0, len = result.routes[0].overview_path.length; i <= len; i++) {
                          // path.push(result.routes[i].overview_path[i]);
                        }
                    }
                });
            }
        }
    }
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Mobile Management</a></li>
    <li class="active"><a href="#">Field Executive Tracker</a></li>
</ol>
<div class="page-heading">            
    <h1>Field Executive Tracker</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Field Executive Tracker</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('MobDatas',array('action'=>'executive_tracker','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <select name="excode" id="excode" class="form-control client-box" style="width:170px;">
                    <option value="">-Select-</option>
                    <?php
                       // $i=1;
                        foreach($executive as $row1){?>
                         <option value="<?php echo $row1['Mob']['Code'];?>"><?php echo $row1['Mob']['Code'];?></option>   

                        <?php } ?>
                    </select>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="Submit" class="btn btn-web" value="View" >
                        </div>
                       
                        <!--
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        -->
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
       
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Field Executive Tracker</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
               <table class="table table-hover table-bordered" border="1" >
                                    <thead> 
                <div id="dvMap" style="width: 1200px; height: 600px"></div>
            </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        
      

    </div>
</div>




