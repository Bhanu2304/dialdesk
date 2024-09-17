<?php ?>
<script>
    setInterval(function(){ $('#remove_msg').remove(); }, 10000);
    function getBillDate(ClientId){
        
    }    
    function allowonlynumber(e,t){
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
                return false;
                }
                 return true;

            }
            catch (err) {
                alert(err.Description);
            }
        }
        
        function add_raw()
        {
            // var client_id = $('#clientId').val();
            var percent = $('#percent1').val();
            var risk_action = $('#risk_action1').val();
            var email = $('#email1').val();
            var email_cc = $('#email_cc1').val();
            var remarks = $('#remarks1').val();
            var return_flag = true;
            
            // if(client_id=="")
            // {
            //     var msg = '<span id="remove_msg"><font color="red">Please Select Client</font></span>';
            //     $('#clientId1').append(msg);
            //     return_flag = false;
            // }
            if(percent=="")
            {
                var msg = '<span id="remove_msg"><font color="red">Please Fill Percent</font></span>';
                $('#percents').after(msg);
                return_flag = false;
            }
            else if(parseInt(percent)>100)
            {
                var msg = '<span id="remove_msg"><font color="red">Please Fill Percent less than 100.</font></span>';
                $('#percent1').after(msg);
                return_flag = false;
            }
            else if(risk_action=='')
            {
                var msg = '<span id="remove_msg"><font color="red">Please Select Action</font></span>';
                $('#risk_action1').after(msg);
                return_flag = false;
            }
             else if(email=="")
             {
                var msg = '<span id="remove_msg"><font color="red">Please Fill Email</font></span>';
                $('#email1').after(msg);
                return_flag = false;
             }
            else if(remarks=="")
            {
                var msg = '<span id="remove_msg"><font color="red">Please Fill Remarks</font></span>';
                $('#remarks1').after(msg);
                return_flag = false;
            }
            
            if(return_flag)
            {
                $.post('<?php echo $this->webroot.'BillingRiskExposures/save_raw1';?>',
                {
                    percent:percent,
                    risk_action:risk_action,
                    email:email,
                    email_cc:email_cc,
                    remarks:remarks
                },
                function(data){
                //var tbl = document.getElementById('tbl').getElementsByTagName('tbody')[0];
                //tbl.append(data);
                if(data=='1')
                {
                    var msg = '<span id="remove_msg"><font color="red">Record Not Saved. Please Try Again.</font></span>';
                    $('#clientId1').html(msg);
                    
                }
                else
                {
                    var msg = '<span id="remove_msg"><font color="green">Record Saved Successfully.</font></span>';
                    $('#clientId1').html(msg);
                    $('#tbl').find('tbody').html(data);
                    $('#clientId').val("");
                    $('#percent1').val("");
                    $('#risk_action1').val("");
                     $('#email1').val("");
                    $('#email_cc1').val("");
                    $('#remarks1').val("");
                    location.reload();
                }
           }
        );
            }
        }
        
        function del_raw1(row_id)
        {
            $.post('<?php echo $this->webroot.'BillingRiskExposures/del_raw1';?>',
            {
                risk_id:row_id
            },
            function(data){
            if(data=='1')
            {
               $('#tr'+row_id).remove();
            }
           }
        );
            
        }
        
     
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Billing Statement</a></li>
    <li class="active"><a href="#">Risk Exposure (Internal)</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Risk Exposure (Internal)</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                
        </div>
            
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                     <h2>Email Details</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <div class="form-horizontal row-border">
                        <table class="table" id="tbl">
                            <thead>
                            <tr>
                                <th>Percent</th>
                                <th>Action</th>
                                <th>Email To</th>
                                <th>Email Cc</th>
                                <th>Remarks</th>
                                <th>Action</th>
                                <th>Record Created</th>
                            </tr>
                            <tr id="tr0">
                                <td>
                                    <input type="text" name="percent1" id="percent1" onkeypress="return allowonlynumber(event,this)" value="" style="width: 40px;text-align: center;" maxlength="3" onkeypress=""  />%<div id="percents"></div>
                                </td>
                                <td>
                                    <select  name="risk_action1" id="risk_action1"  >
                                        <!-- <option value="">Select</option> -->
                                        <option value="Email" selected>Email</option>
                                        <!-- <option value="CallStop">Call Stop</option> -->
                                    </select>
                                </td>
                                <td>
                                    <textarea type="text" id="email1" name="email1" value="" placeholder="Email"  ></textarea>
                                </td>
                                <td>
                                    <textarea type="text" id="email_cc1" name="email_cc1" value="" placeholder="Email Cc"  ></textarea>
                                </td>
                                <td>
                                    <textarea type="text" id="remarks1" name="remarks1" value="" placeholder="Remarks"  ></textarea>
                                </td>
                                <td>
                                    <button type="button" id="btn" value="btn" onclick="add_raw()" >Add</button>
                                </td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($record_arr as $record)
                            {
                                echo '<tr id="tr'.$record['BillRiskExposureMail']['risk_id'].'">';
                                    echo '<td>'.$record['BillRiskExposureMail']['percent'].'%</td>';
                                    echo '<td>'.$record['BillRiskExposureMail']['risk_action'].'</td>';
                                    echo '<td>'.$record['BillRiskExposureMail']['email_id'].'</td>';
                                    echo '<td>'.wordwrap($record['BillRiskExposureMail']['email_cc'],30,"<br>\n",true).'</td>';
                                    echo '<td>'.$record['BillRiskExposureMail']['remarks'].'</td>';
                                    echo '<td>'.'<button type="button"  value="btn" onclick="del_raw1('.$record['BillRiskExposureMail']['risk_id'].')" >Delete</button>'.'</td>';
                                    echo '<td>'.date('d-M-y h:i:s A',strtotime($record['BillRiskExposureMail']['created_at'])).'</td>';
                                echo '</tr>';
                            } ?>
                            </tbody>
                        </table>
                        
                
                
                </div>
            </div>
        </div>
            
            
    </div>
</div>
