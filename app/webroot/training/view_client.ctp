<div class="row-fluid">
	<div class="span12">
		<div class="box dark">
  			<header>
 				<div class="icons"><i class="icon-edit"></i></div>
          		<h5>View Client</h5>
			</header>
    		<div id="div-1" class="accordion-body collapse in body">
           		<table class="table table-bordered table-condensed table-hovered sortableTable">
         			<tr>
                        <th>Company Id</th>
                        <th>Company Name</th>
                        <th>Client Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Contact Person</th>
                        <th>Contact Email</th>
                        <th>Contact Mobile</th>
                    </tr>
                    <?php foreach($clint_record as $row){?>
                        <tr>
                            <td><?php echo $row['RegistrationMaster']['company_id'];?></td>
                            <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                            <td><?php echo $row['RegistrationMaster']['auth_person'];?></td>
                            <td><?php echo $row['RegistrationMaster']['reg_office_address1'];?></td>
                            <td><?php echo $row['RegistrationMaster']['email'];?></td>
                            <td><?php echo $row['RegistrationMaster']['phone_no'];?></td>
                            <td><?php echo $row['RegistrationMaster']['contact_person1'];?></td>
                            <td><?php echo $row['RegistrationMaster']['cp1_email'];?></td>
                            <td><?php echo $row['RegistrationMaster']['cp1_phone'];?></td>
                        </tr>
                    <?php }?>
				</table>
			</div>
		</div>
	</div>
</div>