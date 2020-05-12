<?php 

class CSRF {

	public static function Generate()
	{
		//genereer random bytes for de csrf token
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}

	public static function Show()
	{
		//als de token nog niet gegenereerd is, maak hem dan aan.
		if(!isset($_SESSION['token']))
			CSRF::Generate();

		echo '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">';
	}

	public static function Verify()
	{
		//als de request geen post request is, returneer dan false.
		if($_SERVER['REQUEST_METHOD'] != "POST")
			return false;

		//als de variabele niet bestaan, stuur de user terug naar de index.
		if(!isset($_SESSION['token']) || !isset($_POST['token']))
		{
			Message::Send("error", "An error occured while trying to process your request. (CSRF)", "index.php");
		}

		//als de csrf tokens niet overeenkomen, stuur de user terug naar de index.
		if(strcmp($_SESSION['token'], $_POST['token']))
		{
			Message::Send("error", "An error occured while trying to process your request. (CSRF)", "index.php");
		}
	}
	
}