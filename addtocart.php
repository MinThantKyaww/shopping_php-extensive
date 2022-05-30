<?php 
session_start();
require 'config/config.php';
if ($_POST) {
	$id = $_POST['id'];
	$qty = $_POST['qty'];

	$pdostatement = $pdo->prepare("SELECT quantity FROM products WHERE id=$id");
	$pdostatement->execute();
	$result = $pdostatement->fetch(PDO::FETCH_ASSOC);

	if ($qty > $result['quantity']) {
		 echo "<script>alert('Not enough item');window.location.href='product-details.php?id=$id';</script>";
	}else{
		if ($_SESSION['cart']['id'.$id]) {
		$_SESSION['cart']['id'.$id] += $qty;
	} else {
		$_SESSION['cart']['id'.$id] = $qty;
	}

	header("location: cart.php");
	}


	
	
}

?>