<?php
	//recoge usuario
	$mail=$_POST['mail'];
  	$pass=$_POST['pass'];
  	
	//Conexio a base de datos//
	$user = 'postgres';
	$passwd = 'postgres';
	$db = 'f2f';
	$port = 5432;
	$host = 'localhost';
	$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
	$dbconn = pg_connect($strCnx) or die ("Error de conexio. ". pg_last_error());
?>
