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
  if (!empty($_POST)) {
    $limit = $_POST['amount'];
    $pdostatement = $pdo->prepare("SELECT product_id,SUM(quantity)as sum,order_date FROM sale_order_details
     GROUP BY product_id HAVING SUM(quantity)>= $limit");
    $pdostatement->execute([':limit'=>$limit]);
    $result = $pdostatement->fetchAll();
  } else {
    $limit = 5;
    $pdostatement = $pdo->prepare("SELECT product_id,SUM(quantity)as sum,order_date FROM sale_order_details
     GROUP BY product_id HAVING SUM(quantity)>= $limit");
    $pdostatement->execute([':limit'=>$limit]);
    $result = $pdostatement->fetchAll();
  }
  
?>
<?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
        <h1>Best selling items</h1>
        <form action="best-selling-item.php" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
          <p>Default is 5</p>
          <label>Greater than this amount</label>
          <input type="number" name="amount">
          <input type="submit" name="Submit">
        </form>
          <table id="data-table" class="table table-bordered">
              <thead>
                  <tr>
                    <th>#Id</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Date</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $i=1;
                  if ($result) { 
                    foreach ($result as $value){?>
                    <?php
                      $userStatement = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                      $userStatement->execute();
                      $userResult = $userStatement->fetchAll();
                    ?>
                  <tr>
                      <td><?php echo $i?></td>
                      <td><?php echo escape($userResult[0]['name'])?></td>
                      <td><?php echo escape($value['sum'])?></td>
                      <td><?php echo date('Y-m-d',strtotime($value['order_date']))?></td>
                  </tr>
                  <?php
                  $i++;
                    }
                  }
                  ?>
              </tbody>
          </table>
      </div>
    </div>
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function () {
    $('#data-table').DataTable();
  });
</script>