<?php 

require('main.class.php');

?>

<!doctype html>
<html lang="en">
  	<head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
	    <link href="css/toastr.css" rel="stylesheet">

	    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

	    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    	<script src="js/toastr.js"></script>

	    <title>Poules4ALL | Home</title>
  	</head>
  	<body>
  	<nav class="navbar navbar-expand-md navbar-dark" style="background-color: #58c30a;">
	    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
	        <ul class="navbar-nav mr-auto">
	            
	        	<?php if(User::Logged()) { ?>

		      		<li class="nav-item">
			        	<a class="nav-link" href="index.php">Home</a>
			      	</li>

			      	<?php if($user->data->admin) { ?>

			      		<li class="nav-item">
				        	<a class="nav-link" href="users.php">Users</a>
				      	</li>
				      	<li class="nav-item">
				        	<a class="nav-link" href="poules.php">Poules</a>
				      	</li>

			      	<?php } ?>

		      	<?php } ?>

	        </ul>
	    </div>
	    <div class="mx-auto order-0">
	        <a class="navbar-brand mx-auto" href="#">Poules4ALL</a>
	        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
	            <span class="navbar-toggler-icon"></span>
	        </button>
	    </div>
	    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
	        <ul class="navbar-nav ml-auto">
	            <?php if(User::Logged()) { ?>

	            	<li class="nav-item dropdown">
				        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				          	<?php echo $user->data->username; ?>
				        </a>
				        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
				          	<a class="dropdown-item" href="?logout">Logout</a>
				        </div>
				    </li>

	            <?php } ?>
	        </ul>
	    </div>
	</nav>

	<div class="container-fluid">

	<br>

	<?php Message::Check(); ?>
