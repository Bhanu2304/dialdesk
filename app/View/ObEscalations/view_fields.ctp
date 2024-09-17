<?php  
//echo $this->Html->script('ecr');
echo $this->Html->script('assets/main/dialdesk');
?>
<style>
    #tab1{display: none;}
    #tab2{display: none;}
    #tab3{display: none;}
    #tab4{display: none;}
    #tab5{display: none;}
    #tab6{display: none;}
</style>
<script>
     $(document).ready(function(){ 
    <?php if(isset($tab) && $tab !=""){?>
         document.getElementById("<?php echo $tab;?>").style.display="block";
    <?php }?>
});


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




function checkCharacter(e,t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}

function IsAlphaNumeric(e)
{
        var key;
        if(window.event)
                key = window.event.keyCode;     //IE
    else
                key = e.which;                  //Firefox
     if ((key>47 && key<58) || (key>64 && key<91) || (key>96 && key<123) || key == 8 || key ==0)
     {
                return true;
     }
     else
     {
                return false;
     }
}

 function addCaptureField() {
    var cp  =$('#captureField').val();
    var smt =$('#smsTextArea').val();
    if(smt !=""){
        smt=smt+'';
    }

    var result=[];
    for(var i=0;i<cp.length;i++){
       result.push(":"+cp[i]+":");
    }                                  
     document.getElementById('smsTextArea').value =smt+result; 
}
function addEcrField() {
    var cp =$('#ecrField').val();
    var smt =$('#smsTextArea').val();
    if(smt !=""){
        smt=smt+'';
    }
    
    var result=[];
    for(var i=0;i<cp.length;i++){
       result.push(":"+cp[i]+":");  
    }
    document.getElementById('smsTextArea').value =smt+result; 
}
function removeSmsText(){
    document.getElementById('smsTextArea').value = "";
}
function get_smsHeader(val)
{
    if(val=='' || val=='email')
    {
       document.getElementById("smsHeader").innerHTML=''; 
    }
    else
    {
        var ht = '<label class="col-sm-2 control-label">Sms Header</label>'+'<div class="col-sm-2">';
        ht +='<input name="data[ObEscalations][smsHeader]" class="form-control" placeholder="SMS Header" required="required" id="ObEscalationsSmsHeader" type="text">'
        ht +='</div>';
        document.getElementById("smsHeader").innerHTML=ht;
                                                    
                                                        
                                                     
    }
}

function checkDefineAlert(){
    var alertType=$("#ObEscalationsAlertOn").val();
    var mobil=$("#ObEscalationsMobileNo").val();
    var email=$("#ObEscalationsEmail").val();
	var category=$("#ObEscalationsCategory");
	
	if(category===undefined)
	{
		alert('Please Select Category');
        return false;
	}
	var category_value = category.value;
	
	if(category_value==='')
	{
		alert('Please Select Category');
        $("#ObEscalationsCategory").focus();
        return false;
	}
    
    if(alertType ==="sms" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#ObEscalationsMobileNo").focus();
        return false;
    }
    else if(alertType ==="email" &&  email ===""){
        alert('Please enter email id.');
        $("#ObEscalationsEmail").focus();
        return false;
    }
    else if(alertType ==="both" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#ObEscalationsMobileNo").focus();
        return false;
    }
    else if(alertType ==="both" &&  email ===""){
        alert('Please enter email id.');
        $("#ObEscalationsEmail").focus();
        return false;
    }
    else{
        return true;
    }  
}

function checkDefineEsclation(){
    var alertType=$("#ObEscalationsAlertOnTab2").val();
    var mobil=$("#ObEscalationsMobileNoTab2").val();
    var email=$("#ObEscalationsEmailTab2").val();
    
    if(alertType ==="sms" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#ObEscalationsMobileNoTab2").focus();
        return false;
    }
    else if(alertType ==="email" &&  email ===""){
        alert('Please enter email id.');
        $("#ObEscalationsEmailTab2").focus();
        return false;
    }
    else if(alertType ==="both" &&  mobil ===""){
        alert('Please enter mobile no.');
        $("#ObEscalationsMobileNoTab2").focus();
        return false;
    }
    else if(alertType ==="both" &&  email ===""){
        alert('Please enter email id.');
        $("#ObEscalationsEmailTab2").focus();
        return false;
    }
    else{
        return true;
    }  
}
</script>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->webroot;?>Homes">Home</a></li>                  
    <li class=""><a href="#">Out Call Management</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Ecrs">Manage Alerts & Escalations</a></li>                    
</ol> 
<div class="page-heading">                                           
    <h1>Manage Alerts & Escalations</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
            <div class=" col-md-12"> 
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2></h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Alerts</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab1"   >                                      
                                        <div class="panel panel-default"   data-widget='{"draggable": "false"}'>   
                                            <?php if(isset($tab) && $tab ==="tab1"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_alert_esclation','onsubmit'=>'return checkDefineAlert();','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
                                                <?php echo $this->Form->hidden('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert')); ?>
                                                <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab1')); ?>
                                                <div class="form-group"> 
                                                     <label class="col-sm-2 control-label">Campaign</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$Campaign,'empty'=>'Select','onchange'=>"getTypeOb(this.value,'getEcr','1','a')",'required'=>true)); ?>
                                                    </div>
													
													
                                                    <div id="type"></div>
                                                    <div id="subtype"></div>
                                                    <div id="subtype1"></div>
                                                    <div id="subtype2"></div>
                                                    <div id="subtype3"></div>
                                                   
                                                </div> 
                                                 <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <hr>
                                                    </div>
                                                 </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Name</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('personName',array('label'=>false,'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Designation</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Alert On</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('alertOn',array('label'=>false,'class'=>'form-control','options'=>array('sms'=>'SMS','email'=>'Email','both'=>'both(sms,email)','Whatsapp'=>'Whatsapp'),'empty'=>'Select','onChange'=>'get_smsHeader(this.value)','required'=>true)); ?>
                                                    </div>
                                                    
                                                </div>
                                                <div class="form-group">

                                                    <label class="col-sm-2 control-label">Mobile No.</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('mobileNo',array('label'=>false,'maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','placeholder'=>'Mobile No.','required'=>false)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Email</label>
                                                    <div class="col-sm-6">
                                                        <?php echo $this->Form->input('email',array('type'=>"text",'label'=>false,'class'=>'form-control','placeholder'=>'Email','required'=>false)); ?>
                                                    </div>
                                                    <!--
                                                    <div id='smsHeader'></div>
                                                    -->
                                                </div>
                                                <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                                    <div class="col-sm-2">
                                                        <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                    </div>
                                                </div>
                                            <?php echo $this->Form->end();?>                  
                                        </div>

                                        <?php if(!empty($data1)){?>
                                        <div class="panel panel-default " style="margin-top: 20px;" id="panel-inline">
                                                <div class="panel-heading">
                                                    <h2>View</h2>
                                                    <div class="panel-ctrls"></div>
                                                </div>
                                               
                                                <div class="panel-body1 no-padding scrolling">
                                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                                                        <thead>
                                                          <tr>
                                                            <th>S.No</th>
															<th>CampaignId</th>
                                                            <th>Name</th>
                                                            <th>Alert Type</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                            <th>Alert On</th>
                                                            <th>Action</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($data1 as $d): $id = $d['ObMatrix']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
																	<td><?php echo $d['ObMatrix']['campaignidName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['personName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['alertType']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['categoryName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['typeName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['mobileNo']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['email']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['alertOn']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab1')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObEscalations/delete_matrix?id=<?php echo $id;?>&tab=tab1')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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
                        
                        <script>
                            function view_edit_alert_esclation(id,type){
                              
                                $.post("<?php echo $this->webroot;?>ObEscalations/view_edit_data",{id:id,type:type},function(data){
                                    $("#ae-data").html(data);
                                }); 
                            }
                        </script>
                        
                        <!-- Edit Login Popup -->
                        <!--
                        <div class="modal fade" id="esclationUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" style="top:250px;" >
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Edit Alert & Esclation</h4>
                                    </div>
                                     <div id="ae-data" ></div> 
                                </div>
                            </div>
                        </div>
                        -->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define SMS To Caller</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab3"  >                 
                                         <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                        <?php if(isset($tab) && $tab === "tab3"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_customer_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                                                                       
                                            <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab3')); ?>
                                           
                                        <div class="form-group">
                                        <div class="row">
                                        <div class="col-sm-12">
                                            
                                                <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert','type'=>'hidden')); ?>
                                                  
                                                        <label class="col-sm-2 control-label">Campaign</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$Campaign,'empty'=>'Select','onchange'=>"getTypeOb(this.value,'getEcr','1','b')",'required'=>true)); ?>
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
                                            <label class="col-sm-2 control-label">Sender ID</label>
                                            <div class="col-sm-2">
                                                <?php echo $this->Form->input('senderID',array('label'=>false,'maxlength'=>'6','onkeypress'=>'return  IsAlphaNumeric(event)','class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                                            </div>
                                            <label class="col-sm-2 control-label">SMS Text</label>
                                            <div class="col-sm-6">
                                                <?php echo $this->Form->textArea('smsText',array('label'=>false,'class'=>'form-control','placeholder'=>'Validated SMS Text Otherwise message will be failed','required'=>true)); ?>
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
                                                
                                        <?php if(!empty($data3)){?>
                                            
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
																<th>Campaign</th>
                                                                <th>Alert Type</th>
                                                                <th>Scenario</th>
                                                                <th>Sub Scenario 1</th>
                                                                <th>Sub Scenario 2</th>
                                                                <th>Sub Scenario 3</th>
                                                                <th>Sub Scenario 4</th>
                                                                <th>senderID</th>
                                                                <th>smsText</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $i =1; foreach($data3 as $d): ?>
                                                                <tr>
                                                                    <td><?php echo $i++; $id = $d['ObSMSText']['id']; ?></td>                                                               
									<td><?php echo $d['ObSMSText']['campaignidName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['alertType']; ?></td>
                                                                     <td><?php echo $d['ObSMSText']['categoryName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['typeName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['senderID']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['smsText']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab3')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObEscalations/delete_sms?id=<?php echo $id;?>&tab=tab3')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Whatsapp To Caller</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab3"  >                 
                                         <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                        <?php if(isset($tab) && $tab === "tab5"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_customer_whatsapptext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                                                                       
                                            <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab5')); ?>
                                           
                                        <div class="form-group">
                                        <div class="row">
                                        <div class="col-sm-12">
                                            
                                                <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','value'=>'Alert','type'=>'hidden')); ?>
                                                  
                                                        <label class="col-sm-2 control-label">Campaign</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$Campaign,'empty'=>'Select','onchange'=>"getTypeOb(this.value,'getEcr','1','ss')",'required'=>true)); ?>
                                                        </div>
													
                                                    <div id="sstype"></div>
                                                    <div id="sssubtype"></div>
                                                    </div></div>
                                                    <div class="row">
                                                     <div class="col-sm-12">
                                                        <div id="sssubtype1"></div>
                                                        <div id="sssubtype2"></div>
                                                        <div id="sssubtype3"></div>
                                                     </div>
                                                    </div>
                                                </div>
        
                                        <div class="form-group">
                                                 <div class="row">
                                                     <div class="col-sm-12">
                                                        <label class="col-sm-2 control-label">Vendor</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('vendor',array('label'=>false,'class'=>'form-control','options'=>array('Pratesh'=>'Vendor 1','AiSensy'=>'Vendor 2'),'empty'=>'Select','onChange'=>'get_smsHeader(this.value)','required'=>true)); ?>
                                                        </div>

                                                        <label class="col-sm-2 control-label">Namespace</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('template_id',array('type'=>'text','label'=>false,'class'=>'form-control','placeholder'=>'Namespace','required'=>true)); ?>
                                                        </div>
                                                        <label class="col-sm-2 control-label">Template Name</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('template_name',array('label'=>false,'class'=>'form-control','placeholder'=>'Template Name','required'=>true)); ?>
                                                        </div>
                                                        
                                                        </div>
                                                    </div>
                                        </div>
                                        <div class="form-group">
                                                 <div class="row">
                                                     <div class="col-sm-12">
                                                        <label class="col-sm-2 control-label">Api Key</label>
                                                        <div class="col-sm-2">
                                                            <?php echo $this->Form->input('api_key',array('label'=>false,'class'=>'form-control','placeholder'=>'Api Key','required'=>true)); ?>
                                                        </div>
                                                     <label class="col-sm-2 control-label">Social Type</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('alertOn',array('label'=>false,'class'=>'form-control','options'=>array('Whatsapp'=>'Whatsapp'),'empty'=>'Select','onChange'=>'get_smsHeader(this.value)','required'=>true)); ?>
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
                                                
                                        <?php if(!empty($data5)){?>
                                            
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
																<th>Campaign</th>
                                                                <th>Alert Type</th>
                                                                <th>Scenario</th>
                                                                <th>Sub Scenario 1</th>
                                                                <th>Sub Scenario 2</th>
                                                                <th>Sub Scenario 3</th>
                                                                <th>Sub Scenario 4</th>
                                                                <th>Namespace</th>
                                                                <th>Template Name</th>
                                                                <th>Api key</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $i =1; foreach($data5 as $d): ?>
                                                                <tr>
                                                                    <td><?php echo $i++; $id = $d['ObTemplate']['id']; ?></td>                                                               
									<td><?php echo $d['ObTemplate']['campaignidName']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['alertType']; ?></td>
                                                                     <td><?php echo $d['ObTemplate']['categoryName']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['typeName']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['template_id']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['template_name']; ?></td>
                                                                    <td><?php echo $d['ObTemplate']['api_key']; ?></td>
                                                                    <td>
                                                                        <!-- <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php //echo $id;?>','tab3')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a>  -->
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObEscalations/delete_whatsapp_caller?id=<?php echo $id;?>&tab=tab3')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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
                        
   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Internal Communications</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab4" >                 
                                             <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                            <?php if(isset($tab) && $tab ==="tab4"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_smstext','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                                                                       
                                            <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab4')); ?>
                                        
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><b>Alert Type</b></label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','options'=>array('Alert'=>'Alert','Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Campaign</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$Campaign,'empty'=>'Select','onchange'=>"getTypeOb(this.value,'getEcr','1','c')",'required'=>true)); ?>
                                                    </div>
                                                <div id="ctype"></div>
                                                    <div id="csubtype"></div>
                                                    <div id="csubtype1"></div>
                                                    <div id="csubtype2"></div>
                                                    <div id="csubtype3"></div>
                                            </div>

                                            <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <hr>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Vendor</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('vendor',array('label'=>false,'class'=>'form-control','options'=>array('Pratesh'=>'Vendor 1','AiSensy'=>'Vendor 2'),'empty'=>'Select','onChange'=>'get_smsHeader(this.value)','required'=>true)); ?>
                                                </div>

                                                <label class="col-sm-2 control-label"><b>Namespace</b></label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('template_id',array('label'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Namespace','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label"><b>Template Name</b></label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('template_name',array('label'=>false,'class'=>'form-control','placeholder'=>'Template Name','required'=>true)); ?>
                                                </div>

                                            </div>
                                     

                                            <div class="form-group">
                                                   
                                                    <label class="col-sm-2 control-label"><b>Api Key</b></label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('api_key',array('label'=>false,'class'=>'form-control','placeholder'=>'Api Key','required'=>true)); ?>
                                                    </div>
                                            </div>
                                            <br>
                                 
                                              <!--  
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Sender ID</label>
                                                <div class="col-sm-2">
                                                    <?php //echo $this->Form->input('senderID',array('label'=>false,'pattern'=>'.{6,6}','maxlength'=>'6','onkeypress'=>'return  IsAlphaNumeric(event)','class'=>'form-control','placeholder'=>'Sender ID','required'=>true)); ?>
                                                </div>
                                            </div>
                                             -->
                                            <div class="form-group">                                             
                                                <label class="col-sm-2 control-label">Add Fields</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('capturefield',array('label'=>false,'options'=>$field_send1,'value'=>'','multiple'=>'multiple',"ng-model"=>"select",'style'=>'height: 125px','id'=>'captureField','class'=>'form-control'));?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button onclick="addCaptureField();"  type="button"  class="btn-web btn">Add+</button>
                                                    <button onclick="removeSmsText();" type="button"  class="btn-web btn">Clear </button>                    
                                                </div>
                                               
                                            </div>
                                                <br>
                                            <div class="form-group">                                             
                                                <label class="col-sm-2 control-label">Add Fields</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->input('ecrfields',array('label'=>false,'options'=>$field_send2,'multiple'=>'multiple',"ng-model"=>"select2",'style'=>'height: 125px','id'=>'ecrField','class'=>'form-control'));?>
        
                                                </div>
                                                <div class="col-sm-2">
                                                    <button onclick="addEcrField();" type="button"  class="btn-web btn">Add+</button>
                                                    <button onclick="removeSmsText();" type="button"  class="btn-web btn">Clear </button>
                                                </div>
                                            </div>
                                         <br>
                                            <div class="form-group">                                             
                                                <label class="col-sm-2 control-label">SMS Text</label>
                                                <div class="col-sm-6">
                                                    <?php echo $this->Form->textArea('smsText',array('label'=>false,'class'=>'form-control','id'=>'smsTextArea','placeholder'=>'Validated SMS Text Otherwise message will be failed','readonly'=>false,'required'=>true)); ?>
                                             
                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                                <div class="col-sm-2">
                                                    <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                </div>
                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </div>
                                            
                                            
                                            <?php if(!empty($data4)){?>
                                            
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
                                                                <th>Alert Type</th>
                                                                <th>CampaignId</th>
                                                                <th>Scenario</th>
                                                                <th>Sub Scenario 1</th>
                                                                <th>Sub Scenario 2</th>
                                                                <th>Sub Scenario 3</th>
                                                                <th>Sub Scenario 4</th>
                                                               <!-- <th>senderID</th>-->
                                                                <th>smsText</th>
                                                                <th>Namespace</th>
                                                                <th>Template Name</th>
                                                                <th>Api key</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $i =1; foreach($data4 as $d): ?>
                                                                <tr >
                                                                    <td><?php echo $i++; $id = $d['ObSMSText']['id']; ?></td>                                                               
                                                                    <td><?php echo $d['ObSMSText']['alertType']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['campaignidName']; ?></td>
                                                                     <td><?php echo $d['ObSMSText']['categoryName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['typeName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['subtype2Name']; ?></td>
                                                                   <!-- <td><?php //echo $d['SMSText']['senderID']; ?></td>-->
                                                                    <td><?php echo $d['ObSMSText']['smsText']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['template_id']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['template_name']; ?></td>
                                                                    <td><?php echo $d['ObSMSText']['api_key']; ?></td>
                                                                    <td >
                                                                        <!--
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab4')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        -->
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObEscalations/delete_sms?id=<?php echo $id;?>&tab=tab4')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Define Esclation Matrix</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab2"  >                            
                                        <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                            <?php if(isset($tab) && $tab ==="tab2"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_alert_esclation','onsubmit'=>'return checkDefineEsclation();','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
                                                <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab2')); ?>
                                                <div class="form-group"> 
                                                    <label class="col-sm-2 control-label">Alert Type</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('alertType',array('label'=>false,'class'=>'form-control','options'=>array('Escalation'=>'Escalation','Escalation1'=>'Escalation1','Escalation2'=>'Escalation2','Escalation3'=>'Escalation3'),'empty'=>'Select','required'=>true)); ?>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Campaign</label>
                                                    <div class="col-sm-2">
                                                        <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','options'=>$Campaign,'empty'=>'Select','onchange'=>"getTypeOb(this.value,'getEcr','1','d')",'required'=>true)); ?>
                                                    </div>
                                                <div id="dtype"></div>
                                                    <div id="dsubtype"></div>
                                                    <div id="dsubtype1"></div>
                                                    <div id="dsubtype2"></div>
                                                    <div id="dsubtype3"></div>                                             
                                                </div>
                     
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <hr>
                                                    </div>
                                                 </div>
                                                
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">TAT</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('tat',array('label'=>false,'class'=>'form-control','placeholder'=>'TAT','required'=>true)); ?>
                                                </div>
                                                
                                                <label class="col-sm-2 control-label">Name</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('personName',array('label'=>false,'class'=>'form-control','placeholder'=>'Person Name','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Designation</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('designation',array('label'=>false,'class'=>'form-control','placeholder'=>'Designation','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Alert On</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('alertOn',array('label'=>false,'id'=>'ObEscalationsAlertOnTab2','class'=>'form-control','options'=>array('sms'=>'SMS','email'=>'Email','both'=>'both(sms,email)','Whatsapp'=>'Whatsapp'),'empty'=>'Select','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Mobile No.</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('mobileNo',array('label'=>false,'id'=>'ObEscalationsMobileNoTab2','maxlength'=>'10','onkeypress'=>'return checkCharacter(event,this)','class'=>'form-control','placeholder'=>'Mobile No.','required'=>false)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('email',array('label'=>false,'id'=>'ObEscalationsEmailTab2','class'=>'form-control','placeholder'=>'Email','required'=>false)); ?>
                                                </div>
                                                 
                                            </div>
                                            <div class="form-group" style="margin-left:138px;padding-bottom: 50px;">
                                                <div class="col-sm-2">
                                                    <input type="submit" name="Add" value="Add" class="btn-web btn" >
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                </div>
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                        </div>  
                                                
                                      
                                               <?php if(!empty($data2)){?>
                                            
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
                                                            <th>Name</th>
                                                            <th>Alert Type</th>
                                                            <th>CampaignId</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                            <th>Alert On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($data2 as $d): $id = $d['ObMatrix']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['personName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['alertType']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['campaignidName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['categoryName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['typeName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['mobileNo']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['email']; ?></td>
                                                                    <td><?php echo $d['ObMatrix']['alertOn']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab2')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>ObEscalations/delete_matrix?id=<?php echo $id;?>&tab=tab2')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading">
                                        <h2>Add Whatsapp to Agent</h2>
                                        <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                                    </div>
                                    <div class="panel-body" id="tab6">                            
                                        <div class="panel panel-default"   data-widget='{"draggable": "false"}'>  
                                            <?php if(isset($tab) && $tab ==="tab6"){?>
                                                <div style="color:green;margin-left:75px;margin-top: 5px;"><?php echo $this->Session->flash(); ?></div>
                                            <?php }?>
                                            <?php echo $this->Form->create('ObEscalations',array('action'=>'save_agent_alert','onsubmit'=>'return checkDefineEsclation();','class'=>'form-horizontal row-border','style'=>'margin-right:20px;'));?>                                                                                           
                                                <?php echo $this->Form->hidden('tabType',array('label'=>false,'class'=>'form-control','value'=>'tab6')); ?>
                                                
                                            <div class="form-group">
                                                
                                                <label class="col-sm-2 control-label">Api Key</label>
                                                <div class="col-sm-2">
                                                    <?php echo $this->Form->input('api_key',array('label'=>false,'class'=>'form-control','placeholder'=>'Api Key','required'=>true)); ?>
                                                </div>
                                                <label class="col-sm-2 control-label"></label>
                                                <div class="col-sm-2">
                                                    <input type="submit" name="Add" value="Add" class="btn-web btn">
                                                </div>
                                                <label class="col-sm-2 control-label"></label>
                                                <div class="col-sm-2">
                                                    <input type="reset" name="Reset" value="Reset" class="btn-web btn" >
                                                </div>
                                               
                                            </div>
                                           
                                        <?php echo $this->Form->end(); ?>
                                        </div>  
                                                
                                      
                                               <?php if(!empty($data6)){?>
                                            
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
                                                            <th>Name</th>
                                                            <th>Alert Type</th>
                                                            <th>Scenario</th>
                                                            <th>Sub Scenario 1</th>
                                                            <th>Sub Scenario 2</th>
                                                            <th>Sub Scenario 3</th>
                                                            <th>Sub Scenario 4</th>
                                                            <th>Mobile</th>
                                                            <th>Email</th>
                                                            <th>Alert On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php  $i =1; foreach($data2 as $d): $id = $d['Matrix']['id']; ?>
                                                                <tr >
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $d['Matrix']['personName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['alertType']; ?></td>
                                                                    <td><?php echo $d['Matrix']['categoryName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['typeName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtypeName']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtype1Name']; ?></td>
                                                                    <td><?php echo $d['Matrix']['subtype2Name']; ?></td>
                                                                    <td><?php echo $d['Matrix']['mobileNo']; ?></td>
                                                                    <td><?php echo $d['Matrix']['email']; ?></td>
                                                                    <td><?php echo $d['Matrix']['alertOn']; ?></td>
                                                                    <td >
                                                                        <a  href="#" class="btn-raised" data-toggle="modal" data-target="#esclationUpdate" onclick="view_edit_alert_esclation('<?php echo $id;?>','tab2')" >
                                                                            <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-edit"></i><div class="ripple-container"></div></label>
                                                                        </a> 
                                                                        <a href="#" onclick="deleteData('<?php echo $this->webroot;?>Escalations/delete_matrix?id=<?php echo $id;?>&tab=tab2')" >
                                                                            <label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label>
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
        


<!-- Edit Aleart -->
<!--
<div id="ae-data" ></div> 
-->


        
        
    </div>
</div>


<!-- Edit Login Popup -->
<div class="modal fade" id="esclationUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="top:100px;width:750px;" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Alert & Esclation</h4>
            </div>
             <div id="ae-data"></div> 
        </div>
    </div>
</div>