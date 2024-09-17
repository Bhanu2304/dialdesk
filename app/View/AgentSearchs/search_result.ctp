<?php $keys = array_keys($ecr);
if(isset($search) && is_array($search) && !empty($search)){
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
    <thead>
        <tr>
            <th>In Call Id</th>
            <th>Call From</th>
            <th>Calling Date</th>
    <?php 
        foreach($keys as $k)
        { 
               if($k ==1){
            echo "<th>"."Scenarios"."</th>";
            }
            else{
              echo "<th>"."Sub Scenarios ".$k=($k-1)."</th>";  
            }
        
        }

        foreach($fieldName as $post): 
                echo "<th>".$post['FieldMaster']['FieldName']."</th>";
        endforeach;	
echo "</tr></thead><tbody>";

        foreach($search as $serc):
        echo "<tr>";	
                foreach($serc['CallMaster'] as $ser=>$v) 
                {	
                        echo "<td>".$v."</td>";
                }
        endforeach;
        echo "</tr>";

?>
    </tbody>
</table> 
<?php }?>      