
<?php
	if (isset($_POST['mail2']))
	{
		$mail2=$_POST['mail2'];
		$valmail=val_mail($mail2);
		if(!$valmail){
			$error = 5;	
		}else{
			$msg = 1;
			$caracteres='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$longpalabra=8;
			for($pass='', $n=strlen($caracteres)-1; strlen($pass) < $longpalabra ; ) {
				$x = rand(0,$n);
				$pass.= $caracteres[$x];
			}
			$passwd=sha1($pass);
			$sql2 = "UPDATE users SET passwd = '".$passwd."' WHERE email = '".$mail2."';";
			$result = pg_query($sql2) or die('La consulta ha fallat.' . pg_last_error());
			$sql3 = "select * from users where email = '".$mail2."';";
			$result = pg_query($sql3) or die('La consulta ha fallat.' . pg_last_error());
			while ($row = pg_fetch_assoc($result)) {
				$nick= $row['nick'];
			}
			//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'class.phpmailer.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->IsSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
//$mail->SMTPDebug  = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host       = 'smtp.gmail.com';
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port       = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth   = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username   = "elpuigface2face@gmail.com";
//Password to use for SMTP authentication
$mail->Password   = "Face2Face";
//Set who the message is to be sent to
$mail->AddAddress($mail2, $nick);
//Set the subject line
$mail->Subject = 'PHPMailer GMail SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
$mail->MsgHTML('
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>PHPMailer Test</title>
</head>
<body>
  <div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
    <h1>Cambio de usuario</h1>
    <p>Se ha cambiado la contraseña para su usuario.</p>
    <p>Password: '.$pass.'</p>
  </div>
</body>
</html>')	;
//Send the message, check for errors
		if(!$mail->Send()) {
		  echo "Error al enviar el mesnaje";
		} 
		}

	}


?>
