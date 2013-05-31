<?php
session_start();
include '../connect/conecta_bd.php';
include '../connect/functions.php';

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
			<div class='usu'>
				<label class='info-head'>
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
				</label>
					<a href='../connect/cerrarsesion.php' title='Cerrar sesión'><img class='icon' src='../images/logout.png'></a>
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
				<div id="newRoom" class="button">Nueva Sala</div>
				<div id="ver_salas" class="button">Salas</div>
				<div id="ver_usuarios" class="button">Usuarios</div>
		</div>
			<div id="messages"></div>
			
		  </div>
		<div class='main'>

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
