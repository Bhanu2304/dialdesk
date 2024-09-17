<html>
<head>
    <style>
    p{margin:1px;}
    table.gridtable {
        width: 100%;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
    }
    table.gridtable th {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        font-size:10px;
    }
    table.gridtable td {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        font-size:10px;
    }
    
    table.gridtable2 {
        width:100%;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable2 th {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        font-size:10px;
    }
    table.gridtable2 td {
        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        font-size:10px;
    }

    table.gridtable1 {
        width: 70%;
        font-size:10px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }
    table.gridtable1 th {
       border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
       table.gridtable1 td {

        border-width: 1px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;

    }

    .topleft{
        font-size:10px;
    }
    .topright{
        font-size:10px;
        position: relative;
        left:75%;
        top:-55px;
    }
    .summary{
        position: relative;
        top:-40px;
    }
    </style>
</head>

<body style="font-family:Arial, Helvetica, sans-serif;">

    <div>
        <center>
            <?php echo $this->Html->image('logo.jpg', array('fullBase' => true,'style'=>'width:140px;margin-top:-30px;'));?><br/> 
            <span style="font-size: 8px;font-weight: bold;">A DIVISION OF <font color="red">ISPARK</font> Dataconnect Pvt Ltd</span>
        </center>
    </div>

    <div class="topleft">
        <p><?php echo $ClientInfo['RegistrationMaster']['auth_person'];?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['reg_office_address1']; ?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['city'].', '.$ClientInfo['RegistrationMaster']['state'].' '.' '.$ClientInfo['RegistrationMaster']['pincode']; ?></p>
        <p><?php echo $ClientInfo['RegistrationMaster']['phone_no'];?></p>
    </div>
    
    <div class="topright">
        <p>Statement For: <span style="margin-left:10px;"><?php echo '1234879';?></span></p>
        <p>Service Tax No:<span style="margin-left:10px;"><?php echo 'AAFCM4591GST001'; ?></span></p>
        <p>Pan No:<span style="margin-left:44px;"><?php echo 'AAFCM4591G'; ?></span></p>
    </div>

    <div class="summary" >
        <hr>
       
        <center><span style="font-weight:bold;font-size:10px;">Summary</span></center>
       
        <table style="width:100%;">
            <tr>
                <td>    
                <table class="gridtable" >
                <tr>
                    <th>Description</th>
                    <th>Vol./Pulse</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
                    <?php if(!empty($Inbound['0']['0']['Unit'])) { ?>
                 <tr>
                     <td>ICB @<?php echo $PlanDetails['PlanMaster']['InboundCallCharge'].' Rs./'.$PlanDetails['PlanMaster']['InboundCallMinute'].'Min'; ?></td>
                    <td><?php echo $Inbound['0']['0']['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['PlanMaster']['InboundCallCharge']; ?></td>
                    <td><?php echo round($Inbound['0']['0']['Unit']*$PlanDetails['PlanMaster']['InboundCallCharge'],2); ?></td>
                </tr>
                    <?php } if(!empty($VFO['0']['0']['Unit'])) { ?>
                <tr>
                    <td>VFO @<?php echo $PlanDetails['PlanMaster']['VFOCallCharge'].' Rs./Min'; ?></td>
                    <td><?php echo $VFO['0']['0']['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['PlanMaster']['VFOCallCharge']; ?></td>
                    <td><?php echo round($VFO['0']['0']['Unit']*$PlanDetails['PlanMaster']['VFOCallCharge'],2); ?></td>
                </tr>
                    <?php } if(!empty($SMS['0']['0']['Unit'])) { ?>
                <tr>
                    <td>SMS <?php echo $PlanDetails['PlanMaster']['SMSCharge'].' Rs./Min'; ?></td>
                     <td><?php echo $SMS['0']['0']['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['PlanMaster']['SMSCharge']; ?></td>
                    <td><?php echo round($SMS['0']['0']['Unit']*$PlanDetails['PlanMaster']['SMSCharge'],2); ?></td>
                </tr>
                <?php } if(!empty($Email['0']['0']['Unit'])) { ?>
                <tr>
                    <td>Email <?php echo $PlanDetails['PlanMaster']['EmailCharge'].' Rs./Min'; ?></td>
                    <td><?php echo $Email['0']['0']['Unit'].''; ?></td>
                    <td><?php echo $PlanDetails['PlanMaster']['EmailCharge']; ?></td>
                    <td><?php echo round($Email['0']['0']['Unit']*$PlanDetails['PlanMaster']['EmailCharge'],2); ?></td>
                </tr>
                <?php } ?>
            </table>
             
        </td>
        <td>
            
            <table class="gridtable2">
                <tr>
                    <th>Description</th>
                    <th>&nbsp;</th>
                </tr>
                 <tr>
                    <td>Validity</th>
                    <td>00</th>
                </tr>
                <tr>
                    <td>Credit</td>
                    <td>00</td>
                </tr>
                <tr>
                    <td>Credit Used</td>
                    <td>00</td>
                </tr>
                <tr>
                    <td>Total Credit Used</td>
                    <td>00</th>
                </tr>
                <tr>
                    <td>Credit C/F</td>
                    <td>00</td>
                </tr>
            </table>

        </td>
    </tr>
</table>
            
            
     
        <hr>
        <center><span style="font-weight:bold;font-size:10px;">Statement</span></center>
    </div>
    
    
    <div>
       
        <?php if(!empty($InboundDetails)) { ?>
        <span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['RegistrationMaster']['company_name']; ?> Inbound</span>
        
        <table class="gridtable1">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Call From</th>
                <th>Pulse</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Date</th>
                <th>Time</th>
                <th>Call From</th>
                <th>Pulse</th>
            </tr>
            <?php $InTotal = 0; $i =0; $flag = false;
                    echo "<tr>";
                    foreach($InboundDetails as $inb):
                        if($i%2==0 && $flag) {echo "</tr><tr>";}
                        if($i%2!=0 && $flag) {echo "<td></td>";}
                            echo '<td>'.$inb['0']['CallDate'].'</td>';
                            echo '<td>'.$inb['billing_master']['CallTime'].'</td>';
                            echo '<td>'.$inb['billing_master']['CallFrom'].'</td>';
                            echo '<td>'.$inb['billing_master']['Unit'].'</td>';
                            $InTotal += $inb['billing_master']['Unit'];
                        //if($i%2!=0) {echo "<tr>";}
                            $flag = true; $i++;
                    endforeach;
                    echo '</tr><tr>';
                        echo '<td colspan="9">Total Vol.    '.$InTotal.'</td>';
                    echo "</tr>";
            ?>
        </table>
                
        <?php } if(!empty($SMSDetails)) { ?>
        <span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['RegistrationMaster']['company_name']; ?> SMS</span>
            <table class="gridtable1">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Call From</th>
                    <th>Pulse</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Call From</th>
                    <th>Pulse</th>
                </tr>
                <?php   $SMSTotal = 0; $i =0; $flag = false;
                        echo "<tr>";
                        foreach($SMSDetails as $inb):
                            if($i%2==0 && $flag) {echo "</tr><tr>";}
                            if($i%2!=0 && $flag) {echo "<td></td>";}
                                echo '<td>'.$inb['0']['CallDate'].'</td>';
                                echo '<td>'.$inb['billing_master']['CallTime'].'</td>';
                                echo '<td>'.$inb['billing_master']['CallFrom'].'</td>';
                                echo '<td>'.$inb['billing_master']['Unit'].'</td>';
                                $SMSTotal += $inb['billing_master']['Unit'];
                                $flag = true; $i++;
                        endforeach;
                        echo '<tr>';
                            echo '<td colspan="9">Total Vol.    '.$SMSTotal.'</td>';
                        echo "</tr>";
                ?>
            </table>
               
            <?php } if(!empty($EmailDetails)) { ?>
     
            <span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['RegistrationMaster']['company_name']; ?> Email</span>
            <table class="gridtable1">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Call From</th>
                    <th>Pulse</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Call From</th>
                    <th>Pulse</th>
                </tr>
                <?php $EmailTotal = 0; $i =0; $flag = false;
                        echo "<tr>";
                        foreach($EmailDetails as $inb):
                            if($i%2==0 && $flag) {echo "</tr><tr>";}
                            if($i%2!=0 && $flag) {echo "<td></td>";}
                                echo '<td>'.$inb['0']['CallDate'].'</td>';
                                echo '<td>'.$inb['billing_master']['CallTime'].'</td>';
                                echo '<td>'.$inb['billing_master']['CallFrom'].'</td>';
                                echo '<td>'.$inb['billing_master']['Unit'].'</td>';
                                $EmailTotal += $inb['billing_master']['Unit'];
                                 $flag = true; $i++;
                        endforeach;
                        echo '</tr><tr>';
                            echo '<td colspan="9">Total Vol.    '.$EmailTotal.'</td>';
                        echo "</tr>";
                ?>
            </table>
          
            <?php }  if(!empty($VFODetails)) { ?>
        
            <span style="font-weight:bold;font-size:10px;"><?php echo $ClientInfo['RegistrationMaster']['company_name']; ?> VFO</span>
            <table class="gridtable1">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Call From</th>
                    <th>Pulse</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Call From</th>
                    <th>Pulse</th>
                </tr>
                <?php   $VFOTotal = 0; $i =0; $flag = false;
                         echo "<tr>";
                        foreach($VFODetails as $inb):
                            if($i%2==0 && $flag) {echo "</tr><tr>";}
                            if($i%2!=0 && $flag) {echo "<td></td>";}
                                echo '<td>'.$inb['0']['CallDate'].'</td>';
                                echo '<td>'.$inb['billing_master']['CallTime'].'</td>';
                                echo '<td>'.$inb['billing_master']['CallFrom'].'</td>';
                                echo '<td>'.$inb['billing_master']['Unit'].'</td>';
                                $VFOTotal += $inb['billing_master']['Unit'];
                            $flag = true; $i++;
                        endforeach;
                        echo '</tr><tr>';
                            echo '<td colspan="4">Total Vol.    '.$VFOTotal.'</td>';
                        echo "</tr>";
                ?>
            </table>
            <?php } ?>
    </div>   
</body>
</html>