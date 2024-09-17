<?php
$last= 0;
/*
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
 */
?>
<style>
    .text-color{color:white;}
    .text-font{font-size: 10px; }
    .bgcolor{background-color:#F62424;}
    .head{font-size:15px;font-weight:bold;font-family: fantasy;text-align: center;};
</style>
<div style="float: left;">
    <table border="1" >
        <tr class="bgcolor">
            <td class="text-color head" >Summary</td>
            <td class="text-color head">MTD</td>
            <td class="text-color head">%</td>
            <?php 
            $now = date('Y-m-d');
            $month = date("m",strtotime($now));
            $year = date("Y",strtotime($now));
           
            $first = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
            $last = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

            $thisTime = strtotime($first);
            $endTime = strtotime($last);
            while($thisTime <= $endTime){
            $thisDate = date('d-M-Y', $thisTime);
            $thisTime = strtotime('+1 day', $thisTime);
            ?>
            <td class="text-color head" ><?php echo  $thisDate; ?> </td> 
            <?php  } ?>
        </tr>
        <tr>
            <td>TOTAL RECEIVED SALES CALLS</td>
        </tr>
        <tr>
            <td>SALES LEADS</td>
        </tr>
        <tr>
            <td>MATURED SALES</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
             <td>&nbsp;</td>
        </tr>
        <tr>
            <td>PRODUCT RATES WISE</td>
        </tr>
        
    </table>   
</div>
            
    <div style="float: left;">
        <div id="dual_y_div" style="width:450px;height: 200px;"></div>
    </div>
            
    </tr>
</table>





