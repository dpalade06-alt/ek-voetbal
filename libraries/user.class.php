<?php 

class User {

	public $data = NULL;

	public static function Exists($username)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `users` WHERE `username` = :username");
		$query->bindParam(":username", $username);

		if(!$query->execute())
			return false;

		return $query->rowCount();
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

	public static function GetAll()
	{
		global $db;

		$query = $db->query("SELECT * FROM `users`");

		if(!$query->execute())
			return NULL;

		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	public static function Update($id, $username, $admin, $password = "")
	{
		global $db;

		$query = NULL;

		if(empty($_POST['password']))
		{
			$query = $db->prepare("UPDATE `users` SET `username` = :username, `admin` = :admin WHERE `id` = :id");
			$query->bindParam(":username", $username);
			$query->bindParam(":admin", $admin);
			$query->bindParam(":id", $id);
		}
		else
		{
			$password = sha1($_POST['password']);

			$query = $db->prepare("UPDATE `users` SET `username` = :username, `password` = :password, `admin` = :admin WHERE `id` = :id");
			$query->bindParam(":username", $username);
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

	public static function Register($username, $password)
	{
		global $db;

		$password = sha1($password);

		$query = $db->prepare("INSERT INTO `users` (`username`, `password`) VALUES (:username, :password)");
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);

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

		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

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
		return (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['ip']));
	}

}