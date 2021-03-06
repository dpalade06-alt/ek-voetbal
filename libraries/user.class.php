<?php 

class User {

	public $data = NULL;

	public static function Exists($username)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `users` WHERE `username` = :username");
		$query->bindParam(":username", $username);

		if(!$query->execute())
			return NULL;

		if(!$query->rowCount())
			return NULL;

		return $query->fetch(PDO::FETCH_OBJ);
	}

	public static function Get($id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return NULL;

		return $query->fetch(PDO::FETCH_OBJ);
	}

	public static function GetUsername($id)
	{
		global $db;

		$query = $db->prepare("SELECT `username` FROM `users` WHERE `id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return "N/A";

		if(!$query->rowCount())
			return "N/A";

		return $query->fetch(PDO::FETCH_OBJ)->username;
	}

	public static function GetAll()
	{
		global $db;

		$query = $db->query("SELECT * FROM `users`");

		if(!$query->execute())
			return NULL;

		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	public static function Update($id, $username, $email, $admin, $password = "")
	{
		global $db;

		$query = NULL;

		//als er geen wachtwoord word opgegeven, sla alleen de overige details op.
		if(empty($_POST['password']))
		{
			$query = $db->prepare("UPDATE `users` SET `username` = :username, `email` = :email, `admin` = :admin WHERE `id` = :id");
			$query->bindParam(":username", $username);
			$query->bindParam(":email", $email);
			$query->bindParam(":admin", $admin);
			$query->bindParam(":id", $id);
		}
		else
		{
			$password = sha1($_POST['password']);

			$query = $db->prepare("UPDATE `users` SET `username` = :username, `email` = :email, `password` = :password, `admin` = :admin WHERE `id` = :id");
			$query->bindParam(":username", $username);
			$query->bindParam(":email", $email);
			$query->bindParam(":password", $password);
			$query->bindParam(":admin", $admin);
			$query->bindParam(":id", $id);
		}

		return $query->execute();
	}

	public static function Delete($id)
	{
		global $db;

		$query = $db->prepare("DELETE FROM `users` WHERE `id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return false;

		$query = $db->prepare("DELETE FROM `user_bets` WHERE `user_id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return false;

		$query = $db->prepare("DELETE FROM `user_poules` WHERE `user_id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return false;

		return true;

	}

	public static function Register($username, $password, $email)
	{
		global $db;

		$password = sha1($password);

		$query = $db->prepare("INSERT INTO `users` (`username`, `password`, `email`) VALUES (:username, :password, :email)");
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);
		$query->bindParam(":email", $email);

		return $query->execute();
	}

	public static function Login($username, $password)
	{
		global $db;
		global $user;

		$password = sha1($password);

		$query = $db->prepare("SELECT * FROM `users` WHERE `username` = :username AND `password` = :password");
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);

		if(!$query->execute())
			return false;

		if(!$query->rowCount())
			return false;

		//sla de username, wachtwoord en IP op in een sessie zodat we later kunnen checken of de user is ingelod.
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

		//haal de meest recente waardes op uit de database.
		$user->data = $query->fetch(PDO::FETCH_OBJ);

		return true;
	}

	public static function Check($username, $password)
	{
		global $db;
		global $user;

		$query = $db->prepare("SELECT * FROM `users` WHERE `username` = :username AND `password` = :password");
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);

		if(!$query->execute())
			return false;

		if(!$query->rowCount())
			return false;

		$user->data = $query->fetch(PDO::FETCH_OBJ);

		return true;
	}

	public static function Logged()
	{
		//check of alle benodigde sessie variabele goed staan.
		return (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['ip']));
	}

}