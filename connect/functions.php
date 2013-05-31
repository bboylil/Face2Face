<?php
function rooms(){
       include 'conecta_bd.php';
       $sql = "SELECT * FROM rooms";
       $result = pg_query($dbconn,$sql);
       
       $i=1;   
       while ($row = pg_fetch_assoc($result)){
		   if($i%2==0){
               echo "<p style='padding: 0px; border: 1px solid black; width: 300px; background-color: white;'>".$row['nsala']."</p>";
           }else{
			   echo "<p style='padding: 0px; margin: 0px; border: 1px solid black;  width: 300px; background-color: #E6E6E6;'> ".$row['nsala']."</p>";
			   }
			$i++;
       }
}

function users($email){
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
		echo json_encode($array);
		return 1;
}
?>
