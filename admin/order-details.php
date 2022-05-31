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
  $id = $_GET['id'];
  if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  }
  else {
    $pageno = 1;
  }
    $frames = 1;
    $offset = ($pageno - 1) * $frames;
    
    $pdostatement = $pdo->prepare("SELECT * FROM sale_order_details WHERE sale_order_id=$id");
    $pdostatement->execute();
    $rawResult = $pdostatement->fetchAll();
    $totalpages = ceil(count($rawResult)/$frames);

    $pdostatement = $pdo->prepare("SELECT * FROM sale_order_details WHERE sale_order_id=$id LIMIT $offset,$frames");
    $pdostatement->execute();
    $result = $pdostatement->fetchAll();
  
?>
  <?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
          <table class="table table-bordered">
            <h1>Orders details</h1>
              <thead>
                  <th>#Id</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Date</th>
              </thead>
              <tbody>
                 <?php
                 $i=1;
                  if ($result) {
                      foreach ($result as $value) {
                  ?>
                  <?php
                      $statement = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                      $statement->execute();
                      $userResult = $statement->fetchAll();
                  ?>
                  <tr>
                      <td><?php echo $i?></td>
                      <td><?php echo escape($userResult[0]['name'])?></td>
                      <td><?php echo escape($value['quantity'])?></td>
                      <td><?php echo date('Y-m-d',strtotime($value['order_date']))?></td>
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
          <li class="page-item"><a class="page-link" href="?id=<?php echo $_GET['id'] ?>&pageno=1">Fir</a></li>
          <li class="page-item <?php if($pageno <= 1){echo 'disabled';}?>">
            <a class="page-link" href="<?php if ($pageno <=1) {echo '#';}else {echo "?id=".$_GET['id']."&pageno=".($pageno-1);}?>">Previous</a>
          </li>
          <li class="page-item"><a class="page-link" href="#"><?php echo $pageno;?></a></li>
          <li class="page-item <?php if($pageno >= $totalpages){echo 'disabled';}?>">
            <a class="page-link" href="<?php if($pageno>=$totalpages){echo '#';}else{echo "?id=".$_GET['id']."&pageno=".($pageno+1);}?>">Next</a>
          </li>
          <li class="page-item"><a class="page-link" href="?id=<?php echo $_GET['id'] ?>&pageno=<?php echo $totalpages?>">Last</a></li>
        </ul>
      </nav>
    </div>
</div>
<?php include 'footer.php'; ?>