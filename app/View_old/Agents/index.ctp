<script>
    function getdlfaddress(id){
        <?php 
        $clientId=$this->Session->read("companyid");
        if($clientId =="201"){
        ?>         
        $.post("<?php echo $this->webroot?>Agents/dlfaddress",{subscenaro:id},function(data){
            $("#AgentsField20").html(data);
        });
        <?php }?>
    }
</script>
<script>
function isNumberKey(evt)
   {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
   }
       
function submit_form(){
    var Pvalid=$(".Pvalid").val();
    
    if(Pvalid !="" && Pvalid.length < 10){
        alert('Please enter correct phone no.');
        $(".Pvalid").focus();
        return false;
    }
    else{
        $("#submit_button").hide();   
        return true; 
    }
}
</script>

<div id="tabs">
  <ul>
    <li><a href="#tabs-2">Tagging</a></li>
    <li><a href="#tabs-4">History</a></li>
    <li><a id="tbsearch" href="#tabs-6">Search</a></li>
  </ul>
<div id="tabs-2">
    <div style="font-size: 15px;margin-left:20px;color:green;padding-bottom: 10px;"><?php echo $this->Session->flash();?></div>
    <?php echo $this->Form->create("Agents",array("url"=>"save_tagging",'class'=>'form-horizontal row-border',"onsubmit"=>"return submit_form()"));?>
    <div class="container-fluid">
        <div data-widget-group="group1">
            
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Tagging</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">CALL FROM</label>
                        <div class="col-sm-2">
                            <input type="hidden" value="<?php echo isset($postid)?$postid:""?>" name="postid" >
                             <input type="hidden" value="<?php echo isset($posttype)?$posttype:""?>" name="posttype" >
                            <?php echo $this->Form->input('MSISDN',array('label'=>false,'type'=>'text','maxlength'=>'10','required'=>true,'onKeyPress'=>'return checkCharacter(event)','class'=>'form-control')); ?>
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



                                echo "<label class=\"col-sm-2 control-label\"> Scenarios</label>";
                                echo "<div class=\"col-sm-2\">".$this->Form->input('Category'.$key, array('label'=>false,'options'=>$options,'required'=>true,'empty'=>'Select Scenarios','class'=>'form-control','onChange'=>'inbound_getChild(this.id)')).
                                        "</div><div id='cat1'></div> <div id='cat2'></div> <div id='cat3'></div><div id='cat4'></div>";

                            }
                            unset($options); unset($key);unset($value);unset($i);unset($j);
                            }
                        ?>
                    </div>
                    </div>
                </div>
                
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h2>Tagging</h2>
                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div data-widget-controls="" class="panel-editbox"></div>
                    <div class="panel-body">
                        <div class="form-group">

                <?php $j = 1;
                foreach($fieldName as $post):
                    
                    $fName = $post['FieldMaster']['fieldNumber'];

                        if($j%4==0) echo '</div><div class="form-group">';
                        echo '<label class="col-sm-2 control-label">';
                        if($post['FieldMaster']['FieldType']=='DropDown'){echo "Select ";}
                        echo $post['FieldMaster']['FieldName'].'</label><div class="col-sm-2">';

                        $req = false;
                        $type = 'text';
                        $fun = "";
                        $Datepicker="";  
                        $Maxlen="";
                        $Pvalid="";

                        if($post['FieldMaster']['RequiredCheck']==1)
                        {
                                $req = true;
                        }
                        if($post['FieldMaster']['FieldValidation']=='Numeric')
                        {
                                $type = 'Number';
                                $fun = "return isNumberKey(event)";
                        }
                        if($post['FieldMaster']['FieldValidation']=='Phone')
                        {
                            $fun = "return isNumberKey(event)";
                            $Maxlen="10";
                            $Pvalid="Pvalid";

                        }
                        if($post['FieldMaster']['FieldValidation']=='Datepicker')
                        {
                                $Datepicker="date-picker";
                        }

                        if($post['FieldMaster']['FieldType']=='TextBox')
                        {
                                echo $this->Form->input('Field'.$fName,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'required'=>$req,"class"=>"form-control $Datepicker $Pvalid","maxlength"=>"$Maxlen"));
                        }
                        if($post['FieldMaster']['FieldType']=='TextArea')
                        {
                                echo $this->Form->textArea('Field'.$fName,array('label'=>false,'required'=>$req,'class'=>'form-control'));
                        }
                        if($post['FieldMaster']['FieldType']=='DropDown')
                        {
                                $option = array();
                                $options = explode(',',$fieldValue[$post['FieldMaster']['id']]);
                                $count = count($options);

                                for( $i=0; $i<$count; $i++)
                                {
                                        $option[$options[$i]] = $options[$i];
                                }

                                echo $this->Form->input('Field'.$fName,array('label'=>false,'options'=>$option,'empty'=>'Select '.$post['FieldMaster']['FieldName'],'required'=>$req,'class'=>'form-control'));
                        }

                        echo "</div>";
                endforeach;

                ?>
                            <div class="panel-footer">
                                <div class="clearfix">
                                    <input type="submit" name="submit" id="submit_button" value="submit" class="btn btn-web pull-right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>      
    <?php echo $this->Form->end(); ?>
  </div>
    
<div id="tabs-4" >
     <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Search Result</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body scrolling">  
    
  <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
      <thead><tr><?php
			echo "<th>IN CALL ID</th>";
                        echo "<th>CALL FROM</th>";
            foreach($keys as $k)
			{
                        $jk=$k-1;
                        if($k ==1){
                        echo "<th>Scenarios</th>";
                        }else{
                        echo "<th>"."Sub Scenarios ".$jk."</th>";
                        }
                
                        }
			
			foreach($fieldName as $post): 
				echo "<th>".$post['FieldMaster']['FieldName']."</th>";
			endforeach;
                        echo "<th>Call Action</th>";
                        echo "<th>Call Sub Action</th>";
			echo "<th>Calling Date</th>";		
			?>
          </tr></thead><tbody>
 	<?php
               
		foreach($history as $his){
                    echo "<tr>";
                        echo "<th>".$his['CallMaster']['SrNo']."</th>";
                        echo "<th>".$his['CallMaster']['MSISDN']."</th>";
			foreach($keys as $k){ 
                            echo "<td>".$his['CallMaster']["Category".$k]."</td>";  
                        }
                        
                        foreach($headervalue as $header){  
                            echo "<td>".$his['CallMaster'][$header]."</td>";
                        }
                        
                        
                        echo "<td>".$his['CallMaster']['CloseLoopCate1']."</td>";
                        echo "<td>".$his['CallMaster']['CloseLoopCate2']."</td>";
			echo "<td>".$his['CallMaster']['CallDate']."</td>";
                    echo "</tr>";
                }
	?>
      </tbody>
  </table>
                    
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
		<h2>Search</h2>
		<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">  
           
            <?php echo $this->Form->create("AgentSearchs",array("action"=>"search_result",'class'=>'form-horizontal row-border')); ?>  
                <div class="form-group">
                    <label class="col-sm-2 control-label">IN CALL ID</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('SrNo',array('label'=>false, 'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control')); ?>
                    </div>
                    <label class="col-sm-2 control-label">Call From</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('MSISDN',array('label'=>false, 'onKeyPress'=>'return isNumberKey(event)','class'=>'form-control')); ?>
                    </div>
               
                    <label class="col-sm-2 control-label">CallDate</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('CallDate',array('label'=>false,'class'=>'form-control date-picker')); ?>
                    </div>
                <!--</div>-->
<?php
	$keys = array_keys($ecr);
	for($i =0; $i<count($keys); $i++)
	{
		$key = $keys[$i];
		$value = explode(",",$ecr[$key]);
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
                  echo '<label class="col-sm-2 control-label">'."Scenarios ".$key."</label>";  
                }
		echo '<div class="col-sm-2">'.$this->Form->input('Category'.$keys[$i], array('label'=>false,'options'=>$options,'empty'=>$lb,'class'=>'form-control'))."</div>";
	}
	
	unset($options); unset($key);unset($value);unset($ecr);unset($i);unset($j);
?>
<?php $j = 1;
foreach($fieldName as $post):
        $fName = $post['FieldMaster']['fieldNumber'];
	echo '<label class="col-sm-2 control-label">';
	if($post['FieldMaster']['FieldType']=='DropDown'){echo "Select ";}
	echo $post['FieldMaster']['FieldName'].'</label><div class="col-sm-2">';

	$req = false;
	$type = 'text';
	$fun = "";
	
	if($post['FieldMaster']['FieldValidation']=='Numeric')
	{		
		$fun = "return isNumberKey(event)";
	}
	
	if($post['FieldMaster']['FieldType']=='DropDown')
	{
		$option = array();
		$options = explode(',',$fieldValue[$post['FieldMaster']['id']]);
		$count = count($options);
		
		for( $i=0; $i<$count; $i++)
		{
			$option[$options[$i]] = $options[$i];
		}
		
		echo $this->Form->input('Field'.$fName,array('label'=>false,'options'=>$option,'empty'=>'Select'.$post['FieldMaster']['FieldName'],'class'=>'form-control'));
	}
	else
	{
		echo $this->Form->input('Field'.$fName,array('label'=>false,'type'=>$type,'onKeyPress'=>$fun,'class'=>'form-control'));
	}
	echo "</div>";

endforeach;
?>
              
                    <div class="col-sm-6">
                        <input type="button" onclick="return searchForm(this.form,'<?php echo $this->webroot;?>AgentSearchs/search_result')"   value="search" name="submit" class="btn btn-web pull-right">
                    </div>
                </div>
    <?php //echo $this->Form->end(); ?>
               
            </div>
        </div>
    </div>
 </div> 
    



<script>
function searchForm(form,path){
    $('#searchresult').hide();
    var formData = $(form).serialize();  
    $.post(path,formData).done(function(data){
       if(data !=""){
         $('#searchresult').show();
        $("#search_result").html(data);
        }
        else{
         $('#searchresult').show();
         $("#search_result").html('<span>Result Not Found.</span>');
        }
    });
    return true;
}
</script>

    
<!--Search Result Start -->
<div id="searchresult" style="display:none;">
     <div class="container-fluid">
    <div data-widget-group="group1">
<div style="display:none;" class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Search Result</h2>
                <div class="panel-ctrls"></div>
            </div>
           <div class="panel-body  scrolling" id="search_result">
                
            </div>
            <div class="panel-footer"></div>
        </div>
        
    </div>
     </div>
</div>
<!--Search Result End --> 
  
    

    </div>
     
</div>



<!-- Date picker script file-->
<link rel="stylesheet" href="<?php echo $this->webroot;?>datepicker/jquery-ui.css">
<script src="<?php echo $this->webroot;?>datepicker/jquery-ui.js"></script>
<script>
    $(function() {
        $( ".date-picker" ).datepicker();
    });
</script>