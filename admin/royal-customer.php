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
  $limit = 300000;
  $pdostatement = $pdo->prepare("SELECT * FROM sale_orders WHERE total_price>:limit 
    ORDER BY  total_price desc");
  $pdostatement->execute([':limit'=>$limit]);
  $result = $pdostatement->fetchAll();
?>
<?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
        <h1>Royal customers</h1>
          <table id="data-table" class="table table-bordered">
              <thead>
                  <tr>
                    <th>#Id</th>
                    <th>Name</th>
                    <th>Total-price</th>
                    <th>Date</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $i=1;
                  if ($result) { 
                    foreach ($result as $value){?>
                    <?php
                      $userStatement = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                      $userStatement->execute();
                      $userResult = $userStatement->fetchAll();
                    ?>
                  <tr>
                      <td><?php echo $i?></td>
                      <td><?php echo escape($userResult[0]['name'])?></td>
                      <td><?php echo escape($value['total_price'])?></td>
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