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
        $id = $_POST['id'];
        $updated_at= date("Y-m-d H:i:s");

         $stmt = $pdo->prepare("UPDATE categories SET name=:name,description=:description,
          updated_at=:updated_at WHERE id=$id");
        $result = $stmt->execute(
            array(':name'=>$name,':description'=>$description,':updated_at'=>$updated_at)
          );
        
        if ($result) {
          echo "<script>alert('Category update successful');window.location.href='category.php';</script>";
        }
      } 
    }

    $statement = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
    $statement->execute();
    $result = $statement->fetchAll();
  
?>
  <?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
        <form action="category-edit.php" method="post">
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
                <input type="submit" class="btn btn-primary" name="" value="Submit">
                <a class="btn btn-warning" href="category.php">Back</a>
            </div>
            </form> 
      </div>
    </div>
</div>
<?php include 'footer.php'; ?>