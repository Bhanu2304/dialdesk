<style>
table.table {clear: both;margin-bottom: 6px !important;}
.table-bordered {border: 2px solid #066;border-collapse: separate;border-left: 0;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 5px;}
.table {width: 30%;margin-bottom: 20px;}
table {display: table;border-collapse: separate;border-spacing: 5px;border-color: #066;}
</style>
<div id="main_contant">
	<h1> View Process Integration</h1>
    <a href="<?php echo $this->webroot;?>ProcessIntegrations"><input type="button" value="Add New +" style="cursor:pointer;" /></a><br/><br/>
  	<table border="0" class="table table-bordered table-condensed table-hovered sortableTable">
 		<tr>
    		<td>
     			<?php 
				foreach($record as $key => $value){
        			echo $value['name'].'<br>';
				}
				?>
         	</td>
     	</tr>   
	</table>
</div>