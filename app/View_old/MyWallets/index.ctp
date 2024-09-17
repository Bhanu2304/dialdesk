<?php ?>
<style>
.tblhead{
    border:1px solid gray;
    border-radius:10px;
    text-align: center;
    font-size: 13px;
    color: #424242;
    text-align: center;
}
.statementbtn{
    border:1px solid gray;
    border-radius:10px;
    text-align: center;
    font-size: 13px;
    color: #fff;
    background-color: #607d8b;
    text-align: center;
    
}

</style>
<ol class="breadcrumb">                            
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">My Wallet</a></li>
</ol>
<!--
<div class="page-heading">            
   <h4>My Wallet</h4>
</div>
-->
<div class="container-fluid foam-details" > 
    <div class="row" >
        <div class="col-md-12">
            <div class="panel panel-primary"  data-widget='{"draggable": "false"}'>
                <div class="panel-body detail" >
                    <h4><i class="material-icons">account_balance_wallet</i><span></span></h4>
                    <div class="tab-content">
                        <div class="tab-pane active" id="horizontal-form">

                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="editable">
                                <tr>
                                    <th><div class="tblhead">Plan Name</div></th>
                                    <th><div class="tblhead">Start Date</div></th>
                                    <th><div class="tblhead">End Date</div></th>
                                    <th><div class="tblhead">Validity</div></th>
                                    <th><div class="tblhead">Balance</div></th>
                                    <th><div class="tblhead">Available</div></th>
                                    <th><div class="tblhead">Used Balance</div></th>
                                </tr>
                                <tr>
                                    <td><?php echo isset($plan['PlanName'])?$plan['PlanName']:"";?></td>
                                    <td style="text-align: center;"><?php echo isset($balance['start_date'])?date_format(date_create($balance['start_date']),'d-M-y'):"";?></td>
                                    <td style="text-align: center;"><?php echo isset($balance['end_date'])?date_format(date_create($balance['end_date']),'d-M-y'):"";?></td>
                                    <td style="text-align: center;"><?php echo $plan['RentalPeriod']." ".$plan['PeriodType'];?></td>
                                    <td style="text-align: center;"><?php echo isset($balance['MainBalance'])?$balance['MainBalance']:"";?></td>
                                    
                                  <?php  //if(intval($balance['Used']) >= intval($balance['MainBalance'])){ ?>
                                    <!--
                                    <td style="text-align: center;">0</td>
                                    -->
                                  <?php //}else{?>
                                  <td style="text-align: center;"><?php echo isset($balance['Balance'])?$balance['Balance']:"";?></td>
                                  <?php //}?>
                                    
                                    <td style="text-align: center;"><?php echo isset($balance['Used'])?$balance['Used']:"";?></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><a href="<?php echo $this->webroot;?>ClientBillReports"><input type="button" class="statementbtn" value="Statement" ></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 


