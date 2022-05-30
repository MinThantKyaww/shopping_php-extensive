<?php 
session_start();
unset($_SESSION['cart']['id'.$_GET['id']]);
header("location: cart.php");
 ?>