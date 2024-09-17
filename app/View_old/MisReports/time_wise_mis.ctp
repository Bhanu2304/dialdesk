<?php
$last= 0;
/*
    header("Content-Type: application/vnd.ms-excel; name='excel'");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=import_format.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
 * 
 */
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
  </head>

</html>

<style>
    .text-color{color:white;}
    .text-font{font-size: 10px;}
    .bgcolor{background-color:#F62424;}
    .head{font-size:15px;font-weight:bold;font-family: fantasy;text-align: center;};
</style>




            <div style="float: left;">
                
                <table border="1" >
                <tr class="bgcolor">
                    <td class="text-color head" colspan="2">Date</td>
                    <?php 
                   $now = date('Y-m-d');
   $month = date("m",strtotime($now));
   $year = date("Y",strtotime($now));


   $first = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
   $last = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

   $thisTime = strtotime($first);
   $endTime = strtotime($last);
   while($thisTime <= $endTime)
   {
   $thisDate = date('d-M-Y', $thisTime);
  // echo $last."<br>";

   $thisTime = strtotime('+1 day', $thisTime); // increment for loop
   
                    ?>
                    <td class="text-color head" colspan="3" ><?php echo  $thisDate; ?> </td> 
                  <?php  } ?>
                </tr>
                    <tr class="bgcolor">
                     <th class="text-color " colspan="2">Time</th>
                     <?php 
                    
                     for($i=1;$i<=$thisDate;$i++)
                         {
                     
                        ?>
                         
                    
                     <td class="text-color">Total</td>
                   <td class="text-color">Answered</td>
                     <td class="text-color">Abandon</td>
                      
                       <?php  }
                         ?>
                </tr>
               
                    <?php
$start = "7:00";
$end = "19:00";

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;

while($tNow < $tEnd){
    $tNow1 = strtotime('+30 minutes',$tNow);
     ?>
     <tr>
   
                    <td  colspan="2"><?php echo date("h:i a",$tNow)."\n To\n".date("h:i a",$tNow1); ?></td>
                     
                       </tr>
<?php  $tNow = strtotime('+30 minutes',$tNow);
      }
?>
                    
             
                <tr>
                    <td  colspan="2">Total</td>
                    
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    
                </tr>
               
            </table>   
            </div>
            
            <div style="float: left;">
                <div id="dual_y_div" style="width:450px;height: 200px;"></div>
            </div>
            
        
   
    </tr>
</table>





