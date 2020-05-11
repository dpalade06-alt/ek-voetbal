<?php 

class Message {

	public static function Check()
	{
		if(!isset($_SESSION['message']) || !isset($_SESSION['type']))
			return false;

		echo '<script>toastr["' . $_SESSION['type'] . '"]("' . $_SESSION['message'] . '")</script>';

		unset($_SESSION['message']);
		unset($_SESSION['type']);
	}

	public static function Send($type, $string, $redirect = "/")
	{
		$_SESSION['type'] = $type;
		$_SESSION['message'] = $string;

		header("Location: " . $redirect);
		die();
	}


}