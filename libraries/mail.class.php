<?php 

class Mail {

	public static function Send($to, $subject, $message)
	{
		$headers = 'From: ' . SENDER_EMAIL . "\r\n" .
		    'Reply-To: ' . SENDER_EMAIL . "\r\n" .
		    'X-Mailer: PHP/' . phpversion() . 
		    'MIME-Version: 1.0' . "\r\n" .
		    'Content-type: text/html' . "\r\n";

		$status = mail($to, $subject, $message, $headers);
		
		return $status;
	}

}