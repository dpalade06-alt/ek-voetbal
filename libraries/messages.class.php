<?php 

class Message {

	public static function Check()
	{
		if(!isset($_SESSION['message']) || !isset($_SESSION['type']))
			return false;

		//echo de javascript stukje voor de toastr library om de notificaties te laten zien.
		echo '<script>toastr["' . $_SESSION['type'] . '"]("' . $_SESSION['message'] . '")</script>';

		//haal de variabele weg uit de sessie zodat je niet in een loop van notificaties beland.
		unset($_SESSION['message']);
		unset($_SESSION['type']);
	}

	public static function Send($type, $string, $redirect = "/")
	{
		//zet de waardes in de sessie variabele om de notificatie in het volgende ciclus te displayen. 
		$_SESSION['type'] = $type;
		$_SESSION['message'] = $string;

		header("Location: " . $redirect);
		die();
	}


}