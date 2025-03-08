<?php
session_start(); 
session_unset(); 
session_destroy(); 
header('Location: http://localhost/hotelseite_php/index.php'); 
exit();
?>