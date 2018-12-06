<?php

class Mail{
	
	public static function send_registration_email($user, $password){
		$mailsubject = 'Portalul Imobiliar "CasaTa" www.casata.md - Inregistrare';
		$mailmessage = '
		<html>
		<head>
		 <title>Portalul Imobiliar "CasaTa" www.casata.md - Inregistrare</title>
		</head>
		<body>
		<b>Portalul Imobiliar "CasaTa" <a href="https://www.casata.md">www.casata.md</a> - Inregistrare</b>
		<p>Utilizator: '.$user.'</p>
		<p>Parola: '.$password.'</p>
		Pentru a va logina accesati pagina  <a href="https://accounts.casata.md/login.php">https://accounts.casata.md/login.php</a>
		</body>
		</html>
		';
		Mail::send_email($user, $mailsubject, $mailmessage);
	}

	public static function send_password_email($user, $password){
		$mailsubject = 'Portalul Imobiliar "CasaTa" www.casata.md - Parola Contului';
		$mailmessage = '
		<html>
		<head>
		 <title>Portalul Imobiliar "CasaTa" www.casata.md - Parola Contului</title>
		</head>
		<body>
		<b>Portalul Imobiliar "CasaTa" <a href="https://www.casata.md">www.casata.md</a> - Parola Contului</b>
		<p>Utilizator: '.$user.'</p>
		<p>Parola: '.$password.'</p>
		Pentru a va logina accesati pagina  <a href="https://accounts.casata.md/login.php">https://accounts.casata.md/login.php</a>
		</body>
		</html>
		';
		Mail::send_email($user, $mailsubject, $mailmessage);
	}
	public static function send_comment_email($comment){
		$user=Config::$adminmail;
		$mailsubject = 'Comentariu';
		$mailmessage = '
		<html>
		<head>
		 <title>Comentariu:</title>
		</head>
		<body>
		<p>'.$comment->comment.'</p>
		<p><a href="'.$comment->getLink().'">'.$comment->getLink().'<a/></p>
		<p><a href="'.$comment->getAproveLink().'">'.$comment->getAproveLink().'<a/></p>
		</body>
		</html>
		';
		Mail::send_email($user, $mailsubject, $mailmessage);
	}
	public static function send_commentaprove_email($comment){
		$user=Config::$adminmail;
		if($comment->email!=''){
			$user=$comment->email;
		}
		
		$mailsubject = 'Comentariu Aprobat';
		$mailmessage = '
		<html>
		<head>
		<title>Comentariu Aprobat</title>
		</head>
		<body>
		<p>Buna '.$comment->name.'.</p>		
		<p>Comentariul de mai jos a fost aprobat de operator:</p>
		<p>'.$comment->comment.'</p>
		<p><a href="'.$comment->getLink().'">'.$comment->getLink().'<a/></p>
		</body>
		</html>
		';
		Mail::send_email($user, $mailsubject, $mailmessage);
	}		
	public static function send_email($reciever, $subject, $message){
		$antet  = 'MIME-Version: 1.0\r\n';
		$antet .= 'Content-type: text/html; charset=utf-8\r\n';
		$antet .= 'From: Portalul Imobiliar "CasaTa" <'.Config::$adminmail.'>\r\n';
		$antet .= 'Bcc: '.Config::$adminmail.'\r\n';
		if (Config::$live) {
			return mail($reciever, $subject, $message, $antet);
		}
	
	}
}
?>
