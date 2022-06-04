<?php
    session_start();
    require '../config/config.php';
    require '../config/common.php';
    
    if (empty($_SESSION['user_id']) ||  empty($_SESSION['logged_in'])) {
        echo "<script>
        alert('please log in to continue:');
        window.location.href='login.php';
        </script>";
    }

    if ($_POST) {
        if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) 
          || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])) {
            if (empty($_POST['name'])) {
              $nameError = "Category name is required";
            }
            if (empty($_POST['description'])) {
              $descriptionError = "Description is required";
            }
            if (empty($_POST['category'])) {
              $categoryError = "Category is required";
            }
            if (empty($_POST['quantity'])) {
              $quantityError = "Quantity is required";
            }
            if (empty($_POST['price'])) {
              $priceError = "Price is required";
            }
            if (empty($_FILES['image'])) {
              $imageError = "Image is required";
            }
        }else{ //validation success
            if (is_numeric($_POST['quantity']) != 1) {
                $quantityError = "Quantity must be integer";
            }
            if (is_numeric($_POST['price']) != 1) {
                $priceError = "Price must be integer";
            }
            
            if (!isset($quantityError) && !isset($priceError)) {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];
                $image = $_FILES['image']['name'];

                $targetFile = 'images/'.($_FILES['image']['name']);
                $imageType = pathinfo($targetFile,PATHINFO_EXTENSION);

                if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType && 'png') {
                  echo "<script>alert('Image must be jpg,jpeg or png')</script>";
                }
                move_uploaded_file($_FILES['image']['tmp_name'],$targetFile);

                $stmt = $pdo->prepare("INSERT INTO products(name,description,category_id,quantity,price,image)
                    VALUES (:name,:description,:category_id,:quantity,:price,:image)");
                $result = $stmt->execute(
                    array(':name'=>$name,':description'=>$description,':category_id'=>$category,':quantity'=>$quantity,':price'=>$price,':image'=>$image,)
                  );
                if ($result) {
                    echo "<script>alert('Product add successful!');window.location.href='index.php';</script>";
                }
            }
        }
    }
?>
<?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
        <form action="porduct-add.php" method="post" enctype="multipart/form-data">
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <div class="form-group">
                <label for="">Name*</label></br>
                <p style="color:red;"><?php echo empty($nameError) ? '' : $nameError;?></p>
                <input type="text" class="form-control" name="name" value="" >
            </div>
            <div class="form-group">
                <label for="">Description*</label></br>
                <p style="color:red;"><?php echo empty($descriptionError) ? '' : $descriptionError;?></p>
                <input type="text" class="form-control" name="description" value="" >
            </div>
            <div class="form-group">
                <label for="">Category*</label></br>
                <p style="color:red;"><?php echo empty($categoryError) ? '' : $categoryError;?></p>
                <?php 
                    $catStatement = $pdo->prepare("SELECT * FROM categories ORDER BY name");
                    $catStatement->execute();
                    $catResult = $catStatement->fetchAll();
                ?>
                <select class="form-control" class="" name="category">
                      <option value="">SELECT CATEGORY</option>
                      <?php foreach ($catResult as $value) { ?>
                        <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                      <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Quantity*</label></br>
                <p style="color:red;"><?php echo empty($quantityError) ? '' : $quantityError;?></p>
                <input type="number" class="form-control" name="quantity" value="" >
            </div>
            <div class="form-group">
                <label for="">Price*</label></br>
                <p style="color:red;"><?php echo empty($priceError) ? '' : $priceError;?></p>
                <input type="number" class="form-control" name="price" value="" >
            </div>
            <div class="form-group">
                <label for="">Image*</label></br>
                <p style="color:red;"><?php echo empty($imageError) ? '' : $imageError;?></p>
                <input type="file" name="image" value="" >
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="" value="Submit">
                <a class="btn btn-warning" href="index.php">Back</a>
            </div>
            </form> 
      </div>
    </div>
</div>
<?php include 'footer.php'; ?>