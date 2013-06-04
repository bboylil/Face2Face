<?php
	if (isset($_POST['nsala']) && isset($_POST['ntopic']))
	{
		$nsala=$_POST['nsala'];
		$ntopic=$_POST['ntopic'];

		$sql = "SELECT * from rooms where nsala = '$nsala'";
		$result = pg_query($sql) or die('La consulta ha fallat.' . pg_last_error());

		 if($row = pg_fetch_assoc($result)>0) {
				//La sala ya existe	
				$error=8;
			}else{
				$sql = "INSERT INTO rooms (nsala, topic) VALUES ('".$nsala."','".$ntopic."');";
				$result = pg_query($sql) or die('La consulta ha fallat.' . pg_last_error());
				$msg=4;
		}
	}
?>