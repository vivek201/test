<?php require_once '../includes/functions.php';?>
<?php include 'templates/header.php';?>
<?php include 'templates/navbar.php';?>
<?php 
	// FORM SUBMISSION
	$alert = array();
	
	if (isset($_POST['submit'])) {
		$new_user = new User();
		$_POST['password'] = md5($_POST['password']);
		$new_user->instantiate($_POST);
		
		if (!$new_user->insert()) {
			$alert['error'] = '<b>Sorry!</b> New user could not be added because the <b>username/email</b> has already been used!';
		}
		else {
			if (strtolower($new_user->permission) == 'employee')
			{
				$employee = new Employee();
				$employee->user_id = $new_user->id;
				$employee->validity = $_POST['validity'];
				$employee->insert();
			}
			$alert['success'] = '<b>Okay!</b> The new user has been successfully added!';
		}
	}

?>

<?php 
	// SETTING PARAMETERS FOR SIDEBAR
	$header = 'accounts';
	$page = 'add';


?>
<?php include 'templates/sidebar.php';?>
<div class="row" style="margin-right: 0">
	<div class="col-md-6 col-md-offset-3 page-wrapper">
		<h2>Add New Account</h2>
		<hr>
		<form action="account.add.php" method="post" autocomplete="off" onsubmit="return required()">
			
			<!-- IN ORDER TO STOP BROWSERS FROM AUTOFILLING THE USERNAME AND PASSWORD -->
			<input style="display:none" type="text" name="fakeusernameremembered"/>
			<input style="display:none" type="password" name="fakepasswordremembered"/>
			<!-- DONE -->
			<?php bootstrap_alert($alert); 
			?>
			<div class="row">
			<div class="form-group col-md-6">
				<label for="txtFirstname">First Name:</label>
				<input type="text" class="form-control" id="txtFirstname" name="fname" placeholder="First name" tabindex="1" required>
			</div>
			<div class="form-group col-md-6">
				<label for="txtFirstname">Last Name:</label>
				<input type="text" class="form-control" id="txtLastname" name="lname" placeholder="Last name" tabindex="1" required>
			</div>
			</div>
			<div class="form-group">
			    <label for="txtUsername">Username</label>
			    <input type="text" class="form-control" id="txtAddUsername" placeholder="Username" name="username" required tabindex="1" autocomplete="off" value="">
			</div>
			<div class="form-group">
			    <label for="txtPassword">Password</label>
			    <input type="password" class="form-control" id="txtAddPassword" placeholder="Password" name="password" required tabindex="2" autocomplete="off" value="">
			</div>
			<div class="form-group">
			    <label for="txtEmail">Email address</label>
		    	<input type="email" class="form-control" id="txtEmail" placeholder="E.g. example@abc.com" name="email" required tabindex="3">
			</div>
			<div class="form-group">
			    <label for="selectPerm">Account Type</label>
			    <select class="form-control" id="selectPerm" name="permission" tabindex="4" onchange="checkEmployee()">
				  	<option value="MANAGER">Manager</option>
				  	<option value="STAFF">Staff</option>
				  	<option value="EMPLOYEE">Employee</option>
				</select>
			</div>
			<div class="form-group hidden">
			    <label for="dateValidity">Valid Until</label>
		    	<input type="date" class="form-control" id="dateValidity" name="validity" tabindex="6">
			</div>
			<button type="submit" class="btn btn-primary" name="submit" value="add" tabindex="6">Add User</button>
		</form>
	</div>
</div>
<?php include 'templates/footer.php';?>
<script type="text/javascript">
	function checkEmployee() {
		selectPerm = document.getElementById('selectPerm');
		perm = selectPerm.options[selectPerm.selectedIndex].value;

		dateInput = document.getElementById('dateValidity');
		if (perm == "EMPLOYEE") {
			dateInput.parentNode.className = "form-group show";
			dateInput.required = true;
		}
		else {
			dateInput.parentNode.className = "form-group hidden";
			dateInput.required = false;
		}
	}
</script>