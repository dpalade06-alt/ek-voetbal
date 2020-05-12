<?php 

require('libraries/main.class.php');

header("Access-Control-Allow-Origin: 76431.ict-lab.nl");

if($_SERVER['REQUEST_METHOD'] != "POST")
{
	die(json_encode(array("success" => false, "message" => "Invalid request method used.")));
}

if(!isset($_POST['user_id']) || !isset($_POST['poule_id']))
{
	die(json_encode(array("success" => false, "message" => "Invalid parameters specified.")));
}

$poule = Poule::Get($_POST['poule_id']);
$user_bet = Poule::GetUserBet($_POST['user_id'], $_POST['poule_id']);

$u = explode("|", $user_bet);
$r = explode("|", $poule->results);

echo "<h5>His bet:</h5>";

echo "<ol>";

foreach($r as $i => $r_res)
{
	echo "<li>" . $u[$i] . " " . ($r_res == $u[$i] ? "<i class='fas fa-check text-success'></i>" : "<i class='fas fa-times text-danger'></i>") . "</li>";

}

echo "</ol>";

echo "<hr>";

echo "<h5>Score: " . Poule::GetPointsFromResult($poule->results, $user_bet) . "</h5>";