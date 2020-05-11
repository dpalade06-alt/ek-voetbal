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