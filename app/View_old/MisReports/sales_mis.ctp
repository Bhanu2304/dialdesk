<?php
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
    <script type="text/javascript">
        
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Galaxy','Distance', 'Brightness'],
          ['TOTAL RECEIVED CALLS', 8000, 23.3],
          ['ANSWERED CALLS', 24000, 4.5],
          ['ABANDONED CALLS', 30000, 14.3],
          ['QUALITY %', 50000, 0.9],
          ['AHT', 60000, 13.1],
          ['WORK DAYS', 60000, 13.1],
          ['AVG CAPACITY', 60000, 13.1],
          ['AVG CALL \ AGENT', 60000, 13.1]
         
        ]);
        


        var options = {
          width: 500,
          chart: {
            //title: 'Nearby galaxies',
            //subtitle: 'distance on the left, brightness on the right'
          },
          series: {
            0: { axis: 'distance' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: 'brightness' } // Bind series 1 to an axis named 'brightness'.
          },
          axes: {
            y: {
              //distance: {label: 'parsecs'}, // Left y-axis.
              //brightness: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
            }
          }
        };

      var chart = new google.charts.Bar(document.getElementById('dual_y_div'));
      chart.draw(data, options);
    };
    
    
    
    
    
    
    </script>
  </head>
 
</html>

<style>
    .text-color{color:#FFF;}
    .text-font{font-size: 15px;}
    .bgcolor{background-color:black;}
    .head{font-size:20px;font-weight:bold;font-family: fantasy;text-align: center;};
</style>



<table  style="width:900px;">
    <tr>
        <td>
            <table border="1" style="width:900px;">
                <tr class="bgcolor"><td class="text-color head">DASHBOARD</td></tr>
             </table>
        </td>   
    </tr>
    
    <tr>
        <td>
            <div style="float: left;">
                <table border="1" style="width:400px;">
                <tr class="bgcolor">
                    <th class="text-color">SUMMARY</th>
                    <th class="text-color">MTD</th>
                    <th class="text-color">%</th>
                </tr>
                <tr>
                    <td>TOTAL RECEIVED SALES CALLS</td>
                    <td>144</td>
                    <td></td>
                </tr>
                <tr>
                    <td>SALES LEADS</td>
                    <td>72</td>
                    <td>50%</td>
                </tr>
                <tr>
                    <td>MATURED SALES</td>
                    <td>20</td>
                    <td>28%</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
               <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
               <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
               <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>   
            </div>
            
            <div style="float: left;">
                <div id="dual_y_div" style="width:450px;height: 200px;"></div>
            </div>
            
        </td>
        
       
        
        
        
        
        
    </tr>
</table>





