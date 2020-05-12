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

	public static function GetPointsFromResult($result, $user_value)
	{
		$r = explode("|", $result);
		$u = explode("|", $user_value);

		$total_points = 0;

		//loop door alle resultaten. (user + admin)
		foreach($r as $i => $res)
		{
			//als de gekozen opties van de user in de resultaten array staat, geef hem 1 punt.
			if(in_array($res, $u))
				$total_points ++;

			//als de index niet bestaat, skippen
			if(!isset($u[$i]) || $res != $u[$i])
				continue;

			//ieder index heeft zijn eigen punten telling.
			switch($i)
			{
				case 0:
				{
					$total_points += 10;
					break;
				}
				case 1:
				{
					$total_points += 6;
					break;
				}
				case 2:
				{
					$total_points += 4;
					break;
				}
				case 3:
				{
					$total_points += 2;
					break;
				}
			}
		}

		return $total_points;
	}

	public static function GetUserBet($user_id, $poule_id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `user_bets` WHERE `user_id` = :user AND `poule_id` = :poule");
		$query->bindParam(":user", $user_id);
		$query->bindParam(":poule", $poule_id);

		if(!$query->execute())
			return NULL;

		if(!$query->rowCount())
			return NULL;

		return $query->fetch(PDO::FETCH_OBJ)->data;

	}

	public static function FormatBet($user_id, $poule_id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `user_bets` WHERE `user_id` = :user AND `poule_id` = :poule ORDER BY `id` DESC LIMIT 1");
		$query->bindParam(":user", $user_id);
		$query->bindParam(":poule", $poule_id);

		if(!$query->execute())
			return NULL;

		//explode de bet met de | delimiter en display de rows.
		$data = explode("|", $query->fetch(PDO::FETCH_OBJ)->data);

		foreach($data as $i => $d)
		{
			echo '

				<div class="row m-3">
			    	<div clas="col-md">#' . ($i + 1) . '</div>
			    	<div class="col-md">
			    		<select name="option_' . ($i + 1) . '" class="form-control" disabled><option>' . $d . '</option></select>
			    	</div>
			    </div>

			';
		}
	}

	public static function SetResult($poule_id, $data)
	{
		global $db;

		$query = $db->prepare("UPDATE `poules` SET `results` = :d WHERE `id` = :id");
		$query->bindParam(":d", $data);
		$query->bindParam(":id", $poule_id);

		return $query->execute();
	}

	public static function UnsetResult($poule_id)
	{
		global $db;

		$query = $db->prepare("UPDATE `poules` SET `results` = NULL WHERE `id` = :id");
		$query->bindParam(":id", $poule_id);

		return $query->execute();
	}

	public static function AddBet($user_id, $poule_id, $data)
	{
		global $db;

		$query = $db->prepare("INSERT INTO `user_bets` (`user_id`, `poule_id`, `data`) VALUES (:user, :poule, :data)");
		$query->bindParam(":user", $user_id);
		$query->bindParam(":poule", $poule_id);
		$query->bindParam(":data", $data);

		return $query->execute();
	}

	public static function HasBet($user_id, $poule_id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `user_bets` WHERE `user_id` = :user AND `poule_id` = :poule");
		$query->bindParam(":user", $user_id);
		$query->bindParam(":poule", $poule_id);

		if(!$query->execute())
			return false;

		return $query->rowCount();
	}

	public static function GetPoulesForUser($user_id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `user_poules` WHERE `user_id` = :id");
		$query->bindParam(":id", $user_id);

		if(!$query->execute())
			return NULL;

		$poules = [];

		//voeg alle poules van de user in de array en returneer die.
		foreach($query->fetchAll(PDO::FETCH_OBJ) as $p)
		{
			$poules[] = Poule::Get($p->poule_id);
		}

		return $poules;
	}

	public static function GetAll()
	{
		global $db;

		$query = $db->query("SELECT * FROM `poules`");

		if(!$query->execute())
			return NULL;

		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	public static function GetAllUsers($poule_id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `user_poules` WHERE `poule_id` = :id");
		$query->bindParam(":id", $poule_id);

		if(!$query->execute())
			return NULL;

		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	public static function DeleteMember($poule_id, $user_id)
	{
		global $db;

		$query = $db->prepare("DELETE FROM `user_poules` WHERE `user_id` = :id AND `poule_id` = :p_id");
		$query->bindParam(":id", $user_id);
		$query->bindParam(":p_id", $poule_id);

		if(!$query->execute())
			return false;

		$query = $db->prepare("DELETE FROM `user_bets` WHERE `user_id` = :id AND `poule_id` = :p_id");
		$query->bindParam(":id", $user_id);
		$query->bindParam(":p_id", $poule_id);

		return $query->execute();
	}

	public static function HasUser($poule_id, $user_id)
	{
		global $db;

		$query = $db->prepare("SELECT * FROM `user_poules` WHERE `user_id` = :user AND `poule_id` = :poule");
		$query->bindParam(":user", $user_id);
		$query->bindParam(":poule", $poule_id);

		if(!$query->execute())
			return true;

		return $query->rowCount();
	}

	public static function AddUser($poule_id, $user_id)
	{
		global $db;

		$query = $db->prepare("INSERT INTO `user_poules` (`user_id`, `poule_id`) VALUES (:user, :poule)");
		$query->bindParam(":user", $user_id);
		$query->bindParam(":poule", $poule_id);

		if(!$query->execute())
			return false;

		return $db->lastInsertId();
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