<?php $conn = mysqli_connect("192.168.10.12","root","dial@mas123") or die(mysqli_error($conn));
mysqli_select_db($conn,"db_dialdesk")or die("cannot select DB dialdesk");

if($_GET['type'] == 'delete')
{
  $sql = "DELETE FROM thomson_user WHERE id='" . $_GET['id'] . "'";
  if (mysqli_query($conn, $sql)) {
      echo '<script>alert("Record deleted successfully")</script>';
      header("location: index.php");
  } else {
      echo "Error deleting record: " . mysqli_error($conn);
  }
}
if($_GET['id'])
{
  $result = mysqli_query($conn,"SELECT * FROM thomson_user WHERE id='" . $_GET['id'] . "'");
  $row= mysqli_fetch_array($result); 
}
if(isset($_POST['submit']))
{	 
	 $dialeruser = $_POST['dialeruser'];
	 $zendeskuser = $_POST['zendeskuser'];
	 $password = $_POST['password'];

	 $sql = "INSERT INTO thomson_user (DialerUser,ZendeskUser,ZendeskPass)
	 VALUES ('$dialeruser','$zendeskuser','$password')";
	 if (mysqli_query($conn, $sql)) {
    echo '<script>alert("New record created successfully !")</script>';
    header("location: index.php");
	 } else {
		echo "Error: " . $sql . "
" . mysqli_error($conn);
	 }
	 mysqli_close($conn);
}

if(isset($_POST['update']))
{	
  
	 $dialeruser = $_POST['dialeruser'];
	 $zendeskuser = $_POST['zendeskuser'];
	 $password = $_POST['password'];

   $update = "UPDATE thomson_user set DialerUser='" . $dialeruser . "', ZendeskUser='" . $zendeskuser . "', ZendeskPass='" . $password . "' WHERE id='" . $_GET['id'] . "'";
   $up = mysqli_query($conn, $update);
   //$message = "Record Update Successfully";
   echo '<script>alert("Record Update Successfully!")</script>';
   header("location: index.php");
}

$view = "SELECT * FROM thomson_user";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

</head>
<style>

.vertical-offset-100{
    padding-top:100px;
}
</style>
<body>
<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<!-- <div class="panel-heading">
			    	<h3 class="panel-title">Please sign in</h3>
			 	</div> -->
			  	<div class="panel-body">
			    	<form method="post">
                    <fieldset>
			    	  	<div class="form-group">
			    		    <input class="form-control" placeholder="Dial User" name="dialeruser" type="text" value="<?php if(isset($_GET['id'])){ echo $row['DialerUser']; } ?>">
			    		</div>
              <div class="form-group">
			    		    <input class="form-control" placeholder="Zendesk User" name="zendeskuser" type="text" value="<?php if(isset($_GET['id'])){ echo $row['ZendeskUser']; } ?>">
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Password" name="password" type="password" value="<?php if(isset($_GET['id'])){ echo $row['ZendeskPass']; } ?>">
			    		</div>
            
			    		<!-- <div class="checkbox">
			    	    	<label>
			    	    		<input name="remember" type="checkbox" value="Remember Me"> Remember Me
			    	    	</label>
			    	    </div> -->
			    		<input class="btn btn-lg btn-success btn-block" type="submit" name="<?php if(isset($_GET['id'])){ echo 'update'; } else{ echo 'submit'; }?>" value="Save">
			    	</fieldset>
			      	</form>
			    </div>
          
			</div>
		</div>
	</div>
</div>

<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title text-center">View</h3>
			 	</div>
			  	<div class="panel-body">
          <table class="table">
  <thead>
    <tr>
      <th>id</th>
      <th>Dial User</th>
      <th>Zendesk User</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php $result = mysqli_query($conn, $view);

while($row = mysqli_fetch_assoc($result))
{
  echo '<tr>';
    echo '<td>'.$row["id"].'</td>';
    echo '<td>'.$row["DialerUser"].'</td>';
    echo '<td>'.$row["ZendeskUser"].'</td>';
    echo '<td><a href="index.php?id=' .$row['id']. '" class="btn btn-primary edit" data-id="'.$row["id"].'">Edit</a> || <a href="index.php?type=delete&id=' .$row['id']. '" class="btn btn-danger edit" data-id="'.$row["id"].'">Delete</a></td>';
  echo '</tr>';
} ?>
    
    
  </tbody>
</table>
			    </div>
          
			</div>
		</div>
	</div>
</div>
</body>
</html>