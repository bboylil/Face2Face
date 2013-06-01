<?php
	if (isset($_POST['oldpass']) && isset($_POST['npass1']) && isset($_POST['npass2']))
	{
		$oldpass=$_POST['oldpass'];
		$npass1=$_POST['npass1'];
		$npass2=$_POST['npass2'];
		$valid_user=$_SESSION["valid_user"];

		$valpass=val_pass($npass1,$npass2);
		if(!$valpass){
			$oldpassCOD=sha1($oldpass);
			$sql = "SELECT * from users where email = '$valid_user'";
			$result = pg_query($sql) or die('La consulta ha fallat.' . pg_last_error());
			if (!empty($_SESSION["valid_user"])){
					 while ($row = pg_fetch_assoc($result)) {
						if($row['passwd']!=$oldpassCOD){
							//La contraseña introducida no coincide con la base de datos	
							$error=6;
						}else{
							$newpassCOD=sha1($npass1);
							$sql = "UPDATE users SET passwd = '".$newpassCOD."' WHERE email = '".$valid_user."';";
							$result = pg_query($sql) or die('La consulta ha fallat.' . pg_last_error());
							$msg=3;
							break;
						}
						break;
					}
				}			
		  }else{
		  	//Las contraseñas nuevas no son iguales
		  	$error = 7;
			
		  }
		}

	
			//COmprobamos que las contraseñas sean iguales
	function val_pass($pass,$pass2){	
		if ($pass!=$pass2){return true;}else{return false;}
	}
?>