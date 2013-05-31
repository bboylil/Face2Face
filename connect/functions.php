<?php
session_start();

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	
	users($_SESSION['valid_user']);
}

function rooms(){
	include 'conecta_bd.php';
	$sql = "SELECT * FROM rooms";
	$result = pg_query($dbconn,$sql);   

	$salas = pg_fetch_all($result);

	return $salas;
}

function users($email){
	if(!$email){
		echo json_encode(array("code" => 0, "msg" => "Sesión no iniciada"));
		return;
	}

	include 'conecta_bd.php';
    
     $sql ="select * from users where email='$email'";
       $result = pg_query($dbconn,$sql);  
       while ($row = pg_fetch_assoc($result)){
		  $iduser=$row['id_user'];
		  $nick=$row['nick'];
       }

		$array = array(
			"iduser"  => $iduser,
			"nick"  => $nick,
		);

		echo json_encode(array("code" => 1, "data" => $array));
		return;
}
?>
