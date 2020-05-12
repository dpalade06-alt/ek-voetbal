<?php

require('libraries/header.class.php');

if(isset($_POST['save']) && isset($_POST['poule_id']) && isset($_POST['option_1']) && isset($_POST['option_2']) && isset($_POST['option_3']))
{
	if(empty($_POST['option_1']) || empty($_POST['option_2']) || empty($_POST['option_3']) || empty($_POST['option_4']))
	{
		Message::Send("error", "You must fill in all the fields.", "index.php");
	}

	if(!Poule::Get($_POST['poule_id']))
	{
		Message::Send("error", "That poule does not exist.", "index.php");
	}

	if(!Poule::HasUser($_POST['poule_id'], $user->data->id))
	{
		Message::Send("error", "You're not allowed to participate on that poule.", "index.php");
	}

	if(Poule::HasBet($user->data->id, $_POST['poule_id']))
	{
		Message::Send("error", "You have already bet on this poule.", "index.php");
	}

	if(!in_array($_POST['option_1'], $countries) || !in_array($_POST['option_2'], $countries) || !in_array($_POST['option_3'], $countries) || !in_array($_POST['option_4'], $countries))
	{
		Message::Send("error", "That country does not exist.", "index.php");
	}

	$data = array($_POST['option_1'], $_POST['option_2'], $_POST['option_3'], $_POST['option_4']);

	if(count(array_keys($data, $_POST['option_1'])) > 1 || count(array_keys($data, $_POST['option_2'])) > 1 || count(array_keys($data, $_POST['option_3'])) > 1 || count(array_keys($data, $_POST['option_4'])) > 1)
	{
		Message::Send("error", "You can't select the same team more than once.", "index.php");
	}

	if(!Poule::AddBet($user->data->id, $_POST['poule_id'], implode("|", $data)))
	{
		Message::Send("error", "Could not save bet. Please try again later.", "index.php");
	}

	Message::Send("success", "You have bet on that poule.", "index.php");
}


?>

<div class="row">

<?php $total = 0; ?>

<?php foreach(Poule::GetPoulesForUser($user->data->id) as $poule) { ?>

	<?php $total ++; ?>

	<div class="col-md-4">

		<div class="card">
		  	<div class="card-header">
		    	<?php echo htmlentities($poule->title); ?> - <a href="poule.php?id=<?php echo $poule->id; ?>">view</a>
		  	</div>
		  	<div class="card-body">

		  		<?php if(Poule::HasBet($user->data->id, $poule->id)) { ?>

		  			<?php if($poule->results) { ?>

		  				<?php 

		  					$user_bet = Poule::GetUserBet($user->data->id, $poule->id);
		  					$results = $poule->results;

		  					$u = explode("|", $user_bet);
		  					$r = explode("|", $results);

		  				?>

		  				<h5 class="card-title">Results are in! You have scored <?php echo Poule::GetPointsFromResult($results, $user_bet); ?> points.</h5>

		  				<ol>

		  				<?php 


							foreach($r as $i => $r_res)
							{
								if($r_res == $u[$i])
								{
									echo "<li>" . $u[$i] . " <i class='fas fa-check text-success'></i></li>";
								}
								else
								{
									if(in_array($u[$i], $r))
									{
										echo "<li>" . $u[$i] . " <i class='fas fa-exclamation-triangle text-warning'></i></li>";
									}
									else
									{
										echo "<li>" . $u[$i] . " <i class='fas fa-times text-danger'></i></li>";
									}
								}

							}


		  				?>

		  				</ol>

		  				Click <a href="poule.php?id=<?php echo $poule->id; ?>">here</a> to view all the bets.


		  			<?php } else { ?>

		  				<h5 class="card-title">You have already bet on this poule.</h5>
		    		
			    		<?php Poule::FormatBet($user->data->id, $poule->id); ?>

				    	<center><input type="submit" class="btn btn-primary" value="Save" disabled name="save"></center>

		  			<?php } ?>

		  		<?php } else { ?>

		  			<?php if($poule->results) { ?>

		  				<h5 class="card-title">You can't place bets anymore. Results:</h5>

		  				<?php 

		  					$r = explode("|", $poule->results);

		  					echo "<ol>";

		  					foreach($r as $i => $u_res)
		  					{
		  						echo "<li>" . $u_res . "</li>";
		  					}

		  					echo "</ol>";

		  				?>

		  				Click <a href="poule.php?id=<?php echo $poule->id; ?>">here</a> to view all the bets.


		  			<?php } else { ?>

		  				<h5 class="card-title">Place your bets.</h5>

			  			<form method="POST">

				    		<div class="row m-3">
				    			<div clas="col-md">#1</div>
				    			<div class="col-md">
				    				<select name="option_1" class="vote_dd form-control"></select>
				    			</div>
				    		</div>

				    		<div class="row m-3">
				    			<div clas="col-md">#2</div>
				    			<div class="col-md">
				    				<select name="option_2" class="vote_dd form-control"></select>
				    			</div>
				    		</div>

				    		<div class="row m-3">
				    			<div clas="col-md">#3</div>
				    			<div class="col-md">
				    				<select name="option_3" class="vote_dd form-control"></select>
				    			</div>
				    		</div>

				    		<div class="row m-3">
				    			<div clas="col-md">#4</div>
				    			<div class="col-md">
				    				<select name="option_4" class="vote_dd form-control"></select>
				    			</div>
				    		</div>

				    		<?php CSRF::Show(); ?>

				    		<input type="hidden" name="poule_id" value="<?php echo $poule->id; ?>">

				    		<center><input type="submit" class="btn btn-primary" value="Save" name="save"></center>

				    	</form>

		  			<?php } ?>

		  		<?php } ?>

		  	</div>
		  	<div class="card-footer text-muted">
		    	Created at <?php echo $poule->time; ?>
		  	</div>
		</div>

		<br>

	</div>

<?php } ?>

<?php if($total == 0) { ?>

	<div class="col-md">

		There are no poules available at the moment.

	</div>

<?php } ?>

</div>

<?php require('libraries/footer.class.php'); ?>