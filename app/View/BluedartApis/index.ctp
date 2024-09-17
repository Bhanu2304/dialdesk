<?php ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Upload Service Address</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>BluedartApis">Upload Service Address</a></li>
</ol>
<div class="page-heading">            
    <h1>Upload Service Address</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Upload Service Address</h2>
            </div>
            <div class="panel-body">
                <div style="margin-left:50px;" ><?php echo $this->Session->flash();?></div>
                <form class="form-horizontal" action="<?php echo $this->webroot;?>BluedartApis" method="post" enctype="multipart/form-data"  >
                <div class="col-md-6">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                            <input type="file" name="uploadcsv" accept=".csv"  required="" >
                            <div style="margin-top:5px;" >Note - Upload only csv formate</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top:10px;">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input type="submit" class="btn btn-web pull-left" value="Submit" >
                       </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
