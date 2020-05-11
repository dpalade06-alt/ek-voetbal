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
	    <link href="css/toastr.css" rel="stylesheet">

	    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    	<script src="js/toastr.js"></script>

	    <title>Poules4ALL | Home</title>
  	</head>
  	<body>
  	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  	<a class="navbar-brand" href="#">Navbar</a>
	  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
	  	</button>
	  	<div class="collapse navbar-collapse">
		    <ul class="navbar-nav">

		      	<?php if(User::Logged()) { ?>

		      		<li class="nav-item active">
			        	<a class="nav-link" href="/">Home</a>
			      	</li>

			      	<li class="nav-item">
			        	<a class="nav-link" href="users.php">Users</a>
			      	</li>
			      	<li class="nav-item">
			        	<a class="nav-link" href="poules.php">Poules</a>
			      	</li>

		      	<?php } ?>

		    </ul>
	  	</div>
	</nav>

	<div class="container-fluid">

	<br>

	<?php Message::Check(); ?>
