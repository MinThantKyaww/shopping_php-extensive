<?php include('header.php') ?>
		<?php 
			require 'config/config.php';
		?>
		<?php

		if (!empty($_POST['search'])) {
      		setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
	  }else{
	      if (empty($_GET['pageno'])) {
	        unset($_COOKIE['search']); 
	        setcookie('search', null, -1, '/'); 
	     	}	
	  }

	  if (!empty($_GET['$category_id'])) {
	  	setcookie('category_id',$_GET['$category_id'], time() + (86400 * 30), "/");
	  } else {
	  	if (empty($_GET['pageno'])) {
	  		unset($_COOKIE['category_id']);
	  		setcookie('category_id', null, -1, '/');
	  	}
	  }
	  

        if (!empty($_GET['pageno'])) {
          $pageno = $_GET['pageno'];
        }
        else {
          $pageno = 1;
        }
          $frames = 6;
          $offset = ($pageno - 1) * $frames;

        if (empty($_POST['search']) && !isset($_COOKIE['search'])) {
          	if (!empty($_GET['category_id'])) {
							$category_id = $_GET['category_id'];
							$pdostatement = $pdo->prepare("SELECT * FROM products WHERE category_id LIKE '%$category_id%' ORDER BY id DESC");
							$pdostatement->execute();
							$rawResult = $pdostatement->fetchAll();
							$totalpages = ceil(count($rawResult)/$frames);

							$pdostatement = $pdo->prepare("SELECT * FROM products WHERE category_id LIKE '%$category_id%' ORDER BY id DESC LIMIT $offset,$frames");
							$pdostatement->execute();
							$result = $pdostatement->fetchAll();
						}else{
							$pdostatement = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
							$pdostatement->execute();
							$rawResult = $pdostatement->fetchAll();
							$totalpages = ceil(count($rawResult)/$frames);

							$pdostatement = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$frames");
							$pdostatement->execute();
							$result = $pdostatement->fetchAll();
			      
						}
			
				}else{
						$searchKey = empty($_POST['search']) ? $_COOKIE['search'] : $_POST['search'];
						$pdostatement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
						$pdostatement->execute();
						$rawResult = $pdostatement->fetchAll();
						$totalpages = ceil(count($rawResult)/$frames);

						$pdostatement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$frames");
						$pdostatement->execute();
						$result = $pdostatement->fetchAll();
				}

          
        ?>

        <!-- Start Banner Area -->
				<section class="banner-area organic-breadcrumb">
					<div class="container">
						<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
							<div class="col-first">
								<h1>Welcome</h1>

							</div>
						</div>
					</div>
				</section>
				<!-- End Banner Area -->
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						<li class="main-nav-list">
					<?php
						$catstmt = $pdo->prepare("SELECT * FROM categories");
						$catstmt->execute();
						$catResult = $catstmt->fetchAll();
					?>

					<?php foreach ($catResult as $key => $value) { ?>
						<a href="index.php?category_id=<?php echo $value['id']?>" >
						<span class="lnr lnr-arrow-right"></span><?php echo escape($value['name'])?>
						</a>
					<?php  } ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="pagination">
						<a href="?pageno=1" class="active">First</a>
						<a href="<?php if ($pageno <=1) {echo '#';}else {echo "?pageno=".($pageno-1);}?>" 
						class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
						<a href="#" class="active"><?php echo $pageno;?></a>
						<a href="<?php if($pageno>=$totalpages){echo '#';}else{echo "?pageno=".($pageno+1);}?>" 
						class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
						<a href="?pageno=<?php echo $totalpages;?>" class="active">Last</a>
					</div>
				</div>
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<?php foreach ($result as $key => $value) { ?>
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
									<a href="product-details.php?id=<?php echo $value['id'] ?>">
										<img class="" width="250px" height="200px"
									src="admin/images/<?php echo escape($value['image']) ?>" alt="">
									</a>
								<div class="product-details">
									<h6><?php echo escape($value['name']) ?></h6>
									<div class="price">
										<h6><?php echo escape($value['price']) ?></h6>
									</div>
									<div class="prd-bottom">
										<form action="addtocart.php" method="post">
										<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            				<input type="hidden" name="id" value="<?php echo escape($value['id'])?>">
            				<input type="hidden" name="qty" value="1">
											<div class="social-info">
												<button type="submit" class="social-info" style="display: contents;">
												<span class="ti-bag"></span><p style="left: 20px" class="hover-text">add to bag</p>
											</button>
											</div>
										<a href="product-details.php?id=<?php echo $value['id'] ?>" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
										</a>
										</form>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</section>
				<!-- End Best Seller -->
<?php include('footer.php');?>
