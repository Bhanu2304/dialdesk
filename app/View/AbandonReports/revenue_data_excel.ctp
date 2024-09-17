<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=revenue_data.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
    td {
  text-align: center;
}

</style>
<table cellspacing="0" border="1">
 <thead>
 <tr style="background-color:#317EAC; color:#FFFFFF;">         
    <?php
    $label_arr = array('Amount'); // Assuming you have more labels in your actual code
    ?>
    <th>Client Name</th>
    <?php
    foreach ($datearray as $date) {
        echo "<td style=\"text-align:center;\" width=\"80\">" . date('d', strtotime($date)) . "</td>";
    }
    ?>
</tr>
</thead>
<tbody>
    <?php
    $columnTotals = array(); // Array to store column totals
    foreach ($timearray as $time) {
        echo '<tr>';
        echo "<td>$time</td>";
        foreach ($datearray as $date) {
            foreach ($label_arr as $label => $key) {
                echo "<td>=TEXT({$data[$date][$time][$key]},\"0.00\")</td>";
                // Accumulate values for calculating column totals
                $columnTotals[$date][$key] += $data[$date][$time][$key];
            }
        }
        echo '</tr>';
    }
    ?>
    <tr style="background-color:#317EAC; color:#FFFFFF;">
        <td>Total</td>
        <?php
        foreach ($datearray as $date) {
            foreach ($label_arr as $label => $key) {
                // Print the calculated column total
                echo "<td>=TEXT(" . round($columnTotals[$date][$key], 2) . ",\"0.00\")</td>";
            }
        }
        ?>
    </tr>
</tbody>
</table>


<?php exit; ?>
