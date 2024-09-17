<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
<?php
$keys = array_keys($ecrNew); 

if(isset($search) && is_array($search) && !empty($search)){
    echo "<thead><tr>";

                echo "<th>Calling Date</th>";
        foreach($keys as $k)
        { echo "<th>"."Category".$k."</th>";}
        echo "<th>MSISDN</th>";
        
        
        foreach($fieldName as $post): 
                echo "<th>".$post['ObField']['FieldName']."</th>";
        endforeach;	
echo "</tr></thead><tbody>";
        foreach($search as $serc):
        echo "<tr>";	
                foreach($serc['OutboundMaster'] as $ser=>$v) 
                {	
                        echo "<td>".$v."</td>";
                }
        endforeach;
        echo "</tr>";
}
?>
  </tbody>
</table>  


                     



     
