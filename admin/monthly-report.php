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
    $month = date("m",strtotime($_POST['month']));
    $pdostatement = $pdo->prepare("SELECT * FROM sale_orders WHERE MONTH(order_date)=:month");
    $pdostatement->execute([':month'=>$month]);
    $result = $pdostatement->fetchAll();
  }else{
    $currentDate = date("Y-m-d");
    $fromDate = date("Y-m-d",strtotime($currentDate . '+1 day'));
    $toDate = date("Y-m-d",strtotime($currentDate . '-1 month'));

    $pdostatement = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<:fromDate AND order_date>=:toDate 
      ORDER BY  order_date");
    $pdostatement->execute([':fromDate'=>$fromDate,':toDate'=>$toDate]);
    $result = $pdostatement->fetchAll();
  }
?>
<?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="card-body">
        <h1>Monthly report</h1>
        <form action="monthly-report.php" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
          <label>Entermonth</label>
          <input type="month" name="month">
          <input type="submit" name="Submit">
        </form>
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