<?php
 if(!empty($ecr)){
$keys = array_keys($ecr);
for($i =0; $i <1; $i++){
    $key = $keys[$i];
    $value = explode(",",$ecr[$key]);
    $options = array();
    for($j =0; $j<count($value); $j++)
    {
        $options[$value[$j]] = $value[$j];
    }

    if($i%3==0) echo "<tr>";
    echo "<td> Category".$key."</td>";
    echo "<td>".$this->Form->input('Category'.$key, array('label'=>false,'empty'=>'Select Category','options'=>$options,'onChange'=>'getObecrChild(this.id)'))."</td>";
    if($i%3==2) echo "</tr>";
}
unset($options); unset($key);unset($value);unset($i);unset($j);
echo "</tr>";
 }
?>
