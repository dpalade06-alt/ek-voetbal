<?php

require('libraries/header.class.php');

if(!isset($_GET['id']))
{
	Message::Send("error", "Invalid user id specified.", "users.php");
}

$data = NULL;

if(!$data = User::Get($_GET['id']))
{
	Message::Send("error", "That account does not exist.", "users.php");
}

if(isset($_POST['save']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['admin']))
{
	if(!User::Update($_GET['id'], strip_tags($_POST['username']), strip_tags($_POST['email']), $_POST['admin'], $_POST['password']))
	{
		Message::Send("error", "Could not update account, please try again later.", "users.php");
	}

	Message::Send("success", "User has been modified.", "user.php?id=" . $_GET['id']);
}

?>

<div class="row">

	<div class="col-md"></div>

	<div class="col-md">
		
		<div class="card">

			<div class="card-header">

				<?php echo htmlentities($data->username) . "'s Details"; ?>

			</div>

			<div class="card-body">

				<form method="POST">

					<input type="text" placeholder="Username" name="username" value="<?php echo htmlentities($data->username); ?>" class="form-control"><br>
					<input type="text" placeholder="E-mail" name="email" value="<?php echo htmlentities($data->email); ?>" class="form-control"><br>
					<input type="password" placeholder="Password" name="password" class="form-control"><br>
					<input type="text" placeholder="Admin" name="admin" value="<?php echo $data->admin; ?>" class="form-control">

					<?php CSRF::Show(); ?>

					<hr>

					<input type="submit" class="btn btn-primary" value="Save" name="save">


				</form>

			</div>

		</div>

		<br>

	</div>

	<div class="col-md"></div>

</div>


<?php require('libraries/footer.class.php'); ?>