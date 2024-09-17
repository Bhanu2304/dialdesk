<?php   ?>
<style>
    .h2_wrap
    {
        font-size: 18px;
        overflow-wrap: break-word;

    }
    a[href="https://www.froala.com/wysiwyg-editor?k=u"] {
        display: none !important;
    }
</style>
<script> 
function ttf(url,uqry,div)
{
    var xmlhttp = false;
	
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {   //alert(xmlhttp.responseText);
			console.log(div);
            document.getElementById(div).innerHTML = xmlhttp.responseText;
        }
    }
	
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(uqry);
}		

function getTypeOb(val,url,catype,tab,slt){
    
	//console.log(catype);
	
	if(tab=='a' && catype ==='1'){
        var div="type";
		document.getElementById('type').innerHTML = '';
        document.getElementById('subtype').innerHTML = '';
        document.getElementById('subtype1').innerHTML = '';
        document.getElementById('subtype2').innerHTML = '';
		document.getElementById('subtype3').innerHTML = '';
    }
	
	
    if(tab=='a' && catype ==='2'){
        var div="subtype";
        document.getElementById('subtype').innerHTML = '';
        document.getElementById('subtype1').innerHTML = '';
        document.getElementById('subtype2').innerHTML = '';
		document.getElementById('subtype3').innerHTML = '';
    }
    
    if(tab=='a' && catype ==='3'){
        var div="subtype1";
        document.getElementById('subtype1').innerHTML = '';
        document.getElementById('subtype2').innerHTML = '';
		document.getElementById('subtype3').innerHTML = '';
    }
    
    if(tab=='a' && catype ==='4'){
        var div="subtype2";
        document.getElementById('subtype2').innerHTML = '';
		document.getElementById('subtype3').innerHTML = '';
    }
    if(tab=='a' && catype ==='5'){
        var div="subtype3";
        document.getElementById('subtype3').innerHTML = '';
    }
	
	
	if(tab=='ae' && catype ==='1'){
        var div="aetype";
		document.getElementById('aetype').innerHTML = '';
        document.getElementById('aesubtype').innerHTML = '';
        document.getElementById('aesubtype1').innerHTML = '';
        document.getElementById('aesubtype2').innerHTML = '';
		document.getElementById('aesubtype3').innerHTML = '';
    }
	
	
    if(tab=='ae' && catype ==='2'){
        var div="aesubtype";
        document.getElementById('aesubtype').innerHTML = '';
        document.getElementById('aesubtype1').innerHTML = '';
        document.getElementById('aesubtype2').innerHTML = '';
		document.getElementById('aesubtype3').innerHTML = '';
    }
    
    if(tab=='ae' && catype ==='3'){
        var div="aesubtype1";
        document.getElementById('aesubtype1').innerHTML = '';
        document.getElementById('aesubtype2').innerHTML = '';
		document.getElementById('aesubtype3').innerHTML = '';
    }
    
    if(tab=='ae' && catype ==='4'){
        var div="aesubtype2";
        document.getElementById('aesubtype2').innerHTML = '';
		document.getElementById('aesubtype3').innerHTML = '';
    }
    if(tab=='ae' && catype ==='5'){
        var div="aesubtype3";
        document.getElementById('aesubtype3').innerHTML = '';
    }

	if(tab=='b' && catype ==='1'){
        var div="btype";
		document.getElementById('btype').innerHTML = '';
        document.getElementById('bsubtype').innerHTML = '';
        document.getElementById('bsubtype1').innerHTML = '';
        document.getElementById('bsubtype2').innerHTML = '';
		document.getElementById('bsubtype3').innerHTML = '';
    }
	
	
    if(tab=='b' && catype ==='2'){
        var div="bsubtype";
        document.getElementById('bsubtype').innerHTML = '';
        document.getElementById('bsubtype1').innerHTML = '';
        document.getElementById('bsubtype2').innerHTML = '';
		document.getElementById('bsubtype3').innerHTML = '';
    }
    
    if(tab=='b' && catype ==='3'){
        var div="bsubtype1";
        document.getElementById('bsubtype1').innerHTML = '';
        document.getElementById('bsubtype2').innerHTML = '';
		document.getElementById('bsubtype3').innerHTML = '';
    }
    
    if(tab=='b' && catype ==='4'){
        var div="bsubtype2";
        document.getElementById('bsubtype2').innerHTML = '';
		document.getElementById('bsubtype3').innerHTML = '';
    }
    if(tab=='b' && catype ==='5'){
        var div="bsubtype3";
        document.getElementById('bsubtype3').innerHTML = '';
    }


if(tab=='be' && catype ==='1'){
        var div="betype";
		document.getElementById('betype').innerHTML = '';
        document.getElementById('besubtype').innerHTML = '';
        document.getElementById('besubtype1').innerHTML = '';
        document.getElementById('besubtype2').innerHTML = '';
		document.getElementById('besubtype3').innerHTML = '';
    }
	
	
    if(tab=='be' && catype ==='2'){
        var div="besubtype";
        document.getElementById('besubtype').innerHTML = '';
        document.getElementById('besubtype1').innerHTML = '';
        document.getElementById('besubtype2').innerHTML = '';
		document.getElementById('besubtype3').innerHTML = '';
    }
    
    if(tab=='be' && catype ==='3'){
        var div="besubtype1";
        document.getElementById('besubtype1').innerHTML = '';
        document.getElementById('besubtype2').innerHTML = '';
		document.getElementById('besubtype3').innerHTML = '';
    }
    
    if(tab=='be' && catype ==='4'){
        var div="besubtype2";
        document.getElementById('besubtype2').innerHTML = '';
		document.getElementById('besubtype3').innerHTML = '';
    }
    if(tab=='be' && catype ==='5'){
        var div="besubtype3";
        document.getElementById('besubtype3').innerHTML = '';
    }
    
    if(tab=='c' && catype ==='1'){
        var uqry1 = "campaign_id="+val;
        var div1 = 'ecrField';
        var div2 = 'captureField';
        ttf('get_campaign_ecr',uqry1,div1);
        ttf('get_campaign_req_fields',uqry1,div2);
        
        var div="ctype";
		document.getElementById('ctype').innerHTML = '';
        document.getElementById('csubtype').innerHTML = '';
        document.getElementById('csubtype1').innerHTML = '';
        document.getElementById('csubtype2').innerHTML = '';
		document.getElementById('csubtype3').innerHTML = '';
    }
    if(tab=='c' && catype ==='2'){
        var div="csubtype";
        document.getElementById('csubtype').innerHTML = '';
        document.getElementById('csubtype1').innerHTML = '';
        document.getElementById('csubtype2').innerHTML = '';
		document.getElementById('csubtype3').innerHTML = '';
    }
    
    if(tab=='c' && catype ==='3'){
        var div="csubtype1";
        document.getElementById('csubtype1').innerHTML = '';
        document.getElementById('csubtype2').innerHTML = '';
		document.getElementById('csubtype3').innerHTML = '';
    }
    
    if(tab=='c' && catype ==='4'){
        var div="csubtype2";
        document.getElementById('csubtype2').innerHTML = '';
		document.getElementById('csubtype3').innerHTML = '';
    }
    if(tab=='c' && catype ==='5'){
        var div="csubtype3";
        document.getElementById('csubtype3').innerHTML = '';
    }


if(tab=='d' && catype ==='1'){
        var div="dtype";
		document.getElementById('dtype').innerHTML = '';
        document.getElementById('dsubtype').innerHTML = '';
        document.getElementById('dsubtype1').innerHTML = '';
        document.getElementById('dsubtype2').innerHTML = '';
		document.getElementById('dsubtype3').innerHTML = '';
    }
	
	
    if(tab=='d' && catype ==='2'){
        var div="dsubtype";
        document.getElementById('dsubtype').innerHTML = '';
        document.getElementById('dsubtype1').innerHTML = '';
        document.getElementById('dsubtype2').innerHTML = '';
		document.getElementById('dsubtype3').innerHTML = '';
    }
    
    if(tab=='d' && catype ==='3'){
        var div="dsubtype1";
        document.getElementById('dsubtype1').innerHTML = '';
        document.getElementById('dsubtype2').innerHTML = '';
		document.getElementById('dsubtype3').innerHTML = '';
    }
    
    if(tab=='d' && catype ==='4'){
        var div="dsubtype2";
        document.getElementById('dsubtype2').innerHTML = '';
		document.getElementById('dsubtype3').innerHTML = '';
    }
    if(tab=='d' && catype ==='5'){
        var div="dsubtype3";
        document.getElementById('dsubtype3').innerHTML = '';
    }
    
    if(tab=='de' && catype ==='1'){
        var div="detype";
		document.getElementById('detype').innerHTML = '';
        document.getElementById('desubtype').innerHTML = '';
        document.getElementById('desubtype1').innerHTML = '';
        document.getElementById('desubtype2').innerHTML = '';
		document.getElementById('desubtype3').innerHTML = '';
    }
	
	
    if(tab=='de' && catype ==='2'){
        var div="desubtype";
        document.getElementById('desubtype').innerHTML = '';
        document.getElementById('desubtype1').innerHTML = '';
        document.getElementById('desubtype2').innerHTML = '';
		document.getElementById('desubtype3').innerHTML = '';
    }
    
    if(tab=='de' && catype ==='3'){
        var div="desubtype1";
        document.getElementById('desubtype1').innerHTML = '';
        document.getElementById('desubtype2').innerHTML = '';
		document.getElementById('desubtype3').innerHTML = '';
    }
    
    if(tab=='de' && catype ==='4'){
        var div="desubtype2";
        document.getElementById('desubtype2').innerHTML = '';
		document.getElementById('desubtype3').innerHTML = '';
    }
    if(tab=='de' && catype ==='5'){
        var div="desubtype3";
        document.getElementById('desubtype3').innerHTML = '';
    }

    if(tab=='ss' && catype ==='1'){
        var div="sstype";
		document.getElementById('sstype').innerHTML = '';
        document.getElementById('sssubtype').innerHTML = '';
        document.getElementById('sssubtype1').innerHTML = '';
        document.getElementById('sssubtype2').innerHTML = '';
		document.getElementById('sssubtype3').innerHTML = '';
    }
	
	
    if(tab=='ss' && catype ==='2'){
        var div="sssubtype";
        document.getElementById('sssubtype').innerHTML = '';
        document.getElementById('sssubtype1').innerHTML = '';
        document.getElementById('sssubtype2').innerHTML = '';
		document.getElementById('sssubtype3').innerHTML = '';
    }
    
    if(tab=='ss' && catype ==='3'){
        var div="sssubtype1";
        document.getElementById('sssubtype1').innerHTML = '';
        document.getElementById('sssubtype2').innerHTML = '';
		document.getElementById('sssubtype3').innerHTML = '';
    }
    
    if(tab=='ss' && catype ==='4'){
        var div="sssubtype2";
        document.getElementById('sssubtype2').innerHTML = '';
		document.getElementById('sssubtype3').innerHTML = '';
    }
    if(tab=='ss' && catype ==='5'){
        var div="sssubtype3";
        document.getElementById('sssubtype3').innerHTML = '';
    }
    
    

    if(catype ==='ua'){
        var div="utype";
        document.getElementById('usubtype').innerHTML = '';
        document.getElementById('usubtype1').innerHTML = '';
        document.getElementById('usubtype2').innerHTML = '';
		document.getElementById('subtype3').innerHTML = '';
    }
    if(catype ==='ue'){
        var div="uetype";
        document.getElementById('uesubtype').innerHTML = '';
        document.getElementById('uesubtype1').innerHTML = '';
        document.getElementById('uesubtype2').innerHTML = '';
    }
    if(catype ==='us'){
        var div="ustype";
        document.getElementById('ussubtype').innerHTML = '';
        document.getElementById('ussubtype1').innerHTML = '';
        document.getElementById('ussubtype2').innerHTML = '';
    }
    if(catype ==='uc'){
        var div="uctype";
        document.getElementById('ucsubtype').innerHTML = '';
        document.getElementById('ucsubtype1').innerHTML = '';
        document.getElementById('ucsubtype2').innerHTML = '';
    }

    if(catype ==='ss'){
        var div="sstype";
        document.getElementById('sssubtype').innerHTML = '';
        document.getElementById('sssubtype1').innerHTML = '';
        document.getElementById('sssubtype2').innerHTML = '';
    }
    
    
    
    var values = val.split('@@');
    

    
    
    
    
    
    if(val=='')
    {    }
    else
    { 
      //var url = "http://192.168.137.230/dialdesk/escalations/getEcr";
      var uqry = "Label="+catype+"&slt="+slt+"&divtype="+tab+"&type=Scenario 1&function=getTypeOb&parent="+values[0];
    
      ttf(url,uqry,div);
    }
}

</script>
<!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
<?php echo $this->Html->script('tinymce/js/tinymce/tinymce.min.js'); ?>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#" Call Flow</a></li>
    <li class="active"><a href="#">Add Ob Call Flow</a></li>
</ol>
<div class="page-heading">            
    <h1>Add Ob Call Flow</h1>
</div>
<div class="container-fluid">                     
    <div data-widget-group="group1">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="panel-inline">
                    <div class="panel-heading">
                        <h2>Add Ob Call Flow</h2>
                        <div class="panel-ctrls"></div> 
                    </div>
                    <div class="panel-body">
                        
                    <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Resolution</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body">                 
                                         <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                       
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            
                                            <?php echo $this->Form->create('AkaiFlows',array('action'=>'obresolution','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                                                                       
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-sm-2 control-label">Language</label>
                                                        <div class="col-sm-2">
                                                            <?php $options = ['En' => 'English', 'Hi' => 'Hindi'];?>
                                                            <?php echo $this->Form->input('language',array('label'=>false,'class'=>'form-control','options'=>$options,'empty'=>'Select','required'=>true)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                        <div class="row">
                                        <div class="col-sm-12">
                                                  
                                                        <label class="col-sm-2 control-label">Campaign</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$Campaign,'empty'=>'Select','onchange'=>"getTypeOb(this.value,'getobEcr','1','b')",'required'=>true)); ?>
                                                        </div>
													
                                                    <div id="btype"></div>
                                                    <div id="bsubtype"></div>
                                                    </div></div>
                                                    <div class="row">
                                                     <div class="col-sm-12">
                                                        <div id="bsubtype1"></div>
                                                        <div id="bsubtype2"></div>
                                                        <div id="bsubtype3"></div>
                                                     </div>
                                                    </div>
                                                </div>
        
                                        <div class="form-group">
                                                 <div class="row">
                                                     <div class="col-sm-12">
                                    
                                          
                                            <label class="col-sm-2 control-label">Resolution</label>
                                            <div class="col-sm-6">
                                                <?php //echo $this->Form->textArea('resolution',array('label'=>false,'class'=>'form-control','placeholder'=>'Define Resolution','required'=>true)); ?>
                                                <textarea id="txtarea" name="body" class="form-control"></textarea>
                                            </div>
                                            </div>
                                                    </div>
                                        </div>
                                        <div class="form-group" style="margin-left:365px;padding-bottom: 50px;">
                                            <div class="col-sm-2">
                                               <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                            </div> 
                                        </div>
                                        <?php echo $this->Form->end(); ?>
                                        </div>  
                                                
                                        <?php if(!empty($data)){?>
                                            
                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline1">
                                            
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                  
                                                    
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Language</th>
                                                                <th>Campaign Name</th>
                                                                <th>Scenario</th>
                                                                <th>Sub Scenario 1</th>
                                                                <th>Sub Scenario 2</th>
                                                                <th>Sub Scenario 3</th>
                                                                <th>Sub Scenario 4</th>
                                                                <th>Resolution</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $i =1; foreach($data as $d): ?>
                                                            <tr>
                                                                <td><?php echo $i++; $id = $d['ObCallFlow']['id']; ?></td>
                                                                <td><?php if($d['ObCallFlow']['language'] == "En"){ echo "<td>English</td>";}else{echo "<td>Hindi</td>";} ?></td>
                                                                <td><?php echo $d['ObCallFlow']['campaignidName']; ?></td>
                                                                <td><?php echo $d['ObCallFlow']['category']; ?></td>
                                                                <td><?php echo $d['ObCallFlow']['type']; ?></td>
                                                                <td><?php echo $d['ObCallFlow']['subtype']; ?></td>
                                                                <td><?php echo $d['ObCallFlow']['subtype1']; ?></td>
                                                                <td><?php echo $d['ObCallFlow']['subtype2']; ?></td>
                                                                <td><?php echo $d['ObCallFlow']['resolution'] ?></td>
                                                                <td>
                                                                    <a href="#"
                                                                        onclick="deleteData('<?php echo $this->webroot;?>AkaiFlows/delete_ob_resolution?id=<?php echo $id;?>')">
                                                                        <label
                                                                            class="btn btn-xs tn-midnightblue btn-raised"><i
                                                                                class="fa fa-trash"></i></label>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>

                                                    </tbody>
                                                </table>
                                                 
                                            </div>
                                       
                                            <div class="panel-footer"></div>
                                        </div>
                                               
                                        <?php }?> 
                                                
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    
            </div>   
   	</div>
    </div>
</div> 

    
<script type = "text/javascript">  
    tinymce.init({  
    selector: 'textarea',  
    width: 900,
    height: 300
    }); 
</script> 





