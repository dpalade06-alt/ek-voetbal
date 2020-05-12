<?php 

require('libraries/header.class.php');

if(isset($_POST['add']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
{
	if(empty($_POST['username']))
	{
		Message::Send("error", "You must specify a username.", "users.php");
	}

	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		Message::Send("error", "You must enter a valid email.", "users.php");
	}

	if(User::Exists(strip_tags($_POST['username'])))
	{
		Message::Send("error", "Username is already in use.", "users.php");
	}

	if(!User::Register(strip_tags($_POST['username']), $_POST['password'], strip_tags($_POST['email'])))
	{
		Message::Send("error", "Could not create account. Please try again.", "users.php");
	}

	//send mail
	Mail::Send(
		strip_tags($_POST['email']), 
		"Poules4ALL - Your account is ready!", 
		"<h3>Poules4ALL account</h3>
		<h4>You have been invited to join Poules4ALL! Use the credentials below to login.</h4>
		Username: " . strip_tags($_POST['username']) . "
		<br>
		Password: " . strip_tags($_POST['password']) . "
	");

	Message::Send("success", "Account has been created.", "users.php");
}

if(isset($_GET['delete']))
{
	if($_GET['delete'] == $user->data->id)
	{
		Message::Send("error", "You can't delete the account you're currenty logged in to.", "users.php");
	}

	if(!User::Get($_GET['delete'] ))
	{
		Message::Send("error", "That account does not exist.", "users.php");
	}

	if(!User::Delete($_GET['delete']))
	{
		Message::Send("error", "Could not delete account. Please try again later.", "users.php");
	}

	Message::Send("success", "Account has been deleted.", "users.php");
}

?>


<div class="row">

	<div class="col-md-4">

		<div class="card">

			<div class="card-header">
				Add user
			</div>
			<div class="card-body">

				<form method="POST">

					<input type="text" class="form-control" placeholder="Username" name="username"><br>
					<input type="text" class="form-control" placeholder="Email" name="email"><br>
					<input type="password" class="form-control" placeholder="Password" name="password">

					<?php CSRF::Show(); ?>

					<hr>

					<input type="submit" class="btn btn-primary" value="Add" name="add">

				</form>

			</div>

		</div>

	</div>

	<div class="col-md">
		<div class="card">

			<div class="card-header">
				Users
			</div>
			<div class="card-body">

				<table id="users_table" class="table table-bordered">
				  	<thead>
					    <tr>
					      	<th scope="col">#</th>
					      	<th scope="col">Username</th>
					      	<th scope="col">Admin</th>
					      	<th scope="col">E-mail</th>
					      	<th scope="col">Registration Date</th>
					      	<th scope="col">Edit</th>
					    </tr>
				  	</thead>
				  	<tbody>

				  		<?php foreach(User::GetAll() as $user) { ?>

						    <tr>
						      	<th scope="row"><?php echo $user->id; ?></th>
						      	<td><?php echo htmlentities($user->username); ?></td>
						      	<td><?php echo $user->admin ? "Yes" : "No"; ?></td>
						      	<td><?php echo htmlentities($user->email); ?></td>
						      	<td><?php echo $user->registered; ?></td>
						      	<td>
						      		<a href="user.php?id=<?php echo $user->id; ?>"><button class="btn btn-primary">Edit</button></a>

						      		<a href="?delete=<?php echo $user->id; ?>"><button class="btn btn-danger">Delete</button></a>

						      	</td>
						    </tr>

				  		<?php } ?>

				  	</tbody>
				</table>

			</div>

		</div>
	</div>

</div>


<?php require('libraries/footer.class.php'); ?>