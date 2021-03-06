<?php 

class Session {

	public static function Check()
	{
		global $user;

		$login_required = array("index.php", "users.php", "poules.php", "user.php");
		$admin_required = array("users.php", "poules.php", "user.php");

		//check of de user is ingelogd.
		foreach($login_required as $login)
		{
			if(basename($_SERVER['SCRIPT_FILENAME']) != $login)
				continue;

			if(User::Logged())
				continue;

			Message::Send("error", "You must be logged in to view that page.", "login.php");
		}

		//check of de user een admin is.
		foreach($admin_required as $admin)
		{
			if(basename($_SERVER['SCRIPT_FILENAME']) != $admin)
				continue;

			if($user->data->admin)
				continue;

			Message::Send("error", "You're not allowed to view that page.", "index.php");
		}

	}

	public static function Process()
	{
		//check als de user uitgelogd wilt worden.
		if(isset($_GET['logout']))
		{
			unset($_SESSION['username']);
			unset($_SESSION['password']);
			unset($_SESSION['ip']);

			Message::Send("success", "You have been logged out.", "login.php");
		}

		//log de user uit als er een verandering is in het ip
		if(isset($_SESSION['ip']) && $_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
		{
			unset($_SESSION['username']);
			unset($_SESSION['password']);
			unset($_SESSION['ip']);

			Message::Send("error", "Your session has expired. Please login again.", "login.php");
		}

		//check voor wachtwoord veranderingen. (als de username / wachtwoord veranderd is, dan wordt je automatisch uitgelogd)
		if(User::Logged() && !User::Check($_SESSION['username'], $_SESSION['password']))
		{
			unset($_SESSION['username']);
			unset($_SESSION['password']);
			unset($_SESSION['ip']);

			Message::Send("error", "Your session has expired. Please login again.", "login.php");
		}
	}


}