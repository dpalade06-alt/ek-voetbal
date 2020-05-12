<?php 

class Mail {

	public static function Send($to, $subject, $message)
	{
		$header = "From:" . SENDER_EMAIL . " \r\n";
		$header .= "Cc:" . SENDER_EMAIL . " \r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html\r\n";

		return mail($to, $subject, $message, $header);
	}

}