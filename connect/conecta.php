<?php
	if (isset($_POST['mail']) && isset($_POST['pass']))
	{
		session_start();
		$usuari = $_SESSION['mail'];
		$passwd=sha1($pass);
		// Realitzem una senzilla consulta SQL
		$query ='select * from users where '."email='$mail'"."and passwd='$passwd'" ;
		$result = pg_query($query) or die('La consulta ha fallat: ' . pg_last_error());

		if (pg_num_rows($result)>0){
			$usu_val=0;
			$_SESSION['valid_user']=$mail;
			header( 'Location: site/index.php' ) ;
			}
			
			else{
				$usu_val = 1;
			} 
		pg_close($dbconn);
	}
?>
