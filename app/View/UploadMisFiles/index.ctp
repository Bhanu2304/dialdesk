<style>
    #mis_file { visibility: hidden; }
</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Upload MIS Files</a></li>
</ol>
<div class="page-heading">            
    <h1>Upload MIS Files</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Upload MIS Files</h2>
                
            </div>
            

           
              <div class="panel-body">
  
            <div style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>        
             <hr/>    
                
             <form class="form-horizontal" action="UploadMisFiles/add" method="post" enctype="multipart/form-data">
                
<div class="form-group">
                    <label class="col-sm-1 control-label">MIS Date<font style="color:red;">*</font></label>	
                    <div class="col-sm-2">
                        <input type="text" name="startdate" id="startdate" autocomplete="off" placeholder ="MIS Date" class="form-control date-picker" required="">
                        </div>
                    <div class="col-sm-3">    
                        <input type="button" id="my-button" class="btn btn-web" value="Select Files">
                         <input type="file" name="mis_file" id="mis_file" autocomplete="off" class="form-control" required=""   >
                    </div>
                    <div class="col-sm-3 col-sm-offset-3">
                                <div class="btn-toolbar">
                                    <input type="submit" class="btn btn-web" value="Upload" >
                                </div>
                            </div>
</div>
                    
                    <hr/>
            </form>
              </div>
        </div>
    </div>
</div> 





                        <script>
                            $('#my-button').click(function(){
    $('#mis_file').click();
});
                            </script>









