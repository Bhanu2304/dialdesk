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
            var client_id = $('#clientId').val();
            var percent = $('#percent1').val();
            var risk_action = $('#risk_action1').val();
            var email = $('#email1').val();
            var email_cc = $('#email_cc1').val();
            var remarks = $('#remarks1').val();
            var return_flag = true;
            
            if(client_id=="")
            {
                var msg = '<span id="remove_msg"><font color="red">Please Select Client</font></span>';
                $('#clientId1').append(msg);
                return_flag = false;
            }
            else if(percent=="")
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
                $.post('<?php echo $this->webroot.'BillingRiskExposures/save_raw';?>',
                {
                    client_id:client_id,
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
                }
           }
        );
            }
        }
        
        function del_raw(row_id)
        {
            $.post('<?php echo $this->webroot.'BillingRiskExposures/del_raw';?>',
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
        
        function get_raw(client_id)
        {
            $.post('<?php echo $this->webroot.'BillingRiskExposures/get_raw';?>',
            {
                client_id:client_id
            },
            function(data){
                if(data!='')
            $('#tbl').find('tbody').html(data);
           }
            );
            
        }
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Billing Statement</a></li>
    <li class="active"><a href="#">Risk Exposure</a></li>
</ol>
<div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2>Risk Exposure</h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <form method="post"  class="form-horizontal row-border" >
                <div class="form-group">
                    <label class="col-sm-1 control-label">Client</label>
                    <div class="col-sm-3">
                        <select id="clientId" name="clientId"  class="form-control" onchange="get_raw(this.value)" required="" >
                            <option value="">Select Client</option>
                            <?php
                            foreach($data as $k=>$v)
                            {
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div id="clientId1" class="col-sm-3"></div>
                </div>
                    
                </form>
            </div>
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
                            </tr>
                            <tr id="tr0">
                                <td>
                                    <input type="text" name="percent1" id="percent1" onkeypress="return allowonlynumber(event,this)" value="" style="width: 40px;text-align: center;" maxlength="3" onkeypress=""  />%<div id="percents"></div>
                                </td>
                                <td>
                                    <select  name="risk_action1" id="risk_action1"  >
                                        <option value="">Select</option>
                                        <option value="Email">Email</option>
                                        <option value="CallStop">Call Stop</option>
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
                            </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        
                
                
                </div>
            </div>
        </div>
            
            
    </div>
</div>
