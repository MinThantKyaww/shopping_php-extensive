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
?>

<?php
  if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  }
  else {
    $pageno = 1;
  }
    $frames = 5;
    $offset = ($pageno - 1) * $frames;

    $pdostatement = $pdo->prepare("SELECT * FROM sale_orders ORDER BY order_date LIMIT $offset,$frames");
    $pdostatement->execute();
    $result = $pdostatement->fetchAll();
  
?>
  <?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
          <table class="table table-bordered">
            <h1>Orders</h1>
              <div>
                  <a class="btn btn-primary"  href="category-add.php">Create new</a>
                  <a style="float:right;" class="btn btn-warning" href="logout.php">Log out</a></br>
              </div></br>
              <thead>
                  <th>#Id</th>
                  <th>User</th>
                  <th>Total-price</th>
                  <th>Date</th>
                  <th>Action</th>
              </thead>
              <tbody>
                 <?php
                 $i=1;
                  if ($result) {
                      foreach ($result as $value) {
                  ?>
                  <?php
                      $statement = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                      $statement->execute();
                      $userResult = $statement->fetchAll();
                  ?>
                  <tr>
                      <td><?php echo $i?></td>
                      <td><?php echo escape($userResult[0]['name'])?></td>
                      <td><?php echo escape($value['total_price'])?></td>
                      <td><?php echo date('Y-m-d',strtotime($value['order_date']))?></td>
                      <td>
                          <a href="order-details.php?id=<?php echo $value['id']?>" type="button" class="btn btn-warning">View</a>
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
<?php include 'footer.php'; ?>