<script>
var req="This field is required";
var pho="This field is required";
var ema="This field is required";
	
function updateClient(){
	$("#space").hide();
	$("#err").hide();
	if($.trim($("#company_name").val()) ===""){show_error('company_name',req);return false;}
	else if($.trim($("#pincode").val()) ===""){show_error('pincode',req);return false;}
	else if($.trim($("#state").val()) ===""){show_error('state',req);return false;}
	else if($.trim($("#city").val()) ===""){show_error('city',req);return false;}
	else if($.trim($("#reg_office_address1").val()) ===""){show_error('reg_office_address1',req);return false;}
	else if($.trim($("#auth_person").val()) ===""){show_error('auth_person',req);return false;}
	else if($.trim($("#contact_person1").val()) ===""){show_error('contact_person1',req);return false;}
	else if($.trim($("#cp1_phone").val()) ===""){show_error('cp1_phone',req);return false;}
	else if($.trim($("#cp1_email").val()) ===""){show_error('cp1_email',req);return false;}
	else{return true;}
}

function show_error(data_id,msg){
	$("#"+data_id).focus();
	$("#"+data_id).after("<br/ id='space'><span id='err' style='color:red;'>"+msg+"</span>");
}

function getCity(path,state,city){
	var state=$("#"+state).val();
	$.ajax({
		type:'post',
		url:path,
		data:{id:state},
		success:function(data){
			$("#"+city).html(data);
		}
	});
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
</script>
<style>
.title{color: #903}
.imgset{width:100px;height:100px;cursor:pointer;margin-left:35px;}
</style>

<link rel="stylesheet" type="text/css" media="screen" href="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" />
<style type="text/css">
    a.fancybox img {
        border: none;
        box-shadow: 0 1px 7px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }
</style>
<!--
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js"></script>
<script type="text/javascript">
    $(function($){
        var addToAll = false;
        var gallery = true;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();
</script>
-->
<?php 
if(isset($type) && $type=="edit"){$readonly=false;}else{$readonly=true;}

if(isset($data['RegistrationMaster']['status']) && $data['RegistrationMaster']['status']=="A" || $data['RegistrationMaster']['status']=="D"){
    $readonly1=true;
}
else{
  $readonly1=false;  
}

?>
<style>
font{color:#F00; font-size:14px;}
</style>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Mobile Management</a></li>
    <li class="active"><a href="#">Update Details</a></li>
</ol>
<div class="page-heading">            
    <h1>Update Details</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Update Details</h2>
            </div>
            <div class="panel-body">

                <form action="<?php echo $this->webroot;?>MobDatas/update_details_office" method="post"  accept-charset="utf-8" id="client_form" enctype="multipart/form-data">
            	<span class="title">Auto Details-</span>
           		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
        			<tr>
                		<td>
                        	<?php echo $this->Form->hidden('company_id',array('label'=>false,'value'=>isset ($data['Mobdata']['Id']) ? $data['Mobdata']['Id'] : ""));?> 
                      		
                  <span style="color: #616161;">Customer code<font>*</font></span>
      						<?php echo $this->Form->input('custcode',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['custcode']) ? $data['Mobdata']['custcode'] : ""));?> 
                     </td>
                     <td>	
                  <span style="color: #616161;">Salesman Code<font>*</font></span> 
      						<?php echo $this->Form->input('smancode',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['smancode']) ? $data['Mobdata']['smancode'] : ""));?>
							</td>
              <td>
							<span style="color: #616161;">Code creation date<font></font></span>
      						<?php echo $this->Form->input('codecreatedon',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['codecreatedon']) ? $data['Mobdata']['codecreatedon'] : ""));?> 
						</td>
                      
                     	<td>
                <span style="color: #616161;">Area code<font>*</font></span>
							<?php echo $this->Form->input('area_code',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['area_code']) ? $data['Mobdata']['area_code'] : ""));?>  
                </td> <td>
                     		<span style="color: #616161;">Route code<font>*</font></span>
				<?php echo $this->Form->input('route_code',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['route_code']) ? $data['Mobdata']['route_code'] : ""));?>   
                    	</td>
                 	</tr>
				    </table>
                
                <span class="title">Field/VRM Details-</span>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                		<td>
                      		<span style="color: #616161;">Firm Name<font>*</font></span> 
      						<?php echo $this->Form->input('custname',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['custname']) ? $data['Mobdata']['custname'] : ""));?>  
                     	</td>
                     	<td>
                     		
                    <span style="color: #616161;">Address</span> 
      						<?php echo $this->Form->input('adrress1',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['adrress1']) ? $data['Mobdata']['adrress1'] : ""));?> 
                    	</td>   
                     	<td>
                        
                    <span style="color: #616161;">Locality</span> 
                  <?php echo $this->Form->input('address2',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['address2']) ? $data['Mobdata']['address2'] : ""));?> 
                      </td>  
                      	<td>
                        
                    <span style="color: #616161;">Landmark</span> 
                  <?php echo $this->Form->input('address3',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['address3']) ? $data['Mobdata']['address3'] : ""));?> 
                      </td>    
                     	<td>
                        
                    <span style="color: #616161;">City</span> 
                  <?php echo $this->Form->input('city',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['city']) ? $data['Mobdata']['city'] : ""));?> 
                      </td>   
                 	</tr>



                     <tr>
                    <td>
                          <span style="color: #616161;">Pin code<font></font></span> 
                  <?php echo $this->Form->input('pinccode',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['pinccode']) ? $data['Mobdata']['pinccode'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Email id</span> 
                    <?php echo $this->Form->input('c_email_i',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['c_email_id']) ? $data['Mobdata']['c_email_id'] : ""));?> 
 
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Branch name</span> 
                  <?php echo $this->Form->input('branchname',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['branchname']) ? $data['Mobdata']['branchname'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Drug lic. no. 20B</span> 
                  <?php echo $this->Form->input('dlno1',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['dlno1']) ? $data['Mobdata']['dlno1'] : ""));?> 
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">Drug lic. no. 21B</span> 
                  <?php echo $this->Form->input('dlno2',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['dlno2']) ? $data['Mobdata']['dlno2'] : ""));?> 
                      </td>   
                  </tr>




                  <tr>
                    <td>
                          <span style="color: #616161;">Drug Lic. expiry date<font>*</font></span> 
                  <?php echo $this->Form->input('dlexpdate',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['dlexpdate']) ? $data['Mobdata']['dlexpdate'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Food License no.</span> 
                  <?php echo $this->Form->input('foodlicno',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['foodlicno']) ? $data['Mobdata']['foodlicno'] : ""));?> 
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Pan No.</span> 
                  <?php echo $this->Form->input('pan',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['pan']) ? $data['Mobdata']['pan'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Permanent GST no.</span> 
                  <?php echo $this->Form->input('c_permanent_gstn_no',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['c_permanent_gstn_no']) ? $data['Mobdata']['c_permanent_gstn_no'] : ""));?> 
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">Phone 1</span> 
                  <?php echo $this->Form->input('c_phone_1',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['c_phone_1']) ? $data['Mobdata']['c_phone_1'] : ""));?> 
                      </td>   
                  </tr>





                  <tr>
                    <td>
                          <span style="color: #616161;">Phone 2<font>*</font></span> 
                  <?php echo $this->Form->input('c_phone_2',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['c_phone_2']) ? $data['Mobdata']['c_phone_2'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Registered Mobile</span> 
                  <?php echo $this->Form->input('c_mobile',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['c_mobile']) ? $data['Mobdata']['c_mobile'] : ""));?> 
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Owner Name</span> 
                  <?php echo $this->Form->input('c_contact_person',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['c_contact_person']) ? $data['Mobdata']['c_contact_person'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Order Person name</span> 
                  <?php echo $this->Form->input('OrderPersonname',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['OrderPersonname']) ? $data['Mobdata']['OrderPersonname'] : ""));?> 
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">Shop closing day</span> 
                  <select id="Shopclosingday" name="Shopclosingday"  class="form-control" required="" >
                            <option value="Sun">Sun</option>
                            <option value="Mon">Mon</option>
                            <option value="Tue">Tue</option>
                            <option value="Wed">Wed</option>
                            <option value="Thus">Thus</option>
                            <option value="Fir">Fir</option>
                            <option value="Sat">Sat</option>
                            
                        </select> 
                      </td>   
                  </tr>




                     <tr>
                    <td>
                          <span style="color: #616161;">Google Lat<font>*</font></span> 
                  <?php echo $this->Form->input('GoogleLat',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['GoogleLat']) ? $data['Mobdata']['GoogleLat'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Google Lon<font>*</font></span> 
                  <?php echo $this->Form->input('GoogleLon',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['GoogleLon']) ? $data['Mobdata']['GoogleLon'] : ""));?>  
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">No. of Employees</span> 
                   <select id="NoofEmployees" name="NoofEmployees"  class="form-control" required="" >
                            <option value="0 TO 3">0 TO 3</option>
                            <option value="0 TO 3">0 TO 3</option>
                            <option value="Greater Than 8">Greater Than 8</option>
                            
                        </select> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Shop area sqft.  (Lumpsum)</span> 
                   <select id="Shopareasqft" name="Shopareasqft"  class="form-control" required="" >
                            <option value="100-200">100-200</option>
                            <option value="200-500">200-500</option>
                            <option value="Greater Than 500">Greater Than 500</option>
                            
                        </select>  
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">Computrized</span> 
                  <select id="Computrized" name="Computrized"  class="form-control" required="" >
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                           
                            
                        </select>  
                      </td>   
                  </tr>




                   <tr>
                    <td>
                          <span style="color: #616161;">Nature of outlet<font>*</font></span> 
                  <select id="Natureofoutlet" name="Natureofoutlet"  class="form-control" required="" >
                            <option value="DR/Hosp">DR/Hosp</option>
                            <option value="Market">Market</option>
                            <option value="Residential area">Residential area</option>
                            
                        </select>   
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Item Range<font>*</font></span> 
                  <select id="ItemRange" name="ItemRange"  class="form-control" required="" >
                            <option value="0 TO 2">0 TO 2</option>
                            <option value="3 TO 7">3 TO 7</option>
                            <option value="8 TO 10">8 TO 10</option>
                            
                        </select>   
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Cashier</span> 
                   <select id="Cashier" name="Cashier"  class="form-control" required="" >
                            <option value="Owner">Owner</option>
                            <option value="Employee">Employee</option>
                            <option value="Both">Both</option>
                            
                        </select> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">major companies </span> 
                   <?php echo $this->Form->input('majorcompanies',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['majorcompanies']) ? $data['Mobdata']['majorcompanies'] : ""));?>  
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">major items</span> 
                  <?php echo $this->Form->input('majoritems',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','readonly'=>$readonly,'value'=>isset ($data['Mobdata']['majoritems']) ? $data['Mobdata']['majoritems'] : ""));?>  
                      </td>   
                  </tr>


                  <tr>
                    <td>
                          <span style="color: #616161;">Image 1<font></font></span> 
                  <image src="http://dialdesk.co.in/dd_field/api/Images/<?php echo $data['Mobdata']['Uploadimage1']?>" height="200px" width="200px">
                            
                        </select>   
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Image 2<font></font></span> 
                  <image src="http://dialdesk.co.in/dd_field/api/Images/<?php echo $data['Mobdata']['Uploadimage2']?>" height="200px" width="200px">   
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Image 3</span> 
                   <image src="http://dialdesk.co.in/dd_field/api/Images/<?php echo $data['Mobdata']['Uploadimage3']?>" height="200px" width="200px"> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Image 4</span> 
                   <image src="http://dialdesk.co.in/dd_field/api/Images/<?php echo $data['Mobdata']['Uploadimage4']?>" height="200px" width="200px">  
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">Image 5</span> 
                  <image src="http://dialdesk.co.in/dd_field/api/Images/<?php echo $data['Mobdata']['Uploadimage5']?>" height="200px" width="200px"> 
                      </td>   
                  </tr>



				</table>
                <span class="title">Office Details-</span>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                        <td>
                            <span style="color: #616161;">Salesman Name<font></font></span> 
                            <?php echo $this->Form->input('smanname',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['smanname']) ? $data['Mobdata']['smanname'] : ""));?>  
                        </td>
                        <td>
                            
                    <span style="color: #616161;">Discount</span> 
                            <?php echo $this->Form->input('disc',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['disc']) ? $data['Mobdata']['disc'] : ""));?> 
                        </td>   
                        <td>
                        
                    <span style="color: #616161;">Lock Days</span> 
                  <?php echo $this->Form->input('lockdays',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['lockdays']) ? $data['Mobdata']['lockdays'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Lock nos. of Bill</span> 
                  <?php echo $this->Form->input('locknoofbills',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['locknoofbills']) ? $data['Mobdata']['locknoofbills'] : ""));?> 
                      </td>    
                        <td>
                        
                    <span style="color: #616161;">Credit Limit</span> 
                  <?php echo $this->Form->input('creditlimit',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['creditlimit']) ? $data['Mobdata']['creditlimit'] : ""));?> 
                      </td>   
                    </tr>



                     <tr>
                    <td>
                          <span style="color: #616161;">Credit Days<font></font></span> 
                  <?php echo $this->Form->input('creditdays',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['creditdays']) ? $data['Mobdata']['creditdays'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Customer Category</span> 
                  <?php echo $this->Form->input('customertype',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['customertype']) ? $data['Mobdata']['customertype'] : ""));?> 
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Area Name</span> 
                  <?php echo $this->Form->input('area',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['area']) ? $data['Mobdata']['area'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Route Name</span> 
                  <?php echo $this->Form->input('route',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['route']) ? $data['Mobdata']['route'] : ""));?> 
                      </td>    
                      <td>
                        
                    <span style="color: #616161;">Mannual Lock days</span> 
                  <?php echo $this->Form->input('mannaullocktype',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['mannaullocktype']) ? $data['Mobdata']['mannaullocktype'] : ""));?> 
                      </td>   
                  </tr>




                  <tr>
                    <td>
                          <span style="color: #616161;">TCS Flag<font></font></span> 
                  <?php echo $this->Form->input('tcs_flag',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['tcs_flag']) ? $data['Mobdata']['tcs_flag'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">TDS Flag</span> 
                  <?php echo $this->Form->input('tds_flag',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['tds_flag']) ? $data['Mobdata']['tds_flag'] : ""));?> 
                      </td>   
                      <td>
                        
                    <span style="color: #616161;">Supervisor</span> 
                  <?php echo $this->Form->input('Supervisor',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['Supervisor']) ? $data['Mobdata']['Supervisor'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Delivery Boy</span> 
                  <?php echo $this->Form->input('DeliveryBoy',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['DeliveryBoy']) ? $data['Mobdata']['DeliveryBoy'] : ""));?> 
                      </td>    
                       
                  </tr>


                </table>
                	

                 <span class="title">Not Req.-</span>
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                        <td>
                            <span style="color: #616161;">Norcotic DL no.<font></font></span> 
                            <?php echo $this->Form->input('narcoticdlno',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['narcoticdlno']) ? $data['Mobdata']['narcoticdlno'] : ""));?>  
                        </td>
                        <td>
                            
                    <span style="color: #616161;">Schedule H1 DL no.</span> 
                            <?php echo $this->Form->input('schdlno',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['schdlno']) ? $data['Mobdata']['schdlno'] : ""));?> 
                        </td>   
                        <td>
                        
                    <span style="color: #616161;">Tin No.</span> 
                  <?php echo $this->Form->input('tinno',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['tinno']) ? $data['Mobdata']['tinno'] : ""));?> 
                      </td>  
                        <td>
                        
                    <span style="color: #616161;">Cst No.</span> 
                  <?php echo $this->Form->input('cstno',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['cstno']) ? $data['Mobdata']['cstno'] : ""));?> 
                      </td>    
                        <td>
                        
                    <span style="color: #616161;">DC Day</span> 
                  <?php echo $this->Form->input('dc_day_group',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['dc_day_group']) ? $data['Mobdata']['dc_day_group'] : ""));?> 
                      </td>   
                    </tr>



                     <tr>
                    <td>
                          <span style="color: #616161;">Provisional GST no.<font></font></span> 
                  <?php echo $this->Form->input('c_provisional_gstn_no',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['c_provisional_gstn_no']) ? $data['Mobdata']['c_provisional_gstn_no'] : ""));?>  
                      </td>
                      <td>
                        
                    <span style="color: #616161;">Fax No.</span> 
                  <?php echo $this->Form->input('c_fax',array('label'=>false,'class'=>'form-control','maxlength'=>'255','style'=>'width:175px;','value'=>isset ($data['Mobdata']['c_fax']) ? $data['Mobdata']['c_fax'] : ""));?> 
                      </td>   
                         
                  </tr>

                </table>



  
				</table>


                        
           		
                
                 	<input  type="submit" value="Update" class="btn-web btn"  />
               	
                
              
               
        
        	
                    </form>
                
            </div>
        </div>
    </div>
</div>







