<?php 

require('libraries/header.class.php');

if(isset($_POST['add']) && isset($_POST['title']))
{
	if(empty($_POST['title']))
	{
		Message::Send("error", "You must specify a valid title.", "poules.php");
	}

	if(!Poule::Create($_POST['title']))
	{
		Message::Send("error", "Could not create poule. Please try again.", "poules.php");
	}

	Message::Send("success", "Poule has been created.", "poules.php");
}

if(isset($_GET['delete']))
{
	if(!Poule::Get($_GET['delete'] ))
	{
		Message::Send("error", "That poule does not exist.", "poules.php");
	}

	if(!Poule::Delete($_GET['delete']))
	{
		Message::Send("error", "Could not delete poule. Please try again later.", "poules.php");
	}

	Message::Send("success", "Poule has been deleted.", "poules.php");
}

?>


<div class="row">

	<div class="col-md-4">

		<div class="card">

			<div class="card-header">
				Add poule
			</div>
			<div class="card-body">

				<form method="POST">

					<input type="text" class="form-control" placeholder="Title" name="title">

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

				<table class="table table-bordered">
				  	<thead>
					    <tr>
					      	<th scope="col">#</th>
					      	<th scope="col">Title</th>
					      	<th scope="col">Creation Date</th>
					      	<th scope="col">Edit</th>
					    </tr>
				  	</thead>
				  	<tbody>

				  		<?php foreach(Poule::GetAll() as $poule) { ?>

						    <tr>
						      	<th scope="row"><?php echo $poule->id; ?></th>
						      	<td><?php echo $poule->title; ?></td>
						      	<td><?php echo $poule->time; ?></td>
						      	<td>
						      		<a href="poule.php?id=<?php echo $poule->id; ?>" target="_blank"><button class="btn btn-primary">View</button></a>

						      		<a href="?delete=<?php echo $poule->id; ?>"><button class="btn btn-danger">Delete</button></a>

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