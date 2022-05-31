<?php 

require 'config/config.php';

?>
<?php include 'header.php'; ?>
	<!--================Single Product Area =================-->
	<div class="product_image_area">
		<div class="container">
			<div class="row s_product_inner">
				<?php
					$pdostatement = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
					$pdostatement->execute();
					$result = $pdostatement->fetch(PDO::FETCH_ASSOC);
				?>
				<div class="col-lg-6">
					<img class="img-fluid" height="500px" width="500px" src="admin/images/<?php echo $result['image'] ?>" alt="">
				</div>
					<div class="col-lg-5 offset-lg-1">
						<div class="s_product_text">
						<h3><?php echo escape($result['name']) ?></h3>
						<h2><?php echo escape($result['price']) ?></h2>
						<?php
							$cStmt = $pdo->prepare("SELECT name FROM categories WHERE 
								id=".$result['category_id']);
							$cStmt->execute();
							$cRresult = $cStmt->fetch(PDO::FETCH_ASSOC);
						?>
						<ul class="list">
							<li><a class="active" href="#"><span>Category</span>
								<?php echo $cRresult['name']; ?> </a></li>
							<li><a href="#"><span>Availibility</span>
							<?php echo 
							escape($result['quantity']) == 0 ? 'Out Of Stock' : $result['quantity'].'In Stock' ?></a></li>
						</ul>
						<p><?php echo escape($result['description']) ?></p>
						<form action="addtocart.php" method="post">
						<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            			<input type="hidden" name="id" value="<?php echo escape($result['id'])?>">
							<div class="product_count">
							<label for="qty">Quantity:</label>
							<input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
							<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
							 class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
							<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
							 class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
						</div>
						<div class="card_area d-flex align-items-center">
							<button class="primary-btn" style="border:0 !important" 
							href="">Add to Cart</button>
							<a class="primary-btn" href="index.php">Back</a>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div><br><br>
	<!--================End Single Product Area =================-->
<?php include 'footer.php'; ?>