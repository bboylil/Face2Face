<?php
session_start();
include '../connect/conecta_bd.php';
include '../connect/functions.php';
include '../connect/cambiopass.php';
include '../connect/nuevasala.php';

if(!$_SESSION['valid_user']) header("Location: /");

?>

<html>
  <head>
	  <meta charset="utf-8">
    <title>Face2Face</title>
    <link type="text/css" href="style.css" rel="stylesheet"></link>
	<script src='../js/jquery.min.js'></script>
	<script src='../js/efectos.js'></script>
    <script src="webrtc.io.js"></script>
  </head>
  <body onload="init()">
    <div class="general">
		<div class='header'> 
			<div id='inf_us' class='usu'>
				<table>
					<tr>
						<td class='filafoto'>
							<img src='../images/profile.gif' alt='perfil' class='ppic'>
						</td>
						<td class='pnick'>
							<?php
								$valid_user = $_SESSION['valid_user'];
								$sql ="select nick from users where email='$valid_user'";
								$result = pg_query($sql) or die('La consulta ha fallat: ' . pg_last_error());
						  
								  if (!empty($_SESSION["valid_user"])){
									 while ($row = pg_fetch_assoc($result)) {
										echo "<a href=# style='color:white;'>".$row['nick']."</a>";
									}
								  }
							  ?>
					  </td>
					</tr>

				</table>
			</div>
			<div id='panel' class='usu2'>
				<ul class='lista'>
					<li  id='cambiarpass' class='elemento'>
						<a style='text-decoration: none; color: #DDDDDD;' href='#'>Cambiar contraseña</a>
					</li>
					<li class='elemento'>
						<a style='text-decoration: none; color: #DDDDDD;' href='../connect/cerrarsesion.php'>Logout</a>
					</li>
				</ul>
			</div>
			
		</div>
		  <div id="menu" class="menu">
			<div id="mostrar">
						<div class='users'>
							<div style="background-color: #732222; color: white; width: 100%;text-align:center;font: 18px 'light','Helvetica Neue',Arial,Helvetica,sans-serif;">Usuarios</div>
							<ul class="users_list">
            
          					</ul>
						</div>
						<div class='rooms'>
							<div style="background-color: #732222; color: white; width: 100%;text-align:center;font: 18px 'light','Helvetica Neue',Arial,Helvetica,sans-serif;">Salas</div>
							<ul class="sales_list">
								<?php
								$salas = rooms();
								foreach ($salas as $s) { ?>
									<li><?=$s['nsala']?></li>	
								<?php } ?>
							</ul>
						</div>
						<div class='nroom'>
							<div style="background-color: #732222; color: white; width: 100%;text-align:center;font: 18px 'light','Helvetica Neue',Arial,Helvetica,sans-serif;">Sala nueva</div><br>
							<form style='text-align:center;' action='index.php' method='POST'>
								<input type='text' style='width: 90%;' class='campo' name='nsala' placeholder='Nombre de la sala'>
								<input type='text' style='width: 90%;' class='campo' name='ntopic' placeholder='Nombre del topic'>
								<input type='submit' id='aceptar' class='button' name='ok' value='Aceptar'><br>
								<?php
								if ($error==8){
									echo "<label style='color: #732222;'>Esa sala ya existe</label>";	
								}else if($msg==4){
									header('Location: index.php');
								}
							?>
							</form>
						</div>
			
			</div>
			<div class="buttonBox">
				<input type='button' id="newRoom" class="button" value="+ Sala">
				<input type='button' id="ver_usuarios" class="button" value="Usuarios">
				<input type='button' id="ver_salas" class="button" value="Salas">
			</div>
			<div id="messages">
				<div id='cabecera_sala' class='cabecera_sala'></div>
				<hr id='divisor' style='border: 1px solid #732222;'>
			</div>
			
		  </div>
		<div id="main" class='main'>
			<div id="passchange" class='passchange' style="display:none">
				<div style='width: 100%; height: 30px;'>
					<label class="titulo">Cambio de contraseña</label>
					<a style='text-decoration:none' href='#'><div id='cerrar' class='cerrar'>X</div>	</a>				
				</div>
				<hr style='border: 1px solid #732222;'>
				<form action='index.php' method='POST' class='formpasschange'>
					<input id="oldpass" class="campo" type='Password' name="oldpass" required="required" placeholder="Contraseña actual"><br><br>
					<hr style='border: 1px solid #732222;'>
					<label class="titulo">Introduzca su contraseña nueva</label><br>
					<hr style='border: 1px solid #732222;'>
					<input id="newpass1" class="campo" type='Password' name="npass1" required="required" placeholder="Contraseña"><br>
					<input id="newpass2" class="campo" type='Password' name="npass2" required="required" placeholder="Repita la contraseña"><br>
					<div id="errores" class='errores'></div>
					<input type="submit" id='aceptar' class='button' value='Aceptar'>

					
				</form>
			</div>

			<div id="videos">
				<video id="you" class="flip" autoplay width="100%" height="100%"></video>
			</div>
			
			<div id="chatbox">
			  <input id="chatinput" type="text" placeholder="Mensaje:"/>

			</div>
		</div>
    </div>
    
    <script src="script.js"></script>
    <script type="text/javascript">
      $('.sales_list li').click(function(){
        var val = $(this).html();
        val = val.replace(" ","_");

        window.location.hash = val;
    	location.reload();
      });
    </script>
  </body>
</html>
