<?php 

class Poule {

	public static function Get($id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `poules` WHERE `id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return NULL;

		return $query->fetch(PDO::FETCH_OBJ);
	}

	public static function GetAll()
	{
		global $db;

		$query = $db->query("SELECT * FROM `poules`");

		if(!$query->execute())
			return NULL;

		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	public static function Create($title)
	{
		global $db;

		$query = $db->prepare("INSERT INTO `poules` (`title`) VALUES (:title)");
		$query->bindParam(":title", $title);

		if(!$query->execute())
			return false;

		return $db->lastInsertId();
	}

	public static function Delete($id)
	{
		global $db;

		$query = $db->prepare("DELETE FROM `poules` WHERE `id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return false;

		$query = $db->prepare("DELETE FROM `user_poules` WHERE `poule_id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return false;

		$query = $db->prepare("DELETE FROM `user_bets` WHERE `poule_id` = :id");
		$query->bindParam(":id", $id);

		if(!$query->execute())
			return false;

		return true;
	}

}