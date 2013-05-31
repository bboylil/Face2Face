<?php
session_start();
include 'connect/conecta_bd.php';
include 'connect/conecta.php';
include 'connect/registro.php';
include 'gmail.php';
?>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Face2Face</title>
	<link rel="stylesheet" type="text/css" href="style/default.css" />
	<script src='js/jquery.min.js'></script>
	<script src='js/efectos.js'></script>
	
	
</head>
<body>
	<div id='banner'>
		<h2>Bienvenido a Face2Face Chat</h2>
		<h3>Conoce gente face to face y unete a nuestra comunidad</h3>
	</div>
	
	<div id='formularios' >
		
		
			<!--Inicio sesion -->
			<div id="login" class='form'>
				<div id='form1'>
					<form name='cred' action='index.php' method='POST'>	
						<input id="jid" type="text"  name='mail' placeholder="Correo electronico">
						<input id="pass" type="password" name='pass' placeholder="Password" style="width:150px;">
						<input id="connect" class="button" type="submit" value="Inicio de sesion">
					</form>
					<div class="log" id='log'>
						<?php
						if ($usu_val==1){
							echo "Datos erroneos";
						}else if($error==5){
							echo "El mail no se encuentra en nuestra base de datos";
							
						}else if($msg==1){
							echo " <p style='color:green';>Se ha enviado la contraseña nueva al mail introducido</p>";
							
						}
						?>
					</div>
					<div class='subtitulo'>
						<input id='olvido' class='button giro' value="¿Olvidó su contraseña?">
					</div>
				</div>
				
				
				<div id='form2' class='form2'>
					<h3 class='subtitulo2'>Introduzca el mail con el que se registró para enviarle una nueva contraseña</h3>
					<form name='cred' action='index.php' method='POST'>	
						<input id="jid" type="text"  name='mail2' placeholder="Correo electronico" style="width:190px;">
						<input id="restaurar" class="button button2" type="submit" value="Restaurar">
					</form>
				</div>
			</div>
			
			
			<!--Registro -->
			<div id="registro" class='form'>
				<p id='titulo' style='color: gray;'>¿Eres nuevo en Face2Face? Registrate</p>
				<form method="post">
					<input id="username" type="text" name="username" placeholder="Nombre de usuario">
					<input id="password" type="password" name="password" placeholder="Password">
					<input id="password2" type="password" name="password2" placeholder="Repita el password">			
					<input id="name" type="text" name="name" placeholder="Nombre completo">
					<input id="email" type="text" name="email" placeholder="E-mail">
					<input id="bregistro" class="button" type="submit" value="Registrate en Face2Face">
				</form>
				<div class='log2'>
					<?php
					
						if ($error==1){
							echo "Ese nick ya esta registrado";
							}else if($error==2){
								echo "Las contraseñas no coinciden";
							}else if($error==3){
								echo "El mail introducido es incorrecto";
							}else if($error==4){
								echo "El mail introducido ya está registrado";
							}else if($msg==2){
								echo " <p style='color:green';>Registro se ha realizado con exito</p>";
							}
						
					?>
				</div>
		</div>
	</div>

</body>
</html>
