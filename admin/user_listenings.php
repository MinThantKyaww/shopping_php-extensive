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
    if ($_SESSION['role'] != 1) {
      header('Location: login.php');
    }
    if (!empty($_POST['search'])) {
      setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
    }else{
      if (empty($_GET['pageno'])) {
          unset($_COOKIE['search']); 
          setcookie('search', null, -1, '/'); 
      }
    }
?>

<?php include 'header.php';?>

<?php

  if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  }
  else {
    $pageno = 1;
  }
    $frames = 3;
    $offset = ($pageno - 1) * $frames;



  if (empty($_POST['search']) && !isset($_COOKIE['search'])) {
  	
    $pdostatement = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
    $pdostatement->execute();
    $rawResult = $pdostatement->fetchAll();
    $totalpages = ceil(count($rawResult)/$frames);

    $pdostatement = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$frames");
    $pdostatement->execute();
    $result = $pdostatement->fetchAll();
      
  }else {
  	
    $searchKey = empty($_POST['search']) ? $_COOKIE['search'] : $_POST['search'];
    $pdostatement = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
    $pdostatement->execute();
    $rawResult = $pdostatement->fetchAll();
    $totalpages = ceil(count($rawResult)/$frames);

    $pdostatement = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$frames");
    $pdostatement->execute();
    $result = $pdostatement->fetchAll();
      
  }
  
?>
<div class="content-wrapper">
  <div class="card-body">
    <table class="table table-bordered">
      <h1>User mangement</h1>
        <div>
            <a class="btn btn-primary"  href="user_add.php">Create new</a>
            <a style="float:right;" class="btn btn-warning" href="logout.php">Log out</a></br>
        </div></br>
        <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </thead>
        <tbody>
           <?php
           $i=1;
            if ($result) {
                foreach ($result as $value) {
            ?>
            <tr>
                <td><?php echo escape($value['name'])?></td>
                <td><?php echo escape($value['email'])?></td>
                <td><?php if ($value['role']==1){echo 'admin';}else{echo 'user';} ?></td>
                <td>
                    <a href="user_edit.php?id=<?php echo $value['id']?>" type="button" class="btn btn-warning">Edit</a>
                    <a href="user_delete.php?id=<?php echo $value['id']?>" onclick="return confirm('Are you sure you want to delete this item?');"
                      class="btn btn-danger">Delete</a>
                </td>
            </tr>


            <?php
            $i++;
                }
            }
           ?>
        </tbody>
    </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="?pageno=1">Fir</a></li>
          <li class="page-item <?php if($pageno <= 1){echo 'disabled';}?>">
            <a class="page-link" href="<?php if ($pageno <=1) {echo '#';}else {echo "?pageno=".($pageno-1);}?>">Previous</a>
          </li>
          <li class="page-item"><a class="page-link" href="#"><?php echo $pageno;?></a></li>
          <li class="page-item <?php if($pageno >= $totalpages){echo 'disabled';}?>">
            <a class="page-link" href="<?php if($pageno>=$totalpages){echo '#';}else{echo "?pageno=".($pageno+1);}?>">Next</a>
          </li>
          <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpages?>">Last</a></li>
        </ul>
      </nav>
    </div>
  </div>
</div>


  <?php include('footer.php'); ?>

