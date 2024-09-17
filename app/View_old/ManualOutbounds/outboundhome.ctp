<script>
function outbound_getChild(id){  
var i;
if(id=='ManualOutboundsCategory1') i = 1;
if(id=='ManualOutboundsCategory2') i = 2;
if(id=='ManualOutboundsCategory3') i = 3;
if(id=='ManualOutboundsCategory4') i = 4;
if(id=='ManualOutboundsCategory5') i = 5;
var labeld = i;
for(; i<5; i++)
{
	try{
		document.getElementById("ManualOutboundsCategory"+(i+1)).value;					
		//document.getElementById("cat1").deleteRow(labeld);
                document.getElementById("cat"+i).innerHTML = '';
		}
		catch(err)
		{}
}

	var parent= document.getElementById("ManualOutboundsCategory1").value;
	var label = 1;

	if(parent==''){ return;}
	
	try{
		
		 parent = document.getElementById("ManualOutboundsCategory2").value;
		 label =2;
		 
		 
		 parent = document.getElementById("ManualOutboundsCategory3").value;
		 label =3;
		
		
		 parent = document.getElementById("ManualOutboundsCategory4").value;
		 label =4;
		 
		 
		 parent = document.getElementById("ManualOutboundsCategory5").value;
		 label =5;	
		 
		 }
	catch(err)
	{
		
	}
	
	$.post("/dialdesk/OutboundCategories/getChild",
        {
          Parent: parent,
          Label: label
        },
        function(data,status){
            //alert("Data: " + data + "\nStatus: " + status);
			if(data!='')
			{
                            /*
				var table = document.getElementById("cat1");
				var row   = table.insertRow(label);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				cell1.innerHTML = "Category"+(label+1);
				cell2.innerHTML = data;
                                */
                               
                                    if((label+1)==2)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat1").innerHTML = column;
                                }
                                if((label+1)==3)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat2").innerHTML = column;
                                }
                                if((label+1)==4)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat3").innerHTML = column;
                                }
                                if((label+1)==5)
                                {
                                var column = "<label class=\"col-sm-2 control-label\">Sub Scenarios"+(label)+"</label>";
                                column += "<div class=\"col-sm-2\">"+data+"</div>";
                                document.getElementById("cat4").innerHTML = column;
                                }
                               
			}			
        });
	
}
</script>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Tagging</a></li>
    <li><a href="#tabs-4">History</a></li>
    <li><a id="tbsearch" href="#tabs-6">Search</a></li>
  </ul> 
  <div id="tabs-1">
    <?php echo $this->Form->create("ManualOutbounds",array("url"=>"save_tagging_outbound")); ?>
        <div class="container-fluid">
            <div data-widget-group="group1">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2></h2>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body">
                        <div style="color: green;margin-left: 36px;"><?php echo $this->Session->flash(); ?></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">MSISDN</label>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('LeadId',array('label'=>false,'type'=>'hidden','value'=>isset($leadid)?$leadid:""));?>
                                <?php echo $this->Form->input('MSISDN',array('label'=>false,'value'=>isset($data[$keys[0]])?$data[$keys[0]]:"",'required'=>true,'maxlength'=>'10', 'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control')); ?>
                            </div>
                        <?php
                          if(!empty($ecr)){
                            $keys = array_keys($ecr);
                            for($i =0; $i <1; $i++)
                            {
                                $key = $keys[$i];
                                $value = explode(",",$ecr[$key]);
                                $options = array();
                                for($j =0; $j<count($value); $j++)
                                {
                                    $options[$value[$j]] = $value[$j];
                                }

                                echo "<label class=\"col-sm-2 control-label\"> Scenarios"."</label>";
                                echo "<div class=\"col-sm-2\">".$this->Form->input('Category1', array('label'=>false,'options'=>$options,'empty'=>'Select Category','class'=>'form-control','required'=>true,'onChange'=>'outbound_getChild(this.id)')).
                                        "</div><div id='cat1'></div><div id='cat2'></div><div class=\"form-group\"><div id='cat3'></div><div id='cat4'></div></div>";

                            }
                            unset($options); unset($key);unset($value);unset($i);unset($j);
                            }
                        ?>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <?php 
                            $clientId = $this->Session->read("clientId");
                            if($clientId ==276){
                                $col1="9";
                                $col2="3";  
                            }
                            else{
                                $col1="2";
                                $col2="2";   
                            }
                            $j = 1;
                            foreach($fieldName as $post):
                               
                                $fName = $post['ObField']['fieldNumber'];
                                if($j%4==0) echo '</div><div class="form-group">';
                                echo '<label class="col-sm-'.$col1.' control-label">';
                                if($post['ObField']['FieldType']=='DropDown'){echo "Select ";}
                                echo $post['ObField']['FieldName'].'</label><div class="col-sm-'.$col2.'">';

                                $req = false;
                                $type = 'text';
                                $fun = "";

                                if($post['ObField']['RequiredCheck']==1)
                                {
                                        $req = true;
                                }
                                if($post['ObField']['FieldValidation']=='Numeric')
                                {
                                        $type = 'Number';
                                        $fun = "return isNumberKey(event)";
                                }

                                if($post['ObField']['FieldType']=='TextBox')
                                {
                                        echo $this->Form->input('Field'.$fName,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,'class'=>'form-control'));
                                }
                                if($post['ObField']['FieldType']=='TextArea')
                                {
                                        echo $this->Form->textArea('Field'.$fName,array('label'=>false,'required'=>$req,'class'=>'form-control'));
                                }
                                if($post['ObField']['FieldType']=='DropDown')
                                {
                                        $option = array();
                                        $options = explode(',',$ObFieldValue[$post['ObField']['id']]);
                                        $count = count($options);

                                        for( $i=0; $i<$count; $i++)
                                        {
                                                $option[$options[$i]] = $options[$i];
                                        }

                                        echo $this->Form->input('Field'.$fName,array('label'=>false,'options'=>$option,'empty'=>'Select','required'=>$req,'class'=>'form-control'));
                                }

                                echo "</div>";

                            endforeach;

                            ?>
                            <div class="panel-footer">
                                <div class="clearfix">
                                    <input type="submit"  value="submit" class="btn btn-web pull-right">
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    <?php
    echo $this->Form->input('DataId',array('value'=>$DataId,'type'=>'hidden'));
    echo $this->Form->end(); 
    ?> 
      
  </div>

<?php $j = 1; ?>
  
<div id="tabs-4" >
    
     <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2></h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body "> 
                   
                    <div class="scrolling">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
        <thead>
            <tr>		
                <?php
             
                $ks = array_keys($ecrNew);
                
                  echo "<th>IN CALL ID</th>";
                    echo "<th>CALL FROM</th>";
                    
                    
             foreach($ks as $k)
			{ 
                        if($k ==1){
                    echo "<th>"."Scenarios"."</th>";
                        }
                        else{
                          echo "<th>"."Sub Scenarios ".$k=($k-1)."</th>";  
                        }
                    
                        }
                
                
                

                foreach($fieldName as $post): 
                        echo "<th>".$post['ObField']['FieldName']."</th>";
                endforeach;	
                echo "<th>Calling Date</th>";		
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            
          
            
            foreach($history as $his):
            echo "<tr>";
            //print_r($keys);
             echo "<td>".$his['OutboundMaster']['SrNo']."</td>";
             echo "<td>".$his['OutboundMaster']['MSISDN']."</td>";
           foreach($ks as $k){ 
               
               
               echo "<td>".$his['OutboundMaster']["Category".$k]."</td>";
               
           }
           
           
           
           foreach($headervalue as $header){  
                            echo "<td>".$his['OutboundMaster'][$header]."</td>";
                        }
           
             /*           
            $c=1;
            foreach($fieldName as $post){
                    echo "<td>".$his['OutboundMaster']['Field'.$c]."</td>";
             $c++;
            }*/
            
            
            
            echo "<td>".$his['OutboundMaster']['CallDate']."</td>";
            echo "</tr>";
            endforeach;
            ?>
         </tbody>
    </table> 
                    </div>              
                    
                    
                </div>
            </div>
        </div>
    </div>                    
             
                    
</div>
    
    
 
<div id="tabs-6">
  
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2></h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body"> 
                   <?php //echo $this->Form->create(array('class'=>'form-horizontal row-border')); ?> 
                     <?php echo $this->Form->create("ManualOutbounds",array("url"=>"search_result",'class'=>'form-horizontal row-border')); ?>  
                        <div class="form-group">
                            <label class="col-sm-2 control-label">CALL FROM</label>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('MSISDN',array('label'=>false,'maxlength'=>'10', 'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control')); ?>
                            </div>

                            <label class="col-sm-2 control-label">CallDate</label>
                            <div class="col-sm-2">
                                <?php echo $this->Form->input('CallDate',array('label'=>false,'onClick'=>"displayDatePicker('data[ObField][CallDate]');",'class'=>'form-control date-picker')); ?>
                            </div>
                            
                         
                            <?php 
                            
                                    $keys = array_keys($ecrNew);
                                    
                                   

                                    for($i =0; $i<count($keys); $i++)
                                    {
                                            $key = $keys[$i];
                                            $value = explode(",",$ecrNew[$key]);
                                            $options = array();
                                                    for($j =0; $j<count($value); $j++)
                                                    {
                                                            $options[$value[$j]] = $value[$j];
                                                    }
                                                    
                                                    
                                                    
                                               if($key ==1){
                 $lb="Select Scenarios";
		echo '<label class="col-sm-2 control-label">'."Scenarios"."</label>";
                }
                else{
                    $lb="Select Scenarios".$key=($key-1);
                   echo '<label class="col-sm-2 control-label">'."Sub Scenarios ".$key."</label>"; 
                }     
                                                    
                                                    
                                           // echo '<label class="col-sm-2 control-label">'."Category".$key."</label>";						
                                            echo '<div class="col-sm-2">'.$this->Form->input('Category.'.$keys[$i], array('label'=>false,'options'=>$options,'empty'=>$lb,'class'=>'form-control'))."</div>";
                                    }

                                    unset($options); unset($key);unset($value);unset($ecrNew);unset($i);unset($j);
                                   
                            ?>
                            

                            
                            <?php $j = 1;
                            foreach($fieldName as $post):
                                     $fName = $post['ObField']['fieldNumber'];
                                    echo '<label class="col-sm-2 control-label">';
                                    if($post['ObField']['FieldType']=='DropDown'){echo "Select ";}
                                    echo $post['ObField']['FieldName'].'</label><div class="col-sm-2">';

                                    $req = false;
                                    $type = 'text';
                                    $fun = "";

                                    if($post['ObField']['FieldValidation']=='Numeric')
                                    {		
                                            $fun = "return isNumberKey(event)";
                                    }

                                    if($post['ObField']['FieldType']=='DropDown')
                                    {
                                            $option = array();
                                            $options = explode(',',$ObFieldValue[$post['ObField']['id']]);
                                            $count = count($options);

                                            for( $i=0; $i<$count; $i++)
                                            {
                                                    $option[$options[$i]] = $options[$i];
                                            }

                                            echo $this->Form->input('Field'.$fName,array('label'=>false,'options'=>$option,'empty'=>'Select '.$post['ObField']['FieldName'],'class'=>'form-control'));
                                    }
                                    else
                                    {
                                            echo $this->Form->input('Field'.$fName,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'class'=>'form-control'));
                                    }
                                    echo "</div>";

                            endforeach;
                            ?>  
                            <!--
                            <div class="col-sm-6">
                                <input type="submit" name="submit" value="Search" class="btn-web btn">
                            </div>
                            -->
                            <div class="col-sm-6">
                                <input type="button" onclick="return obSearchForm(this.form,'<?php echo $this->webroot;?>ManualOutbounds/search_result')"   value="search" name="submit" class="btn btn-web pull-right">
                            </div>
                            
                        </div>
                    <?php echo $this->Form->end(); ?> 
                    
                    
                   
                </div>
            </div>
        </div>
    </div>                    
                
    
    <script>
function obSearchForm(form,path){
    $('#obsearchresult').hide();
    var formData = $(form).serialize();  
    $.post(path,formData).done(function(data){
       if(data !=""){
         $('#obsearchresult').show();
        $("#obsearch_result").html(data);
        }
        else{
         $('#obsearchresult').show();
         $("#obsearch_result").html('<span>Result Not Found.</span>');
        }
    });
    return true;
}
</script>


    
<!--Search Result Start -->
<div id="obsearchresult" style="display:none;">
     <div class="container-fluid">
    <div data-widget-group="group1">
<div style="display:none;" class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Search Result</h2>
                <div class="panel-ctrls"></div>
            </div>
           <div class="panel-body  scrolling" id="obsearch_result">
                
            </div>
            <div class="panel-footer"></div>
        </div>
        
    </div>
     </div>
</div>
<!--Search Result End --> 
    

<!-- Date picker script file-->
<link rel="stylesheet" href="<?php echo $this->webroot;?>datepicker/jquery-ui.css">
<script src="<?php echo $this->webroot;?>datepicker/jquery-ui.js"></script>
<script>
    $(function() {
        $( ".date-picker" ).datepicker();
    });
</script>

  
    </div>
     
</div>

