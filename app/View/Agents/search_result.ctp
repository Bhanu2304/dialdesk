<?php $keys = array_keys($ecr);
if(isset($search) && is_array($search) && !empty($search)){
?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
    <thead>
        <tr>
            <th>CALL FROM</th>
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
        $count =3+ count($keys);
        foreach($fieldName as $post): 
                echo "<th>".$post['FieldMaster']['FieldName']."</th>";
        endforeach;
echo "</tr></thead><tbody>";

//print_r($search); exit;
        foreach($search as $serc)
        {
            echo "<tr>"; $i =1;	
                foreach($serc['CallMaster'] as $ser=>$v) 
                {	
                        echo "<td>".$v."</td>";
                        if($i==$count) {break;}
                        $i++;
                }
                
                foreach($fieldName as $post): 
                    echo "<td>".$serc['CallMaster']['Field'.$post['FieldMaster']['fieldNumber']]."</td>";
                endforeach;
        
            echo "</tr>";
        }
?>
    </tbody>
</table> 
<?php }?>           