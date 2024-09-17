

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Download MIS Reports</a></li>
</ol>
<div class="page-heading">            
    <h1>Download MIS File</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Download MIS File</h2>
                
            </div>
            

           
              <div class="panel-body">
  
            <div style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>        
             <hr/>    
                
             <form class="form-horizontal" method="post">
                
<div class="form-group">	
                    <div class="col-sm-2">
                        <input type="text" name="startdate" id="startdate" autocomplete="off" placeholder ="From Date" class="form-control date-picker" required="">
                        </div>
                    <div class="col-sm-2">
                        <input type="text" name="enddate" id="enddate" autocomplete="off" placeholder ="To Date" class="form-control date-picker" required="">
                        </div>
                   
                    <div class="col-sm-3">
                                <div class="btn-toolbar">
                                    <input type="submit" class="btn btn-web" value="Download" >
                                </div>
                            </div>
</div>
                    
                    <hr/>
            </form>
              </div>
        </div>
    </div>
</div> 