<?php 

class Mail {

	public static function Send($to, $subject, $message)
	{
		//zet de email headers in een variabele.
		$headers = 'From: ' . SENDER_EMAIL . "\r\n" .
		    'Reply-To: ' . SENDER_EMAIL . "\r\n" .
		    'X-Mailer: PHP/' . phpversion() . 
		    'MIME-Version: 1.0' . "\r\n" .
		    'Content-type: text/html' . "\r\n";

		//verstuur de email en wacht op de status.
		$status = mail($to, $subject, $message, $headers);
		
		return $status;
	}

}