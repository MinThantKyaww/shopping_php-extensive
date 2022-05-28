<?php
session_start();
require 'config/config.php';
require 'config/common.php';

if ($_POST) {
	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address'])
	 || empty($_POST['password']) || strlen($_POST['password']) < 4) {
		if (empty($_POST['name'])) {
			$nameError = "1";
		}
		if (empty($_POST['email'])) {
			$emailError = "1";
		}
		if (empty($_POST['phone'])) {
			$phoneError = "1";
		}
		if (empty($_POST['address'])) {
			$addressError = "1";
		}
		if (empty($_POST['password'])) {
			$passwordError = "1";
		}
		if (strlen($_POST['password']) < 4) {
			$passwordError = "Password must be al lesat 4 characters";
		}
	}else{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$password = $_POST['password'];
		$passwordHash = password_hash($_POST['password'],PASSWORD_BCRYPT);

		$stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
		$stmt->execute(
			[':email'=>$email]
		);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			echo "<script>alert('Email already exist');</script>";
		}else{
			$stmt = $pdo->prepare("INSERT INTO users(name,email,phone,address,password) VALUES 
				(:name,:email,:phone,:address,:password)");
			$stmt->execute(
				array(':name'=>$name,':email'=>$email,
					':phone'=>$phone,':address'=>$address,':password'=>$passwordHash)
			);

			echo "<script>alert('Successfully registered.U can now log in');window.location.href='login.php';</script>";
		}

	}
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Karma Shop</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html"><h4>AP Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="#" class="cart"><span class="ti-bag"></span></a></li>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_form_inner">
						<h3>Log in to enter</h3>
						<form class="row login_form" action="register.php" method="post" id="contactForm" novalidate="novalidate">
							<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="name" placeholder="Name" 
								style="<?php echo empty($nameError) ? '' : 'border-bottom: 1px solid red;';?>" 
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'">
							</div>
							<div class="col-md-12 form-group">
								<input type="email" class="form-control" id="name" name="email" placeholder="Email" 
								style="<?php echo empty($emailError) ? '' : 'border-bottom: 1px solid red;';?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="phone" placeholder="Phone"
								style="<?php echo empty($phoneError) ? '' : 'border-bottom: 1px solid red;';?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="address" placeholder="Address" 
								style="<?php echo empty($addressError) ? '' : 'border-bottom: 1px solid red;';?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" id="name" name="password" 
								placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
								<p style="text-align: left;"><?php echo empty($passwordError) ? '' : $passwordError;?></p>
							</div><br><br>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Register</button>
								<a class="primary-btn" href="login.php">Log in</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
	<div class="container">
	<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
	  <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
	Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved<i class="fa fa-heart-o" aria-hidden="true"></i><a href="#" target="_blank"></a>
	<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
	</p>
	</div>
	</div>
	</footer>
	<!-- End footer Area -->


	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>