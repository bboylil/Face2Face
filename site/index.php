<?php
session_start();
include '../connect/conecta_bd.php';
include '../connect/functions.php';
include '../connect/cambiopass.php';
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
						<td>
							<img src='../images/profile.gif' alt='perfil' class='ppic'>
						</td>
						<td class='pnick'>
					<?php
						$valid_user = $_SESSION['valid_user'];
						$sql ="select nick from users where email='$valid_user'";
						$result = pg_query($sql) or die('La consulta ha fallat: ' . pg_last_error());
				  
						  if (!empty($_SESSION["valid_user"])){
							 while ($row = pg_fetch_assoc($result)) {
								echo $row['nick'];
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
		  <div class="menu">
			<div id="mostrar">
						<div class='users'>
							<ul class="users_list">
            
          					</ul>
							<?php
							//users($valid_user);
							?>
						</div>
						<div class='rooms'>
							<ul class="sales_list">
								<?php
								$salas = rooms();
								foreach ($salas as $s) { ?>
									<li><?=$s['nsala']?></li>	
								<?php } ?>
							</ul>
						</div>
			
			</div>
			<div class="buttonBox">
				<input type='button' id="newRoom" class="button" value="+ Sala">
				<input type='button' id="ver_salas" class="button" value="Salas">
				<input type='button' id="ver_usuarios" class="button" value="Usuarios">
		</div>
			<div id="messages"></div>
			
		  </div>
		<div class='main'>
			<div class='formpasschange'>
				<form action='index.php' method='POST'>
					<label class="titulo">Cambio de contraseña</label><br>
					<input class="campo" type='Password' name="oldpass" placeholder="Contraseña antigua"><br><br>
					<label class="titulo">Introduzca su contraseña nueva</label>
					<input class="campo" type='Password' name="npass1" placeholder="Contraseña"><br>
					<input class="campo" type='Password' name="npass2" placeholder="Repita la contraseña"><br>
								<?php
						if ($error==6){
							echo "Las contraseña introducida no coincide con la registrada";
							
						}else if($error==7){
							echo "Las contraseñas introducidas deben ser iguales!";
						}else if($msg==3){
							echo "La contraseña ha sido cambiada con exito";
							
						}
					?>
					<input type="submit" id='aceptar' class='button' value='Aceptar'>

					
				</form>
			</div>

			<div id="videos">
				<video id="you" class="flip" autoplay width="100%" height="100%" style="position: relative; float:left;"></video>
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
