<?php
//nos conectamos a la bse de datos
include "config.php";

  if ($go)
 
   // Seleccionamos el email de la base de datos para verificar
   $result = @mysql_query("SELECT email,password FROM tu_tabla WHERE email='$email'");
   if (!$result) {
     echo("<p>Error al seleccionar tabla: " . mysql_error() . "</p>");
     exit();
   }
   //Chekeamos si existe el email
$sql_check_num = mysql_num_rows($result);
   if($sql_check_num == 0){
   
   //si no te aperce el email del que puso  ej: $email prueba con ".$_POST['email']."
       echo "<table width='467'><tr><td><font color=ff0000 face=verdana>El e-mail <b >$email</b> no fue encontrado en nuestra base de datos</font><br />
       <center>
<p>

<form action=\"enviar_datos.php\" method=\"post\">
Intente de nuevo: <input type=\"text\" name=\"email\">
<input type=\"submit\" value=\"Enviar\" name=\"go\">
</form>
</p></center></td><tr></table>";
       exit();
   }
   // Si va todo bien sacamos todo de la base de datos
   while ( $row = mysql_fetch_array($result) ) {
     $email = $row["email"];
     $password = $row ["password"];

   }
 
   // creamos el email
 
 $mensaje = "Su password para modificar  en   tu_sitio.com es: \n $password no lo vuelvas a perder";
$email_webmaster = "webmaster@tu_dominio.com";
$asunto = "Su contraseña para bla bla bla";

mail($email,$asunto,$mensaje,"FROM: $email_webmaster");

//le decimos al usuario que fue enviado su password
//y que vaya rrapido a revisar su correo electronico

echo  ("<table width='467'><tr><td>tu password ha sido enviado al siguiente correo: $email <br>
   
   por favor dirigirse a <a href='login.php'>sector miembros para ingresar </a>
</td><tr></table>");

?>
 Enviado a la(s) 16:34 del lunes
 
