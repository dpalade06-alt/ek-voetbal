<?php 

require('libraries/header.class.php');

if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']))
{
	if(!User::Exists($_POST['username']))
	{
		Message::Send("error", "Invalid credentials specified.", "login.php");
	}

	if(!User::Login($_POST['username'], $_POST['password']))
	{
		Message::Send("error", "Invalid credentials specified.", "login.php");
	}

	Message::Send("success", "You have been logged in.");

}

?>

<div class="row">

	<div class="col-md"></div>

	<div class="col-md">
		<div class="card">

			<div class="card-header">
				Login
			</div>
			<div class="card-body">

				<form method="POST">

					<input type="text" placeholder="Username" name="username" class="form-control"><br>
					<input type="password" placeholder="Password" name="password" class="form-control"><br>

					<input type="submit" class="btn btn-primary" value="Login" name="login">

				</form>

			</div>

		</div>
	</div>

	<div class="col-md"></div>

</div>

<?php require('libraries/footer.class.php'); ?>
