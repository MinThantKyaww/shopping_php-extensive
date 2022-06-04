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

  if (!empty($_POST)) {
    if (empty($_POST['name']) || empty($_POST['description'])) {
      if (empty($_POST['name'])) {
        $nameError = "Category name is required";
      }
      if (empty($_POST['description'])) {
        $descriptionError = "Description is required";
      }
    }else{
      $name = $_POST['name'];
      $description = $_POST['description'];

      $stmt = $pdo->prepare("INSERT INTO categories (name,description) VALUES (:name,:description)");
      $result = $stmt->execute(
          array(':name'=>$name,':description'=>$description)
        );
      
      if ($result) {
        echo "<script>alert('Category add successful');window.location.href='category.php';</script>";
      }
    } 
  }
?>
<?php include 'header.php'; ?>
<div class="content-wrapper">
  <div class="card-body">
    <form action="category-add.php" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <div class="form-group">
            <label for="username">Name*</label></br>
            <p style="color:red;"><?php echo empty($nameError) ? '' : $nameError;?></p>
            <input type="text" class="form-control" name="name" value="" >
        </div>
        <div class="form-group">
            <label for="username">Description*</label></br>
            <p style="color:red;"><?php echo empty($descriptionError) ? '' : $descriptionError;?></p>
            <input type="text" class="form-control" name="description" value="" >
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="" value="Submit">
            <a class="btn btn-warning" href="index.php">Back</a>
        </div>
    </form> 
  </div>
</div>
<?php include 'footer.php'; ?>