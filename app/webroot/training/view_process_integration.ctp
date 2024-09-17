<div class="row-fluid">
	<div class="span12">
		<div class="box dark">
  			<header>
 				<div class="icons"><i class="icon-edit"></i></div>
          		<h5>View Process Integration</h5>
			</header>
    		<div id="div-1" class="accordion-body collapse in body">
           		<table class="table table-bordered table-condensed table-hovered sortableTable">
         			<tr>
                    	<th>S.NO</th>
                        <th>Process Name</th>
                        <th>Client Name</th>
                    </tr>
                    <?php 
					$i = 1; 
					foreach($parents as $pid){?>
                        <tr>
                        	<td><?php echo $i++;?></td>
                            <td>
                            
                            
                            <?php echo $pro;?>
                            
                            
                            	<!--<ul>
                                	<li><?php echo $names[$pid][0];?>
                                    	<ul>
                                        	<?php 
											foreach ( $children[ $pid ] as $child_id ) {
											?>
                                        	<li><?php echo $names[$child_id][0];?></li>
                                            <?php }?>
                                     	</ul>
                                   	</li>
                                </ul> -->  
                            </td>
                            
                            <td><?php echo $client_name[$pid][0];?></td>
                        </tr>
                    <?php }?>
				</table>
			</div>
		</div>
	</div>
</div>


