<?php
	if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['name']) && isset($_POST['email']))
	{
		$mail=$_POST['email'];
		$pass=$_POST['password'];
		$pass2=$_POST['password2'];
		$nick=$_POST['username'];
		$name=$_POST['name'];
		
		
		$valpass=val_pass($pass,$pass2);
		$filtermail=filter_var($mail, FILTER_VALIDATE_EMAIL);
		$valuser=val_usu($nick);
		$valmail=val_mail($mail);
		
		if ($valuser){
			//Usuario ya existe
			$error = 1;
		}else if($valpass){
			//Contraseña mal
			$error = 2;
		}else if(!$filtermail){
			//Mail mal				
			$error = 3;	
		}else if($valmail){
			//Mail ya existe			
			$error = 4;	
		}else{
			$passwd=sha1($pass);
			// Realitzem una senzilla consulta SQL
			$sql = "INSERT INTO users (nick, passwd, email, nombre) VALUES ('".$nick."','".$passwd."','".$mail."','".$name."');";
			$result = pg_query($sql) or die('La consulta ha fallat.' . pg_last_error());
			//Usuario registrado
			$msg =2;
		}

	}
	
	//COmprobamos que las contraseñas sean iguales
	function val_pass($pass,$pass2){	
		if ($pass!=$pass2){return true;}else{return false;}
	}

	//Comprobamos que el usuario no exista
	function val_usu($nick){
		$sql ='select nick from users where '."nick='$nick'" ;
		$result = pg_query($sql) or die('La consulta ha fallat: ' . pg_last_error());
		if (pg_num_rows($result)>0){
			return true;
			
			}else{
				return false;
			} 
	}

	//COmprobamos que el mail no exista
	function val_mail($mail){
		$sql ='select email from users where '."email='$mail'" ;
		$result = pg_query($sql) or die('La consulta ha fallat: ' . pg_last_error());
		if (pg_num_rows($result)>0){
			return true;
			
			}else{
				return false;
			} 
	}
?>
