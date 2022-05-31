<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if ($_POST) {
$name = $_POST['name'];
$description = $_POST['description'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$id=$_POST['id'];
$updated_at= date("Y-m-d H:i:s");

  if (is_numeric($_POST['quantity']) != 1) {
    $quantityError = "Quantity must be integer";
  }else{
    $quantityError = '';
  }
  if (is_numeric($_POST['price']) != 1) {
    $priceError = "Price must be integer";
  }else{
    $priceError = '';
  }
            
  if ($quantityError == '' && $priceError == ''){
    if ($_FILES['image']['name'] != null) {

    $image=$_FILES['image']['name'];
    $targetFile = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($targetFile,PATHINFO_EXTENSION);

    if ($imageType != 'jpg' &&  $imageType != 'png' && $imageType != 'jpeg') {
      echo "Image type can't be jpg,jpeg or png!";
    }else {
      move_uploaded_file($_FILES['image']['tmp_name'],$targetFile);
    }
    if ($_POST['name'] == '' || $_POST['description'] == '' || $_POST['category'] == ''
        || $_POST['quantity'] == '' || $_POST['price'] == '') {
      echo "<script>alert('name,description,category,quantity or price cannot be empty');</script>";
    }else{
      $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,
                              price=:price,quantity=:quantity,image=:image,updated_at=:updated_at WHERE id=$id");

      $result = $stmt->execute(
            array(':name'=>$name,':description'=>$description,':category'=>$category,':price'=>$price,':quantity'=>$quantity,':image'=>$image,':updated_at'=>$updated_at)
        );

      if ($result) {
          echo "<script>alert('Product is updated');window.location.href='index.php';</script>";
      }
    }
  }else{
      $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,
                                price=:price,quantity=:quantity,updated_at=:updated_at WHERE id=$id");

      $result = $stmt->execute(
              array(':name'=>$name,':description'=>$description,':category'=>$category,':price'=>$price,
                ':quantity'=>$quantity,':updated_at'=>$updated_at)
          );

      if ($result) {
            echo "<script>alert('Product is updated without changing photo');window.location.href='index.php';</script>";
      }
  }
  }
}

  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetchAll();
 
?>
  <?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data"> 
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?php echo escape($result[0]['id'])?>">
            <div class="form-group">
                <label for="username">Name*</label></br>
                <p style="color:red;"><?php echo empty($nameError) ? '' : $nameError;?></p>
                <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name'])?>" >
            </div>
            <div class="form-group">
                <label for="username">Description*</label></br>
                <p style="color:red;"><?php echo empty($descriptionError) ? '' : $descriptionError;?></p>
                <input type="text" class="form-control" name="description" value="<?php echo escape($result[0]['description'])?>" >
            </div>
            <div class="form-group">
                    <?php
                      $catStmt = $pdo->prepare("SELECT * FROM categories");
                      $catStmt->execute();
                      $catResult = $catStmt->fetchAll();
                    ?>
                    <label for="">Category</label><p style="color:red"><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
                    <select class="form-control" class="" name="category">
                      <option value="">SELECT CATEGORY</option>
                      <?php foreach ($catResult as $value) {?>
                        <?php if($value['id'] == $result[0]['category_id']): ?>
                          <option value="<?php echo $value['id']?>" selected><?php echo $value['name']?></option>
                        <?php else: ?>
                          <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                        <?php endif ?>
                      <?php } ?>
                    </select>
            </div>
            <div class="form-group">
                <label for="">Quantity*</label></br>
                <p style="color:red;"><?php echo empty($quantityError) ? '' : $quantityError;?></p>
                <input type="number" class="form-control" name="quantity" value="<?php echo escape($result[0]['quantity'])?>" >
            </div>
            <div class="form-group">
                <label for="">Price*</label></br>
                <p style="color:red;"><?php echo empty($priceError) ? '' : $priceError;?></p>
                <input type="number" class="form-control" name="price" value="<?php echo escape($result[0]['price'])?>" >
            </div>
            <div class="form-group">
                <label for="">Image*</label></br>
                <img src="images/<?php echo $result[0]['image']?>" style="float:right"  width="100px" height="auto"  alt="">
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