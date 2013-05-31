<?
  session_start();
  unset($_SESSION["valid_user"]); 
  session_destroy();
  header("Location: ../index.php");
  exit;
?>
