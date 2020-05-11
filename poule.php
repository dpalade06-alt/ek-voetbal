<?php 

require('libraries/header.class.php');

if(!isset($_GET['id']))
{
	Message::Send("error", "Invalid poule id specified.", "poules.php");
}

$poule = NULL;

if(!$poule = Poule::Get($_GET['id']))
{
	Message::Send("error", "That poule does not exist.", "poules.php");
}

if(isset($_POST['add']) && isset($_POST['username']))
{
	if(empty($_POST['username']))
	{
		Message::Send("error", "You must specify a valid username.", "poule.php?id=".$_GET['id']);
	}

	$u = NULL;

	if(!$u = User::Exists($_POST['username']))
	{
		Message::Send("error", "User does not exist.", "poule.php?id=".$_GET['id']);
	}

	if(Poule::HasUser($_GET['id'], $u->id))
	{
		Message::Send("error", "Poule already contains that user.", "poule.php?id=".$_GET['id']);
	}

	if(!Poule::AddUser($_GET['id'], $u->id))
	{
		Message::Send("error", "Could not create poule. Please try again.", "poule.php?id=".$_GET['id']);
	}

	Message::Send("success", "User has been added to poule.", "poule.php?id=".$_GET['id']);
}

if(isset($_GET['delete']))
{
	if(!Poule::HasUser($_GET['id'], $_GET['delete']))
	{
		Message::Send("error", "Poule does not contain that user.", "poule.php?id=".$_GET['id']);
	}

	if(!Poule::DeleteMember($_GET['id'], $_GET['delete']))
	{
		Message::Send("error", "Could not delete poule member. Please try again later.", "poule.php?id=".$_GET['id']);
	}

	Message::Send("success", "Poule member has been deleted.", "poule.php?id=".$_GET['id']);
}

if(isset($_POST['save']) && isset($_POST['option_1']) && isset($_POST['option_2']) && isset($_POST['option_3']))
{
	if(empty($_POST['option_1']) || empty($_POST['option_2']) || empty($_POST['option_3']) || empty($_POST['option_4']))
	{
		Message::Send("error", "You must fill in all the fields.", "poule.php?id=".$_GET['id']);
	}

	if(!Poule::Get($_GET['id']))
	{
		Message::Send("error", "That poule does not exist.", "index.php");
	}

	$data = $_POST['option_1'] . "|" . $_POST['option_2'] . "|" . $_POST['option_3'] . "|" . $_POST['option_4'];

	if(!Poule::SetResult($_GET['id'], $data))
	{
		Message::Send("error", "Could not save results. Please try again later.", "poule.php?id=".$_GET['id']);
	}

	Message::Send("success", "Results have been saved.", "poule.php?id=".$_GET['id']);
}


if(isset($_GET['unset']))
{
	if(!Poule::Get($_GET['id']))
	{
		Message::Send("error", "That poule does not exist.", "index.php");
	}

	if(!Poule::UnsetResult($_GET['id']))
	{
		Message::Send("error", "Could not unset results. Please try again later.", "poule.php?id=".$_GET['id']);
	}

	Message::Send("success", "Results have been unset.", "poule.php?id=".$_GET['id']);
}

?>


<div class="row">

	<div class="col-md-4">

		<div class="card">

			<div class="card-header">
				Poule Info
			</div>
			<div class="card-body">
				
				<h5>Info</h5>

				<ul>
					<li>Poule ID: <?php echo $poule->id; ?></li>
					<li>Poule Title: <?php echo $poule->title; ?></li>
					<li>Poule Creation Date: <?php echo $poule->time; ?></li>
				</ul>

				<hr>

				<?php if($poule->results != NULL) { ?>

					<h5>Results</h5>

					<?php 

						$d = explode("|", $poule->results);

						echo "<ol>";

						foreach($d as $i => $r)
						{
							echo "<li>" . $r . "</li>";
						}

						echo "</ol>";

					?>

					<?php if($user->data->admin) { ?>

					<hr>

					<a href="?id=<?php echo $poule->id; ?>&unset"><button class="btn btn-warning">Unset</button></a>

					<?php } ?>

				<?php } else if($user->data->admin) { ?>

					<form method="POST">

			    		<div class="row m-3">
			    			<div clas="col-md">#1</div>
			    			<div class="col-md">
			    				<select name="option_1" class="dropdown form-control"></select>
			    			</div>
			    		</div>

			    		<div class="row m-3">
			    			<div clas="col-md">#2</div>
			    			<div class="col-md">
			    				<select name="option_2" class="dropdown form-control"></select>
			    			</div>
			    		</div>

			    		<div class="row m-3">
			    			<div clas="col-md">#3</div>
			    			<div class="col-md">
			    				<select name="option_3" class="dropdown form-control"></select>
			    			</div>
			    		</div>

			    		<div class="row m-3">
			    			<div clas="col-md">#4</div>
			    			<div class="col-md">
			    				<select name="option_4" class="dropdown form-control"></select>
			    			</div>
			    		</div>

			    		<hr>

			    		<input type="submit" class="btn btn-primary" value="Save" name="save">

			    	</form>

				<?php } ?>

				<?php if($user->data->admin) { ?>

					<hr>

					<form method="POST">

						<h5>Add Users</h5>

						<input type="text" placeholder="Username" name="username" class="form-control">
						<hr>
						<input type="submit" name="add" value="Add" class="btn btn-primary">

					</form>

				<?php } ?>

			</div>

		</div>


	</div>

	<div class="col-md">
		<div class="card">

			<div class="card-header">
				Users
			</div>
			<div class="card-body">

				<table class="table table-bordered">
				  	<thead>
					    <tr>
					      	<th scope="col">#</th>
					      	<th scope="col">Username</th>
					      	<th scope="col">Points</th>
					      	<th scope="col">Added Date</th>
					      	<th scope="col">Edit</th>
					    </tr>
				  	</thead>
				  	<tbody>

				  		<?php foreach(Poule::GetAllUsers($_GET['id']) as $u) { ?>

						    <tr>
						      	<th scope="row"><?php echo $u->id; ?></th>
						      	<td><?php echo User::GetUsername($u->user_id); ?></td>
						      	<td>
						      		<?php echo $poule->results ? Poule::GetPointsFromResult($poule->results, Poule::GetUserBet($user->data->id, $poule->id)) : "N/A"; ?>

						      	</td>
						      	<td><?php echo $u->time; ?></td>
						      	<td>
						      		<a href="poule.php?id=<?php echo $u->id; ?>" target="_blank"><button class="btn btn-primary">View</button></a>

						      		<?php if($user->data->admin) { ?>

						      		<a href="?id=<?php echo $_GET['id']; ?>&delete=<?php echo $u->user_id; ?>"><button class="btn btn-danger">Delete</button></a>

						      		<?php } ?>

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