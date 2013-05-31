<?php
	if (isset($_POST['mail2']))
	{
		$mail2=$_POST['mail2'];
		$valmail=val_mail($mail2);
		if(!$valmail){
			$error = 5;	
		}else{
			$msg = 1;
			
			$pass = $mail2;
			$passwd=sha1($pass);
			$sql2 = "UPDATE users SET passwd = '".$passwd."' WHERE email = '".$mail2."';";
			$result = pg_query($sql2) or die('La consulta ha fallat.' . pg_last_error());
			mail("$mail2","asuntillo","jeje");
			

		
	}

	}

?>